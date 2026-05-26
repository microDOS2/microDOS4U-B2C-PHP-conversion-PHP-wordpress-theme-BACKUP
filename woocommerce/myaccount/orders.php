<?php
/**
 * Orders Template - Custom Styled
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$orders = wc_get_orders([
    'customer_id' => get_current_user_id(),
    'limit' => -1,
    'status' => ['wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending']
]);
?>

<div class="woocommerce-orders">

    <h2 class="text-2xl font-bold text-white mb-6"><?php esc_html_e('Your Orders', 'woocommerce'); ?></h2>

    <?php if (empty($orders)) : ?>

        <div class="p-8 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <p class="text-slate-400 text-lg mb-4">You haven't placed any orders yet.</p>
            <a href="/shop/" class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold transition-all duration-300" style="background-color: #44f80c; color: #0a0514;">
                Start Shopping
            </a>
        </div>

    <?php else : ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left" style="color: #94a3b8;">
                <thead>
                    <tr style="border-bottom: 2px solid #1f2b47;">
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Order', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Date', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Status', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Total', 'woocommerce'); ?></th>
                        <th class="py-4 px-4" style="color: #fff;"><?php esc_html_e('Actions', 'woocommerce'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) : ?>
                        <tr style="border-bottom: 1px solid #1a1329;">
                            <td class="py-4 px-4">
                                <a href="<?php echo esc_url($order->get_view_order_url()); ?>" style="color: #38bdf8; font-weight: 600;">
                                    #<?php echo esc_html($order->get_order_number()); ?>
                                </a>
                            </td>
                            <td class="py-4 px-4"><?php echo esc_html(date_i18n(wc_date_format(), strtotime($order->get_date_created()))); ?></td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium" 
                                      style="background-color: <?php echo $order->get_status() === 'completed' ? '#44f80c20' : '#f59e0b20'; ?>; 
                                             color: <?php echo $order->get_status() === 'completed' ? '#44f80c' : '#f59e0b'; ?>;">
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>
                                </span>
                            </td>
                            <td class="py-4 px-4" style="color: #fff; font-weight: 600;"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                            <td class="py-4 px-4">
                                <?php
                                $actions = wc_get_account_orders_actions($order);
                                if (!empty($actions)) {
                                    foreach ($actions as $key => $action) {
                                        printf('<a href="%s" class="text-sm mr-3" style="color: #38bdf8;">%s</a>', esc_url($action['url']), esc_html($action['name']));
                                    }
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>

</div>
