<?php
/**
 * Template Name: Shipping Portal
 *
 * Standalone shipping dashboard for the shipping department.
 * Phase 1: Bulk ship, search/filter, estimated weight, auto-refresh
 *
 * @package microDOS4U
 */

// Require login
if (!defined('ABSPATH')) exit;
if (!is_user_logged_in()) { auth_redirect(); exit; }
if (!current_user_can('manage_woocommerce')) {
    wp_die('<h1>Access Denied</h1><p>You do not have permission to access the shipping portal.</p>', 403);
}

// ─── HANDLE BULK SHIP ───
$notice = '';
if (isset($_POST['microdos_bulk_ship']) && check_admin_referer('microdos_portal_nonce')) {
    $order_ids = array_map('intval', (array) ($_POST['bulk_order_ids'] ?? []));
    $shipped   = 0;
    foreach ($order_ids as $oid) {
        $order = wc_get_order($oid);
        if (!$order) continue;
        $tracking_key = 'tracking_' . $oid;
        $tracking = sanitize_text_field($_POST[$tracking_key] ?? '');
        if ($tracking) {
            $order->update_meta_data('_microdos_tracking_number', $tracking);
            $order->update_meta_data('_microdos_tracking_carrier', 'usps');
        }
        $order->update_status('shipped', __('Bulk shipped via portal.', 'microdos4u'));
        $order->save();
        $shipped++;
    }
    if ($shipped) {
        $notice = '<div class="portal-notice portal-success">' . $shipped . ' order(s) marked as shipped. Customer emails sent.</div>';
    }
}

// ─── HANDLE SINGLE SHIP ───
if (isset($_POST['microdos_portal_ship']) && check_admin_referer('microdos_portal_nonce')) {
    $order_id = intval($_POST['order_id']);
    $tracking = sanitize_text_field($_POST['tracking_number'] ?? '');
    $order = wc_get_order($order_id);
    if ($order) {
        if ($tracking) {
            $order->update_meta_data('_microdos_tracking_number', $tracking);
            $order->update_meta_data('_microdos_tracking_carrier', 'usps');
        }
        $order->update_status('shipped', __('Shipped via portal.', 'microdos4u'));
        $order->save();
        $notice = '<div class="portal-notice portal-success">Order #' . esc_html($order->get_order_number()) . ' marked as shipped.</div>';
    }
}

