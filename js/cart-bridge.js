/**
 * Cart Bridge: Syncs the custom cart drawer UI with WooCommerce native cart.
 * 
 * This file overrides the cart functions from main.js to use WooCommerce
 * as the cart backend instead of localStorage. The visual cart drawer
 * behavior is preserved.
 * 
 * Loaded after main.js. Depends on jQuery and microdos4u-scripts.
 */

(function() {
    'use strict';

    // Abort if WooCommerce is not available
    if (typeof microdosCartConfig === 'undefined') {
        console.warn('[Cart Bridge] microdosCartConfig not found. Cart sync disabled.');
        return;
    }

    var config = microdosCartConfig;
    var isLoading = false;

    // Override loadCart: Fetch from WooCommerce instead of localStorage
    window.loadCart = function() {
        jQuery.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'microdos_get_cart',
                nonce: config.nonce
            },
            success: function(response) {
                if (response.success) {
                    window._wcCartData = response.data;
                    renderCartFromWC(response.data);
                }
            },
            error: function() {
                console.error('[Cart Bridge] Failed to load cart from WooCommerce');
            }
        });
    };

    // Override addToCart: Send to WooCommerce instead of localStorage
    window.addToCart = function(planId) {
        var qtyInput = document.getElementById('qty-' + planId);
        var quantity = qtyInput ? parseInt(qtyInput.value) : 1;

        jQuery.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'microdos_add_to_cart',
                nonce: config.nonce,
                product_key: planId,
                quantity: quantity
            },
            beforeSend: function() {
                isLoading = true;
            },
            success: function(response) {
                isLoading = false;
                if (response.success) {
                    // Refresh cart drawer
                    window._wcCartData = null; // Force refresh
                    loadCart();
                    openCart();

                    // Show toast with product name
                    var productName = (window.products && window.products[planId]) 
                        ? window.products[planId].name 
                        : 'Item';
                    showToast(productName + ' added to cart!');
                } else {
                    showToast('Error: ' + (response.data || 'Could not add to cart'));
                }
            },
            error: function() {
                isLoading = false;
                showToast('Network error. Please try again.');
            }
        });
    };

    // Override removeFromCart: Remove from WooCommerce
    window.removeFromCart = function(cartItemKey) {
        jQuery.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'microdos_remove_cart_item',
                nonce: config.nonce,
                cart_item_key: cartItemKey
            },
            success: function(response) {
                if (response.success) {
                    window._wcCartData = null;
                    loadCart();
                    // Reload page if cart is now empty and we're on the cart page
                    if (response.data && response.data.count === 0 && window.location.pathname.indexOf('cart') > -1) {
                        window.location.reload();
                    }
                }
            }
        });
    };

    // Override updateCartQty: Update quantity in WooCommerce
    window.updateCartQty = function(cartItemKey, change) {
        if (!window._wcCartData || !window._wcCartData.items) return;

        var item = window._wcCartData.items.find(function(i) {
            return i.cart_item_key === cartItemKey;
        });
        if (!item) return;

        var newQty = item.quantity + change;
        if (newQty < 1) {
            removeFromCart(cartItemKey);
            return;
        }

        jQuery.ajax({
            url: config.ajaxUrl,
            type: 'POST',
            data: {
                action: 'microdos_update_cart_qty',
                nonce: config.nonce,
                cart_item_key: cartItemKey,
                quantity: newQty
            },
            success: function(response) {
                if (response.success) {
                    window._wcCartData = null;
                    loadCart();
                }
            }
        });
    };

    // Override renderCart: Render from WooCommerce data
    window.renderCart = function() {
        // This function is replaced by renderCartFromWC below.
        // If called directly (from legacy code), trigger a load.
        loadCart();
    };

    function renderCartFromWC(data) {
        var cartItems = document.getElementById('cart-items');
        var cartBadge = document.getElementById('cart-badge');
        var cartSubtotal = document.getElementById('cart-subtotal');
        var cartTax = document.getElementById('cart-tax');
        var cartTotal = document.getElementById('cart-total');

        if (!cartItems) return;

        var items = data.items || [];
        var count = data.count || 0;
        var subtotal = data.subtotal || '$0.00';
        var total = data.total || '$0.00';

        if (items.length === 0) {
            cartItems.innerHTML = '<div class="text-center text-slate-400 py-8"><p>Your cart is empty</p></div>';
        } else {
            cartItems.innerHTML = items.map(function(item) {
                return `
                    <div class="flex items-center gap-3 mb-4 p-3 rounded-lg bg-slate-800/50">
                        <div class="flex-1">
                            <p class="text-white font-semibold text-sm">${escapeHtml(item.name)}</p>
                            <p class="text-slate-400 text-sm">$${item.price.toFixed(2)} each</p>
                        </div>
                        <div class="flex items-center gap-1">
                            <button onclick="updateCartQty('${item.cart_item_key}', -1)" class="w-7 h-7 rounded bg-slate-700 text-white flex items-center justify-center hover:bg-slate-600">-</button>
                            <span class="text-white w-6 text-center text-sm">${item.quantity}</span>
                            <button onclick="updateCartQty('${item.cart_item_key}', 1)" class="w-7 h-7 rounded bg-slate-700 text-white flex items-center justify-center hover:bg-slate-600">+</button>
                        </div>
                        <button onclick="removeFromCart('${item.cart_item_key}')" class="text-slate-400 hover:text-red-400 ml-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                        </button>
                    </div>
                `;
            }).join('');
        }

        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.classList.toggle('hidden', count === 0);
        }
        if (cartSubtotal) cartSubtotal.textContent = subtotal;
        if (cartTax) cartTax.textContent = '—'; // Tax shown at checkout
        if (cartTotal) cartTotal.textContent = total;
    }

    // Override proceedToCheckout: Redirect to WooCommerce checkout
    window.proceedToCheckout = function() {
        closeCart();
        window.location.href = config.checkoutUrl;
    };

    // Override updateCheckoutSummary: Use WooCommerce data
    window.updateCheckoutSummary = function() {
        // WooCommerce checkout handles its own summary.
        // This function is a no-op when using native WC checkout.
        // If custom checkout elements exist, sync them.
        var summarySubtotal = document.getElementById('summary-subtotal');
        var summaryTax = document.getElementById('summary-tax');
        var summaryTotal = document.getElementById('summary-total');
        var orderContent = document.getElementById('order-summary-content');

        if (!window._wcCartData) return;

        var items = window._wcCartData.items || [];
        var subtotal = window._wcCartData.subtotal || '$0.00';
        var total = window._wcCartData.total || '$0.00';

        if (orderContent) {
            if (items.length === 0) {
                orderContent.innerHTML = '<p class="text-slate-400">No items in cart</p>';
            } else {
                orderContent.innerHTML = items.map(function(item) {
                    return `<div class="flex justify-between text-sm">
                        <span>${escapeHtml(item.name)} x${item.quantity}</span>
                        <span>$${(item.price * item.quantity).toFixed(2)}</span>
                    </div>`;
                }).join('');
            }
        }

        if (summarySubtotal) summarySubtotal.textContent = subtotal;
        if (summaryTax) summaryTax.textContent = '—';
        if (summaryTotal) summaryTotal.textContent = total;
    };

    // Remove old localStorage functions
    window.saveCart = function() {
        // No-op: Cart is saved server-side by WooCommerce
    };

    // Helper: Escape HTML
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Listen for WooCommerce cart fragment updates
    jQuery(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function() {
        loadCart();
    });

    // Initial load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadCart);
    } else {
        loadCart();
    }

    // Migrate: Clear old localStorage cart to prevent flash of stale data
