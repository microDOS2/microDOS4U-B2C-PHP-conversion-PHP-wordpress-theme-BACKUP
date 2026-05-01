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

<script>
// Fix duplicate order review table on checkout
(function() {
    function removeDuplicateOrderTable() {
        var tables = document.querySelectorAll('.woocommerce-checkout-review-order-table');
        if (tables.length > 1) {
            // Hide all but the first table
            for (var i = 1; i < tables.length; i++) {
                tables[i].style.display = 'none';
            }
        }
        // Also hide duplicate order review sections
        var sections = document.querySelectorAll('#order_review');
        if (sections.length > 1) {
            for (var j = 1; j < sections.length; j++) {
                sections[j].style.display = 'none';
            }
        }
    }
    // Run after page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', removeDuplicateOrderTable);
    } else {
        removeDuplicateOrderTable();
    }
    // Also run after WooCommerce updates the checkout
    jQuery(document.body).on('updated_checkout', removeDuplicateOrderTable);
})();
</script>

<?php
get_footer();
