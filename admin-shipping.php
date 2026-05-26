<?php
/**
 * Shipping Dashboard Admin Page
 * 
 * Centralized shipping management interface:
 * - View all orders ready to ship
 * - Quick-enter tracking numbers
 * - One-click "Mark as Shipped"
 * - View recently shipped orders
 * 
 * @package microDOS4U
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Register admin page
add_action('admin_menu', 'microdos_register_shipping_dashboard');
function microdos_register_shipping_dashboard() {
    add_menu_page(
        __('Shipping', 'microdos4u'),
        __('Shipping', 'microdos4u'),
        'manage_woocommerce',
        'microdos-shipping',
        'microdos_render_shipping_dashboard',
        'dashicons-car',
        26 // Position after WooCommerce (25)
    );
}

// Render the dashboard
function microdos_render_shipping_dashboard() {
    if (!current_user_can('manage_woocommerce')) {
        wp_die(__('You do not have permission to access this page.', 'microdos4u'));
    }

    // Handle mark-as-shipped POST
    if (isset($_POST['microdos_ship_action']) && check_admin_referer('microdos_ship_nonce')) {
        $order_id = intval($_POST['order_id']);
        $tracking = sanitize_text_field($_POST['tracking_number'] ?? '');
        $order = wc_get_order($order_id);
        if ($order) {
            if ($tracking) {
                $order->update_meta_data('_microdos_tracking_number', $tracking);
                $order->update_meta_data('_microdos_tracking_carrier', 'usps');
            }
            $order->update_status('shipped', __('Marked as shipped via Shipping Dashboard.', 'microdos4u'));
            $order->save();
            echo '<div class="notice notice-success is-dismissible"><p>' . sprintf(__('Order #%d marked as shipped.', 'microdos4u'), $order->get_order_number()) . '</p></div>';
        }
    }

    // Stats
    $processing_count = wc_get_orders(['status' => 'processing', 'limit' => -1, 'return' => 'ids']);
    $shipped_count = wc_get_orders(['status' => 'shipped', 'limit' => -1, 'return' => 'ids']);
    
    // Shipped today
    $today_start = date('Y-m-d 00:00:00');
    $today_end = date('Y-m-d 23:59:59');
    $shipped_today = wc_get_orders([
        'status'       => ['shipped', 'completed'],
        'date_created' => $today_start . '...' . $today_end,
        'limit'        => -1,
        'return'       => 'ids'
    ]);

    // Get tab
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'ready';

    // Pagination
    $per_page = 25;
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    ?>

    <div class="wrap microdos-shipping-dashboard">
        <h1 style="color:#44f80c;font-size:24px;margin-bottom:20px;">
            <span class="dashicons dashicons-car" style="color:#44f80c;font-size:28px;width:28px;height:28px;"></span>
            Shipping Dashboard
        </h1>

        <!-- Stats Cards -->
        <div class="microdos-stats-grid">
            <div class="microdos-stat-card stat-ready">
                <div class="stat-number"><?php echo count($processing_count); ?></div>
                <div class="stat-label">Ready to Ship</div>
            </div>
            <div class="microdos-stat-card stat-shipped-today">
                <div class="stat-number"><?php echo count($shipped_today); ?></div>
                <div class="stat-label">Shipped Today</div>
            </div>
            <div class="microdos-stat-card stat-shipped">
                <div class="stat-number"><?php echo count($shipped_count); ?></div>
                <div class="stat-label">Total Shipped</div>
            </div>
        </div>

        <!-- Tabs -->
        <h2 class="nav-tab-wrapper" style="border-color:#1f2b47;">
            <a href="<?php echo admin_url('admin.php?page=microdos-shipping&tab=ready'); ?>" class="nav-tab <?php echo $tab === 'ready' ? 'nav-tab-active' : ''; ?>" style="<?php echo $tab === 'ready' ? 'background:#150f24;border-color:#44f80c;color:#44f80c;' : 'background:#0a0514;border-color:#1f2b47;color:#94a3b8;'; ?>">
                <span class="dashicons dashicons-box" style="font-size:16px;line-height:20px;"></span> Ready to Ship
                <?php if (count($processing_count) > 0) : ?>
                    <span class="microdos-badge"><?php echo count($processing_count); ?></span>
                <?php endif; ?>
            </a>
            <a href="<?php echo admin_url('admin.php?page=microdos-shipping&tab=shipped'); ?>" class="nav-tab <?php echo $tab === 'shipped' ? 'nav-tab-active' : ''; ?>" style="<?php echo $tab === 'shipped' ? 'background:#150f24;border-color:#44f80c;color:#44f80c;' : 'background:#0a0514;border-color:#1f2b47;color:#94a3b8;'; ?>">
                <span class="dashicons dashicons-yes-alt" style="font-size:16px;line-height:20px;"></span> Shipped Orders
            </a>
        </h2>

        <?php if ($tab === 'ready') : ?>
            <!-- Ready to Ship Tab -->
            <div class="microdos-tab-content">
                <h2 style="color:#e2e8f0;margin-top:20px;">Orders Ready to Ship</h2>
                
                <?php
                $orders = wc_get_orders([
                    'status'   => 'processing',
                    'limit'    => $per_page,
                    'page'     => $paged,
                    'orderby'  => 'date',
                    'order'    => 'ASC', // Oldest first
                ]);

                if (empty($orders)) : ?>
                    <div class="microdos-empty-state">
                        <p>No orders waiting to be shipped. All caught up!</p>
                    </div>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped microdos-shipping-table">
                        <thead>
                            <tr>
                                <th style="width:80px;">Order</th>
                                <th style="width:140px;">Date</th>
                                <th>Customer</th>
                                <th style="width:100px;">Items</th>
                                <th style="width:80px;">Total</th>
                                <th style="width:180px;">Shipping Address</th>
                                <th style="width:200px;">Tracking #</th>
                                <th style="width:100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : 
                                $tracking = $order->get_meta('_microdos_tracking_number', true);
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url($order->get_edit_order_url()); ?>" target="_blank" style="color:#44f80c;font-weight:700;">
                                        #<?php echo esc_html($order->get_order_number()); ?>
                                    </a>
                                </td>
                                <td style="color:#94a3b8;"><?php echo esc_html($order->get_date_created()->date('M j, Y g:i A')); ?></td>
                                <td>
                                    <strong style="color:#e2e8f0;"><?php echo esc_html($order->get_formatted_billing_full_name()); ?></strong><br>
                                    <span style="color:#64748b;font-size:12px;"><?php echo esc_html($order->get_billing_email()); ?></span>
                                </td>
                                <td>
                                    <?php foreach ($order->get_items() as $item) : ?>
                                        <span style="color:#e2e8f0;font-size:12px;"><?php echo esc_html($item->get_name()); ?> (x<?php echo $item->get_quantity(); ?>)</span><br>
                                    <?php endforeach; ?>
                                </td>
                                <td style="color:#44f80c;font-weight:700;"><?php echo $order->get_formatted_order_total(); ?></td>
                                <td style="font-size:12px;color:#94a3b8;">
                                    <?php echo esc_html($order->get_shipping_address_1()); ?><br>
                                    <?php echo esc_html($order->get_shipping_city() . ', ' . $order->get_shipping_state() . ' ' . $order->get_shipping_postcode()); ?>
                                </td>
                                <td>
                                    <form method="post" style="margin:0;">
                                        <?php wp_nonce_field('microdos_ship_nonce'); ?>
                                        <input type="hidden" name="microdos_ship_action" value="1">
                                        <input type="hidden" name="order_id" value="<?php echo $order->get_id(); ?>">
                                        <input type="text" 
                                            name="tracking_number" 
                                            value="<?php echo esc_attr($tracking); ?>" 
                                            placeholder="USPS Tracking #" 
                                            style="width:100%;padding:6px 8px;border:1px solid #1f2b47;background:#150f24;color:#e2e8f0;border-radius:4px;font-size:13px;box-sizing:border-box;"
                                        >
                                </td>
                                <td>
                                        <button type="submit" class="button button-primary" style="background:#44f80c;border-color:#44f80c;color:#0a0514;font-weight:700;width:100%;">
                                            Mark Shipped
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

        <?php elseif ($tab === 'shipped') : ?>
            <!-- Shipped Orders Tab -->
            <div class="microdos-tab-content">
                <h2 style="color:#e2e8f0;margin-top:20px;">Shipped Orders</h2>
                
                <?php
                $orders = wc_get_orders([
                    'status'   => 'shipped',
                    'limit'    => $per_page,
                    'page'     => $paged,
                    'orderby'  => 'date',
                    'order'    => 'DESC',
                ]);

                if (empty($orders)) : ?>
                    <div class="microdos-empty-state">
                        <p>No shipped orders yet.</p>
                    </div>
                <?php else : ?>
                    <table class="wp-list-table widefat fixed striped microdos-shipping-table">
                        <thead>
                            <tr>
                                <th style="width:80px;">Order</th>
                                <th style="width:140px;">Date</th>
                                <th>Customer</th>
                                <th style="width:100px;">Items</th>
                                <th style="width:80px;">Total</th>
                                <th style="width:180px;">Tracking</th>
                                <th style="width:100px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : 
                                $tracking = $order->get_meta('_microdos_tracking_number', true);
                                $tracking_url = microdos_get_tracking_url_by_carrier($tracking, 'usps');
                            ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url($order->get_edit_order_url()); ?>" target="_blank" style="color:#44f80c;font-weight:700;">
                                        #<?php echo esc_html($order->get_order_number()); ?>
                                    </a>
                                </td>
                                <td style="color:#94a3b8;"><?php echo esc_html($order->get_date_created()->date('M j, Y g:i A')); ?></td>
                                <td>
                                    <strong style="color:#e2e8f0;"><?php echo esc_html($order->get_formatted_billing_full_name()); ?></strong><br>
                                    <span style="color:#64748b;font-size:12px;"><?php echo esc_html($order->get_billing_email()); ?></span>
                                </td>
                                <td>
                                    <?php foreach ($order->get_items() as $item) : ?>
                                        <span style="color:#e2e8f0;font-size:12px;"><?php echo esc_html($item->get_name()); ?></span><br>
                                    <?php endforeach; ?>
                                </td>
                                <td style="color:#44f80c;font-weight:700;"><?php echo $order->get_formatted_order_total(); ?></td>
                                <td>
                                    <?php if ($tracking && $tracking_url) : ?>
                                        <a href="<?php echo esc_url($tracking_url); ?>" target="_blank" style="color:#44f80c;text-decoration:underline;font-size:13px;">
                                            <?php echo esc_html($tracking); ?>
                                        </a><br>
                                        <span style="color:#64748b;font-size:11px;">USPS</span>
                                    <?php else : ?>
                                        <span style="color:#64748b;font-size:12px;">No tracking</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="microdos-status-badge status-shipped">Shipped</span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <style>
    /* Shipping Dashboard Dark Theme */
    .microdos-shipping-dashboard {
        background: #0a0514;
        min-height: 100vh;
        padding: 20px;
        margin: 0 -20px -20px -20px;
    }
    .microdos-shipping-dashboard h1 {
        color: #44f80c;
    }
    .microdos-shipping-dashboard .nav-tab-wrapper {
        border-bottom: 2px solid #1f2b47;
        padding-top: 0;
    }
    .microdos-shipping-dashboard .nav-tab {
        background: #0a0514;
        border: 1px solid #1f2b47;
        color: #94a3b8;
        margin-right: 4px;
        padding: 8px 16px;
    }
    .microdos-shipping-dashboard .nav-tab:hover {
        background: #150f24;
        color: #e2e8f0;
    }
    .microdos-shipping-dashboard .nav-tab-active {
        background: #150f24 !important;
        border-bottom-color: #44f80c !important;
        color: #44f80c !important;
    }

    /* Stats Grid */
    .microdos-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
        margin: 20px 0;
    }
    .microdos-stat-card {
        background: #150f24;
        border: 1px solid #1f2b47;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        border-left: 4px solid #44f80c;
    }
    .microdos-stat-card.stat-ready {
        border-left-color: #ff66c4;
    }
    .microdos-stat-card.stat-shipped-today {
        border-left-color: #44f80c;
    }
    .microdos-stat-card.stat-shipped {
        border-left-color: #38bdf8;
    }
    .microdos-stat-card .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: #e2e8f0;
        line-height: 1;
    }
    .microdos-stat-card.stat-ready .stat-number {
        color: #ff66c4;
    }
    .microdos-stat-card .stat-label {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 4px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Badges */
    .microdos-badge {
        background: #ef4444;
        color: #fff;
        border-radius: 10px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 700;
        margin-left: 6px;
        vertical-align: middle;
    }

    /* Table */
    .microdos-shipping-table {
        background: transparent !important;
        border: 1px solid #1f2b47;
    }
    .microdos-shipping-table thead th {
        background: #150f24 !important;
        color: #94a3b8;
        font-weight: 600;
        border-bottom: 2px solid #1f2b47;
        padding: 12px;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .microdos-shipping-table tbody tr {
        background: #150f24;
    }
    .microdos-shipping-table tbody tr:nth-child(even) {
        background: #0a0514;
    }
    .microdos-shipping-table tbody tr:hover {
        background: #1a1040;
    }
    .microdos-shipping-table tbody td {
        border-bottom: 1px solid #1f2b47;
        padding: 12px;
        vertical-align: top;
    }

    /* Status Badges */
    .microdos-status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .microdos-status-badge.status-shipped {
        background: #44f80c20;
        color: #44f80c;
    }
    .microdos-status-badge.status-processing {
        background: #ff66c420;
        color: #ff66c4;
    }

    /* Empty State */
    .microdos-empty-state {
        background: #150f24;
        border: 1px solid #1f2b47;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        margin-top: 20px;
    }
    .microdos-empty-state p {
        color: #64748b;
        font-size: 16px;
    }

    /* Form inputs in table */
    .microdos-shipping-table input[type="text"] {
        background: #150f24 !important;
        border: 1px solid #1f2b47 !important;
        color: #e2e8f0 !important;
        padding: 6px 8px;
        border-radius: 4px;
        font-size: 13px;
    }
    .microdos-shipping-table input[type="text"]:focus {
        border-color: #44f80c !important;
        outline: none;
        box-shadow: 0 0 0 1px #44f80c;
    }
    .microdos-shipping-table .button-primary {
        background: #44f80c !important;
        border-color: #44f80c !important;
        color: #0a0514 !important;
        font-weight: 700;
    }
    .microdos-shipping-table .button-primary:hover {
        background: #3de00b !important;
        border-color: #3de00b !important;
    }
    </style>
    <?php
}
