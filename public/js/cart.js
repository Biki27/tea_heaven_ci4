/**
 * cart.js — Tea Haven Global Cart
 * ─────────────────────────────────────────────────────────────
 * Handles:
 *  1. "Add to Cart" buttons (.add-to-cart-btn) on any page
 *  2. Live cart badge count updates (#cartBadge)
 *  3. Toast notification system
 *  4. Cart count polling on page load
 *
 * Requires: BASE_URL global set in the layout header
 */

/* ── Helpers ──────────────────────────────────────────────── */

/**
 * Determine base URL reliably.
 * CI4 layout should set: <script>const BASE_URL = '<?= base_url() ?>';</script>
 * Fallback: try to derive from current href.
 */
function getBase() {
  if (typeof BASE_URL !== 'undefined') return BASE_URL;
  const segments = window.location.pathname.split('/');
  // Works for both root install and subdirectory installs
  return window.location.origin + '/';
}

/**
 * Lightweight fetch wrapper — returns parsed JSON or throws.
 */
// Add this helper to get the token from the cookie
/**
 * Helper to get a cookie value by name
 */
 
// 1. Add this helper function at the top of cart.js
function getCookie(name) {
    let cookieValue = null;
    if (document.cookie && document.cookie !== '') {
        const cookies = document.cookie.split(';');
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i].trim();
            if (cookie.substring(0, name.length + 1) === (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}

// 2. Update your postJSON function
async function postJSON(url, params) {
    const body = new URLSearchParams(params).toString();
    
    // Look for the CI4 CSRF cookie (default name is 'csrf_cookie_name')
    const csrfToken = getCookie('csrf_cookie_name');

    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken // This is the vital line
        },
        body,
    });
    
    if (!res.ok) throw new Error('Network response was not ok');
    return res.json();
}

/* ── Toast system ─────────────────────────────────────────── */

/**
 * Show a small toast at the bottom-right of the screen.
 * @param {string} message
 * @param {'success'|'error'|'info'} type
 * @param {number} duration  ms before auto-dismiss
 */
function showToast(message, type = 'success', duration = 3000) {
  // Ensure container exists
  let container = document.getElementById('toastContainer');
  if (! container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.style.cssText =
      'position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
    document.body.appendChild(container);
  }

  const colors = {
    success: { bg: '#6b8e23', icon: '✓' },
    error:   { bg: '#e53935', icon: '✗' },
    info:    { bg: '#0288d1', icon: 'ℹ' },
  };
  const c = colors[type] || colors.info;

  const toast = document.createElement('div');
  toast.style.cssText = `
    background:${c.bg};color:#fff;padding:12px 20px;border-radius:10px;
    box-shadow:0 6px 20px rgba(0,0,0,.2);display:flex;align-items:center;
    gap:10px;font-size:.9rem;font-weight:500;min-width:220px;max-width:340px;
    opacity:0;transform:translateX(40px);transition:all .3s ease;
  `;
  toast.innerHTML = `<span style="font-size:1.1rem">${c.icon}</span><span>${message}</span>`;
  container.appendChild(toast);

  // Animate in
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      toast.style.opacity  = '1';
      toast.style.transform = 'translateX(0)';
    });
  });

  // Auto-dismiss
  setTimeout(() => {
    toast.style.opacity   = '0';
    toast.style.transform = 'translateX(40px)';
    setTimeout(() => toast.remove(), 320);
  }, duration);
}

/* ── Cart badge ───────────────────────────────────────────── */

/**
 * Update all cart badge elements on the page.
 */
// function updateCartBadge(count) {
//   document.querySelectorAll('#cartBadge, .cart-badge').forEach(el => {
//     el.textContent = count;
//     // Small pop animation
//     el.style.transform = 'scale(1.4)';
//     setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
//   });
// }
/* ── Cart badge ───────────────────────────────────────────── */

/**
 * Update all cart badge elements on the page.
 */
function updateCartBadge(count) {
  document.querySelectorAll('#cartBadge, .cart-badge').forEach(el => {
    el.textContent = count;
    
    // Show the badge if count > 0, otherwise hide it
    if (count > 0) {
        el.style.display = 'flex'; // Uses flex to keep the number centered
        // Small pop animation
        el.style.transform = 'scale(1.4)';
        setTimeout(() => { el.style.transform = 'scale(1)'; }, 200);
    } else {
        el.style.display = 'none';
    }
  });
}
/**
 * Fetch cart count from server and update badge.
 * Called once on DOMContentLoaded.
 */
async function syncCartBadge() {
  try {
    const res  = await fetch(getBase() + 'cart/count', {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();
    if (data && typeof data.count !== 'undefined') {
      updateCartBadge(data.count);
    }
  } catch (_) {
    // Silently fail — badge stays at last known value
  }
}

/* ── Add to Cart ──────────────────────────────────────────── */

/**
 * Handle a click on any element with class `.add-to-cart-btn`
 * Expected: data-id (product id), data-name (optional, for toast)
 */
async function handleAddToCart(btn) {
  const productId = btn.dataset.id;
  const name      = btn.dataset.name || 'Item';

  if (! productId) return;

  // Visual feedback
  const originalHtml = btn.innerHTML;
  btn.disabled   = true;
  btn.innerHTML  = '<i class="fas fa-spinner fa-spin"></i>';

  try {
    const data = await postJSON(getBase() + 'cart/add', {
      product_id: productId,
      qty: 1,
    });

    if (data.status === 'ok') {
      updateCartBadge(data.cart_count);
      showToast(`"${name}" added to cart!`, 'success');
    } else {
      showToast(data.message || 'Could not add to cart.', 'error');
    }
  } catch (e) {
    showToast('Network error — please try again.', 'error');
    console.error('[Cart] Add error:', e);
  } finally {
    btn.disabled  = false;
    btn.innerHTML = originalHtml;
  }
}

/* ── Event delegation ─────────────────────────────────────── */

document.addEventListener('DOMContentLoaded', () => {
  // Sync badge count on every page load
  syncCartBadge();

  // Delegated listener — catches buttons added dynamically too
  document.body.addEventListener('click', (e) => {
    const btn = e.target.closest('.add-to-cart-btn');
    if (btn) {
      e.preventDefault();
      handleAddToCart(btn);
    }
  });

  // Smooth transition for cart badge
  document.querySelectorAll('#cartBadge, .cart-badge').forEach(el => {
    el.style.transition = 'transform .2s ease';
  });
});
