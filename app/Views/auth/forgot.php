<?php $pageTitle = 'Forgot Password'; ?>
<?= view('layouts/header', ['cartCount' => $cartCount ?? 0, 'pageTitle' => $pageTitle]) ?>
<style>
.forgot-page{min-height:calc(100vh - 80px);display:flex;align-items:center;justify-content:center;padding:40px 16px;background:#f6f5f7;}
.forgot-card{background:#fff;border-radius:16px;box-shadow:0 14px 48px rgba(0,0,0,.12);padding:44px 40px;max-width:440px;width:100%;text-align:center;}
.forgot-card h1{font-size:1.5rem;font-weight:700;margin-bottom:8px;}
.forgot-card p{color:#888;font-size:.9rem;margin-bottom:28px;}
.forgot-card input{width:100%;padding:12px 16px;background:#eee;border:none;border-radius:8px;font-size:.95rem;margin-bottom:16px;outline:none;transition:.2s;}
.forgot-card input:focus{background:#e0e0e0;box-shadow:0 0 0 2px rgba(107,142,35,.25);}
.btn-submit{width:100%;padding:13px;background:#6b8e23;color:#fff;border:none;border-radius:30px;font-weight:700;font-size:.95rem;cursor:pointer;transition:.2s;}
.btn-submit:hover{background:#5a7a1a;}
.back-link{display:inline-block;margin-top:16px;font-size:.87rem;color:#6b8e23;text-decoration:none;}
.alert-success{background:#d4edda;color:#155724;padding:11px 14px;border-radius:8px;font-size:.88rem;margin-bottom:16px;}
.alert-danger{background:#fde8e8;color:#c62828;padding:11px 14px;border-radius:8px;font-size:.88rem;margin-bottom:16px;}
</style>
<div class="forgot-page">
  <div class="forgot-card">
    <div style="font-size:2.5rem;margin-bottom:14px;">🔑</div>
    <h1>Forgot Password?</h1>
    <p>Enter your email and we'll send you a reset link.</p>

    <?php if ($s = session()->getFlashdata('success')): ?>
      <div class="alert-success"><i class="fas fa-check-circle me-2"></i><?= esc($s) ?></div>
    <?php endif; ?>
    <?php if ($e = session()->getFlashdata('error')): ?>
      <div class="alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= esc($e) ?></div>
    <?php endif; ?>

    <form action="<?= base_url('auth/forgot') ?>" method="POST">
      <?= csrf_field() ?>
      <input type="email" name="email" placeholder="your@email.com" required>
      <button type="submit" class="btn-submit">Send Reset Link</button>
    </form>
    <a href="<?= base_url('auth/login') ?>" class="back-link">← Back to Sign In</a>
  </div>
</div>
<?= view('layouts/footer') ?>
