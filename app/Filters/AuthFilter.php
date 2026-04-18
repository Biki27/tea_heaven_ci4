<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * AuthFilter
 * Redirects guests to the login page for protected routes.
 */
class AuthFilter implements FilterInterface
{
    // public function before(RequestInterface $request, $arguments = null)
    // {
    //     if (! session()->get('user_id')) {
    //         return redirect()
    //             ->to(base_url('auth/login'))
    //             ->with('error', 'Please log in to continue.');
    //     }
    // }
    // Inside app/Filters/AuthFilter.php
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('user_id')) {
            // THIS IS THE CRITICAL LINE:
            session()->set('redirect_after_login', current_url());

            return redirect()->to('/auth/login')->with('error', 'Please log in to checkout.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing needed after response
    }
}