try { localStorage.removeItem('microdos_cart'); } catch(e) {}

    // === AJAX Cart Page Removal (Fix B + C) ===
    // Intercepts remove links on cart page, uses AJAX instead of page reload
    // Then triggers fragment refresh for fresh nonces on remaining items
    (function() {
        // Only run on cart page
        if (!document.querySelector('.woocommerce-cart-form')) return;

        jQuery(document).on('click', '.woocommerce-cart-form .remove', function(e) {
            e.preventDefault();
            var $link = jQuery(this);
            var cartItemKey = $link.data('cart_item_key');
            var $row = $link.closest('tr.cart_item');

            if (!cartItemKey || $link.hasClass('removing')) return;
            $link.addClass('removing').css('opacity', '0.5');

            // Use WooCommerce's built-in cart AJAX endpoint
            jQuery.ajax({
                url: wc_add_to_cart_params ? wc_add_to_cart_params.wc_ajax_url.replace('%%endpoint%%', 'remove_from_cart') : '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'woocommerce_remove_from_cart',
                    cart_item_key: cartItemKey
                },
                success: function() {
                    // Remove the row visually
                    $row.fadeOut(300, function() {
                        $row.remove();

                        // Check if cart is now empty
                        var remaining = jQuery('.woocommerce-cart-form tr.cart_item').length;
                        if (remaining === 0) {
                            // Reload to show empty cart state
                            window.location.reload();
                            return;
                        }

                        // Trigger WooCommerce fragment refresh to get fresh nonces (Fix C)
                        jQuery(document.body).trigger('wc_fragment_refresh');
                    });
                },
                error: function() {
                    $link.removeClass('removing').css('opacity', '1');
                    // Fallback: try normal link navigation
                    window.location.href = $link.attr('href');
                }
            });
        });

        // Listen for fragment refresh to update nonce values in remaining links
        jQuery(document.body).on('wc_fragments_refreshed', function() {
            // Fragments have been refreshed — remaining remove links now have fresh nonces
            console.log('[Cart Bridge] Cart fragments refreshed with new nonces');
        });
    })();

    console.log('[Cart Bridge] WooCommerce cart sync active');

})();

