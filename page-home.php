<?php
/**
 * Template Name: Home Page
 *
 * @package microDOS4U
 */

get_header();
?>

<!-- Hero Section -->
<section class="hero-bg py-20 md:py-28">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 tracking-tight leading-tight">
            One Pill. One Dose.<br><span class="gradient-text">Pure Clarity.</span>
        </h1>
        <p class="text-lg md:text-xl max-w-2xl mx-auto mb-10" style="color: #94a3b8;">
            Legal, fast-acting psychedelic exploration.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-12">
            <a href="#pricing" class="w-full sm:w-auto px-8 py-4 text-lg text-white font-bold rounded-lg shadow-lg btn-primary" style="text-decoration: none;">
                Start Your $12.95 Trial
            </a>
            <a href="#benefits" class="w-full sm:w-auto px-8 py-4 text-lg font-semibold text-white border rounded-lg transition-colors" style="background-color: #1f1a2e; border-color: #475569; text-decoration: none;">
                Learn More
            </a>
        </div>
        <div class="flex justify-center gap-2 mt-8" id="slide-dots">
            <button class="slide-dot active"></button>
            <button class="slide-dot"></button>
            <button class="slide-dot"></button>
            <button class="slide-dot"></button>
            <button class="slide-dot"></button>
            <button class="slide-dot"></button>
        </div>
    </div>
</section>

<!-- Benefits Section -->
<section id="benefits" class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important;">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <span class="text-sky-400 font-semibold">THE MICRODOS ADVANTAGE</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">A Cleaner, Clearer Experience</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 card-bg rounded-xl border border-slate-800">
                <div class="inline-flex feature-icon mb-4">
                    <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Rapid Onset</h3>
                <p class="text-slate-400">Effects begin in ~10 minutes for a predictable, manageable session.</p>
            </div>
            <div class="text-center p-6 card-bg rounded-xl border border-slate-800">
                <div class="inline-flex feature-icon mb-4">
                    <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.085a2 2 0 00-1.736.97l-2.714 4.224a2 2 0 00.174 2.573V10h4z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">No Nausea</h3>
                <p class="text-slate-400">A clean formula designed to avoid the discomfort of traditional mushrooms.</p>
            </div>
            <div class="text-center p-6 card-bg rounded-xl border border-slate-800">
                <div class="inline-flex feature-icon mb-4">
                    <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Precision Dose</h3>
                <p class="text-slate-400">Each 2mg scored pill ensures a consistent, reliable microdose every time.</p>
            </div>
            <div class="text-center p-6 card-bg rounded-xl border border-slate-800">
                <div class="inline-flex feature-icon mb-4">
                    <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-2">Legal &amp; Safe</h3>
                <p class="text-slate-400">Not federally scheduled. Shipped discreetly and reliably to your door.</p>
            </div>
        </div>
    </div>
</section>

