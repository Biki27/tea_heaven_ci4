<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CartModel;

class Profile extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();
        $user      = $userModel->find(session()->get('user_id'));

        return view('profile/index', [
            'user'      => $user,
            'cartCount' => (new CartModel())->getCount(),
        ]);
    }

    public function update()
    {
        $rules = [
            'name'    => 'required|min_length[2]|max_length[120]',
            'phone'   => 'permit_empty|min_length[10]',
            'address' => 'permit_empty|max_length[300]',
            'city'    => 'permit_empty|max_length[100]',
            'pincode' => 'permit_empty|min_length[6]|max_length[12]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $userModel->update(session()->get('user_id'), [
            'name'    => $this->request->getPost('name'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'city'    => $this->request->getPost('city'),
            'pincode' => $this->request->getPost('pincode'),
            'country' => $this->request->getPost('country') ?? 'India',
        ]);

        // Refresh session name
        session()->set('user_name', $this->request->getPost('name'));

        return redirect()->to('profile')->with('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $user      = $userModel->find(session()->get('user_id'));

        if (! isset($user['password']) || ! password_verify(
            $this->request->getPost('current_password'),
            (string) $user['password']
        )) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $userModel->update($user['id'], [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT),
        ]);

        return redirect()->to('profile')->with('success', 'Password changed successfully!');
    }
}
