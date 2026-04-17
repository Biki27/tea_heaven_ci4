<?php

namespace App\Controllers;

use App\Models\CartModel;

class Contact extends BaseController
{
    public function index()
    {
        $cartModel = new CartModel();
        $cartCount = $cartModel->getCount();

        return view('contact/index', [
            'cartCount' => $cartCount,
            'pageTitle' => 'Contact Us — Tea Haven',
        ]);
    }

    public function send()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'name'    => 'required|min_length[3]',
            'email'   => 'required|valid_email',
            'subject' => 'required|min_length[5]',
            'message' => 'required|min_length[10]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return response()->setJSON([
                'success' => false,
                'errors'  => $validation->getErrors(),
            ])->setStatusCode(400);
        }

        // TODO: Send email or save to database
        // For now, just return success
        return response()->setJSON([
            'success' => true,
            'message' => 'Message sent successfully! We\'ll get back to you soon.',
        ]);
    }
}
