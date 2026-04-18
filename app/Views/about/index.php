<?php
/**
 * Views/about/index.php
 * Variables: $cartCount, $pageTitle
 */
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
/* About Page Specific Styles */
:root {
    --tea-gold: #D4AF37;
    --leaf-green: #6B8E23;
    --warm-brown: #4E342E;
    --forest-dark: #1a2310;
    --glass-white: rgba(255, 255, 255, 0.92);
}

.about-container {
    background: #faf7f2;
}

/* Hero Section */
.about-hero {
    position: relative;
    height: 65vh;
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=80') center/cover no-repeat fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    margin-bottom: 80px;
    overflow: hidden;
}

.hero-content {
    max-width: 700px;
    animation: fadeInUp 0.8s ease-out;
}

.hero-eyebrow {
    color: var(--tea-gold);
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 600;
    font-size: 0.85rem;
    display: block;
    margin-bottom: 15px;
}

.hero-title {
    font-size: clamp(2rem, 6vw, 3.5rem);
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 15px;
}

.hero-title em {
    color: var(--tea-gold);
    font-style: italic;
    display: block;
}

.hero-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
    line-height: 1.6;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Section Styling */
.section-eyebrow {
    color: var(--leaf-green);
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 700;
    font-size: 0.75rem;
    display: block;
    margin-bottom: 12px;
}

.section-heading {
    font-weight: 700;
    color: var(--warm-brown);
    font-size: 2rem;
    margin-bottom: 30px;
    line-height: 1.3;
}

.section-heading em {
    color: var(--tea-gold);
    font-style: italic;
}

/* Heritage Section */
.heritage-section {
    padding: 80px 0;
}

.heritage-img-wrap {
    position: relative;
    padding: 20px;
    margin-bottom: 30px;
}

.heritage-img-wrap img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 20px 20px 0 var(--leaf-green);
}

.heritage-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: var(--forest-dark);
    color: var(--tea-gold);
    width: 110px;
    height: 110px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-weight: 700;
    border: 3px solid var(--tea-gold);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
}

.heritage-badge-number {
    font-size: 1.8rem;
    line-height: 1;
}

.heritage-badge-label {
    font-size: 0.55rem;
    letter-spacing: 1px;
    margin-top: 5px;
}

.heritage-text p {
    font-size: 1rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 20px;
}

.heritage-text p:last-child {
    margin-bottom: 0;
}

/* Process Section */
.process-section {
    background: var(--forest-dark);
    color: white;
    padding: 100px 0;
    position: relative;
}

.process-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.05)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.05)"/></svg>');
    opacity: 0.3;
    pointer-events: none;
}

.process-header {
    text-align: center;
    margin-bottom: 60px;
    position: relative;
    z-index: 1;
}

.process-card {
    background: rgba(255, 255, 255, 0.05);
    padding: 35px;
    border-radius: 15px;
    border-left: 5px solid var(--tea-gold);
    height: 100%;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.process-card:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.process-num {
    font-size: 3rem;
    color: rgba(212, 175, 55, 0.2);
    font-weight: 800;
    position: absolute;
    top: 20px;
    right: 20px;
}

.process-card h5 {
    color: var(--tea-gold);
    font-weight: 600;
    margin-bottom: 12px;
    position: relative;
    z-index: 2;
}

.process-card p {
    color: rgba(255, 255, 255, 0.75);
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Quality Section */
.quality-section {
    padding: 100px 0;
}

.quality-header {
    text-align: center;
    margin-bottom: 60px;
}

.quality-card {
    background: white;
    padding: 45px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    text-align: center;
    height: 100%;
    border-bottom: 5px solid var(--tea-gold);
    transition: all 0.3s ease;
}

.quality-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.12);
}

.quality-icon {
    font-size: 2.8rem;
    color: var(--leaf-green);
    margin-bottom: 20px;
    display: block;
}

.quality-card h5 {
    color: var(--warm-brown);
    font-weight: 600;
    font-size: 1.1rem;
    margin-bottom: 12px;
}

