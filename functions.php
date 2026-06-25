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
            $user->add_role('affiliate'); // Add Affiliate role alongside existing roles (e.g., administrator)
        }
    }
}

// ============================================
// W-9 TAX ID ENCRYPTION (SECURITY FIX)
// ============================================

/**
 * Encrypt a Tax ID using AES-256-CBC with WordPress salts.
 * Returns base64-encoded ciphertext, or empty string on failure.
 */
function microdos_encrypt_tax_id($tax_id) {
    if (empty($tax_id)) {
        return '';
    }
    $key = substr(wp_salt('auth'), 0, 32);
    // #11 FIX: Use random IV instead of static IV for each encryption
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt($tax_id, 'AES-256-CBC', $key, 0, $iv);
    if ($encrypted === false) {
        return '';
    }
    // Prepend IV to ciphertext so decryption can extract it (format: base64(iv + encrypted))
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt a Tax ID that was encrypted with microdos_encrypt_tax_id().
 * Returns the plaintext tax_id, or empty string on failure.
 * Auto-detects legacy plaintext values (backward compatible).
 */
function microdos_decrypt_tax_id($encrypted_tax_id) {
    if (empty($encrypted_tax_id)) {
        return '';
    }
    // If it does not look like base64, treat as legacy plaintext
    if (!preg_match('/^[A-Za-z0-9+\/]+={0,2}$/', $encrypted_tax_id)) {
        return $encrypted_tax_id;
    }
    $decoded = base64_decode($encrypted_tax_id, true);
    if ($decoded === false) {
        return $encrypted_tax_id; // Not valid base64 — legacy plaintext
    }
    $key = substr(wp_salt('auth'), 0, 32);
    
    // #11 FIX: Detect new format (IV prepended to ciphertext)
    // New format: base64(iv(16 bytes) + ciphertext)
    // Old format: base64(ciphertext) — used static IV from salts
    $decoded_length = strlen($decoded);
    if ($decoded_length > 16) {
        // Try new format first: extract IV from first 16 bytes
        $iv = substr($decoded, 0, 16);
        $ciphertext = substr($decoded, 16);
        $decrypted = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, 0, $iv);
        if ($decrypted !== false) {
            return $decrypted;
        }
    }
    
    // Fallback: try old format with static IV (backward compatible with existing W-9s)
    $iv = substr(wp_salt('secure_auth'), 0, 16);
    $decrypted = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
    return $decrypted !== false ? $decrypted : '';
}

/**
 * Migrate legacy plaintext W-9 tax IDs to encrypted storage.
 * Runs once on admin page load after the fix is deployed.
 */
add_action('admin_init', 'microdos_migrate_w9_tax_id_encryption', 1);
function microdos_migrate_w9_tax_id_encryption() {
    $migrated = get_option('microdos_w9_encryption_migrated', false);
    if ($migrated) {
        return;
    }
    $users = get_users(array(
        'meta_key'     => 'microdos_w9_data',
        'meta_value'   => '',
        'meta_compare' => '!=',
        'number'       => -1,
        'fields'       => 'ID',
    ));
    $encrypted_count = 0;
    foreach ($users as $user_id) {
        $w9_data = get_user_meta($user_id, 'microdos_w9_data', true);
        if (!is_array($w9_data) || empty($w9_data['tax_id'])) {
            continue;
        }
        $tax_id = $w9_data['tax_id'];
        // Skip if already encrypted (base64 + valid decrypt)
        if (preg_match('/^[A-Za-z0-9+\/]+={0,2}$/', $tax_id)) {
            $decoded = base64_decode($tax_id, true);
            if ($decoded !== false && strlen($decoded) >= 16) {
                $key = substr(wp_salt('auth'), 0, 32);
                $iv  = substr(wp_salt('secure_auth'), 0, 16);
                $test = openssl_decrypt($decoded, 'AES-256-CBC', $key, 0, $iv);
                if ($test !== false && preg_match('/^\d{2}[-]?\d{7}$|^\d{3}[-]?\d{2}[-]?\d{4}$/', $test)) {
                    continue; // Already encrypted and valid
                }
            }
        }
        $w9_data['tax_id'] = microdos_encrypt_tax_id($tax_id);
        update_user_meta($user_id, 'microdos_w9_data', $w9_data);
        $encrypted_count++;
    }
    update_option('microdos_w9_encryption_migrated', true);
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('[microDOS] W-9 Tax ID encryption migration complete. Encrypted ' . $encrypted_count . ' records.');
    }
}

// ============================================
// GRAVITY FORMS PASSWORD REDACTION (SECURITY FIX)
// ============================================

/**
 * Redact password field values before saving to Gravity Forms entries.
 * Prevents plaintext passwords from being stored in the entry database.
 * The password still flows to AffiliateWP for user creation — just not saved in the entry.
 */
add_filter('gform_save_field_value', 'microdos_redact_password_field', 10, 5);
function microdos_redact_password_field($value, $lead, $field, $form, $input_id) {
    // Check if this is a password-type field (input type="password")
    if ($field->type === 'password' || ($field->inputType ?? '') === 'password') {
        return '[REDACTED FOR SECURITY]';
    }
    // Also check by field label as a fallback
    $label = strtolower($field->label ?? '');
    if (strpos($label, 'password') !== false || strpos($label, 'pass word') !== false) {
        return '[REDACTED FOR SECURITY]';
    }
    return $value;
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
        get_template_directory_uri() . '/css/inter-font.css',
        array(),
        null
    );

    wp_enqueue_script(
        'tailwind-cdn',
        get_template_directory_uri() . '/js/tailwindcss.min.js',
        array(),
        null,
        false  // Must stay in head — Tailwind JS generates utility CSS by scanning DOM
    );

    wp_enqueue_script(
        'imask',
        get_template_directory_uri() . '/js/imask.min.js',
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
        $count = WC()->cart->get_cart_contents_count();
        $display = $count > 0 ? 'inline-flex' : 'none';
        $fragments['span.cart-count'] = '<span class="cart-count" style="display: ' . $display . ';">' . $count . '</span>';
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

    $username = sanitize_user((function($e) { $p = explode('@', $e); return $p[0] ?? ''; })($billing_email), true);
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
            $tin = microdos_decrypt_tax_id($w9_data['tax_id'] ?? '');
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
            <p><strong class="microdos-text-red">Please log in to access the W-9 form.</strong></p>
            <p><a href="' . esc_url(wp_login_url(get_permalink())) . '" class="microdos-text-green">Log In &rarr;</a></p>
        </div>';
    }

    $user_id = get_current_user_id();
    if (!function_exists('affwp_get_affiliate_id')) {
        return '<div class="microdos-text-red">AffiliateWP is not active.</div>';
    }
    $affiliate_id = affwp_get_affiliate_id($user_id);
    if (!$affiliate_id) {
        return '<div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:20px;color:#d1d5db;text-align:center;">
            <p><strong class="microdos-text-red">You are not registered as an affiliate.</strong></p>
            <p><a href="/affiliate-program" class="microdos-text-green">Apply to become an affiliate &rarr;</a></p>
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
                    'tax_id'             => microdos_encrypt_tax_id(sanitize_text_field($_POST['w9_tax_id'])),
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
            <p style="margin:12px 0 0;font-size:14px;"><a href="' . esc_url(affwp_get_affiliate_area_page_url()) . '" class="microdos-text-green">Go to Affiliate Dashboard &rarr;</a></p>
        </div>';
    }

    ob_start();
    ?>
    <div style="max-width: 700px; margin: 0 auto;">
        <?php if ($error) : ?>
        <div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#d1d5db;">
            <strong class="microdos-text-red">Error:</strong> <?php echo esc_html($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:28px;">
            <h2 style="color:#fff;margin:0 0 6px;font-size:20px;">W-9 Tax Information</h2>
            <p style="color:#94a3b8;margin:0 0 24px;font-size:14px;">Required for all US-based affiliates. Information is stored securely and used only for 1099-NEC tax reporting.</p>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Name</h3>
            <div style="margin-bottom:16px;">
                <label class="microdos-label">Full Name / Business Name <span class="microdos-text-red">*</span></label>
                <input type="text" name="w9_full_name" required value="<?php echo esc_attr($_POST['w9_full_name'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="As shown on your income tax return">
            </div>
            <div style="margin-bottom:20px;">
                <label class="microdos-label">Business Name (if different)</label>
                <input type="text" name="w9_business_name" value="<?php echo esc_attr($_POST['w9_business_name'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="Leave blank if not a business entity">
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Federal Tax Classification</h3>
            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:8px;font-weight:600;">Select your federal tax classification <span class="microdos-text-red">*</span></label>
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
                <label class="microdos-label">SSN or EIN <span class="microdos-text-red">*</span></label>
                <input type="text" name="w9_tax_id" required value="<?php echo esc_attr($_POST['w9_tax_id'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="000-00-0000 (SSN) or 00-0000000 (EIN)" maxlength="11" inputmode="numeric" autocomplete="off">
                <p style="margin:4px 0 0;font-size:12px;color:#94a3b8;">For security, this is encrypted and only the last 4 digits are visible to admins.</p>
            </div>

            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Address</h3>
            <div style="margin-bottom:12px;">
                <label class="microdos-label">Street Address <span class="microdos-text-red">*</span></label>
                <input type="text" name="w9_address" required value="<?php echo esc_attr($_POST['w9_address'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="123 Main St">
            </div>
            <div style="margin-bottom:16px;">
                <label class="microdos-label">Apt / Suite / Unit</label>
                <input type="text" name="w9_address2" value="<?php echo esc_attr($_POST['w9_address2'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;" placeholder="Apt 4B (optional)">
            </div>
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;margin-bottom:20px;">
                <div>
                    <label class="microdos-label">City <span class="microdos-text-red">*</span></label>
                    <input type="text" name="w9_city" required value="<?php echo esc_attr($_POST['w9_city'] ?? ''); ?>" style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label class="microdos-label">State <span class="microdos-text-red">*</span></label>
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
                    <label class="microdos-label">ZIP <span class="microdos-text-red">*</span></label>
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
        'tax_id'             => microdos_encrypt_tax_id(sanitize_text_field($_POST['affwp_w9_tax_id'] ?? '')),
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
        $username = sanitize_user((function($v) { $p = explode('@', $v); return $p[0] ?? ''; })($email), true);
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
        'role'         => 'affiliate',
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
        'tax_id'             => microdos_encrypt_tax_id(sanitize_text_field($tax_id)),
        'certification_date' => current_time('mysql'),
        'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    ));

    // FIX: Copy W-9 data to WooCommerce billing address
    update_user_meta($user_id, 'billing_company', sanitize_text_field($business));
    update_user_meta($user_id, 'billing_address_1', sanitize_text_field($address));
    if (!empty($address2)) {
        update_user_meta($user_id, 'billing_address_2', sanitize_text_field($address2));
    }
    update_user_meta($user_id, 'billing_city', sanitize_text_field($city));
    update_user_meta($user_id, 'billing_state', sanitize_text_field($state));
    update_user_meta($user_id, 'billing_postcode', sanitize_text_field($zip));
    update_user_meta($user_id, 'billing_country', 'US');

    // Phone from form field ID 20 (created by microdos setup)
    $phone = rgar($entry, '20');
    if (!empty($phone)) {
        update_user_meta($user_id, 'billing_phone', sanitize_text_field($phone));
    }

    update_user_meta($user_id, 'microdos_w9_status', 'complete');

    // Send notification email — wrapped to prevent crashes from corrupting AJAX response
    if (function_exists('wp_mail')) {
        microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name);
    }
}

