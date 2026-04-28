</main><!-- #main -->

<!-- Cart Overlay -->
<div id="cart-overlay" class="cart-overlay" onclick="closeCart()"></div>

<!-- Cart Drawer -->
<div id="cart-drawer" class="cart-drawer">
    <div class="p-4 border-b border-slate-800/50 flex justify-between items-center bg-gradient-to-r from-[#150f24] to-[#0a0514]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-r from-sky-400 to-violet-500 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <h2 class="text-lg font-bold text-white">Your Cart</h2>
        </div>
        <button onclick="closeCart()" class="w-8 h-8 rounded-full bg-slate-800/50 hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
        </button>
    </div>
    <div id="cart-items" class="cart-items-container p-4">
        <!-- Cart items injected here -->
    </div>
    <div class="cart-footer p-4 border-t border-slate-800/50 bg-gradient-to-t from-[#0a0514] via-[#0a0514] to-[#150f24]">
        <div class="space-y-2 mb-4">
            <div class="flex justify-between text-slate-400 text-sm">
                <span>Subtotal</span>
                <span id="cart-subtotal" class="text-slate-300">$0.00</span>
            </div>
            <div class="flex justify-between text-slate-400 text-sm">
                <span>Tax</span>
                <span id="cart-tax" class="text-slate-300">$0.00</span>
            </div>
            <div class="flex justify-between text-slate-400 text-sm">
                <span>Shipping</span>
                <span class="text-green-400 font-medium">FREE</span>
            </div>
            <div class="flex justify-between text-white font-bold pt-2 border-t border-slate-700/50">
                <span>Total</span>
                <span id="cart-total" class="text-sky-400">$0.00</span>
            </div>
        </div>
        <button id="checkout-btn" onclick="proceedToCheckout()" class="w-full btn-primary text-white font-semibold py-3 rounded-xl shadow-lg shadow-sky-500/20 hover:shadow-sky-500/40 transition-all">
            Proceed to Checkout
        </button>
        <button onclick="closeCart()" class="w-full mt-2 text-slate-400 hover:text-white py-2 text-sm font-medium transition-colors">
            Continue Shopping
        </button>
    </div>
</div>

<?php if (is_front_page()) : ?>
<!-- Full Footer (Homepage) -->
<footer class="bg-slate-900 border-t border-slate-800" style="background-color: #0a0514 !important;">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <div class="text-2xl font-bold cursor-pointer" onclick="window.scrollTo(0,0)"><span class="text-[#44f80c]">micro</span><span class="text-[#9a02d0]">DOS</span><span class="text-[#ff66c4]">(2)</span></div>
                <p class="max-w-xs text-slate-400 mt-2">Precision psychedelics, simplified for the modern mind.</p>
            </div>
            <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div>
                    <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="hover:text-white">Benefits</a></li>
                        <li><a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="hover:text-white">Pricing</a></li>
                        <li><a href="<?php echo esc_url(home_url('/#faq')); ?>" class="hover:text-white">FAQ</a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact')); ?>" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Resources</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="hover:text-white">Micro-dosing Guide</a></li>
                        <li><a href="<?php echo esc_url(home_url('/user-stories')); ?>" class="hover:text-white">User Experiences</a></li>
                        <li><a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="hover:text-white">Metocin (4-HO-MET)</a></li>
                        <li><a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="hover:text-white">Articles & Studies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Legal</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="<?php echo esc_url(home_url('/legal-disclaimer')); ?>" class="hover:text-white">Privacy Policy</a></li>
                        <li><a href="<?php echo esc_url(home_url('/legal-disclaimer')); ?>" class="hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="mt-12 border-t border-slate-700 pt-8 text-center text-slate-500">
            <p class="text-sm mb-4">
                <strong>Important:</strong> Overconsumption may cause intense visuals and hallucinations. This product is intended for adults. Use responsibly. These statements have not been evaluated by the Food and Drug Administration. This product is not intended to diagnose, treat, cure, or prevent any disease.
            </p>
            <p class="text-sm">&copy; <?php echo date('Y'); ?> <span class="font-bold"><span class="text-[#44f80c]">micro</span><span class="text-[#9a02d0]">DOS</span><span class="text-[#ff66c4]">(2)</span></span> Inc. All rights reserved.</p>
        </div>
    </div>
</footer>
<?php else : ?>
<!-- Simple Footer (Sub-pages) -->
<footer class="text-center py-10 border-t border-slate-800" style="background-color: #0a0514 !important;">
    <p class="text-slate-500">&copy; <?php echo date('Y'); ?> <span class="text-[#44f80c]">micro</span><span class="text-[#9a02d0]">DOS</span><span class="text-[#ff66c4]">(2)</span>. All rights reserved.</p>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
