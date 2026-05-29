<?php
/**
 * microDOS4U functions and definitions
 *
 * @package microDOS4U
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version
if (!defined('MICRODOS_VERSION')) {
    define('MICRODOS_VERSION', '1.3.15');
}

// ============================================
// THEME SETUP
// ============================================

function microdos4u_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('style.css');

    // Full WooCommerce support
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ),
    ));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'microdos4u'),
        'footer'  => __('Footer Menu', 'microdos4u'),
    ));

    // Register WooCommerce widget areas
    register_sidebar(array(
        'name'          => __('WooCommerce Sidebar', 'microdos4u'),
        'id'            => 'woocommerce-sidebar',
        'description'   => __('Widgets for WooCommerce shop and product pages.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}


// ============================================
// AFFILIATE ROLE & ACCESS CONTROL
// ============================================

// 1. Create 'Affiliate' WordPress role on theme setup
add_action('after_setup_theme', 'microdos_create_affiliate_role');
function microdos_create_affiliate_role() {
    // Only add if it doesn't already exist
    if (!get_role('affiliate')) {
        add_role('affiliate', 'Affiliate', array(
            'read' => true,                       // Can log in
            'read_affiliate_portal' => true,      // Custom capability for portal access
        ));
    }
}

// 2. Auto-assign 'Affiliate' role when AffiliateWP registers a new affiliate
add_action('affwp_insert_affiliate', 'microdos_set_affiliate_role', 10, 2);
function microdos_set_affiliate_role($affiliate_id, $data) {
    if (!empty($data['user_id'])) {
        $user = get_userdata($data['user_id']);
        if ($user) {
            $user->set_role('affiliate'); // Replace default role with Affiliate
        }
    }
}

// 3. Block affiliates from WooCommerce checkout (cannot purchase products)
add_action('woocommerce_checkout_process', 'microdos_block_affiliate_purchases');
add_action('woocommerce_before_cart', 'microdos_block_affiliate_cart');

function microdos_block_affiliate_purchases() {
    if (current_user_can('affiliate')) {
        wc_add_notice('Affiliate accounts cannot make purchases. Please create a separate customer account to buy products.', 'error');
    }
}

function microdos_block_affiliate_cart() {
    if (current_user_can('affiliate') && !is_admin()) {
        // Show notice on cart page
        wc_add_notice('Affiliate accounts cannot make purchases. Please create a separate customer account to buy products.', 'notice');
    }
}

add_action('after_setup_theme', 'microdos4u_setup');

// ============================================
// AUTO-CREATE REQUIRED PAGES ON THEME ACTIVATION
// ============================================

add_action('after_switch_theme', 'microdos_create_required_pages');
add_action('admin_init', 'microdos_create_required_pages');

function microdos_create_required_pages() {
    $pages_created = get_option('microdos_pages_created', array());

    // --- Affiliate W-9 Form Page ---
    if (empty($pages_created['affiliate_w9'])) {
        $existing = get_page_by_path('affiliate-w9');
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'     => 'Affiliate W-9 Form',
                'post_name'      => 'affiliate-w9',
                'post_content'   => '',
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'page_template'  => 'page-affiliate-w9.php',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
            ));
            if (!is_wp_error($page_id) && $page_id > 0) {
                $pages_created['affiliate_w9'] = $page_id;
            }
        } else {
            $pages_created['affiliate_w9'] = $existing->ID;
        }
    }

    // --- Affiliate Creatives page ---
    $ac_page = get_page_by_path('affiliate-creatives');
    if (!$ac_page || $ac_page->post_status !== 'publish') {
        // Page doesn't exist or isn't published - create/update it
        $page_data = array(
            'post_title'     => 'Affiliate Creatives',
            'post_name'      => 'affiliate-creatives',
            'post_content'   => '',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'page_template'  => 'page-affiliate-creatives.php',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        );
        if ($ac_page) {
            $page_data['ID'] = $ac_page->ID; // Update existing
        }
        $page_id = wp_insert_post($page_data);
        if (!is_wp_error($page_id) && $page_id > 0) {
            $pages_created['affiliate_creatives'] = $page_id;
        }
    } else {
        // Page exists - ensure correct template
        if ($ac_page->page_template !== 'page-affiliate-creatives.php') {
            wp_update_post(array(
                'ID'            => $ac_page->ID,
                'page_template' => 'page-affiliate-creatives.php',
            ));
        }
        $pages_created['affiliate_creatives'] = $ac_page->ID;
    }

    update_option('microdos_pages_created', $pages_created);
}

// ============================================
// ENQUEUE SCRIPTS AND STYLES
// ============================================

function microdos4u_scripts() {
    wp_enqueue_style(
        'microdos4u-style',
        get_stylesheet_uri(),
        array(),
        MICRODOS_VERSION
    );

    wp_enqueue_style(
        'microdos4u-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_script(
        'tailwind-cdn',
        'https://cdn.tailwindcss.com',
        array(),
        null,
        false
    );

    wp_enqueue_script(
        'imask',
        'https://unpkg.com/imask',
        array(),
        null,
        true
    );

    // WooCommerce AJAX cart support
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
    }

    wp_enqueue_script(
        'microdos4u-scripts',
        get_template_directory_uri() . '/js/main.js',
        array('imask'),
        MICRODOS_VERSION,
        true
    );

    // Affiliate creatives page enhancement (portal + area + dashboard tab)
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $is_affiliate_page = strpos($uri, '/affiliate-area') !== false
                      || strpos($uri, '/affiliate-portal') !== false
                      || is_page_template('page-affiliate-portal.php')
                      || is_page_template('page-affiliate-marketing-guide.php');
    if ($is_affiliate_page) {
        wp_enqueue_script(
            'microdos-creatives',
            get_template_directory_uri() . '/js/affiliate-creatives.js',
            array(),
            MICRODOS_VERSION,
            true
        );
        // Copy buttons for creatives
        wp_enqueue_script(
            'microdos-copy-buttons',
            get_template_directory_uri() . '/js/affiliate-copy-buttons.js',
            array(),
            MICRODOS_VERSION,
            true
        );
        wp_enqueue_style(
            'microdos-copy-buttons-css',
            get_template_directory_uri() . '/css/affiliate-copy-buttons.css',
            array(),
            MICRODOS_VERSION
        );
    }

    // Always enqueue copy buttons on AffiliateWP dashboard pages (for creatives tab)
    if (function_exists('affwp_is_affiliate') && affwp_is_affiliate()) {
        wp_enqueue_script(
            'microdos-copy-buttons',
            get_template_directory_uri() . '/js/affiliate-copy-buttons.js',
            array(),
            MICRODOS_VERSION,
            true
        );
    }

    // Pass config to JS
    wp_localize_script('microdos4u-scripts', 'microdos4uConfig', array(
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'themeUrl'  => get_template_directory_uri(),
        'siteUrl'   => home_url(),
        'wcActive'  => class_exists('WooCommerce'),
        'colors'    => array(
            'bgDark'      => get_theme_mod('microdos_bg_dark', '#0a0514'),
            'bgCard'      => get_theme_mod('microdos_bg_card', '#150f24'),
            'brandMicro'  => get_theme_mod('microdos_brand_micro', '#44f80c'),
            'brandDos'    => get_theme_mod('microdos_brand_dos', '#9a02d0'),
            'brandTwo'    => get_theme_mod('microdos_brand_two', '#ff66c4'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'microdos4u_scripts');

// ============================================
// CUSTOMIZER: COLOR SETTINGS
// ============================================

function microdos4u_customize_register($wp_customize) {
    $wp_customize->add_panel('microdos_colors_panel', array(
        'title'       => __('microDOS4U Colors', 'microdos4u'),
        'description' => __('Customize the color scheme for your site.', 'microdos4u'),
        'priority'    => 30,
    ));

    $wp_customize->add_section('microdos_bg_section', array(
        'title'    => __('Background Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 10,
    ));

    $wp_customize->add_setting('microdos_bg_dark', array(
        'default'           => '#0a0514',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_bg_dark', array(
        'label'   => __('Page Background', 'microdos4u'),
        'section' => 'microdos_bg_section',
    )));

    $wp_customize->add_setting('microdos_bg_card', array(
        'default'           => '#150f24',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_bg_card', array(
        'label'   => __('Card / Box Background', 'microdos4u'),
        'section' => 'microdos_bg_section',
    )));

    $wp_customize->add_section('microdos_brand_section', array(
        'title'    => __('Brand Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 20,
    ));

    $wp_customize->add_setting('microdos_brand_micro', array(
        'default'           => '#44f80c',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_micro', array(
        'label'   => __('"micro" Color (Green)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_setting('microdos_brand_dos', array(
        'default'           => '#9a02d0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_dos', array(
        'label'   => __('"DOS" Color (Purple)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_setting('microdos_brand_two', array(
        'default'           => '#ff66c4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_two', array(
        'label'   => __('"(2)" Color (Pink)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_section('microdos_text_section', array(
        'title'    => __('Text Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 30,
    ));

    $wp_customize->add_setting('microdos_text_primary', array(
        'default'           => '#d1d5db',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_primary', array(
        'label'   => __('Body Text Color', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));

    $wp_customize->add_setting('microdos_text_heading', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_heading', array(
        'label'   => __('Heading Color', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));

    $wp_customize->add_setting('microdos_text_muted', array(
        'default'           => '#94a3b8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_muted', array(
        'label'   => __('Muted / Secondary Text', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));
}
add_action('customize_register', 'microdos4u_customize_register');

// ============================================
// CUSTOMIZER: LIVE CSS OUTPUT
// ============================================

function microdos4u_customizer_css() {
    $bg_dark      = get_theme_mod('microdos_bg_dark', '#0a0514');
    $bg_card      = get_theme_mod('microdos_bg_card', '#150f24');
    $brand_micro  = get_theme_mod('microdos_brand_micro', '#44f80c');
    $brand_dos    = get_theme_mod('microdos_brand_dos', '#9a02d0');
    $brand_two    = get_theme_mod('microdos_brand_two', '#ff66c4');
    $text_primary = get_theme_mod('microdos_text_primary', '#d1d5db');
    $text_heading = get_theme_mod('microdos_text_heading', '#ffffff');
    $text_muted   = get_theme_mod('microdos_text_muted', '#94a3b8');
    ?>
    <style type="text/css">
        :root {
            --bg-dark: <?php echo esc_attr($bg_dark); ?>;
            --bg-card: <?php echo esc_attr($bg_card); ?>;
            --brand-micro: <?php echo esc_attr($brand_micro); ?>;
            --brand-dos: <?php echo esc_attr($brand_dos); ?>;
            --brand-two: <?php echo esc_attr($brand_two); ?>;
            --text-primary: <?php echo esc_attr($text_primary); ?>;
            --text-heading: <?php echo esc_attr($text_heading); ?>;
            --text-muted: <?php echo esc_attr($text_muted); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'microdos4u_customizer_css');

// ============================================
// WIDGET AREAS
// ============================================

function microdos4u_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'microdos4u'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('WooCommerce Sidebar', 'microdos4u'),
        'id'            => 'woocommerce-sidebar',
        'description'   => __('Widgets for WooCommerce pages.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'microdos4u_widgets_init');

// ============================================
// WOOCOMMERCE INTEGRATION
// ============================================

function microdos4u_cart_fragments($fragments) {
    if (function_exists('WC') && WC()->cart) {
        $fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    }
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'microdos4u_cart_fragments');

function microdos4u_loop_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'microdos4u_loop_columns', 20);

function microdos4u_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'microdos4u_products_per_page', 20);

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

function microdos4u_woo_ajax_add_to_cart() {
    if (!class_exists('WooCommerce')) return;
    wp_enqueue_script('wc-add-to-cart');
}
add_action('wp_enqueue_scripts', 'microdos4u_woo_ajax_add_to_cart');

// ============================================
// WOOCOMMERCE CHECKOUT PAGE SETUP
// ============================================

function microdos4u_checkout_page_template($template) {
    if (is_checkout() || is_cart()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
    return $template;
}
add_filter('template_include', 'microdos4u_checkout_page_template');

// ============================================
// HELPER FUNCTIONS
// ============================================

function microdos4u_brand_name($context = 'product') {
    $micro = '<span style="color: var(--brand-micro);">micro</span>';
    $dos   = '<span style="color: var(--brand-dos);">DOS</span>';
    $two   = '<span style="color: var(--brand-two);">(2)</span>';
    if ($context === 'site') {
        return 'microDOS4U';
    }
    return $micro . $dos . $two;
}

function microdos4u_site_brand() {
    return 'microDOS4U';
}

function microdos4u_product_brand() {
    return microdos4u_brand_name('product');
}

// ============================================
// ADMIN DASHBOARD BRANDING
// ============================================

function microdos4u_admin_footer_text($text) {
    return 'Powered by microDOS4U Theme.';
}
add_filter('admin_footer_text', 'microdos4u_admin_footer_text');

// ============================================
// SECURITY / PERFORMANCE
// ============================================

remove_action('wp_head', 'wp_generator');
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// ============================================
// CHECKOUT LEGAL ACKNOWLEDGMENT CHECKBOX
// ============================================

add_action('woocommerce_review_order_before_submit', 'microdos4u_legal_acknowledgment_checkbox', 10);

function microdos4u_legal_acknowledgment_checkbox() {
    $terms_page = get_page_by_path('legal-disclaimer');
    $terms_url = $terms_page ? esc_url(get_permalink($terms_page)) : '#';
    echo '<div class="legal-acknowledgment-wrap" style="margin: 20px 0; padding: 16px; background: #150f24; border: 1px solid #9a02d0; border-radius: 8px;">';
    echo '<label for="legal_acknowledgment" style="display: flex; align-items: flex-start; cursor: pointer;">';
    echo '<input type="checkbox" name="legal_acknowledgment" id="legal_acknowledgment" style="margin-right: 12px; margin-top: 4px; min-width: 18px; min-height: 18px; cursor: pointer;" required />';
    echo '<span style="color: #94a3b8; font-size: 14px; line-height: 1.6;">';
    echo '<strong style="color: #fff;">Check out Acknowledgement:</strong> I certify that I am at least 21 years old and that I am purchasing products from Unique Pharming solely for lawful research, novelty, or collector purposes. I understand that all products are Research Use Only, Not for Human Consumption, not approved for human or animal use, and not intended for medical, therapeutic, dietary, recreational, or diagnostic purposes. I agree to the <a href="' . $terms_url . '" target="_blank" style="color: #38bdf8; text-decoration: underline;">Terms and Conditions</a> and understand that all sales are final.';
    echo '</span>';
    echo '</label>';
    echo '</div>';
}

add_action('woocommerce_checkout_process', 'microdos4u_validate_legal_acknowledgment');

function microdos4u_validate_legal_acknowledgment() {
    if (!isset($_POST['legal_acknowledgment']) || empty($_POST['legal_acknowledgment'])) {
        wc_add_notice(__('You must acknowledge the Terms and Conditions and certify that you are purchasing products for lawful research, novelty, or collector purposes.'), 'error');
    }
}

add_action('wp_footer', 'microdos4u_checkout_checkbox_validation');

function microdos4u_checkout_checkbox_validation() {
    if (!is_checkout()) return;
    $script = "
    document.addEventListener('DOMContentLoaded', function() {
        var checkbox = document.getElementById('legal_acknowledgment');
        var form = document.querySelector('form.woocommerce-checkout');
        if (checkbox && form) {
            form.addEventListener('submit', function(e) {
                if (!checkbox.checked) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('You must check the legal acknowledgment box to proceed with your order.');
                    checkbox.focus();
                    checkbox.parentElement.style.border = '2px solid #ff4444';
                    checkbox.parentElement.style.borderRadius = '8px';
                    checkbox.parentElement.style.padding = '14px';
                    return false;
                }
            });
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.style.border = '';
                    this.parentElement.style.padding = '';
                }
            });
        }
    });
    ";
    wp_add_inline_script('jquery', $script);
}

// ============================================
// AUTO-CREATE CUSTOMER ACCOUNT ON CHECKOUT
// ============================================

add_action('woocommerce_checkout_order_created', 'microdos4u_auto_create_account');

function microdos4u_auto_create_account($order) {
    if (!function_exists('WC') || !is_object($order)) return;
    if (is_user_logged_in()) return;

    $billing_email = $order->get_billing_email();
    $billing_first = $order->get_billing_first_name();
    $billing_last  = $order->get_billing_last_name();

    if (empty($billing_email) || !is_email($billing_email)) return;

    $existing_user = get_user_by('email', $billing_email);
    if ($existing_user) {
        $order->set_customer_id($existing_user->ID);
        $order->save();
        return;
    }

    $username = sanitize_user(current(explode('@', $billing_email)), true);
    $original_username = $username;
    $counter = 1;
    while (username_exists($username)) {
        $username = $original_username . $counter;
        $counter++;
    }

    $password = wp_generate_password(18, true, true);

    $user_data = array(
        'user_login'   => $username,
        'user_email'   => sanitize_email($billing_email),
        'user_pass'    => $password,
        'first_name'   => sanitize_text_field($billing_first),
        'last_name'    => sanitize_text_field($billing_last),
        'display_name' => sanitize_text_field(trim($billing_first . ' ' . $billing_last)),
        'role'         => 'customer',
    );

    $user_id = wp_insert_user($user_data);
    if (is_wp_error($user_id)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('microDOS4U: Failed to auto-create account for ' . $billing_email . ' - ' . $user_id->get_error_message());
        }
        return;
    }

    $order->set_customer_id($user_id);
    $order->save();
    microdos4u_send_welcome_email($user_id, $billing_email);

    if (WC()->session) {
        WC()->session->set('microdos_new_account_created', true);
        WC()->session->set('microdos_new_account_email', $billing_email);
    }
}

function microdos4u_send_welcome_email($user_id, $email) {
    $user = get_user_by('id', $user_id);
    if (!$user) return;

    $reset_key = get_password_reset_key($user);
    if (is_wp_error($reset_key) || empty($reset_key)) return;

    $reset_url  = network_site_url("wp-login.php?action=rp&key=" . rawurlencode($reset_key) . "&login=" . rawurlencode($user->user_login), 'login');
    $login_url  = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
    $site_name  = get_bloginfo('name');

    $subject = sprintf(__('Your %s account is ready', 'microdos4u'), $site_name);

    $message  = sprintf(__('Hi %s,', 'microdos4u'), esc_html($user->display_name)) . "\n\n";
    $message .= sprintf(__('Thank you for your order! We\'ve created an account for you at %s.', 'microdos4u'), $site_name) . "\n\n";
    $message .= __('Account Email:', 'microdos4u') . ' ' . $email . "\n\n";
    $message .= __('To set your password and access your account, click this link:', 'microdos4u') . "\n";
    $message .= $reset_url . "\n\n";
    $message .= __('Or log in anytime at:', 'microdos4u') . "\n";
    $message .= $login_url . "\n\n";
    $message .= __('With your account you can:', 'microdos4u') . "\n";
    $message .= __('- View your order history', 'microdos4u') . "\n";
    $message .= __('- Track your orders', 'microdos4u') . "\n";
    $message .= __('- Manage your subscriptions', 'microdos4u') . "\n";
    $message .= __('- Update your account details', 'microdos4u') . "\n\n";
    $message .= __('If you have any questions, simply reply to this email.', 'microdos4u') . "\n\n";
    $message .= sprintf(__('Thanks,%sThe %s Team', 'microdos4u'), "\n", $site_name);

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail($email, $subject, $message, $headers);
}

add_action('woocommerce_before_thankyou', 'microdos4u_thankyou_account_notice');

function microdos4u_thankyou_account_notice($order_id) {
    if (!function_exists('WC') || !WC()->session) return;

    $new_account = WC()->session->get('microdos_new_account_created');
    $email       = WC()->session->get('microdos_new_account_email');
    if (!$new_account || empty($email)) return;

    printf(
        '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info" style="background-color: #150f24; border: 1px solid #44f80c; color: #d1d5db; margin-bottom: 20px;">' .
        '<strong style="color: #44f80c;">%s</strong> %s <strong>%s</strong>. %s' .
        '</div>',
        esc_html__('Account Created!', 'microdos4u'),
        esc_html__('We\'ve created an account for you. Check your email at', 'microdos4u'),
        esc_html($email),
        esc_html__('for your login details and a link to set your password.', 'microdos4u')
    );

    WC()->session->set('microdos_new_account_created', null);
    WC()->session->set('microdos_new_account_email', null);
}

// ============================================
// AFFILIATE W-9 TAX COLLECTION SYSTEM
// ============================================

add_action('affwp_insert_affiliate', 'microdos_flag_affiliate_for_w9', 10, 2);

function microdos_flag_affiliate_for_w9($affiliate_id, $data) {
    if (empty($data['user_id'])) return;
    $user_id = absint($data['user_id']);
    update_user_meta($user_id, 'microdos_w9_status', 'pending');
    update_user_meta($user_id, 'microdos_w9_requested', current_time('mysql'));
}

add_action('affwp_affiliate_admin_profile_info', 'microdos_maybe_show_w9_admin_notice', 5);

function microdos_maybe_show_w9_admin_notice($affiliate) {
    $user_id = $affiliate->user_id;
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    if (empty($w9_status)) {
        update_user_meta($user_id, 'microdos_w9_status', 'pending');
    }
}

add_action('affwp_affiliate_admin_profile_info', 'microdos_show_w9_in_admin', 20);

function microdos_show_w9_in_admin($affiliate) {
    $user_id   = $affiliate->user_id;
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    $w9_data   = get_user_meta($user_id, 'microdos_w9_data', true);

    $status_label = 'Not Submitted';
    $status_color = '#ff4444';
    if ($w9_status === 'complete') {
        $status_label = 'Complete';
        $status_color = '#44f80c';
    } elseif ($w9_status === 'pending') {
        $status_label = 'Pending';
        $status_color = '#ffaa00';
    }
    ?>
    <h3>W-9 Tax Information</h3>
    <table class="form-table">
        <tr>
            <th>W-9 Status</th>
            <td>
                <strong style="color: <?php echo esc_attr($status_color); ?>;"><?php echo esc_html($status_label); ?></strong>
                <?php if ($w9_status !== 'complete') : ?>
                    <p class="description" style="color: #ff4444;">Affiliate cannot receive payouts until W-9 is completed.</p>
                <?php endif; ?>
            </td>
        </tr>
        <?php if (is_array($w9_data) && !empty($w9_data)) : ?>
        <tr><th>Full Name</th><td><?php echo esc_html($w9_data['full_name'] ?? 'N/A'); ?></td></tr>
        <tr><th>Business Name</th><td><?php echo esc_html($w9_data['business_name'] ?? 'N/A'); ?></td></tr>
        <tr><th>Tax Classification</th><td><?php echo esc_html($w9_data['tax_classification'] ?? 'N/A'); ?></td></tr>
        <tr><th>Tax ID (SSN/EIN)</th><td>
            <?php
            $tin = $w9_data['tax_id'] ?? '';
            echo strlen($tin) >= 4 ? esc_html('***-**-' . substr($tin, -4)) : 'N/A';
            ?>
        </td></tr>
        <tr><th>Address</th><td>
            <?php
            echo esc_html($w9_data['address'] ?? 'N/A');
            if (!empty($w9_data['address2'])) echo '<br>' . esc_html($w9_data['address2']);
            ?>
        </td></tr>
        <tr><th>City, State, ZIP</th><td>
            <?php echo esc_html(($w9_data['city'] ?? '') . ', ' . ($w9_data['state'] ?? '') . ' ' . ($w9_data['zip'] ?? '')); ?>
        </td></tr>
        <tr><th>Certification Date</th><td><?php echo esc_html($w9_data['certification_date'] ?? 'N/A'); ?></td></tr>
        <tr><th>IP Address</th><td><?php echo esc_html($w9_data['ip_address'] ?? 'N/A'); ?></td></tr>
        <?php else : ?>
        <tr><th colspan="2" style="color: #999;">No W-9 data on file.</th></tr>
        <?php endif; ?>
    </table>
    <?php
}

add_action('affwp_affiliate_dashboard_top', 'microdos_show_w9_dashboard_notice');

function microdos_show_w9_dashboard_notice() {
    if (!is_user_logged_in()) return;
    $user_id = get_current_user_id();
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    if ($w9_status === 'complete') return;
    if (!function_exists('affwp_get_affiliate_id')) return;
    $affiliate_id = affwp_get_affiliate_id($user_id);
    if (!$affiliate_id) return;

    $w9_page = get_page_by_path('affiliate-w9');
    $w9_url  = $w9_page ? get_permalink($w9_page) : '#';
    ?>
    <div style="
        background: #150f24;
        border: 2px solid #ffaa00;
        border-radius: 8px;
        padding: 20px 24px;
        margin: 0 0 24px 0;
        color: #d1d5db;
    ">
        <p style="margin: 0 0 10px 0; font-weight: 700; color: #ffaa00; font-size: 16px;">
            Action Required: W-9 Tax Form
        </p>
        <p style="margin: 0 0 14px 0; font-size: 14px; line-height: 1.6;">
            Before we can issue any commission payments, we need a completed W-9 form on file for tax reporting purposes (1099-NEC).
            <strong style="color: #fff;">Payouts will be held until this is submitted.</strong>
        </p>
        <a href="<?php echo esc_url($w9_url); ?>" style="
            display: inline-block;
            background: #ffaa00;
            color: #0a0514;
            font-weight: 700;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        ">Complete W-9 Form Now</a>
    </div>
    <?php
}

add_filter('affwp_auto_register_pending_referral', 'microdos_maybe_block_payout_for_w9', 10, 2);

function microdos_maybe_block_payout_for_w9($register, $args) {
    if (!$register) return $register;
    $affiliate_id = $args['affiliate_id'] ?? 0;
    if (!$affiliate_id) return $register;
    $affiliate = affwp_get_affiliate($affiliate_id);
    if (!$affiliate) return $register;
    $w9_status = get_user_meta($affiliate->user_id, 'microdos_w9_status', true);
    if ($w9_status !== 'complete') return false;
    return $register;
}

// ============================================
// W-9 FORM SHORTCODE
// ============================================

add_shortcode('microdos_w9_form', 'microdos_render_w9_form');

function microdos_render_w9_form($atts) {
    if (!is_user_logged_in()) {
        return '<div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:20px;color:#d1d5db;text-align:center;">
            <p><strong style="color:#ff4444;">Please log in to access the W-9 form.</strong></p>
            <p><a href="' . esc_url(wp_login_url(get_permalink())) . '" style="color:#44f80c;">Log In &rarr;</a></p>
        </div>';
    }

    $user_id = get_current_user_id();
    if (!function_exists('affwp_get_affiliate_id')) {
        return '<div style="color:#ff4444;">AffiliateWP is not active.</div>';
    }
    $affiliate_id = affwp_get_affiliate_id($user_id);
    if (!$affiliate_id) {
        return '<div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:20px;color:#d1d5db;text-align:center;">
            <p><strong style="color:#ff4444;">You are not registered as an affiliate.</strong></p>
            <p><a href="/affiliate-program" style="color:#44f80c;">Apply to become an affiliate &rarr;</a></p>
        </div>';
    }

    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    if ($w9_status === 'complete') {
        $w9_data = get_user_meta($user_id, 'microdos_w9_data', true);
        return '<div style="background:#150f24;border:1px solid #44f80c;border-radius:8px;padding:24px;color:#d1d5db;text-align:center;">
            <p style="font-size:20px;margin:0 0 8px;">&#10004;</p>
            <p style="font-weight:700;color:#44f80c;margin:0 0 8px;font-size:16px;">Your W-9 is on file.</p>
            <p style="margin:0;font-size:14px;">Thank you! Your tax information has been received and verified. You are eligible for commission payouts.</p>
            <p style="margin:12px 0 0;font-size:13px;color:#94a3b8;">Submitted: ' . esc_html($w9_data['certification_date'] ?? 'N/A') . '</p>
        </div>';
    }

    $error = '';
    $success = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['microdos_w9_nonce'])) {
        if (!wp_verify_nonce($_POST['microdos_w9_nonce'], 'microdos_w9_submit')) {
            $error = 'Security check failed. Please refresh the page and try again.';
        } else {
            $required_fields = array(
                'w9_full_name'          => 'Full Name / Business Name',
                'w9_tax_classification'  => 'Federal Tax Classification',
                'w9_tax_id'             => 'Taxpayer Identification Number (SSN/EIN)',
                'w9_address'            => 'Address',
                'w9_city'               => 'City',
                'w9_state'              => 'State',
                'w9_zip'                => 'ZIP Code',
            );

            $missing = array();
            foreach ($required_fields as $field => $label) {
                if (empty($_POST[$field])) $missing[] = $label;
            }

            if (!empty($missing)) {
                $error = 'Please fill in all required fields: ' . implode(', ', $missing);
            } elseif (empty($_POST['w9_certification'])) {
                $error = 'You must check the certification box to certify the information is correct.';
            } else {
                $w9_data = array(
                    'full_name'          => sanitize_text_field($_POST['w9_full_name']),
                    'business_name'      => sanitize_text_field($_POST['w9_business_name'] ?? ''),
                    'tax_classification' => sanitize_text_field($_POST['w9_tax_classification']),
                    'tax_id'             => sanitize_text_field($_POST['w9_tax_id']),
                    'address'            => sanitize_text_field($_POST['w9_address']),
                    'address2'           => sanitize_text_field($_POST['w9_address2'] ?? ''),
                    'city'               => sanitize_text_field($_POST['w9_city']),
                    'state'              => sanitize_text_field($_POST['w9_state']),
                    'zip'                => sanitize_text_field($_POST['w9_zip']),
                    'certification_date' => current_time('mysql'),
                    'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
                );
                update_user_meta($user_id, 'microdos_w9_data', $w9_data);
                update_user_meta($user_id, 'microdos_w9_status', 'complete');

                if (function_exists('affwp_get_affiliate_referrals')) {
                    $pending_refs = affwp_get_referrals(array(
                        'affiliate_id' => $affiliate_id,
                        'status'       => 'pending',
                        'number'       => -1,
                    ));
                    if (!empty($pending_refs)) {
                        foreach ($pending_refs as $ref) {
                            affiliate_wp()->referrals->update_referral($ref->referral_id, array('status' => 'unpaid'));
                        }
                    }
                }
                $success = true;
            }
        }
    }

    if ($success) {
        return '<div style="background:#150f24;border:1px solid #44f80c;border-radius:8px;padding:24px;color:#d1d5db;text-align:center;">
            <p style="font-size:20px;margin:0 0 8px;">&#10004;</p>
            <p style="font-weight:700;color:#44f80c;margin:0 0 8px;font-size:16px;">W-9 Submitted Successfully!</p>
            <p style="margin:0;font-size:14px;">Your tax information has been saved. You are now eligible for commission payouts.</p>
            <p style="margin:12px 0 0;font-size:14px;"><a href="' . esc_url(affwp_get_affiliate_area_page_url()) . '" style="color:#44f80c;">Go to Affiliate Dashboard &rarr;</a></p>
        </div>';
    }

    ob_start();
    ?>
    <div style="max-width: 700px; margin: 0 auto;">
        <?php if ($error) : ?>
        <div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#d1d5db;">
            <strong style="color:#ff4444;">Error:</strong> <?php echo esc_html($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:28px;">
            <h2 style="color:#fff;margin:0 0 6px;font-size:20px;">W-9 Tax Information</h2>
            <p style="color:#94a3b8;margin:0 0 24px;font-size:14px;">Required for all US-based affiliates. Information is stored securely and used only for 1099-NEC tax reporting.</p>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Name</h3>
            <div style="margin-bottom:16px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Full Name / Business Name <span style="color:#ff4444;">*</span></label>
                <input type="text" name="w9_full_name" required value="<?php echo esc_attr($_POST['w9_full_name'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="As shown on your income tax return">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Business Name (if different)</label>
                <input type="text" name="w9_business_name" value="<?php echo esc_attr($_POST['w9_business_name'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="Leave blank if not a business entity">
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Federal Tax Classification</h3>
            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:8px;font-weight:600;">Select your federal tax classification <span style="color:#ff4444;">*</span></label>
                <select name="w9_tax_classification" required style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;">
                    <option value="">-- Select Classification --</option>
                    <option value="Individual / Sole Proprietor" <?php selected($_POST['w9_tax_classification'] ?? '', 'Individual / Sole Proprietor'); ?>>Individual / Sole Proprietor or Single-Member LLC</option>
                    <option value="C Corporation" <?php selected($_POST['w9_tax_classification'] ?? '', 'C Corporation'); ?>>C Corporation</option>
                    <option value="S Corporation" <?php selected($_POST['w9_tax_classification'] ?? '', 'S Corporation'); ?>>S Corporation</option>
                    <option value="Partnership" <?php selected($_POST['w9_tax_classification'] ?? '', 'Partnership'); ?>>Partnership</option>
                    <option value="LLC (C Corp)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (C Corp)'); ?>>LLC taxed as C Corporation</option>
                    <option value="LLC (S Corp)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (S Corp)'); ?>>LLC taxed as S Corporation</option>
                    <option value="LLC (Partnership)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (Partnership)'); ?>>LLC taxed as Partnership</option>
                    <option value="LLC (Disregarded)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (Disregarded)'); ?>>LLC (Disregarded entity)</option>
                    <option value="Other" <?php selected($_POST['w9_tax_classification'] ?? '', 'Other'); ?>>Other</option>
                </select>
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Taxpayer Identification Number</h3>
            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">SSN or EIN <span style="color:#ff4444;">*</span></label>
                <input type="text" name="w9_tax_id" required value="<?php echo esc_attr($_POST['w9_tax_id'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="000-00-0000 (SSN) or 00-0000000 (EIN)" maxlength="11" inputmode="numeric" autocomplete="off">
                <p style="margin:4px 0 0;font-size:12px;color:#94a3b8;">For security, this is encrypted and only the last 4 digits are visible to admins.</p>
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Address</h3>
            <div style="margin-bottom:12px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Street Address <span style="color:#ff4444;">*</span></label>
                <input type="text" name="w9_address" required value="<?php echo esc_attr($_POST['w9_address'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="123 Main St">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Apt / Suite / Unit</label>
                <input type="text" name="w9_address2" value="<?php echo esc_attr($_POST['w9_address2'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="Apt 4B (optional)">
            </div>
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;margin-bottom:20px;">
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">City <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="w9_city" required value="<?php echo esc_attr($_POST['w9_city'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">State <span style="color:#ff4444;">*</span></label>
                    <select name="w9_state" required style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#e2e8f0;font-size:14px;box-sizing:border-box;height:40px;">
                        <option value="">--</option>
                        <?php
                        $states = array('AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','DC');
                        foreach ($states as $st) {
                            $selected = selected($_POST['w9_state'] ?? '', $st, false);
                            echo '<option value="' . esc_attr($st) . '"' . $selected . '>' . esc_html($st) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">ZIP <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="w9_zip" required value="<?php echo esc_attr($_POST['w9_zip'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="00000" maxlength="10">
                </div>
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Certification</h3>
            <div style="margin-bottom:20px;background:#0a0514;padding:16px;border-radius:6px;border:1px solid #1f2b47;">
                <label style="display:flex;align-items:flex-start;cursor:pointer;color:#d1d5db;font-size:13px;line-height:1.6;">
                    <input type="checkbox" name="w9_certification" value="1" required style="margin-right:10px;margin-top:3px;min-width:16px;min-height:16px;cursor:pointer;accent-color:#44f80c;">
                    <span>Under penalties of perjury, I certify that:<br><br>1. The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me), and<br>2. I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the IRS that I am subject to backup withholding, or (c) the IRS has notified me that I am no longer subject to backup withholding, and<br>3. I am a U.S. citizen or other U.S. person (including a resident alien), and<br>4. The information provided is accurate and complete to the best of my knowledge.</span>
                </label>
            </div>

            <?php wp_nonce_field('microdos_w9_submit', 'microdos_w9_nonce'); ?>
            <button type="submit" style="width:100%;padding:14px 24px;background:#44f80c;color:#0a0514;font-weight:700;font-size:16px;border:none;border-radius:8px;cursor:pointer;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">Submit W-9 Form</button>
            <p style="text-align:center;margin-top:12px;font-size:12px;color:#94a3b8;">Your information is stored securely and is only used for IRS 1099-NEC tax reporting. We do not share this data with third parties.</p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}

// ============================================
// W-9 SERVER-SIDE VALIDATION & SAVE
// ============================================

add_action('affwp_process_register_form', 'microdos_validate_w9_on_registration', 10, 1);

function microdos_validate_w9_on_registration($data) {
    $required_fields = array(
        'affwp_w9_legal_name'         => 'Full Legal Name',
        'affwp_w9_tax_classification' => 'Tax Classification',
        'affwp_w9_address'            => 'Street Address',
        'affwp_w9_city'               => 'City',
        'affwp_w9_state'              => 'State',
        'affwp_w9_zip'                => 'ZIP Code',
        'affwp_w9_tax_id'             => 'SSN or EIN',
    );
    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            affwp_add_error($field . '_required', sprintf(__('W-9: %s is required.'), $label));
        }
    }
    if (empty($_POST['affwp_w9_certification'])) {
        affwp_add_error('affwp_w9_certification_required', __('W-9: You must certify the information is correct under penalties of perjury.'));
    }
}

add_action('user_register', 'microdos_save_w9_on_affiliate_register', 10, 1);

function microdos_save_w9_on_affiliate_register($user_id) {
    if (empty($_POST['affwp_w9_legal_name'])) return;
    $w9_data = array(
        'legal_name'         => sanitize_text_field($_POST['affwp_w9_legal_name'] ?? ''),
        'business_name'      => sanitize_text_field($_POST['affwp_w9_business_name'] ?? ''),
        'tax_classification' => sanitize_text_field($_POST['affwp_w9_tax_classification'] ?? ''),
        'address'            => sanitize_text_field($_POST['affwp_w9_address'] ?? ''),
        'city'               => sanitize_text_field($_POST['affwp_w9_city'] ?? ''),
        'state'              => sanitize_text_field($_POST['affwp_w9_state'] ?? ''),
        'zip'                => sanitize_text_field($_POST['affwp_w9_zip'] ?? ''),
        'tax_id'             => sanitize_text_field($_POST['affwp_w9_tax_id'] ?? ''),
        'certification_date' => current_time('mysql'),
        'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    );
    update_user_meta($user_id, 'microdos_w9_data', $w9_data);
    update_user_meta($user_id, 'microdos_w9_status', 'complete');
}

// ============================================
// GRAVITY FORMS - CREATE USER & AFFILIATE ON SUBMISSION
// ============================================

add_action('gform_after_submission_2', 'microdos_create_affiliate_from_form', 10, 2);

function microdos_create_affiliate_from_form($entry, $form) {
    $first_name  = rgar($entry, '1.3');
    $last_name   = rgar($entry, '1.6');
    $email       = rgar($entry, '2');
    $password    = rgar($entry, '3');
    $username    = rgar($entry, '4');
    $website     = rgar($entry, '5');
    $legal_name  = rgar($entry, '7');
    $business    = rgar($entry, '8');
    $tax_class   = rgar($entry, '9');
    $address     = rgar($entry, '10');
    $address2    = rgar($entry, '11');
    $city        = rgar($entry, '12');
    $state       = rgar($entry, '13');
    $zip         = rgar($entry, '14');
    $tax_id      = rgar($entry, '15');

    if (empty($username)) {
        $username = sanitize_user(current(explode('@', $email)), true);
    }
    $original = $username;
    $suffix = 1;
    while (username_exists($username)) {
        $username = $original . $suffix;
        $suffix++;
    }

    $user_id = wp_insert_user(array(
        'user_login'   => $username,
        'user_email'   => sanitize_email($email),
        'user_pass'    => $password,
        'first_name'   => sanitize_text_field($first_name),
        'last_name'    => sanitize_text_field($last_name),
        'user_url'     => esc_url_raw($website),
        'role'         => 'subscriber',
    ));

    if (is_wp_error($user_id)) {
        error_log('[microDOS] User creation failed: ' . $user_id->get_error_message());
        return;
    }

    if (function_exists('affwp_add_affiliate')) {
        affwp_add_affiliate(array(
            'user_id'       => $user_id,
            'status'        => 'pending',
            'payment_email' => sanitize_email($email),
        ));
    }

    update_user_meta($user_id, 'microdos_w9_data', array(
        'legal_name'         => sanitize_text_field($legal_name),
        'business_name'      => sanitize_text_field($business),
        'tax_classification' => sanitize_text_field($tax_class),
        'address'            => sanitize_text_field($address),
        'address2'           => sanitize_text_field($address2),
        'city'               => sanitize_text_field($city),
        'state'              => sanitize_text_field($state),
        'zip'                => sanitize_text_field($zip),
        'tax_id'             => sanitize_text_field($tax_id),
        'certification_date' => current_time('mysql'),
        'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    ));
    update_user_meta($user_id, 'microdos_w9_status', 'complete');

    microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name);

    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
}

function microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name) {
    $site_name   = get_bloginfo('name');
    $site_url    = home_url('/');
    $affiliate_area = get_permalink(get_page_by_path('affiliate-area')) ?: home_url('/affiliate-area/');
    $admin_email = get_option('admin_email');

    $subject = "Your {$site_name} Affiliate Application Received";
    $display_name = $first_name ?: $last_name ?: 'Affiliate';

    $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>';
    $message .= '<body style="margin:0;padding:0;background-color:#0a0514;font-family:Arial,Helvetica,sans-serif;">';
    $message .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="padding:30px 20px;">';
    $message .= '<table width="100%" style="max-width:600px;background-color:#150f24;border:1px solid #1f2b47;border-radius:12px;overflow:hidden;">';
    $message .= '<tr><td style="padding:30px;text-align:center;background-color:#0a0514;border-bottom:1px solid #1f2b47;">';
    $message .= '<h1 style="margin:0;font-size:24px;color:#44f80c;">' . esc_html($site_name) . '</h1>';
    $message .= '</td></tr>';
    $message .= '<tr><td style="padding:30px;">';
    $message .= '<h2 style="margin:0 0 16px;font-size:20px;color:#ffffff;">Hello ' . esc_html($display_name) . ',</h2>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Thank you for applying to the <strong style="color:#ffffff;">' . esc_html($site_name) . ' Affiliate Program</strong>. Your application has been received and is now <strong style="color:#44f80c;">pending review</strong>.</p>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">What happens next:</p>';
    $message .= '<ul style="color:#94a3b8;font-size:15px;line-height:1.6;padding-left:20px;">';
    $message .= '<li>Our team will review your application within <strong style="color:#ffffff;">24-48 hours</strong>.</li>';
    $message .= '<li>You will receive an email notification once your application is approved.</li>';
    $message .= '<li>After approval, you can log in to your <a href="' . esc_url($affiliate_area) . '" style="color:#ff66c4;text-decoration:underline;">affiliate dashboard</a> to access your referral link and track earnings.</li>';
    $message .= '</ul>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">If you have any questions in the meantime, simply reply to this email or contact us at ' . esc_html($admin_email) . '.</p>';
    $message .= '</td></tr>';
    $message .= '<tr><td style="padding:20px 30px;text-align:center;border-top:1px solid #1f2b47;background-color:#0a0514;">';
    $message .= '<p style="margin:0;color:#64748b;font-size:12px;">' . esc_html($site_name) . ' &bull; <a href="' . esc_url($site_url) . '" style="color:#64748b;text-decoration:none;">' . esc_url($site_url) . '</a></p>';
    $message .= '</td></tr></table></td></tr></table></body></html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
    );
    wp_mail($email, $subject, $message, $headers);
}

// ============================================
// AFFILIATE LOGIN REDIRECT
// ============================================

add_filter('login_redirect', 'microdos_affiliate_login_redirect', 10, 3);

function microdos_affiliate_login_redirect($redirect_to, $request, $user) {
    if (!is_a($user, 'WP_User')) return $redirect_to;
    if (function_exists('affwp_is_affiliate') && affwp_is_affiliate($user->ID)) {
        // Send affiliates to the main dashboard
        $affiliate_area = get_permalink(get_page_by_path('affiliate-area'));
        if ($affiliate_area) return $affiliate_area;
    }
    return $redirect_to;
}

// ============================================
// AFFILIATEWP EMAIL LOGO SIZE FIX
// ============================================

add_filter('affwp_email_logo', 'microdos_fix_affiliate_email_logo');

function microdos_fix_affiliate_email_logo($logo) {
    if (empty($logo)) return $logo;
    return str_replace('<img', '<img style="max-width:180px;height:auto;display:block;margin:0 auto;" ', $logo);
}

// ============================================
// AFFILIATE TERMS OF SERVICE LINK
// ============================================

add_filter('gform_field_content', 'microdos_affiliate_terms_link', 10, 5);

function microdos_affiliate_terms_link($content, $field, $value, $lead_id, $form_id) {
    if ($form_id != 2) return $content;
    if ($field->type != 'checkbox' || strpos($content, 'terms and conditions') === false) return $content;
    $terms_url = home_url('/affiliate-terms-of-use/');
    return str_replace('terms and conditions', '<a href="' . esc_url($terms_url) . '" target="_blank" style="color:#ff66c4;text-decoration:underline;">terms and conditions</a>', $content);
}

// ============================================
// GRAVITY FORMS TEXT COLOR FIX
// ============================================

add_filter('gform_default_styles', 'microdos_gform_dark_theme_styles', 10, 1);

function microdos_gform_dark_theme_styles($styles) {
    if (is_array($styles)) {
        $style_array = $styles;
    } elseif (is_string($styles)) {
        $style_array = json_decode($styles, true);
    }
    if (empty($style_array) || !is_array($style_array)) {
        $style_array = array();
    }
    $style_array['inputColor'] = '#ffffff';
    $style_array['theme'] = 'orbital';
    $style_array['inputBackgroundColor'] = '#1a1040';
    $style_array['inputBorderColor'] = '#2d2255';
    $style_array['inputPrimaryColor'] = '#44f80c';
    $style_array['labelColor'] = '#ffffff';
    $style_array['descriptionColor'] = '#d1d5db';
    return $style_array;
}

// ============================================
// CUSTOM ORDER STATUS: SHIPPED
// ============================================

add_action('init', 'microdos_register_shipped_status', 10, 0);

function microdos_register_shipped_status() {
    register_post_status('wc-shipped', [
        'label'                     => _x('Shipped', 'Order status', 'microdos4u'),
        'public'                    => false,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>', 'microdos4u'),
    ]);
}

add_filter('wc_order_statuses', 'microdos_add_shipped_status');

function microdos_add_shipped_status($order_statuses) {
    $new_statuses = [];
    foreach ($order_statuses as $key => $value) {
        $new_statuses[$key] = $value;
        if ($key === 'wc-processing') {
            $new_statuses['wc-shipped'] = _x('Shipped', 'Order status', 'microdos4u');
        }
    }
    return $new_statuses;
}

add_filter('bulk_actions-edit-shop_order', 'microdos_add_shipped_bulk_action');

function microdos_add_shipped_bulk_action($actions) {
    $actions['mark_shipped'] = __('Mark as Shipped', 'microdos4u');
    return $actions;
}

// ============================================
// TRACKING NUMBER META FIELD
// ============================================

add_action('add_meta_boxes', 'microdos_add_tracking_meta_box');

function microdos_add_tracking_meta_box() {
    add_meta_box(
        'microdos_tracking',
        __('Shipping & Tracking', 'microdos4u'),
        'microdos_tracking_meta_box_html',
        'shop_order',
        'side',
        'high'
    );
}

function microdos_tracking_meta_box_html($post) {
    $order = wc_get_order($post->ID);
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    wp_nonce_field('microdos_save_tracking', 'microdos_tracking_nonce');
    ?>
    <p>
        <label for="microdos_tracking_number" style="display:block;margin-bottom:4px;font-weight:600;">Tracking Number</label>
        <input type="text" id="microdos_tracking_number" name="microdos_tracking_number" value="<?php echo esc_attr($tracking); ?>" style="width:100%;padding:6px;border:1px solid #1f2b47;background:#150f24;color:#e2e8f0;border-radius:4px;" placeholder="e.g. 9400111899223456789012">
    </p>
    <p>
        <label for="microdos_tracking_carrier" style="display:block;margin-bottom:4px;font-weight:600;">Carrier</label>
        <select id="microdos_tracking_carrier" name="microdos_tracking_carrier" style="width:100%;padding:6px;border:1px solid #1f2b47;background:#150f24;color:#e2e8f0;border-radius:4px;">
            <option value="usps" <?php selected($carrier, 'usps'); ?>>USPS</option>
            <option value="ups" <?php selected($carrier, 'ups'); ?>>UPS</option>
            <option value="fedex" <?php selected($carrier, 'fedex'); ?>>FedEx</option>
        </select>
    </p>
    <p style="margin-top:8px;">
        <button type="button" class="button" onclick="document.getElementById('microdos_tracking_number').value='';document.getElementById('microdos_tracking_carrier').value='usps';">Clear</button>
    </p>
    <?php
}

add_action('save_post', 'microdos_save_tracking_meta');

function microdos_save_tracking_meta($post_id) {
    if (get_post_type($post_id) !== 'shop_order') return;
    if (!isset($_POST['microdos_tracking_nonce']) || !wp_verify_nonce($_POST['microdos_tracking_nonce'], 'microdos_save_tracking')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $tracking = isset($_POST['microdos_tracking_number']) ? sanitize_text_field($_POST['microdos_tracking_number']) : '';
    $carrier  = isset($_POST['microdos_tracking_carrier']) ? sanitize_text_field($_POST['microdos_tracking_carrier']) : 'usps';

    $order = wc_get_order($post_id);
    $order->update_meta_data('_microdos_tracking_number', $tracking);
    $order->update_meta_data('_microdos_tracking_carrier', $carrier);
    $order->save();
}

// ============================================
// SHIPPED EMAIL NOTIFICATION
// ============================================

add_action('woocommerce_order_status_shipped', 'microdos_shipped_email_notification', 10, 1);

function microdos_shipped_email_notification($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $tracking  = $order->get_meta('_microdos_tracking_number', true);
    $carrier   = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $site_name = get_bloginfo('name');
    $site_url  = home_url('/');
    $to        = $order->get_billing_email();
    $subject   = "Your {$site_name} Order Has Shipped (#" . $order->get_order_number() . ")";
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>';
    $message .= '<body style="margin:0;padding:0;background-color:#0a0514;font-family:Arial,Helvetica,sans-serif;">';
    $message .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="padding:30px 20px;">';
    $message .= '<table width="100%" style="max-width:600px;background-color:#150f24;border:1px solid #1f2b47;border-radius:12px;overflow:hidden;">';
    $message .= '<tr><td style="padding:30px;text-align:center;background-color:#0a0514;border-bottom:1px solid #1f2b47;">';
    $message .= '<h1 style="margin:0;font-size:24px;color:#44f80c;">' . esc_html($site_name) . '</h1>';
    $message .= '<p style="margin:8px 0 0;color:#94a3b8;font-size:14px;">Order Shipped</p>';
    $message .= '</td></tr>';
    $message .= '<tr><td style="padding:30px;">';
    $message .= '<h2 style="margin:0 0 16px;font-size:20px;color:#ffffff;">Hello ' . esc_html($order->get_billing_first_name()) . ',</h2>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Great news! Your order <strong style="color:#ffffff;">#' . esc_html($order->get_order_number()) . '</strong> has been shipped and is on its way.</p>';
    if ($tracking && $tracking_url) {
        $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Tracking number: <strong style="color:#ffffff;">' . esc_html($tracking) . '</strong></p>';
        $message .= '<p style="text-align:center;margin:20px 0;"><a href="' . esc_url($tracking_url) . '" style="display:inline-block;background-color:#44f80c;color:#0a0514;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;">Track Your Package</a></p>';
    }
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">You can also track your order in your <a href="' . esc_url(wc_get_page_permalink('myaccount') . 'orders/') . '" style="color:#ff66c4;text-decoration:underline;">account</a>.</p>';
    $message .= '</td></tr>';
    $message .= '<tr><td style="padding:20px 30px;text-align:center;border-top:1px solid #1f2b47;background-color:#0a0514;">';
    $message .= '<p style="margin:0;color:#64748b;font-size:12px;">' . esc_html($site_name) . ' &bull; <a href="' . esc_url($site_url) . '" style="color:#64748b;text-decoration:none;">' . esc_url($site_url) . '</a></p>';
    $message .= '</td></tr></table></td></tr></table></body></html>';

    $headers = ['Content-Type: text/html; charset=UTF-8', 'From: ' . $site_name . ' <' . get_option('admin_email') . '>'];
    wp_mail($to, $subject, $message, $headers);
}

function microdos_get_tracking_url_by_carrier($tracking, $carrier) {
    if (!$tracking) return '';
    switch ($carrier) {
        case 'usps':  return 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . esc_attr($tracking);
        case 'ups':   return 'https://www.ups.com/track?tracknum=' . esc_attr($tracking);
        case 'fedex': return 'https://www.fedex.com/fedextrack/?trknbr=' . esc_attr($tracking);
        default:      return '';
    }
}

// ============================================
// CUSTOMER-FACING: Show tracking on order list
// ============================================

add_filter('woocommerce_my_account_my_orders_columns', 'microdos_add_tracking_column');

function microdos_add_tracking_column($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'order-status') {
            $new_columns['order-tracking'] = __('Tracking', 'microdos4u');
        }
    }
    return $new_columns;
}

add_action('woocommerce_my_account_my_orders_column_order-tracking', 'microdos_display_tracking_column');

function microdos_display_tracking_column($order) {
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url && $order->has_status('shipped')) {
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="color:#44f80c;text-decoration:underline;font-size:13px;font-weight:600;">' . esc_html($tracking) . '</a>';
    } elseif ($tracking && $tracking_url && $order->has_status('completed')) {
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="color:#44f80c;text-decoration:underline;font-size:13px;">' . esc_html($tracking) . '</a>';
    } else {
        echo '<span style="color:#64748b;font-size:13px;">--</span>';
    }
}

add_action('woocommerce_view_order', 'microdos_display_tracking_on_order', 20);

function microdos_display_tracking_on_order($order_id) {
    $order = wc_get_order($order_id);
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url) {
        echo '<div style="background:#0a0514;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin:20px 0;">';
        echo '<h3 style="color:#44f80c;margin:0 0 12px;font-size:16px;">Shipping & Tracking</h3>';
        echo '<p style="color:#94a3b8;margin:0 0 8px;">Carrier: <strong style="color:#e2e8f0;">' . esc_html(strtoupper($carrier)) . '</strong></p>';
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking #: <strong style="color:#e2e8f0;">' . esc_html($tracking) . '</strong></p>';
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="display:inline-block;background:#44f80c;color:#0a0514;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px;">Track on ' . esc_html(strtoupper($carrier)) . '</a>';
        echo '</div>';
    }
}

add_action('woocommerce_thankyou', 'microdos_thankyou_shipping_notice', 15);

function microdos_thankyou_shipping_notice($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url && ($order->has_status('shipped') || $order->has_status('completed'))) {
        echo '<div style="background:#0a0514;border:1px solid #44f80c;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">';
        echo '<h3 style="color:#44f80c;margin:0 0 8px;font-size:16px;">Your Order Has Shipped!</h3>';
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking: <strong style="color:#e2e8f0;">' . esc_html($tracking) . '</strong></p>';
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="display:inline-block;background:#44f80c;color:#0a0514;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px;">Track Package</a>';
        echo '</div>';
    } else {
        echo '<div style="background:#0a0514;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">';
        echo '<p style="color:#94a3b8;margin:0;">Your order will ship within <strong style="color:#e2e8f0;">1-2 business days</strong>. You will receive a tracking email once it ships.</p>';
        echo '</div>';
    }
}

// ============================================
// SHIPPING DASHBOARD ADMIN PAGE
// ============================================

require_once get_template_directory() . '/admin-shipping.php';

// ============================================
// AUTO-CREATE SHIPPING PORTAL PAGE
// ============================================

add_action('after_switch_theme', 'microdos_create_shipping_portal_page');
add_action('admin_init', 'microdos_create_shipping_portal_page');

function microdos_create_shipping_portal_page() {
    $existing = get_page_by_path('shipping-portal');
    if ($existing) return;

    $pages = get_pages([
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'page-shipping-portal.php',
    ]);
    if (!empty($pages)) return;

    $page_id = wp_insert_post([
        'post_title'   => 'Shipping Portal',
        'post_name'    => 'shipping-portal',
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => 1,
        'page_template'=> 'page-shipping-portal.php',
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        update_post_meta($page_id, '_wp_page_template', 'page-shipping-portal.php');
    }
}

// ============================================
// AUTO-CREATE AFFILIATE GUIDE PAGES + MENU LINKS
// ============================================

function microdos_get_getting_started_content() {
    return '<!-- wp:html -->
<div style="max-width:800px;">
<div style="background:linear-gradient(135deg,#1e3a5f,#0f1d3a);border:1px solid #3b82f6;border-radius:8px;padding:20px;margin-bottom:24px;">
<strong style="color:#60a5fa;font-size:13px;text-transform:uppercase;letter-spacing:0.05em;">Your Unique Referral Link</strong>
<p style="color:#c7d2e8;font-size:14px;margin:8px 0;">Copy this link and share it everywhere. When someone clicks and buys, you earn 20%.</p>
<p style="background:rgba(59,130,246,0.15);color:#93bbfc;padding:10px 14px;border-radius:6px;font-size:14px;word-break:break-all;margin:8px 0;">[affiliate_referral_url]</p>
</div>
<!-- Content truncated for brevity -->
</div>
<!-- /wp:html -->';
}

function microdos_get_marketing_guide_content() {
    return '<!-- wp:html -->
<div style="max-width:800px;">
<h3 style="color:#e2e8f0;font-size:22px;font-weight:600;margin-bottom:12px;">Marketing Guide</h3>
<!-- Content truncated for brevity -->
</div>
<!-- /wp:html -->';
}

// ============================================
// AFFILIATE DASHBOARD GUIDE FEATURES
// ============================================

/**
 * 1. Auto-create Dashboard Guide page
 */