// ─── PACKING SLIP ───
if (isset($_GET['action']) && $_GET['action'] === 'packing-slip' && isset($_GET['order_id'])) {
    $slip_order = wc_get_order(intval($_GET['order_id']));
    if ($slip_order && current_user_can('manage_woocommerce')) {
        $sw = microdos_estimated_weight($slip_order);
        ?><!DOCTYPE html><html><head><meta charset="UTF-8"><title>Packing Slip #<?php echo $slip_order->get_order_number(); ?></title>
        <style>body{font-family:Arial,sans-serif;font-size:13px;color:#333;max-width:600px;margin:40px auto;padding:20px;border:1px solid #ddd}
        .header{text-align:center;border-bottom:2px solid #44f80c;padding-bottom:15px;margin-bottom:20px}
        .header h1{color:#44f80c;margin:0;font-size:22px}.header p{color:#666;margin:4px 0}
        .section{margin-bottom:18px}.section h3{border-bottom:1px solid #eee;padding-bottom:6px;margin-bottom:10px;color:#333;font-size:14px}
        .row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0f0f0}
        .row:last-child{border-bottom:none}.label{color:#666}.value{font-weight:600}
        table{width:100%;border-collapse:collapse;margin-top:10px}th{text-align:left;border-bottom:2px solid #333;padding:8px;font-size:12px}td{padding:8px;border-bottom:1px solid #eee}
        .footer{margin-top:30px;text-align:center;color:#999;font-size:11px;border-top:1px solid #eee;padding-top:15px}
        @media print{body{border:none;margin:0;padding:0}.no-print{display:none}}.no-print{text-align:center;margin-bottom:20px}
        .no-print button{background:#44f80c;color:#0a0514;border:none;padding:10px 24px;border-radius:6px;font-weight:700;cursor:pointer}
        </style></head><body>
        <div class="no-print"><button onclick="window.print()">&#128424; Print Packing Slip</button></div>
        <div class="header"><h1>microDOS(2)</h1><p>Packing Slip &middot; Order #<?php echo $slip_order->get_order_number(); ?></p><p><?php echo date('F j, Y g:i A'); ?></p></div>
        <div class="section"><h3>Ship To</h3><strong><?php echo esc_html($slip_order->get_formatted_shipping_full_name()); ?></strong><br><?php echo esc_html($slip_order->get_shipping_address_1()); ?><br><?php if($slip_order->get_shipping_address_2()) echo esc_html($slip_order->get_shipping_address_2()) . '<br>'; ?><?php echo esc_html($slip_order->get_shipping_city() . ', ' . $slip_order->get_shipping_state() . ' ' . $slip_order->get_shipping_postcode()); ?><br><?php echo esc_html($slip_order->get_shipping_country()); ?></div>
        <div class="section"><h3>Order Details</h3><div class="row"><span class="label">Order Date</span><span class="value"><?php echo $slip_order->get_date_created()->date('M j, Y'); ?></span></div><div class="row"><span class="label">Est. Weight</span><span class="value"><?php echo $sw['g']; ?>g / <?php echo $sw['oz']; ?> oz</span></div></div>
        <div class="section"><h3>Items</h3><table><thead><tr><th>Product</th><th style="text-align:center">Qty</th></tr></thead><tbody><?php foreach ($slip_order->get_items() as $item) : ?><tr><td><?php echo esc_html($item->get_name()); ?></td><td style="text-align:center;font-weight:700"><?php echo $item->get_quantity(); ?></td></tr><?php endforeach; ?></tbody></table></div>
        <div class="footer">microDOS(2) &middot; Thank you for your order &middot; For research purposes only</div>
        </body></html><?php exit;
    }
}

// ─── SAVE ORDER NOTE ───
if (isset($_POST['microdos_save_note']) && check_admin_referer('microdos_portal_nonce')) {
    $note_order_id = intval($_POST['note_order_id']);
    $note_text = sanitize_textarea_field($_POST['order_note_text'] ?? '');
    $note_order = wc_get_order($note_order_id);
    if ($note_order) {
        $note_order->update_meta_data('_microdos_shipping_note', $note_text);
        $note_order->save();
        $notice = '<div class="portal-notice portal-success">Note saved for Order #' . esc_html($note_order->get_order_number()) . '</div>';
    }
}

// ─── EXPORT CSV ───
if (isset($_GET['export']) && $_GET['export'] === 'csv' && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'microdos_export_nonce')) {
    $export_status = isset($_GET['tab']) && $_GET['tab'] === 'shipped' ? ['shipped','completed'] : 'processing';
    $export_orders = wc_get_orders([
        'status'   => $export_status,
        'limit'    => -1,
        'orderby'  => 'date',
        'order'    => 'DESC',
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="orders-' . sanitize_title($tab) . '-' . date('Y-m-d') . '.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Order #', 'Date', 'Customer', 'Email', 'Items', 'Total', 'Address', 'City', 'State', 'ZIP', 'Tracking', 'Affiliate', 'Notes']);

    foreach ($export_orders as $eo) {
        $e_items = [];
        foreach ($eo->get_items() as $ei) {
            $e_items[] = $ei->get_name() . ' x' . $ei->get_quantity();
        }
        $e_tracking = $eo->get_meta('_microdos_tracking_number', true);
        $e_note = $eo->get_meta('_microdos_shipping_note', true);

        // Get affiliate
        $e_affiliate = '';
        if (function_exists('affiliate_wp')) {
            $referrals = affiliate_wp()->referrals->get_referrals(['reference' => $eo->get_id(), 'number' => 1]);
            if (!empty($referrals)) {
                                $aff = affiliate_wp()->affiliates->get_affiliate($referrals[0]->affiliate_id);
                if ($aff) {
                    $aff_user = get_userdata($aff->user_id);
                    $e_affiliate = $aff_user ? $aff_user->display_name : '';
                }
            }
        }

        fputcsv($output, [
            $eo->get_order_number(),
            $eo->get_date_created()->date('Y-m-d H:i:s'),
            $eo->get_formatted_billing_full_name(),
            $eo->get_billing_email(),
            implode('; ', $e_items),
            $eo->get_total(),
            $eo->get_shipping_address_1(),
            $eo->get_shipping_city(),
            $eo->get_shipping_state(),
            $eo->get_shipping_postcode(),
            $e_tracking,
            $e_affiliate,
            $e_note,
        ]);
    }
    fclose($output);
    exit;
}

// ─── CREATE ORDER ───
$create_notice = '';
if (isset($_POST['microdos_create_order']) && check_admin_referer('microdos_create_order_nonce')) {
    $billing_first = sanitize_text_field($_POST['billing_first_name'] ?? '');
    $billing_last  = sanitize_text_field($_POST['billing_last_name'] ?? '');
    $billing_email = sanitize_email($_POST['billing_email'] ?? '');
    $billing_phone = sanitize_text_field($_POST['billing_phone'] ?? '');
    $address_1     = sanitize_text_field($_POST['shipping_address_1'] ?? '');
    $address_2     = sanitize_text_field($_POST['shipping_address_2'] ?? '');
    $city          = sanitize_text_field($_POST['shipping_city'] ?? '');
    $state         = sanitize_text_field($_POST['shipping_state'] ?? '');
    $postcode      = sanitize_text_field($_POST['shipping_postcode'] ?? '');
    $product_id    = intval($_POST['product_id'] ?? 0);
    $quantity      = max(1, intval($_POST['quantity'] ?? 1));
    $order_note    = sanitize_textarea_field($_POST['order_note'] ?? '');

    if ($billing_first && $billing_last && $billing_email && $address_1 && $city && $product_id) {
        $new_order = wc_create_order();
        $product = wc_get_product($product_id);
        if ($product && $new_order) {
            $new_order->add_product($product, $quantity);
            $new_order->set_billing_first_name($billing_first);
            $new_order->set_billing_last_name($billing_last);
            $new_order->set_billing_email($billing_email);
            $new_order->set_billing_phone($billing_phone);
            $new_order->set_shipping_first_name($billing_first);
            $new_order->set_shipping_last_name($billing_last);
            $new_order->set_shipping_address_1($address_1);
            $new_order->set_shipping_address_2($address_2);
            $new_order->set_shipping_city($city);
            $new_order->set_shipping_state($state);
            $new_order->set_shipping_postcode($postcode);
            $new_order->set_shipping_country('US');
            $new_order->set_payment_method('');
            $new_order->set_created_via('shipping_portal');
            if ($order_note) {
                $new_order->add_order_note($order_note, false, false);
            }
            $new_order->calculate_totals();
            $new_order->update_status('processing', __('Created via Shipping Portal.', 'microdos4u'));
            $create_notice = '<div class="portal-notice portal-success">Order #' . esc_html($new_order->get_order_number()) . ' created successfully.</div>';
        } else {
            $create_notice = '<div class="portal-notice portal-error">Invalid product selected.</div>';
        }
    } else {
        $create_notice = '<div class="portal-notice portal-error">Please fill in all required fields.</div>';
    }
}

// ─── SEARCH ───
$search = isset($_GET['q']) ? sanitize_text_field($_GET['q']) : '';

// ─── TAB / PAGINATION / FILTERS ───
$tab    = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'ready';
$page   = isset($_GET['portal_page']) ? max(1, intval($_GET['portal_page'])) : 1;
$per    = 25;
$range  = isset($_GET['range']) ? sanitize_text_field($_GET['range']) : '';
$sort   = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'date_asc';

// ─── DATE RANGE ───
$date_after = '';
$date_before = '';
switch ($range) {
    case 'today':
        $date_after = date('Y-m-d 00:00:00');
        $date_before = date('Y-m-d 23:59:59');
        break;
    case 'yesterday':
        $date_after = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $date_before = date('Y-m-d 23:59:59', strtotime('-1 day'));
        break;
    case 'week':
        $date_after = date('Y-m-d 00:00:00', strtotime('-7 days'));
        break;
    case 'month':
        $date_after = date('Y-m-d 00:00:00', strtotime('-30 days'));
        break;
}

// ─── STATS ───
$processing_ids = wc_get_orders(['status' => 'processing', 'limit' => -1, 'return' => 'ids']);
$shipped_ids    = wc_get_orders(['status' => 'shipped',    'limit' => -1, 'return' => 'ids']);
$today_start    = date('Y-m-d 00:00:00');
$today_end      = date('Y-m-d 23:59:59');
$shipped_today  = wc_get_orders(['status' => ['shipped','completed'], 'date_modified' => $today_start . '...' . $today_end, 'limit' => -1, 'return' => 'ids']);

// ─── QUERY ARGS ───
$sort_field = 'date';
$sort_dir   = 'ASC';
switch ($sort) {
    case 'date_desc':   $sort_field = 'date'; $sort_dir = 'DESC'; break;
    case 'total_asc':   $sort_field = 'total'; $sort_dir = 'ASC'; break;
    case 'total_desc':  $sort_field = 'total'; $sort_dir = 'DESC'; break;
    case 'name_asc':    $sort_field = 'billing_last_name'; $sort_dir = 'ASC'; break;
    case 'name_desc':   $sort_field = 'billing_last_name'; $sort_dir = 'DESC'; break;
    default:            $sort_field = 'date'; $sort_dir = ($tab === 'ready') ? 'ASC' : 'DESC';
}

if ($tab === 'ready') {
    $query_args = ['status' => 'processing', 'limit' => $per, 'page' => $page, 'orderby' => $sort_field, 'order' => $sort_dir];
} else {
    $query_args = ['status' => ['shipped','completed'], 'limit' => $per, 'page' => $page, 'orderby' => $sort_field, 'order' => $sort_dir];
}

// Apply date range
if ($date_after || $date_before) {
    if ($date_after && $date_before) {
        $query_args['date_created'] = $date_after . '...' . $date_before;
    } elseif ($date_after) {
        $query_args['date_after'] = $date_after;
    }
}

// Apply search
if ($search !== '') {
    $query_args['_shipping_first_name'] = $search; // fallback - we'll filter manually
}

$orders      = wc_get_orders($query_args);
$total_items = ($tab === 'ready') ? count($processing_ids) : count($shipped_ids);

// Manual search filter (more reliable than WC meta search)
if ($search !== '' && !empty($orders)) {
    $filtered = [];
    $s_lower = strtolower($search);
    foreach ($orders as $order) {
        $haystack = strtolower(
            $order->get_order_number() . ' ' .
            $order->get_formatted_billing_full_name() . ' ' .
            $order->get_billing_email() . ' ' .
            $order->get_shipping_city() . ' ' .
            $order->get_shipping_state() . ' ' .
            $order->get_shipping_postcode()
        );
        if (strpos($haystack, $s_lower) !== false) {
            $filtered[] = $order;
        }
    }
    $orders = $filtered;
    $total_items = count($filtered);
}

$total_pages = max(1, ceil($total_items / $per));

// ─── WEIGHT CALC ───
function microdos_estimated_weight($order) {
    $bottles = 0;
    $cards   = 0;
    foreach ($order->get_items() as $item) {
        $name = strtolower($item->get_name());
        $qty  = $item->get_quantity();
        if (strpos($name, 'pill') !== false || strpos($name, 'bottle') !== false) {
            $bottles += $qty;
        }
        if (strpos($name, 'trial') !== false || strpos($name, 'card') !== false || strpos($name, 'starter') !== false) {
            $cards += $qty;
        }
    }
    $item_weight = ($bottles * 7) + ($cards * 0.7);
    $pkg_weight  = $bottles > 0 ? 15 : 5;
    $total_g     = $item_weight + $pkg_weight;
    $total_oz    = $total_g / 28.35;
    return ['g' => round($total_g, 1), 'oz' => round($total_oz, 1), 'bottles' => $bottles, 'cards' => $cards];
}

// ─── TRACKING URL HELPER ───
function portal_tracking_url($tracking, $carrier = 'usps') {
    if (!$tracking) return '';
    switch ($carrier) {
        case 'usps':  return 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . esc_attr($tracking);
        case 'ups':   return 'https://www.ups.com/track?tracknum=' . esc_attr($tracking);
        case 'fedex': return 'https://www.fedex.com/fedextrack/?trknbr=' . esc_attr($tracking);
        default:      return '';
    }
}

// ─── TODAY REVENUE ───
$today_revenue = 0;
if ($tab === 'ready' && !empty($processing_ids)) {
    foreach ($processing_ids as $pid) {
        $o = wc_get_order($pid);
        if ($o) $today_revenue += (float) $o->get_total();
    }
}

wp_head();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="300">
<title>Shipping Portal - microDOS(2)</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{background:#0a0514;color:#e2e8f0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Arial,sans-serif;font-size:14px;line-height:1.5;min-height:100vh}

/* Header */
.portal-header{background:linear-gradient(135deg,#0a0514 0%,#1a1040 100%);border-bottom:1px solid #1f2b47;padding:0 24px;height:56px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
.portal-header-left{display:flex;align-items:center;gap:12px}
.portal-logo{font-size:20px;font-weight:700;color:#44f80c;letter-spacing:1px}
.portal-subtitle{font-size:12px;color:#64748b;text-transform:uppercase;letter-spacing:1px;border-left:1px solid #1f2b47;padding-left:12px}
.portal-user{display:flex;align-items:center;gap:12px;font-size:13px;color:#94a3b8}
.portal-user a{color:#94a3b8;text-decoration:none;transition:color .2s}
.portal-user a:hover{color:#44f80c}

/* Container */
.portal-container{max-width:1480px;margin:0 auto;padding:20px 24px}

/* Notice */
.portal-notice{padding:12px 16px;border-radius:8px;margin-bottom:16px;font-size:14px;font-weight:500}
.portal-success{background:#44f80c15;border:1px solid #44f80c40;color:#44f80c}
.portal-error{background:#ef444415;border:1px solid #ef444440;color:#ef4444}

/* Stats */
.portal-stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:12px;margin-bottom:20px}
.portal-stat{background:#150f24;border:1px solid #1f2b47;border-radius:10px;padding:16px 20px;border-left:4px solid #44f80c;transition:transform .15s,border-color .15s}
.portal-stat:hover{transform:translateY(-1px);border-color:#44f80c}
.portal-stat.ready{border-left-color:#ff66c4}.portal-stat.ready:hover{border-color:#ff66c4}
.portal-stat.today{border-left-color:#44f80c}
.portal-stat.total{border-left-color:#38bdf8}.portal-stat.total:hover{border-color:#38bdf8}
.portal-stat.revenue{border-left-color:#9a02d0}.portal-stat.revenue:hover{border-color:#9a02d0}
.portal-stat-num{font-size:30px;font-weight:700;color:#fff;line-height:1;margin-bottom:4px}
.portal-stat.ready .portal-stat-num{color:#ff66c4}
.portal-stat.revenue .portal-stat-num{color:#9a02d0}
.portal-stat-label{font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:1px}

/* Toolbar */
.portal-toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:16px;flex-wrap:wrap}
.portal-tabs{display:flex;gap:2px;background:#150f24;border-radius:8px;padding:3px;width:fit-content}
.portal-tab{padding:8px 20px;border-radius:6px;text-decoration:none;color:#94a3b8;font-size:13px;font-weight:600;transition:all .2s;display:flex;align-items:center;gap:8px;border:1px solid transparent}
.portal-tab:hover{color:#e2e8f0;background:#1a104040}
.portal-tab.active{background:#1a1040;color:#44f80c;border-color:#2d2255}
.portal-tab-badge{background:#ef4444;color:#fff;border-radius:10px;padding:1px 7px;font-size:11px;font-weight:700;min-width:18px;text-align:center}

/* Search */
.portal-search{display:flex;align-items:center;gap:8px;background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:2px 12px;width:320px;max-width:100%}
.portal-search input{background:transparent;border:none;color:#e2e8f0;font-size:13px;padding:7px 4px;width:100%;outline:none}
.portal-search input::placeholder{color:#64748b}
.portal-search button{background:transparent;border:none;color:#64748b;cursor:pointer;padding:4px;font-size:15px}
.portal-search button:hover{color:#44f80c}

/* Bulk bar */
.portal-bulk-bar{display:none;align-items:center;gap:10px;background:#1a1040;border:1px solid #44f80c40;border-radius:8px;padding:10px 16px;margin-bottom:12px}
.portal-bulk-bar.active{display:flex}
.portal-bulk-bar span{color:#94a3b8;font-size:13px}
.portal-btn-bulk{background:#44f80c;color:#0a0514;border:none;border-radius:6px;padding:8px 20px;font-size:13px;font-weight:700;cursor:pointer;transition:all .2s}
.portal-btn-bulk:hover{background:#3de00b}

/* Table wrap */
.portal-table-wrap{background:#150f24;border:1px solid #1f2b47;border-radius:10px;overflow:hidden}
.portal-table-header{padding:14px 20px;border-bottom:1px solid #1f2b47;display:flex;align-items:center;justify-content:space-between}
.portal-table-title{font-size:15px;font-weight:700;color:#e2e8f0}
.portal-table-count{font-size:12px;color:#64748b}

.portal-table{width:100%;border-collapse:collapse}
.portal-table th{background:#0a0514;color:#94a3b8;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;padding:10px 12px;text-align:left;border-bottom:1px solid #1f2b47;white-space:nowrap}
.portal-table td{padding:12px;border-bottom:1px solid #1f2b47;vertical-align:top;font-size:13px}
.portal-table tbody tr{background:#150f24;transition:background .15s}
.portal-table tbody tr:nth-child(even){background:#120d20}
.portal-table tbody tr:hover{background:#1a1040}
.portal-table tbody tr:last-child td{border-bottom:none}

/* Cell styles */
.cell-order a{color:#44f80c;font-weight:700;text-decoration:none;font-size:14px}.cell-order a:hover{text-decoration:underline}
.cell-date{color:#94a3b8;font-size:12px}
.cell-name{font-weight:600;color:#e2e8f0}
.cell-email{color:#64748b;font-size:11px}
.cell-items{font-size:12px;color:#e2e8f0;line-height:1.7}
.cell-items .qty{color:#64748b}
.cell-total{color:#44f80c;font-weight:700;font-size:14px}
.cell-address{font-size:12px;color:#94a3b8;line-height:1.6}
.cell-weight{font-size:12px;color:#38bdf8;font-weight:600}
.cell-weight .detail{color:#64748b;font-weight:400;font-size:11px}
.cell-tracking a{color:#44f80c;font-size:12px;text-decoration:underline}
.cell-tracking .no-track{color:#64748b;font-size:12px}

/* Checkbox */
.portal-check{width:18px;height:18px;cursor:pointer;accent-color:#44f80c}

/* Tracking input */
.portal-tracking-input{width:100%;padding:7px 10px;border:1px solid #1f2b47;background:#0a0514;color:#e2e8f0;border-radius:6px;font-size:12px;font-family:'Courier New',monospace;transition:border-color .2s}
.portal-tracking-input:focus{outline:none;border-color:#44f80c;box-shadow:0 0 0 2px #44f80c20}

/* Buttons */
.portal-btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:7px 14px;border:none;border-radius:6px;font-size:12px;font-weight:700;cursor:pointer;transition:all .2s;text-decoration:none;white-space:nowrap}
.portal-btn-ship{background:#44f80c;color:#0a0514;width:100%}.portal-btn-ship:hover{background:#3de00b;transform:translateY(-1px);box-shadow:0 4px 12px #44f80c30}
.portal-btn-view{background:#1a1040;color:#94a3b8;border:1px solid #2d2255;font-size:11px;padding:5px 10px}.portal-btn-view:hover{background:#2d2255;color:#e2e8f0}

/* Badges */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:700;text-transform:uppercase}
.badge-processing{background:#ff66c420;color:#ff66c4}
.badge-shipped{background:#44f80c20;color:#44f80c}
.badge-completed{background:#38bdf820;color:#38bdf8}
.badge-product{font-size:11px;padding:2px 8px;border-radius:6px;font-weight:600;margin-right:4px}
.badge-bottle{background:#44f80c20;color:#44f80c}
.badge-card{background:#9a02d020;color:#9a02d0}

/* Pagination */
.portal-pagination{display:flex;justify-content:center;gap:4px;padding:14px;border-top:1px solid #1f2b47}
.portal-pagination a,.portal-pagination span{padding:7px 12px;border-radius:6px;font-size:13px;text-decoration:none;min-width:34px;text-align:center}
.portal-pagination a{background:#150f24;color:#94a3b8;border:1px solid #1f2b47}.portal-pagination a:hover{background:#1a1040;color:#e2e8f0;border-color:#2d2255}
.portal-pagination span.current{background:#44f80c;color:#0a0514;font-weight:700}

/* Empty */
.portal-empty{padding:50px 20px;text-align:center}.portal-empty p{color:#64748b;font-size:15px}

/* Create Order Form */
.portal-create-form{max-width:700px}
.portal-form-section{background:#150f24;border:1px solid #1f2b47;border-radius:10px;padding:20px;margin-bottom:16px}
.portal-form-section h3{color:#44f80c;font-size:14px;margin:0 0 14px;text-transform:uppercase;letter-spacing:1px}
.portal-form-row{display:flex;gap:12px;margin-bottom:12px}
.portal-form-row.three-col .portal-form-field{flex:1}
.portal-form-field{flex:1;display:flex;flex-direction:column}
.portal-form-field label{color:#94a3b8;font-size:12px;margin-bottom:5px;font-weight:500}
.portal-form-field input,.portal-form-field select,.portal-form-field textarea{background:#0a0514;border:1px solid #1f2b47;color:#e2e8f0;padding:10px 12px;border-radius:6px;font-size:13px;outline:none;transition:border-color .2s;width:100%}
.portal-form-field input:focus,.portal-form-field select:focus,.portal-form-field textarea:focus{border-color:#44f80c;box-shadow:0 0 0 2px #44f80c20}
.portal-form-field input::placeholder{color:#64748b}
.portal-form-field select option{background:#0a0514;color:#e2e8f0}
.portal-form-field textarea{resize:vertical;min-height:80px}
.portal-form-actions{display:flex;gap:12px;margin-top:20px;align-items:center}

/* Keyboard hint */
.portal-hints{display:flex;gap:16px;margin-top:12px;font-size:11px;color:#64748b}
.portal-hints kbd{background:#1f2b47;padding:2px 6px;border-radius:4px;font-family:inherit;font-size:11px}

/* Sortable headers */
.sort-link{color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:color .2s}
.sort-link:hover{color:#44f80c}
.sort-link.active{color:#44f80c;font-weight:600}

/* Affiliate */
.cell-affiliate{white-space:nowrap}

/* Note input */
.portal-note-input{width:100%;padding:5px 8px;border:1px solid transparent;background:transparent;color:#94a3b8;font-size:11px;border-radius:4px;cursor:text;transition:all .2s;font-family:inherit}
.portal-note-input:hover{border-color:#1f2b47;background:#0a0514}
.portal-note-input:focus{outline:none;border-color:#44f80c;background:#0a0514;color:#e2e8f0}
.portal-note-input::placeholder{color:#3a3450;font-size:10px}

/* Mobile */
@media(max-width:1024px){
.portal-table th:nth-child(11),.portal-table td:nth-child(11),.portal-table th:nth-child(12),.portal-table td:nth-child(12){display:none}
}
@media(max-width:768px){
.portal-stats{grid-template-columns:repeat(2,1fr)}
.portal-search{width:100%}
.portal-toolbar{flex-direction:column;align-items:stretch}
.portal-table th:nth-child(4),.portal-table td:nth-child(4),.portal-table th:nth-child(6),.portal-table td:nth-child(6){display:none}
.portal-container{padding:12px}
.portal-header{padding:0 12px}
}
</style>
</head>
<body>

<div class="portal-header">
    <div class="portal-header-left">
        <span class="portal-logo">microDOS(2)</span>
        <span class="portal-subtitle">Shipping Portal</span>
    </div>
    <div class="portal-user">
        <span><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
        <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
    </div>
</div>

<div class="portal-container">

<?php echo $notice; ?>

<!-- Stats -->
<div class="portal-stats">
    <div class="portal-stat ready">
        <div class="portal-stat-num"><?php echo count($processing_ids); ?></div>
        <div class="portal-stat-label">Ready to Ship</div>
    </div>
    <div class="portal-stat today">
        <div class="portal-stat-num"><?php echo count($shipped_today); ?></div>
        <div class="portal-stat-label">Shipped Today</div>
    </div>
    <div class="portal-stat total">
        <div class="portal-stat-num"><?php echo count($shipped_ids); ?></div>
        <div class="portal-stat-label">Total Shipped</div>
    </div>
    <?php if ($tab === 'ready') : ?>
    <div class="portal-stat revenue">
        <div class="portal-stat-num">$<?php echo number_format($today_revenue, 2); ?></div>
        <div class="portal-stat-label">Ready Revenue</div>
    </div>
    <?php endif; ?>
</div>

<!-- Toolbar: tabs + search -->
<div class="portal-toolbar">
    <div class="portal-tabs">
        <a href="?tab=ready" class="portal-tab <?php echo $tab === 'ready' ? 'active' : ''; ?>">
            Ready to Ship
            <?php if (count($processing_ids) > 0) : ?>
                <span class="portal-tab-badge"><?php echo count($processing_ids); ?></span>
            <?php endif; ?>
        </a>
        <a href="?tab=shipped" class="portal-tab <?php echo $tab === 'shipped' ? 'active' : ''; ?>">
            Shipped Orders
        </a>
        <a href="?tab=create" class="portal-tab <?php echo $tab === 'create' ? 'active' : ''; ?>">
            <span style="font-size:14px;">+</span> Create Order
        </a>
    </div>

    <form method="get" class="portal-search" action="">
        <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">
        <input type="text" name="q" value="<?php echo esc_attr($search); ?>" placeholder="Search order #, name, email...">
        <button type="submit">&#128269;</button>
        <?php if ($search) : ?>
            <a href="?tab=<?php echo esc_attr($tab); ?>" style="color:#64748b;text-decoration:none;font-size:13px;padding:2px 4px;">Clear</a>
        <?php endif; ?>
    </form>

    <div style="display:flex;gap:8px;align-items:center;">
        <!-- Date Range -->
        <form method="get" style="margin:0;">
            <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">
            <?php if ($search) : ?><input type="hidden" name="q" value="<?php echo esc_attr($search); ?>"><?php endif; ?>
            <select name="range" onchange="this.form.submit()" style="background:#150f24;border:1px solid #1f2b47;color:#e2e8f0;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">
                <option value="" <?php selected($range, ''); ?>>All Time</option>
                <option value="today" <?php selected($range, 'today'); ?>>Today</option>
                <option value="yesterday" <?php selected($range, 'yesterday'); ?>>Yesterday</option>
                <option value="week" <?php selected($range, 'week'); ?>>Last 7 Days</option>
                <option value="month" <?php selected($range, 'month'); ?>>Last 30 Days</option>
            </select>
        </form>

        <!-- Sort -->
        <form method="get" style="margin:0;">
            <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>">
            <?php if ($search) : ?><input type="hidden" name="q" value="<?php echo esc_attr($search); ?>"><?php endif; ?>
            <?php if ($range) : ?><input type="hidden" name="range" value="<?php echo esc_attr($range); ?>"><?php endif; ?>
            <select name="sort" onchange="this.form.submit()" style="background:#150f24;border:1px solid #1f2b47;color:#e2e8f0;padding:7px 10px;border-radius:6px;font-size:12px;cursor:pointer;">
                <option value="date_asc" <?php selected($sort, 'date_asc'); ?>><?php echo $tab === 'ready' ? 'Oldest First' : 'Newest First'; ?></option>
                <option value="date_desc" <?php selected($sort, 'date_desc'); ?>><?php echo $tab === 'ready' ? 'Newest First' : 'Oldest First'; ?></option>
                <option value="total_desc" <?php selected($sort, 'total_desc'); ?>>Highest Total</option>
                <option value="total_asc" <?php selected($sort, 'total_asc'); ?>>Lowest Total</option>
                <option value="name_asc" <?php selected($sort, 'name_asc'); ?>>Name A-Z</option>
                <option value="name_desc" <?php selected($sort, 'name_desc'); ?>>Name Z-A</option>
            </select>
        </form>

        <!-- Export CSV -->
        <a href="?tab=<?php echo esc_attr($tab); ?>&amp;export=csv&amp;_wpnonce=<?php echo wp_create_nonce('microdos_export_nonce'); ?>" class="portal-btn portal-btn-view" style="font-size:12px;">&#11015; Export CSV</a>
    </div>
</div>

<?php if ($tab === 'ready' || $tab === 'shipped') : ?>

<!-- Bulk Ship Bar -->
<div class="portal-bulk-bar" id="bulkBar">
    <span id="bulkCount">0 orders selected</span>
    <button type="button" class="portal-btn-bulk" onclick="document.getElementById('bulkForm').submit();">Ship Selected</button>
</div>

<!-- Table -->
<div class="portal-table-wrap">
    <div class="portal-table-header">
        <span class="portal-table-title"><?php echo $tab === 'ready' ? 'Orders Ready to Ship' : 'Shipped Orders'; ?></span>
        <span class="portal-table-count"><?php echo $total_items; ?> orders</span>
    </div>

    <?php if (empty($orders)) : ?>
        <div class="portal-empty">
            <p><?php echo $search ? 'No orders match "' . esc_html($search) . '"' : ($tab === 'ready' ? 'All caught up! No orders waiting.' : 'No shipped orders yet.'); ?></p>
        </div>
    <?php else : ?>

        <form method="post" id="bulkForm">
            <?php wp_nonce_field('microdos_portal_nonce'); ?>
            <input type="hidden" name="microdos_bulk_ship" value="1">

            <table class="portal-table">
                <thead>
                    <tr>
                        <?php if ($tab === 'ready') : ?><th style="width:30px"><input type="checkbox" class="portal-check" id="selectAll" title="Select all"></th><?php endif; ?>
                        <th style="width:65px;"><a href="?tab=<?php echo $tab; ?>&amp;sort=<?php echo $sort === 'date_asc' ? 'date_desc' : 'date_asc'; ?><?php echo $search ? '&amp;q=' . urlencode($search) : ''; ?><?php echo $range ? '&amp;range=' . $range : ''; ?>" class="sort-link <?php echo strpos($sort, 'date') === 0 ? 'active' : ''; ?>">Order <?php echo strpos($sort, 'date') === 0 ? ($sort === 'date_asc' ? '&#9650;' : '&#9660;') : ''; ?></a></th>
                        <th style="width:110px;">Date</th>
                        <th><a href="?tab=<?php echo $tab; ?>&amp;sort=<?php echo $sort === 'name_asc' ? 'name_desc' : 'name_asc'; ?><?php echo $search ? '&amp;q=' . urlencode($search) : ''; ?><?php echo $range ? '&amp;range=' . $range : ''; ?>" class="sort-link <?php echo strpos($sort, 'name') === 0 ? 'active' : ''; ?>">Customer <?php echo strpos($sort, 'name') === 0 ? ($sort === 'name_asc' ? '&#9650;' : '&#9660;') : ''; ?></a></th>
                        <th>Items</th>
                        <th style="width:75px;"><a href="?tab=<?php echo $tab; ?>&amp;sort=<?php echo $sort === 'total_desc' ? 'total_asc' : 'total_desc'; ?><?php echo $search ? '&amp;q=' . urlencode($search) : ''; ?><?php echo $range ? '&amp;range=' . $range : ''; ?>" class="sort-link <?php echo strpos($sort, 'total') === 0 ? 'active' : ''; ?>">Total <?php echo strpos($sort, 'total') === 0 ? ($sort === 'total_desc' ? '&#9660;' : '&#9650;') : ''; ?></a></th>
                        <th style="width:130px">Ship To</th>
                        <th style="width:80px">Est. Weight</th>
                        <th style="width:100px;">Affiliate</th>
                        <th style="width:110px;">Notes</th>
                        <?php if ($tab === 'ready') : ?>
                            <th style="width:160px">Tracking #</th>
                            <th style="width:90px"></th>
                        <?php else : ?>
                            <th style="width:160px">Tracking</th>
                            <th style="width:80px">Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) :
                        $oid      = $order->get_id();
                        $tracking = $order->get_meta('_microdos_tracking_number', true);
                        $t_url    = portal_tracking_url($tracking, 'usps');
                        $weight   = microdos_estimated_weight($order);
                        $address  = $order->get_shipping_address_1();
                        $city     = $order->get_shipping_city();
                        $state    = $order->get_shipping_state();
                        $zip      = $order->get_shipping_postcode();
                    ?>
                    <tr>
                        <?php if ($tab === 'ready') : ?>
                            <td><input type="checkbox" class="portal-check bulk-check" name="bulk_order_ids[]" value="<?php echo $oid; ?>" data-tracking="tracking_<?php echo $oid; ?>"></td>
                        <?php endif; ?>

                        <td class="cell-order"><a href="<?php echo esc_url($order->get_edit_order_url()); ?>" target="_blank">#<?php echo esc_html($order->get_order_number()); ?></a></td>
                        <td class="cell-date"><?php echo esc_html($order->get_date_created()->date('M j')); ?><br><?php echo esc_html($order->get_date_created()->date('g:i A')); ?></td>
                        <td>
                            <div class="cell-name"><?php echo esc_html($order->get_formatted_billing_full_name()); ?></div>
                            <div class="cell-email"><?php echo esc_html($order->get_billing_email()); ?></div>
                        </td>
                        <td class="cell-items">
                            <?php foreach ($order->get_items() as $item) :
                                $iname = strtolower($item->get_name());
                                $is_bottle = strpos($iname, 'pill') !== false || strpos($iname, 'bottle') !== false;
                                $is_card   = strpos($iname, 'trial') !== false || strpos($iname, 'card') !== false || strpos($iname, 'starter') !== false;
                            ?>
                                <?php if ($is_bottle) : ?>
                                    <span class="badge badge-product badge-bottle">&#x1F9EA; <?php echo esc_html($item->get_name()); ?> x<?php echo $item->get_quantity(); ?></span><br>
                                <?php elseif ($is_card) : ?>
                                    <span class="badge badge-product badge-card">&#x1F4B3; <?php echo esc_html($item->get_name()); ?> x<?php echo $item->get_quantity(); ?></span><br>
                                <?php else : ?>
                                    <?php echo esc_html($item->get_name()); ?> <span class="qty">x<?php echo $item->get_quantity(); ?></span><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                        <td class="cell-total"><?php echo $order->get_formatted_order_total(); ?></td>
                        <td class="cell-address"><?php echo esc_html($address); ?><br><?php echo esc_html("{$city}, {$state} {$zip}"); ?></td>
                        <td class="cell-weight">
                            <?php echo $weight['g']; ?>g<br>
                            <span class="detail"><?php echo $weight['oz']; ?> oz</span>
                        </td>
                        <td class="cell-affiliate">
                            <?php
                            $aff_name = '';
                            if (function_exists('affiliate_wp')) {
                                $referrals = affiliate_wp()->referrals->get_referrals(['reference' => $oid, 'number' => 1]);
                                if (!empty($referrals)) {
                                    $aff = affiliate_wp()->affiliates->get_affiliate($referrals[0]->affiliate_id);
                                    if ($aff) {
                                        $aff_user = get_userdata($aff->user_id);
                                        $aff_name = $aff_user ? $aff_user->display_name : '';
                                    }
                                }
                            }
                            echo $aff_name ? '<span style="color:#ff66c4;font-size:12px;">' . esc_html($aff_name) . '</span>' : '<span style="color:#64748b;font-size:12px;">--</span>';
                            ?>
                        </td>
                        <td class="cell-note">
                            <?php
                            $order_note = $order->get_meta('_microdos_shipping_note', true);
                            if ($tab === 'ready') : ?>
                                <form method="post" style="margin:0;">
                                    <?php wp_nonce_field('microdos_portal_nonce'); ?>
                                    <input type="hidden" name="microdos_save_note" value="1">
                                    <input type="hidden" name="note_order_id" value="<?php echo $oid; ?>">
                                    <input type="text" name="order_note_text" value="<?php echo esc_attr($order_note); ?>" placeholder="Add note..." class="portal-note-input" onchange="this.form.submit()">
                                </form>
                            <?php else :
                                echo $order_note ? '<span style="color:#94a3b8;font-size:12px;">' . esc_html($order_note) . '</span>' : '<span style="color:#64748b;font-size:12px;">--</span>';
                            endif; ?>
                        </td>

                        <?php if ($tab === 'ready') : ?>
                            <td>
                                <input type="text" name="tracking_<?php echo $oid; ?>" class="portal-tracking-input" placeholder="940011..." value="<?php echo esc_attr($tracking); ?>" id="tracking_<?php echo $oid; ?>">
                            </td>
                            <td>
                                <div style="display:flex;gap:4px;flex-direction:column;">
                                    <button type="submit" name="microdos_portal_ship" value="1" class="portal-btn portal-btn-ship" onclick="document.getElementById('bulkForm').action='';return true;">Ship</button>
                                    <a href="?tab=ready&amp;action=packing-slip&amp;order_id=<?php echo $oid; ?>" target="_blank" class="portal-btn portal-btn-view">&#128424; Print Slip</a>
                                </div>
                                <input type="hidden" name="order_id" value="<?php echo $oid; ?>">
                            </td>
                        <?php else : ?>
                            <td class="cell-tracking">
                                <?php if ($tracking && $t_url) : ?>
                                    <a href="<?php echo esc_url($t_url); ?>" target="_blank"><?php echo esc_html($tracking); ?></a><br><span style="color:#64748b;font-size:11px;">USPS</span>
                                <?php else : ?>
                                    <span class="no-track">No tracking</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge badge-<?php echo $order->get_status(); ?>"><?php echo ucfirst($order->get_status()); ?></span></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if ($total_pages > 1) : ?>
    <div class="portal-pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <?php if ($i === $page) : ?>
                <span class="current"><?php echo $i; ?></span>
            <?php else : ?>
                <a href="?tab=<?php echo $tab; ?>&amp;portal_page=<?php echo $i; ?><?php echo $search ? '&amp;q=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
<?php if ($tab === 'create') : ?>
    <!-- Create Order Tab -->
    <div class="portal-tab-content">
        <h2 style="color:#e2e8f0;margin:0 0 16px;font-size:18px;">Create New Order</h2>
        <?php echo $create_notice; ?>

        <form method="post" class="portal-create-form">
            <?php wp_nonce_field('microdos_create_order_nonce'); ?>
            <input type="hidden" name="microdos_create_order" value="1">

            <div class="portal-form-section">
                <h3>Customer Information</h3>
                <div class="portal-form-row">
                    <div class="portal-form-field">
                        <label>First Name *</label>
                        <input type="text" name="billing_first_name" required placeholder="John">
                    </div>
                    <div class="portal-form-field">
                        <label>Last Name *</label>
                        <input type="text" name="billing_last_name" required placeholder="Doe">
                    </div>
                </div>
                <div class="portal-form-row">
                    <div class="portal-form-field">
                        <label>Email *</label>
                        <input type="email" name="billing_email" required placeholder="john@example.com">
                    </div>
                    <div class="portal-form-field">
                        <label>Phone</label>
                        <input type="tel" name="billing_phone" placeholder="(555) 123-4567">
                    </div>
                </div>
            </div>

            <div class="portal-form-section">
                <h3>Shipping Address</h3>
                <div class="portal-form-field">
                    <label>Address Line 1 *</label>
                    <input type="text" name="shipping_address_1" required placeholder="123 Main St">
                </div>
                <div class="portal-form-field">
                    <label>Address Line 2</label>
                    <input type="text" name="shipping_address_2" placeholder="Apt 4B">
                </div>
                <div class="portal-form-row three-col">
                    <div class="portal-form-field">
                        <label>City *</label>
                        <input type="text" name="shipping_city" required placeholder="Dallas">
                    </div>
                    <div class="portal-form-field">
                        <label>State *</label>
                        <input type="text" name="shipping_state" required placeholder="TX" maxlength="2" style="text-transform:uppercase;">
                    </div>
                    <div class="portal-form-field">
                        <label>ZIP Code *</label>
                        <input type="text" name="shipping_postcode" required placeholder="75201" maxlength="10">
                    </div>
                </div>
            </div>

            <div class="portal-form-section">
                <h3>Order Items</h3>
                <div class="portal-form-row">
                    <div class="portal-form-field" style="flex:2;">
                        <label>Product *</label>
                        <select name="product_id" required>
                            <option value="">-- Select Product --</option>
                            <?php
                            $products = wc_get_products(['status' => 'publish', 'limit' => -1, 'orderby' => 'name', 'order' => 'ASC']);
                            foreach ($products as $product) {
                                echo '<option value="' . esc_attr($product->get_id()) . '">' . esc_html($product->get_name()) . ' - ' . wc_price($product->get_price()) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="portal-form-field" style="flex:1;">
                        <label>Quantity *</label>
                        <input type="number" name="quantity" value="1" min="1" max="99" required>
                    </div>
                </div>
            </div>

            <div class="portal-form-section">
                <h3>Notes</h3>
                <div class="portal-form-field">
                    <label>Order Note (internal)</label>
                    <textarea name="order_note" rows="3" placeholder="Any special instructions..."></textarea>
                </div>
            </div>

            <div class="portal-form-actions">
                <button type="submit" class="portal-btn portal-btn-ship" style="width:auto;padding:12px 32px;font-size:14px;">Create Order</button>
                <a href="?tab=ready" class="portal-btn portal-btn-view" style="width:auto;padding:12px 24px;font-size:14px;">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>
</div>

<!-- Keyboard hints -->
<div class="portal-hints">
    <span><kbd>Tab</kbd> Next field</span>
    <span><kbd>Enter</kbd> Submit</span>
    <span><kbd>Esc</kbd> Clear search</span>
    <span style="margin-left:auto;">Auto-refreshes every 5 min &#x21BB;</span>
</div>

</div>

<script>
// ── Select All ──
document.getElementById('selectAll')?.addEventListener('change', function() {
    document.querySelectorAll('.bulk-check').forEach(cb => cb.checked = this.checked);
    updateBulkBar();
});

// ── Individual checkboxes ──
document.querySelectorAll('.bulk-check').forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
});

function updateBulkBar() {
    const checked = document.querySelectorAll('.bulk-check:checked');
    const bar     = document.getElementById('bulkBar');
    const count   = document.getElementById('bulkCount');
    if (checked.length > 0) {
        bar.classList.add('active');
        count.textContent = checked.length + ' order' + (checked.length > 1 ? 's' : '') + ' selected';
    } else {
        bar.classList.remove('active');
    }
}

// ── Auto-focus search with / key ──
document.addEventListener('keydown', function(e) {
    if (e.key === '/' && document.activeElement.tagName !== 'INPUT') {
        e.preventDefault();
        document.querySelector('.portal-search input')?.focus();
    }
    if (e.key === 'Escape') {
        const s = document.querySelector('.portal-search input');
        if (s === document.activeElement) {
            window.location.href = '?tab=<?php echo $tab; ?>';
        }
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
