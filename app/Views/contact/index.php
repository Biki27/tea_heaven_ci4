<?php
/**
 * Views/contact/index.php
 * Variables: $cartCount, $pageTitle
 */
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
/* Contact Page Specific Styles */
.contact-page {
    background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.8)),
                url('https://images.unsplash.com/photo-1532634726-8021309a93b1?w=1800&q=80') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    padding: 60px 0 100px;
    min-height: 100vh;
}

.contact-glass-panel {
    background: rgba(255, 255, 255, 0.07);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 60px;
    margin-bottom: 100px;
    box-shadow: 0 40px 100px rgba(0, 0, 0, 0.5);
}

.contact-title {
    font-size: 42px;
    font-weight: 700;
    margin-bottom: 50px;
    letter-spacing: 1px;
    color: #fff;
    text-align: center;
}

.form-control {
    background: rgba(255, 255, 255, 0.95) !important;
    border: none;
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 0.95rem;
    color: #333;
}

.form-control::placeholder {
    color: #999;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.98) !important;
    box-shadow: 0 0 0 3px rgba(107, 142, 35, 0.2);
}

.submit-btn {
    background: linear-gradient(135deg, var(--leaf-green), #90C695);
    color: white;
    border: none;
    padding: 16px;
    border-radius: 50px;
    font-weight: 600;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: 0.4s;
    box-shadow: 0 10px 30px rgba(107, 142, 35, 0.3);
}

.submit-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(107, 142, 35, 0.5);
    background: linear-gradient(135deg, var(--warm-brown), var(--tea-gold));
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Info Cards */
.info-card {
    background: rgba(255, 255, 255, 0.05);
    padding: 35px;
    border-radius: 18px;
    text-align: center;
    height: 100%;
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: 0.3s;
}

.info-card:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--tea-gold);
}

.info-icon {
    font-size: 2.5rem;
    color: var(--tea-gold);
    margin-bottom: 20px;
}

.info-card h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 15px;
}

.info-card p {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Form Messages */
.alert {
    border-radius: 12px;
    border: none;
}

.form-error {
    color: #dc3545;
    font-size: 0.85rem;
    margin-top: 5px;
    display: none;
}

.form-group.error .form-control {
    border-bottom: 2px solid #dc3545;
}

.form-group.error .form-error {
    display: block;
}

@media (max-width: 768px) {
    .contact-glass-panel {
        padding: 30px;
    }

    .contact-title {
        font-size: 28px;
        margin-bottom: 30px;
    }
}
</style>

<div class="contact-page">
    <div class="container">
        <h1 class="contact-title">Contact Our Tea Masters</h1>

        <div class="contact-glass-panel">
            <div class="row g-5">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <h4 class="mb-4" style="color: var(--tea-gold)">Inquiry Form</h4>
                    <form id="contactForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name" required>
                                    <span class="form-error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
                                    <span class="form-error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                            <span class="form-error"></span>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="message" name="message" rows="6" placeholder="Your Message..." required></textarea>
                            <span class="form-error"></span>
                        </div>
                        <button type="submit" class="submit-btn" id="submitBtn">Send Message</button>
                    </form>
                    <div id="successMessage" class="alert alert-success mt-3" style="display: none;"></div>
                    <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="info-card">
                                <i class="fa-solid fa-location-dot info-icon"></i>
                                <h5>Visit the Estate</h5>
                                <p>123 Emerald Valley, Darjeeling,<br>West Bengal 734101, India</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card">
                                <i class="fa-solid fa-phone-volume info-icon"></i>
                                <h5>Direct Line</h5>
                                <p>Toll Free: 1800-TEA-HAVEN<br>Office: +91 33 2444 0000</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card">
                                <i class="fa-solid fa-envelope info-icon"></i>
                                <h5>Email</h5>
                                <p>info@teaheaven.in<br>support@teaheaven.in</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const successMsg = document.getElementById('successMessage');
    const errorMsg = document.getElementById('errorMessage');

    // Clear previous messages
    successMsg.style.display = 'none';
    errorMsg.style.display = 'none';

    // Collect form data
    const formData = new FormData(form);

    try {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        const response = await fetch('<?= base_url('contact/send') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            successMsg.textContent = data.message;
            successMsg.style.display = 'block';
            form.reset();
        } else {
            if (data.errors) {
                for (const [field, error] of Object.entries(data.errors)) {
                    const fieldGroup = form.querySelector(`[name="${field}"]`).closest('.form-group');
                    fieldGroup.classList.add('error');
                    fieldGroup.querySelector('.form-error').textContent = Array.isArray(error) ? error[0] : error;
                }
            }
            errorMsg.textContent = data.message || 'Please fill in all required fields.';
            errorMsg.style.display = 'block';
        }
    } catch (error) {
        console.error('Error:', error);
        errorMsg.textContent = 'An error occurred. Please try again later.';
        errorMsg.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Send Message';
    }
});

// Clear errors on input
document.querySelectorAll('.form-control').forEach(field => {
    field.addEventListener('input', function() {
        const formGroup = this.closest('.form-group');
        formGroup.classList.remove('error');
        formGroup.querySelector('.form-error').textContent = '';
    });
});
</script>

<?= view('layouts/footer') ?>