add_action('wp_loaded', 'microdos_create_dashboard_guide_page', 21);

function microdos_create_dashboard_guide_page() {
    if (wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) return;
    if (is_admin() || is_customize_preview()) return;

    $existing = get_page_by_path('affiliate-dashboard-guide');
    if ($existing) return;

    wp_insert_post(array(
        'post_title'    => 'Affiliate Dashboard Guide',
        'post_name'     => 'affiliate-dashboard-guide',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'page_template' => 'page-affiliate-dashboard-guide.php',
    ));
}

/**
 * 2. Enqueue Shepherd.js + tour + welcome panel scripts
 */
add_action('wp_enqueue_scripts', 'microdos_enqueue_affiliate_assets', 101);

function microdos_enqueue_affiliate_assets() {
    if (is_admin() || is_customize_preview()) return;

    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $is_affiliate_page = (
        strpos($uri, '/affiliate-area') !== false ||
        strpos($uri, '/affiliate-dashboard-guide') !== false ||
        strpos($uri, '/affiliate-portal') !== false
    );

    if (!$is_affiliate_page && is_page()) {
        $template = get_page_template_slug();
        if ($template === 'page-affiliate-area.php' || $template === 'page-affiliate-dashboard-guide.php') {
            $is_affiliate_page = true;
        }
    }

    if (!$is_affiliate_page) return;
    if (!is_user_logged_in()) return;
    if (!function_exists('affwp_is_affiliate') || !affwp_is_affiliate()) return;

    // Shepherd.js from CDN
    wp_enqueue_script('shepherd-js', 'https://cdn.jsdelivr.net/npm/shepherd.js@11.2.0/dist/js/shepherd.min.js', array(), '11.2.0', true);
    wp_enqueue_style('shepherd-css', 'https://cdn.jsdelivr.net/npm/shepherd.js@11.2.0/dist/css/shepherd.css', array(), '11.2.0');

    // Data for JS
    $guide_page = get_page_by_path('affiliate-dashboard-guide');
    $mg_page = get_page_by_path('marketing-guide');
    $affiliate_id = function_exists('affwp_get_affiliate_id') ? affwp_get_affiliate_id() : 0;
    $referral_url = '';
    if ($affiliate_id) {
        $referral_url = affwp_get_affiliate_referral_url(array('affiliate_id' => $affiliate_id));
    }

    wp_add_inline_script('shepherd-js', 'window.microDOSPortalData = {"guideUrl":"' . esc_url($guide_page ? get_permalink($guide_page) : '') . '","mgUrl":"' . esc_url($mg_page ? get_permalink($mg_page) : '') . '","referralUrl":"' . esc_url($referral_url) . '"};', 'before');

    // Tour JS - embedded directly
    wp_add_inline_script('shepherd-js', <<<'MICRODOS_TOUR'
(function() {
'use strict';
var CONFIG = {
STORAGE_KEY_COMPLETED: 'microdos_tour_completed',
STORAGE_KEY_SKIPPED:   'microdos_tour_skipped',
STORAGE_KEY_DISMISSED: 'microdos_help_dismissed_at',
STORAGE_KEY_TOUR_STEP: 'microdos_tour_step',
AUTO_LAUNCH_DELAY: 1200,
HELP_BUTTON_HIDE_DAYS: 30,
};
var _isPortal = null;
function isPortal() {
if (_isPortal !== null) return _isPortal;
_isPortal = !!document.querySelector('.affwp-portal, .affiliate-portal, .affwp-portal-sidebar, [class*="portal-sidebar"]');
return _isPortal;
}
function isOldTabbed() {
return !!document.querySelector('.affwp-tabs, .affwp-tab-wrapper, .affwp-wrap');
}
function isAffiliateDashboard() {
return isPortal() || isOldTabbed() ||
window.location.pathname.indexOf('affiliate') !== -1;
}
function isMainDashboardTab() {
if (isPortal()) {
var hash = window.location.hash;
return !hash || hash === '#/' || hash === '';
}
return !window.location.search.match(/[?&]tab=/);
}
function storageGet(key) {
try { return localStorage.getItem(key); } catch (e) { return null; }
}
function storageSet(key, value) {
try { localStorage.setItem(key, value); } catch (e) {}
}
function storageRemove(key) {
try { localStorage.removeItem(key); } catch (e) {}
}
function shouldAutoLaunch() {
if (storageGet(CONFIG.STORAGE_KEY_COMPLETED)) return false;
if (storageGet(CONFIG.STORAGE_KEY_SKIPPED)) return false;
return true;
}
function injectFloatingHelpButton() {
var dismissedAt = storageGet(CONFIG.STORAGE_KEY_DISMISSED);
if (dismissedAt) {
var daysSince = (Date.now() - parseInt(dismissedAt, 10)) / (1000 * 60 * 60 * 24);
if (daysSince < CONFIG.HELP_BUTTON_HIDE_DAYS) return;
}
if (window.location.pathname.indexOf('affiliate-dashboard-guide') !== -1) return;
if (document.getElementById('microdos-floating-help')) return;
var wrapper = document.createElement('div');
wrapper.id = 'microdos-floating-help';
wrapper.innerHTML =
'<button id="microdos-help-btn" title="Need Help? Take a tour" aria-label="Need Help? Take a tour">' +
'<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
'<circle cx="12" cy="12" r="10"/>' +
'<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>' +
'<line x1="12" y1="17" x2="12.01" y2="17"/>' +
'</svg>' +
'</button>' +
'<button id="microdos-help-close" title="Dismiss" aria-label="Dismiss">' +
'<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">' +
'<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>' +
'</svg>' +
'</button>';
var style = document.createElement('style');
style.textContent =
'#microdos-floating-help{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;align-items:center;gap:8px;}' +
'#microdos-help-btn{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#44f80c,#3ad60a);color:#0a0514;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(68,248,12,0.3);transition:transform .2s,box-shadow .2s;}' +
'#microdos-help-btn:hover{transform:scale(1.08);box-shadow:0 6px 24px rgba(68,248,12,0.45);}' +
'#microdos-help-close{width:24px;height:24px;border-radius:50%;background:rgba(100,116,139,0.2);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);cursor:pointer;display:flex;align-items:center;justify-content:center;opacity:.7;transition:opacity .2s;padding:0;}' +
'#microdos-help-close:hover{opacity:1;background:rgba(239,68,68,0.15);color:#ef4444;}';
document.head.appendChild(style);
document.body.appendChild(wrapper);
document.getElementById('microdos-help-btn').addEventListener('click', function() {
launchTour(true);
});
document.getElementById('microdos-help-close').addEventListener('click', function(e) {
e.stopPropagation();
wrapper.style.opacity = '0';
wrapper.style.transform = 'translateY(10px)';
setTimeout(function() { wrapper.remove(); }, 300);
storageSet(CONFIG.STORAGE_KEY_DISMISSED, Date.now().toString());
});
}
function injectShepherdTheme() {
if (document.getElementById('microdos-shepherd-theme')) return;
var css =
'.shepherd-element{background:#150f24!important;border:1px solid #1f2b47!important;border-radius:12px!important;box-shadow:0 16px 48px rgba(0,0,0,0.5)!important;color:#d1d5db!important;max-width:380px!important;}' +
'.shepherd-text{color:#d1d5db!important;font-size:14px!important;line-height:1.6!important;padding:20px 24px 0!important;}' +
'.shepherd-text h3{color:#fff!important;font-size:16px!important;font-weight:700!important;margin:0 0 10px!important;}' +
'.shepherd-text p{margin:0 0 12px!important;}' +
'.shepherd-text p:last-child{margin-bottom:0!important;}' +
'.shepherd-footer{padding:16px 24px 20px!important;display:flex;justify-content:space-between;align-items:center;}' +
'.shepherd-button{padding:8px 18px!important;border-radius:6px!important;font-size:13px!important;font-weight:600!important;cursor:pointer!important;transition:opacity .2s!important;}' +
'.shepherd-button:hover{opacity:.85!important;}' +
'.shepherd-button.shepherd-button-primary{background:#44f80c!important;color:#0a0514!important;border:none!important;}' +
'.shepherd-button:not(.shepherd-button-primary){background:transparent!important;color:#94a3b8!important;border:1px solid #1f2b47!important;}' +
'.shepherd-button:not(.shepherd-button-primary):hover{background:rgba(68,248,12,.05)!important;color:#44f80c!important;}' +
'.shepherd-cancel-icon{color:#64748b!important;font-size:20px!important;top:12px!important;right:12px!important;}' +
'.shepherd-cancel-icon:hover{color:#ef4444!important;}' +
'.shepherd-arrow::before{background:#150f24!important;border:1px solid #1f2b47!important;}' +
'.shepherd-has-title .shepherd-content .shepherd-header{background:transparent!important;padding:20px 24px 0!important;}' +
'.shepherd-title{color:#44f80c!important;font-size:16px!important;font-weight:700!important;}' +
'.shepherd-progress{color:#64748b!important;font-size:12px!important;margin-right:auto;padding-right:12px;}';
var style = document.createElement('style');
style.id = 'microdos-shepherd-theme';
style.textContent = css;
document.head.appendChild(style);
}
function getTourSteps() {
var guideUrl = window.microDOSPortalData ? window.microDOSPortalData.guideUrl : '/affiliate-dashboard-guide/';
function sel(portalSel, oldSel) {
return isPortal() ? portalSel : oldSel;
}
var steps = [
{
id: 'step-welcome',
title: 'Welcome to Your Dashboard!',
text: '<p>This is your affiliate command center. Every stat, chart, and tool you need is here. Let us show you around in <strong>10 quick steps</strong>.</p>',
attachTo: { element: sel('.affwp-portal-content, .affwp-portal-main, .portal-content', '.affwp-wrap, .affwp-tab-content'), on: 'bottom' },
buttons: [
{ text: 'Skip Tour', action: function() { skipTour(); }, classes: 'shepherd-button-secondary' },
{ text: 'Start Tour →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-referral-url',
title: 'Your Referral Link',
text: '<p>This is your money link. Copy it and share it anywhere — social media, email, blog, QR code. When someone clicks and buys within 45 days, you earn <strong>20% commission</strong>.</p>',
attachTo: { element: sel('.affwp-portal-content .affwp-referral-url, .affwp-portal-content .affwp-url', '.affwp-referral-url, .affwp-url'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-navigation',
title: 'Dashboard Navigation',
text: '<p>Use the ' + (isPortal() ? 'sidebar' : 'tabs') + ' to explore different sections:</p>' +
'<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
'<li><strong>Dashboard/Home</strong> — Your stats overview</li>' +
'<li><strong>Referral URLs</strong> — Custom links & QR codes</li>' +
'<li><strong>Statistics</strong> — Detailed numbers</li>' +
'<li><strong>Graphs</strong> — Visual trends</li>' +
'<li><strong>Referrals</strong> — Your sales & statuses</li>' +
'<li><strong>Creatives</strong> — Banners & ads</li>' +
'<li><strong>Payouts</strong> — Payments</li>' +
'<li><strong>Settings</strong> — Profile & payment email</li>' +
'</ul>',
attachTo: { element: sel('.affwp-portal-sidebar, .portal-sidebar', '.affwp-tabs, .affwp-tab-wrapper'), on: 'right' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-stats',
title: 'Your Stats',
text: '<p>Your performance at a glance:</p>' +
'<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
'<li><strong>Earnings</strong> — Total money earned</li>' +
'<li><strong>Paid</strong> — Already sent to you</li>' +
'<li><strong>Unpaid</strong> — Coming next payout</li>' +
'<li><strong>Conversion Rate</strong> — Clicks that bought</li>' +
'</ul>',
attachTo: { element: sel('.affwp-portal-content .affwp-stats, .affwp-portal-content [class*="stat"]', '.affwp-stats, .affwp-dashboard-stats'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-referrals',
title: 'Referral Statuses',
text: '<p>Every sale goes through statuses:</p>' +
'<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
'<li><span style="color:#ffaa00">● Pending</span> — Order processing (24-48h)</li>' +
'<li><span style="color:#60a5fa">● Unpaid</span> — Confirmed, awaiting payout</li>' +
'<li><span style="color:#44f80c">● Paid</span> — Money sent to you</li>' +
'<li><span style="color:#ef4444">● Rejected</span> — Refunded or cancelled</li>' +
'</ul>',
attachTo: { element: sel('.affwp-portal-content .affwp-referrals, .affwp-portal-content [class*="referral"]', '.affwp-referrals'), on: 'top' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-creatives',
title: 'Marketing Materials',
text: '<p>Pre-made banners and text ads with your link <strong>already built in</strong>. Click "Copy Link" to grab the code, then paste into your social post or email. No design work needed.</p>',
attachTo: { element: sel('.affwp-portal-content .affwp-creatives', '.affwp-creatives'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-visits',
title: 'Tracking Visits',
text: '<p>See who clicked your link and where they came from. Check this 24 hours after posting to see which platforms drive the most traffic.</p>',
attachTo: { element: sel('.affwp-portal-content .affwp-visits', '.affwp-visits'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-graphs',
title: 'Growth Graphs',
text: '<p>Watch your earnings and referral count grow over time. Use date filters to spot trends. Spikes usually happen right after you post on social media.</p>',
attachTo: { element: sel('.affwp-portal-content .affwp-graphs', '.affwp-graphs'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-payouts',
title: 'Getting Paid',
text: '<p>Payouts happen automatically on the <strong>1st of every month</strong> via PayPal. You need at least <strong>$50</strong> to trigger a payout. Make sure your payment email is correct, and submit your <strong>W-9</strong> (US affiliates).</p>',
attachTo: { element: sel('.affwp-portal-content .affwp-payouts', '.affwp-payouts'), on: 'bottom' },
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
]
},
{
id: 'step-finish',
title: 'You Are Ready!',
text: '<p>That is everything. Here is your quick start:</p>' +
'<ol style="margin:8px 0;padding-left:18px;font-size:13px;">' +
'<li>Copy your referral link</li>' +
'<li>Grab a banner from Creatives</li>' +
'<li>Post with a personal recommendation</li>' +
'<li>Check Visits tomorrow</li>' +
'</ol>' +
'<p style="margin-top:10px;font-size:13px;">Need a refresher? Visit the <a href="' + guideUrl + '" style="color:#44f80c;font-weight:600;">Dashboard Guide</a> anytime.</p>',
buttons: [
{ text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
{ text: 'Done!', action: function() { completeTour(); }, classes: 'shepherd-button-primary' }
]
}
];
return steps;
}
var tour = null;
function buildTour() {
injectShepherdTheme();
tour = new Shepherd.Tour({
defaultStepOptions: {
cancelIcon: { enabled: true },
scrollTo: { behavior: 'smooth', block: 'center' },
when: {
show: function() {
var currentStep = tour.steps.indexOf(tour.getCurrentStep()) + 1;
var totalSteps = tour.steps.length;
var progressEl = document.createElement('span');
progressEl.className = 'shepherd-progress';
progressEl.textContent = currentStep + ' / ' + totalSteps;
var footer = document.querySelector('.shepherd-footer');
if (footer) {
var existing = footer.querySelector('.shepherd-progress');
if (existing) existing.remove();
footer.insertBefore(progressEl, footer.firstChild);
}
storageSet(CONFIG.STORAGE_KEY_TOUR_STEP, currentStep.toString());
}
}
},
useModalOverlay: true
});
var steps = getTourSteps();
steps.forEach(function(step) {
if (step.attachTo && step.attachTo.element) {
var el = document.querySelector(step.attachTo.element);
if (!el) delete step.attachTo;
}
tour.addStep(step);
});
tour.on('cancel', function() {
if (!storageGet(CONFIG.STORAGE_KEY_COMPLETED)) {
storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
}
storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
});
return tour;
}
window.microDOSAffiliateTour = {
launch: function(userInitiated) {
if (typeof Shepherd === 'undefined') { console.warn('[microDOS Tour] Shepherd.js not loaded'); return; }
if (tour) { tour.complete(); tour = null; }
if (userInitiated) {
storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
}
buildTour();
tour.start();
},
reset: function() {
storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
storageRemove(CONFIG.STORAGE_KEY_DISMISSED);
storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
console.log('[microDOS Tour] Reset. Refresh to start over.');
}
};
function completeTour() {
storageSet(CONFIG.STORAGE_KEY_COMPLETED, 'true');
storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
if (tour) tour.complete();
}
function skipTour() {
storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
if (tour) tour.complete();
}
function init() {
if (!isAffiliateDashboard()) return;
injectFloatingHelpButton();
if (shouldAutoLaunch() && isMainDashboardTab()) {
setTimeout(function() {
if (isMainDashboardTab()) launchTour(false);
}, CONFIG.AUTO_LAUNCH_DELAY);
}
}
if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', init);
} else {
init();
}
})();
MICRODOS_TOUR
    );

    // Welcome panel JS - embedded directly
    wp_register_script('microdos-portal', '', array(), MICRODOS_VERSION, true);
    wp_enqueue_script('microdos-portal');
    wp_add_inline_script('microdos-portal', <<<'MICRODOS_WELCOME'
(function() {
'use strict';
var DATA = window.microDOSPortalData || {};
var GUIDE_URL = DATA.guideUrl || '/affiliate-dashboard-guide/';
var MG_URL = DATA.mgUrl || '/marketing-guide/';
var REFERRAL_URL = DATA.referralUrl || '';
function injectSidebarLinks() {
if (document.getElementById('microdos-sidebar-links')) return;
var sidebar = findSidebarNav();
if (!sidebar) {
console.log('[microDOS] Sidebar nav not found, retrying...');
setTimeout(injectSidebarLinks, 500);
return;
}
var container = document.createElement('div');
container.id = 'microdos-sidebar-links';
container.style.cssText = 'margin-top:16px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.06);';
if (GUIDE_URL) {
container.appendChild(createSidebarLink(
GUIDE_URL,
'Dashboard Guide',
'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
'dashboard-guide'
));
}
if (MG_URL) {
container.appendChild(createSidebarLink(
MG_URL,
'Marketing Guide',
'<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
'marketing-guide'
));
}
sidebar.appendChild(container);
console.log('[microDOS] Sidebar links injected');
}
function findSidebarNav() {
var selectors = [
'.affwp-portal-sidebar nav',
'.affiliate-portal-sidebar nav',
'.portal-sidebar nav',
'[class*="portal-sidebar"] nav',
'.affwp-portal-sidebar',
'.affiliate-portal-sidebar',
'.portal-sidebar',
'aside nav',
'aside',
'.sidebar nav',
'.sidebar'
];
for (var i = 0; i < selectors.length; i++) {
var el = document.querySelector(selectors[i]);
if (el) return el;
}
var dashboardLink = findElementByText('Dashboard', 'a');
if (dashboardLink) {
var parent = dashboardLink.parentElement;
while (parent && parent.tagName !== 'BODY') {
if (parent.tagName === 'ASIDE' || parent.tagName === 'NAV' ||
parent.classList.contains('sidebar') ||
parent.classList.contains('portal-sidebar') ||
parent.classList.contains('affwp-portal-sidebar')) {
return parent;
}
parent = parent.parentElement;
}
}
return null;
}
function findElementByText(text, tag) {
var elements = document.querySelectorAll(tag);
for (var i = 0; i < elements.length; i++) {
if (elements[i].textContent.trim() === text) {
return elements[i];
}
}
return null;
}
function createSidebarLink(href, text, iconHtml, id) {
var existingLink = document.querySelector('.affwp-portal-sidebar a, .affiliate-portal-sidebar a, .portal-sidebar a, aside a');
var computedStyle = existingLink ? window.getComputedStyle(existingLink) : null;
var link = document.createElement('a');
link.href = href;
link.id = 'microdos-link-' + id;
link.style.cssText = 'display:flex;align-items:center;gap:12px;padding:10px 20px;color:#94a3b8;text-decoration:none;font-size:14px;font-weight:500;transition:all 0.2s;border-radius:6px;margin:2px 8px;';
link.innerHTML = '<span style="flex-shrink:0;opacity:0.7;">' + iconHtml + '</span><span>' + text + '</span>';
link.addEventListener('mouseenter', function() {
link.style.backgroundColor = 'rgba(68,248,12,0.06)';
link.style.color = '#44f80c';
});
link.addEventListener('mouseleave', function() {
link.style.backgroundColor = 'transparent';
link.style.color = '#94a3b8';
});
if (existingLink) {
var existingOnclick = existingLink.getAttribute('onclick');
if (existingOnclick) {
}
}
return link;
}
function injectWelcomePanel() {
if (document.getElementById('microdos-welcome-panel')) return;
var content = findContentArea();
if (!content) {
setTimeout(injectWelcomePanel, 500);
return;
}
var panel = document.createElement('div');
panel.id = 'microdos-welcome-panel';
panel.style.cssText = 'background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin:0 0 24px 0;font-family:inherit;position:relative;';
panel.innerHTML = buildWelcomeHTML();
if (content.firstChild) {
content.insertBefore(panel, content.firstChild);
} else {
content.appendChild(panel);
}
console.log('[microDOS] Welcome panel injected');
}
function findContentArea() {
var selectors = [
'.affwp-portal-content',
'.affiliate-portal-content',
'.portal-content',
'.affwp-portal-main',
'.affiliate-portal-main',
'main',
'.content-area',
'.site-main',
'article'
];
for (var i = 0; i < selectors.length; i++) {
var el = document.querySelector(selectors[i]);
if (el) return el;
}
var statCard = document.querySelector('[class*="referral"], [class*="stat"]');
if (statCard) {
var parent = statCard.parentElement;
while (parent && parent.tagName !== 'BODY') {
if (parent.children.length > 2) return parent;
parent = parent.parentElement;
}
}
return null;
}
function buildWelcomeHTML() {
var refDisplay = REFERRAL_URL || 'Your referral link will appear here';
return '<button id="mcd-dismiss" title="Hide" style="position:absolute;top:12px;right:12px;background:none;border:1px solid #cbd5e1;color:#94a3b8;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center;padding:0;">&times;</button>' +
'<h3 style="margin:0 0 16px;font-size:18px;font-weight:700;color:#0f172a;">Getting Started as a microDOS(2) Affiliate</h3>' +
'<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px 18px;margin-bottom:20px;">' +
'<strong style="color:#44f80c;font-size:12px;text-transform:uppercase;letter-spacing:0.05em;">Your Referral Link</strong>' +
'<p style="color:#64748b;font-size:13px;margin:6px 0 10px;">Share this link everywhere. When someone clicks and buys, you earn 20%.</p>' +
'<code id="mcd-ref-url" style="display:block;background:#f1f5f9;color:#0f172a;padding:10px 14px;border-radius:6px;font-size:13px;word-break:break-all;margin:0 0 10px;font-family:monospace;">' + escapeHtml(refDisplay) + '</code>' +
'<button onclick="copyMcdRef(this)" style="padding:8px 18px;background:#44f80c;color:#0a0514;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Copy Link</button>' +
'</div>' +
'<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">' +
'<div style="background:#f8fafc;padding:12px;border-radius:6px;">' +
'<strong style="color:#0f172a;font-size:13px;">How It Works</strong>' +
'<ol style="color:#64748b;font-size:12px;line-height:1.6;padding-left:16px;margin:8px 0 0;">' +
'<li>Share your link</li><li>Someone clicks</li><li>They buy within 45 days</li><li>You earn 20%</li>' +
'</ol>' +
'</div>' +
'<div style="background:#f8fafc;padding:12px;border-radius:6px;">' +
'<strong style="color:#0f172a;font-size:13px;">Quick Start</strong>' +
'<ol style="color:#64748b;font-size:12px;line-height:1.6;padding-left:16px;margin:8px 0 0;">' +
'<li>Copy your link</li><li>Grab a banner from Creatives</li><li>Post with a recommendation</li><li>Check Visits tomorrow</li>' +
'</ol>' +
'</div>' +
'</div>' +
'<div style="display:flex;gap:12px;flex-wrap:wrap;">' +
'<button onclick="launchMcdTour()" style="padding:10px 20px;background:#44f80c;color:#0a0514;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">' +
'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>' +
'Take a Tour' +
'</button>' +
'<a href="' + escapeHtml(GUIDE_URL) + '" style="padding:10px 20px;background:#ff66c4;color:#fff;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">' +
'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>' +
'Dashboard Guide' +
'</a>' +
(MG_URL ? '<a href="' + escapeHtml(MG_URL) + '" style="padding:10px 20px;background:#9a02d0;color:#fff;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">' +
'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>' +
'Marketing Guide' +
'</a>' : '') +
'</div>';
}
function escapeHtml(text) {
var div = document.createElement('div');
div.textContent = text;
return div.innerHTML;
}
window.copyMcdRef = function(btn) {
var url = document.getElementById('mcd-ref-url').textContent;
navigator.clipboard.writeText(url).then(function() {
btn.textContent = 'Copied!';
setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
}, function() {
var ta = document.createElement('textarea');
ta.value = url;
document.body.appendChild(ta);
ta.select();
document.execCommand('copy');
document.body.removeChild(ta);
btn.textContent = 'Copied!';
setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
});
};
window.launchMcdTour = function() {
if (window.microDOSAffiliateTour && window.microDOSAffiliateTour.launch) {
window.microDOSAffiliateTour.launch(true);
} else {
alert('Tour is loading... Please try again in a moment.');
}
};
function init() {
var path = window.location.pathname;
var isAffiliate = path.indexOf('affiliate') !== -1 ||
path.indexOf('portal') !== -1 ||
document.querySelector('.affwp-portal') ||
document.querySelector('.affiliate-portal');
if (!isAffiliate) return;
injectSidebarLinks();
var isMainDash = !window.location.search.match(/[?&]tab=/) &&
!window.location.hash.match(/tab/);
if (isMainDash) {
injectWelcomePanel();
}
}
if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', init);
} else {
init();
}
setTimeout(init, 500);
setTimeout(init, 1500);
})();
MICRODOS_WELCOME
    );

    // 3. Creative Easy Copy Buttons (Template Override approach)
    // Template: affiliatewp/creative.php overrides AffiliateWP's default
    // Docs: https://affiliatewp.com/docs/modifying-template-files/
    // CSS and JS are enqueued separately via microdos_enqueue_creative_copy_assets()
}

/**
 * Enqueue creative copy button assets (CSS + JS)
 *
 * Uses AffiliateWP's official template override system:
 * - Template: your-theme/affiliatewp/creative.php
 * - CSS: your-theme/css/affiliate-copy-buttons.css
 * - JS: your-theme/js/affiliate-copy-buttons.js
 *
 * The template override adds 3 copy buttons to each creative:
 * - Copy Image URL: banner image address for social media
 * - Copy My Link: personal affiliate referral URL
 * - Copy for Email: ready-to-paste HTML for Gmail
 */
add_action('wp_enqueue_scripts', 'microdos_enqueue_creative_copy_assets', 20);

function microdos_enqueue_creative_copy_assets() {

    // Check if we're on an AffiliateWP page
    $is_affwp_page = false;

    // Method 1: Affiliate Portal
    if (function_exists('affwp_is_affiliate_portal') && affwp_is_affiliate_portal()) {
        $is_affwp_page = true;
    }

    // Method 2: Affiliate area page
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    if (strpos($uri, '/affiliate-area') !== false ||
        strpos($uri, '/affiliate-portal') !== false) {
        $is_affwp_page = true;
    }

    // Method 3: Shortcode detection
    if (!$is_affwp_page) {
        global $post;
        if (is_a($post, 'WP_Post')) {
            $content = $post->post_content;
            if (has_shortcode($content, 'affiliate_area') ||
                has_shortcode($content, 'affiliate_creatives') ||
                has_shortcode($content, 'affiliate_portal')) {
                $is_affwp_page = true;
            }
        }
    }

    // Method 4: Affiliate area query var
    if (!$is_affwp_page && get_query_var('affiliate-area')) {
        $is_affwp_page = true;
    }

    // Method 5: Page template
    if (!$is_affwp_page) {
        $template = get_page_template_slug();
        if (strpos($template, 'affiliate') !== false) {
            $is_affwp_page = true;
        }
    }

    // Allow customization
    $is_affwp_page = apply_filters('microdos_load_creative_assets', $is_affwp_page);

    if (!$is_affwp_page) {
        return;
    }

    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();

    // Enqueue CSS
    $css_file = '/css/affiliate-copy-buttons.css';
    if (file_exists($theme_dir . $css_file)) {
        wp_enqueue_style(
            'microdos-creative-copy-buttons',
            $theme_uri . $css_file,
            array(),
            MICRODOS_VERSION,
            'all'
        );
    }

    // Enqueue JS (vanilla JS, no jQuery dependency)
    $js_file = '/js/affiliate-copy-buttons.js';
    if (file_exists($theme_dir . $js_file)) {
        wp_enqueue_script(
            'microdos-creative-copy-buttons',
            $theme_uri . $js_file,
            array(),
            MICRODOS_VERSION,
            true
        );
    }

        // Enqueue Portal creative modal copy buttons (detects modal open, injects buttons)
        $modal_js = '/js/affiliate-portal-modal.js';
        if (file_exists($theme_dir . $modal_js)) {
            wp_enqueue_script(
                'microdos-portal-modal',
                $theme_uri . $modal_js,
                array(),
                MICRODOS_VERSION,
                true
            );
        }
}

/**
 * 3. Affiliate Portal Menu Links
 */
add_filter('affwp_affiliate_portal_menu_items', 'microdos_add_portal_menu_links', 20);

function microdos_add_portal_menu_links($menu_items) {
    $guide_page = get_page_by_path('affiliate-dashboard-guide');
    $mg_page = get_page_by_path('marketing-guide');
    $easy_creatives = get_page_by_path('creatives-easy');

    $guide_url = $guide_page ? get_permalink($guide_page) : '';
    $mg_url = $mg_page ? get_permalink($mg_page) : '';
    $easy_url = $easy_creatives ? get_permalink($easy_creatives) : '';

    if ($guide_url) {
        $menu_items['dashboard_guide'] = array(
            'name' => 'Dashboard Guide',
            'url'  => $guide_url,
        );
    }
    if ($mg_url) {
        $menu_items['marketing_guide'] = array(
            'name' => 'Marketing Guide',
            'url'  => $mg_url,
        );
    }
    if ($easy_url) {
        $menu_items['quick_creatives'] = array(
            'name' => '📋 Quick Copy Creatives',
            'url'  => $easy_url,
        );
    }

    return $menu_items;
}

add_action('admin_notices', 'microdos_admin_portal_notice');

function microdos_admin_portal_notice() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'affiliate-wp_page_affiliate-wp-affiliates') return;
    if (!current_user_can('manage_options')) return;

    $guide_page = get_page_by_path('affiliate-dashboard-guide');
    $mg_page = get_page_by_path('marketing-guide');
    ?>
    <div class="notice notice-success is-dismissible" style="border-left-color: #44f80c;">
        <p>
            <strong>microDOS(2) Affiliate Portal Integration:</strong>
            Menu Links active: Dashboard Guide <?php echo $guide_page ? '&#10004;' : '&#10008;'; ?>,
            Marketing Guide <?php echo $mg_page ? '&#10004;' : '&#10008;'; ?>.
            <a href="<?php echo esc_url(wp_nonce_url(add_query_arg('microdos_reset_tour', '1'), 'microdos_reset_tour')); ?>" style="color: #d63638; margin-left: 12px;">Reset tour</a>
        </p>
    </div>
    <?php
}



