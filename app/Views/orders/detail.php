<?php
/**
 * Views/orders/detail.php
 * Variables: $order (array), $items (array), $cartCount
 */
$pageTitle = 'Order ' . $order['order_number'];
$statusColors = [
  'pending'=>'#f59e0b','confirmed'=>'#3b82f6','processing'=>'#8b5cf6',
  'shipped'=>'#06b6d4','delivered'=>'#10b981','cancelled'=>'#ef4444',
];
$steps = ['pending','confirmed','processing','shipped','delivered'];
$currentStep = array_search($order['status'], $steps);
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
:root{--leaf-green:#6b8e23;--warm-brown:#4e342e;}
.order-detail-page{padding:40px 0 80px;}

/* Status tracker */
.status-tracker{display:flex;align-items:center;justify-content:center;margin-bottom:36px;flex-wrap:wrap;gap:0;}
.tracker-step{display:flex;flex-direction:column;align-items:center;gap:8px;position:relative;flex:1;min-width:80px;}
.tracker-step::after{content:'';position:absolute;top:20px;left:60%;width:80%;height:3px;background:#e5e7eb;z-index:0;}
.tracker-step:last-child::after{display:none;}
.tracker-step.done::after,.tracker-step.active::after{background:var(--leaf-green);}
.tracker-circle{width:42px;height:42px;border-radius:50%;border:3px solid #e5e7eb;background:#fff;display:flex;align-items:center;justify-content:center;font-size:1rem;color:#ccc;z-index:1;position:relative;transition:.3s;}
.tracker-step.done .tracker-circle{border-color:var(--leaf-green);background:var(--leaf-green);color:#fff;}
.tracker-step.active .tracker-circle{border-color:var(--leaf-green);color:var(--leaf-green);box-shadow:0 0 0 4px rgba(107,142,35,.2);}
.tracker-label{font-size:.72rem;font-weight:600;color:#aaa;text-transform:uppercase;letter-spacing:.5px;text-align:center;}
.tracker-step.done .tracker-label,.tracker-step.active .tracker-label{color:var(--leaf-green);}

.info-card{background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 18px rgba(0,0,0,.07);margin-bottom:20px;}
.info-card h3{font-size:1rem;font-weight:700;color:#333;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f0f0f0;}
.info-row{display:flex;gap:8px;margin-bottom:10px;font-size:.9rem;}
.info-label{color:#888;min-width:130px;flex-shrink:0;}
.info-val{color:#333;font-weight:500;}

.item-row{display:flex;align-items:center;gap:16px;padding:14px 0;border-bottom:1px solid #f5f5f5;}
.item-row:last-child{border:none;}
.item-img{width:64px;height:64px;border-radius:10px;object-fit:cover;flex-shrink:0;}
.item-name{flex:1;font-weight:600;color:#222;font-size:.95rem;}
.item-qty{font-size:.85rem;color:#888;margin-top:2px;}
.item-price{font-weight:700;color:var(--leaf-green);}

.total-rows .row-line{display:flex;justify-content:space-between;padding:8px 0;font-size:.9rem;color:#555;border-bottom:1px solid #f8f8f8;}
.total-rows .row-line.grand{font-size:1.1rem;font-weight:700;color:var(--warm-brown);border-top:2px solid var(--leaf-green);margin-top:6px;padding-top:12px;border-bottom:none;}
.total-rows .row-line.grand span:last-child{color:var(--leaf-green);}

.badge-status{padding:5px 16px;border-radius:20px;color:#fff;font-weight:700;font-size:.82rem;text-transform:capitalize;}
.btn-back{display:inline-flex;align-items:center;gap:6px;padding:9px 22px;border:2px solid var(--leaf-green);color:var(--leaf-green);border-radius:25px;text-decoration:none;font-weight:600;font-size:.88rem;transition:.2s;margin-bottom:24px;}
.btn-back:hover{background:var(--leaf-green);color:#fff;}
.btn-invoice{display:inline-flex;align-items:center;gap:6px;padding:9px 22px;background:var(--leaf-green);color:#fff;border-radius:25px;text-decoration:none;font-weight:600;font-size:.88rem;transition:.2s;margin-left:10px;}
.btn-invoice:hover{background:#5a7a1a;color:#fff;}
</style>

<div class="order-detail-page">
  <div class="container">

    <!-- Back + Invoice buttons -->
    <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
      <a href="<?= base_url('orders') ?>" class="btn-back">
        <i class="fas fa-arrow-left"></i> All Orders
      </a>
      <a href="<?= base_url('orders/' . $order['id'] . '/invoice') ?>" class="btn-invoice" target="_blank">
        <i class="fas fa-file-invoice"></i> Download Invoice
      </a>
    </div>

    <!-- Order number + status -->
    <div class="info-card" style="text-align:center;padding:28px;">
      <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:6px;"><?= esc($order['order_number']) ?></h2>
      <span class="badge-status" style="background:<?= $statusColors[$order['status']] ?? '#888' ?>">
        <?= esc(ucfirst($order['status'])) ?>
      </span>
      <p style="color:#888;font-size:.87rem;margin-top:8px;">
        Placed on <?= date('d M Y \a\t h:i A', strtotime($order['created_at'])) ?>
      </p>
    </div>

    <!-- Tracker (hide if cancelled) -->
    <?php if ($order['status'] !== 'cancelled' && $currentStep !== false): ?>
    <div class="info-card">
      <div class="status-tracker">
        <?php foreach ($steps as $i => $s): ?>
          <div class="tracker-step <?= $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : '') ?>">
            <div class="tracker-circle">
              <?php if ($i < $currentStep): ?>
                <i class="fas fa-check"></i>
              <?php else: ?>
                <?= ['<i class="fas fa-clock"></i>','<i class="fas fa-check-circle"></i>','<i class="fas fa-cog"></i>','<i class="fas fa-truck"></i>','<i class="fas fa-home"></i>'][$i] ?>
              <?php endif; ?>
            </div>
            <span class="tracker-label"><?= esc(ucfirst($s)) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-lg-7">

        <!-- Order items -->
        <div class="info-card">
          <h3><i class="fas fa-shopping-bag me-2 text-success"></i>Items Ordered</h3>
          <?php foreach ($items as $item): ?>
            <div class="item-row">
              <img class="item-img"
                   src="<?= esc($item['product_image']) ?>"
                   alt="<?= esc($item['product_name']) ?>"
                   onerror="this.src='https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=100'">
              <div style="flex:1;">
                <div class="item-name"><?= esc($item['product_name']) ?></div>
                <div class="item-qty">Qty: <?= $item['quantity'] ?> × ₹<?= number_format($item['price'], 2) ?></div>
              </div>
              <div class="item-price">₹<?= number_format($item['subtotal'], 2) ?></div>
            </div>
          <?php endforeach; ?>

          <!-- Totals -->
          <div class="total-rows" style="margin-top:16px;">
            <div class="row-line"><span>Subtotal</span><span>₹<?= number_format($order['subtotal'],2) ?></span></div>
            <div class="row-line"><span>GST</span><span>₹<?= number_format($order['tax'],2) ?></span></div>
            <div class="row-line"><span>Shipping</span><span>₹<?= number_format($order['shipping'],2) ?></span></div>
            <?php if ($order['discount'] > 0): ?>
              <div class="row-line" style="color:var(--leaf-green)"><span>Discount</span><span>−₹<?= number_format($order['discount'],2) ?></span></div>
            <?php endif; ?>
            <div class="row-line grand"><span>Total</span><span>₹<?= number_format($order['total'],2) ?></span></div>
          </div>
        </div>

      </div>

      <div class="col-lg-5">

        <!-- Shipping address -->
        <div class="info-card">
          <h3><i class="fas fa-map-marker-alt me-2 text-success"></i>Shipping Address</h3>
          <div class="info-row"><span class="info-label">Name</span><span class="info-val"><?= esc($order['first_name'] . ' ' . $order['last_name']) ?></span></div>
          <div class="info-row"><span class="info-label">Email</span><span class="info-val"><?= esc($order['email']) ?></span></div>
          <div class="info-row"><span class="info-label">Phone</span><span class="info-val"><?= esc($order['phone'] ?: '—') ?></span></div>
          <div class="info-row"><span class="info-label">Address</span><span class="info-val"><?= esc($order['address']) ?>, <?= esc($order['city']) ?> - <?= esc($order['pincode']) ?>, <?= esc($order['country']) ?></span></div>
          <?php if ($order['notes']): ?>
            <div class="info-row"><span class="info-label">Notes</span><span class="info-val"><?= esc($order['notes']) ?></span></div>
          <?php endif; ?>
        </div>

        <!-- Payment info -->
        <div class="info-card">
          <h3><i class="fas fa-credit-card me-2 text-success"></i>Payment</h3>
          <div class="info-row"><span class="info-label">Method</span><span class="info-val"><?= esc(strtoupper($order['payment_method'] ?? '—')) ?></span></div>
          <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-val" style="color:<?= $order['payment_status']==='paid'?'#10b981':'#f59e0b' ?>;font-weight:700;">
              <?= esc(ucfirst($order['payment_status'])) ?>
            </span>
          </div>
          <?php if ($order['razorpay_payment_id']): ?>
            <div class="info-row"><span class="info-label">Payment ID</span><span class="info-val" style="font-size:.8rem;"><?= esc($order['razorpay_payment_id']) ?></span></div>
          <?php endif; ?>
        </div>

      </div>
    </div><!-- /row -->
  </div>
</div>

<?= view('layouts/footer') ?>