.quality-card p {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Responsive */
@media (max-width: 768px) {
    .about-hero {
        height: 55vh;
        margin-bottom: 60px;
    }

    .hero-title {
        font-size: clamp(1.5rem, 5vw, 2.5rem);
    }

    .hero-subtitle {
        font-size: 0.95rem;
    }

    .heritage-img-wrap img {
        height: 300px;
    }

    .heritage-badge {
        width: 90px;
        height: 90px;
    }

    .heritage-badge-number {
        font-size: 1.4rem;
    }

    .section-heading {
        font-size: 1.5rem;
    }

    .process-section,
    .quality-section {
        padding: 60px 0;
    }

    .heritage-section {
        padding: 60px 0;
    }
}

@media (max-width: 576px) {
    .about-hero {
        height: 50vh;
        margin-bottom: 40px;
    }

    .hero-eyebrow {
        font-size: 0.75rem;
    }

    .hero-title {
        font-size: 1.5rem;
    }

    .hero-subtitle {
        font-size: 0.9rem;
    }

    .heritage-img-wrap {
        padding: 10px;
    }

    .heritage-img-wrap img {
        height: 250px;
        box-shadow: 15px 15px 0 var(--leaf-green);
    }

    .heritage-badge {
        width: 80px;
        height: 80px;
        border-width: 2px;
    }

    .section-heading {
        font-size: 1.3rem;
    }

    .process-card,
    .quality-card {
        padding: 25px 20px;
    }

    .process-num {
        font-size: 2rem;
    }
}
</style>

<main class="about-container">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="hero-content">
            <span class="hero-eyebrow">Est. 1998 · Darjeeling, India</span>
            <h1 class="hero-title">Rooted in Soil,<br><em>Refined by Time</em></h1>
            <p class="hero-subtitle">Every cup we craft begins as a whisper in the morning fog of a mountain garden.</p>
        </div>
    </section>

    <!-- Heritage Section -->
    <section class="container heritage-section">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="heritage-img-wrap">
                    <img src="https://images.unsplash.com/photo-1582793988951-9aed5509eb97?w=800&q=80" alt="Tea gardens in Darjeeling" loading="lazy">
                    <div class="heritage-badge">
                        <span class="heritage-badge-number">25+</span>
                        <span class="heritage-badge-label">YEARS</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 heritage-text">
                <span class="section-eyebrow">Our Heritage</span>
                <h2 class="section-heading">Where Every <em>Leaf Tells a Story</em></h2>
                <p>It began in 1998, when our founder Meera Devi climbed the mist-wrapped hillsides of Darjeeling and tasted a tea so alive it seemed to breathe — muscatel notes curling upward, a fleeting floral brightness that vanished like perfume in the breeze.</p>
                <p>What started as a small trading partnership with three family gardens has grown into a curated network spanning the Nilgiris, Assam's golden plains, and the frost-kissed elevations of Sikkim.</p>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="process-header">
                <span class="section-eyebrow" style="color: var(--tea-gold);">From Leaf to Cup</span>
                <h2 class="section-heading text-white">A Ritual of <em>Meticulous Care</em></h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <span class="process-num">01</span>
                        <h5>Hand-Plucking</h5>
                        <p>Only the finest two leaves and a bud are gathered by skilled pickers before the morning sun grows harsh.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <span class="process-num">02</span>
                        <h5>Slow Withering</h5>
                        <p>Fresh leaves rest on bamboo trays for up to 18 hours, concentrating flavor into something rich and eloquent.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <span class="process-num">03</span>
                        <h5>Artisan Firing</h5>
                        <p>Our masters halt oxidation at its most beautiful moment — locking in brightness, depth, and fragrance.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="process-card">
                        <span class="process-num">04</span>
                        <h5>Sealed Fresh</h5>
                        <p>Each batch is sealed in food-grade nitrogen packaging to ensure the aroma is exactly what our masters intended.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Section -->
    <section class="container quality-section">
        <div class="quality-header">
            <span class="section-eyebrow">Our Commitment</span>
            <h2 class="section-heading">The Quality <em>Promise</em></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="quality-card">
                    <i class="fa-solid fa-leaf quality-icon"></i>
                    <h5>Pesticide-Free</h5>
                    <p>We source exclusively from estates certified under India's National Programme for Organic Production.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="quality-card">
                    <i class="fa-solid fa-award quality-icon"></i>
                    <h5>Master Taster Approved</h5>
                    <p>Every single batch passes through the palate of a licensed Tea Board of India taster before it earns our seal.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="quality-card">
                    <i class="fa-solid fa-heart quality-icon"></i>
                    <h5>Pure Happiness</h5>
                    <p>If your first brew doesn't transport you, we will replace it or refund it. Full stop.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?= view('layouts/footer') ?>
