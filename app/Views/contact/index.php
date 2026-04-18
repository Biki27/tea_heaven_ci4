<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
/* Contact Page Specific Styles */
:root {
    --tea-gold: #D4AF37;
    --leaf-green: #6B8E23;
    --warm-brown: #4E342E;
    --light-bg: #faf7f2;
}

body {
    background-color: var(--light-bg);
}

/* Hero Section matching the image gradient */
.contact-hero {
    position: relative;
    height: 40vh;
    min-height: 350px;
    background: linear-gradient(135deg, #7c9954 0%, #685043 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    margin-bottom: 60px;
}

.hero-content {
    max-width: 600px;
    animation: fadeIn 0.8s ease-out;
}

.hero-eyebrow {
    color: var(--tea-gold);
    text-transform: uppercase;
    letter-spacing: 2px;
    font-weight: 600;
    font-size: 0.85rem;
    display: block;
    margin-bottom: 15px;
}

.hero-title {
    font-size: clamp(2rem, 5vw, 3rem);
    font-weight: 700;
    margin-bottom: 15px;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    line-height: 1.6;
}

/* Form Styling */
.form-heading {
    font-weight: 700;
    color: var(--warm-brown);
    font-size: 1.8rem;
}

.form-heading span {
    color: var(--tea-gold);
}

.form-label {
    font-weight: 500;
    font-size: 0.9rem;
    color: #555;
    margin-bottom: 8px;
}

.form-control {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.form-control:focus {
    border-color: var(--leaf-green);
    box-shadow: 0 0 0 0.25rem rgba(107, 142, 35, 0.15);
}

.btn-submit {
    background-color: var(--leaf-green);
    color: white;
    border: none;
    padding: 14px 25px;
    font-weight: 600;
    border-radius: 8px;
    width: 100%;
    transition: background-color 0.3s;
    margin-top: 10px;
}

.btn-submit:hover {
    background-color: #5a781d;
}

.btn-submit:disabled {
    background-color: #a0b380;
    cursor: not-allowed;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    border-left: 4px solid var(--tea-gold);
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.info-icon {
    background: rgba(107, 142, 35, 0.1);
    color: var(--leaf-green);
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.info-content h5 {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--warm-brown);
    margin-bottom: 8px;
}

.info-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
    line-height: 1.6;
}

.info-content a {
    color: var(--leaf-green);
    text-decoration: none;
    font-weight: 500;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<main>
    <section class="contact-hero">
        <div class="hero-content px-3">
            <span class="hero-eyebrow">Get in touch</span>
            <h1 class="hero-title">Contact Our Tea Masters</h1>
            <p class="hero-subtitle">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </section>

    <section class="container mb-5 pb-5">
        <div class="row g-5">
            <div class="col-lg-7">
                <h3 class="mb-4 form-heading">Send us a <span>Message</span></h3>
                
                <form id="contactForm">
                    <?= csrf_field() ?>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="Your name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Subject *</label>
                            <input type="text" name="subject" class="form-control" placeholder="What is this about?" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Message *</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="Tell us more about your inquiry..." required></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn-submit" id="submitBtn">SEND MESSAGE</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div class="info-content">
                        <h5>Visit Us</h5>
                        <p>123 Emerald Valley<br>Darjeeling, West Bengal<br>734101, India</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="info-content">
                        <h5>Call Us</h5>
                        <p>Toll Free: <strong>1800-TEA-HAVEN</strong><br>
                        Office: <strong>+91 33 2444 0000</strong><br>
                        Mon - Fri, 9am - 6pm IST</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="info-content">
                        <h5>Email Us</h5>
                        <p>General: <a href="mailto:info@teaheaven.in">info@teaheaven.in</a><br>
                        Support: <a href="mailto:support@teaheaven.in">support@teaheaven.in</a><br>
                        We'll reply within 24 hours</p>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="info-content">
                        <h5>Business Hours</h5>
                        <p>Monday - Friday: 9:00 AM - 6:00 PM<br>
                        Saturday: 10:00 AM - 4:00 PM<br>
                        Sunday: Closed</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');

    contactForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent standard page reload
        
        // Update button state to show loading
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>SENDING...';
        submitBtn.disabled = true;

        const formData = new FormData(this);

        try {
            // Send AJAX request to your CodeIgniter 4 controller
            const response = await fetch(BASE_URL + 'contact/send', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success Alert
                Swal.fire({
                    icon: 'success',
                    title: 'Message Sent!',
                    text: data.message,
                    confirmButtonColor: '#6B8E23', // Matches your --leaf-green
                    background: '#faf7f2',
                });
                contactForm.reset(); // Clear the form
            } else {
                // Validation Error Alert
                let errorHtml = 'Please check your inputs.';
                if(data.errors) {
                    // Extract validation errors from the JSON response
                    errorHtml = Object.values(data.errors).join('<br>');
                } else if (data.message) {
                    errorHtml = data.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: errorHtml,
                    confirmButtonColor: '#6B8E23',
                    background: '#faf7f2',
                });
            }
        } catch (error) {
            // Server or Network Error Alert
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again later.',
                confirmButtonColor: '#6B8E23',
                background: '#faf7f2',
            });
            console.error('Error:', error);
        } finally {
            // Restore button state
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
    });
});
</script>

<?= view('layouts/footer') ?>