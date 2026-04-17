<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice <?= esc($order['order_number']) ?></title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:#333;padding:40px;background:#fff;}
.invoice-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:40px;}
.brand{font-size:1.6rem;font-weight:800;color:#6b8e23;}
.brand-tagline{font-size:.85rem;color:#999;margin-top:4px;}
.invoice-meta{text-align:right;}
.invoice-meta h2{font-size:1.2rem;font-weight:700;color:#333;}
.invoice-meta p{font-size:.85rem;color:#888;margin-top:3px;}
.divider{border:none;border-top:2px solid #f0f0f0;margin:24px 0;}
.address-grid{display:grid;grid-template-columns:1fr 1fr;gap:30px;margin-bottom:30px;}
.address-box h4{font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.7px;color:#aaa;margin-bottom:8px;}
.address-box p{font-size:.9rem;line-height:1.7;color:#555;}
table{width:100%;border-collapse:collapse;margin-bottom:30px;}
thead tr{background:#6b8e23;color:#fff;}
thead th{padding:12px 16px;text-align:left;font-size:.85rem;font-weight:600;}
tbody tr{border-bottom:1px solid #f0f0f0;}
tbody tr:nth-child(even){background:#fafafa;}
tbody td{padding:12px 16px;font-size:.88rem;color:#444;}
.text-right{text-align:right;}
.totals-table{width:300px;margin-left:auto;}
.totals-table td{padding:7px 0;font-size:.9rem;}
.totals-table .grand td{font-size:1.1rem;font-weight:700;border-top:2px solid #6b8e23;padding-top:12px;color:#333;}
.totals-table .grand td:last-child{color:#6b8e23;}
.status-badge{display:inline-block;padding:3px 14px;border-radius:20px;font-size:.78rem;font-weight:700;color:#fff;text-transform:capitalize;}
.footer-note{text-align:center;font-size:.82rem;color:#aaa;margin-top:40px;border-top:1px solid #f0f0f0;padding-top:20px;}
@media print{body{padding:20px;}.no-print{display:none;}}
</style>
</head>
<body>

<button class="no-print" onclick="window.print()"
        style="position:fixed;top:20px;right:20px;background:#6b8e23;color:#fff;border:none;padding:10px 24px;border-radius:8px;cursor:pointer;font-size:.9rem;font-weight:600;">
  🖨️ Print Invoice
</button>

<!-- Header -->
<div class="invoice-header">
  <div>
    <div class="brand">🍃 Tea Haven</div>
    <div class="brand-tagline">Premium Organic Teas · teahaven.in</div>
    <div style="margin-top:12px;font-size:.85rem;color:#888;">
      Kolkata, West Bengal, India<br>
      GSTIN: 19XXXXX0000X1ZX
    </div>
  </div>
  <div class="invoice-meta">
    <h2>INVOICE</h2>
    <p><strong><?= esc($order['order_number']) ?></strong></p>
    <p>Date: <?= date('d M Y', strtotime($order['created_at'])) ?></p>
    <p>
      <span class="status-badge"
            style="background:<?= $order['payment_status']==='paid'?'#10b981':'#f59e0b' ?>">
        <?= esc(ucfirst($order['payment_status'])) ?>
      </span>
    </p>
  </div>
</div>

<hr class="divider">

<!-- Addresses -->
<div class="address-grid">
  <div class="address-box">
    <h4>Bill / Ship To</h4>
    <p>
      <strong><?= esc($order['first_name'] . ' ' . $order['last_name']) ?></strong><br>
      <?= esc($order['address']) ?><br>
      <?= esc($order['city']) ?> – <?= esc($order['pincode']) ?><br>
      <?= esc($order['country']) ?><br>
      <?= esc($order['phone']) ?><br>
      <?= esc($order['email']) ?>
    </p>
  </div>
  <div class="address-box">
    <h4>Payment Details</h4>
    <p>
      Method: <?= esc(strtoupper($order['payment_method'] ?? '—')) ?><br>
      Status: <?= esc(ucfirst($order['payment_status'])) ?><br>
      <?php if ($order['razorpay_payment_id']): ?>
        Txn ID: <?= esc($order['razorpay_payment_id']) ?>
      <?php endif; ?>
    </p>
  </div>
</div>

<!-- Items table -->
<table>
  <thead>
    <tr>
      <th>#</th>
      <th>Product</th>
      <th class="text-right">Unit Price</th>
      <th class="text-right">Qty</th>
      <th class="text-right">Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $i => $item): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= esc($item['product_name']) ?></td>
        <td class="text-right">₹<?= number_format($item['price'], 2) ?></td>
        <td class="text-right"><?= $item['quantity'] ?></td>
        <td class="text-right">₹<?= number_format($item['subtotal'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- Totals -->
<table class="totals-table">
  <tr><td>Subtotal</td><td class="text-right">₹<?= number_format($order['subtotal'], 2) ?></td></tr>
  <tr><td>GST (5%)</td><td class="text-right">₹<?= number_format($order['tax'], 2) ?></td></tr>
  <tr><td>Shipping</td><td class="text-right">₹<?= number_format($order['shipping'], 2) ?></td></tr>
  <?php if ($order['discount'] > 0): ?>
    <tr><td style="color:#6b8e23;">Discount</td><td class="text-right" style="color:#6b8e23;">−₹<?= number_format($order['discount'], 2) ?></td></tr>
  <?php endif; ?>
  <tr class="grand"><td>Total</td><td class="text-right">₹<?= number_format($order['total'], 2) ?></td></tr>
</table>

<div class="footer-note">
  Thank you for shopping with Tea Haven! For any queries, contact us at hello@teahaven.in<br>
  This is a computer-generated invoice and does not require a signature.
</div>

</body>
</html>
