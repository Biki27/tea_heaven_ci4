<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CartModel;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\Facebook;

class Auth extends BaseController
{
 
    public function loginPage()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        return view('auth/login', ['cartCount' => (new CartModel())->getCount()]);
    }

    public function login()
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $user      = $userModel->findByEmail($this->request->getPost('email'));

        if (! $user || ! password_verify($this->request->getPost('password'), $user['password'] ?? '')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        if (! $user['is_active']) {
            return redirect()->back()->with('error', 'Your account is disabled.');
        }

        $this->_createSession($user);

        // Merge guest cart
        (new CartModel())->mergeSessionCart($user['id'],session_id());

        return redirect()->to(session()->get('redirect_after_login') ?? '/');
    }

    // ── Register ──────────────────────────────────────────────

    public function registerPage(): string
    {
        return view('auth/login', ['mode' => 'register', 'cartCount' => (new CartModel())->getCount()]);
    }

    public function register()
    {
        $rules = [
            'name'     => 'required|min_length[2]|max_length[120]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('register_error', implode('<br>', $this->validator->getErrors()));
        }

        $userModel = new UserModel();
        $id = $userModel->insert([
            'name'      => $this->request->getPost('name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'is_active' => 1,
        ]);

        $user = $userModel->find($id);
        $this->_createSession($user);
        (new CartModel())->mergeSessionCart($user['id'],session_id());

        return redirect()->to('/')->with('success', 'Welcome to Tea Haven!');
    }

    // ── Logout ────────────────────────────────────────────────

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out.');
    }

    // ── Forgot password (basic flow) ─────────────────────────

    public function forgotPage(): string
    {
        return view('auth/forgot', ['cartCount' => (new CartModel())->getCount()]);
    }
    // pending
    public function forgot()
    {
        // In production: generate token, email reset link.
        // For now return a generic message.

        return redirect()->back()->with('success', 'If that email exists, a reset link has been sent.');
    }

    // ── Google OAuth ──────────────────────────────────────────

    public function googleRedirect()
    {
        $provider = $this->_googleProvider();
        $authUrl  = $provider->getAuthorizationUrl(['scope' => ['email', 'profile']]);
        session()->set('oauth2state_google', $provider->getState());
        return redirect()->to($authUrl);
    }

    public function googleCallback()
    {
        $provider = $this->_googleProvider();
        $state    = $this->request->getGet('state');
        $code     = $this->request->getGet('code');

        if (! $state || $state !== session()->get('oauth2state_google')) {
            session()->remove('oauth2state_google');
            return redirect()->to('auth/login')->with('error', 'Invalid OAuth state.');
        }

        try {
            $token        = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $googleUser   = $provider->getResourceOwner($token);
            $googleArray  = $googleUser->toArray();

            $userModel = new UserModel();
            $user      = $userModel->findOrCreateFromGoogle([
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'avatar'    => $googleUser->getAvatar(),
            ]);

            $this->_createSession($user);
            (new CartModel())->mergeSessionCart($user['id'],session_id());

            return redirect()->to('/')->with('success', 'Welcome, ' . $user['name'] . '!');
        } catch (\Exception $e) {
            return redirect()->to('auth/login')->with('error', 'Google login failed: ' . $e->getMessage());
        }
    }

    // ── Facebook OAuth ────────────────────────────────────────

    public function facebookRedirect()
    {
        $provider = $this->_facebookProvider();
        $authUrl  = $provider->getAuthorizationUrl(['scope' => ['email', 'public_profile']]);
        session()->set('oauth2state_facebook', $provider->getState());
        return redirect()->to($authUrl);
    }

    public function facebookCallback()
    {
        $provider = $this->_facebookProvider();
        $state    = $this->request->getGet('state');
        $code     = $this->request->getGet('code');

        if (! $state || $state !== session()->get('oauth2state_facebook')) {
            session()->remove('oauth2state_facebook');
            return redirect()->to('auth/login')->with('error', 'Invalid OAuth state.');
        }

        try {
            $token      = $provider->getAccessToken('authorization_code', ['code' => $code]);
            $fbUser     = $provider->getResourceOwner($token);
            $pic        = 'https://graph.facebook.com/' . $fbUser->getId() . '/picture?type=large';

            $userModel = new UserModel();
            $user      = $userModel->findOrCreateFromFacebook([
                'facebook_id' => $fbUser->getId(),
                'name'        => $fbUser->getName(),
                'email'       => $fbUser->getEmail() ?? '',
                'avatar'      => $pic,
            ]);

            $this->_createSession($user);
            (new CartModel())->mergeSessionCart($user['id'],session_id());

            return redirect()->to('/')->with('success', 'Welcome, ' . $user['name'] . '!');
        } catch (\Exception $e) {
            return redirect()->to('auth/login')->with('error', 'Facebook login failed: ' . $e->getMessage());
        }
    }

    // ── Private helpers ───────────────────────────────────────

    private function _createSession(array $user): void
    {
        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_avatar'=> $user['avatar'] ?? null,
            'logged_in'  => true,
        ]);
    }

    private function _googleProvider(): Google
    {
        return new Google([
            'clientId'     => getenv('GOOGLE_CLIENT_ID'),
            'clientSecret' => getenv('GOOGLE_CLIENT_SECRET'),
            'redirectUri'  => base_url('auth/google/callback'),
        ]);
    }

    private function _facebookProvider(): Facebook
    {
        return new Facebook([
            'clientId'        => getenv('FACEBOOK_APP_ID'),
            'clientSecret'    => getenv('FACEBOOK_APP_SECRET'),
            'redirectUri'     => base_url('auth/facebook/callback'),
            'graphApiVersion' => 'v18.0',
        ]);
    }
}
