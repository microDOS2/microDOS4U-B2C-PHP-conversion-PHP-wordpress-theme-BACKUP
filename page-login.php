<?php
/**
 * Template Name: Login
 *
 * Login page — redirects affiliates to the affiliate area.
 *
 * @package microDOS4U
 */

// If already logged in, redirect appropriately
if (is_user_logged_in()) {
    // Affiliates go to the affiliate dashboard
    if (function_exists('affwp_is_affiliate') && affwp_is_affiliate()) {
        wp_redirect(get_permalink(get_page_by_path('affiliate-area')) ?: home_url('/'));
        exit;
    }
    // Everyone else goes to My Account
    $redirect_url = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('dashboard') : home_url('/');
    wp_redirect($redirect_url);
    exit;
}

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 500px;">

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Log In</h1>
            <p class="text-slate-400">Access your account to view orders and manage subscriptions.</p>
        </div>

        <!-- Login Form -->
        <div class="card p-8 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">

            <?php
            // Show any WooCommerce notices
            if (function_exists('wc_print_notices')) {
                wc_print_notices();
            }

            // WordPress login form
            $account_redirect = function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('dashboard') : home_url('/');
            wp_login_form([
                'redirect'       => $account_redirect,
                'form_id'        => 'customer-login',
                'label_username' => __('Username or Email Address', 'woocommerce'),
                'label_password' => __('Password', 'woocommerce'),
                'label_remember' => __('Remember me', 'woocommerce'),
                'label_log_in'   => __('Log In', 'woocommerce'),
                'remember'       => true,
            ]);
            ?>

            <!-- Lost Password Link -->
            <div class="text-center mt-6" style="border-top: 1px solid #1a1329; padding-top: 24px;">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="color: #38bdf8; font-size: 14px;">
                    Lost your password?
                </a>
            </div>

            <!-- Create Account Link -->
            <div class="text-center mt-4">
                <p class="text-slate-400" style="font-size: 14px;">
                    Don't have an account? 
                    <a href="/my-account/" style="color: #44f80c; font-weight: 600;">Create one at checkout</a>
                </p>
            </div>

        </div>

    </div>
</section>

<style>
    #customer-login label {
        display: block;
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: 500;
    }
    #customer-login input[type="text"],
    #customer-login input[type="password"] {
        width: 100%;
        padding: 12px 16px;
        background-color: #0a0514;
        border: 1px solid #1f2b47;
        border-radius: 8px;
        color: #fff;
        font-size: 16px;
        margin-bottom: 20px;
        box-sizing: border-box;
    }
    #customer-login input[type="text"]:focus,
    #customer-login input[type="password"]:focus {
        outline: none;
        border-color: #44f80c;
    }
    #customer-login input[type="checkbox"] {
        margin-right: 8px;
    }
    #customer-login .login-remember {
        margin-bottom: 20px;
        color: #94a3b8;
        font-size: 14px;
    }
    #customer-login input[type="submit"] {
        width: 100%;
        padding: 14px;
        background-color: #44f80c;
        color: #0a0514;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    #customer-login input[type="submit"]:hover {
        background-color: #3de00b;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(68, 248, 12, 0.3);
    }
</style>

<?php
get_footer();
