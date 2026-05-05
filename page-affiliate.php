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
            if (function_exists('affiliate_wp')) {
                echo do_shortcode('[affiliate_area]');
            } else {
                echo '<p class="text-white text-center">AffiliateWP is not active. Please install and activate AffiliateWP.</p>';
            }
            ?>
        </div>


<!-- Password Eye Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var pwdFields = document.querySelectorAll('input[type="password"]');
    pwdFields.forEach(function(field) {
        var btn = document.createElement('button');
        btn.type = 'button';
        btn.innerHTML = '👁️';
        btn.title = 'Show/Hide';
        btn.style.cssText = 'position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;font-size:18px;z-index:5;';
        btn.onclick = function(e) {
            e.preventDefault();
            if (field.type === 'password') {
                field.type = 'text';
                btn.innerHTML = '🙈';
            } else {
                field.type = 'password';
                btn.innerHTML = '👁️';
            }
        };
        if (field.parentNode) {
            field.parentNode.style.position = 'relative';
            field.parentNode.appendChild(btn);
        }
    });
});
</script>
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
                    <div class="px-4 pb-4 text-slate-400">Yes! You can share your referral link on any platform &mdash; Instagram, TikTok, Twitter, Facebook, blogs, email newsletters, and more.</div>
                </details>
                <details class="card rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
                    <summary class="p-4 cursor-pointer text-white font-semibold">Is there a minimum payout?</summary>
                    <div class="px-4 pb-4 text-slate-400">Yes. You must earn at least $50 in commissions before a payout is issued. Unpaid commissions roll over to the next month.</div>
                </details>
            </div>
        </div>

    </div>
</section>


<!-- Hide Rewards floating button -->
<script>
(function() {
    function hideRewards() {
        var allElements = document.querySelectorAll('button, a, div, span, iframe');
        allElements.forEach(function(el) {
            if (el.textContent && el.textContent.trim() === 'Rewards') {
                el.style.display = 'none !important';
                el.style.visibility = 'hidden !important';
                el.style.opacity = '0 !important';
                el.style.pointerEvents = 'none !important';
            }
        });
    }

    // Hide immediately on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', hideRewards);
    } else {
        hideRewards();
    }

    // Also hide after a delay (for dynamically injected widgets)
    setTimeout(hideRewards, 500);
    setTimeout(hideRewards, 1500);
    setTimeout(hideRewards, 3000);

    // Watch for dynamically added elements
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    if (node.textContent && node.textContent.trim() === 'Rewards') {
                        node.style.display = 'none !important';
                        node.style.visibility = 'hidden !important';
                    }
                    // Also check children
                    var children = node.querySelectorAll ? node.querySelectorAll('*') : [];
                    children.forEach(function(child) {
                        if (child.textContent && child.textContent.trim() === 'Rewards') {
                            child.style.display = 'none !important';
                            child.style.visibility = 'hidden !important';
                        }
                    });
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
})();
</script>

<?php
get_footer();
