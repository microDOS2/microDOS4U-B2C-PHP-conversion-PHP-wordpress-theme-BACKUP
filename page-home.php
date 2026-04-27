<?php
/**
 * Template Name: Home Page
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="hero py-5">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content animate-fade-in">
            <h1 class="hero-title">
                <span class="brand-micro">micro</span><span class="brand-dos">DOS</span><span class="brand-two">(2)</span><br>
                <span style="font-size:0.6em; font-weight:600; color:var(--text-muted);">Precision Psychedelics, Simplified.</span>
            </h1>
            <p class="hero-subtitle">
                Research-grade compounds for the curious mind. 
                Consistent dosing, third-party tested, delivered discreetly.
            </p>
            <div class="hero-actions flex justify-center gap-2 mt-3">
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>" class="btn btn-primary">
                    Explore Products
                </a>
                <a href="#how-it-works" class="btn btn-secondary">How It Works</a>
            </div>
        </div>
    </div>
</section>

<section id="how-it-works" class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="gradient-text">How It Works</h2>
            <p class="max-w-xl mx-auto text-muted">
                We handle the science so you can focus on the experience
            </p>
        </div>
        <div class="features-grid">
            <div class="card text-center">
                <div class="feature-icon mb-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3>Precise Dosing</h3>
                <p>Every compound is pre-measured to exact specifications, removing all guesswork.</p>
            </div>
            <div class="card text-center">
                <div class="feature-icon mb-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>
                </div>
                <h3>Verified Quality</h3>
                <p>Independent, third-party lab testing for purity and potency on every batch.</p>
            </div>
            <div class="card text-center">
                <div class="feature-icon mb-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                </div>
                <h3>Discreet Delivery</h3>
                <p>Secure, unmarked packaging shipped via USPS to your door.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="gradient-text">Choose Your Path</h2>
            <p class="max-w-xl mx-auto text-muted">
                A regimen designed for your specific goals, delivered to you monthly.
            </p>
        </div>
        <div class="pricing-grid">
            <div class="pricing-card text-center">
                <h3>The Trial Pack</h3>
                <div class="price">$12.95</div>
                <p class="price-period">One-time purchase</p>
                <p>2 doses. The perfect introduction for the curious mind.</p>
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>" class="btn btn-primary w-full">Start Here</a>
            </div>
            <div class="pricing-card text-center">
                <h3>Explorer Box</h3>
                <div class="price">$47.56</div>
                <p class="price-period">/ month</p>
                <p>10 doses. The standard for the modern microdoser.</p>
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>" class="btn btn-primary w-full">Subscribe</a>
            </div>
            <div class="pricing-card text-center">
                <h3>Optimizer Box</h3>
                <div class="price">$128.31</div>
                <p class="price-period">/ month</p>
                <p>30 doses. For the dedicated practitioner.</p>
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>" class="btn btn-primary w-full">Subscribe</a>
            </div>
            <div class="pricing-card text-center">
                <h3>Master Box</h3>
                <div class="price">$217.56</div>
                <p class="price-period">/ month</p>
                <p>60 doses. Maximum value for the experienced user.</p>
                <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/')); ?>" class="btn btn-primary w-full">Subscribe</a>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="gradient-text">What You're Getting</h2>
        </div>
        <div class="features-grid">
            <div class="card">
                <h4>Metocin (4-HO-MET)</h4>
                <p>A research compound known for its vivid, manageable visual and cognitive effects.</p>
            </div>
            <div class="card">
                <h4>Consistent 2mg Tablets</h4>
                <p>No guesswork, no scale needed. Every single tablet contains exactly 2mg.</p>
            </div>
            <div class="card">
                <h4>90-Day Shelf Life</h4>
                <p>Lab-verified stability for 3 months in cool, dark storage.</p>
            </div>
            <div class="card">
                <h4>Tested & Verified</h4>
                <p>Each batch is verified by third-party laboratories for purity.</p>
            </div>
            <div class="card">
                <h4>Discreet Shipping</h4>
                <p>Secure, unmarked packaging via USPS.</p>
            </div>
            <div class="card">
                <h4>Guided Protocol</h4>
                <p>Receive a clear, easy-to-follow guide for safe and effective use.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="gradient-text">Frequently Asked Questions</h2>
        </div>
        <div class="max-w-2xl mx-auto">
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is this legal?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="faq-answer">
                    <p>Our products are sold strictly for research purposes. Please review our Legal Disclaimer for full details.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Is shipping discreet?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="faq-answer">
                    <p>Yes. All orders are shipped in secure, unmarked packaging via USPS.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>Do I need to measure the doses?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="faq-answer">
                    <p>No. Our compounds are pre-dosed into consistent 2mg tablets.</p>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    <span>How long does shipping take?</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
                <div class="faq-answer">
                    <p>All orders are processed within 24-48 hours. Standard USPS shipping times apply.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
