<?php
/**
 * Views/auth/login.php
 * Handles both Sign In and Sign Up panels — mirrors login.html design
 * Variables: $mode ('login'|'register'), $cartCount
 */
$mode ??= 'login';
?>
<?= view('layouts/header', ['cartCount' => $cartCount ?? 0, 'pageTitle' => 'Sign In / Register']) ?>

<style>
body{background:#f6f5f7;}
.auth-page{min-height:calc(100vh - 80px);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 16px;}
.auth-page h2{font-size:1.5rem;font-weight:700;margin-bottom:28px;color:#333;}

/* Container (mirrors the original flip-card design) */
.auth-box{background:#fff;border-radius:14px;box-shadow:0 14px 48px rgba(0,0,0,.18);position:relative;overflow:hidden;width:100%;max-width:800px;min-height:500px;}

/* Two panels */
.panel{position:absolute;top:0;height:100%;width:50%;transition:all .6s ease-in-out;}
.sign-in-panel{left:0;z-index:2;}
.sign-up-panel{left:0;opacity:0;z-index:1;}

/* Active: sign-up visible */
.auth-box.show-register .sign-in-panel{transform:translateX(100%);}
.auth-box.show-register .sign-up-panel{transform:translateX(100%);opacity:1;z-index:5;animation:fadeIn .6s;}
@keyframes fadeIn{0%,49.99%{opacity:0;z-index:1;}50%,100%{opacity:1;z-index:5;}}

/* Form inside panel */
.panel form{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;padding:40px 36px;background:#fff;}
.panel h1{font-size:1.6rem;font-weight:700;margin-bottom:14px;color:#333;}
.panel span{font-size:.82rem;color:#888;margin-bottom:12px;}
.panel input[type=text],.panel input[type=email],.panel input[type=password]{width:100%;padding:11px 14px;margin:6px 0;background:#eee;border:none;border-radius:6px;font-size:.9rem;outline:none;transition:.2s;}
.panel input:focus{background:#e0e0e0;box-shadow:0 0 0 2px rgba(107,142,35,.25);}
.panel a.forgot{font-size:.82rem;color:#888;text-decoration:none;margin:8px 0;}
.panel a.forgot:hover{color:var(--leaf-green);}
.btn-submit{margin-top:14px;width:100%;padding:12px;background:var(--leaf-green);color:#fff;border:none;border-radius:30px;font-weight:700;font-size:.9rem;letter-spacing:.8px;text-transform:uppercase;cursor:pointer;transition:.2s;}
.btn-submit:hover{background:#5a7a1a;transform:scale(.98);}

/* Social row */
.social-row{display:flex;gap:10px;margin:12px 0;}
.social-btn{width:42px;height:42px;border-radius:50%;border:1px solid #ddd;display:inline-flex;align-items:center;justify-content:center;font-size:1rem;text-decoration:none;color:#555;transition:.25s;}
.social-btn:hover{border-color:var(--leaf-green);color:var(--leaf-green);}
.social-btn.google:hover{color:#db4437;border-color:#db4437;}
.social-btn.facebook:hover{color:#3b5998;border-color:#3b5998;}

/* Overlay */
.overlay-col{position:absolute;top:0;left:50%;width:50%;height:100%;overflow:hidden;transition:transform .6s ease-in-out;z-index:100;}
.auth-box.show-register .overlay-col{transform:translateX(-100%);}
.overlay-bg{background:linear-gradient(135deg,var(--leaf-green),#4a6217);color:#fff;position:relative;left:-100%;height:100%;width:200%;transform:translateX(0);transition:transform .6s ease-in-out;display:flex;}
.auth-box.show-register .overlay-bg{transform:translateX(50%);}
.overlay-panel{width:50%;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:0 30px;text-align:center;}
.overlay-panel h1{font-size:1.5rem;font-weight:700;margin-bottom:12px;}
.overlay-panel p{font-size:.88rem;opacity:.9;margin-bottom:22px;}
.btn-ghost{background:transparent;color:#fff;border:2px solid #fff;border-radius:30px;padding:10px 36px;font-weight:700;font-size:.85rem;cursor:pointer;letter-spacing:.8px;text-transform:uppercase;transition:.25s;}
.btn-ghost:hover{background:rgba(255,255,255,.15);}

/* Error box */
.alert-box{width:100%;padding:10px 14px;border-radius:8px;font-size:.85rem;margin-bottom:10px;}
.alert-danger{background:#fde8e8;color:#c62828;}
.alert-success{background:#e8f5e9;color:#2e7d32;}

/* Responsive: stack on mobile */
@media(max-width:640px){
  .auth-box{min-height:auto;}
  .panel,.overlay-col{position:static;width:100%;opacity:1!important;transform:none!important;}
  .sign-up-panel{display:none;}
  .auth-box.show-register .sign-in-panel{display:none;}
  .auth-box.show-register .sign-up-panel{display:flex;animation:none;}
  .overlay-col{display:none;}
}
</style>

<div class="auth-page">
  <h2>Sign In / Sign Up</h2>

  <div class="auth-box <?= $mode === 'register' ? 'show-register' : '' ?>" id="authBox">

    <!-- ── Sign In Panel ─────────────────────────────────── -->
    <div class="panel sign-in-panel">
      <form action="<?= base_url('auth/login') ?>" method="POST">
        <?= csrf_field() ?>
        <h1>Sign In</h1>

        <?php if ($error = session()->getFlashdata('error')): ?>
          <div class="alert-box alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="social-row">
          <a href="<?= base_url('auth/google') ?>"   class="social-btn google"   title="Sign in with Google">
            <i class="fab fa-google"></i>
          </a>
          <a href="<?= base_url('auth/facebook') ?>" class="social-btn facebook" title="Sign in with Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
        </div>
        <span>or use your email account</span>

        <input type="email"    name="email"    placeholder="Email"    required value="<?= old('email') ?>">
        <input type="password" name="password" placeholder="Password" required>
        <a href="<?= base_url('auth/forgot') ?>" class="forgot">Forgot your password?</a>
        <button type="submit" class="btn-submit">Sign In</button>
      </form>
    </div>

    <!-- ── Sign Up Panel ──────────────────────────────────── -->
    <div class="panel sign-up-panel">
      <form action="<?= base_url('auth/register') ?>" method="POST">
        <?= csrf_field() ?>
        <h1>Create Account</h1>

        <?php if ($err = session()->getFlashdata('register_error')): ?>
          <div class="alert-box alert-danger"><?= $err ?></div>
        <?php endif; ?>

        <div class="social-row">
          <a href="<?= base_url('auth/google') ?>"   class="social-btn google"   title="Sign up with Google">
            <i class="fab fa-google"></i>
          </a>
          <a href="<?= base_url('auth/facebook') ?>" class="social-btn facebook" title="Sign up with Facebook">
            <i class="fab fa-facebook-f"></i>
          </a>
        </div>
        <span>or use your email for registration</span>

        <input type="text"     name="name"     placeholder="Full Name" required value="<?= old('name') ?>">
        <input type="email"    name="email"    placeholder="Email"     required value="<?= old('email') ?>">
        <input type="password" name="password" placeholder="Password (min 8 chars)" required>
        <button type="submit" class="btn-submit">Sign Up</button>
      </form>
    </div>

    <!-- ── Overlay ─────────────────────────────────────────── -->
    <div class="overlay-col">
      <div class="overlay-bg">
        <!-- Left panel (visible during register) -->
        <div class="overlay-panel">
          <h1>Welcome Back!</h1>
          <p>Already have an account? Sign in to access your orders and profile.</p>
          <button class="btn-ghost" id="btnShowSignIn">Sign In</button>
        </div>
        <!-- Right panel (visible during login) -->
        <div class="overlay-panel">
          <h1>Hello, Friend!</h1>
          <p>New to Tea Haven? Register now and enjoy your first order discount.</p>
          <button class="btn-ghost" id="btnShowSignUp">Sign Up</button>
        </div>
      </div>
    </div>

  </div><!-- /auth-box -->
</div>

<script>
const authBox   = document.getElementById('authBox');
document.getElementById('btnShowSignUp').addEventListener('click', () => authBox.classList.add('show-register'));
document.getElementById('btnShowSignIn').addEventListener('click', () => authBox.classList.remove('show-register'));
</script>

<?= view('layouts/footer') ?>
