<?php
/**
 * Template Name: Affiliate Area - Custom
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

        <!-- Commission Info -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white text-center mb-2">Commission Structure</h2>
            <p class="text-center text-slate-400 text-sm mb-6">First 15 Founding Affiliates get <span style="color: #44f80c;">enhanced rates</span> — <a href="#apply" style="color: #ff66c4; text-decoration: underline;">apply now</a> to lock in Founding rates.</p>
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Founding Affiliate -->
                <div class="text-center p-5 rounded-lg" style="background-color: #0a0514; border: 1px solid #44f80c;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color: #44f80c;">Founding Affiliate (First 15)</p>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Initial Sale</p>
                            <p class="text-2xl font-bold" style="color: #44f80c;">25%</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Recurring</p>
                            <p class="text-2xl font-bold" style="color: #44f80c;">15%</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-xs">24-month cap on recurring</p>
                </div>
                <!-- Standard Affiliate -->
                <div class="text-center p-5 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                    <p class="text-xs font-bold uppercase tracking-wider mb-2 text-slate-400">Standard Affiliate (16+)</p>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Initial Sale</p>
                            <p class="text-2xl font-bold" style="color: #44f80c;">20%</p>
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs mb-1">Recurring</p>
                            <p class="text-2xl font-bold" style="color: #44f80c;">10%</p>
                        </div>
                    </div>
                    <p class="text-slate-500 text-xs">24-month cap on recurring</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mt-4">
                <div class="text-center p-3 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-xs mb-1">Cookie Duration</p>
                    <p class="text-xl font-bold" style="color: #44f80c;">45 Days</p>
                </div>
                <div class="text-center p-3 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-xs mb-1">Minimum Payout</p>
                    <p class="text-xl font-bold" style="color: #44f80c;">$50</p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white text-center mb-6">How It Works</h2>
            <div class="grid sm:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center text-lg font-bold" style="background-color: #44f80c; color: #0a0514;">1</div>
                    <p class="text-white text-sm font-semibold mb-1">Register</p>
                    <p class="text-slate-400 text-xs">Fill out the affiliate application below.</p>
                </div>
                <div>
                    <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center text-lg font-bold" style="background-color: #44f80c; color: #0a0514;">2</div>
                    <p class="text-white text-sm font-semibold mb-1">Get Approved</p>
                    <p class="text-slate-400 text-xs">We'll review and approve your application within 24-48 hours.</p>
                </div>
                <div>
                    <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center text-lg font-bold" style="background-color: #44f80c; color: #0a0514;">3</div>
                    <p class="text-white text-sm font-semibold mb-1">Share Your Link</p>
                    <p class="text-slate-400 text-xs">Post your unique referral link on social media, blogs, or email.</p>
                </div>
                <div>
                    <div class="w-12 h-12 rounded-full mx-auto mb-3 flex items-center justify-center text-lg font-bold" style="background-color: #44f80c; color: #0a0514;">4</div>
                    <p class="text-white text-sm font-semibold mb-1">Earn Commissions</p>
                    <p class="text-slate-400 text-xs">Get paid for every sale and subscription renewal.</p>
                </div>
            </div>
        </div>

        <!-- AffiliateWP Content Area -->
        <div id="apply" class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <?php
            if (function_exists('affiliate_wp')) {
                echo do_shortcode('[affiliate_area]');
            } else {
                echo '<p class="text-white text-center">AffiliateWP is not active. Please install and activate AffiliateWP.</p>';
            }
            ?>
        </div>

        <!-- FAQ -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-white text-center mb-8">Affiliate FAQ</h2>
            <div class="space-y-4">
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">How do I get paid?</summary>
                    <div class="px-4 pb-4 text-slate-400">Commissions are paid monthly via PayPal or bank transfer once you reach the $50 minimum payout threshold. You must complete a W-9 form (US affiliates) before your first payout can be issued.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">How long does the referral cookie last?</summary>
                    <div class="px-4 pb-4 text-slate-400">When someone clicks your referral link, a 45-day cookie is placed on their browser. If they purchase within 45 days, you get the commission — even if they don't buy immediately.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">What are recurring commissions?</summary>
                    <div class="px-4 pb-4 text-slate-400">For subscription products, you earn a commission not just on the initial sale but on every monthly renewal for up to 24 months. Founding Affiliates earn 15% on recurring; Standard Affiliates earn 10%.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">Can I promote on social media?</summary>
                    <div class="px-4 pb-4 text-slate-400">Yes! You can share your referral link on any platform — Instagram, TikTok, Twitter/X, Facebook, blogs, email newsletters, Discord, Reddit, and more. Just follow our <a href="/affiliate-terms" style="color: #ff66c4; text-decoration: underline;">Affiliate Terms</a>.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">Why do I need to submit a W-9 form?</summary>
                    <div class="px-4 pb-4 text-slate-400">US tax law requires us to collect a completed W-9 form (Request for Taxpayer Identification Number) from all US-based affiliates before we can issue commission payments totaling $600 or more in a calendar year. This allows us to file the required 1099-NEC tax form. W-9 collection is mandatory and payouts cannot be issued until it is completed.</div>
                </details>
            </div>
        </div>

    </div>
</section>

<?php
get_footer();
