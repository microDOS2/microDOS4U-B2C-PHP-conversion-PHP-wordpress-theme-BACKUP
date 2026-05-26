<?php
/**
 * The template for displaying 404 error pages.
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 800px;">

        <div class="text-center mb-12">
            <div style="font-size: 80px; font-weight: 800; color: #9a02d0; margin-bottom: 16px;">404</div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Page Not Found</h1>
            <p class="text-slate-400 text-lg">The page you are looking for does not exist or has been moved.</p>
        </div>

        <div class="card p-8 rounded-lg mb-8" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4 text-center">Where would you like to go?</h2>

            <div class="grid grid-cols-2 gap-4">
                <a href="/shop/" class="block p-4 rounded-lg text-center transition-all duration-300" style="background-color: #0a0514; border: 1px solid #1f2b47; color: #44f80c;">
                    <span class="block text-2xl mb-2">🛒</span>
                    <span class="font-semibold">Shop</span>
                </a>
                <a href="/my-account/" class="block p-4 rounded-lg text-center transition-all duration-300" style="background-color: #0a0514; border: 1px solid #1f2b47; color: #38bdf8;">
                    <span class="block text-2xl mb-2">👤</span>
                    <span class="font-semibold">My Account</span>
                </a>
                <a href="/" class="block p-4 rounded-lg text-center transition-all duration-300" style="background-color: #0a0514; border: 1px solid #1f2b47; color: #ff66c4;">
                    <span class="block text-2xl mb-2">🏠</span>
                    <span class="font-semibold">Home</span>
                </a>
                <a href="/contact/" class="block p-4 rounded-lg text-center transition-all duration-300" style="background-color: #0a0514; border: 1px solid #1f2b47; color: #94a3b8;">
                    <span class="block text-2xl mb-2">📧</span>
                    <span class="font-semibold">Contact</span>
                </a>
            </div>
        </div>

    </div>
</section>

<?php
get_footer();
