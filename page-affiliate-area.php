<?php
/**
 * Template Name: Affiliate Area - Custom
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="affiliate-hero py-16" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Affiliate Program</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Partner with microDOS(2) and earn commissions on every referral. Share your unique link or QR code and get paid for every purchase and subscription renewal.</p>
        </div>
    </section>

    <section class="affiliate-content py-12" style="background-color: #150f24;">
        <div class="container mx-auto px-4 max-w-4xl">

                        <!-- AFFILIATE LOGIN -->
            <?php if (!is_user_logged_in()) : ?>
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <div class="max-w-md mx-auto">
                    <h2 class="text-xl font-bold text-white mb-4">Affiliate Login</h2>
                    <p class="text-slate-400 text-sm mb-4">Already an affiliate? Log in to access your dashboard.</p>

                    <?php
                    if (function_exists('affiliate_wp')) {
                        $affwp_redirect = get_permalink(affiliate_wp()->settings->get('affiliates_page'));
                        echo affiliate_wp()->login->login_form($affwp_redirect);
                    } else {
                        wp_login_form([
                            'redirect'       => get_permalink(),
                            'form_id'        => 'affiliate-login',
                            'label_username' => __('Username or Email', 'microdos4u'),
                            'label_password' => __('Password', 'microdos4u'),
                            'label_remember' => __('Remember me', 'microdos4u'),
                            'label_log_in'   => __('Log In', 'microdos4u'),
                            'remember'       => true,
                        ]);
                    }
                    ?>
                    <p class="text-center mt-3">
                        <a href="<?php echo esc_url(wp_lostpassword_url(get_permalink())); ?>" style="color: #38bdf8; font-size: 13px;">Lost your password?</a>
                    </p>
                    <p class="text-center mt-4" style="border-top: 1px solid #1f2b47; padding-top: 16px;">
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('affiliate-dashboard-guide')) ?: ''); ?>" style="color: #44f80c; font-size: 20px; font-weight: 700; display: block; margin-bottom: 12px; padding: 8px 0;">&#128214; Dashboard Guide</a>
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('marketing-guide')) ?: ''); ?>" style="color: #ff66c4; font-size: 20px; font-weight: 700; display: block; padding: 8px 0;">&#127760; Marketing Guide</a>
                    </p>
                </div>
            </div>
            <?php endif; ?>

<!-- Commission Structure -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Commission Structure
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-4 rounded" style="background-color: #150f24;">
                        <h3 class="text-lg font-semibold mb-2" style="color: #44f80c;">Initial Purchase</h3>
                        <p class="text-3xl font-bold text-white mb-1">20%</p>
                        <p class="text-slate-400 text-sm">Commission on every first-time purchase made by your referral.</p>
                    </div>
                    <div class="p-4 rounded" style="background-color: #150f24;">
                        <h3 class="text-lg font-semibold mb-2" style="color: #9a02d0;">Subscription Renewals</h3>
                        <p class="text-3xl font-bold text-white mb-1">10%</p>
                        <p class="text-slate-400 text-sm">Recurring commission on every monthly subscription renewal for 24 months.</p>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    How It Works
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #44f80c20; color: #44f80c;">1</div>
                        <div>
                            <p class="text-white font-medium">Register</p>
                            <p class="text-slate-400 text-sm">Fill out the affiliate application form below. All applications require admin approval.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #9a02d020; color: #9a02d0;">2</div>
                        <div>
                            <p class="text-white font-medium">Get Approved</p>
                            <p class="text-slate-400 text-sm">We'll review and approve your application within 24-48 hours.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #ff66c420; color: #ff66c4;">3</div>
                        <div>
                            <p class="text-white font-medium">Share Your Link</p>
                            <p class="text-slate-400 text-sm">Post your unique referral link or QR code on social media, blogs, or email.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #44f80c20; color: #44f80c;">4</div>
                        <div>
                            <p class="text-white font-medium">Earn Commissions</p>
                            <p class="text-slate-400 text-sm">Get paid for every sale and subscription renewal your referrals make.</p>
                        </div>
                    </div>
                </div>
            </div>

                        <!-- ====== AFFILIATE PORTAL ====== -->
            <?php if (!is_user_logged_in()) : ?>
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">

                    <h2 class="text-xl font-bold text-white mb-4">Apply to Become an Affiliate</h2>
                    <p class="text-slate-400 text-sm mb-4">New here? Fill out the application below to get started.</p>

                    <?php
                    if (function_exists('gravity_form')) {
                        gravity_form_enqueue_scripts(2, true);
                        gravity_form(2, false, false, false, '', true, 1);
                    } else {
                        echo '<p style="color:#ff4444;text-align:center;">Gravity Forms is not active.</p>';
                    }
                    ?>

            </div>
            <?php elseif (function_exists('affwp_is_affiliate') && affwp_is_affiliate()) : ?>
                    <!-- LOGGED IN AS AFFILIATE: Show dashboard -->
                    
                    <h2 class="text-xl font-bold text-white mb-4">Affiliate Dashboard</h2>
                    <?php echo do_shortcode('[affiliate_area]'); ?>
                    
                <?php else : ?>
                    <!-- LOGGED IN BUT NOT AFFILIATE: Show registration -->
                    
                    <h2 class="text-xl font-bold text-white mb-2">Apply to Become an Affiliate</h2>
                    <p class="text-slate-400 text-sm mb-4">You're logged in as <strong style="color: #44f80c;"><?php echo esc_html(wp_get_current_user()->display_name); ?></strong>. Complete the application below to join our affiliate program.</p>
                    
                    <?php
                    if (function_exists('gravity_form')) {
                        gravity_form_enqueue_scripts(2, true);
                        gravity_form(2, false, false, false, '', true, 1);
                    } else {
                        echo '<p style="color:#ff4444;text-align:center;">Gravity Forms is not active.</p>';
                    }
                    ?>
                    
                <?php endif; ?>
    

            <!-- ====== END PORTAL ====== -->

<!-- FAQ -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff66c4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Frequently Asked Questions
                </h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-white font-medium mb-1">How do I get paid?</h3>
                        <p class="text-slate-400 text-sm">Commissions are paid monthly via PayPal or bank transfer once you reach the $50 minimum payout threshold. You must complete a W-9 form (US affiliates) before your first payout can be issued.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">How long does the referral cookie last?</h3>
                        <p class="text-slate-400 text-sm">When someone clicks your referral link, a 45-day cookie is placed on their browser. If they purchase within 45 days, you get the commission.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">What are recurring commissions?</h3>
                        <p class="text-slate-400 text-sm">For subscription products, you earn a commission not just on the initial sale but on every monthly renewal for up to 24 months.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Why do I need to submit a W-9 form?</h3>
                        <p class="text-slate-400 text-sm">US tax law requires us to collect a completed W-9 form from all US-based affiliates before we can issue commission payments totaling $600 or more in a calendar year. This allows us to file the required 1099-NEC tax form.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Can I promote on social media?</h3>
                        <p class="text-slate-400 text-sm">Yes! You can share your referral link on any platform — Instagram, TikTok, Twitter/X, Facebook, blogs, email newsletters, Discord, Reddit, and more. Just follow our <a href="/affiliate-terms" style="color: #ff66c4; text-decoration: underline;">Affiliate Terms</a>.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

</main>

<style>
    /* Login form dark theme styling */
    #affiliate-login label {
        display: block;
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 8px;
        font-weight: 500;
    }
    #affiliate-login input[type="text"],
    #affiliate-login input[type="password"] {
        background-color: #150f24 !important;
        border: 1px solid #1f2b47 !important;
        color: #e2e8f0 !important;
        padding: 0.75rem !important;
        border-radius: 0.375rem !important;
        font-size: 1rem !important;
        width: 100% !important;
    }
    #affiliate-login input[type="submit"] {
        background-color: #44f80c !important;
        color: #0a0514 !important;
        font-weight: 700 !important;
        padding: 0.75rem 2rem !important;
        border: none !important;
        border-radius: 0.5rem !important;
        font-size: 1rem !important;
        cursor: pointer !important;
        width: 100% !important;
        margin-top: 12px;
    }
</style>

<?php
get_footer();
