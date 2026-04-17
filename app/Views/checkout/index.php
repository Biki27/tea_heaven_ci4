<?php
/**
 * Views/checkout/index.php
 * Variables: $items, $subtotal, $tax, $shipping, $discount, $total, $user, $cartCount, $razorpay_key
 */
$pageTitle = 'Checkout';
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<!-- Razorpay SDK -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  // Globals consumed by payment.js
  const RAZORPAY_KEY = '<?= esc($razorpay_key ?? '') ?>';
</script>
<script src="<?= base_url('js/payment.js') ?>"></script>

<style>
:root{--leaf-green:#6b8e23;--warm-brown:#4e342e;}
.checkout-page{padding:36px 0 80px;background:#f9fafb;}

/* Progress bar */
.progress-steps{display:flex;justify-content:space-between;align-items:center;margin-bottom:36px;position:relative;}
.progress-steps::before{content:'';position:absolute;top:18px;left:0;right:0;height:3px;background:#e5e7eb;z-index:0;}
.step{display:flex;flex-direction:column;align-items:center;z-index:1;gap:6px;}
.step-circle{width:38px;height:38px;border-radius:50%;background:#e5e7eb;color:#aaa;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;transition:.3s;}
.step.done .step-circle{background:var(--leaf-green);color:#fff;}
.step.active .step-circle{background:var(--leaf-green);color:#fff;box-shadow:0 0 0 4px rgba(107,142,35,.2);}
.step-label{font-size:.75rem;color:#888;font-weight:600;text-transform:uppercase;letter-spacing:.5px;}
.step.active .step-label{color:var(--leaf-green);}

/* Section cards */
.section-card{background:#fff;border-radius:14px;padding:26px;margin-bottom:20px;box-shadow:0 4px 18px rgba(0,0,0,.07);}
.section-card h2{font-size:1.1rem;font-weight:700;margin-bottom:18px;padding-bottom:10px;border-bottom:1px solid #f0f0f0;color:#333;}
.form-label{font-size:.85rem;font-weight:600;color:#444;margin-bottom:4px;}
.form-control,.form-select{border-radius:8px;border:1px solid #ddd;padding:10px 14px;font-size:.9rem;transition:.2s;}
.form-control:focus,.form-select:focus{border-color:var(--leaf-green);box-shadow:0 0 0 3px rgba(107,142,35,.15);}

/* Payment options */
.payment-opt{display:flex;align-items:center;gap:12px;padding:14px 18px;border:2px solid #eee;border-radius:10px;cursor:pointer;transition:.3s;margin-bottom:10px;}
.payment-opt:hover{border-color:var(--leaf-green);background:#f6fbf0;}
.payment-opt input[type=radio]{accent-color:var(--leaf-green);width:18px;height:18px;}
.payment-opt label{cursor:pointer;font-weight:600;font-size:.9rem;color:#333;display:flex;align-items:center;gap:8px;margin:0;flex:1;}
.payment-opt.selected{border-color:var(--leaf-green);background:#f6fbf0;}
.pay-icon{font-size:1.2rem;}

/* Order summary sidebar */
.summary-sidebar{position:sticky;top:90px;}
.summary-card{background:#fff;border-radius:16px;padding:24px;box-shadow:0 6px 25px rgba(0,0,0,.09);}
.order-item-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f5f5f5;}
.order-item-row:last-child{border:none;}
.order-item-img{width:52px;height:52px;border-radius:8px;object-fit:cover;flex-shrink:0;}
.order-item-name{flex:1;font-size:.88rem;font-weight:600;color:#333;}
.order-item-price{font-size:.9rem;font-weight:700;color:var(--leaf-green);}
.order-item-qty{font-size:.78rem;color:#999;margin-top:2px;}
.total-row{display:flex;justify-content:space-between;padding:8px 0;font-size:.92rem;color:#555;}
.total-row.grand{font-size:1.15rem;font-weight:700;color:var(--warm-brown);border-top:2px solid var(--leaf-green);margin-top:6px;padding-top:12px;}
.total-row.grand span:last-child{color:var(--leaf-green);}
.discount-row{color:var(--leaf-green);}

.pay-btn{width:100%;padding:16px;background:linear-gradient(135deg,var(--leaf-green),#90c695);color:#fff;border:none;border-radius:50px;font-size:1.1rem;font-weight:700;cursor:pointer;transition:.3s;box-shadow:0 8px 25px rgba(107,142,35,.3);margin-top:16px;}
.pay-btn:hover{transform:translateY(-2px);box-shadow:0 14px 35px rgba(107,142,35,.4);}
.pay-btn:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.trust-note{display:flex;align-items:center;justify-content:center;gap:6px;font-size:.78rem;color:#999;margin-top:10px;}

/* Alert */
.alert-danger{background:#fde8e8;color:#c62828;padding:12px 16px;border-radius:8px;font-size:.88rem;margin-bottom:16px;}
</style>

<div class="checkout-page">
  <div class="container">

    <!-- Progress -->
    <div class="progress-steps">
      <div class="step done">
        <div class="step-circle"><i class="fas fa-check"></i></div>
        <span class="step-label">Cart</span>
      </div>
      <div class="step active">
        <div class="step-circle">2</div>
        <span class="step-label">Details</span>
      </div>
      <div class="step">
        <div class="step-circle">3</div>
        <span class="step-label">Payment</span>
      </div>
    </div>

    <?php if ($error = session()->getFlashdata('error')): ?>
      <div class="alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <!-- ── LEFT: Forms ──────────────────────────────────── -->
      <div class="col-lg-7">

        <!-- Contact Information -->
        <div class="section-card">
          <h2><i class="fas fa-user-circle me-2 text-success"></i>Contact Information</h2>
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">First Name*</label>
              <input type="text" class="form-control" id="firstName" name="first_name"
                     value="<?= esc($user['name'] ? explode(' ', $user['name'])[0] : '') ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Last Name*</label>
              <input type="text" class="form-control" id="lastName" name="last_name"
                     value="<?= esc(strpos($user['name'] ?? '', ' ') !== false ? substr($user['name'], strpos($user['name'], ' ') + 1) : '') ?>" required>
            </div>
            <div class="col-sm-7">
              <label class="form-label">Email*</label>
              <input type="email" class="form-control" id="emailField" name="email"
                     value="<?= esc($user['email'] ?? '') ?>" required>
            </div>
            <div class="col-sm-5">
              <label class="form-label">Phone*</label>
              <input type="tel" class="form-control" id="phoneField" name="phone"
                     value="<?= esc($user['phone'] ?? '') ?>" required>
            </div>
          </div>
        </div>

        <!-- Shipping Address -->
        <div class="section-card">
          <h2><i class="fas fa-map-marker-alt me-2 text-success"></i>Shipping Address</h2>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Street Address*</label>
              <input type="text" class="form-control" id="addressField" name="address"
                     value="<?= esc($user['address'] ?? '') ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">City*</label>
              <input type="text" class="form-control" id="cityField" name="city"
                     value="<?= esc($user['city'] ?? '') ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">PIN Code*</label>
              <input type="text" class="form-control" id="pincodeField" name="pincode"
                     value="<?= esc($user['pincode'] ?? '') ?>" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Country</label>
              <select class="form-select" id="countryField" name="country">
                <option value="India" selected>India</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Order Notes (optional)</label>
              <textarea class="form-control" name="notes" rows="2" placeholder="Any special delivery instructions..."></textarea>
            </div>
          </div>
        </div>

        <!-- Payment Method -->
        <div class="section-card">
          <h2><i class="fas fa-credit-card me-2 text-success"></i>Payment Method</h2>

          <div class="payment-opt" onclick="selectPayment(this,'razorpay')">
            <input type="radio" name="payment_method" value="razorpay" id="pm_razorpay" checked>
            <label for="pm_razorpay">
              <span class="pay-icon">💳</span> Credit / Debit Card &amp; UPI (Razorpay)
            </label>
            <img src="https://razorpay.com/favicon.png" height="20" alt="">
          </div>

          <div class="payment-opt" onclick="selectPayment(this,'upi')">
            <input type="radio" name="payment_method" value="upi" id="pm_upi">
            <label for="pm_upi">
              <span class="pay-icon">📲</span> UPI / Net Banking (COD-style)
            </label>
          </div>

          <div class="payment-opt" onclick="selectPayment(this,'cod')">
            <input type="radio" name="payment_method" value="cod" id="pm_cod">
            <label for="pm_cod">
              <span class="pay-icon">💵</span> Cash on Delivery
            </label>
          </div>
        </div>

      </div><!-- /col-lg-7 -->

      <!-- ── RIGHT: Order Summary ─────────────────────────── -->
      <div class="col-lg-5">
        <div class="summary-sidebar">
          <div class="summary-card">
            <h2 style="font-size:1.05rem;font-weight:700;margin-bottom:16px;border-bottom:1px solid #f0f0f0;padding-bottom:10px;">Order Summary</h2>

            <!-- Items -->
            <?php foreach ($items as $item):
              $price = $item['sale_price'] > 0 ? $item['sale_price'] : $item['price'];
            ?>
              <div class="order-item-row">
                <img class="order-item-img"
                     src="<?= esc($item['image']) ?>"
                     alt="<?= esc($item['name']) ?>"
                     onerror="this.src='https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=80'">
                <div class="order-item-name">
                  <?= esc($item['name']) ?>
                  <div class="order-item-qty">Qty: <?= $item['quantity'] ?></div>
                </div>
                <div class="order-item-price">₹<?= number_format($price * $item['quantity'], 2) ?></div>
              </div>
            <?php endforeach; ?>

            <!-- Totals -->
            <div class="total-row"><span>Subtotal</span><span>₹<?= number_format($subtotal, 2) ?></span></div>
            <div class="total-row"><span>GST (5%)</span><span>₹<?= number_format($tax, 2) ?></span></div>
            <div class="total-row"><span>Shipping</span><span>₹<?= number_format($shipping, 2) ?></span></div>
            <?php if ($discount > 0): ?>
              <div class="total-row discount-row"><span>Discount</span><span>−₹<?= number_format($discount, 2) ?></span></div>
            <?php endif; ?>
            <div class="total-row grand">
              <span>Total</span>
              <span>₹<?= number_format($total, 2) ?></span>
            </div>

            <button class="pay-btn" id="payBtn" onclick="handlePayment()">
              <i class="fas fa-lock me-2"></i>
              Pay ₹<?= number_format($total, 2) ?>
            </button>
            <div class="trust-note">
              <i class="fas fa-shield-alt"></i> 256-bit SSL secured &nbsp;|&nbsp;
              <i class="fas fa-undo"></i> 14-day returns
            </div>
          </div>
        </div>
      </div>
    </div><!-- /row -->
  </div>
</div>

<!-- Hidden COD form (submitted when not Razorpay) -->
<form id="codForm" action="<?= base_url('order/place') ?>" method="POST" style="display:none">
  <?= csrf_field() ?>
  <input type="hidden" name="first_name"      id="hd_first_name">
  <input type="hidden" name="last_name"       id="hd_last_name">
  <input type="hidden" name="email"           id="hd_email">
  <input type="hidden" name="phone"           id="hd_phone">
  <input type="hidden" name="address"         id="hd_address">
  <input type="hidden" name="city"            id="hd_city">
  <input type="hidden" name="pincode"         id="hd_pincode">
  <input type="hidden" name="country"         id="hd_country">
  <input type="hidden" name="notes"           id="hd_notes">
  <input type="hidden" name="payment_method"  id="hd_pm" value="cod">
</form>

<!-- Razorpay verify form -->
<form id="rzpVerifyForm" action="<?= base_url('payment/verify') ?>" method="POST" style="display:none">
  <?= csrf_field() ?>
  <input type="hidden" name="razorpay_payment_id" id="rzp_payment_id">
  <input type="hidden" name="razorpay_order_id"   id="rzp_order_id">
  <input type="hidden" name="razorpay_signature"  id="rzp_signature">
  <input type="hidden" name="order_id"            id="rzp_internal_id">
</form>

<script>
// Order total (used by payment.js)
const ORDER_TOTAL = <?= (int)round($total * 100) ?>;
</script>

<?= view('layouts/footer') ?>
