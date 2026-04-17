<?php
/**
 * layouts/footer.php
 * Shared footer for all pages
 */
?>
<footer class="site-footer">
  <div class="container">
    <div class="row">
      <div class="col-12 col-md-4 mb-4">
        <h5 class="footer-brand">🍃 Tea Haven</h5>
        <p class="footer-about">
          We bring you the finest handpicked tea leaves sourced directly from
          renowned tea gardens. Every blend is crafted with passion for an
          unforgettable cup.
        </p>
        <div class="social-icons">
          <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <div class="col-6 col-md-2 mb-4">
        <h6 class="footer-heading">Categories</h6>
        <ul class="footer-links">
          <li><a href="<?= base_url('products?category=black-tea') ?>">Black Tea</a></li>
          <li><a href="<?= base_url('products?category=green-tea') ?>">Green Tea</a></li>
          <li><a href="<?= base_url('products?category=herbal-tea') ?>">Herbal Tea</a></li>
          <li><a href="<?= base_url('products?category=oolong-tea') ?>">Oolong Tea</a></li>
          <li><a href="<?= base_url('products?category=masala-chai') ?>">Masala Chai</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2 mb-4">
        <h6 class="footer-heading">Quick Links</h6>
        <ul class="footer-links">
          <li><a href="<?= base_url() ?>">Home</a></li>
          <li><a href="<?= base_url('products') ?>">Shop</a></li>
          <?php if (session()->get('user_id')): ?>
            <li><a href="<?= base_url('orders') ?>">My Orders</a></li>
            <li><a href="<?= base_url('profile') ?>">Profile</a></li>
            <li><a href="<?= base_url('auth/logout') ?>">Logout</a></li>
          <?php else: ?>
            <li><a href="<?= base_url('auth/login') ?>">Sign In</a></li>
            <li><a href="<?= base_url('auth/register') ?>">Register</a></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="col-12 col-md-4 mb-4">
        <h6 class="footer-heading">Contact Us</h6>
        <ul class="footer-links">
          <li><i class="fas fa-map-marker-alt me-2"></i>Kolkata, West Bengal, India</li>
          <li><i class="fas fa-phone me-2"></i>+91 98765 43210</li>
          <li><i class="fas fa-envelope me-2"></i>hello@teahaven.in</li>
        </ul>
        <div class="trust-badges mt-3">
          <span class="badge-item"><i class="fas fa-shield-alt"></i> Secure Checkout</span>
          <span class="badge-item"><i class="fas fa-undo"></i> 14-Day Returns</span>
          <span class="badge-item"><i class="fas fa-truck"></i> Free Ship ₹500+</span>
        </div>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> Tea Haven. All Rights Reserved.</p>
      <p><a href="#">Privacy Policy</a> · <a href="#">Terms of Service</a> · <a href="#">Sitemap</a></p>
    </div>
  </div>
</footer>

<style>
.site-footer{background:#1a1a1a;color:#bbb;padding:60px 0 20px;}
.footer-brand{color:#fff;font-size:1.3rem;font-weight:700;margin-bottom:12px;}
.footer-about{font-size:.9rem;line-height:1.7;color:#999;}
.footer-heading{color:#fff;font-weight:600;margin-bottom:14px;text-transform:uppercase;font-size:.82rem;letter-spacing:.8px;}
.footer-links{list-style:none;padding:0;margin:0;}
.footer-links li{margin-bottom:8px;font-size:.9rem;color:#999;}
.footer-links a{color:#999;text-decoration:none;transition:.2s;}
.footer-links a:hover{color:var(--leaf-green);}
.social-icons{display:flex;gap:10px;margin-top:14px;}
.social-icons a{width:36px;height:36px;border-radius:50%;border:1px solid #444;display:inline-flex;align-items:center;justify-content:center;color:#999;text-decoration:none;transition:.3s;font-size:.85rem;}
.social-icons a:hover{background:var(--leaf-green);border-color:var(--leaf-green);color:#fff;}
.trust-badges{display:flex;flex-wrap:wrap;gap:8px;}
.badge-item{font-size:.78rem;color:#aaa;background:#2a2a2a;padding:5px 10px;border-radius:20px;}
.badge-item i{color:var(--leaf-green);margin-right:4px;}
.footer-divider{border-color:#333;margin:30px 0 20px;}
.footer-bottom{display:flex;justify-content:space-between;flex-wrap:wrap;gap:8px;font-size:.85rem;color:#666;}
.footer-bottom a{color:#777;text-decoration:none;}
.footer-bottom a:hover{color:var(--leaf-green);}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>const BASE_URL = '<?= base_url() ?>/';</script>
<script src="<?= base_url('js/cart.js') ?>"></script>
</body>
</html>
