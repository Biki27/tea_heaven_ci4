document.addEventListener('DOMContentLoaded', () => {
    // Initialize cart listeners
    Cart.init();
});

const Cart = {
    // Grab the initial CSRF token from the meta tag in header.php
    csrfToken: document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content'),

    init: function () {
        // 1. Bind 'Add to Cart' buttons (Works for both Home/Grid and Detail page)
        document.querySelectorAll('.add-to-cart, .detail-add-to-cart').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Fetch the ID from data-id (matches your index.php and detail.php)
                const productId = button.getAttribute('data-id');

                // Check if we are on the detail page with a specific qty input
                const detailQtyInput = document.getElementById('detail-qty');
                const quantity = detailQtyInput ? parseInt(detailQtyInput.value) : 1;

                this.add(productId, quantity, button);
            });
        });

        // 2. Bind 'Remove' buttons in the cart view
        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const rowId = button.getAttribute('data-id');
                this.remove(rowId);
            });
        });
    },

    // Get updated CSRF token from headers if CI4 regenerates it
    updateCsrf: function (newToken) {
        if (newToken) {
            this.csrfToken = newToken;
            // Also update the meta tag so other scripts get the fresh token
            const meta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (meta) meta.setAttribute('content', newToken);
        }
    },

    add: async function(productId, quantity, button = null) {
        let originalText = '';
        if (button) {
            originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            button.disabled = true;
        }

        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('qty', quantity);

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });

            // If the server crashes (500 error), response.ok will be false.
            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const data = await response.json();
            this.updateCsrf(data.csrf_token); 

            if (data.status === 'ok') {
                this.showToast(data.message || 'Added to cart!', 'success');
                this.updateCartBadge(data.cart_count);

                if (button) {
                    button.innerHTML = '<i class="fas fa-check"></i> Added!';
                    button.style.backgroundColor = '#4E342E';
                    button.style.color = 'white';
                }
            } else {
                this.showToast(data.message || 'Failed to add item.', 'error');
                if (button) button.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Cart Add Error:', error);
            this.showToast('An unexpected error occurred.', 'error');
            if (button) button.innerHTML = originalText;
        } finally {
            if (button) {
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.style.backgroundColor = '';
                }, 1000);
            }
        }
    },

    remove: async function (rowId) {
        try {
            const formData = new FormData();
            formData.append('cart_id', rowId); // Cart.php expects 'cart_id'

            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });


            const rawText = await response.text();
            // console.log("RAW SERVER RESPONSE:", rawText);
            const data = JSON.parse(rawText); // Temporarily comment this out
            this.updateCsrf(data.csrf_token);

            if (response.ok && data.status === 'ok') {
                this.showToast('Item removed from cart', 'success');

                // Remove the row from the DOM
                const rowElement = document.getElementById(`cart-item-${rowId}`);
                if (rowElement) rowElement.remove();

                this.updateCartBadge(data.cart_count);

                // If cart is empty, reload the page to show the empty cart UI
                if (data.cart_count === 0) {
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Update totals on the page
                    document.getElementById('cart-subtotal').innerText = '₹' + data.subtotal;
                    document.getElementById('cart-tax').innerText = '₹' + data.tax;
                    document.getElementById('cart-total').innerText = '₹' + data.total;
                }
            } else {
                this.showToast(data.message || 'Failed to remove item.', 'error');
            }
        } catch (error) {
            console.error('Cart Remove Error:', error);
        }
    },

    updateCartBadge: function (totalItems) {
        // Matches the ID in your header.php
        const badge = document.getElementById('cartBadge');
        if (badge) badge.innerText = totalItems;
    },

    // Minimal Toast System
    showToast: function (message, type = 'success') {
        const toastContainer = document.getElementById('toast-container') || this.createToastContainer();

        const toast = document.createElement('div');
        toast.className = `toast-message ${type}`;
        toast.style.cssText = `
            padding: 12px 24px;
            margin-bottom: 10px;
            border-radius: 6px;
            color: white;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            background: ${type === 'success' ? '#6B8E23' : '#F44336'}; /* Matches your brand green */
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            transition: opacity 0.3s, transform 0.3s;
            transform: translateY(10px);
            opacity: 0;
        `;
        toast.innerHTML = type === 'success' ? `<i class="fas fa-check-circle me-2"></i> ${message}` : `<i class="fas fa-exclamation-circle me-2"></i> ${message}`;

        toastContainer.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        }, 10);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    createToastContainer: function () {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position: fixed; top: 90px; right: 20px; z-index: 9999; display: flex; flex-direction: column; align-items: flex-end;';
        document.body.appendChild(container);
        return container;
    }
};