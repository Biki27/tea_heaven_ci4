<?php
/**
 * layouts/header.php
 * Variables expected: $cartCount (int), $pageTitle (string, optional)
 */
$pageTitle ??= 'Tea Haven';
$cartCount ??= 0;
$userId    = session()->get('user_id');
$userName  = session()->get('user_name');
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
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<style>
:root{--leaf-green:#6b8e23;--tea-gold:#d4af37;--warm-brown:#4e342e;}
body{font-family:'Poppins',sans-serif;padding-top:80px;}
.tea-nav{background:rgba(255,255,255,.95);backdrop-filter:blur(12px);box-shadow:0 4px 25px rgba(0,0,0,.08);}
.navbar-brand{font-weight:700;font-size:1.4rem;color:var(--warm-brown)!important;}
.logo-img{height:38px;border-radius:6px;margin-right:8px;}
.nav-link{color:#555!important;font-weight:500;padding:8px 16px!important;border-radius:25px;transition:.25s;}
.nav-link:hover,.nav-link.active{color:var(--leaf-green)!important;background:rgba(107,142,35,.09);}
.tea-dropdown{border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.12);border:none;}
.cart-icon-wrapper{position:relative;display:inline-flex;}
.cart-badge{position:absolute;top:-6px;right:-8px;background:var(--tea-gold);color:#fff;border-radius:50%;width:20px;height:20px;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center;}
.nav-icon-btn{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;color:#555;transition:.3s;text-decoration:none;}
.nav-icon-btn:hover{background:rgba(107,142,35,.1);color:var(--leaf-green);}
.flash-success{background:#d4edda;color:#155724;padding:12px 20px;text-align:center;}
.flash-error  {background:#f8d7da;color:#721c24;padding:12px 20px;text-align:center;}
</style>
<script>
    const BASE_URL = '<?= base_url() ?>/';
</script>
</head>
<body>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="flash-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="flash-error"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top tea-nav">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url() ?>">
      <img src="<?= base_url('images/logo.png') ?>" alt="Tea Haven" class="logo-img" onerror="this.style.display='none'">
      Tea Haven
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav mx-auto">
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
            <li><a class="dropdown-item" href="<?= base_url('products') ?>">All Products</a></li>
          </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('about') ?>">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('contact') ?>">Contact</a></li>
      </ul>

      <div class="d-flex align-items-center gap-2">
        <!-- Cart -->
        <a href="<?= base_url('cart') ?>" class="nav-icon-btn cart-icon-wrapper" title="Cart">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-badge" id="cartBadge"><?= (int) $cartCount ?></span>
        </a>

        <!-- User menu -->
        <?php if ($userId): ?>
          <div class="dropdown">
            <button class="nav-icon-btn" data-bs-toggle="dropdown" title="Account">
              <?php if (session()->get('user_avatar')): ?>
                <img src="<?= esc(session()->get('user_avatar')) ?>" style="width:30px;height:30px;border-radius:50%;object-fit:cover">
              <?php else: ?>
                <i class="fas fa-user-circle fa-lg"></i>
              <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end tea-dropdown">
              <li><span class="dropdown-item-text fw-bold text-muted"><?= esc($userName) ?></span></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= base_url('profile') ?>"><i class="fas fa-user me-2"></i>Profile</a></li>
              <li><a class="dropdown-item" href="<?= base_url('orders') ?>"><i class="fas fa-box me-2"></i>My Orders</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="<?= base_url('auth/login') ?>" class="nav-icon-btn" title="Sign In">
            <i class="fas fa-user"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
