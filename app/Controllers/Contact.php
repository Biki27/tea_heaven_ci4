<?php

namespace App\Controllers;

use App\Models\CartModel;
use App\Models\ContactMessageModel;

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

        // Save to database
        $messageModel = new ContactMessageModel();
        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
            'status'  => 'pending',
        ];

        if ($messageModel->insert($data)) {
            return response()->setJSON([
                'success' => true,
                'message' => 'Message sent successfully! We\'ll get back to you soon.',
            ]);
        } else {
            return response()->setJSON([
                'success' => false,
                'message' => 'Failed to send message. Please try again later.',
            ])->setStatusCode(500);
        }
    }
}
