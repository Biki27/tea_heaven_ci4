<?php
/**
 * Views/orders/history.php
 * Variables: $orders (array), $cartCount
 */
$pageTitle = 'My Orders';
$statusColors = [
  'pending'    => '#f59e0b',
  'confirmed'  => '#3b82f6',
  'processing' => '#8b5cf6',
  'shipped'    => '#06b6d4',
  'delivered'  => '#10b981',
  'cancelled'  => '#ef4444',
];
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
:root{--leaf-green:#6b8e23;}
.orders-page{padding:40px 0 80px;}
.page-hero{background:linear-gradient(135deg,var(--leaf-green),#90c695);color:#fff;padding:40px;border-radius:20px;margin-bottom:36px;text-align:center;}
.page-hero h1{font-size:2rem;font-weight:700;margin:0;}
.page-hero p{margin:8px 0 0;opacity:.9;}

.order-card{background:#fff;border-radius:16px;padding:24px;margin-bottom:18px;box-shadow:0 4px 18px rgba(0,0,0,.07);transition:.3s;}
.order-card:hover{box-shadow:0 12px 35px rgba(0,0,0,.12);transform:translateY(-2px);}
.order-top{display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:16px;}
.order-num{font-size:1.05rem;font-weight:700;color:#222;}
.order-date{font-size:.84rem;color:#888;margin-top:3px;}
.status-chip{padding:4px 14px;border-radius:20px;font-size:.78rem;font-weight:700;color:#fff;text-transform:capitalize;}
.order-items-preview{display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;}
.order-item-thumb{width:52px;height:52px;border-radius:8px;object-fit:cover;border:2px solid #f0f0f0;}
.more-items{width:52px;height:52px;border-radius:8px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;font-size:.8rem;color:#888;font-weight:700;}
.order-footer{display:flex;justify-content:space-between;align-items:center;padding-top:14px;border-top:1px solid #f5f5f5;flex-wrap:wrap;gap:10px;}
.order-total{font-size:1.15rem;font-weight:700;color:var(--leaf-green);}
.btn-view{padding:8px 22px;background:var(--leaf-green);color:#fff;border-radius:25px;text-decoration:none;font-size:.85rem;font-weight:600;transition:.2s;display:inline-flex;align-items:center;gap:6px;}
.btn-view:hover{background:#5a7a1a;color:#fff;}

.empty-orders{text-align:center;padding:80px 20px;}
.empty-orders i{font-size:4rem;color:#ddd;margin-bottom:20px;}
.btn-shop{background:var(--leaf-green);color:#fff;padding:12px 32px;border-radius:30px;text-decoration:none;font-weight:600;display:inline-block;margin-top:10px;}
</style>

<div class="orders-page">
  <div class="container">
    <div class="page-hero">
      <h1><i class="fas fa-box-open me-2"></i>My Orders</h1>
      <p>Track and manage all your Tea Haven orders</p>
    </div>

    <?php if (empty($orders)): ?>
      <div class="empty-orders">
        <i class="fas fa-box"></i>
        <h3>No orders yet</h3>
        <p>You haven't placed any orders. Start shopping!</p>
        <a href="<?= base_url('products') ?>" class="btn-shop">Browse Teas</a>
      </div>
    <?php else: ?>
      <?php foreach ($orders as $order): ?>
        <div class="order-card">
          <div class="order-top">
            <div>
              <div class="order-num"><?= esc($order['order_number']) ?></div>
              <div class="order-date">
                <i class="fas fa-calendar-alt me-1"></i>
                <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?>
              </div>
            </div>
            <div>
              <span class="status-chip"
                    style="background:<?= $statusColors[$order['status']] ?? '#888' ?>">
                <?= esc(ucfirst($order['status'])) ?>
              </span>
              <div style="margin-top:6px;text-align:right;">
                <small style="color:<?= $order['payment_status']==='paid'?'#10b981':'#f59e0b' ?>;font-weight:600;">
                  <i class="fas fa-<?= $order['payment_status']==='paid'?'check-circle':'clock' ?> me-1"></i>
                  Payment: <?= esc(ucfirst($order['payment_status'])) ?>
                </small>
              </div>
            </div>
          </div>

          <div class="order-footer">
            <div class="order-total">
              Total: ₹<?= number_format($order['total'], 2) ?>
            </div>
            <div style="display:flex;gap:10px;flex-wrap:wrap;">
              <a href="<?= base_url('orders/' . $order['id']) ?>" class="btn-view">
                <i class="fas fa-eye"></i> View Details
              </a>
              <a href="<?= base_url('orders/' . $order['id'] . '/invoice') ?>"
                 class="btn-view" style="background:#555;" target="_blank">
                <i class="fas fa-file-invoice"></i> Invoice
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>

<?= view('layouts/footer') ?>
