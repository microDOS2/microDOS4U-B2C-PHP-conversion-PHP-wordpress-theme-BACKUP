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
// Fix duplicate order review table on checkout using MutationObserver
(function() {
    function removeDuplicates() {
        // Remove extra tables - keep only the FIRST one
        var tables = document.querySelectorAll('.woocommerce-checkout-review-order-table');
        for (var i = 1; i < tables.length; i++) {
            tables[i].style.display = 'none';
            tables[i].style.visibility = 'hidden';
            tables[i].style.height = '0';
            tables[i].style.overflow = 'hidden';
        }
        // Remove extra #order_review sections
        var reviews = document.querySelectorAll('#order_review');
        for (var j = 1; j < reviews.length; j++) {
            reviews[j].style.display = 'none';
            reviews[j].style.visibility = 'hidden';
            reviews[j].style.height = '0';
            reviews[j].style.overflow = 'hidden';
        }
        // Remove extra order review headings
        var headings = document.querySelectorAll('h3#order_review_heading');
        for (var k = 1; k < headings.length; k++) {
            headings[k].style.display = 'none';
        }
    }

    // Run immediately
    removeDuplicates();

    // Watch for DOM changes and remove duplicates as they appear
    var observer = new MutationObserver(function(mutations) {
        removeDuplicates();
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Also run on WooCommerce checkout update
    if (typeof jQuery !== 'undefined') {
        jQuery(document.body).on('updated_checkout', removeDuplicates);
    }
})();
</script>

<?php
get_footer();
