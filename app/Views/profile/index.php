<?php
/**
 * Views/profile/index.php
 * Variables: $user (array), $cartCount (int)
 */
$pageTitle = 'My Profile';
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
:root{--leaf-green:#6b8e23;--warm-brown:#4e342e;}
.profile-page{padding:40px 0 80px;background:#f9fafb;}
.profile-hero{background:linear-gradient(135deg,var(--leaf-green),#90c695);color:#fff;padding:36px;border-radius:20px;margin-bottom:32px;display:flex;align-items:center;gap:24px;}
.avatar-circle{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,.6);flex-shrink:0;background:#fff;}
.avatar-placeholder{width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:2rem;flex-shrink:0;}
.profile-hero h1{font-size:1.5rem;font-weight:700;margin:0 0 4px;}
.profile-hero p{margin:0;opacity:.85;font-size:.9rem;}

.tab-card{background:#fff;border-radius:16px;box-shadow:0 4px 18px rgba(0,0,0,.07);overflow:hidden;}
.profile-tabs{display:flex;border-bottom:2px solid #f0f0f0;}
.tab-btn{padding:14px 24px;font-size:.9rem;font-weight:600;color:#888;background:none;border:none;cursor:pointer;border-bottom:2px solid transparent;margin-bottom:-2px;transition:.2s;}
.tab-btn.active{color:var(--leaf-green);border-bottom-color:var(--leaf-green);}
.tab-btn:hover{color:var(--leaf-green);}
.tab-panel{display:none;padding:30px;}
.tab-panel.active{display:block;}

.form-label{font-size:.85rem;font-weight:600;color:#444;margin-bottom:4px;}
.form-control,.form-select{border-radius:8px;border:1px solid #ddd;padding:10px 14px;font-size:.9rem;transition:.2s;}
.form-control:focus,.form-select:focus{border-color:var(--leaf-green);box-shadow:0 0 0 3px rgba(107,142,35,.15);}
.btn-save{background:var(--leaf-green);color:#fff;border:none;padding:11px 32px;border-radius:30px;font-weight:700;font-size:.9rem;cursor:pointer;transition:.2s;}
.btn-save:hover{background:#5a7a1a;}
.oauth-note{background:#f0f7e6;border:1px solid #c8e6a0;border-radius:8px;padding:12px 16px;font-size:.85rem;color:#4a7a1a;margin-bottom:20px;}
.alert-success{background:#d4edda;color:#155724;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.88rem;}
.alert-danger {background:#fde8e8;color:#c62828;padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:.88rem;}
.section-divider{border:none;border-top:1px solid #f0f0f0;margin:24px 0;}
</style>

<div class="profile-page">
  <div class="container">

    <!-- Hero banner -->
    <div class="profile-hero">
      <?php if ($user['avatar']): ?>
        <img src="<?= esc($user['avatar']) ?>" class="avatar-circle" alt="Avatar">
      <?php else: ?>
        <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
      <?php endif; ?>
      <div>
        <h1><?= esc($user['name']) ?></h1>
        <p><?= esc($user['email']) ?></p>
        <?php if ($user['google_id'] || $user['facebook_id']): ?>
          <p style="font-size:.8rem;opacity:.7;margin-top:4px;">
            <?php if ($user['google_id']): ?>
              <i class="fab fa-google me-1"></i>Google Account &nbsp;
            <?php endif; ?>
            <?php if ($user['facebook_id']): ?>
              <i class="fab fa-facebook-f me-1"></i>Facebook Account
            <?php endif; ?>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="tab-card">

          <!-- Tabs -->
          <div class="profile-tabs">
            <button class="tab-btn active" onclick="switchTab('info', this)">
              <i class="fas fa-user me-1"></i> Personal Info
            </button>
            <button class="tab-btn" onclick="switchTab('password', this)">
              <i class="fas fa-lock me-1"></i> Change Password
            </button>
          </div>

          <!-- Tab: Personal Info -->
          <div class="tab-panel active" id="tab-info">
            <?php if ($s = session()->getFlashdata('success')): ?>
              <div class="alert-success"><i class="fas fa-check-circle me-2"></i><?= esc($s) ?></div>
            <?php endif; ?>
            <?php if ($e = session()->getFlashdata('error')): ?>
              <div class="alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= $e ?></div>
            <?php endif; ?>

            <form action="<?= base_url('profile/update') ?>" method="POST">
              <?= csrf_field() ?>
              <div class="row g-3">
                <div class="col-sm-6">
                  <label class="form-label">Full Name*</label>
                  <input type="text" class="form-control" name="name"
                         value="<?= esc(old('name', $user['name'])) ?>" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control"
                         value="<?= esc($user['email']) ?>" disabled>
                  <small class="text-muted">Email cannot be changed.</small>
                </div>
                <div class="col-sm-6">
                  <label class="form-label">Phone</label>
                  <input type="tel" class="form-control" name="phone"
                         value="<?= esc(old('phone', $user['phone'])) ?>"
                         placeholder="+91 98765 43210">
                </div>
                <div class="col-sm-6">
                  <label class="form-label">City</label>
                  <input type="text" class="form-control" name="city"
                         value="<?= esc(old('city', $user['city'])) ?>">
                </div>
                <div class="col-12">
                  <label class="form-label">Address</label>
                  <input type="text" class="form-control" name="address"
                         value="<?= esc(old('address', $user['address'])) ?>"
                         placeholder="Street, Area">
                </div>
                <div class="col-sm-6">
                  <label class="form-label">PIN Code</label>
                  <input type="text" class="form-control" name="pincode"
                         value="<?= esc(old('pincode', $user['pincode'])) ?>"
                         maxlength="6">
                </div>
                <div class="col-sm-6">
                  <label class="form-label">Country</label>
                  <select class="form-select" name="country">
                    <option value="India" <?= ($user['country'] ?? 'India') === 'India' ? 'selected' : '' ?>>India</option>
                    <option value="Other" <?= ($user['country'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                  </select>
                </div>
                <div class="col-12 mt-2">
                  <button type="submit" class="btn-save">
                    <i class="fas fa-save me-2"></i>Save Changes
                  </button>
                </div>
              </div>
            </form>
          </div>

          <!-- Tab: Change Password -->
          <div class="tab-panel" id="tab-password">
            <?php if ($user['google_id'] && ! $user['password']): ?>
              <div class="oauth-note">
                <i class="fab fa-google me-2"></i>
                You signed in with Google. Password login is not set for this account.
                You can set a password below to enable email login as well.
              </div>
            <?php endif; ?>
            <?php if ($user['facebook_id'] && ! $user['password']): ?>
              <div class="oauth-note">
                <i class="fab fa-facebook-f me-2"></i>
                You signed in with Facebook. Set a password below to also enable email login.
              </div>
            <?php endif; ?>

            <form action="<?= base_url('profile/password') ?>" method="POST">
              <?= csrf_field() ?>
              <div class="row g-3">
                <?php if ($user['password']): ?>
                  <div class="col-12">
                    <label class="form-label">Current Password*</label>
                    <input type="password" class="form-control" name="current_password" required>
                  </div>
                <?php endif; ?>
                <div class="col-sm-6">
                  <label class="form-label">New Password* (min 8 chars)</label>
                  <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="col-sm-6">
                  <label class="form-label">Confirm New Password*</label>
                  <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <div class="col-12 mt-2">
                  <button type="submit" class="btn-save">
                    <i class="fas fa-lock me-2"></i>Update Password
                  </button>
                </div>
              </div>
            </form>
          </div>

        </div><!-- /tab-card -->
      </div>

      <!-- Quick links sidebar -->
      <div class="col-lg-4">
        <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 4px 18px rgba(0,0,0,.07);">
          <h6 style="font-weight:700;color:#333;margin-bottom:16px;">Quick Links</h6>
          <a href="<?= base_url('orders') ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#555;transition:.2s;margin-bottom:8px;border:1px solid #eee;">
            <i class="fas fa-box" style="color:var(--leaf-green);width:20px;"></i>
            My Orders
          </a>
          <a href="<?= base_url('cart') ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#555;transition:.2s;margin-bottom:8px;border:1px solid #eee;">
            <i class="fas fa-shopping-bag" style="color:var(--leaf-green);width:20px;"></i>
            My Cart
          </a>
          <a href="<?= base_url('products') ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#555;transition:.2s;margin-bottom:8px;border:1px solid #eee;">
            <i class="fas fa-mug-hot" style="color:var(--leaf-green);width:20px;"></i>
            Browse Teas
          </a>
          <hr style="border-color:#f0f0f0;">
          <a href="<?= base_url('auth/logout') ?>" style="display:flex;align-items:center;gap:12px;padding:12px;border-radius:10px;text-decoration:none;color:#e53935;transition:.2s;border:1px solid #fde8e8;">
            <i class="fas fa-sign-out-alt" style="width:20px;"></i>
            Sign Out
          </a>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function switchTab(id, btn) {
  document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('tab-' + id).classList.add('active');
  btn.classList.add('active');
}
</script>

<?= view('layouts/footer') ?>
