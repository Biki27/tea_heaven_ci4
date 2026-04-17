<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CartModel;

class Home extends BaseController
{
    public function index(): string
    {
        $productModel = new ProductModel();
        $cartModel    = new CartModel();

        $data = [
            'newArrivals'  => $productModel->getActive(['sort' => 'new'], 4),
            'bestSellers'  => $productModel->getActive([], 8),
            'cartCount'    => $cartModel->getCount(),
        ];

        return view('home/index', $data);
    }
}