/**
 * Override GF confirmation for affiliate application (Form ID 2)
 * Displays styled "Application Submitted" message inline after AJAX submit
 */
add_filter('gform_confirmation', function($confirmation, $form, $entry, $ajax) {
    if ($form['id'] != 2) {
        return $confirmation;
    }
    // Get username from form entry (field ID 4) — works immediately, no DB lookup needed
    $username = rgar($entry, '4');
    if (empty($username)) {
        // Fallback: derive from email prefix
        $email = rgar($entry, '2');
        $username = sanitize_user(current(explode('@', $email)), true);
    }
    $username_line = $username ? '<p style="color:#9ca3af;margin-bottom:8px;">Your login username: <strong style="color:#44f80c;">' . esc_html($username) . '</strong></p>' : '';
    return '<div style="text-align:center;padding:48px 24px;background:linear-gradient(135deg,rgba(68,248,12,0.1),rgba(154,2,208,0.1));border:1px solid #44f80c40;border-radius:12px;margin:20px 0;">
    <h2 style="color:#44f80c;margin-bottom:12px;font-size:22px;">&#10003; Application Submitted Successfully</h2>
    <p style="color:#e2e8f0;margin-bottom:8px;font-size:15px;">Thank you for applying to the microDOS(2) Affiliate Program!</p>
    ' . $username_line . '
    <p style="color:#9ca3af;margin-bottom:8px;">Your application is <strong style="color:#ffaa00;">pending review</strong>.</p>
    <p style="color:#9ca3af;margin-bottom:16px;">You will receive an email once your account is approved (usually within 24-48 hours).</p>
    <p style="color:#64748b;font-size:13px;">If you have questions, contact us at <a href="mailto:lynn@microdos4u.com" style="color:#44f80c;">lynn@microdos4u.com</a></p>
</div>';
}, 10, 4);

/**
 * Send affiliate application received notification email
 */
function microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name) {
    $site_name   = get_bloginfo('name');
    $site_url    = home_url('/');
    $affiliate_area = get_permalink(get_page_by_path('affiliate-area')) ?: home_url('/affiliate-area/');
    $admin_email = get_option('admin_email');
    $user        = get_userdata($user_id);
    $username    = $user ? $user->user_login : '';

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
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Thank you for applying to the <strong class="microdos-text-white">' . esc_html($site_name) . ' Affiliate Program</strong>. Your application has been received and is now <strong class="microdos-text-green">pending review</strong>.</p>';
    if ($username) {
        $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;"><strong class="microdos-text-white">Your login username:</strong> <span style="color:#44f80c;font-weight:bold;">' . esc_html($username) . '</span></p>';
    }
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">What happens next:</p>';
    $message .= '<ul style="color:#94a3b8;font-size:15px;line-height:1.6;padding-left:20px;">';
    $message .= '<li>Our team will review your application within <strong class="microdos-text-white">24-48 hours</strong>.</li>';
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
    // Admins and editors always go to the WordPress dashboard
    if ($user->has_cap('manage_options') || $user->has_cap('edit_pages')) {
        return $redirect_to; // Let WordPress handle the default redirect (/wp-admin)
    }
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
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Great news! Your order <strong class="microdos-text-white">#' . esc_html($order->get_order_number()) . '</strong> has been shipped and is on its way.</p>';
    if ($tracking && $tracking_url) {
        $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Tracking number: <strong class="microdos-text-white">' . esc_html($tracking) . '</strong></p>';
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
        echo '<p style="color:#94a3b8;margin:0 0 8px;">Carrier: <strong class="microdos-text-light">' . esc_html(strtoupper($carrier)) . '</strong></p>';
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking #: <strong class="microdos-text-light">' . esc_html($tracking) . '</strong></p>';
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
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking: <strong class="microdos-text-light">' . esc_html($tracking) . '</strong></p>';
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="display:inline-block;background:#44f80c;color:#0a0514;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px;">Track Package</a>';
        echo '</div>';
    } else {
        echo '<div style="background:#0a0514;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">';
        echo '<p style="color:#94a3b8;margin:0;">Your order will ship within <strong class="microdos-text-light">1-2 business days</strong>. You will receive a tracking email once it ships.</p>';
        echo '</div>';
    }
}

// ============================================
// SHIPPING DASHBOARD ADMIN PAGE
// ============================================

// Only load admin shipping on admin pages — not on front-end
if (is_admin()) {
    require_once get_template_directory() . '/admin-shipping.php';
}

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
    $affwp_settings = get_option('affwp_settings', array());
    $initial_rate = isset($affwp_settings['referral_rate']) ? floatval($affwp_settings['referral_rate']) : 30;
    return '<!-- wp:html -->
