<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ProductModel;

class Cart extends BaseController
{
    private CartModel $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
    }

    /** Cart page (shop.html equivalent) */
    public function index(): string
    {
        $items = $this->cartModel->getItems();
        [$subtotal, $tax, $total] = $this->_totals($items);

        return view('cart/index', [
            'items'     => $items,
            'subtotal'  => $subtotal,
            'tax'       => $tax,
            'total'     => $total,
            'cartCount' => $this->cartModel->getCount(),
        ]);
    }

    /** POST /cart/add — AJAX */
    public function add()
    {
        $productId = (int) $this->request->getPost('product_id');
        $qty       = (int) ($this->request->getPost('qty') ?? 1);

        if ($productId <= 0 || $qty <= 0) {
            return $this->jsonError('Invalid product.');
        }

        $product = (new ProductModel())->find($productId);
        if (! $product || ! $product['is_active']) {
            return $this->jsonError('Product not found.');
        }

        if ($product['stock'] < $qty) {
            return $this->jsonError('Insufficient stock.');
        }

        $this->cartModel->addItem($productId, $qty);

        return $this->response->setJSON([
            'status'     => 'ok',
            'message'    => 'Added to cart!',
            'cart_count' => $this->cartModel->getCount(),
        ]);
    }

    /** POST /cart/update — AJAX */
    public function update()
    {
        $cartId = (int) $this->request->getPost('cart_id');
        $qty    = (int) $this->request->getPost('qty');

        $this->cartModel->updateItem($cartId, $qty);

        $items = $this->cartModel->getItems();
        [$subtotal, $tax, $total] = $this->_totals($items);

        return $this->response->setJSON([
            'status'     => 'ok',
            'cart_count' => $this->cartModel->getCount(),
            'subtotal'   => number_format($subtotal, 2),
            'tax'        => number_format($tax, 2),
            'total'      => number_format($total, 2),
        ]);
    }

    /** POST /cart/remove — AJAX */
    public function remove()
    {
        $cartId = (int) $this->request->getPost('cart_id');
        $this->cartModel->removeItem($cartId);

        $items = $this->cartModel->getItems();
        [$subtotal, $tax, $total] = $this->_totals($items);

        return $this->response->setJSON([
            'status'     => 'ok',
            'cart_count' => $this->cartModel->getCount(),
            'subtotal'   => number_format($subtotal, 2),
            'tax'        => number_format($tax, 2),
            'total'      => number_format($total, 2),
        ]);
    }

    /** GET /cart/count — quick badge update */
    public function count()
    {
        return $this->response->setJSON(['count' => $this->cartModel->getCount()]);
    }

    /** POST /cart/promo — validate promo code */
    public function applyPromo()
    {
        $code  = strtoupper(trim($this->request->getPost('code') ?? ''));
        $total = (float) $this->request->getPost('total');

        if (! $code) {
            return $this->jsonError('Enter a promo code.');
        }

        $db    = \Config\Database::connect();
        $promo = $db->table('promo_codes')
            ->where('code', $code)
            ->where('is_active', 1)
            ->get()->getRowArray();

        if (! $promo) {
            return $this->jsonError('Invalid promo code.');
        }
        if ($promo['expires_at'] && strtotime($promo['expires_at']) < time()) {
            return $this->jsonError('This promo code has expired.');
        }
        if ($promo['max_uses'] && $promo['used_count'] >= $promo['max_uses']) {
            return $this->jsonError('This promo code has reached its usage limit.');
        }
        if ($total < $promo['min_order']) {
            return $this->jsonError('Minimum order ₹' . number_format($promo['min_order'], 0) . ' required for this code.');
        }

        $discount = $promo['discount_type'] === 'percent'
            ? round($total * $promo['discount_value'] / 100, 2)
            : $promo['discount_value'];

        session()->set('promo_code', $code);
        session()->set('promo_discount', $discount);

        return $this->response->setJSON([
            'status'   => 'ok',
            'message'  => 'Promo applied! You save ₹' . number_format($discount, 2),
            'discount' => number_format($discount, 2),
            'new_total'=> number_format(max(0, $total - $discount), 2),
        ]);
    }

    // ── Private ───────────────────────────────────────────────

    private function _totals(array $items): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $price     = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            $subtotal += $price * $item['quantity'];
        }
        $tax   = round($subtotal * 0.05, 2);   // 5 % GST
        $total = $subtotal + $tax + 50;          // flat ₹50 shipping

        return [$subtotal, $tax, $total];
    }

    private function jsonError(string $msg): \CodeIgniter\HTTP\Response
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $msg]);
    }
}
