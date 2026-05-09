<?php
/**
 * View Subscription Template
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

wc_print_notices();

$subscription = wcs_get_subscription($subscription_id);

if (!$subscription) {
    echo '<p class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">' . esc_html__('Invalid subscription.', 'woocommerce-subscriptions') . '</p>';
    return;
}

$order = $subscription->get_parent_order();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 1100px;">

        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                <?php printf(esc_html__('Subscription #%s', 'woocommerce-subscriptions'), esc_html($subscription->get_order_number())); ?>
            </h1>
            <p class="text-slate-400">
                <?php 
                $status = $subscription->get_status();
                $status_name = wcs_get_subscription_status_name($status);
                printf(esc_html__('Status: %s', 'woocommerce-subscriptions'), '<span class="text-white font-semibold">' . esc_html($status_name) . '</span>'); 
                ?>
            </p>
        </div>

        <!-- Subscription Details -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white mb-6"><?php esc_html_e('Subscription Details', 'woocommerce-subscriptions'); ?></h2>

            <table class="w-full text-left" style="color: #94a3b8;">
                <tbody>
                    <tr class="border-b" style="border-color: #1a1329;">
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Product', 'woocommerce-subscriptions'); ?></th>
                        <td class="py-3">
                            <?php 
                            foreach ($subscription->get_items() as $item) {
                                echo esc_html($item->get_name());
                            }
                            ?>
                        </td>
                    </tr>
                    <tr class="border-b" style="border-color: #1a1329;">
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Total', 'woocommerce-subscriptions'); ?></th>
                        <td class="py-3" style="color: #44f80c;"><?php echo wp_kses_post($subscription->get_formatted_order_total()); ?></td>
                    </tr>
                    <tr class="border-b" style="border-color: #1a1329;">
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Start Date', 'woocommerce-subscriptions'); ?></th>
                        <td class="py-3"><?php echo esc_html($subscription->get_date_to_display('start_date')); ?></td>
                    </tr>
                    <tr class="border-b" style="border-color: #1a1329;">
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Next Payment', 'woocommerce-subscriptions'); ?></th>
                        <td class="py-3" style="color: #ff66c4;">
                            <?php 
                            $next_payment = $subscription->get_date('next_payment');
                            echo $next_payment ? esc_html(date_i18n(wc_date_format(), strtotime($next_payment))) : esc_html__('Not scheduled', 'woocommerce-subscriptions');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Billing', 'woocommerce-subscriptions'); ?></th>
                        <td class="py-3"><?php echo esc_html(wcs_get_subscription_period_interval_strings($subscription->get_billing_interval()) . ' ' . wcs_get_subscription_period_strings(1, $subscription->get_billing_period())); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Subscription Actions -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white mb-6"><?php esc_html_e('Actions', 'woocommerce-subscriptions'); ?></h2>

            <div class="flex flex-wrap gap-4">
                <?php
                $actions = wcs_get_all_user_actions_for_subscription($subscription, get_current_user_id());
                if (!empty($actions)) {
                    foreach ($actions as $key => $action) {
                        printf(
                            '<a href="%s" class="inline-block px-6 py-3 rounded-lg font-semibold text-center %s" style="background-color: %s; color: %s;">%s</a>',
                            esc_url($action['url']),
                            $key === 'cancel' ? '' : '',
                            $key === 'cancel' ? '#dc2626' : ($key === 'suspend' ? '#f59e0b' : '#44f80c'),
                            $key === 'cancel' ? '#fff' : '#0a0514',
                            esc_html($action['name'])
                        );
                    }
                }
                ?>
            </div>
        </div>

        <!-- Related Orders -->
        <div class="card p-8 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white mb-6"><?php esc_html_e('Related Orders', 'woocommerce-subscriptions'); ?></h2>

            <table class="w-full text-left" style="color: #94a3b8;">
                <thead>
                    <tr class="border-b" style="border-color: #1a1329;">
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Order', 'woocommerce-subscriptions'); ?></th>
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Date', 'woocommerce-subscriptions'); ?></th>
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Status', 'woocommerce-subscriptions'); ?></th>
                        <th class="py-3" style="color: #fff;"><?php esc_html_e('Total', 'woocommerce-subscriptions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $related_orders = $subscription->get_related_orders();
                    if (!empty($related_orders)) {
                        foreach ($related_orders as $order_post_id) {
                            $order = wc_get_order($order_post_id);
                            if ($order) {
                                ?>
                                <tr class="border-b" style="border-color: #1a1329;">
                                    <td class="py-3">
                                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" style="color: #38bdf8;">
                                            #<?php echo esc_html($order->get_order_number()); ?>
                                        </a>
                                    </td>
                                    <td class="py-3"><?php echo esc_html(date_i18n(wc_date_format(), strtotime($order->get_date_created()))); ?></td>
                                    <td class="py-3" style="color: #fff;"><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></td>
                                    <td class="py-3"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                </tr>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" class="py-3"><?php esc_html_e('No related orders found.', 'woocommerce-subscriptions'); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</section>
