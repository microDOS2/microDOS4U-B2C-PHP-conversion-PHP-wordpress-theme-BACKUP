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
    define('MICRODOS_VERSION', '1.1.0');
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
add_action('after_setup_theme', 'microdos4u_setup');

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

// Cart fragments for AJAX cart count
function microdos4u_cart_fragments($fragments) {
    if (function_exists('WC') && WC()->cart) {
        $fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    }
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'microdos4u_cart_fragments');

// Loop columns
function microdos4u_loop_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'microdos4u_loop_columns', 20);

// Products per page
function microdos4u_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'microdos4u_products_per_page', 20);

// Disable WooCommerce default CSS (we'll style everything)
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Add "Add to Cart" AJAX class to buttons on homepage
function microdos4u_woo_ajax_add_to_cart() {
    if (!class_exists('WooCommerce')) return;
    wp_enqueue_script('wc-add-to-cart');
}
add_action('wp_enqueue_scripts', 'microdos4u_woo_ajax_add_to_cart');

// ============================================
// WOOCOMMERCE CHECKOUT PAGE SETUP
// ============================================

// Force checkout page to use full-width template (no sidebar)
function microdos4u_checkout_page_template($template) {
    if (is_checkout() || is_cart()) {
        // Remove sidebar on cart/checkout
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
