<?php
/**
 * Template Name: Checkout Page
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white">Secure Checkout</h2>
        </div>
        <?php
        if (class_exists('WooCommerce')) {
            echo do_shortcode('[woocommerce_checkout]');
        } else {
            echo '<p class="text-white text-center">WooCommerce is not active. Please install and activate WooCommerce.</p>';
        }
        ?>
    </div>
</section>

<?php
get_footer();
