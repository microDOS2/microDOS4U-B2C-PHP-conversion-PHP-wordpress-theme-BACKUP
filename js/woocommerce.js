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

});

