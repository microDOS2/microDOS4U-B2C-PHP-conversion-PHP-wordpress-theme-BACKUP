<?php
/**
 * Template Name: Affiliate Area
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 960px;">

        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Affiliate Program</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Share <span style="color: #44f80c;">micro</span><span style="color: #9a02d0;">DOS</span><span style="color: #ff66c4;">(2)</span> with your network and earn commissions on every sale you refer.</p>
        </div>

        <!-- How It Works -->
        <div class="grid md:grid-cols-3 gap-6 mb-16">
            <div class="card text-center p-6 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                <div class="w-12 h-12 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #44f80c, #9a02d0);">1</div>
                <h3 class="text-white font-bold text-lg mb-2">Sign Up</h3>
                <p class="text-slate-400 text-sm">Register as an affiliate and get your unique referral link.</p>
            </div>
            <div class="card text-center p-6 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                <div class="w-12 h-12 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #44f80c, #9a02d0);">2</div>
                <h3 class="text-white font-bold text-lg mb-2">Share</h3>
                <p class="text-slate-400 text-sm">Share your link on social media, blogs, email, or QR code.</p>
            </div>
            <div class="card text-center p-6 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                <div class="w-12 h-12 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #44f80c, #9a02d0);">3</div>
                <h3 class="text-white font-bold text-lg mb-2">Earn</h3>
                <p class="text-slate-400 text-sm">Earn commission on every sale made through your link.</p>
            </div>
        </div>

        <!-- Commission Info -->
        <div class="card p-8 rounded-lg mb-12" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white text-center mb-6">Commission Structure</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="text-center p-4 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-sm mb-1">Commission Rate</p>
                    <p class="text-3xl font-bold" style="color: #44f80c;">20%</p>
                    <p class="text-slate-400 text-xs mt-1">per sale</p>
                </div>
                <div class="text-center p-4 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-sm mb-1">Cookie Duration</p>
                    <p class="text-3xl font-bold" style="color: #44f80c;">60 Days</p>
                    <p class="text-slate-400 text-xs mt-1">referral tracking</p>
                </div>
            </div>
        </div>

        <!-- AffiliateWP Shortcode Area -->
        <div class="card p-8 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <?php
            if (function_exists('affiliate_wp')) :
                if (affwp_is_affiliate()) :
                    // Logged in affiliate - show dashboard
                    echo do_shortcode('[affiliate_dashboard]');
                elseif (is_user_logged_in()) :
                    // User logged in but not an affiliate
            ?>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-4">Become an Affiliate</h2>
                    <p class="text-slate-400 mb-6">You are logged in. Click below to register as an affiliate.</p>
                    <?php echo do_shortcode('[affiliate_registration]'); ?>
                </div>
            <?php
                else :
                    // Not logged in
            ?>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-4">Get Started</h2>
                    <p class="text-slate-400 mb-6">Log in or register to become an affiliate.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="px-6 py-3 rounded-lg font-semibold text-black" style="background: linear-gradient(135deg, #44f80c, #9a02d0);">Log In</a>
                        <a href="<?php echo esc_url(wp_registration_url()); ?>" class="px-6 py-3 rounded-lg font-semibold text-white border border-slate-600 hover:border-purple-500 transition">Create Account</a>
                    </div>
                    <div class="mt-8 pt-6" style="border-top: 1px solid #1f2b47;">
                        <p class="text-slate-400 text-sm mb-4">Already have an affiliate account?</p>
                        <?php echo do_shortcode('[affiliate_login]'); ?>
                    </div>
                </div>
            <?php
                endif;
            else :
                // AffiliateWP not active
            ?>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-white mb-4">Coming Soon</h2>
                    <p class="text-slate-400">Our affiliate program will be launching shortly. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- FAQ -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Affiliate FAQ</h2>
            <div class="space-y-4">
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">How do I get paid?</summary>
                    <div class="px-4 pb-4 text-slate-400">Commissions are paid monthly via your preferred payment method once you reach the minimum payout threshold.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">How long does the referral cookie last?</summary>
                    <div class="px-4 pb-4 text-slate-400">When someone clicks your referral link, a 60-day cookie is placed on their browser. If they purchase within 60 days, you get the commission.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">Can I promote on social media?</summary>
                    <div class="px-4 pb-4 text-slate-400">Yes! You can share your referral link on any platform — Instagram, TikTok, Twitter, Facebook, blogs, email newsletters, and more.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">Is there a minimum payout?</summary>
                    <div class="px-4 pb-4 text-slate-400">Yes. You must earn at least $50 in commissions before a payout is issued. Unpaid commissions roll over to the next month.</div>
                </details>
            </div>
        </div>

    </div>
</section>

<?php
get_footer();
