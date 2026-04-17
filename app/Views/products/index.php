<?php
/**
 * Views/products/index.php
 * Variables: $products, $categories, $filters, $total, $page, $perPage, $cartCount
 */
$pageTitle = 'Shop All Teas';
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
:root{--leaf-green:#6b8e23;}
.shop-wrap{display:grid;grid-template-columns:260px 1fr;gap:28px;padding:36px 0 60px;}
@media(max-width:900px){.shop-wrap{grid-template-columns:1fr;}}

/* ── Sidebar ─────────────────────────────────────────────── */
.filter-sidebar{position:sticky;top:90px;font-size:.9rem;}
.facet{background:#fafafa;border:1px solid #eee;padding:16px;border-radius:10px;margin-bottom:14px;}
.facet h4{font-weight:700;font-size:.85rem;text-transform:uppercase;letter-spacing:.6px;margin-bottom:12px;color:#333;}
.facet label{display:flex;align-items:center;gap:8px;margin-bottom:8px;cursor:pointer;color:#555;}
.facet label input{accent-color:var(--leaf-green);}
.facet select{width:100%;padding:8px 10px;border:1px solid #ddd;border-radius:6px;font-size:.88rem;accent-color:var(--leaf-green);}

/* ── Product grid ────────────────────────────────────────── */
.products-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.products-header .count{color:#888;font-size:.9rem;}
.prod-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
@media(max-width:1200px){.prod-grid{grid-template-columns:repeat(3,1fr);}}
@media(max-width:800px) {.prod-grid{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px) {.prod-grid{grid-template-columns:1fr;}}

.prod-card{background:#fff;border-radius:10px;box-shadow:0 4px 18px rgba(0,0,0,.07);overflow:hidden;display:flex;flex-direction:column;transition:.3s;}
.prod-card:hover{transform:translateY(-6px);box-shadow:0 14px 35px rgba(0,0,0,.13);}
.prod-card .media{position:relative;overflow:hidden;aspect-ratio:1/1;}
.prod-card .media img{width:100%;height:100%;object-fit:cover;transition:.4s;}
.prod-card:hover .media img{transform:scale(1.06);}
.prod-badge{position:absolute;top:10px;right:10px;padding:3px 12px;border-radius:20px;font-size:.72rem;font-weight:700;color:#fff;}
.badge-sale{background:#e53935;}
.badge-new{background:var(--leaf-green);}
.badge-popular{background:#d4af37;}

.prod-meta{padding:14px;flex:1;display:flex;flex-direction:column;}
.prod-name{font-weight:600;font-size:.95rem;color:#222;margin-bottom:6px;text-decoration:none;}
.prod-name:hover{color:var(--leaf-green);}
.prod-price{margin-bottom:12px;}
.price-new{color:var(--leaf-green);font-weight:700;}
.price-old{text-decoration:line-through;color:#aaa;font-size:.85rem;margin-left:6px;}
.btn-add{margin-top:auto;padding:9px;background:var(--leaf-green);color:#fff;border:none;border-radius:25px;font-size:.85rem;font-weight:600;cursor:pointer;transition:.2s;width:100%;}
.btn-add:hover{background:#5a7a1a;}

/* ── Pagination ──────────────────────────────────────────── */
.pagination-wrap{display:flex;justify-content:center;gap:8px;margin-top:40px;}
.page-btn{padding:8px 16px;border:1px solid #ddd;border-radius:8px;text-decoration:none;color:#555;font-size:.88rem;transition:.2s;}
.page-btn:hover,.page-btn.active{background:var(--leaf-green);color:#fff;border-color:var(--leaf-green);}

/* Empty state */
.empty-state{text-align:center;padding:80px 20px;color:#888;}
.empty-state i{font-size:3rem;margin-bottom:16px;color:#ccc;}

/* Toast */
.toast-container{position:fixed;bottom:24px;right:24px;z-index:9999;}
</style>

<div class="container">
  <div class="shop-wrap">

    <!-- ── SIDEBAR ─────────────────────────────────────────── -->
    <aside class="filter-sidebar">
      <form id="filterForm" method="GET" action="<?= base_url('products') ?>">

        <div class="facet">
          <h4>Category</h4>
          <?php foreach ($categories as $cat): ?>
            <label>
              <input type="radio" name="category"
                     value="<?= esc($cat['slug']) ?>"
                     <?= ($filters['category'] === $cat['slug']) ? 'checked' : '' ?>
                     onchange="this.form.submit()">
              <?= esc($cat['name']) ?>
            </label>
          <?php endforeach; ?>
          <label>
            <input type="radio" name="category" value=""
                   <?= empty($filters['category']) ? 'checked' : '' ?>
                   onchange="this.form.submit()">
            All Categories
          </label>
        </div>

        <div class="facet">
          <h4>Price (₹)</h4>
          <label><input type="radio" name="price_max" value=""    onchange="this.form.submit()" <?= empty($filters['price_max']) ? 'checked' : '' ?>> All Prices</label>
          <label><input type="radio" name="price_max" value="100" onchange="this.form.submit()" <?= $filters['price_max']==='100'?'checked':'' ?>> Under ₹100</label>
          <label><input type="radio" name="price_max" value="300" onchange="this.form.submit()" <?= $filters['price_max']==='300'?'checked':'' ?>> Under ₹300</label>
          <label><input type="radio" name="price_max" value="500" onchange="this.form.submit()" <?= $filters['price_max']==='500'?'checked':'' ?>> Under ₹500</label>
        </div>

        <div class="facet">
          <h4>Sort By</h4>
          <select name="sort" onchange="this.form.submit()">
            <option value="popular"    <?= $filters['sort']==='popular'   ?'selected':'' ?>>Most Popular</option>
            <option value="new"        <?= $filters['sort']==='new'       ?'selected':'' ?>>Newest First</option>
            <option value="price-asc"  <?= $filters['sort']==='price-asc' ?'selected':'' ?>>Price: Low to High</option>
            <option value="price-desc" <?= $filters['sort']==='price-desc'?'selected':'' ?>>Price: High to Low</option>
          </select>
        </div>

        <!-- Keep page reset on filter change -->
        <input type="hidden" name="page" value="1">
      </form>
    </aside>

    <!-- ── PRODUCT SECTION ─────────────────────────────────── -->
    <section>
      <div class="products-header">
        <span class="count">Showing <?= count($products) ?> of <?= $total ?> products</span>
        <?php if (! empty($filters['category']) || ! empty($filters['price_max'])): ?>
          <a href="<?= base_url('products') ?>" style="font-size:.85rem;color:var(--leaf-green);text-decoration:none;">
            <i class="fas fa-times-circle me-1"></i>Clear Filters
          </a>
        <?php endif; ?>
      </div>

      <?php if (empty($products)): ?>
        <div class="empty-state">
          <i class="fas fa-mug-hot"></i>
          <h4>No teas found</h4>
          <p>Try adjusting your filters.</p>
          <a href="<?= base_url('products') ?>" class="btn-add" style="width:auto;display:inline-block;padding:10px 28px;text-decoration:none;">View All Teas</a>
        </div>
      <?php else: ?>
        <div class="prod-grid">
          <?php foreach ($products as $p): ?>
            <?php $price = ($p['sale_price'] > 0) ? $p['sale_price'] : $p['price']; ?>
            <article class="prod-card">
              <a class="media" href="<?= base_url('products/' . $p['slug']) ?>">
                <img src="<?= esc($p['image']) ?>" alt="<?= esc($p['name']) ?>" loading="lazy">
                <?php if ($p['badge']): ?>
                  <?php
                  $badgeClass = match($p['badge']) {
                    'Sale'    => 'badge-sale',
                    'New'     => 'badge-new',
                    'Popular' => 'badge-popular',
                    default   => 'badge-new',
                  };
                  ?>
                  <span class="prod-badge <?= $badgeClass ?>"><?= esc($p['badge']) ?></span>
                <?php endif; ?>
              </a>
              <div class="prod-meta">
                <a class="prod-name" href="<?= base_url('products/' . $p['slug']) ?>"><?= esc($p['name']) ?></a>
                <div class="prod-price">
                  <span class="price-new">₹<?= number_format($price, 2) ?></span>
                  <?php if ($p['sale_price'] > 0): ?>
                    <span class="price-old">₹<?= number_format($p['price'], 2) ?></span>
                  <?php endif; ?>
                </div>
                <button class="btn-add add-to-cart-btn"
                        data-id="<?= $p['id'] ?>"
                        data-name="<?= esc($p['name']) ?>">
                  <i class="fas fa-cart-plus me-1"></i> Add to Cart
                </button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php
        $totalPages = ceil($total / $perPage);
        if ($totalPages > 1):
          $q = http_build_query(array_merge($filters, ['page' => '']));
        ?>
        <div class="pagination-wrap">
          <?php if ($page > 1): ?>
            <a class="page-btn" href="?<?= $q ?><?= $page - 1 ?>">‹ Prev</a>
          <?php endif; ?>
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="page-btn <?= $i === $page ? 'active' : '' ?>"
               href="?<?= $q ?><?= $i ?>"><?= $i ?></a>
          <?php endfor; ?>
          <?php if ($page < $totalPages): ?>
            <a class="page-btn" href="?<?= $q ?><?= $page + 1 ?>">Next ›</a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      <?php endif; ?>
    </section>

  </div><!-- /shop-wrap -->
</div>

<div class="toast-container" id="toastContainer"></div>

<?= view('layouts/footer') ?>
