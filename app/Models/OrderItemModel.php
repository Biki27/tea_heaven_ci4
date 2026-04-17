<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table      = 'order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'order_id', 'product_id', 'product_name', 'product_image',
        'price', 'quantity', 'subtotal',
    ];

    public function getByOrderId(int $orderId): array
    {
        return $this->where('order_id', $orderId)->findAll();
    }
}
