<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CartModel;

class Products extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        $cartModel    = new CartModel();

        // Read filter params
        $filters = [
            'category'  => $this->request->getGet('category') ?? '',
            'price_min' => $this->request->getGet('price_min') ?? '',
            'price_max' => $this->request->getGet('price_max') ?? '',
            'sort'      => $this->request->getGet('sort') ?? 'popular',
        ];

        $perPage  = 12;
        $page     = (int) ($this->request->getGet('page') ?? 1);
        $offset   = ($page - 1) * $perPage;
        $total    = $productModel->countActive($filters);
        $products = $productModel->getActive($filters, $perPage, $offset);

        // Fetch categories for sidebar
        $categories = $this->db->table('categories')
            ->where('is_active', 1)
            ->get()->getResultArray();

        return view('products/index', [
            'products'   => $products,
            'categories' => $categories,
            'filters'    => $filters,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'cartCount'  => $cartModel->getCount(),
        ]);
    }

    public function show(string $slug): string
    {
        $productModel = new ProductModel();
        $product      = $productModel->findBySlug($slug);

        if (! $product) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Product not found: $slug");
        }

        $related = $productModel->getActive(['category' => null], 4);

        return view('products/detail', [
            'product'   => $product,
            'related'   => $related,
            'cartCount' => (new CartModel())->getCount(),
        ]);
    }

    // ── Inject DB builder via property ───────────────────────
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
}
