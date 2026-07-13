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
// Credit card validation — Luhn algorithm + HTML5 pattern
jQuery(function($) {
    $(document.body).on('updated_checkout', function() {
        var $cardInput = $('input[name*="card"], input[id*="card"], input[name*="authorize"], input[name*="cim"]').filter(function() {
            var type = $(this).attr('type') || '';
            return type === 'text' || type === 'tel';
        }).first();

        if ($cardInput.length && !$cardInput.data('validation-added')) {
            $cardInput.attr({
                'pattern': '[0-9]{13,19}',
                'maxlength': '19',
                'inputmode': 'numeric',
                'title': 'Please enter a valid 13-19 digit credit card number'
            });
            $cardInput.data('validation-added', '1');

            $cardInput.on('blur', function() {
                var cardNum = $(this).val().replace(/\s/g, '');
                if (cardNum && !isValidLuhn(cardNum)) {
                    $(this).addClass('woocommerce-invalid');
                    $(this).siblings('.card-validation-error').remove();
                    $(this).after('<span class="card-validation-error" style="color: #e2401c; font-size: 0.85em; display: block; margin-top: 4px;">Please enter a valid credit card number.</span>');
                } else {
                    $(this).removeClass('woocommerce-invalid');
                    $(this).siblings('.card-validation-error').remove();
                }
            });

            $cardInput.on('focus', function() {
                $(this).removeClass('woocommerce-invalid');
                $(this).siblings('.card-validation-error').remove();
            });
        }
    });

    function isValidLuhn(cardNumber) {
        if (!cardNumber || cardNumber.length < 13 || cardNumber.length > 19) return false;
        if (!/^\d+$/.test(cardNumber)) return false;
        var sum = 0, isEven = false;
        for (var i = cardNumber.length - 1; i >= 0; i--) {
            var digit = parseInt(cardNumber.charAt(i), 10);
            if (isEven) { digit *= 2; if (digit > 9) digit -= 9; }
            sum += digit; isEven = !isEven;
        }
        return (sum % 10) === 0;
    }
});

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

<script>
// Clear default state selection — ensures no state is pre-selected
jQuery(function($) {
    $(document.body).on('updated_checkout', function() {
        var $stateSelect = $('select#billing_state');
        if ($stateSelect.length && $stateSelect.val()) {
            // Only clear if it's the initial load (not user-selected)
            if (!$stateSelect.data('user-changed')) {
                $stateSelect.val('').trigger('change');
            }
        }
    });
    // Also mark when user manually changes the state
    $(document).on('change', 'select#billing_state', function() {
        $(this).data('user-changed', true);
    });
});
</script>

<?php
get_footer();
