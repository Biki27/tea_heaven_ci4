<?php

/**
 * Views/cart/index.php
 * Variables: $items (array), $subtotal, $tax, $total, $cartCount
 */
$pageTitle = 'Your Cart';
$shipping  = 50.00;
$discount  = (float)(session()->get('promo_discount') ?? 0);
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
  :root {
    --leaf-green: #6b8e23;
    --tea-gold: #d4af37;
    --warm-brown: #4e342e;
  }

  .cart-page {
    padding: 40px 0 80px;
  }

  .cart-header-block {
    background: linear-gradient(135deg, var(--leaf-green), #90c695);
    color: #fff;
    padding: 32px;
    border-radius: 20px;
    margin-bottom: 32px;
    text-align: center;
    box-shadow: 0 10px 40px rgba(107, 142, 35, .3);
  }

  .cart-header-block h1 {
    font-size: 1.9rem;
    font-weight: 700;
    margin: 0;
  }

  .cart-header-block p {
    margin: 6px 0 0;
    opacity: .9;
  }

  /* Cart items */
  .cart-item {
    background: #fff;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, .07);
    transition: .3s;
  }

  .cart-item:hover {
    box-shadow: 0 12px 35px rgba(0, 0, 0, .13);
    transform: translateY(-2px);
  }

  .cart-img {
    width: 95px;
    height: 95px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
  }

  .cart-details {
    flex: 1;
    min-width: 0;
  }

  .cart-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--warm-brown);
    margin-bottom: 4px;
  }

  .cart-unit-price {
    color: #888;
    font-size: .9rem;
    margin-bottom: 8px;
  }

  .cart-line-total {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--leaf-green);
  }

  .qty-control {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f5f5f5;
    padding: 6px 10px;
    border-radius: 30px;
  }

  .qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: #fff;
    border-radius: 50%;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: .2s;
    box-shadow: 0 2px 6px rgba(0, 0, 0, .1);
  }

  .qty-btn:hover {
    background: var(--leaf-green);
    color: #fff;
  }

  .qty-val {
    width: 40px;
    text-align: center;
    font-weight: 700;
    font-size: 1rem;
    border: none;
    background: transparent;
  }

  .remove-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f8f8f8;
    border: none;
    color: #bbb;
    cursor: pointer;
    transition: .3s;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .remove-btn:hover {
    background: #ff4757;
    color: #fff;
  }

  /* Empty state */
  .empty-cart {
    text-align: center;
    padding: 80px 20px;
  }

  .empty-cart i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 20px;
  }

  .empty-cart h3 {
    color: #555;
    margin-bottom: 10px;
  }

  .empty-cart a {
    background: var(--leaf-green);
    color: #fff;
    padding: 12px 32px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    margin-top: 10px;
  }

  /* Summary card */
  .summary-card {
    background: #fff;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .09);
    position: sticky;
    top: 100px;
  }

  .promo-wrap {
    background: #f5f5f5;
    padding: 16px;
    border-radius: 14px;
    margin-bottom: 20px;
  }

  .promo-wrap label {
    font-weight: 700;
    font-size: .9rem;
    display: block;
    margin-bottom: 10px;
  }

  .promo-row {
    display: flex;
  }

  .promo-input {
    flex: 1;
    padding: 10px 14px;
    border: 2px solid #e0e0e0;
    border-radius: 25px 0 0 25px;
    outline: none;
    font-size: .9rem;
    transition: .2s;
  }

  .promo-input:focus {
    border-color: var(--leaf-green);
  }

  .promo-btn {
    padding: 10px 20px;
    background: var(--leaf-green);
    color: #fff;
    border: none;
    border-radius: 0 25px 25px 0;
    cursor: pointer;
    font-weight: 600;
    font-size: .9rem;
    transition: .2s;
  }

  .promo-btn:hover {
    background: #5a7a1a;
  }

  .promo-msg {
    font-size: .82rem;
    margin-top: 6px;
  }

  .promo-msg.success {
    color: var(--leaf-green);
  }

  .promo-msg.error {
    color: #e53935;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: .95rem;
    color: #555;
  }

  .summary-row:last-child {
    border: none;
  }

  .summary-total {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--warm-brown);
  }

  .summary-total span {
    color: var(--leaf-green);
  }

  .checkout-btn {
    display: block;
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--leaf-green), #90c695);
    color: #fff;
    border: none;
    border-radius: 50px;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    margin-top: 20px;
    box-shadow: 0 8px 25px rgba(107, 142, 35, .3);
    transition: .3s;
  }

  .checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 35px rgba(107, 142, 35, .4);
    color: #fff;
  }

  .continue-link {
    display: block;
    text-align: center;
    margin-top: 12px;
    font-size: .88rem;
    color: var(--leaf-green);
    text-decoration: none;
  }

  .continue-link:hover {
    text-decoration: underline;
  }

  .discount-row {
    color: var(--leaf-green);
    font-weight: 600;
  }

  .toast-container {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
  }
