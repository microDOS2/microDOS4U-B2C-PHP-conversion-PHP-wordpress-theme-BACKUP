<?php
/**
 * My Account Template - Custom Styled
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="woocommerce-MyAccount-content" style="color: #94a3b8; line-height: 1.7;">

    <!-- Welcome Message -->
    <div class="mb-8 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <h2 class="text-2xl font-bold text-white mb-2">
            <?php
            $current_user = wp_get_current_user();
            printf(esc_html__('Welcome, %s', 'woocommerce'), esc_html($current_user->display_name));
            ?>
        </h2>
        <p class="text-slate-400">
            From your account dashboard you can view your orders, manage your subscriptions, 
            and edit your account details.
        </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #44f80c;">
                <?php 
                $customer_orders = wc_get_orders(array('customer_id' => get_current_user_id(), 'limit' => -1));
                echo count($customer_orders); 
                ?>
            </div>
            <div class="text-slate-400">Orders</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #9a02d0;">
                <?php
                if (function_exists('wcs_get_users_subscriptions')) {
                    $subs = wcs_get_users_subscriptions(get_current_user_id());
                    echo count($subs);
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="text-slate-400">Subscriptions</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #ff66c4;">
                <?php
                $customer = new WC_Customer(get_current_user_id());
                echo wp_kses_post(wc_price($customer->get_total_spent()));
                ?>
            </div>
            <div class="text-slate-400">Total Spent</div>
        </div>
    </div>

    <!-- Let WooCommerce render its default content (navigation + content) -->
    <?php do_action('woocommerce_account_content'); ?>

</div>
