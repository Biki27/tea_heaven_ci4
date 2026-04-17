/**
 * payment.js — Tea Haven Razorpay Integration
 * ─────────────────────────────────────────────────────────────
 * Handles:
 *  1. Checkout form validation (client-side)
 *  2. Collecting shipping form data
 *  3. Creating a Razorpay order via POST /payment/create-order
 *  4. Opening the Razorpay checkout modal
 *  5. Submitting the verify form on payment success
 *  6. COD / UPI fallback (standard form POST)
 *  7. Payment method tab switching
 *
 * Requires:
 *  - Razorpay SDK: https://checkout.razorpay.com/v1/checkout.js
 *  - Globals set by the checkout view:
 *      const BASE_URL     = '<?= base_url() ?>';
 *      const RAZORPAY_KEY = '<?= getenv("RAZORPAY_KEY_ID") ?>';
 *      const ORDER_TOTAL  = <total in paise>;
 *      const USER_NAME    = '...';
 *      const USER_EMAIL   = '...';
 *      const USER_PHONE   = '...';
 */

/* ═══════════════════════════════════════════════════════════
   1. FORM VALIDATION
═══════════════════════════════════════════════════════════ */

const REQUIRED_FIELDS = [
  { id: 'firstName',    label: 'First Name' },
  { id: 'lastName',     label: 'Last Name'  },
  { id: 'emailField',   label: 'Email'      },
  { id: 'phoneField',   label: 'Phone'      },
  { id: 'addressField', label: 'Address'    },
  { id: 'cityField',    label: 'City'       },
  { id: 'pincodeField', label: 'PIN Code'   },
];

/**
 * Validate all required fields.
 * Highlights invalid ones and returns true only if all pass.
 */
function validateCheckoutForm() {
  let valid = true;

  REQUIRED_FIELDS.forEach(({ id, label }) => {
    const el = document.getElementById(id);
    if (! el) return;

    const val = el.value.trim();
    if (! val) {
      markInvalid(el, `${label} is required.`);
      valid = false;
    } else if (id === 'emailField' && ! /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
      markInvalid(el, 'Please enter a valid email address.');
      valid = false;
    } else if (id === 'phoneField' && val.replace(/\D/g, '').length < 10) {
      markInvalid(el, 'Please enter a valid 10-digit phone number.');
      valid = false;
    } else if (id === 'pincodeField' && val.replace(/\D/g, '').length < 6) {
      markInvalid(el, 'Please enter a valid 6-digit PIN code.');
      valid = false;
    } else {
      markValid(el);
    }
  });

  return valid;
}

function markInvalid(el, message) {
  el.style.borderColor = '#ef4444';
  el.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.15)';

  // Show/update error message below the field
  let msg = el.nextElementSibling;
  if (! msg || ! msg.classList.contains('field-error')) {
    msg = document.createElement('div');
    msg.className = 'field-error';
    msg.style.cssText = 'color:#ef4444;font-size:.78rem;margin-top:4px;';
    el.parentNode.insertBefore(msg, el.nextSibling);
  }
  msg.textContent = message;

  // Scroll to first error
  el.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function markValid(el) {
  el.style.borderColor = '#6b8e23';
  el.style.boxShadow   = '0 0 0 3px rgba(107,142,35,.12)';
  const msg = el.nextElementSibling;
  if (msg && msg.classList.contains('field-error')) msg.remove();
}

// Clear validation state when user starts typing
document.addEventListener('DOMContentLoaded', () => {
  REQUIRED_FIELDS.forEach(({ id }) => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', () => markValid(el));
    }
  });
});

/* ═══════════════════════════════════════════════════════════
   2. COLLECT FORM DATA
═══════════════════════════════════════════════════════════ */

function getShippingData() {
  return {
    first_name: document.getElementById('firstName')?.value.trim()    || '',
    last_name:  document.getElementById('lastName')?.value.trim()     || '',
    email:      document.getElementById('emailField')?.value.trim()   || '',
    phone:      document.getElementById('phoneField')?.value.trim()   || '',
    address:    document.getElementById('addressField')?.value.trim() || '',
    city:       document.getElementById('cityField')?.value.trim()    || '',
    pincode:    document.getElementById('pincodeField')?.value.trim() || '',
    country:    document.getElementById('countryField')?.value        || 'India',
    notes:      document.querySelector('textarea[name=notes]')?.value || '',
  };
}

