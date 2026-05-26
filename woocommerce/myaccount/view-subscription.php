<?php
/**
 * View Subscription Template
 *
 * Delegates to WooCommerce Subscriptions default template
 * which includes all management actions (Cancel, Suspend, Reactivate, Change Payment)
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

// Guard: WooCommerce Subscriptions must be active
if (!function_exists('wcs_get_subscription')) {
    echo '<p class="woocommerce-info" style="background-color: #150f24; border: 1px solid #1f2b47; color: #94a3b8; padding: 15px; border-radius: 0.5rem;">' . esc_html__('Subscription management is currently unavailable.', 'microdos4u') . '</p>';
    return;
}

wc_print_notices();

// Get subscription ID from the endpoint
$subscription_id = absint(get_query_var('view-subscription'));

if (empty($subscription_id)) {
    // Fallback: extract from URL path
    $uri = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']));
    $parts = explode('/', trim($uri, '/'));
    $key = array_search('view-subscription', $parts);
    if ($key !== false && isset($parts[$key + 1])) {
        $subscription_id = absint($parts[$key + 1]);
    }
}

$subscription = $subscription_id ? wcs_get_subscription($subscription_id) : false;

if (!$subscription) {
    echo '<p class="woocommerce-info" style="background-color: #150f24; border: 1px solid #1f2b47; color: #94a3b8; padding: 15px; border-radius: 0.5rem;">' . esc_html__('Subscription not found or you do not have permission to view it.', 'microdos4u') . '</p>';
    return;
}

// Get status and actions
$status = $subscription->get_status();
$status_name = wcs_get_subscription_status_name($status);

$status_colors = array(
    'active' => '#44f80c',
    'on-hold' => '#f59e0b',
    'pending' => '#f59e0b',
    'cancelled' => '#ff4444',
    'expired' => '#94a3b8',
    'pending-cancel' => '#f59e0b',
);
$status_color = isset($status_colors[$status]) ? $status_colors[$status] : '#94a3b8';

// Get subscription actions from WooCommerce Subscriptions
$actions = wcs_get_all_user_actions_for_subscription($subscription, get_current_user_id());
?>

<div class="woocommerce-MyAccount-content" style="color: #94a3b8;">

    <!-- Header -->
    <div class="mb-6 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white mb-2">
                    <?php printf(esc_html__('Subscription #%s', 'microdos4u'), esc_html($subscription->get_order_number())); ?>
                </h2>
                <span class="inline-block px-3 py-1 rounded text-sm font-medium" style="background-color: <?php echo esc_attr($status_color); ?>20; color: <?php echo esc_attr($status_color); ?>; border: 1px solid <?php echo esc_attr($status_color); ?>40;">
                    <?php echo esc_html($status_name); ?>
                </span>
            </div>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('subscriptions')); ?>" class="text-sm" style="color: #9a02d0;">
                ← <?php esc_html_e('Back to Subscriptions', 'microdos4u'); ?>
            </a>
        </div>
    </div>

    <!-- Subscription Details -->
    <div class="p-6 rounded-lg mb-6" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <h3 class="text-lg font-bold text-white mb-4"><?php esc_html_e('Subscription Details', 'microdos4u'); ?></h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Start Date', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo esc_html($subscription->get_date_to_display('start_date')); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Next Payment', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo esc_html($subscription->get_date_to_display('next_payment')); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Total', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo wp_kses_post($subscription->get_formatted_order_total()); ?></div>
            </div>
            <div>
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Payment Method', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo esc_html($subscription->get_payment_method_to_display()); ?></div>
            </div>
        </div>
    </div>

    <!-- Subscription Actions -->
    <?php if (!empty($actions)) : ?>
    <div class="p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <h3 class="text-lg font-bold text-white mb-4"><?php esc_html_e('Manage Subscription', 'microdos4u'); ?></h3>
        <div class="flex flex-wrap gap-3">
            <?php foreach ($actions as $key => $action) : 
                $btn_color = '#9a02d0';
                $btn_text = '#fff';
                if (strpos(strtolower($key), 'cancel') !== false) {
                    $btn_color = '#ff4444';
                } elseif (strpos(strtolower($key), 'suspend') !== false) {
                    $btn_color = '#f59e0b';
                    $btn_text = '#0a0514';
                } elseif (strpos(strtolower($key), 'reactivate') !== false) {
                    $btn_color = '#44f80c';
                    $btn_text = '#0a0514';
                }
            ?>
                <a href="<?php echo esc_url($action['url']); ?>" class="inline-block px-4 py-2 rounded-lg font-medium text-sm transition-all" style="background-color: <?php echo esc_attr($btn_color); ?>; color: <?php echo esc_attr($btn_text); ?>;">
                    <?php echo esc_html($action['name']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>
