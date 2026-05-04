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

        <!-- Commission Info -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-2xl font-bold text-white text-center mb-6">Commission Structure</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="text-center p-4 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-sm mb-1">Commission Rate</p>
                    <p class="text-3xl font-bold" style="color: #44f80c;">15%</p>
                    <p class="text-slate-400 text-xs mt-1">per sale</p>
                </div>
                <div class="text-center p-4 rounded-lg" style="background-color: #0a0514;">
                    <p class="text-slate-400 text-sm mb-1">Cookie Duration</p>
                    <p class="text-3xl font-bold" style="color: #44f80c;">60 Days</p>
                    <p class="text-slate-400 text-xs mt-1">referral tracking</p>
                </div>
            </div>
        </div>

        <!-- AffiliateWP Content Area -->
        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <?php
            // Run WordPress loop so AffiliateWP can process form submissions properly
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    // Output the page content - this renders AffiliateWP's block with proper form handling
                    the_content();
                }
            }
            ?>
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