</style>

<div class="cart-page">
  <div class="container">
    <!-- Header -->
    <div class="cart-header-block">
      <h1><i class="fas fa-shopping-bag me-2"></i>Shopping Cart</h1>
      <p>Review your tea selection before checkout</p>
    </div>

    <?php if (empty($items)): ?>
      <div class="empty-cart">
        <i class="fas fa-shopping-bag"></i>
        <h3>Your cart is empty</h3>
        <p>Looks like you haven't added any teas yet.</p>
        <a href="<?= base_url('products') ?>"><i class="fas fa-mug-hot me-2"></i>Browse Teas</a>
      </div>

    <?php else: ?>

      <div class="row g-4">
        <!-- ── Cart Items ────────────────────────────────────── -->
        <div class="col-lg-8">
          <div id="cartItemsWrapper">
            <?php foreach ($items as $item):
              $price    = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
              $lineTotal = $price * $item['quantity'];
            ?>
              <div class="cart-item" id="cartRow<?= $item['id'] ?>">
                <img src="<?= esc($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="cart-img"
                  onerror="this.src='<?= base_url('assets/images/placeholder.png') ?>'">
                <div class="cart-details">
                  <div class="cart-name"><?= esc($item['name']) ?></div>
                  <div class="cart-unit-price">₹<?= number_format($price, 2) ?> / unit</div>
                  <div class="qty-control">
                    <button class="qty-btn" onclick="changeQty(<?= $item['id'] ?>, -1)">−</button>
                    <span class="qty-val" id="qty<?= $item['id'] ?>"><?= $item['quantity'] ?></span>
                    <button class="qty-btn" onclick="changeQty(<?= $item['id'] ?>, 1)">+</button>
                  </div>
                </div>
                <div class="cart-line-total" id="lineTotal<?= $item['id'] ?>">
                  ₹<?= number_format($lineTotal, 2) ?>
                </div>
                <!-- Remove Button -->
                 <!-- add toast confirmation -->
                <button class="remove-btn" type="button" onclick="removeItem(<?= $item['id'] ?>)" title="Remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- ── Summary ──────────────────────────────────────── -->
        <div class="col-lg-4">
          <div class="summary-card">

            <!-- Promo code -->
            <div class="promo-wrap">
              <label><i class="fas fa-tag me-2"></i>Have a promo code?</label>
              <div class="promo-row">
                <input type="text" class="promo-input" id="promoInput"
                  placeholder="Enter code"
                  value="<?= esc(session()->get('promo_code') ?? '') ?>">
                <button class="promo-btn" onclick="applyPromo()">Apply</button>
              </div>
              <div class="promo-msg" id="promoMsg">
                <?php if (session()->get('promo_discount')): ?>
                  <span class="success">✓ Code applied! Saving ₹<?= number_format(session()->get('promo_discount'), 2) ?></span>
                <?php endif; ?>
              </div>
            </div>

            <!-- Totals -->
            <div class="summary-row">
              <span>Subtotal</span>
              <span id="summarySubtotal">₹<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="summary-row">
              <span>GST (5%)</span>
              <span id="summaryTax">₹<?= number_format($tax, 2) ?></span>
            </div>
            <div class="summary-row">
              <span>Shipping</span>
              <span>₹<?= number_format($shipping, 2) ?></span>
            </div>
            <?php if ($discount > 0): ?>
              <div class="summary-row discount-row">
                <span>Discount</span>
                <span id="summaryDiscount">−₹<?= number_format($discount, 2) ?></span>
              </div>
            <?php endif; ?>
            <div class="summary-row summary-total">
              <span>Total</span>
              <span id="summaryTotal">₹<?= number_format($total, 2) ?></span>
            </div>

            <a href="<?= base_url('checkout') ?>" class="checkout-btn">
              <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
            </a>
            <a href="<?= base_url('products') ?>" class="continue-link">
              ← Continue Shopping
            </a>
          </div>
        </div>
      </div><!-- /row -->

    <?php endif; ?>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script>
  const BASE = '<?= base_url() ?>';

  // Update quantity (+1 / -1 delta)
  function changeQty(cartId, delta) {
    const el = document.getElementById('qty' + cartId);
    const qty = Math.max(0, parseInt(el.textContent) + delta);

    fetch(BASE + 'cart/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: `cart_id=${cartId}&qty=${qty}`
      })
      .then(r => r.json())
      .then(d => {
        if (d.status === 'ok') {
          if (qty === 0) {
            document.getElementById('cartRow' + cartId)?.remove();
          } else {
            el.textContent = qty;
            updateSummary(d);
          }
          updateCartBadge(d.cart_count);
          if (qty === 0) checkEmpty();
        }
      });
  }

  function removeItem(cartId) {
    Swal.fire({
      title: 'Remove item?',
      text: "Are you sure you want to remove this tea from your cart?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e53935',  
      cancelButtonColor: '#888',
      confirmButtonText: 'Yes, remove it',
      background: '#faf7f2',  
      customClass: {
          confirmButton: 'rounded-pill px-4',
          cancelButton: 'rounded-pill px-4'
      }
    }).then((result) => {
      // 2. Only proceed if the user clicked "Yes"
      if (result.isConfirmed) {
        fetch(BASE + 'cart/remove', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: `cart_id=${cartId}`
          })
          .then(r => r.json())
          .then(d => {
            // Smoothly fade out the row before removing it
            const row = document.getElementById('cartRow' + cartId);
            if (row) {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'scale(0.95)';
                setTimeout(() => row.remove(), 300);
            }
            
            updateSummary(d);
            updateCartBadge(d.cart_count);
            
            // Show a success toast after removal
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Item removed from cart',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                background: '#faf7f2'
            });

            // Check if cart is empty after the animation finishes
            setTimeout(checkEmpty, 350);
          });
      }
    });
  }

  function applyPromo() {
    const code = document.getElementById('promoInput').value.trim();
    const totalEl = document.getElementById('summaryTotal');
    const raw = totalEl?.textContent.replace(/[₹,]/g, '').trim() || '0';

    fetch(BASE + 'cart/promo', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: `code=${encodeURIComponent(code)}&total=${raw}`
      })
      .then(r => r.json())
      .then(d => {
        const msg = document.getElementById('promoMsg');
        if (d.status === 'ok') {
          msg.innerHTML = `<span class="success">✓ ${d.message}</span>`;
          if (totalEl) totalEl.textContent = '₹' + d.new_total;
        } else {
          msg.innerHTML = `<span class="error">✗ ${d.message}</span>`;
        }
      });
  }

  function updateSummary(d) {
    document.getElementById('summarySubtotal') && (document.getElementById('summarySubtotal').textContent = '₹' + d.subtotal);
    document.getElementById('summaryTax') && (document.getElementById('summaryTax').textContent = '₹' + d.tax);
    document.getElementById('summaryTotal') && (document.getElementById('summaryTotal').textContent = '₹' + d.total);
  }

  function updateCartBadge(count) {
    document.querySelectorAll('#cartBadge').forEach(el => el.textContent = count);
  }

  function checkEmpty() {
    const rows = document.querySelectorAll('.cart-item');
    
    if (rows.length === 0) {
      const cartContainer = document.querySelector('.cart-page .container');
      
      // Briefly fade out the container
      cartContainer.style.transition = 'opacity 0.3s ease';
      cartContainer.style.opacity = '0';
      
      setTimeout(() => {
        // Swap out the entire cart interface for the Empty State HTML
        cartContainer.innerHTML = `
          <div class="cart-header-block">
            <h1><i class="fas fa-shopping-bag me-2"></i>Shopping Cart</h1>
            <p>Review your tea selection before checkout</p>
          </div>
          <div class="empty-cart">
            <i class="fas fa-shopping-bag"></i>
            <h3>Your cart is empty</h3>
            <p>Looks like you haven't added any teas yet.</p>
            <a href="${BASE}products"><i class="fas fa-mug-hot me-2"></i>Browse Teas</a>
          </div>
        `;
        
        // Fade the container back in smoothly
        cartContainer.style.opacity = '1';
      },  300);
    }
  }
</script>

<?= view('layouts/footer') ?>