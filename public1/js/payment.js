const CheckoutConfig = {
    csrfToken: document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content'),
    razorpayKeyId: '', // Will be set by index.php
    
    // Accept the form element as a parameter
    processPayment: async function(formElement) {
        const payBtn = document.getElementById('pay-btn');
        const originalBtnText = payBtn.innerHTML;
        
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        payBtn.disabled = true;

        try {
            // 1. Create Order on Backend
            // FIXED: Using FormData(formElement) automatically grabs ALL inputs (name, address, etc.)
            const formData = new FormData(formElement); 
            
            // CI4 prefers the token in the header, but we'll append it just in case
            formData.append('csrf_test_name', this.csrfToken);

            const orderResponse = await fetch('/payment/create-order', {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });

            if (!orderResponse.ok) {
                throw new Error(`Server Error: ${orderResponse.status}`);
            }

            const orderData = await orderResponse.json();

            if (orderData.status !== 'ok') {
                throw new Error(orderData.message || 'Failed to create order.');
            }

            // 2. Initialize Razorpay Checkout
            const options = {
                "key": this.razorpayKeyId,
                "amount": orderData.amount, 
                "currency": "INR",
                "name": "Tea Haven",
                "description": "Premium Tea Purchase",
                "image": "/images/logo.png", 
                "order_id": orderData.razorpay_order_id, 
                "handler": function (response) {
                    // 3. Payment Success - Verify Signature
                    payBtn.innerHTML = '<i class="fas fa-check me-2"></i>Verifying...';
                    CheckoutConfig.verifyPayment(response);
                },
                "prefill": {
                    "name": formElement.first_name.value + ' ' + formElement.last_name.value,
                    "email": formElement.email.value,
                    "contact": formElement.phone.value
                },
                "theme": {
                    "color": "#6B8E23" // Matches your Leaf Green brand color
                },
                "modal": {
                    "ondismiss": function() {
                        payBtn.innerHTML = originalBtnText;
                        payBtn.disabled = false;
                    }
                }
            };

            const rzp = new Razorpay(options);
            rzp.on('payment.failed', function (response){
                window.location.href = `/payment/failed?reason=${encodeURIComponent(response.error.description)}`;
            });
            rzp.open();

        } catch (error) {
            console.error('Checkout Error:', error);
            alert(error.message);
            payBtn.innerHTML = originalBtnText;
            payBtn.disabled = false;
        }
    },

    verifyPayment: async function(razorpayResponse) {
        try {
            const formData = new FormData();
            formData.append('csrf_test_name', this.csrfToken);
            formData.append('razorpay_payment_id', razorpayResponse.razorpay_payment_id);
            formData.append('razorpay_order_id', razorpayResponse.razorpay_order_id);
            formData.append('razorpay_signature', razorpayResponse.razorpay_signature);

            const verifyResponse = await fetch('/payment/verify', {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: formData
            });

            // Redirect based on backend response
            if (verifyResponse.redirected) {
                window.location.href = verifyResponse.url;
            } else {
                window.location.href = '/payment/success';
            }
        } catch (error) {
            console.error('Verification Error:', error);
            window.location.href = '/payment/failed';
        }
    }
};
 