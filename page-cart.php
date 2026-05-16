<?php
/**
 * Template Name: Cart Page
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white">Shopping Cart</h2>
        </div>
        <?php
        if (class_exists('WooCommerce')) {
            echo do_shortcode('[woocommerce_cart]');
        } else {
            echo '<p class="text-white text-center">WooCommerce is not active. Please install and activate WooCommerce.</p>';
        }
        ?>
        <div class="text-center mt-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block px-6 py-3 rounded-lg font-semibold text-white border border-slate-600 hover:border-brand-dos hover:text-white transition" style="background: linear-gradient(135deg, var(--brand-micro), var(--brand-dos)); color: #000;">
                Continue Shopping
            </a>
        </div>
    </div>
</section>

<?php
get_footer();
