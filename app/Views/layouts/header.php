<?php

/**
 * layouts/header.php
 * Variables expected: $cartCount (int), $pageTitle (string, optional)
 */
$pageTitle ??= 'Tea Haven';
$cartCount ??= 0;
$userId    = session()->get('user_id');
$userName  = session()->get('user_name');

// Extract the first letter of the user's name for the dynamic avatar
$userInitial = $userName ? strtoupper(substr(trim($userName), 0, 1)) : 'U';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($pageTitle) ?> – Tea Haven</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
  
  <style>
    :root {
      --leaf-green: #6b8e23;
      --tea-gold: #d4af37;
      --warm-brown: #4e342e;
      --bg-light: #faf7f2;
    }

    body {
      font-family: 'Poppins', sans-serif;
      padding-top: 85px; /* Adjusted for natural navbar height */
    }

    /* Navbar Styling */
    .tea-nav {
      background: rgba(255, 255, 255, 0.98);
      backdrop-filter: blur(12px);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
      padding: 12px 0;
      transition: all 0.3s ease;
      height: 81px;

    }

    .navbar-brand {
      font-weight: 700;
      font-size: 1.4rem;
      color: var(--warm-brown) !important;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo-img {
      height: 40px;
      border-radius: 6px;
    }

    /* Nav Links */
    .nav-link {
      color: #444 !important;
      font-weight: 500;
      padding: 8px 18px !important;
      border-radius: 25px;
      transition: all 0.3s ease;
      margin: 0 4px;
    }

    .nav-link:hover,
    .nav-link.active {
      color: var(--leaf-green) !important;
      background: rgba(107, 142, 35, 0.08);
    }

    /* Dropdown Styling */
    .tea-dropdown {
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      border: none;
      padding: 10px 0;
      margin-top: 10px;
      animation: dropdownFade 0.3s ease forwards;
      transform-origin: top;
    }

    @keyframes dropdownFade {
      0% { opacity: 0; transform: translateY(10px); }
      10% { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
      padding: 8px 20px;
      font-weight: 400;
      color: #555;
      transition: 0.2s;
    }

    .dropdown-item:hover {
      background-color: rgba(107, 142, 35, 0.08);
      color: var(--leaf-green);
      padding-left: 25px; /* Slight indent on hover */
    }

    /* Icons Group (Cart & User) */
    .nav-icons-group {
      display: flex;
      align-items: center;
      gap: 12px; /* Clean spacing between icons */
    }

    .nav-icon-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #555;
      transition: all 0.3s ease;
      text-decoration: none;
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 0;
    }

    .nav-icon-btn:hover {
      background: rgba(107, 142, 35, 0.1);
      color: var(--leaf-green);
      transform: translateY(-2px);
    }

    /* Cart Badge */
    .cart-icon-wrapper {
      position: relative;
    }

    .cart-badge {
      position: absolute;
      top: -2px;
      right: -4px;
      background: var(--tea-gold);
      color: #fff;
      border-radius: 50%;
      min-width: 20px;
      height: 20px;
      padding: 0 6px;
      font-size: 0.7rem;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 2px solid #fff;
    }

    /* Dynamic User Avatar */
    .user-avatar-img {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--leaf-green);
    }

    .avatar-initial {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background-color: var(--leaf-green);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
      font-size: 1.1rem;
      box-shadow: 0 2px 5px rgba(107, 142, 35, 0.3);
    }
    
    /* Remove caret from user dropdown */
    .dropdown-toggle-no-caret::after {
        display: none !important;
    }
  </style>
  <script>
    const BASE_URL = '<?= base_url() ?>/';
  </script>
</head>

<body>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            background: '#faf7f2',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        <?php if (session()->getFlashdata('success')): ?>
            Toast.fire({
                icon: 'success',
                title: '<?= esc(session()->getFlashdata('success')) ?>',
                iconColor: '#6B8E23'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            Toast.fire({
                icon: 'error',
                title: '<?= esc(session()->getFlashdata('error')) ?>'
            });
        <?php endif; ?>
    });
  </script>

  <nav class="navbar navbar-expand-lg fixed-top tea-nav">
    <div class="container">
      <a class="navbar-brand" href="<?= base_url() ?>">
        <img src="<?= base_url('images/logo.png') ?>" alt="Tea Haven" class="logo-img" onerror="this.style.display='none'">
        Tea Haven
      </a>

      <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <i class="fa-solid fa-bars" style="color: var(--warm-brown); font-size: 1.5rem;"></i>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="<?= base_url() ?>">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Our Teas</a>
            <ul class="dropdown-menu tea-dropdown">
              <li><a class="dropdown-item" href="<?= base_url('products?category=black-tea') ?>">Black Tea</a></li>
              <li><a class="dropdown-item" href="<?= base_url('products?category=green-tea') ?>">Green Tea</a></li>
              <li><a class="dropdown-item" href="<?= base_url('products?category=herbal-tea') ?>">Herbal Tea</a></li>
              <li><a class="dropdown-item" href="<?= base_url('products?category=oolong-tea') ?>">Oolong Tea</a></li>
              <li><a class="dropdown-item" href="<?= base_url('products?category=masala-chai') ?>">Masala Chai</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item fw-medium" href="<?= base_url('products') ?>">All Products <i class="fa-solid fa-arrow-right fa-sm ms-1"></i></a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
        </ul>

        <div class="nav-icons-group mt-3 mt-lg-0">
          
         <a href="<?= base_url('cart') ?>" class="nav-icon-btn cart-icon-wrapper" title="Cart">
            <i class="fas fa-cart-shopping fa-lg"></i>
            <span class="cart-badge" id="cartBadge" style="<?= $cartCount == 0 ? 'display: none;' : '' ?>">
              <?= (int) $cartCount ?>
            </span>
          </a>

          <?php if ($userId): ?>
            <div class="dropdown">
              <button class="nav-icon-btn dropdown-toggle dropdown-toggle-no-caret" data-bs-toggle="dropdown" title="Account" aria-expanded="false">
                <?php if (session()->get('user_avatar')): ?>
                  <img src="<?= esc(session()->get('user_avatar')) ?>" class="user-avatar-img" alt="User">
                <?php else: ?>
                  <span class="avatar-initial"><?= esc($userInitial) ?></span>
                <?php endif; ?>
              </button>
              
              <ul class="dropdown-menu dropdown-menu-end tea-dropdown">
                <li>
                    <div class="px-4 py-2 d-flex align-items-center gap-3">
                        <?php if (session()->get('user_avatar')): ?>
                            <img src="<?= esc(session()->get('user_avatar')) ?>" class="user-avatar-img" alt="User">
                        <?php else: ?>
                            <span class="avatar-initial" style="width: 40px; height: 40px; font-size: 1.2rem;"><?= esc($userInitial) ?></span>
                        <?php endif; ?>
                        <div>
                            <span class="d-block fw-bold text-dark" style="font-size: 0.95rem;">Hi, <?= esc($userName) ?></span>
                            <span class="d-block text-muted" style="font-size: 0.8rem;">My Account</span>
                        </div>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user-cog fa-fw me-2 text-muted"></i>Profile</a></li>
                <li><a class="dropdown-item" href="<?= base_url('orders') ?>"><i class="fas fa-box-open fa-fw me-2 text-muted"></i>My Orders</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="<?= base_url('auth/login') ?>" class="nav-icon-btn" title="Sign In">
              <i class="fas fa-user fa-lg"></i>
            </a>
          <?php endif; ?>
          
        </div>
      </div>
    </div>
  </nav>