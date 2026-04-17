<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table      = 'cart';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'session_id', 'product_id', 'quantity',
    ];

    // ── Key helpers ───────────────────────────────────────────

    /**
     * Build a WHERE condition for the current identity
     * (logged-in user_id OR guest session_id).
     */
    private function identityWhere(?\CodeIgniter\Database\BaseBuilder $builder = null): array
    {
        $userId    = session()->get('user_id');
        $sessionId = session_id();

        return $userId
            ? ['user_id' => $userId]
            : ['session_id' => $sessionId, 'user_id' => null];
    }

    /**
     * All cart rows with joined product info for the current identity.
     */
    public function getItems(): array
    {
        $userId    = session()->get('user_id');
        $sessionId = session_id();

        $builder = $this->db->table('cart c')
            ->select('c.id, c.quantity, p.id AS product_id, p.name, p.image,
                      p.price, p.sale_price, p.stock')
            ->join('products p', 'p.id = c.product_id');

        if ($userId) {
            $builder->where('c.user_id', $userId);
        } else {
            $builder->where('c.session_id', $sessionId)
                    ->where('c.user_id', null);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Add a product or increment its quantity.
     */
    public function addItem(int $productId, int $qty = 1): void
    {
        $userId    = session()->get('user_id');
        $sessionId = session_id();

        // Search for existing cart item
        if ($userId) {
            $existing = $this->where('product_id', $productId)
                ->where('user_id', $userId)
                ->first();
        } else {
            $existing = $this->where('product_id', $productId)
                ->where('session_id', $sessionId)
                ->where('user_id', null)
                ->first();
        }

        if ($existing) {
            $this->update($existing['id'], ['quantity' => $existing['quantity'] + $qty]);
        } else {
            $this->insert([
                'user_id'    => $userId,
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity'   => $qty,
            ]);
        }
    }

    /**
     * Update a specific cart row quantity.
     * Removes the row if qty ≤ 0.
     */
    public function updateItem(int $cartId, int $qty): void
    {
        if ($qty <= 0) {
            $this->removeItem($cartId);
            return;
        }
        $this->update($cartId, ['quantity' => $qty]);
    }

    /**
     * Remove a specific cart row.
     */
    public function removeItem(int $cartId): void
    {
        $this->delete($cartId);
    }

    /**
     * Clear all cart rows for the current identity.
     */
    public function clearCart(): void
    {
        $userId    = session()->get('user_id');
        $sessionId = session_id();

        if ($userId) {
            $this->where('user_id', $userId)->delete();
        } else {
            $this->where('session_id', $sessionId)->where('user_id', null)->delete();
        }
    }

    /**
     * Merge guest session cart into user cart after login.
     */
    public function mergeSessionCart(int $userId, string $sessionId): void
    {
        $guestItems = $this->where('session_id', $sessionId)
                           ->where('user_id', null)
                           ->findAll();

        foreach ($guestItems as $item) {
            $existing = $this->where('user_id', $userId)
                             ->where('product_id', $item['product_id'])
                             ->first();
            if ($existing) {
                $this->update($existing['id'], [
                    'quantity' => $existing['quantity'] + $item['quantity'],
                ]);
                $this->delete($item['id']);
            } else {
                $this->update($item['id'], [
                    'user_id'    => $userId,
                    'session_id' => null,
                ]);
            }
        }
    }

    /**
     * Total item count (sum of quantities) for the current identity.
     */
    public function getCount(): int
    {
        $items = $this->getItems();
        return array_sum(array_column($items, 'quantity'));
    }
}
