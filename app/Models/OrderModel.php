<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table      = 'orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'order_number', 'status', 'payment_status',
        'payment_method', 'razorpay_order_id', 'razorpay_payment_id', 'razorpay_signature',
        'subtotal', 'tax', 'shipping', 'discount', 'total',
        'first_name', 'last_name', 'email', 'phone',
        'address', 'city', 'pincode', 'country', 'notes',
    ];

    public function generateOrderNumber(): string
    {
        return 'TH' . strtoupper(substr(uniqid(), -8));
    }

    /** Orders for a specific user, newest first. */
    public function getForUser(int $userId): array
    {
        return $this->where('user_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /** Single order with ownership check. */
    public function getForUserById(int $orderId, int $userId): ?array
    {
        return $this->where('id', $orderId)
                    ->where('user_id', $userId)
                    ->first();
    }
}
