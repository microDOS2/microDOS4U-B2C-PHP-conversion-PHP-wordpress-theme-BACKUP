<?php
/**
 * Template Name: Thank You - Order Confirmation
 * 
 * Displayed after successful checkout.
 * 
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 800px;">

        <!-- Success Header -->
        <div class="text-center mb-12">
            <div style="font-size: 64px; margin-bottom: 16px;">✅</div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Thank You for Your Order!</h1>
            <p class="text-slate-400 text-lg">Your order has been received and is being processed.</p>
        </div>

        <!-- Order Details -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">Order Confirmation</h2>

            <div class="text-center mb-6">
                <p class="text-slate-400 mb-2">Order Number:</p>
                <p class="text-2xl font-bold" style="color: #44f80c;">#<?php echo isset($_GET['order']) ? esc_html(sanitize_text_field($_GET['order'])) : '—'; ?></p>
            </div>

            <div style="border-top: 1px solid #1a1329; padding-top: 24px; margin-top: 24px;">
                <p class="text-slate-400 text-center mb-4">
                    <strong style="color: #fff;">Important:</strong> You will receive an order confirmation email shortly. 
                    If you selected Cash on Delivery, please have your payment ready when your order arrives.
                </p>
            </div>

            <div style="border-top: 1px solid #1a1329; padding-top: 24px; margin-top: 24px;">
                <p class="text-slate-400 text-center">
                    <strong style="color: #fff;">Next Steps:</strong><br>
                    1. Check your email for order details<br>
                    2. Track your order in <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" style="color: #38bdf8;">My Account</a><br>
                    3. Your subscription will begin processing immediately
                </p>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold transition-all duration-300"
               style="background-color: #44f80c; color: #0a0514;">
                View My Account
            </a>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold transition-all duration-300"
               style="background-color: #150f24; color: #44f80c; border: 1px solid #44f80c;">
                Continue Shopping
            </a>
        </div>

    </div>
</section>

<?php
get_footer();
