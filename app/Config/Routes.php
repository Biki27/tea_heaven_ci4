<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Tea Haven — Route Definitions
 *
 * @var RouteCollection $routes
 */

// ── Public routes ─────────────────────────────────────────────
$routes->get('/',                    'Home::index');
$routes->get('products',             'Products::index');
$routes->get('products/(:segment)',  'Products::show/$1');  // product detail
$routes->get('about',                'About::index');
$routes->get('contact',              'Contact::index');
$routes->post('contact/send',        'Contact::send');

// ── Auth routes ───────────────────────────────────────────────
$routes->get ('auth/login',          'Auth::loginPage');
$routes->post('auth/login',          'Auth::login');
$routes->get ('auth/register',       'Auth::registerPage');
$routes->post('auth/register',       'Auth::register');
$routes->get ('auth/logout',         'Auth::logout');
$routes->get ('auth/forgot',         'Auth::forgotPage');
$routes->post('auth/forgot',         'Auth::forgot');

// Google OAuth
$routes->get('auth/google',          'Auth::googleRedirect');
$routes->get('auth/google/callback', 'Auth::googleCallback');

// Facebook OAuth
$routes->get('auth/facebook',          'Auth::facebookRedirect');
$routes->get('auth/facebook/callback', 'Auth::facebookCallback');

// ── Cart routes (AJAX + page) ──────────────────────────────────
$routes->get ('cart',            'Cart::index');
$routes->post('cart/add',        'Cart::add');
$routes->post('cart/update',     'Cart::update');
$routes->post('cart/remove',     'Cart::remove');
$routes->get ('cart/count',      'Cart::count');
$routes->post('cart/promo',      'Cart::applyPromo');

// ── Checkout / Orders (require login) ─────────────────────────
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get ('checkout',                'Order::checkout');
    $routes->post('order/place',             'Order::place');
    $routes->get ('orders',                  'Order::history');
    $routes->get ('orders/(:num)',           'Order::detail/$1');
    $routes->get ('orders/(:num)/invoice',   'Order::invoice/$1');
});

// ── Payment (Razorpay) ────────────────────────────────────────
$routes->post('payment/create-order',  'Payment::createOrder');
$routes->post('payment/verify',        'Payment::verify');
$routes->get ('payment/success',       'Payment::success');
$routes->get ('payment/failed',        'Payment::failed');
$routes->post('payment/webhook',       'Payment::webhook');   // Razorpay server-side webhook

// ── Profile (require login) ───────────────────────────────────
$routes->group('profile', ['filter' => 'auth'], function ($routes) {
    $routes->get ('',        'Profile::index');
    $routes->post('update',  'Profile::update');
    $routes->post('password','Profile::changePassword');
});
