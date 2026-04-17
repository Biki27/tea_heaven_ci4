<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ProductModel;
use App\Models\UserModel;

class Order extends BaseController
{
    /** GET /checkout — shows checkout form (payment.html equivalent) */
    public function checkout(): string
    {
        $cartModel = new CartModel();
        $items     = $cartModel->getItems();

        if (empty($items)) {
            return redirect()->to('cart')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $price     = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        $tax      = round($subtotal * 0.05, 2);
        $shipping = 50.00;
        $discount = (float) (session()->get('promo_discount') ?? 0);
        $total    = max(0, $subtotal + $tax + $shipping - $discount);

        // Pre-fill address from user profile
        $user = (new UserModel())->find(session()->get('user_id'));

        return view('checkout/index', [
            'items'     => $items,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'shipping'  => $shipping,
            'discount'  => $discount,
            'total'     => $total,
            'user'      => $user,
            'cartCount' => $cartModel->getCount(),
            'razorpay_key' => getenv('RAZORPAY_KEY_ID') ?: env('RAZORPAY_KEY_ID'),
        ]);
    }

    /** POST /order/place — COD / UPI net banking (non-Razorpay) */
    public function place()
    {
        $cartModel = new CartModel();
        $items     = $cartModel->getItems();

        if (empty($items)) {
            return redirect()->to('cart')->with('error', 'Cart is empty.');
        }

        // Validate contact + shipping fields
        $rules = [
            'first_name'     => 'required|min_length[2]',
            'last_name'      => 'required|min_length[2]',
            'email'          => 'required|valid_email',
            'phone'          => 'required|min_length[10]',
            'address'        => 'required|min_length[5]',
            'city'           => 'required',
            'pincode'        => 'required|min_length[6]',
            'payment_method' => 'required|in_list[cod,upi]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        [$subtotal, $tax, $shipping, $discount, $total] = $this->_calculateTotals($items);

        $orderModel     = new OrderModel();
        $orderItemModel = new OrderItemModel();

        $orderId = $orderModel->insert([
            'user_id'        => session()->get('user_id'),
            'order_number'   => $orderModel->generateOrderNumber(),
            'status'         => 'confirmed',
            'payment_status' => $this->request->getPost('payment_method') === 'cod' ? 'pending' : 'pending',
            'payment_method' => $this->request->getPost('payment_method'),
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'shipping'       => $shipping,
            'discount'       => $discount,
            'total'          => $total,
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'email'          => $this->request->getPost('email'),
            'phone'          => $this->request->getPost('phone'),
            'address'        => $this->request->getPost('address'),
            'city'           => $this->request->getPost('city'),
            'pincode'        => $this->request->getPost('pincode'),
            'country'        => $this->request->getPost('country') ?? 'India',
            'notes'          => $this->request->getPost('notes'),
        ]);

        $this->_saveOrderItems($orderItemModel, (int) $orderId, $items);
        $cartModel->clearCart();
        session()->remove('promo_code');
        session()->remove('promo_discount');

        return redirect()->to('orders/' . $orderId)->with('success', 'Order placed successfully!');
    }

    /** GET /orders — order history */
    public function history(): string
    {
        $orderModel = new OrderModel();
        $orders     = $orderModel->getForUser((int) session()->get('user_id'));

        return view('orders/history', [
            'orders'    => $orders,
            'cartCount' => (new CartModel())->getCount(),
        ]);
    }

    /** GET /orders/:id — order detail */
    public function detail(int $id): string
    {
        $orderModel     = new OrderModel();
        $orderItemModel = new OrderItemModel();

        $order = $orderModel->getForUserById($id, (int) session()->get('user_id'));
        if (! $order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found.');
        }

        $items = $orderItemModel->getByOrderId($id);

        return view('orders/detail', [
            'order'     => $order,
            'items'     => $items,
            'cartCount' => (new CartModel())->getCount(),
        ]);
    }

    /** GET /orders/:id/invoice — printable invoice */
    public function invoice(int $id): string
    {
        $orderModel     = new OrderModel();
        $orderItemModel = new OrderItemModel();

        $order = $orderModel->getForUserById($id, (int) session()->get('user_id'));
        if (! $order) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Order not found.');
        }

        return view('orders/invoice', [
            'order' => $order,
            'items' => $orderItemModel->getByOrderId($id),
        ]);
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
            $price    = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
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
