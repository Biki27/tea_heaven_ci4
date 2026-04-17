<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table      = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'stock', 'image', 'badge', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $updatedField  = '';   // no updated_at column on products

    // ── Helpers ───────────────────────────────────────────────

    /** Active products with their category name. */
    public function getActive(array $filters = [], int $limit = 0, int $offset = 0): array
    {
        $builder = $this->db->table('products p')
            ->select('p.*, c.name AS category_name')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.is_active', 1);

        if (! empty($filters['category'])) {
            $builder->where('c.slug', $filters['category']);
        }
        if (! empty($filters['price_max'])) {
            $builder->where('p.price <=', (float) $filters['price_max']);
        }
        if (! empty($filters['price_min'])) {
            $builder->where('p.price >=', (float) $filters['price_min']);
        }
        if (! empty($filters['sort'])) {
            match ($filters['sort']) {
                'price-asc'  => $builder->orderBy('p.price', 'ASC'),
                'price-desc' => $builder->orderBy('p.price', 'DESC'),
                'new'        => $builder->orderBy('p.id', 'DESC'),
                default      => $builder->orderBy('p.id', 'ASC'),
            };
        }

        if ($limit > 0) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    public function countActive(array $filters = []): int
    {
        $builder = $this->db->table('products p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.is_active', 1);

        if (! empty($filters['category'])) {
            $builder->where('c.slug', $filters['category']);
        }

        return (int) $builder->countAllResults();
    }

    public function findBySlug(string $slug): ?array
    {
        return $this->db->table('products p')
            ->select('p.*, c.name AS category_name')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('p.slug', $slug)
            ->where('p.is_active', 1)
            ->get()->getRowArray();
    }

    /** Effective price — sale_price when set, otherwise price. */
    public static function effectivePrice(array $product): float
    {
        return isset($product['sale_price']) && $product['sale_price'] > 0
            ? (float) $product['sale_price']
            : (float) $product['price'];
    }
}
