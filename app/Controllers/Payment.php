<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\UserModel;
use Razorpay\Api\Api;

class Payment extends BaseController
{
    private Api $razorpay;

    public function __construct()
    {
        $keyId = getenv('RAZORPAY_KEY_ID') ?: env('RAZORPAY_KEY_ID');
        $keySecret = getenv('RAZORPAY_KEY_SECRET') ?: env('RAZORPAY_KEY_SECRET');
        $this->razorpay = new Api($keyId, $keySecret);
    }

    /**
     * POST /payment/create-order
     * Called via AJAX from checkout page.
     * Creates a Razorpay Order and returns the order_id to the frontend.
     */
    public function createOrder()
    {
        $cartModel = new CartModel();
        $items     = $cartModel->getItems();

        if (empty($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart is empty.']);
        }

        // Validate shipping fields sent via JSON/POST
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        [$subtotal, $tax, $shipping, $discount, $total] = $this->_calculateTotals($items);

        try {
            // Razorpay amounts are in paise (1 INR = 100 paise)
            $rzpOrder = $this->razorpay->order->create([
                'amount'          => (int) round($total * 100),
                'currency'        => 'INR',
                'receipt'         => 'TH' . time(),
                'payment_capture' => 1,
            ]);

            // Persist a pending order row so we can link the payment later
            $orderModel = new OrderModel();
            $orderId    = $orderModel->insert([
                'user_id'           => session()->get('user_id'),
                'order_number'      => $orderModel->generateOrderNumber(),
                'status'            => 'pending',
                'payment_status'    => 'pending',
                'payment_method'    => 'razorpay',
                'razorpay_order_id' => $rzpOrder['id'],
                'subtotal'          => $subtotal,
                'tax'               => $tax,
                'shipping'          => $shipping,
                'discount'          => $discount,
                'total'             => $total,
                'first_name'        => $data['first_name'] ?? '',
                'last_name'         => $data['last_name']  ?? '',
                'email'             => $data['email']      ?? session()->get('user_email'),
                'phone'             => $data['phone']      ?? '',
                'address'           => $data['address']    ?? '',
                'city'              => $data['city']       ?? '',
                'pincode'           => $data['pincode']    ?? '',
                'country'           => $data['country']    ?? 'India',
                'notes'             => $data['notes']      ?? null,
            ]);

            $orderItemModel = new OrderItemModel();
            $this->_saveOrderItems($orderItemModel, (int) $orderId, $items);

            // Store pending order id in session for verify step
            session()->set('pending_order_id', $orderId);

            return $this->response->setJSON([
                'status'           => 'ok',
                'razorpay_order_id'=> $rzpOrder['id'],
                'amount'           => (int) round($total * 100),
                'currency'         => 'INR',
                'order_id'         => $orderId,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Razorpay create order failed: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Payment gateway error. Try again.']);
        }
    }

    /**
     * POST /payment/verify
     * Called after Razorpay checkout modal succeeds.
     * Verifies signature and marks order as paid.
     */
    public function verify()
    {
        $data = $this->request->getPost();

        $razorpayOrderId   = $data['razorpay_order_id']   ?? '';
        $razorpayPaymentId = $data['razorpay_payment_id'] ?? '';
        $razorpaySignature = $data['razorpay_signature']  ?? '';
        $internalOrderId   = (int) ($data['order_id'] ?? session()->get('pending_order_id'));

        try {
            $this->razorpay->utility->verifyPaymentSignature([
                'razorpay_order_id'   => $razorpayOrderId,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature'  => $razorpaySignature,
            ]);

            // Signature valid — update order
            $orderModel = new OrderModel();
            $orderModel->update($internalOrderId, [
                'payment_status'     => 'paid',
                'status'             => 'confirmed',
                'razorpay_payment_id'=> $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
            ]);

            (new CartModel())->clearCart();
            session()->remove('promo_code');
            session()->remove('promo_discount');
            session()->remove('pending_order_id');

            return redirect()->to('orders/' . $internalOrderId)
                ->with('success', 'Payment successful! Your order has been confirmed.');
        } catch (\Exception $e) {
            // Signature mismatch — possible tampering
            log_message('error', 'Razorpay signature verify failed: ' . $e->getMessage());
            $orderModel = new OrderModel();
            $orderModel->update($internalOrderId, ['payment_status' => 'failed']);
            return redirect()->to('payment/failed');
        }
    }

    /** GET /payment/success */
    public function success(): string
    {
        return view('payment/success', ['cartCount' => (new CartModel())->getCount()]);
    }

    /** GET /payment/failed */
    public function failed(): string
    {
        return view('payment/failed', ['cartCount' => (new CartModel())->getCount()]);
    }

    /**
     * POST /payment/webhook
     * Razorpay server-to-server webhook (configure in Razorpay Dashboard).
     * Header: X-Razorpay-Signature
     */
    public function webhook()
    {
        $secret = getenv('RAZORPAY_KEY_SECRET') ?: env('RAZORPAY_KEY_SECRET');
        $body      = $this->request->getBody();
        $signature = $this->request->getHeaderLine('X-Razorpay-Signature');

        try {
            $this->razorpay->utility->verifyWebhookSignature($body, $signature, $secret);
        } catch (\Exception $e) {
            log_message('error', 'Webhook signature invalid: ' . $e->getMessage());
            return $this->response->setStatusCode(400)->setBody('Invalid signature');
        }

        $event   = json_decode($body, true);
        $eventId = $event['event'] ?? '';

        if ($eventId === 'payment.captured') {
            $paymentId = $event['payload']['payment']['entity']['id']     ?? '';
            $orderId   = $event['payload']['payment']['entity']['order_id'] ?? '';

            $orderModel = new OrderModel();
            $order      = $orderModel->where('razorpay_order_id', $orderId)->first();
            if ($order) {
                $orderModel->update($order['id'], [
                    'payment_status'     => 'paid',
                    'status'             => 'confirmed',
                    'razorpay_payment_id'=> $paymentId,
                ]);
            }
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }

    // ── Private helpers ───────────────────────────────────────

    private function _calculateTotals(array $items): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $price     = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        $tax      = round($subtotal * 0.05, 2);
        $shipping = 50.00;
        $discount = (float) (session()->get('promo_discount') ?? 0);
        $total    = max(0, $subtotal + $tax + $shipping - $discount);

        return [$subtotal, $tax, $shipping, $discount, $total];
    }

    private function _saveOrderItems(OrderItemModel $model, int $orderId, array $cartItems): void
    {
        foreach ($cartItems as $item) {
            $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            $model->insert([
                'order_id'      => $orderId,
                'product_id'    => $item['product_id'],
                'product_name'  => $item['name'],
                'product_image' => $item['image'],
                'price'         => $price,
                'quantity'      => $item['quantity'],
                'subtotal'      => $price * $item['quantity'],
            ]);
        }
    }
}
