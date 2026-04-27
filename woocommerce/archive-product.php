<?php
/**
 * The Template for displaying product archives
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="hero py-4">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title gradient-text">Products</h1>
            <p class="hero-subtitle">Choose the right regimen for your research goals.</p>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <?php if (woocommerce_product_loop()) : ?>
            <?php woocommerce_product_loop_start(); ?>
            <?php if (wc_get_loop_prop('total')) : ?>
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            <?php endif; ?>
            <?php woocommerce_product_loop_end(); ?>
            <?php do_action('woocommerce_after_shop_loop'); ?>
        <?php else : ?>
            <?php do_action('woocommerce_no_products_found'); ?>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