/* ═══════════════════════════════════════════════════════════
   3. PAYMENT METHOD SWITCHING
═══════════════════════════════════════════════════════════ */

function selectPayment(el, method) {
  document.querySelectorAll('.payment-opt').forEach(o => {
    o.classList.remove('selected');
    o.style.borderColor = '#eee';
    o.style.background  = '#fff';
  });
  el.classList.add('selected');
  el.style.borderColor = '#6b8e23';
  el.style.background  = '#f6fbf0';
  const radio = el.querySelector('input[type=radio]');
  if (radio) radio.checked = true;

  // Update pay button label
  updatePayButtonLabel(method);
}

function updatePayButtonLabel(method) {
  const btn = document.getElementById('payBtn');
  if (! btn) return;

  const labels = {
    razorpay: '💳 Pay with Razorpay',
    upi:      '📲 Place Order (UPI/Net Banking)',
    cod:      '💵 Place Order (Cash on Delivery)',
  };
  btn.innerHTML = `<i class="fas fa-lock me-2"></i>${labels[method] || 'Place Order'}`;
}

/* ═══════════════════════════════════════════════════════════
   4. MAIN PAYMENT HANDLER
═══════════════════════════════════════════════════════════ */

/**
 * Called when "Pay Now" button is clicked.
 * Branches based on selected payment method.
 */
function handlePayment() {
  if (! validateCheckoutForm()) return;

  const method = document.querySelector('input[name=payment_method]:checked')?.value || 'razorpay';
  const data   = getShippingData();

  if (method === 'razorpay') {
    launchRazorpay(data);
  } else {
    submitCodForm(data, method);
  }
}

/* ═══════════════════════════════════════════════════════════
   5. RAZORPAY FLOW
═══════════════════════════════════════════════════════════ */

/**
 * Step 1: Create a Razorpay Order on the server.
 * Step 2: Open the Razorpay checkout modal.
 * Step 3: On success, POST to /payment/verify.
 */