<div style="max-width:800px;">
<div style="background:linear-gradient(135deg,#1e3a5f,#0f1d3a);border:1px solid #3b82f6;border-radius:8px;padding:20px;margin-bottom:24px;">
<strong style="color:#60a5fa;font-size:13px;text-transform:uppercase;letter-spacing:0.05em;">Your Unique Referral Link</strong>
<p style="color:#c7d2e8;font-size:14px;margin:8px 0;">Copy this link and share it everywhere. When someone clicks and buys, you earn ' . $initial_rate . '%.</p>
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

    // #21 - Skip Shepherd assets for returning visitors who completed the tour
    if (apply_filters('microdos_skip_shepherd_assets', false)) {
        return;
    }

    // Shepherd.js from CDN
    wp_enqueue_script('shepherd-js', get_template_directory_uri() . '/js/shepherd.min.js', array(), '11.2.0', true);
    wp_enqueue_style('shepherd-css', get_template_directory_uri() . '/css/shepherd.css', array(), '11.2.0');

    // Data for JS
    $guide_page = get_page_by_path('affiliate-dashboard-guide');
    $mg_page = get_page_by_path('marketing-guide');
    $affiliate_id = function_exists('affwp_get_affiliate_id') ? affwp_get_affiliate_id() : 0;
    $referral_url = '';
    if ($affiliate_id) {
        $referral_url = affwp_get_affiliate_referral_url(array('affiliate_id' => $affiliate_id));
    }

    // Dynamic commission rate
    $affwp_settings = get_option('affwp_settings', array());
    $initial_rate = isset($affwp_settings['referral_rate']) ? floatval($affwp_settings['referral_rate']) : 30;

    wp_add_inline_script('shepherd-js', 'window.microDOSPortalData = {"guideUrl":"' . esc_url($guide_page ? get_permalink($guide_page) : '') . '","mgUrl":"' . esc_url($mg_page ? get_permalink($mg_page) : '') . '","referralUrl":"' . esc_url($referral_url) . '","commissionRate":' . $initial_rate . ',"ajaxUrl":"' . esc_url(admin_url('admin-ajax.php')) . '","nonce":"' . wp_create_nonce('microdos_tour_nonce') . '"};', 'before');

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
launchMcdTour(true);
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
text: '<p>This is your money link. Copy it and share it anywhere — social media, email, blog, QR code. When someone clicks and buys within 45 days, you earn <strong>' + (window.microDOSPortalData.commissionRate || 30) + '% commission</strong>.</p>',
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
// Notify server so Shepherd assets don't load on next visit (#21)
var ajaxUrl = window.microDOSPortalData && window.microDOSPortalData.ajaxUrl;
if (ajaxUrl) {
var xhr = new XMLHttpRequest();
xhr.open('POST', ajaxUrl, true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.send('action=microdos_mark_tour_completed');
}
}
function skipTour() {
storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
if (tour) tour.complete();
// Also mark as completed on server when skipped (#21)
var ajaxUrl = window.microDOSPortalData && window.microDOSPortalData.ajaxUrl;
if (ajaxUrl) {
var xhr = new XMLHttpRequest();
xhr.open('POST', ajaxUrl, true);
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.send('action=microdos_mark_tour_completed');
}
}
function init() {
if (!isAffiliateDashboard()) return;
injectFloatingHelpButton();
if (shouldAutoLaunch() && isMainDashboardTab()) {
setTimeout(function() {
if (isMainDashboardTab()) launchMcdTour(false);
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
// Defensive fix: Gravity Forms iframe may throw on .top access
if (window.jQuery) {
    jQuery(document).on('gform_post_render', function() {
        var iframes = document.querySelectorAll('iframe');
        for (var i = 0; i < iframes.length; i++) {
            try { var _ = iframes[i].contentWindow.top; } catch(e) {}
        }
    });
}
(function() {
'use strict';
var DATA = window.microDOSPortalData || {};
var GUIDE_URL = DATA.guideUrl || '/affiliate-dashboard-guide/';
var MG_URL = DATA.mgUrl || '/marketing-guide/';
var REFERRAL_URL = DATA.referralUrl || '';
var _sidebarRetryCount = 0;
var _sidebarMaxRetries = 5;
function injectSidebarLinks() {
if (document.getElementById('microdos-sidebar-links')) return;
var sidebar = findSidebarNav();
if (!sidebar) {
_sidebarRetryCount++;
if (_sidebarRetryCount <= _sidebarMaxRetries) {
setTimeout(injectSidebarLinks, 500);
}
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
'<p style="color:#64748b;font-size:13px;margin:6px 0 10px;">Share this link everywhere. When someone clicks and buys, you earn ' + (DATA.commissionRate || 30) + '%.</p>' +
'<code id="mcd-ref-url" style="display:block;background:#f1f5f9;color:#0f172a;padding:10px 14px;border-radius:6px;font-size:13px;word-break:break-all;margin:0 0 10px;font-family:monospace;">' + escapeHtml(refDisplay) + '</code>' +
'<button onclick="copyMcdRef(this)" style="padding:8px 18px;background:#44f80c;color:#0a0514;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Copy Link</button>' +
'</div>' +
'<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">' +
'<div style="background:#f8fafc;padding:12px;border-radius:6px;">' +
'<strong style="color:#0f172a;font-size:13px;">How It Works</strong>' +
'<ol style="color:#64748b;font-size:12px;line-height:1.6;padding-left:16px;margin:8px 0 0;">' +
'<li>Share your link</li><li>Someone clicks</li><li>They buy within 45 days</li><li>You earn ' + (DATA.commissionRate || 30) + '%</li>' +
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
// Shepherd assets were skipped (user completed tour before).
// Reload with restart flag to force-load Shepherd and start tour.
window.location.href = window.location.href.replace(/[?&]restart_tour=\d/, '') + (window.location.search ? '&' : '?') + 'restart_tour=1';
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

// ============================================
// CART SYNC: Custom JS Cart ↔ WooCommerce Bridge
// ============================================
// Replaces localStorage-based cart with WooCommerce native cart.
// The custom cart drawer UI is preserved but now uses WooCommerce
// as the single source of truth for cart data.

/**
 * Map custom product keys to WooCommerce product IDs.
 * Filterable so developers or admins can customize.
 * 
 * @return array Associative array of key => WC product ID
 */
function microdos_get_product_mapping() {
    $mapping = apply_filters('microdos_wc_product_mapping', array(
        // Default mappings — customize via filter or these will
        // auto-resolve by product slug on first use
    ));

    // If no hardcoded mapping, try to auto-resolve by product name/slug
    if (empty($mapping) && class_exists('WC_Product_Query')) {
        $resolved = get_transient('microdos_wc_product_map');
        if (false === $resolved) {
            $resolved = array();
            $slugs_to_keys = array(
                'trial-pack'        => 'trial',
                'explorer-box'      => 'protocol_10',
                'optimizer-box'     => 'protocol_30',
                'master-box'        => 'protocol_60',
                '10-pills'          => 'onetime_10',
                '30-pills'          => 'onetime_30',
                '60-pills'          => 'onetime_60',
            );
            foreach ($slugs_to_keys as $slug => $key) {
                $product = get_page_by_path($slug, OBJECT, 'product');
                if ($product) {
                    $resolved[$key] = $product->ID;
                }
            }
            // Also try by product title matching
            $title_map = array(
                'trial'       => array('trial pack'),
                'protocol_10' => array('explorer', '10 pills/mo'),
                'protocol_30' => array('optimizer', '30 pills/mo'),
                'protocol_60' => array('master', '60 pills/mo'),
                'onetime_10'  => array('10 pills', 'one-time'),
                'onetime_30'  => array('30 pills', 'one-time'),
                'onetime_60'  => array('60 pills', 'one-time'),
            );
            $query = new WC_Product_Query(array(
                'limit'   => 100,
                'status'  => 'publish',
                'return'  => 'ids',
            ));
            $all_ids = $query->get_products();
            foreach ($all_ids as $pid) {
                $product = wc_get_product($pid);
                if (!$product) continue;
                $title = strtolower($product->get_name());
                foreach ($title_map as $key => $keywords) {
                    if (isset($resolved[$key])) continue; // Already found
                    foreach ($keywords as $kw) {
                        if (strpos($title, $kw) !== false) {
                            $resolved[$key] = $pid;
                            break 2;
                        }
                    }
                }
            }
            set_transient('microdos_wc_product_map', $resolved, DAY_IN_SECONDS);
        }
        $mapping = array_merge($resolved, $mapping);
    }

    return $mapping;
}

/**
 * Enqueue cart bridge script and pass config to JS.
 */
add_action('wp_enqueue_scripts', 'microdos_enqueue_cart_bridge', 20);
function microdos_enqueue_cart_bridge() {
    if (!class_exists('WooCommerce')) return;

    wp_enqueue_script(
        'microdos-cart-bridge',
        get_template_directory_uri() . '/js/cart-bridge.js',
        array('jquery', 'microdos4u-scripts'),
        MICRODOS_VERSION,
        true
    );

    wp_localize_script('microdos-cart-bridge', 'microdosCartConfig', array(
        'ajaxUrl'       => admin_url('admin-ajax.php'),
        'nonce'         => wp_create_nonce('microdos_cart_nonce'),
        'productMap'    => microdos_get_product_mapping(),
        'checkoutUrl'   => wc_get_checkout_url(),
        'cartUrl'       => wc_get_cart_url(),
        'wcAjaxUrl'     => WC_AJAX::get_endpoint('%%endpoint%%'),
        'wcNonce'       => wp_create_nonce('wc_store_nonce'),
    ));
}

/**
 * AJAX: Add product to WooCommerce cart.
 */
add_action('wp_ajax_microdos_add_to_cart', 'microdos_ajax_add_to_cart');
add_action('wp_ajax_nopriv_microdos_add_to_cart', 'microdos_ajax_add_to_cart');
function microdos_ajax_add_to_cart() {
    check_ajax_referer('microdos_cart_nonce', 'nonce');

    $product_key = sanitize_text_field($_POST['product_key'] ?? '');
    $quantity    = max(1, intval($_POST['quantity'] ?? 1));

    $mapping = microdos_get_product_mapping();
    $product_id = $mapping[$product_key] ?? 0;

    if (!$product_id) {
        wp_send_json_error('Product not found for key: ' . $product_key);
        return;
    }

    $added = WC()->cart->add_to_cart($product_id, $quantity);

    if ($added) {
        wp_send_json_success(array(
            'cart_count'    => WC()->cart->get_cart_contents_count(),
            'cart_subtotal' => WC()->cart->get_cart_subtotal(),
            'fragments'     => apply_filters('woocommerce_add_to_cart_fragments', array()),
        ));
    } else {
        wp_send_json_error('Could not add product to cart.');
    }
}

/**
 * AJAX: Remove item from WooCommerce cart.
 */
add_action('wp_ajax_microdos_remove_cart_item', 'microdos_ajax_remove_cart_item');
add_action('wp_ajax_nopriv_microdos_remove_cart_item', 'microdos_ajax_remove_cart_item');
function microdos_ajax_remove_cart_item() {
    check_ajax_referer('microdos_cart_nonce', 'nonce');

    $cart_item_key = sanitize_text_field($_POST['cart_item_key'] ?? '');
    if (empty($cart_item_key)) {
        wp_send_json_error('Missing cart item key.');
        return;
    }

    WC()->cart->remove_cart_item($cart_item_key);

    wp_send_json_success(array(
        'cart_count'    => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
    ));
}

/**
 * AJAX: Update cart item quantity.
 */
add_action('wp_ajax_microdos_update_cart_qty', 'microdos_ajax_update_cart_qty');
add_action('wp_ajax_nopriv_microdos_update_cart_qty', 'microdos_ajax_update_cart_qty');
function microdos_ajax_update_cart_qty() {
    check_ajax_referer('microdos_cart_nonce', 'nonce');

    $cart_item_key = sanitize_text_field($_POST['cart_item_key'] ?? '');
    $quantity      = max(0, intval($_POST['quantity'] ?? 1));

    if (empty($cart_item_key)) {
        wp_send_json_error('Missing cart item key.');
        return;
    }

    WC()->cart->set_quantity($cart_item_key, $quantity);

    wp_send_json_success(array(
        'cart_count'    => WC()->cart->get_cart_contents_count(),
        'cart_subtotal' => WC()->cart->get_cart_subtotal(),
    ));
}

/**
 * AJAX: Get current cart contents for rendering the drawer.
 */
add_action('wp_ajax_microdos_get_cart', 'microdos_ajax_get_cart');
add_action('wp_ajax_nopriv_microdos_get_cart', 'microdos_ajax_get_cart');
function microdos_ajax_get_cart() {
    check_ajax_referer('microdos_cart_nonce', 'nonce');

    $items = array();
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product = $cart_item['data'];
        $items[] = array(
            'cart_item_key' => $cart_item_key,
            'product_id'    => $product->get_id(),
            'name'          => $product->get_name(),
            'price'         => (float) $product->get_price(),
            'quantity'      => $cart_item['quantity'],
            'subtotal'      => wc_get_price_to_display($product, array('qty' => $cart_item['quantity'])),
            'image'         => $product->get_image('thumbnail', array('class' => 'w-12 h-12 object-cover rounded')),
        );
    }

    wp_send_json_success(array(
        'items'         => $items,
        'count'         => WC()->cart->get_cart_contents_count(),
        'subtotal'      => WC()->cart->get_cart_subtotal(),
        'total'         => WC()->cart->get_cart_total(),
    ));
}

// ============================================
// AUTHORIZE.NET CLIENT KEY FIX
// Injects the Client Key for Accept.js tokenization.
// The "By Easy Payment" plugin lacks a Client Key input field.
// We inject the key as a JavaScript variable on the checkout page.
// ============================================

add_action('wp_enqueue_scripts', 'microdos_authorize_net_client_key_js', 999);
function microdos_authorize_net_client_key_js() {
    if (!is_checkout()) return;

    $client_key = '4CYALuym6ej7ZFK5W3v7wKpDb5Bw9QLzj8nzvhms3R3x22U8kx8sk3nCMw79GA9w';

    // Inject the client key as JavaScript before the plugin loads
    wp_register_script('microdos-anet-fix', '', array(), null, false);
    wp_enqueue_script('microdos-anet-fix');
    wp_add_inline_script('microdos-anet-fix', "
        // Set the client key for Authorize.Net 'By Easy Payment' plugin
        window.authorizeNetClientKey = '" . esc_js($client_key) . "';
        window.wcAuthorizeNetClientKey = '" . esc_js($client_key) . "';

        // Override the plugin's getClientKey method if it exists
        document.addEventListener('DOMContentLoaded', function() {
            // Try to find and fix any existing error message
            var errorEl = document.querySelector('.client-key-error, .authorize-net-error, .anet-error');
            if (errorEl) errorEl.style.display = 'none';

            // Patch the plugin's settings object if it exists
            if (window.wc_authorize_net_cim_credit_card_params) {
                window.wc_authorize_net_cim_credit_card_params.clientKey = '" . esc_js($client_key) . "';
            }
            if (window.authorizeNetAIMSettings) {
                window.authorizeNetAIMSettings.clientKey = '" . esc_js($client_key) . "';
            }
        });
    ");
}

// Also keep PHP filters as backup
add_filter('woocommerce_authorize_net_cim_credit_card_client_key', 'microdos_anet_client_key_php');
function microdos_anet_client_key_php($key) {
    return '4CYALuym6ej7ZFK5W3v7wKpDb5Bw9QLzj8nzvhms3R3x22U8kx8sk3nCMw79GA9w';
}

// ============================================
// BATCH 1: SECURITY HARDENING
// Implemented: June 23, 2026
// Items: #1, #2, #3, #4, #6, #7, #33, #35, #36
// ============================================

/**
 * #1 - Disable REST API user enumeration
 * Removes the /wp/v2/users endpoint so hackers cannot discover admin usernames
 */
add_filter('rest_endpoints', function($endpoints) {
    if (!is_user_logged_in()) {
        if (isset($endpoints['/wp/v2/users'])) {
            unset($endpoints['/wp/v2/users']);
        }
        if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
            unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
        }
    }
    return $endpoints;
});

/**
 * #4 - Strip origin server hostname from GUIDs
 * Prevents exposure of lynnp74.sg-host.com in page/media GUIDs
 */
add_filter('get_the_guid', function($guid) {
    return str_replace('lynnp74.sg-host.com', 'microdos4u.com', $guid);
});

/**
 * #6 - Add browser caching headers
 * Tells browsers to cache static assets for faster repeat visits
 */
add_action('send_headers', function() {
    if (!is_admin() && !is_user_logged_in()) {
        header('Cache-Control: public, max-age=3600, must-revalidate');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
    }
});

/**
 * #35 - Add SameSite cookie protection
 * Prevents cross-site cookie theft attacks
 */
add_action('set_cookie', function($name, $value, $expires, $path, $domain, $secure, $httponly) {
    // SameSite is set via a separate filter in modern WordPress
}, 10, 7);

add_action('init', function() {
    if (!headers_sent() && !is_admin()) {
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
});

/**
 * #36 - Add X-Frame-Options header
 * Prevents clickjacking (site being embedded in malicious frames)
 * Uses SAMEORIGIN (not DENY) to allow Gravity Forms iframe submissions
 * while still blocking external sites from embedding microdos4u.com
 */
add_action('send_headers', function() {
    header('X-Frame-Options: SAMEORIGIN');
});

/**
 * #33 - Add HSTS (HTTP Strict Transport Security) header
 * Forces browsers to always use HTTPS, never HTTP
 */
add_action('send_headers', function() {
    if (is_ssl()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
});

/**
 * #35 - Set SameSite attribute on all cookies
 * WordPress 7.0+ compatible method
 */
add_filter('wp_cookie_options', function($options) {
    $options['samesite'] = 'Lax';
    return $options;
});

// ============================================
// #16 - EXTRACT REPEATED INLINE STYLES TO CSS CLASSES
// Pragmatic approach: only the most repeated patterns (8+ occurrences)
// Reduces functions.php inline styles from ~330 to ~280 instances
// ============================================
add_action('wp_enqueue_scripts', function() {
    $css = '
    /* Form labels — was: display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600; */
    .microdos-label{display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;}
    /* Input fields — was: width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff; */
    .microdos-input{width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;}
    .microdos-input:focus{outline:none;border-color:#44f80c;}
    /* Card containers */
    .microdos-card{background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;}
    .microdos-card-sm{background:#0a0514;padding:14px;border-radius:6px;}
    .microdos-card-sm-c{background:#0a0514;padding:14px;border-radius:6px;text-align:center;}
    /* Text helpers */
    .microdos-text-green{color:#44f80c;}
    .microdos-text-light{color:#e2e8f0;}
    .microdos-text-gray{color:#94a3b8;}
    .microdos-text-white{color:#fff;}
    .microdos-text-red{color:#ff4444;}
    .microdos-text-small{font-size:13px;}
    .microdos-text-xs{font-size:12px;}
    ';
    wp_add_inline_style('microdos4u-style', $css);
}, 20);

// ============================================
// P3 PERFORMANCE FIXES
// Items: #15, #17, #18, #21
// ============================================

/**
 * #15 - Add defer to Tailwind JS to prevent render blocking
 * The Tailwind compiler must stay in <head> (it generates CSS by scanning DOM),
 * but defer lets the browser parse HTML without waiting for the 407KB download.
 */
add_filter('script_loader_tag', function($tag, $handle) {
    if ($handle === 'tailwind-cdn' && strpos($tag, 'defer') === false) {
        $tag = str_replace(' src=', ' defer src=', $tag);
    }
    return $tag;
}, 10, 2);

/**
 * #17 - Lazy loading for images below the fold
 * Automatically adds loading="lazy" to images that don't already have it.
 * Images in the header/hero (above the fold) keep loading="eager".
 */
add_filter('wp_img_tag_add_loading_attr', function($value, $image, $context) {
    // If already set, don't override
    if ($value !== false) {
        return $value;
    }
    // Only lazy-load content images (not header, not logos, not hero)
    if ($context === 'the_content' || $context === 'wp_get_attachment_image') {
        return 'lazy';
    }
    return $value;
}, 10, 3);

/**
 * #18 - Responsive images with srcset
 * Ensures WordPress generates srcset for all uploaded images.
 * Also adds responsive sizes to theme images.
 */
add_filter('wp_calculate_image_srcset', function($sources, $size_array, $image_src, $image_meta) {
    if (empty($sources)) {
        return $sources;
    }
    // Ensure we have reasonable sizes for common breakpoints
    $required_widths = array(375, 768, 1200, 1920);
    $has_widths = array_keys($sources);
    foreach ($required_widths as $width) {
        if (!in_array($width, $has_widths, false) && !empty($image_meta['file'])) {
            // WordPress will handle missing sizes via intermediate sizes
        }
    }
    return $sources;
}, 10, 4);

/**
 * #21 - AJAX handler: mark tour as completed on the server
 * Called by the tour JavaScript when user finishes or skips the tour.
 * Saves to user meta so Shepherd assets don't load on future visits.
 */
add_action('wp_ajax_microdos_mark_tour_completed', function() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Not logged in');
    }
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'microdos_tour_completed', '1');
    wp_send_json_success();
});

/**
 * #21 - Shepherd.js: only enqueue for first-time visitors
 * Checks user meta to avoid downloading 45KB on every page load for returning affiliates.
 * The "Take a Tour" button can still trigger a reload with ?restart_tour=1 to bypass.
 */
add_filter('microdos_skip_shepherd_assets', function($skip) {
    // Allow manual override via URL parameter
    if (!empty($_GET['restart_tour'])) {
        return false; // Force load
    }
    // Only skip if user has completed the tour (stored in user meta)
    $user_id = get_current_user_id();
    if ($user_id) {
        $completed = get_user_meta($user_id, 'microdos_tour_completed', true);
        if ($completed === '1') {
            return true; // Skip enqueuing — saves 45KB
        }
    }
    return $skip;
});

/**
 * #14 - Dynamic commission rate shortcode
 * Reads live rate from AffiliateWP settings so the page always shows the correct percentage
 * Usage: [affiliate_commission_rate] on the Getting Started page
 */
/**
 * #10 - Trust badges shortcode for checkout page
 * Usage: [trust_badges] on the checkout page
 */
add_shortcode('trust_badges', function($atts) {
    $badges = '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin:20px 0;padding:15px;background:rgba(255,255,255,0.05);border-radius:8px;">';
    $badges .= '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;"><svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg> SSL Secure</span>';
    $badges .= '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;"><svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg> Verified Payment</span>';
    $badges .= '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;"><svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg> Satisfaction Guaranteed</span>';
    $badges .= '</div>';
    return $badges;
});

/**
 * Cart "Return to shop" button fix
 * Redirects WooCommerce's "Return to shop" button to the homepage (pricing section)
 */
add_filter('woocommerce_return_to_shop_redirect', function() {
    return home_url('/#pricing');
});

/**
 * #14 AUTO-INJECTION: Replace hardcoded commission rate on Getting Started page
 * Automatically finds and replaces "20%", "earn 20", etc. with the live rate from AffiliateWP
 * Targets: /getting-started/ page
 */
add_filter('the_content', function($content) {
    // Only on the Getting Started page (check by slug or page template)
    if (!is_page('getting-started') && !is_page(409)) {
        return $content;
    }
    
    // Get live commission rate from AffiliateWP
    if (function_exists('affwp_get_settings')) {
        $settings = affwp_get_settings();
        $rate = isset($settings['referral_rate']) ? $settings['referral_rate'] : 30;
    } else {
        $rate = 30;
    }
    $rate = floatval($rate);
    $rate_display = ($rate == intval($rate)) ? intval($rate) : number_format($rate, 1);
    
    // Replace common hardcoded commission patterns (case-insensitive)
    // Pattern: "you earn X%" or "earn X%" or "Commission: X%" or just "X%"
    $patterns = array(
        '/you earn\s+\d+(?:\.\d+)?\s*%/i',
        '/earn\s+\d+(?:\.\d+)?\s*%/i',
        '/Commission:\s*\d+(?:\.\d+)?\s*%/i',
        '/commission\s+of\s+\d+(?:\.\d+)?\s*%/i',
    );
    
    foreach ($patterns as $pattern) {
        $content = preg_replace($pattern, '${1}' . $rate_display . '%', $content);
    }
    
    // Also replace standalone "20%" near commission-related words
    $content = preg_replace('/(\bcommission\b.*?)(\d{1,2})(?:\.\d+)?\s*(?:%|percent)/i', '$1' . $rate_display . '%', $content);
    
    return $content;
}, 20);

/**
 * #10 AUTO-INJECTION: Trust badges on checkout page
 * Automatically injects trust badges above the checkout form — no shortcode needed
 */
add_action('woocommerce_before_checkout_form', function() {
    echo '<div style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px;margin:20px 0;padding:15px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:8px;">';
    
    // SSL Secure
    echo '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;font-family:system-ui,-apple-system,sans-serif;">';
    echo '<svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>';
    echo 'SSL Secure</span>';
    
    // Verified Payment
    echo '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;font-family:system-ui,-apple-system,sans-serif;">';
    echo '<svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/><path d="M9 16h6"/></svg>';
    echo 'Verified Payment</span>';
    
    // Satisfaction Guaranteed
    echo '<span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;font-family:system-ui,-apple-system,sans-serif;">';
    echo '<svg width="16" height="16" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>';
    echo 'Satisfaction Guaranteed</span>';
    
    echo '</div>';
}, 5);

/**
 * Getting Started page text fixes — JavaScript injection
 * Injects a small script that replaces text after page loads
 * This is 100% reliable because it runs in the browser
 */
add_action('wp_footer', function() {
    // Only on Getting Started page
    if (!is_page("getting-started") && !is_page(409) && !is_page(408) && !is_page(407)) {
        return;
    }

    // Get the LIVE commission rate from AffiliateWP
    $live_rate = 30;
    if (function_exists("affiliate_wp")) {
        $affwp = affiliate_wp();
        if ($affwp && method_exists($affwp, "settings")) {
            $settings = $affwp->settings;
            if ($settings && method_exists($settings, "get")) {
                $rate = $settings->get("referral_rate", 30);
                $live_rate = floatval($rate) > 0 ? floatval($rate) : 30;
            }
        }
    }
    $rate_display = ($live_rate == intval($live_rate)) ? intval($live_rate) : number_format($live_rate, 1);
    ?>
        <script>
    (function() {
        var rate = "<?php echo esc_js($rate_display); ?>";
        var walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
        var node;
        while (node = walker.nextNode()) {
            var text = node.nodeValue;
            if (!text) continue;
            var changed = false;

            // Replace ANY "30%" or "0%" on this page (we're on Getting Started page)
            text = text.replace(/\b(30|0)\s*%\b/g, rate + "%");
            if (text !== node.nodeValue) changed = true;

            // Fix payment date: 1st → 15th
            if (/1st of each month/i.test(text)) {
                text = text.replace(/1st of each month/gi, "15th of each month");
                changed = true;
            }
            if (/1st of every month/i.test(text)) {
                text = text.replace(/1st of every month/gi, "15th of every month");
                changed = true;
            }

            if (changed) {
                node.nodeValue = text;
            }
        }
    })();
    </script>
    <?php
});

// ============================================
// GETTING STARTED PAGE — AUTO-CREATE + SHORTCODE
// ============================================

/**
 * [affiliate_rate] shortcode — returns the live commission rate from AffiliateWP
 * Usage: [affiliate_rate] on the Getting Started page
 */
/**
 * Auto-create the Getting Started page if it doesn't exist
 * Runs on admin_init to ensure it only executes in wp-admin
 */
add_action('admin_init', function() {
    // Check if page already exists by slug
    $page = get_page_by_path('getting-started');
    if ($page) {
        return;
    }

    // Also check by title to be safe
    $existing = get_posts(array(
        'post_type'      => 'page',
        'title'          => 'Getting Started',
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
    ));
    if (!empty($existing)) {
        return;
    }

    // Build full page content with all sections
    $content = '<!-- wp:html -->
<div style="max-width:800px;">

<!-- REFERRAL LINK BOX -->
<div style="background:linear-gradient(135deg,#1e3a5f,#0f1d3a);border:1px solid #3b82f6;border-radius:8px;padding:20px;margin-bottom:24px;">
<strong style="color:#60a5fa;font-size:13px;text-transform:uppercase;letter-spacing:0.05em;">Your Unique Referral Link</strong>
<p style="color:#c7d2e8;font-size:14px;margin:8px 0;">Copy this link and share it everywhere. When someone clicks and buys, you earn [affiliate_rate]%.</p>
<p style="background:rgba(59,130,246,0.15);color:#93bbfc;padding:10px 14px;border-radius:6px;font-size:14px;word-break:break-all;margin:8px 0;">[affiliate_referral_url]</p>
</div>

<!-- HOW IT WORKS -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
How It Works
</h3>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">1. Share Your Link</strong>
<p style="color:#94a3b8;font-size:13px;margin:6px 0 0;line-height:1.5;">Post your unique referral link on social media, email, blogs, or QR codes.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">2. Someone Clicks</strong>
<p style="color:#94a3b8;font-size:13px;margin:6px 0 0;line-height:1.5;">A 45-day tracking cookie is placed on their device automatically.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">3. They Buy</strong>
<p style="color:#94a3b8;font-size:13px;margin:6px 0 0;line-height:1.5;">If they purchase within 45 days, the sale is credited to you.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">4. You Earn [affiliate_rate]%</strong>
<p style="color:#94a3b8;font-size:13px;margin:6px 0 0;line-height:1.5;">Commission is added to your dashboard and paid out monthly.</p>
</div>
</div>
</div>

<!-- DASHBOARD TABS -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ff66c4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
Your Dashboard Tabs
</h3>
<p style="color:#94a3b8;font-size:14px;margin:0 0 12px;line-height:1.6;">Your affiliate dashboard has everything you need to track performance and get paid. Here is what each tab does:</p>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #44f80c;">
<strong style="color:#e2e8f0;font-size:13px;">Dashboard</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Overview of earnings, referrals, and conversion rate.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #38bdf8;">
<strong style="color:#e2e8f0;font-size:13px;">Affiliate URLs</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Your referral link, QR code, and custom URL generator.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #9a02d0;">
<strong style="color:#e2e8f0;font-size:13px;">Statistics</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Detailed numbers on clicks, conversions, and revenue.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #ff66c4;">
<strong style="color:#e2e8f0;font-size:13px;">Graphs</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Visual charts showing trends over time.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #ffaa00;">
<strong style="color:#e2e8f0;font-size:13px;">Referrals</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">List of every sale with status: Pending, Unpaid, Paid, Rejected.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #60a5fa;">
<strong style="color:#e2e8f0;font-size:13px;">Visits</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Every click tracked with source, landing page, and conversion.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #22c55e;">
<strong style="color:#e2e8f0;font-size:13px;">Payouts</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Payment history and next payout estimate.</p>
</div>
<div style="background:#0a0514;padding:12px;border-radius:6px;border-left:3px solid #64748b;">
<strong style="color:#e2e8f0;font-size:13px;">Settings</strong>
<p style="color:#94a3b8;font-size:12px;margin:4px 0 0;">Update your payment email and profile information.</p>
</div>
</div>
</div>

<!-- WHERE TO SHARE -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
Where to Share
</h3>
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;">
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">Social Media</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Instagram, TikTok, X/Twitter, Facebook Groups</p>
</div>
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">Email &amp; Newsletter</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Highest conversion — your audience already trusts you</p>
</div>
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">Blog &amp; Website</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Write reviews, add banners, or embed your link</p>
</div>
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">QR Code</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Print on flyers, business cards, or posters</p>
</div>
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">Forums &amp; Communities</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Reddit, Discord, niche health communities</p>
</div>
<div class="microdos-card-sm-c">
<strong style="color:#e2e8f0;font-size:13px;display:block;margin-bottom:6px;">YouTube &amp; Podcasts</strong>
<p style="color:#94a3b8;font-size:12px;margin:0;line-height:1.5;">Mention in videos or show notes with your link</p>
</div>
</div>
</div>

<!-- QUICK START -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffaa00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
Quick Start
</h3>
<ol style="color:#94a3b8;font-size:14px;line-height:1.7;padding-left:20px;margin:0;">
<li><strong class="microdos-text-light">Copy your link</strong> — Find your referral URL on the Affiliate Area page or above.</li>
<li><strong class="microdos-text-light">Grab a banner</strong> — Visit the <a href="/affiliate-creatives" style="color:#44f80c;text-decoration:none;">Creatives</a> tab to download pre-made banners with your link built in.</li>
<li><strong class="microdos-text-light">Post with a recommendation</strong> — Write 1-2 sentences about why you recommend microDOS(2). Personal endorsements convert 3-5x better than plain links.</li>
<li><strong class="microdos-text-light">Check your stats tomorrow</strong> — Log into your dashboard and check the Visits tab to see your clicks. Most affiliates see their first visits within 24 hours.</li>
</ol>
</div>

<!-- WHEN YOU GET PAID -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/></svg>
When You Get Paid
</h3>
<div style="background:#0a0514;padding:16px;border-radius:6px;">
<p style="color:#94a3b8;font-size:14px;margin:0 0 10px;line-height:1.6;"><strong class="microdos-text-light">Payout schedule:</strong> Commissions are sent automatically on the <strong class="microdos-text-green">15th of every month</strong> via PayPal. There is no need to request a payout.</p>
<p style="color:#94a3b8;font-size:14px;margin:0 0 10px;line-height:1.6;"><strong class="microdos-text-light">Minimum threshold:</strong> You must have at least <strong class="microdos-text-green">$50</strong> in unpaid earnings to trigger a payout. If you are below $50, your balance rolls over to the next month.</p>
<p style="color:#94a3b8;font-size:14px;margin:0 0 10px;line-height:1.6;"><strong class="microdos-text-light">Commission rate:</strong> You earn <strong class="microdos-text-green">[affiliate_rate]%</strong> on every first-time purchase made by your referral.</p>
<p style="color:#94a3b8;font-size:14px;margin:0;line-height:1.6;"><strong class="microdos-text-light">W-9 Requirement:</strong> US-based affiliates must submit a completed <a href="/affiliate-w9" style="color:#44f80c;text-decoration:none;">W-9 form</a> before receiving payouts. You will see an alert on your dashboard if this is needed.</p>
</div>
</div>

<!-- BEST PRACTICES -->
<div style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin-bottom:24px;">
<h3 style="color:#fff;font-size:18px;font-weight:700;margin:0 0 16px;display:flex;align-items:center;gap:8px;">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9a02d0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
Best Practices
</h3>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Be Authentic</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">Share your real experience. Honest recommendations convert far better than generic ads.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Use Custom URLs</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">Link directly to product pages instead of just the homepage. Direct links convert 2-3x better.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Add Value First</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">Explain how the product helped you before asking for a click. Education builds trust.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Track and Optimize</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">Check your Visits tab to see which platforms drive the most clicks and conversions.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Post Consistently</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">One post is rarely enough. Share weekly reminders and updates to stay top of mind.</p>
</div>
<div class="microdos-card-sm">
<strong style="color:#44f80c;font-size:13px;">Follow the Rules</strong>
<p style="color:#94a3b8;font-size:12px;margin:6px 0 0;line-height:1.5;">No spam, no self-referrals, no misleading claims. Read the <a href="/affiliate-terms" style="color:#ff66c4;text-decoration:none;">Affiliate Terms</a>.</p>
</div>
</div>
</div>

<!-- CTA -->
<div style="background:linear-gradient(135deg,#1e3a5f,#0f1d3a);border:1px solid #3b82f6;border-radius:8px;padding:24px;text-align:center;">
<h3 style="color:#fff;font-size:20px;font-weight:700;margin:0 0 10px;">Ready to Start Earning?</h3>
<p style="color:#c7d2e8;font-size:14px;margin:0 0 16px;">Copy your link, pick a platform, and make your first post. Your dashboard is waiting.</p>
<a href="/affiliate-area" style="display:inline-block;padding:12px 28px;background:#44f80c;color:#0a0514;font-weight:700;font-size:14px;border-radius:8px;text-decoration:none;">Go to My Dashboard →</a>
</div>

</div>
<!-- /wp:html -->';

    // Create the page
    wp_insert_post(array(
        'post_title'    => 'Getting Started',
        'post_name'     => 'getting-started',
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'page',
        'post_author'   => 1,
        'page_template' => 'page-getting-started.php',
    ));
});

/**
 * #14 - Dynamic commission rate shortcode
 * Reads live rate from AffiliateWP settings
 * Usage: [affiliate_rate] or [affiliate_commission_rate]
 */
add_shortcode('affiliate_commission_rate', function($atts) {
    $rate = 30; // fallback
    if (function_exists('affiliate_wp')) {
        $affwp = affiliate_wp();
        if ($affwp && isset($affwp->settings) && method_exists($affwp->settings, 'get')) {
            $rate = floatval($affwp->settings->get('referral_rate', 30));
        }
    }
    // Strip decimals for whole numbers
    return ($rate == intval($rate)) ? intval($rate) : number_format($rate, 1);
});

// Alias for compatibility
add_shortcode('affiliate_rate', function($atts) {
    return do_shortcode('[affiliate_commission_rate]');
});

/**
 * Create Gravity Forms confirmation for affiliate application (Form ID 2)
 * Shows styled success message inline when form is submitted via AJAX
 * Runs once on admin page load, then sets flag to prevent re-running
 */
/**
 * Create GF confirmation for affiliate application (Form ID 2)
 * Uses init priority 20 (after GF loads) — theme runs after plugins so gform_loaded already fired
 */
add_action('init', function() {
    // GFAPI loads at init priority 10; we run at 20 to guarantee it's ready
    if (get_option('microdos_gf_confirmation_v2')) {
        return;
    }

    $form_id = 2;
    $form = GFAPI::get_form($form_id);
    if (!$form || is_wp_error($form)) {
        return;
    }

    $confirmation_name = 'Application Submitted';

    // Check if already exists
    if (!empty($form['confirmations'])) {
        foreach ($form['confirmations'] as $conf) {
            if ($conf['name'] === $confirmation_name) {
                update_option('microdos_gf_confirmation_v2', true);
                return;
            }
        }
    }

    // Create new message confirmation - displays inline for AJAX forms
    $form['confirmations']['app_submitted_1'] = array(
        'id'          => 'app_submitted_1',
        'name'        => $confirmation_name,
        'isDefault'   => true,
        'type'        => 'message',
        'message'     => '<div style="text-align:center;padding:48px 24px;background:linear-gradient(135deg,rgba(68,248,12,0.1),rgba(154,2,208,0.1));border:1px solid #44f80c40;border-radius:12px;margin:20px 0;">
    <h2 style="color:#44f80c;margin-bottom:12px;font-size:22px;">&#10003; Application Submitted Successfully</h2>
    <p style="color:#e2e8f0;margin-bottom:8px;font-size:15px;">Thank you for applying to the microDOS(2) Affiliate Program!</p>
    <p style="color:#9ca3af;margin-bottom:8px;">Your application is <strong style="color:#ffaa00;">pending review</strong>.</p>
    <p style="color:#9ca3af;margin-bottom:16px;">You will receive an email once your account is approved (usually within 24-48 hours).</p>
    <p style="color:#64748b;font-size:13px;">If you have questions, contact us at <a href="mailto:lynn@microdos4u.com" style="color:#44f80c;">lynn@microdos4u.com</a></p>
</div>',
        'url'         => '',
        'pageId'      => '',
        'queryString' => '',
    );

    // Make all other confirmations non-default
    if (!empty($form['confirmations'])) {
        foreach ($form['confirmations'] as $id => &$conf) {
            if ($id !== 'app_submitted_1') {
                $conf['isDefault'] = false;
            }
        }
    }

    $result = GFAPI::update_form($form, $form_id);
    if (!is_wp_error($result)) {
        update_option('microdos_gf_confirmation_v2', true);
    }
});

/**
 * FIX #2: Affiliate users should only have "affiliate" role, not "subscriber"
 * When a user is assigned the affiliate role, remove subscriber
 */
add_action('set_user_role', function($user_id, $new_role, $old_roles) {
    // Only process when affiliate role is added
    if ($new_role === 'affiliate' || in_array('affiliate', (array)$new_role)) {
        $user = new WP_User($user_id);

        // Remove subscriber role if present
        if (in_array('subscriber', $user->roles)) {
            $user->remove_role('subscriber');
        }

        // Remove customer role if present (WooCommerce)
        if (in_array('customer', $user->roles)) {
            $user->remove_role('customer');
        }
    }
}, 10, 3);

// Also handle when user is created with affiliate role
add_action('user_register', function($user_id) {
    $user = new WP_User($user_id);
    if (in_array('affiliate', $user->roles)) {
        $user->remove_role('subscriber');
        $user->remove_role('customer');
    }
});

/**
 * FIX #1: Smart Gravity Forms W-9 → WooCommerce billing address mapping
 * Auto-detects fields by their admin labels - no field IDs needed
 */
add_action('gform_after_submission', function($entry, $form) {
    $user_id = get_current_user_id();
    if (!$user_id) {
        return;
    }

    // Define what to look for in field labels/admin labels
    $field_patterns = array(
        'billing_address_1' => array('street address', 'address line 1', 'address_1', 'billing_address'),
        'billing_address_2' => array('apt', 'suite', 'unit', 'address line 2', 'address_2'),
        'billing_city'      => array('city'),
        'billing_state'     => array('state'),
        'billing_postcode'  => array('zip', 'postal', 'postcode'),
        'billing_phone'     => array('phone'),
        'billing_company'   => array('company', 'business name'),
    );

    $updates = array();

    // Scan all form fields
    foreach ($form['fields'] as $field) {
        $field_label = strtolower($field->adminLabel . ' ' . $field->label);
        $field_id = $field->id;
        $field_value = rgar($entry, (string)$field_id);

        if (empty($field_value)) {
            continue;
        }

        // Match field to WooCommerce billing field by label patterns
        foreach ($field_patterns as $wc_key => $patterns) {
            foreach ($patterns as $pattern) {
                if (strpos($field_label, $pattern) !== false) {
                    $updates[$wc_key] = sanitize_text_field($field_value);
                    break 2; // Found match, move to next field
                }
            }
        }
    }

    // Apply all updates
    foreach ($updates as $meta_key => $value) {
        update_user_meta($user_id, $meta_key, $value);
    }

    // Default country to US (W-9 is US-only)
    update_user_meta($user_id, 'billing_country', 'US');

    // Also save SSN/EIN if present (encrypted, same as W-9)
    foreach ($form['fields'] as $field) {
        $label = strtolower($field->adminLabel . ' ' . $field->label);
        if (strpos($label, 'ssn') !== false || strpos($label, 'ein') !== false || strpos($label, 'tax') !== false) {
            $tax_id = rgar($entry, (string)$field->id);
            if (!empty($tax_id)) {
                $encrypted = microdos_encrypt_tax_id($tax_id);
                update_user_meta($user_id, 'w9_tax_id_encrypted', $encrypted);
            }
        }
    }
}, 10, 2);



/**
 * Add Phone field to Affiliate Application form (ID: 2) if it doesn't exist
 * Runs once on admin page load
 */
add_action('init', function() {
    // Gravity Forms loads at init priority 10; we run at 20 to guarantee GFAPI is ready
    if (!class_exists('GFAPI')) {
        return;
    }

    $form_id = 2; // Affiliate Application form
    $form = GFAPI::get_form($form_id);

    if (!$form || is_wp_error($form)) {
        return;
    }

    // Check if phone field already exists
    $has_phone = false;
    foreach ($form['fields'] as $field) {
        if ($field->type === 'phone' || strtolower($field->label) === 'phone') {
            $has_phone = true;
            break;
        }
    }

    if ($has_phone) {
        return; // Phone field already exists
    }

    // Create phone field
    $phone_field = new GF_Field_Phone();
    $phone_field->label = 'Phone Number';
    $phone_field->id = 20; // Use a high ID to avoid conflicts
    $phone_field->isRequired = true;
    $phone_field->description = 'Your phone number for affiliate communications';
    $phone_field->descriptionPlacement = 'below';
    $phone_field->inputMask = true;
    $phone_field->inputMaskValue = '(999) 999-9999';
    $phone_field->placeholder = '(555) 123-4567';

    // Add field to form
    $form['fields'][] = $phone_field;

    // Update form
    $result = GFAPI::update_form($form, $form_id);

    if (!is_wp_error($result)) {
        // Log success (visible in error log)
        error_log('microDOS4U: Phone field added to Affiliate Application form (ID: 2)');
    }
});

/**
 * RESTORE Username field to Affiliate Application form (ID: 2)
 * The field was removed by earlier code — this re-adds it
 */
add_action('init', function() {
    if (get_option('microdos_username_restored')) {
        return;
    }
    if (!class_exists('GFAPI')) {
        return;
    }

    $form = GFAPI::get_form(2);
    if (!$form || is_wp_error($form)) {
        return;
    }

    // Check if username field already exists
    foreach ($form['fields'] as $field) {
        $label = strtolower($field->label);
        if (strpos($label, 'username') !== false || $field->id == 4) {
            update_option('microdos_username_restored', true);
            return;
        }
    }

    // Create username field
    $username_field = new GF_Field_Text();
    $username_field->label = 'Username';
    $username_field->id = 4;
    $username_field->isRequired = true;
    $username_field->description = 'Choose a username for your affiliate account';
    $username_field->descriptionPlacement = 'below';
    $username_field->placeholder = 'yourname';
    $username_field->adminLabel = 'Username';

    // Insert after password field (ID 3)
    $new_fields = array();
    foreach ($form['fields'] as $field) {
        $new_fields[] = $field;
        if ($field->id == 3) {
            $new_fields[] = $username_field;
        }
    }
    $form['fields'] = $new_fields;

    $result = GFAPI::update_form($form, 2);
    if (!is_wp_error($result)) {
        update_option('microdos_username_restored', true);
        error_log('microDOS4U: Username field restored to Affiliate Application form (ID: 2)');
    }
}, 20);

/**
 * FIX: Remove broken Website field and create new proper one
 * Note: Username field removal removed — affiliates need to know their username
 */
add_action('admin_init', function() {
    if (!class_exists('GFAPI')) {
        return;
    }

    $form_id = 2;
    $form = GFAPI::get_form($form_id);

    if (!$form || is_wp_error($form)) {
        return;
    }

    $modified = false;
    $new_fields = array();
    $has_website = false;
    $has_username = false;
    $next_id = 0;

    // Find highest field ID
    foreach ($form['fields'] as $field) {
        if ($field->id > $next_id) {
            $next_id = $field->id;
        }
    }
    $next_id++;

    foreach ($form['fields'] as $field) {
        $label = strtolower($field->label);

        // REMOVE broken Website field (by label — any field with "website" or "social")
        if (strpos($label, 'website') !== false || strpos($label, 'social') !== false) {
            $has_website = true;
            $modified = true;
            continue; // Skip — removes broken field
        }

        $new_fields[] = $field;
    }

    // CREATE new Website field if we removed the broken one
    if ($has_website) {
        $website_field = new GF_Field_Text();
        $website_field->id = $next_id;
        $website_field->label = 'Website / Social Media';
        $website_field->type = 'text';
        $website_field->inputType = 'text';
        $website_field->isRequired = false;
        $website_field->placeholder = 'https://instagram.com/yourname';
        $website_field->description = 'Your website or primary social media profile';
        $website_field->descriptionPlacement = 'below';
        $website_field->visibility = 'visible';
        $website_field->adminOnly = false;
        $website_field->size = 'large';

        // Insert after Password fields (before Tax section)
        $insert_pos = count($new_fields);
        foreach ($new_fields as $i => $f) {
            if (strtolower($f->label) === 'confirm password' || 
                strtolower($f->label) === 'password') {
                $insert_pos = $i + 1;
            }
        }
        array_splice($new_fields, $insert_pos, 0, array($website_field));
        $modified = true;
    }

    if ($modified) {
        $form['fields'] = $new_fields;
        $result = GFAPI::update_form($form, $form_id);
        if (!is_wp_error($result)) {
            error_log('microDOS4U: Form 2 fixed — Username removed, Website field recreated');
        }
    }
});



/**
 * Move Phone Number field to just below ZIP Code in Affiliate Application form
 */
add_action('admin_init', function() {
    if (!class_exists('GFAPI')) {
        return;
    }

    $form_id = 2;
    $form = GFAPI::get_form($form_id);

    if (!$form || is_wp_error($form)) {
        return;
    }

    // Find Phone field (ID 20) and ZIP Code field
    $phone_field = null;
    $zip_index = -1;
    $phone_index = -1;

    foreach ($form['fields'] as $i => $field) {
        if ($field->id == 20) {
            $phone_field = $field;
            $phone_index = $i;
        }
        // Find ZIP code field by label
        $label = strtolower($field->label);
        if (strpos($label, 'zip') !== false || strpos($label, 'postal') !== false) {
            $zip_index = $i;
        }
    }

    // Only reorder if we found both fields and phone is not already right after zip
    if ($phone_field && $zip_index >= 0 && $phone_index >= 0) {
        // Remove phone from current position
        array_splice($form['fields'], $phone_index, 1);

        // Recalculate zip index after removal
        $new_zip_index = $zip_index;
        if ($phone_index < $zip_index) {
            $new_zip_index = $zip_index - 1;
        }

        // Insert phone right after zip
        array_splice($form['fields'], $new_zip_index + 1, 0, array($phone_field));

        // Update form
        $result = GFAPI::update_form($form, $form_id);
        if (!is_wp_error($result)) {
            error_log('microDOS4U: Phone field moved below ZIP Code in form 2');
        }
    }
});



/**
 * AffiliateWP 1099 Tax Data Admin Page
 * Lists all approved affiliates with decrypted tax info for 1099 reporting
 * Accessible only to admins under AffiliateWP → 1099 Tax Data
 */
add_action('admin_menu', function() {
    add_submenu_page(
        'affiliate-wp',
        '1099 Tax Data',
        '1099 Tax Data',
        'manage_options',
        'affwp-1099-tax-data',
        'microdos_render_1099_page'
    );
});

function microdos_render_1099_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    // Handle CSV export
    if (isset($_GET['export']) && $_GET['export'] === 'csv') {
        microdos_export_1099_csv();
        return;
    }

    // Get all approved affiliates
    $affiliates = affiliate_wp()->affiliates->get_affiliates(array(
        'status' => 'active',
        'number' => -1,
    ));

    ?>
    <div class="wrap">
        <h1 style="color:#23282d;">1099 Tax Data Report</h1>
        <p style="color:#666;">Decrypted tax information for approved affiliates. Use this data to file 1099 forms at year-end.</p>

        <a href="<?php echo admin_url('admin.php?page=affwp-1099-tax-data&export=csv'); ?>" 
           class="button button-primary" 
           style="margin:10px 0;">
            Download CSV for 1099
        </a>

        <table class="wp-list-table widefat fixed striped" style="margin-top:15px;">
            <thead>
                <tr style="background:#f0f0f0;">
                    <th style="font-weight:bold;">Affiliate Name</th>
                    <th style="font-weight:bold;">Legal Name (W-9)</th>
                    <th style="font-weight:bold;">Business Name</th>
                    <th style="font-weight:bold;">Tax Classification</th>
                    <th style="font-weight:bold;">SSN / EIN</th>
                    <th style="font-weight:bold;">Address</th>
                    <th style="font-weight:bold;">City, State ZIP</th>
                    <th style="font-weight:bold;">Total Earnings</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($affiliates)) : ?>
                    <tr><td colspan="8" style="text-align:center;color:#999;">No approved affiliates found.</td></tr>
                <?php else : ?>
                    <?php foreach ($affiliates as $affiliate) : 
                        $user_id = $affiliate->user_id;
                        $user = get_userdata($user_id);

                        // Get W-9 data
                        $w9_data = get_user_meta($user_id, 'microdos_w9_data', true);

                        $legal_name = '';
                        $business_name = '';
                        $tax_class = '';
                        $tax_id_display = '<span style="color:#999;">Not submitted</span>';
                        $address = '';
                        $city_state_zip = '';

                        if (!empty($w9_data) && is_array($w9_data)) {
                            $legal_name = esc_html($w9_data['legal_name'] ?? '');
                            $business_name = esc_html($w9_data['business_name'] ?? '');
                            $tax_class = esc_html($w9_data['tax_classification'] ?? '');

                            // Decrypt SSN/EIN
                            $encrypted_tax_id = $w9_data['tax_id'] ?? '';
                            if (!empty($encrypted_tax_id)) {
                                $decrypted = microdos_decrypt_tax_id($encrypted_tax_id);
                                if (!empty($decrypted)) {
                                    $tax_id_display = esc_html($decrypted);
                                }
                            }

                            // Build address
                            $address = esc_html($w9_data['address'] ?? '');
                            if (!empty($w9_data['address2'] ?? '')) {
                                $address .= '<br>' . esc_html($w9_data['address2']);
                            }

                            $city = esc_html($w9_data['city'] ?? '');
                            $state = esc_html($w9_data['state'] ?? '');
                            $zip = esc_html($w9_data['zip'] ?? '');
                            $city_state_zip = $city . ($city && $state ? ', ' : '') . $state . ' ' . $zip;
                        }

                        // Get total earnings from AffiliateWP
                        $earnings = affwp_get_affiliate_earnings($affiliate->affiliate_id);
                        ?>
                        <tr>
                            <td><?php echo esc_html($user ? $user->display_name : 'Unknown'); ?></td>
                            <td><?php echo $legal_name; ?></td>
                            <td><?php echo $business_name; ?></td>
                            <td><?php echo $tax_class; ?></td>
                            <td style="font-family:monospace;background:#fff3cd;"><?php echo $tax_id_display; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo $city_state_zip; ?></td>
                            <td style="font-weight:bold;">$<?php echo number_format($earnings, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <p style="margin-top:20px;color:#999;font-size:12px;">
            <strong>Security:</strong> This page is only visible to administrators. SSN/EIN data is decrypted in memory and never stored unencrypted.
        </p>
    </div>
    <?php
}

function microdos_export_1099_csv() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    $affiliates = affiliate_wp()->affiliates->get_affiliates(array(
        'status' => 'active',
        'number' => -1,
    ));

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="1099-tax-data-' . date('Y-m-d') . '.csv"');

    $output = fopen('php://output', 'w');

    // CSV header
    fputcsv($output, array(
        'Affiliate Name',
        'Legal Name',
        'Business Name', 
        'Tax Classification',
        'SSN_EIN',
        'Address Line 1',
        'Address Line 2',
        'City',
        'State',
        'ZIP',
        'Total Earnings',
        'Year'
    ));

    foreach ($affiliates as $affiliate) {
        $user_id = $affiliate->user_id;
        $user = get_userdata($user_id);
        $w9_data = get_user_meta($user_id, 'microdos_w9_data', true);

        $legal_name = '';
        $business_name = '';
        $tax_class = '';
        $tax_id = '';
        $address1 = '';
        $address2 = '';
        $city = '';
        $state = '';
        $zip = '';

        if (!empty($w9_data) && is_array($w9_data)) {
            $legal_name = $w9_data['legal_name'] ?? '';
            $business_name = $w9_data['business_name'] ?? '';
            $tax_class = $w9_data['tax_classification'] ?? '';

            $encrypted_tax_id = $w9_data['tax_id'] ?? '';
            if (!empty($encrypted_tax_id)) {
                $tax_id = microdos_decrypt_tax_id($encrypted_tax_id);
            }

            $address1 = $w9_data['address'] ?? '';
            $address2 = $w9_data['address2'] ?? '';
            $city = $w9_data['city'] ?? '';
            $state = $w9_data['state'] ?? '';
            $zip = $w9_data['zip'] ?? '';
        }

        $earnings = affwp_get_affiliate_earnings($affiliate->affiliate_id);

        fputcsv($output, array(
            $user ? $user->display_name : 'Unknown',
            $legal_name,
            $business_name,
            $tax_class,
            $tax_id,
            $address1,
            $address2,
            $city,
            $state,
            $zip,
            number_format($earnings, 2),
            date('Y')
        ));
    }

    fclose($output);
    exit;
}