<!-- Product Specs -->
<section class="py-10" style="background-color: rgba(21, 15, 36, 0.5) !important;">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-2xl font-bold text-white mb-8">Product Specifications</h2>
        <div class="flex flex-col md:flex-row justify-center gap-8 md:gap-16">
            <div>
                <p class="text-slate-400 font-semibold uppercase tracking-wider text-sm">Active Compound</p>
                <p class="text-lg font-bold text-white">2mg Metocin (4-HO-MET)</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold uppercase tracking-wider text-sm">Form</p>
                <p class="text-lg font-bold text-white">Scored, Non-Chewable Pill</p>
            </div>
            <div>
                <p class="text-slate-400 font-semibold uppercase tracking-wider text-sm">Shipping</p>
                <p class="text-lg font-bold text-white">Discreet, within 48 hours</p>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section id="reviews" class="py-20 overflow-hidden" style="background-color: rgba(10, 5, 20, 0.7) !important;">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-white text-center mb-12">Trusted by thousands for focus and clarity</h2>
        <div class="text-center text-slate-400">
            <p>User testimonials coming soon.</p>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20" style="background-color: rgba(10, 5, 20, 0.5) !important;">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-16">Get Started in 3 Simple Steps</h2>
        <div class="relative">
            <div class="hidden md:block absolute top-10 left-[16.66%] right-[16.66%] h-0.5 bg-slate-700 z-0" style="background-color: #1a1329 !important;"></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center relative z-10">
                <div>
                    <div class="w-20 h-20 mx-auto bg-slate-800 border-4 border-slate-700 rounded-full flex items-center justify-center text-3xl font-bold text-sky-400 mb-6 shadow-inner" style="background-color: #150f24 !important;">1</div>
                    <h3 class="text-xl font-bold text-white mb-3">Choose Your Plan</h3>
                    <p class="text-slate-400 leading-relaxed">Start with our risk-free trial or choose your favorite quantity.</p>
                </div>
                <div>
                    <div class="w-20 h-20 mx-auto bg-slate-800 border-4 border-slate-700 rounded-full flex items-center justify-center text-3xl font-bold text-sky-400 mb-6 shadow-inner" style="background-color: #150f24 !important;">2</div>
                    <h3 class="text-xl font-bold text-white mb-3">Fast, Discreet Shipping</h3>
                    <p class="text-slate-400 leading-relaxed">We ship your order in plain packaging within 48 hours. Your privacy is guaranteed.</p>
                </div>
                <div>
                    <div class="w-20 h-20 mx-auto bg-slate-800 border-4 border-slate-700 rounded-full flex items-center justify-center text-3xl font-bold text-sky-400 mb-6 shadow-inner" style="background-color: #150f24 !important;">3</div>
                    <h3 class="text-xl font-bold text-white mb-3">Experience Clarity</h3>
                    <p class="text-slate-400 leading-relaxed">Take one pill for one consistent microdose.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" class="py-20 bg-slate-900" style="background-color: #0a0514 !important;">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl md:text-4xl font-bold text-white text-center mb-12">Find Your Flow State</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto items-stretch">
            
            <!-- Trial Pack -->
            <div class="pricing-card card-bg rounded-xl p-8 border border-slate-800 flex flex-col shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 py-1.5 px-4 bg-sky-400 text-white text-xs font-bold rounded-bl-lg">GREAT VALUE</div>
                <h3 class="text-2xl font-bold text-white mb-2">Trial Pack</h3>
                <p class="text-slate-400 mb-2">A low-risk introduction.</p>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/microDOS2.jpg" alt="microDOS(2) logo" class="w-40 mx-auto mb-4 rounded-lg" onerror="this.style.display='none'">
                <div class="my-4 text-center">
                    <span class="text-5xl font-extrabold text-white">$12.95</span>
                    <span class="text-slate-400">/ one-time</span>
                </div>
                <ul class="space-y-3 text-slate-300 text-left mb-8">
                    <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>2 Pills</li>
                    <li class="flex items-center"><svg class="w-5 h-5 mr-2 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"></path></svg>Free Shipping</li>
                </ul>
                <div class="mt-auto">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <span class="text-slate-400 text-sm mr-2">Quantity:</span>
                        <button class="quantity-btn" onclick="updateQty('trial', -1)">-</button>
                        <input type="text" id="qty-trial" class="quantity-input" value="1" readonly>
                        <button class="quantity-btn" onclick="updateQty('trial', 1)">+</button>
                    </div>
                    <button onclick="addToCart('trial', 'Trial Pack', 12.95)" class="w-full btn-primary text-white font-semibold py-4 rounded-lg text-center block">Add to Cart</button>
                </div>
            </div>

            <!-- Dynamic Purchase Card (Protocol & One-Time) -->
            <div class="pricing-card card-bg rounded-xl p-8 border border-slate-800 flex flex-col shadow-lg">
                
                <!-- Toggle Switch -->
                <div class="flex justify-center mb-8">
                    <div class="bg-slate-900 p-1 rounded-full inline-flex relative w-64 border border-slate-700" style="background-color: #0a0514 !important;">
                        <button id="btn-protocol" onclick="switchPurchaseTab('protocol')" class="relative z-10 w-1/2 py-2 text-sm font-bold text-white transition-colors" style="background-color: #1a1329; border-radius: 9999px;">The Protocol</button>
                        <button id="btn-onetime" onclick="switchPurchaseTab('onetime')" class="relative z-10 w-1/2 py-2 text-sm font-bold text-slate-400 hover:text-white transition-colors">One-Time</button>
                    </div>
                </div>

                <!-- Protocol Container -->
                <div id="protocol-container" class="flex flex-col flex-grow">
                    <h3 class="text-2xl font-bold text-white mb-2">The Protocol <span class="text-xs font-bold text-sky-400 bg-sky-400/10 px-2 py-1 rounded ml-2 align-middle">SAVE 15%</span></h3>
                    <p class="text-slate-400 mb-6">A guided Monthly Wellness Protocol.</p>
                    <div class="space-y-4 mt-auto">
                        <!-- Protocol 10 -->
                        <div class="p-4 rounded-lg border border-sky-500/20" style="background-color: #1a1329 !important;">
                            <p class="font-bold text-white">Explorer Box <span class="text-xs text-slate-400 font-normal ml-1">(10 Pills)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$47.56</span> <span class="text-slate-400 text-sm">/ mo ($4.76/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('exp', -1)">-</button>
                                <input type="text" id="qty-exp" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('exp', 1)">+</button>
                                <button onclick="addToCart('exp', 'Explorer Box', 47.56)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                        <!-- Protocol 30 -->
                        <div class="p-4 rounded-lg border border-sky-500/20 relative overflow-hidden" style="background-color: #1a1329 !important;">
                            <div class="absolute top-0 right-0 bg-sky-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-bl">RECOMMENDED</div>
                            <p class="font-bold text-white">Optimizer Box <span class="text-xs text-slate-400 font-normal ml-1">(30 Pills)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$128.31</span> <span class="text-slate-400 text-sm">/ mo ($4.28/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('opt', -1)">-</button>
                                <input type="text" id="qty-opt" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('opt', 1)">+</button>
                                <button onclick="addToCart('opt', 'Optimizer Box', 128.31)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                        <!-- Protocol 60 -->
                        <div class="p-4 rounded-lg border border-sky-500/20" style="background-color: #1a1329 !important;">
                            <p class="font-bold text-white">Master Box <span class="text-xs text-slate-400 font-normal ml-1">(60 Pills)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$217.56</span> <span class="text-slate-400 text-sm">/ mo ($3.63/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('master', -1)">-</button>
                                <input type="text" id="qty-master" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('master', 1)">+</button>
                                <button onclick="addToCart('master', 'Master Box', 217.56)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- One-Time Container -->
                <div id="onetime-container" class="flex flex-col flex-grow hidden">
                    <h3 class="text-2xl font-bold text-white mb-2">One-Time Purchase</h3>
                    <p class="text-slate-400 mb-6">Buy once, no subscription.</p>
                    <div class="space-y-4 mt-auto">
                        <!-- One-Time 10 -->
                        <div class="p-4 rounded-lg border border-sky-500/20" style="background-color: #1a1329 !important;">
                            <p class="font-bold text-white">10 Pills <span class="text-xs text-slate-400 font-normal ml-1">(One-Time)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$55.95</span> <span class="text-slate-400 text-sm">($5.60/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('ot10', -1)">-</button>
                                <input type="text" id="qty-ot10" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('ot10', 1)">+</button>
                                <button onclick="addToCart('ot10', '10 Pills (One-Time)', 55.95)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                        <!-- One-Time 30 -->
                        <div class="p-4 rounded-lg border border-sky-500/20 relative overflow-hidden" style="background-color: #1a1329 !important;">
                            <div class="absolute top-0 right-0 bg-sky-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-bl">POPULAR</div>
                            <p class="font-bold text-white">30 Pills <span class="text-xs text-slate-400 font-normal ml-1">(One-Time)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$150.95</span> <span class="text-slate-400 text-sm">($5.03/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('ot30', -1)">-</button>
                                <input type="text" id="qty-ot30" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('ot30', 1)">+</button>
                                <button onclick="addToCart('ot30', '30 Pills (One-Time)', 150.95)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                        <!-- One-Time 60 -->
                        <div class="p-4 rounded-lg border border-sky-500/20" style="background-color: #1a1329 !important;">
                            <p class="font-bold text-white">60 Pills <span class="text-xs text-slate-400 font-normal ml-1">(One-Time)</span></p>
                            <p class="mb-3"><span class="text-2xl font-bold text-white">$255.95</span> <span class="text-slate-400 text-sm">($4.27/pill)</span></p>
                            <div class="flex items-center gap-2">
                                <button class="quantity-btn" onclick="updateQty('ot60', -1)">-</button>
                                <input type="text" id="qty-ot60" class="quantity-input" value="1" readonly>
                                <button class="quantity-btn" onclick="updateQty('ot60', 1)">+</button>
                                <button onclick="addToCart('ot60', '60 Pills (One-Time)', 255.95)" class="flex-1 btn-primary text-white text-sm font-semibold rounded-lg py-2">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="py-20 bg-slate-900" style="background-color: #0a0514 !important;">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-3xl font-bold text-white text-center mb-12">Frequently Asked Questions</h2>
        <div class="space-y-4" id="faq-container">
            <div class="card-bg rounded-lg border border-slate-800">
                <button class="faq-question flex justify-between items-center w-full p-6 text-left font-semibold text-white" aria-expanded="false" aria-controls="faq1-answer">
                    <span>Is this legal?</span>
                    <svg class="faq-arrow w-5 h-5 shrink-0 transition-transform duration-200 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq1-answer" class="faq-answer hidden p-6 pt-0 text-slate-300 leading-relaxed">Yes. Our active ingredient, 4-HO-MET, is not federally scheduled in the United States. We recommend checking your local regulations, but we ship to all 50 states.</div>
            </div>
            <div class="card-bg rounded-lg border border-slate-800">
                <button class="faq-question flex justify-between items-center w-full p-6 text-left font-semibold text-white" aria-expanded="false" aria-controls="faq2-answer">
                    <span>What does a microdose feel like?</span>
                    <svg class="faq-arrow w-5 h-5 shrink-0 transition-transform duration-200 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq2-answer" class="faq-answer hidden p-6 pt-0 text-slate-300 leading-relaxed">
                    <p>Effects are subtle and non-intrusive. Users commonly report enhanced focus, increased creativity, a more positive mood, and a feeling of being more present and engaged. It is not a "trip" but a gentle cognitive and emotional lift.</p>
                </div>
            </div>
            <div class="card-bg rounded-lg border border-slate-800">
                <button class="faq-question flex justify-between items-center w-full p-6 text-left font-semibold text-white" aria-expanded="false" aria-controls="faq3-answer">
                    <span>Is it safe?</span>
                    <svg class="faq-arrow w-5 h-5 shrink-0 transition-transform duration-200 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div id="faq3-answer" class="faq-answer hidden p-6 pt-0 text-slate-300 leading-relaxed">
                    <p>Yes. Our product is formulated for safety and consistency. We always recommend responsible consumption. Please consult a healthcare professional if you have pre-existing conditions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
get_footer();
