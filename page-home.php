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
        <h1 id="hero-title" class="text-4xl md:text-6xl font-extrabold tracking-tighter text-white mb-4 transition-opacity duration-500 min-h-[4rem]"></h1>
        <p id="hero-subtitle" class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto mb-8 transition-opacity duration-500 min-h-[3rem]"></p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="#pricing" class="w-full sm:w-auto px-8 py-4 text-lg text-white font-bold rounded-lg shadow-lg btn-primary transform hover:scale-105">
                Start Your $12.95 Trial
            </a>
            <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 text-lg font-semibold text-white bg-slate-700 border border-slate-600 rounded-lg shadow-md hover:bg-slate-600 transition-colors" style="background-color: #1f1a2e !important;">
                Learn More
            </a>
        </div>
        <div class="mt-16 max-w-sm mx-auto aspect-square relative">
            <div id="video-container" class="w-full h-full bg-slate-800 rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-700" style="background-color: #150f24 !important;"></div>
        </div>
        <div id="video-dots" class="text-center mt-6"></div>
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
        <div class="relative">
            <div id="testimonial-carousel" class="testimonial-carousel"></div>
            <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-slate-900 to-transparent pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-slate-900 to-transparent pointer-events-none"></div>
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

<!-- Checkout Section -->
<section id="checkout" class="py-20 hidden" style="background-color: rgba(10, 5, 20, 0.7) !important;">
    <div class="container mx-auto px-4 sm:px-6">
        <div id="checkout-container" class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white">Secure Checkout</h2>
            </div>

            <!-- Step Indicator -->
            <div class="flex justify-center border-b border-slate-800 mb-12">
                <div id="step-1-indicator" class="step-active py-4 px-6 text-sm font-semibold border-b-2">1. Shipping</div>
                <div id="step-2-indicator" class="step-inactive py-4 px-6 text-sm font-semibold border-b-2">2. Payment</div>
                <div id="step-3-indicator" class="step-inactive py-4 px-6 text-sm font-semibold border-b-2">3. Confirm</div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Form Section -->
                <div class="lg:col-span-1">
                    <form id="checkout-form" novalidate>
                        <!-- Step 1: Shipping -->
                        <div id="step-1">
                            <h3 class="text-xl font-bold text-white mb-6">Shipping Details</h3>
                            <div class="space-y-4">
                                <input type="email" id="email" class="w-full input-field" placeholder="Email Address" required>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" id="first-name" class="w-full input-field" placeholder="First Name" required>
                                    <input type="text" id="last-name" class="w-full input-field" placeholder="Last Name" required>
                                </div>
                                <input type="text" id="address" class="w-full input-field" placeholder="Street Address" required>
                                <div class="grid grid-cols-3 gap-4">
                                    <input type="text" id="city" class="w-full input-field col-span-1" placeholder="City" required>
                                    <select id="state" class="w-full input-field" required>
                                        <option value="">State</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="DC">Washington DC</option>
                                    </select>
                                    <input type="text" id="zip" class="w-full input-field" placeholder="ZIP" required maxlength="5">
                                </div>
                            </div>
                            <button type="button" id="to-step-2" class="w-full mt-8 btn-primary text-white font-bold py-3 rounded-lg">Continue to Payment</button>
                        </div>

                        <!-- Step 2: Payment -->
                        <div id="step-2" class="hidden">
                            <h3 class="text-xl font-bold text-white mb-6">Payment Method</h3>
                            <div class="space-y-4">
                                <input type="text" id="card-number" class="w-full input-field" placeholder="0000 0000 0000 0000" required>
                                <input type="text" id="card-name" class="w-full input-field" placeholder="Name on Card" required>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" id="expiry-date" class="w-full input-field" placeholder="MM / YY" required>
                                    <input type="text" id="cvc" class="w-full input-field" placeholder="CVC" required>
                                </div>
                            </div>
                            
                            <!-- Billing Address Toggle -->
                            <div class="mt-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="same-billing" class="mr-3" checked onchange="toggleBillingAddress()">
                                    <span class="text-slate-300">Billing address is the same as shipping address</span>
                                </label>
                            </div>
                            
                            <!-- Billing Address Section (hidden by default) -->
                            <div id="billing-address-section" class="hidden mt-6 space-y-4">
                                <h4 class="text-lg font-semibold text-white">Billing Address</h4>
                                <input type="text" id="billing-name" class="w-full input-field" placeholder="Cardholder Name">
                                <input type="text" id="billing-address" class="w-full input-field" placeholder="Street Address">
                                <div class="grid grid-cols-3 gap-4">
                                    <input type="text" id="billing-city" class="w-full input-field" placeholder="City">
                                    <select id="billing-state" class="w-full input-field">
                                        <option value="">State</option>
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="DC">Washington DC</option>
                                    </select>
                                    <input type="text" id="billing-zip" class="w-full input-field" placeholder="ZIP" maxlength="5">
                                </div>
                            </div>
                            
                            <div class="mt-8 flex gap-4">
                                <button type="button" id="back-to-step-1" class="w-1/3 text-slate-400 hover:text-white">Back</button>
                                <button type="button" id="to-step-3" class="w-2/3 btn-primary text-white font-bold py-3 rounded-lg">Review Order</button>
                            </div>
                        </div>
                        
                        <!-- Step 3: Confirmation -->
                        <div id="step-3" class="hidden">
                            <h3 class="text-xl font-bold text-white mb-6">Confirm Order</h3>
                            <div class="card-bg border border-slate-700 rounded-lg p-6 space-y-4 text-slate-300">
                                <div id="confirm-summary-text"></div>
                                <div id="confirm-shipping-address" class="text-sm border-t border-slate-700 pt-4 mt-4"></div>
                                <div id="confirm-billing-address" class="text-sm border-t border-slate-700 pt-4 hidden"></div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="ageVerified" class="mr-3"> I am 21 or older.
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" id="legalAccepted" class="mr-3"> I agree to terms.
                                </label>
                            </div>
                            <div class="mt-8 flex gap-4">
                                <button type="button" id="back-to-step-2" class="w-1/3 text-slate-400 hover:text-white">Back</button>
                                <button type="submit" id="confirm-purchase" class="w-2/3 btn-primary text-white font-bold py-3 rounded-lg flex items-center justify-center">
                                    <span class="btn-text">Confirm Purchase</span>
                                    <span class="spinner hidden"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Sidebar Summary -->
                <div class="lg:col-span-1">
                    <div class="card-bg rounded-lg p-6 border border-slate-800 sticky top-28 shadow-xl">
                        <h3 class="text-lg font-bold text-white mb-4">Your Order</h3>
                        <div id="order-summary-content" class="text-slate-300 space-y-2"></div>
                        <div class="border-t border-slate-700 mt-4 pt-4 space-y-2">
                            <div class="flex justify-between text-slate-400">
                                <span>Subtotal</span>
                                <span id="summary-subtotal">$0.00</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Tax</span>
                                <span id="summary-tax">$0.00</span>
                            </div>
                            <div class="flex justify-between text-slate-400">
                                <span>Shipping</span>
                                <span class="text-green-400">FREE</span>
                            </div>
                            <div class="flex justify-between text-white font-bold text-lg pt-2 border-t border-slate-700">
                                <span>Total</span>
                                <span id="summary-total">$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success -->
        <div id="success-message" class="hidden text-center card-bg border border-green-500 rounded-lg p-8 max-w-lg mx-auto shadow-2xl">
            <div class="w-16 h-16 bg-green-500/20 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-4">Order Received!</h2>
            <p class="text-slate-300 mb-8">Your journey to clarity begins soon. A confirmation email has been sent to your address.</p>
            <button id="reset-checkout-btn" class="w-full btn-secondary text-white font-bold py-3 rounded-lg">Back to Store</button>
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
