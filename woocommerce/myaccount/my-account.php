<?php
/**
 * My Account Template - Enhanced Dashboard
 *
 * Shows orders, subscription status, next billing date,
 * and action buttons (pause/cancel/switch).
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$customer_orders = wc_get_orders([
    'customer_id' => get_current_user_id(),
    'limit' => -1,
    'status' => ['wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending']
]);

$subscriptions = function_exists('wcs_get_users_subscriptions') 
    ? wcs_get_users_subscriptions(get_current_user_id()) 
    : [];
?>

<div class="woocommerce-MyAccount-content" style="color: #94a3b8; line-height: 1.7;">

    <!-- Welcome Header -->
    <div class="mb-8 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <h2 class="text-2xl font-bold text-white mb-2">
            <?php printf(esc_html__('Welcome, %s', 'woocommerce'), esc_html($current_user->display_name)); ?>
        </h2>
        <p class="text-slate-400">
            Manage your orders, subscriptions, and account details.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #44f80c;">
                <?php echo count($customer_orders); ?>
            </div>
            <div class="text-slate-400">Orders</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #9a02d0;">
                <?php echo count($subscriptions); ?>
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

<!-- Orders Section -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <span style="color: #44f80c; margin-right: 8px;">&#128230;</span> Your Orders
        </h3>

        <?php if (empty($customer_orders)) : ?>
            <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <p class="text-slate-400">No orders yet. <a href="/shop/" style="color: #44f80c;">Start shopping &rarr;</a></p>
            </div>
        <?php else : ?>
            <div class="rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <?php foreach ($customer_orders as $order) :
                    $order_id = $order->get_id();
                    $status = $order->get_status();
                    $status_color = ($status === 'completed') ? '#44f80c' : (($status === 'processing') ? '#f59e0b' : '#94a3b8');
                    $status_bg = ($status === 'completed') ? '#44f80c20' : (($status === 'processing') ? '#f59e0b20' : '#94a3b820');
                ?>
                <!-- Order Row -->
                <div style="border-bottom: 1px solid #1a1329;">
                    <div class="flex flex-col md:flex-row md:items-center justify-between p-4 cursor-pointer hover:bg-opacity-50"
                         style="transition: background 0.2s;"
                         onclick="toggleOrder('order-details-<?php echo $order_id; ?>', this)">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 md:gap-6 flex-1">
                            <span style="color: #38bdf8; font-weight: 600; min-width: 70px;">
                                #<?php echo esc_html($order->get_order_number()); ?>
                            </span>
                            <span class="text-slate-400 text-sm" style="min-width: 100px;">
                                <?php echo esc_html(date_i18n('M j, Y', strtotime($order->get_date_created()))); ?>
                            </span>
                            <span class="px-2 py-1 rounded text-xs font-medium inline-block" style="background-color: <?php echo $status_bg; ?>; color: <?php echo $status_color; ?>; width: fit-content;">
                                <?php echo esc_html(ucfirst($status)); ?>
                            </span>
                            <span class="text-white font-medium" style="min-width: 80px;">
                                <?php echo wp_kses_post($order->get_formatted_order_total()); ?>
                            </span>
                        </div>
                        <div class="mt-2 md:mt-0 flex items-center gap-4">
                            <a href="<?php echo esc_url($order->get_view_order_url()); ?>" 
                               class="text-sm" style="color: #38bdf8;"
                               onclick="event.stopPropagation();">
                               Full Details &rarr;
                            </a>
                            <span class="order-chevron" style="color: #94a3b8; font-size: 12px; transition: transform 0.3s;">&#9660;</span>
                        </div>
                    </div>

                    <!-- Expandable Order Details -->
                    <div id="order-details-<?php echo $order_id; ?>" style="display: none; border-top: 1px solid #1a1329; background: #0f0a1a;">
                        <div class="p-4 md:p-6">
                            <!-- Products -->
                            <h4 style="color: #fff; font-size: 14px; font-weight: 600; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Items Ordered</h4>
                            <div class="mb-4">
                                <?php foreach ($order->get_items() as $item_id => $item) : 
                                    $product = $item->get_product();
                                    $product_image = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : '';
                                ?>
                                <div class="flex items-center gap-3 py-3" style="border-bottom: 1px solid #1a1329;">
                                    <?php if ($product_image) : ?>
                                        <img src="<?php echo esc_url($product_image); ?>" alt="" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid #1f2b47;">
                                    <?php else : ?>
                                        <div style="width: 48px; height: 48px; background: #1a1329; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 20px;">&#128230;</div>
                                    <?php endif; ?>
                                    <div class="flex-1">
                                        <p style="color: #fff; font-weight: 500; margin: 0;"><?php echo esc_html($item->get_name()); ?></p>
                                        <p style="color: #94a3b8; font-size: 13px; margin: 2px 0 0;">Qty: <?php echo esc_html($item->get_quantity()); ?></p>
                                    </div>
                                    <div style="color: #44f80c; font-weight: 500;">
                                        <?php echo wp_kses_post($order->get_formatted_line_subtotal($item)); ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Two Column Layout: Shipping + Totals -->
                            <div class="flex flex-col md:flex-row gap-6 mt-4">
                                <!-- Shipping Address -->
                                <div class="flex-1">
                                    <h4 style="color: #fff; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Shipping Address</h4>
                                    <address style="color: #94a3b8; font-size: 13px; line-height: 1.7; font-style: normal;">
                                        <?php echo wp_kses_post($order->get_formatted_shipping_address() ?: $order->get_formatted_billing_address()); ?>
                                    </address>
                                    <p style="color: #94a3b8; font-size: 13px; margin-top: 8px;">
                                        <strong style="color: #64748b;">Method:</strong> <?php echo esc_html($order->get_shipping_method()); ?>
                                    </p>
                                    <p style="color: #94a3b8; font-size: 13px; margin-top: 4px;">
                                        <strong style="color: #64748b;">Payment:</strong> <?php echo esc_html($order->get_payment_method_title()); ?>
                                    </p>
                                </div>

                                <!-- Order Totals -->
                                <div style="min-width: 200px;">
                                    <h4 style="color: #fff; font-size: 14px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Order Summary</h4>
                                    <div style="font-size: 13px;">
                                        <div class="flex justify-between py-1" style="color: #94a3b8;">
                                            <span>Subtotal</span>
                                            <span><?php echo wp_kses_post($order->get_subtotal_to_display()); ?></span>
                                        </div>
                                        <div class="flex justify-between py-1" style="color: #94a3b8;">
                                            <span>Shipping</span>
                                            <span><?php echo wp_kses_post($order->get_shipping_to_display()); ?></span>
                                        </div>
                                        <?php if ($order->get_total_tax() > 0) : ?>
                                        <div class="flex justify-between py-1" style="color: #94a3b8;">
                                            <span>Tax</span>
                                            <span><?php echo wp_kses_post($order->get_total_tax()); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="flex justify-between py-2 mt-1" style="border-top: 1px solid #1f2b47; color: #fff; font-weight: 700; font-size: 14px;">
                                            <span>Total</span>
                                            <span><?php echo wp_kses_post($order->get_formatted_order_total()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (count($customer_orders) > 5) : ?>
                <div class="p-4 text-center" style="border-top: 1px solid #1f2b47;">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" style="color: #44f80c;">View all <?php echo count($customer_orders); ?> orders &rarr;</a>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Expand/Collapse Script -->
    <script>
    function toggleOrder(detailsId, headerEl) {
        var details = document.getElementById(detailsId);
        var chevron = headerEl.querySelector('.order-chevron');
        if (details.style.display === 'none') {
            details.style.display = 'block';
            chevron.style.transform = 'rotate(180deg)';
            headerEl.style.backgroundColor = 'rgba(255,255,255,0.03)';
        } else {
            details.style.display = 'none';
            chevron.style.transform = 'rotate(0deg)';
            headerEl.style.backgroundColor = '';
        }
    }
    </script>

    <!-- Subscriptions Section -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <span style="color: #9a02d0; margin-right: 8px;">🔄</span> Your Subscriptions
        </h3>

        <?php if (empty($subscriptions)) : ?>
            <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <p class="text-slate-400">No active subscriptions. <a href="/shop/" style="color: #9a02d0;">Browse subscription products →</a></p>
            </div>
        <?php else : ?>
            <?php foreach ($subscriptions as $subscription) : 
                $status = $subscription->get_status();
                $status_colors = [
                    'active' => ['#44f80c20', '#44f80c'],
                    'on-hold' => ['#f59e0b20', '#f59e0b'],
                    'pending' => ['#f59e0b20', '#f59e0b'],
                    'cancelled' => ['#dc262620', '#dc2626'],
                    'expired' => ['#94a3b820', '#94a3b8'],
                ];
                $bg_color = $status_colors[$status][0] ?? '#94a3b820';
                $text_color = $status_colors[$status][1] ?? '#94a3b8';
                $items = $subscription->get_items();
                $next_payment = $subscription->get_date('next_payment');
            ?>
                <div class="p-6 rounded-lg mb-4" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>;">
                                <?php echo esc_html(ucfirst($status)); ?>
                            </span>
                            <span class="ml-3 text-white font-semibold">
                                Subscription #<?php echo esc_html($subscription->get_order_number()); ?>
                            </span>
                        </div>
                        <div class="mt-2 md:mt-0 text-slate-400 text-sm">
                            Started: <?php echo esc_html(date_i18n('M j, Y', strtotime($subscription->get_date('start_date')))); ?>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <?php foreach ($items as $item) : ?>
                        <div class="mb-4 pb-4" style="border-bottom: 1px solid #1a1329;">
                            <p class="text-white font-semibold text-lg"><?php echo esc_html($item->get_name()); ?></p>
                            <p class="text-slate-400 text-sm">
                                <?php echo wp_kses_post($subscription->get_formatted_order_total()); ?> 
                                every <?php echo esc_html($subscription->get_billing_interval()); ?> 
                                <?php echo esc_html($subscription->get_billing_period()); ?>(s)
                            </p>
                        </div>
                    <?php endforeach; ?>

                    <!-- Next Payment -->
                    <?php if ($next_payment && $status === 'active') : ?>
                        <div class="mb-4 p-3 rounded" style="background-color: #0a0514;">
                            <p class="text-sm">
                                <span class="text-slate-400">Next payment:</span>
                                <span class="font-semibold" style="color: #ff66c4;">
                                    <?php echo esc_html(date_i18n('F j, Y', strtotime($next_payment))); ?>
                                </span>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mt-4">
                        <?php 
                        $actions = wcs_get_all_user_actions_for_subscription($subscription, get_current_user_id());
                        foreach ($actions as $key => $action) : 
                            $btn_colors = [
                                'cancel' => ['#dc2626', '#fff'],
                                'suspend' => ['#f59e0b', '#0a0514'],
                                'reactivate' => ['#44f80c', '#0a0514'],
                                'change_payment_method' => ['#150f24', '#38bdf8'],
                            ];
                            $btn_bg = $btn_colors[$key][0] ?? '#150f24';
                            $btn_text = $btn_colors[$key][1] ?? '#fff';
                            $btn_border = $key === 'change_payment_method' ? '1px solid #1f2b47' : 'none';
                        ?>
                            <a href="<?php echo esc_url($action['url']); ?>" 
                               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:opacity-80"
                               style="background-color: <?php echo $btn_bg; ?>; color: <?php echo $btn_text; ?>; border: <?php echo $btn_border; ?>;">
                                <?php echo esc_html($action['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- WooCommerce Default Content (for other endpoints) -->
    <div class="mt-8">
        <?php do_action('woocommerce_account_content'); ?>
    </div>

</div>
