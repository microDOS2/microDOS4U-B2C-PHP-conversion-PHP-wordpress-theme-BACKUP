<?php
/**
 * Template Name: Affiliate Marketing Guide
 *
 * Marketing guide for affiliates — social media platform instructions.
 * Public page, not restricted by AffiliateWP.
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main" style="background-color: #0a0514; min-height: 100vh;">
    <div class="container mx-auto px-4 max-w-4xl" style="padding: 40px 16px 80px;">

        <!-- Page Header -->
        <div style="text-align: center; padding: 40px 0 32px; border-bottom: 1px solid #1f2b47; margin-bottom: 40px;">
            <h1 style="color: #fff; font-size: 32px; font-weight: 800; margin: 0 0 12px;">
                <span style="color: #44f80c;">Marketing</span> <span style="color: #ff66c4;">Guide</span>
            </h1>
            <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; max-width: 600px; margin: 0 auto;">
                Platform-by-platform instructions for sharing your microDOS(2) referral link.
            </p>
        </div>

        <!-- Page Content -->
        <div style="color: #d1d5db; line-height: 1.7; font-size: 15px;">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    the_content();
                endwhile;
            endif;
            ?>
        </div>

    </div>
</main>

<style>
/* Marketing Guide dark theme overrides */
.page-template-page-marketing-guide h2 {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin-top: 40px;
    margin-bottom: 12px;
}
.page-template-page-marketing-guide h3 {
    color: #38bdf8;
    font-size: 18px;
    font-weight: 600;
    margin-top: 24px;
    margin-bottom: 8px;
}
.page-template-page-marketing-guide p {
    color: #94a3b8;
    margin-bottom: 12px;
}
.page-template-page-marketing-guide ol,
.page-template-page-marketing-guide ul {
    color: #d1d5db;
    margin-bottom: 16px;
    padding-left: 24px;
}
.page-template-page-marketing-guide li {
    margin-bottom: 6px;
}
.page-template-page-marketing-guide strong {
    color: #fff;
    font-weight: 600;
}
.page-template-page-marketing-guide blockquote {
    background: #150f24;
    border-left: 3px solid #44f80c;
    border-radius: 6px;
    padding: 12px 16px;
    margin: 16px 0;
}
.page-template-page-marketing-guide blockquote p {
    color: #d1d5db;
    margin: 0;
}
.page-template-page-marketing-guide a {
    color: #ff66c4;
    text-decoration: underline;
}
.page-template-page-marketing-guide a:hover {
    color: #ff85d4;
}
</style>

<?php
get_footer();
