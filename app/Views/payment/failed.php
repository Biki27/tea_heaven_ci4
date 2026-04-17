<?php
/**
 * Views/payment/failed.php
 */
?>
<?= view('layouts/header', ['cartCount' => $cartCount ?? 0, 'pageTitle' => 'Payment Failed']) ?>
<style>
.result-page{min-height:60vh;display:flex;align-items:center;justify-content:center;padding:60px 20px;}
.result-card{background:#fff;border-radius:20px;padding:50px 40px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.1);max-width:480px;width:100%;}
.result-icon{font-size:4rem;margin-bottom:20px;}
.result-card h2{font-size:1.6rem;font-weight:700;margin-bottom:10px;color:#e53935;}
.result-card p{color:#777;margin-bottom:24px;}
.btn-primary{background:#6b8e23;color:#fff;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:600;display:inline-block;margin:6px;transition:.2s;}
.btn-primary:hover{background:#5a7a1a;color:#fff;}
.btn-secondary{background:#f5f5f5;color:#555;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:600;display:inline-block;margin:6px;}
</style>
<div class="result-page">
  <div class="result-card">
    <div class="result-icon">❌</div>
    <h2>Payment Failed</h2>
    <p>We couldn't process your payment. Your cart is still saved — please try again.</p>
    <a href="<?= base_url('checkout') ?>" class="btn-primary"><i class="fas fa-redo me-2"></i>Try Again</a>
    <a href="<?= base_url('cart') ?>" class="btn-secondary">Back to Cart</a>
  </div>
</div>
<?= view('layouts/footer') ?>
