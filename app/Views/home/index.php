<?php
/**
 * Views/home/index.php
 * Dynamic home page — replaces new-home.html
 * Variables: $newArrivals (array), $bestSellers (array), $cartCount (int)
 */
$pageTitle = 'Premium Organic Teas';
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
/* ── Hero / Banner ─────────────────────────────────────────── */
.slide-banner{position:relative;height:90vh;overflow:hidden;background:#1a1a1a;display:flex;align-items:center;justify-content:center;}
.bg-video{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.45;}
.hero-overlay{position:relative;z-index:2;text-align:center;color:#fff;padding:0 20px;}
.hero-overlay h1{font-size:clamp(2rem,5vw,4rem);font-weight:700;text-shadow:0 2px 20px rgba(0,0,0,.5);}
.hero-overlay p{font-size:1.15rem;margin:16px 0 32px;opacity:.9;}
.hero-btn{background:var(--leaf-green);color:#fff;padding:14px 36px;border-radius:40px;text-decoration:none;font-weight:600;font-size:1rem;transition:.3s;display:inline-block;}
.hero-btn:hover{background:#5a7a1a;color:#fff;transform:translateY(-2px);box-shadow:0 8px 25px rgba(107,142,35,.4);}

/* Slides */
.slide-dots{position:absolute;bottom:24px;left:50%;transform:translateX(-50%);display:flex;gap:8px;z-index:3;}
.dot{width:10px;height:10px;border-radius:50%;background:rgba(255,255,255,.5);cursor:pointer;transition:.3s;}
.dot.active{background:#fff;width:28px;border-radius:6px;}

/* ── New Arrivals ───────────────────────────────────────────── */
.new-arrivals-section{padding:80px 5%;background:#d8d6d3;text-align:center;}
.section-title{font-size:2.2rem;font-weight:600;margin-bottom:56px;color:#333;}
.arrivals-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:36px;}
.arrival-card{position:relative;background:#f0f0f0;padding:36px 18px;transition:.3s;cursor:pointer;border-radius:4px;}
.arrival-card:hover{background:#e5e5e5;transform:translateY(-6px);box-shadow:0 16px 40px rgba(0,0,0,.1);}
.sale-badge{position:absolute;top:10px;right:10px;background:#e53935;color:#fff;padding:3px 14px;font-size:.75rem;border-radius:20px;font-weight:600;}
.new-badge{position:absolute;top:10px;right:10px;background:var(--leaf-green);color:#fff;padding:3px 14px;font-size:.75rem;border-radius:20px;font-weight:600;}
.pop-badge{position:absolute;top:10px;right:10px;background:var(--tea-gold);color:#fff;padding:3px 14px;font-size:.75rem;border-radius:20px;font-weight:600;}
.image-circle{width:200px;height:200px;margin:0 auto 22px;border-radius:50%;overflow:hidden;background:#ddd;}
.image-circle img{width:100%;height:100%;object-fit:cover;}
.arrival-card h3{font-size:1rem;font-weight:600;color:#222;margin-bottom:8px;}
.price-line .new-price{color:#6b8e23;font-weight:700;margin-right:8px;}
.price-line .old-price{text-decoration:line-through;color:#aaa;font-size:.9rem;}
.arrival-card .add-to-cart-btn{margin-top:14px;padding:8px 22px;background:var(--leaf-green);color:#fff;border:none;border-radius:25px;cursor:pointer;font-size:.85rem;font-weight:600;transition:.25s;width:100%;}
.arrival-card .add-to-cart-btn:hover{background:#5a7a1a;}

/* ── Best Sellers ───────────────────────────────────────────── */
.best-seller-section{padding:80px 0;background:#fff;}
.bs-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:22px;}
.bs-card{border-radius:10px;overflow:hidden;box-shadow:0 4px 18px rgba(0,0,0,.07);background:#fff;transition:.3s;}
.bs-card:hover{transform:translateY(-6px);box-shadow:0 16px 35px rgba(0,0,0,.13);}
.bs-image{position:relative;overflow:hidden;}
.bs-image img{width:100%;height:240px;object-fit:cover;transition:.5s;}
.bs-card:hover .bs-image img{transform:scale(1.07);}
.bs-hover{position:absolute;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;opacity:0;transition:.35s;}
.bs-card:hover .bs-hover{opacity:1;}
.bs-hover button{background:var(--leaf-green);color:#fff;border:none;padding:10px 24px;border-radius:25px;font-weight:600;cursor:pointer;transition:.2s;}
.bs-hover button:hover{background:#5a7a1a;}
.bs-meta{padding:14px 16px;}
.bs-meta h5{font-size:1rem;font-weight:600;color:#222;margin-bottom:6px;}
.bs-meta .new-price{color:var(--leaf-green);font-weight:700;margin-right:8px;}
.bs-meta .old-price{text-decoration:line-through;color:#aaa;font-size:.88rem;}

/* ── About ──────────────────────────────────────────────────── */
.about-section{padding:90px 0;background:linear-gradient(rgba(0,0,0,.65),rgba(0,0,0,.65)),url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=1400') center/cover fixed;color:#fff;}
.about-wrapper{display:flex;align-items:center;gap:56px;}
.about-left{flex:1;}
.about-content h2{font-size:2.2rem;font-weight:700;margin-bottom:18px;}
.about-content p{font-size:1rem;line-height:1.8;opacity:.9;margin-bottom:14px;}
.about-btn{background:var(--leaf-green);color:#fff;padding:12px 30px;border-radius:30px;text-decoration:none;font-weight:600;display:inline-block;margin-top:8px;transition:.3s;}
.about-btn:hover{background:#5a7a1a;color:#fff;transform:translateY(-2px);}
.about-right{flex:1;}
.about-right img{width:100%;border-radius:14px;box-shadow:0 20px 50px rgba(0,0,0,.3);}

/* ── New Arrivals (card hover) ──────────────────────────────── */
.tea-category-section{padding:80px 0;background:#f8f8f8;}
.tea-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:0;}
.tea-card{position:relative;overflow:hidden;aspect-ratio:3/4;}
.tea-card img{width:100%;height:100%;object-fit:cover;transition:.5s;}
.tea-card:hover img{transform:scale(1.08);}
.tea-overlay{position:absolute;inset:0;background:rgba(0,0,0,.5);display:flex;flex-direction:column;align-items:center;justify-content:center;opacity:0;transition:.35s;}
.tea-card:hover .tea-overlay{opacity:1;}
.tea-overlay h3{color:#fff;font-size:1.3rem;font-weight:700;margin-bottom:12px;}
.tea-overlay a{background:var(--leaf-green);color:#fff;padding:8px 24px;border-radius:25px;text-decoration:none;font-weight:600;transition:.2s;}
.tea-overlay a:hover{background:#5a7a1a;}

/* ── Responsive ─────────────────────────────────────────────── */
@media(max-width:1100px){.arrivals-grid,.bs-grid{grid-template-columns:repeat(2,1fr);}.tea-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:768px){.about-wrapper{flex-direction:column;}.arrivals-grid,.bs-grid,.tea-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.arrivals-grid,.bs-grid{grid-template-columns:1fr;}}

/* Toast */
.toast-container{position:fixed;bottom:24px;right:24px;z-index:9999;}
</style>

<!-- ── HERO BANNER ─────────────────────────────────────────── -->
<section class="slide-banner">
  <video autoplay muted loop playsinline class="bg-video">
    <source src="<?= base_url('media/tea.mp4') ?>" type="video/mp4">
  </video>
  <div class="hero-overlay">
    <h1 id="heroTitle">Premium Black Tea</h1>
    <p id="heroSubtitle">Rich, bold flavours from the finest Darjeeling estates</p>
    <a href="<?= base_url('products') ?>" class="hero-btn">Shop Now</a>
  </div>
  <div class="slide-dots">
    <span class="dot active" data-slide="0"></span>
    <span class="dot" data-slide="1"></span>
    <span class="dot" data-slide="2"></span>
  </div>
</section>

<!-- ── NEW ARRIVALS ───────────────────────────────────────── -->
<section class="new-arrivals-section">
  <h2 class="section-title">New Arrivals</h2>
  <div class="arrivals-grid">
    <?php foreach ($newArrivals as $p): ?>
      <?php $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price']; ?>
      <div class="arrival-card">
        <?php if ($p['badge']): ?>
          <span class="<?= $p['badge'] === 'Sale' ? 'sale-badge' : ($p['badge'] === 'New' ? 'new-badge' : 'pop-badge') ?>"><?= esc($p['badge']) ?></span>
        <?php endif; ?>
        <a href="<?= base_url('products/' . $p['slug']) ?>">
          <div class="image-circle">
            <img src="<?= esc($p['image']) ?>" alt="<?= esc($p['name']) ?>" loading="lazy">
          </div>
        </a>
        <!-- <div class="image-circle">
          <img src="<?= esc($p['image']) ?>" alt="<?= esc($p['name']) ?>" loading="lazy">
        </div> -->
        <h3><?= esc($p['name']) ?></h3>
        <div class="price-line">
          <span class="new-price">₹<?= number_format($price, 2) ?></span>
          <?php if ($p['sale_price'] > 0): ?>
            <span class="old-price">₹<?= number_format($p['price'], 2) ?></span>
          <?php endif; ?>
        </div>
        <button class="add-to-cart-btn"
                data-id="<?= $p['id'] ?>"
                data-name="<?= esc($p['name']) ?>">
          Add to Cart
        </button>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ── BEST SELLERS ───────────────────────────────────────── -->
<section class="best-seller-section">
  <div class="container">
    <h2 class="section-title">Best Sellers</h2>
    <div class="bs-grid">
      <?php foreach ($bestSellers as $p): ?>
        <?php $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price']; ?>
        <div class="bs-card">
          <a href="<?= base_url('products/' . $p['slug']) ?>">
            <div class="bs-image">
              <img src="<?= esc($p['image']) ?>" alt="<?= esc($p['name']) ?>" loading="lazy">
              <div class="bs-hover">
                <button class="add-to-cart-btn"
                        data-id="<?= $p['id'] ?>"
                        data-name="<?= esc($p['name']) ?>">
                  Add to Cart
                </button>
              </div>
            </div>
          </a>
          <div class="bs-meta">
            <h5><?= esc($p['name']) ?></h5>
            <div>
              <span class="new-price">₹<?= number_format($price, 2) ?></span>
              <?php if ($p['sale_price'] > 0): ?>
                <span class="old-price">₹<?= number_format($p['price'], 2) ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ── ABOUT ──────────────────────────────────────────────── -->
<section class="about-section" id="about">
  <div class="container">
    <div class="about-wrapper">
      <div class="about-left">
        <div class="about-content">
          <h2>About Our Tea Haven</h2>
          <p>We bring you the finest handpicked tea leaves sourced directly from the most renowned tea gardens across India and beyond.</p>
          <p>Every blend is crafted with passion to ensure premium quality, rich aroma, and an unforgettable tea experience in every cup.</p>
          <a href="<?= base_url('products') ?>" class="about-btn">Explore All Teas</a>
        </div>
      </div>
      <div class="about-right">
        <img src="https://images.unsplash.com/photo-1523920290228-4f321a939b4c?w=600" alt="Tea Garden">
      </div>
    </div>
  </div>
</section>

<!-- ── CATEGORY GRID ─────────────────────────────────────── -->
<section class="tea-category-section">
  <div class="container">
    <h2 class="section-title">Shop by Category</h2>
  </div>
  <div class="tea-grid">
    <?php
    $cats = [
      ['label'=>'Black Tea',   'slug'=>'black-tea',   'img'=>'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=500'],
      ['label'=>'Masala Chai', 'slug'=>'masala-chai', 'img'=>'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=500'],
      ['label'=>'Herbal Tea',  'slug'=>'herbal-tea',  'img'=>'https://th.bing.com/th/id/OIP.3TpyV8jc_W7U-JVyicsX9QHaDt?w=313&h=174&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3'],
      ['label'=>'Green Tea',   'slug'=>'green-tea',   'img'=>'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=500'],
    ];
    foreach ($cats as $c): ?>
      <div class="tea-card">
        <img src="<?= $c['img'] ?>" alt="<?= $c['label'] ?>">
        <div class="tea-overlay">
          <h3><?= $c['label'] ?></h3>
          <a href="<?= base_url('products?category=' . $c['slug']) ?>">Shop Now</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Toast container -->
<div class="toast-container" id="toastContainer"></div>

<script>
// ── Hero slides ────────────────────────────────────────────
const slides = [
  {title:'Premium Black Tea',   sub:'Rich, bold flavours from the finest Darjeeling estates'},
  {title:'Organic Green Tea',   sub:'Fresh antioxidant-rich leaves hand-picked daily'},
  {title:'Herbal Infusions',    sub:'Caffeine-free wellness blends for every mood'},
];
let current = 0;
const dots  = document.querySelectorAll('.dot');

function goToSlide(i) {
  current = i;
  document.getElementById('heroTitle').textContent    = slides[i].title;
  document.getElementById('heroSubtitle').textContent = slides[i].sub;
  dots.forEach((d,idx) => d.classList.toggle('active', idx === i));
}
dots.forEach(d => d.addEventListener('click', () => goToSlide(+d.dataset.slide)));
setInterval(() => goToSlide((current + 1) % slides.length), 4500);
</script>

<?= view('layouts/footer') ?>
