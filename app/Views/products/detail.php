<?php
/**
 * Views/products/detail.php
 * Variables: $product (array), $related (array), $cartCount (int)
 */
$pageTitle = $product['name'];
$price     = ($product['sale_price'] > 0) ? $product['sale_price'] : $product['price'];
$saving    = ($product['sale_price'] > 0) ? round((1 - $product['sale_price'] / $product['price']) * 100) : 0;
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
:root{--leaf-green:#6b8e23;--warm-brown:#4e342e;}
.detail-page{padding:40px 0 80px;}
.breadcrumb-wrap{font-size:.84rem;color:#aaa;margin-bottom:28px;}
.breadcrumb-wrap a{color:#aaa;text-decoration:none;}
.breadcrumb-wrap a:hover{color:var(--leaf-green);}
.breadcrumb-wrap span{color:#333;}

.product-grid{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;}
@media(max-width:768px){.product-grid{grid-template-columns:1fr;}}

/* Image panel */
.image-panel{position:sticky;top:100px;}
.main-img-wrap{border-radius:16px;overflow:hidden;background:#f5f5f5;aspect-ratio:1/1;}
.main-img-wrap img{width:100%;height:100%;object-fit:cover;transition:.4s;}
.main-img-wrap:hover img{transform:scale(1.04);}

/* Info panel */
.product-category{font-size:.8rem;text-transform:uppercase;letter-spacing:.8px;color:var(--leaf-green);font-weight:700;margin-bottom:8px;}
.product-title{font-size:1.8rem;font-weight:700;color:#222;margin-bottom:12px;line-height:1.3;}
.price-block{display:flex;align-items:center;gap:14px;margin-bottom:20px;}
.price-new{font-size:1.9rem;font-weight:800;color:var(--leaf-green);}
.price-old{font-size:1.2rem;text-decoration:line-through;color:#aaa;}
.save-chip{background:#fde8e8;color:#c62828;padding:4px 12px;border-radius:20px;font-size:.8rem;font-weight:700;}
.stock-badge{font-size:.85rem;font-weight:600;margin-bottom:20px;}
.stock-badge.in{color:#10b981;}<br>.stock-badge.out{color:#ef4444;}
.product-desc{font-size:.95rem;line-height:1.8;color:#666;margin-bottom:28px;}

/* Qty + Add to cart */
.qty-row{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
.qty-ctrl{display:flex;align-items:center;gap:8px;background:#f5f5f5;padding:6px 10px;border-radius:30px;}
.qty-btn{width:36px;height:36px;border:none;background:#fff;border-radius:50%;font-size:1.1rem;font-weight:700;cursor:pointer;transition:.2s;box-shadow:0 2px 6px rgba(0,0,0,.1);}
.qty-btn:hover{background:var(--leaf-green);color:#fff;}
.qty-num{width:36px;text-align:center;font-weight:700;font-size:1rem;background:none;border:none;}
.btn-add-cart{flex:1;padding:14px 28px;background:var(--leaf-green);color:#fff;border:none;border-radius:40px;font-size:1rem;font-weight:700;cursor:pointer;transition:.3s;}
.btn-add-cart:hover{background:#5a7a1a;transform:translateY(-2px);box-shadow:0 8px 25px rgba(107,142,35,.3);}
.btn-add-cart:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.btn-wishlist{width:50px;height:50px;border-radius:50%;border:2px solid #ddd;background:#fff;cursor:pointer;font-size:1.1rem;color:#aaa;transition:.3s;flex-shrink:0;}
.btn-wishlist:hover{border-color:#ef4444;color:#ef4444;}

/* Meta pills */
.meta-pills{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px;}
.meta-pill{background:#f5f5f5;color:#555;padding:6px 16px;border-radius:20px;font-size:.82rem;font-weight:500;}
.meta-pill i{color:var(--leaf-green);margin-right:5px;}

/* Divider */
.section-divider{border:none;border-top:1px solid #f0f0f0;margin:28px 0;}

/* Related */
.related-section{padding:60px 0;}
.related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
@media(max-width:900px){.related-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.related-grid{grid-template-columns:1fr;}}
.rel-card{background:#fff;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.07);overflow:hidden;transition:.3s;}
.rel-card:hover{transform:translateY(-5px);box-shadow:0 12px 30px rgba(0,0,0,.12);}
.rel-card img{width:100%;height:180px;object-fit:cover;}
.rel-meta{padding:12px;}
.rel-meta h5{font-size:.9rem;font-weight:600;color:#222;margin-bottom:4px;}
.rel-meta .price{color:var(--leaf-green);font-weight:700;font-size:.95rem;}

.toast-container{position:fixed;bottom:24px;right:24px;z-index:9999;}
</style>

<div class="detail-page">
  <div class="container">

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrap">
      <a href="<?= base_url() ?>">Home</a> ›
      <a href="<?= base_url('products') ?>">Products</a> ›
      <?php if ($product['category_name']): ?>
        <a href="<?= base_url('products?category=' . urlencode($product['slug'])) ?>">
          <?= esc($product['category_name']) ?>
        </a> ›
      <?php endif; ?>
      <span><?= esc($product['name']) ?></span>
    </div>

    <!-- Main grid -->
    <div class="product-grid">

      <!-- Image -->
      <div class="image-panel">
        <div class="main-img-wrap">
          <img src="<?= esc($product['image']) ?>"
               alt="<?= esc($product['name']) ?>"
               id="mainImg"
               onerror="this.src='https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600'">
        </div>
      </div>

      <!-- Info -->
      <div class="info-panel">
        <?php if ($product['category_name']): ?>
          <div class="product-category"><?= esc($product['category_name']) ?></div>
        <?php endif; ?>

        <h1 class="product-title"><?= esc($product['name']) ?></h1>

        <!-- Price -->
        <div class="price-block">
          <span class="price-new">₹<?= number_format($price, 2) ?></span>
          <?php if ($product['sale_price'] > 0): ?>
            <span class="price-old">₹<?= number_format($product['price'], 2) ?></span>
            <span class="save-chip">Save <?= $saving ?>%</span>
          <?php endif; ?>
        </div>

        <!-- Stock -->
        <div class="stock-badge <?= $product['stock'] > 0 ? 'in' : 'out' ?>">
          <?php if ($product['stock'] > 0): ?>
            <i class="fas fa-check-circle me-1"></i> In Stock (<?= $product['stock'] ?> units)
          <?php else: ?>
            <i class="fas fa-times-circle me-1"></i> Out of Stock
          <?php endif; ?>
        </div>

        <!-- Description -->
        <p class="product-desc"><?= esc($product['description'] ?? 'Premium quality tea, carefully sourced and blended for an exceptional cup every time.') ?></p>

        <!-- Meta pills -->
        <div class="meta-pills">
          <span class="meta-pill"><i class="fas fa-leaf"></i> 100% Natural</span>
          <span class="meta-pill"><i class="fas fa-truck"></i> Ships in 2-3 days</span>
          <span class="meta-pill"><i class="fas fa-undo"></i> 14-day returns</span>
          <span class="meta-pill"><i class="fas fa-shield-alt"></i> Secure checkout</span>
        </div>

        <hr class="section-divider">

        <!-- Qty + Add to cart -->
        <?php if ($product['stock'] > 0): ?>
          <div class="qty-row">
            <div class="qty-ctrl">
              <button class="qty-btn" onclick="changeQty(-1)">−</button>
              <span class="qty-num" id="qtyNum">1</span>
              <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
            <button class="btn-add-cart" id="addToCartBtn"
                    data-id="<?= $product['id'] ?>"
                    data-name="<?= esc($product['name']) ?>"
                    onclick="addWithQty()">
              <i class="fas fa-cart-plus me-2"></i>Add to Cart
            </button>
            <button class="btn-wishlist" title="Add to Wishlist">
              <i class="far fa-heart"></i>
            </button>
          </div>
        <?php else: ?>
          <button class="btn-add-cart" disabled>Out of Stock</button>
        <?php endif; ?>

        <hr class="section-divider">

        <!-- Trust badges -->
        <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:.83rem;color:#888;">
          <span><i class="fas fa-credit-card me-1" style="color:var(--leaf-green)"></i>Razorpay / COD</span>
          <span><i class="fas fa-headset me-1" style="color:var(--leaf-green)"></i>Support 9am–6pm</span>
          <span><i class="fas fa-certificate me-1" style="color:var(--leaf-green)"></i>Quality Certified</span>
        </div>

      </div><!-- /info-panel -->
    </div><!-- /product-grid -->
  </div>
</div>

<!-- Related Products -->
<?php if (! empty($related)): ?>
<section class="related-section" style="background:#f8f8f8;">
  <div class="container">
    <h2 style="font-size:1.5rem;font-weight:700;margin-bottom:28px;text-align:center;">You May Also Like</h2>
    <div class="related-grid">
      <?php foreach (array_slice($related, 0, 4) as $r):
        if ($r['id'] === $product['id']) continue;
        $rPrice = ($r['sale_price'] > 0) ? $r['sale_price'] : $r['price'];
      ?>
        <div class="rel-card">
          <a href="<?= base_url('products/' . $r['slug']) ?>">
            <img src="<?= esc($r['image']) ?>" alt="<?= esc($r['name']) ?>"
                 onerror="this.src='https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300'">
          </a>
          <div class="rel-meta">
            <h5><?= esc($r['name']) ?></h5>
            <div class="price">₹<?= number_format($rPrice, 2) ?></div>
            <button class="add-to-cart-btn"
                    data-id="<?= $r['id'] ?>"
                    data-name="<?= esc($r['name']) ?>"
                    style="margin-top:8px;width:100%;padding:7px;background:var(--leaf-green);color:#fff;border:none;border-radius:20px;cursor:pointer;font-size:.82rem;font-weight:600;">
              Add to Cart
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<div class="toast-container" id="toastContainer"></div>

<script>
let qty = 1;
const maxQty = <?= (int)$product['stock'] ?>;

function changeQty(delta) {
  qty = Math.max(1, Math.min(maxQty, qty + delta));
  document.getElementById('qtyNum').textContent = qty;
}

function addWithQty() {
  const btn = document.getElementById('addToCartBtn');
  const pid  = btn.dataset.id;
  const name = btn.dataset.name;
  const orig = btn.innerHTML;

  btn.disabled  = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding…';

  fetch(BASE_URL + 'cart/add', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded','X-Requested-With':'XMLHttpRequest'},
    body: `product_id=${pid}&qty=${qty}`,
  })
  .then(r => r.json())
  .then(d => {
    if (d.status === 'ok') {
      document.querySelectorAll('#cartBadge,.cart-badge').forEach(el => el.textContent = d.cart_count);
      showToast(`"${name}" added to cart!`, 'success');
    } else {
      showToast(d.message || 'Error adding to cart.', 'error');
    }
  })
  .catch(() => showToast('Network error. Try again.', 'error'))
  .finally(() => {
    btn.disabled  = false;
    btn.innerHTML = orig;
  });
}
</script>

<?= view('layouts/footer') ?>
