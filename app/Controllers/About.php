<?php

namespace App\Controllers;

use App\Models\CartModel;

class About extends BaseController
{
    public function index()
    {
        $cartModel = new CartModel();
        $cartCount = $cartModel->getCount();

        return view('about/index', [
            'cartCount' => $cartCount,
            'pageTitle' => 'About Us — Tea Haven',
        ]);
    }
}
