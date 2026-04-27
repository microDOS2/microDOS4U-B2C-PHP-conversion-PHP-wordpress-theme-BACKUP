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
