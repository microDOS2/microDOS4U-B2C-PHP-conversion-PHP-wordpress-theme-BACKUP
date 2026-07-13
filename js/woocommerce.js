/**
 * microDOS4U WooCommerce JavaScript
 */

jQuery(document).ready(function($) {

    // Update cart count dynamically
    $(document.body).on('added_to_cart removed_from_cart', function() {
        $.ajax({
            url: microdos4uConfig.ajaxUrl,
            type: 'POST',
            data: {
                action: 'microdos_get_cart_count'
            },
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.data.count);
                }
            }
        });
    });

    // Safe auto-update cart quantities on change
    (function() {
        // Guard: Only run on cart page
        if (!$('body').hasClass('woocommerce-cart')) return;

        var $cartForm = $('form.woocommerce-cart-form');
        var $updateButton = $cartForm.find('button[name="update_cart"]');
        var isUpdating = false;

        // Guard: Exit if cart form or update button missing
        if (!$cartForm.length || !$updateButton.length) return;

        $cartForm.on('change', '.qty', function() {
            // Guard: Prevent re-entrant calls
            if (isUpdating) return;

            var $input = $(this);
            var quantity = parseFloat($input.val());
            var inputName = $input.attr('name') || '';

            // Guard 1: Quantity must be > 0 (never interfere with remove)
            if (!quantity || quantity <= 0) return;

            // Guard 2: Verify this is a real cart quantity input
            if (!inputName.match(/^cart\[.*\]\[qty\]$/)) return;

            // Guard 3: Don't trigger if update button is disabled
            if ($updateButton.is(':disabled')) return;

            // Safe: Trigger update
            isUpdating = true;
            $updateButton.trigger('click');

            // Re-enable after short delay
            setTimeout(function() { isUpdating = false; }, 500);
        });
    })();

    // Quick add to cart on product cards
    $('.pricing-card .btn-primary').on('click', function(e) {
        const href = $(this).attr('href');
        if (href && href.includes('add-to-cart=')) {
            e.preventDefault();
            const productId = href.split('add-to-cart=')[1];

            $.ajax({
                url: microdos4uConfig.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'woocommerce_ajax_add_to_cart',
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    if (response.error) {
                        alert(response.error);
                    } else {
                        $(document.body).trigger('added_to_cart');
                        // Show success message
                        alert('Product added to cart!');
                    }
                }
            });
        }
    });

    // === Credit Card Validation (Bug #5 Fix) ===
    // Luhn algorithm check + HTML5 pattern for card number field
    (function() {
        // Only run on checkout page
        if (!$('body').hasClass('woocommerce-checkout')) return;

        var $cardInput = $('input[name="authorizenet_card_number"], #authorizenet_card_number, input#card_number, .wc-authorize-net-cim-credit-card-new-payment-form input[name*="card"]').first();

        // Try broader selector if specific ones don't match
        if (!$cardInput.length) {
            $cardInput = $('input[name*="card_number"], input[name*="cardnumber"], input[id*="card_number"], input[id*="cardnumber"]').first();
        }

        if ($cardInput.length) {
            // Add HTML5 validation attributes
            $cardInput.attr({
                'pattern': '[0-9]{13,19}',
                'maxlength': '19',
                'inputmode': 'numeric',
                'title': 'Please enter a valid 13-19 digit credit card number'
            });

            // Luhn algorithm validation on input
            $cardInput.on('blur', function() {
                var cardNum = $(this).val().replace(/\s/g, '');
                if (cardNum && !isValidLuhn(cardNum)) {
                    $(this).addClass('woocommerce-invalid');
                    // Remove existing error
                    $(this).siblings('.card-validation-error').remove();
                    $(this).after('<span class="card-validation-error" style="color: #e2401c; font-size: 0.85em; display: block; margin-top: 4px;">Please enter a valid credit card number.</span>');
                } else {
                    $(this).removeClass('woocommerce-invalid');
                    $(this).siblings('.card-validation-error').remove();
                }
            });

            // Clear error on focus
            $cardInput.on('focus', function() {
                $(this).removeClass('woocommerce-invalid');
                $(this).siblings('.card-validation-error').remove();
            });
        }

        // Validate on form submission
        $('form.woocommerce-checkout').on('checkout_place_order', function() {
            var $card = $('input[name="authorizenet_card_number"], #authorizenet_card_number, input#card_number, input[name*="card_number"], input[name*="cardnumber"]').first();
            if ($card.length) {
                var cardNum = $card.val().replace(/\s/g, '');
                if (cardNum && !isValidLuhn(cardNum)) {
                    // Trigger WooCommerce notice
                    $(document.body).trigger('checkout_error', ['Please enter a valid credit card number.']);
                    return false; // Prevent submission
                }
            }
            return true;
        });

        // Luhn algorithm implementation
        function isValidLuhn(cardNumber) {
            if (!cardNumber || cardNumber.length < 13 || cardNumber.length > 19) return false;
            if (!/^\d+$/.test(cardNumber)) return false;

            var sum = 0;
            var isEven = false;
            for (var i = cardNumber.length - 1; i >= 0; i--) {
                var digit = parseInt(cardNumber.charAt(i), 10);
                if (isEven) {
                    digit *= 2;
                    if (digit > 9) digit -= 9;
                }
                sum += digit;
                isEven = !isEven;
            }
            return (sum % 10) === 0;
        }
    })();

});