async function launchRazorpay(data) {
  const btn = document.getElementById('payBtn');
  setButtonLoading(btn, 'Creating secure payment…');

  try {
    const resp = await fetch((typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'payment/create-order', {
      method:  'POST',
      headers: {
        'Content-Type':     'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: JSON.stringify(data),
    });

    const orderData = await resp.json();

    if (orderData.status !== 'ok') {
      showPaymentError(orderData.message || 'Payment gateway error. Please try again.');
      resetButton(btn);
      return;
    }

    openRazorpayModal(orderData, data, btn);

  } catch (err) {
    console.error('[Payment] Create order failed:', err);
    showPaymentError('Network error. Please check your connection and try again.');
    resetButton(btn);
  }
}

/**
 * Open the Razorpay checkout popup.
 */
function openRazorpayModal(orderData, shippingData, btn) {
  const key = (typeof RAZORPAY_KEY !== 'undefined') ? RAZORPAY_KEY : '';

  if (! key) {
    showPaymentError('Razorpay key not configured. Please contact support.');
    resetButton(btn);
    return;
  }

  const options = {
    key:         key,
    amount:      orderData.amount,       // already in paise
    currency:    orderData.currency || 'INR',
    name:        'Tea Haven',
    description: 'Premium Tea Order — ' + orderData.order_id,
    image:       (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'images/logo.png',
    order_id:    orderData.razorpay_order_id,

    handler: function (response) {
      // Payment success — verify signature server-side
      submitVerifyForm(
        response.razorpay_payment_id,
        response.razorpay_order_id,
        response.razorpay_signature,
        orderData.order_id
      );
    },

    prefill: {
      name:    shippingData.first_name + ' ' + shippingData.last_name,
      email:   shippingData.email,
      contact: shippingData.phone,
    },

    notes: {
      address: shippingData.address + ', ' + shippingData.city,
    },

    theme: {
      color:      '#6b8e23',
      hide_topbar: false,
    },

    modal: {
      confirm_close: true,
      escape:        false,
      ondismiss: function () {
        resetButton(btn);
        // Don't show an error — user closed the modal intentionally
      },
    },

    retry: {
      enabled:     true,
      max_count:   3,
    },
  };

  try {
    const rzp = new Razorpay(options);

    rzp.on('payment.failed', function (response) {
      console.error('[Razorpay] Payment failed:', response.error);
      showPaymentError(
        response.error.description ||
        'Payment failed. Please try a different payment method.'
      );
      resetButton(btn);
    });

    rzp.open();
  } catch (e) {
    console.error('[Razorpay] Could not open modal:', e);
    showPaymentError('Unable to load payment gateway. Refresh and try again.');
    resetButton(btn);
  }
}

/**
 * Submit the hidden verify form to /payment/verify.
 */
function submitVerifyForm(paymentId, orderId, signature, internalOrderId) {
  const form = document.getElementById('rzpVerifyForm');
  if (! form) {
    // Fallback: redirect with query params (less secure — use only if form missing)
    window.location.href =
      (typeof BASE_URL !== 'undefined' ? BASE_URL : '/') + 'payment/verify?' +
      new URLSearchParams({ razorpay_payment_id: paymentId, razorpay_order_id: orderId,
                            razorpay_signature: signature, order_id: internalOrderId });
    return;
  }
  document.getElementById('rzp_payment_id').value  = paymentId;
  document.getElementById('rzp_order_id').value    = orderId;
  document.getElementById('rzp_signature').value   = signature;
  document.getElementById('rzp_internal_id').value = internalOrderId;
  form.submit();
}

/* ═══════════════════════════════════════════════════════════
   6. COD / UPI FORM SUBMIT
═══════════════════════════════════════════════════════════ */

function submitCodForm(data, method) {
  const form = document.getElementById('codForm');
  if (! form) return;

  Object.keys(data).forEach(key => {
    const el = document.getElementById('hd_' + key);
    if (el) el.value = data[key];
  });
  document.getElementById('hd_pm').value = method;
  form.submit();
}

/* ═══════════════════════════════════════════════════════════
   7. UI HELPERS
═══════════════════════════════════════════════════════════ */

function setButtonLoading(btn, text) {
  if (! btn) return;
  btn._originalHtml = btn.innerHTML;
  btn.disabled  = true;
  btn.innerHTML = `<span class="spinner" style="display:inline-block;width:18px;height:18px;border:3px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-right:8px;"></span>${text}`;
  if (! document.getElementById('spinStyle')) {
    const s = document.createElement('style');
    s.id = 'spinStyle';
    s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}';
    document.head.appendChild(s);
  }
}

function resetButton(btn) {
  if (! btn) return;
  btn.disabled  = false;
  btn.innerHTML = btn._originalHtml || '<i class="fas fa-lock me-2"></i>Pay Now';
}

function showPaymentError(message) {
  // Remove any existing error
  document.querySelector('.payment-error-banner')?.remove();

  const banner = document.createElement('div');
  banner.className = 'payment-error-banner';
  banner.style.cssText =
    'background:#fde8e8;color:#c62828;padding:14px 18px;border-radius:10px;font-size:.9rem;margin-bottom:16px;border-left:4px solid #ef4444;';
  banner.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i>${message}`;

  const card = document.querySelector('.summary-card') || document.querySelector('.section-card');
  if (card) card.prepend(banner);
  banner.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/* ═══════════════════════════════════════════════════════════
   8. INIT
═══════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {
  // Mark first payment option as selected on load
  const firstOpt = document.querySelector('.payment-opt');
  if (firstOpt) {
    firstOpt.classList.add('selected');
    firstOpt.style.borderColor = '#6b8e23';
    firstOpt.style.background  = '#f6fbf0';
  }

  // Live PIN code formatting — digits only
  const pinEl = document.getElementById('pincodeField');
  if (pinEl) {
    pinEl.addEventListener('input', () => {
      pinEl.value = pinEl.value.replace(/\D/g, '').slice(0, 6);
    });
  }

  // Live phone formatting
  const phoneEl = document.getElementById('phoneField');
  if (phoneEl) {
    phoneEl.addEventListener('input', () => {
      phoneEl.value = phoneEl.value.replace(/[^\d +\-()]/g, '').slice(0, 14);
    });
  }
});
