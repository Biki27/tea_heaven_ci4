<?php
/**
 * Views/about/index.php
 * Variables: $cartCount, $pageTitle
 */
?>
<?= view('layouts/header', ['cartCount' => $cartCount, 'pageTitle' => $pageTitle]) ?>

<style>
/* About Page Specific Styles */
.about-hero {
    position: relative;
    height: 70vh;
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1800&q=80') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    margin-bottom: 80px;
}

.hero-title {
    font-size: clamp(2.5rem, 5vw, 4.5rem);
    font-weight: 700;
}

.hero-title em {
    color: var(--tea-gold);
    font-style: italic;
}

.section-eyebrow {
    color: var(--leaf-green);
    text-transform: uppercase;
    letter-spacing: 3px;
    font-weight: 600;
    font-size: 0.8rem;
    display: block;
    margin-bottom: 10px;
}

.section-heading {
    font-weight: 700;
    color: var(--warm-brown);
    margin-bottom: 25px;
}

.section-heading em {
    color: var(--tea-gold);
    font-style: italic;
}

/* Heritage Image */
.heritage-img-wrap {
    position: relative;
    padding: 20px;
}

.heritage-img-wrap img {
    width: 100%;
    border-radius: 15px;
    box-shadow: 20px 20px 0 var(--leaf-green);
}

.heritage-badge {
    position: absolute;
    bottom: -10px;
    right: -10px;
    background: var(--forest-dark);
    color: var(--tea-gold);
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-weight: 700;
    border: 2px solid var(--tea-gold);
}

/* Process Section */
.process-section {
    background: var(--forest-dark);
    color: white;
    padding: 100px 0;
    margin-top: 80px;
}

.process-card {
    background: rgba(255,255,255,0.05);
    padding: 30px;
    border-radius: 15px;
    border-left: 4px solid var(--tea-gold);
    height: 100%;
    transition: 0.3s;
}

.process-card:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-5px);
}

.process-num {
    font-size: 2.5rem;
    color: rgba(212, 175, 55, 0.3);
    font-weight: 800;
    float: right;
}

/* Quality Cards */
.quality-card {
    background: white;
    padding: 40px 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    text-align: center;
    height: 100%;
    border-bottom: 5px solid var(--tea-gold);
    transition: 0.3s;
}

.quality-card:hover {
    transform: translateY(-10px);
}

.quality-icon {
    font-size: 2.5rem;
    color: var(--leaf-green);
    margin-bottom: 20px;
}

.about-page {
    background: #faf7f2;
    padding-top: 0;
}
</style>

<div class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <span class="section-eyebrow" style="color: var(--tea-gold);">Est. 1998 · Darjeeling, India</span>
            <h1 class="hero-title">Rooted in Soil,<br><em>Refined by Time</em></h1>
            <p class="mt-3 lead" style="max-width: 700px; margin: 0 auto; opacity: 0.9;">Every cup we craft begins as a whisper in the morning fog of a mountain garden.</p>
        </div>
    </section>

    <!-- Heritage Section -->
    <section class="container my-5 py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="heritage-img-wrap">
                    <img src="https://images.unsplash.com/photo-1582793988951-9aed5509eb97?w=800&q=80" alt="Tea garden">
                    <div class="heritage-badge">
                        <span style="font-size: 1.5rem;">25+</span>
                        <small style="font-size: 0.6rem; letter-spacing: 1px;">YEARS</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span class="section-eyebrow">Our Heritage</span>
                <h2 class="section-heading">Where Every <em>Leaf Tells a Story</em></h2>
                <p class="text-muted">It began in 1998, when our founder Meera Devi climbed the mist-wrapped hillsides of Darjeeling and tasted a tea so alive it seemed to breathe — muscatel notes curling upward, a fleeting floral brightness that vanished like perfume in the breeze.</p>
                <p class="text-muted">What started as a small trading partnership with three family gardens has grown into a curated network spanning the Nilgiris, Assam's golden plains, and the frost-kissed elevations of Sikkim.</p>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="process-section">
        <div class="container">
            <div class="text-center mb-5">
                <span class="section-eyebrow" style="color: var(--tea-gold);">From Leaf to Cup</span>
                <h2 class="section-heading text-white">A Ritual of <em>Meticulous Care</em></h2>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="process-card">
                        <span class="process-num">01</span>
                        <h5 class="text-white mb-3">Hand-Plucking</h5>
                        <p class="small text-white-50">Only the finest two leaves and a bud are gathered by skilled pickers before the morning sun grows harsh.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-card">
                        <span class="process-num">02</span>
                        <h5 class="text-white mb-3">Slow Withering</h5>
                        <p class="small text-white-50">Fresh leaves rest on bamboo trays for up to 18 hours, concentrating flavor into something rich and eloquent.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-card">
                        <span class="process-num">03</span>
                        <h5 class="text-white mb-3">Artisan Firing</h5>
                        <p class="small text-white-50">Our masters halt oxidation at its most beautiful moment — locking in brightness, depth, and fragrance.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="process-card">
                        <span class="process-num">04</span>
                        <h5 class="text-white mb-3">Sealed Fresh</h5>
                        <p class="small text-white-50">Each batch is sealed in food-grade nitrogen packaging to ensure the aroma is exactly what our masters intended.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quality Section -->
    <section class="container py-5 my-5">
        <div class="text-center mb-5">
            <span class="section-eyebrow">Our Commitment</span>
            <h2 class="section-heading">The Quality <em>Promise</em></h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="quality-card">
                    <i class="fa-solid fa-leaf quality-icon"></i>
                    <h5>Pesticide-Free</h5>
                    <p class="text-muted small">We source exclusively from estates certified under India's National Programme for Organic Production.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="quality-card">
                    <i class="fa-solid fa-award quality-icon"></i>
                    <h5>Master Taster Approved</h5>
                    <p class="text-muted small">Every single batch passes through the palate of a licensed Tea Board of India taster before it earns our seal.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="quality-card">
                    <i class="fa-solid fa-heart quality-icon"></i>
                    <h5>Pure Happiness</h5>
                    <p class="text-muted small">If your first brew doesn't transport you, we will replace it or refund it. Full stop.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<?= view('layouts/footer') ?>
