<?php
/**
 * Template Name: Legal Disclaimer
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="hero py-5">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title gradient-text">Legal Disclaimer</h1>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card">
            <h2>Important Notice</h2>
            <p>The products sold on this website are intended strictly for <strong>research purposes only</strong>. By purchasing from <?php echo esc_html(microdos4u_site_brand()); ?>, you acknowledge and agree to the following:</p>
            <ul style="margin: 1rem 0; padding-left: 1.5rem; color: var(--text-muted);">
                <li>These compounds are NOT intended for human consumption.</li>
                <li>You are at least 18 years of age.</li>
                <li>You will use these products only for legitimate research purposes.</li>
                <li>You understand the legal status of these compounds in your jurisdiction.</li>
                <li>You assume all responsibility for compliance with local laws.</li>
            </ul>
            <p><?php echo esc_html(microdos4u_site_brand()); ?> does not condone illegal activity. We cannot be held responsible for the actions of individuals who purchase our products.</p>
            <p style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <strong>Contact:</strong> <a href="mailto:microDOS4U@gmail.com">microDOS4U@gmail.com</a>
            </p>
        </div>
    </div>
</section>

<?php
get_footer();
