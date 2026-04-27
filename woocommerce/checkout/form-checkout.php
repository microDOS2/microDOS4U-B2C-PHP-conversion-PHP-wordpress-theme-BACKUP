<?php
/**
 * Checkout Form
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'microdos4u')));
    return;
}
?>

<section class="py-5">
    <div class="container">
        <h1 class="gradient-text mb-4">Checkout</h1>
        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="col2-set" id="customer_details">
                    <div class="col-1">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>
                    <div class="col-2">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>
                </div>
                <div id="order_review" class="woocommerce-checkout-review-order">
                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
                    <h3 id="order_review_heading"><?php esc_html_e('Your order', 'microdos4u'); ?></h3>
                    <?php do_action('woocommerce_checkout_before_order_review'); ?>
                    <table class="shop_table woocommerce-checkout-review-order-table">
                        <thead>
                            <tr>
                                <th class="product-name"><?php esc_html_e('Product', 'microdos4u'); ?></th>
                                <th class="product-total"><?php esc_html_e('Subtotal', 'microdos4u'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            do_action('woocommerce_review_order_before_cart_contents');
                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                    ?>
                                    <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                        <td class="product-name">
                                            <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                            <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); ?>
                                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                        </td>
                                        <td class="product-total">
                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            do_action('woocommerce_review_order_after_cart_contents');
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="cart-subtotal">
                                <th><?php esc_html_e('Subtotal', 'microdos4u'); ?></th>
                                <td><?php wc_cart_totals_subtotal_html(); ?></td>
                            </tr>
                            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                    <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                                    <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                <?php do_action('woocommerce_review_order_before_shipping'); ?>
                                <?php wc_cart_totals_shipping_html(); ?>
                                <?php do_action('woocommerce_review_order_after_shipping'); ?>
                            <?php endif; ?>
                            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                <tr class="fee">
                                    <th><?php echo esc_html($fee->name); ?></th>
                                    <td><?php wc_cart_totals_fee_html($fee); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                    <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                        <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                            <th><?php echo esc_html($tax->label); ?></th>
                                            <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="tax-total">
                                        <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                        <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php do_action('woocommerce_review_order_before_order_total'); ?>
                            <tr class="order-total">
                                <th><?php esc_html_e('Total', 'microdos4u'); ?></th>
                                <td><?php wc_cart_totals_order_total_html(); ?></td>
                            </tr>
                            <?php do_action('woocommerce_review_order_after_order_total'); ?>
                        </tfoot>
                    </table>
                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>
            </div>
            <?php do_action('woocommerce_checkout_after_customer_details'); ?>
            <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
            <?php do_action('woocommerce_checkout_order_review'); ?>
        </form>
        <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
    </div>
</section>
