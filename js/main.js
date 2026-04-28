/**
 * microDOS4U Theme Main JavaScript
 * Exact copy from original HTML
 */

// State tax rates for checkout
const stateTaxRates = {
    'AL': 0.09, 'AK': 0.00, 'AZ': 0.084, 'AR': 0.095,
    'CA': 0.083, 'CO': 0.079, 'CT': 0.064, 'DE': 0.00,
    'FL': 0.07, 'GA': 0.074, 'HI': 0.044, 'ID': 0.06,
    'IL': 0.088, 'IN': 0.07, 'IA': 0.068, 'KS': 0.086,
    'KY': 0.06, 'LA': 0.10, 'ME': 0.055, 'MD': 0.06,
    'MA': 0.0625, 'MI': 0.06, 'MN': 0.074, 'MS': 0.07,
    'MO': 0.084, 'MT': 0.00, 'NE': 0.068, 'NV': 0.082,
    'NH': 0.00, 'NJ': 0.066, 'NM': 0.078, 'NY': 0.084,
    'NC': 0.07, 'ND': 0.068, 'OH': 0.072, 'OK': 0.089,
    'OR': 0.00, 'PA': 0.063, 'RI': 0.07, 'SC': 0.072,
    'SD': 0.06, 'TN': 0.10, 'TX': 0.082, 'UT': 0.071,
    'VT': 0.06, 'VA': 0.057, 'WA': 0.088, 'WV': 0.06,
    'WI': 0.054, 'WY': 0.054, 'DC': 0.06
};

// Products
const products = {
    trial: { name: 'Trial Pack', price: 12.95 },
    protocol_10: { name: 'Explorer Box Protocol (10 Pills/mo)', price: 47.56 },
    protocol_30: { name: 'Optimizer Box Protocol (30 Pills/mo)', price: 128.31 },
    protocol_60: { name: 'Master Box Protocol (60 Pills/mo)', price: 217.56 },
    onetime_10: { name: '10 Pills (One-Time)', price: 55.95 },
    onetime_30: { name: '30 Pills (One-Time)', price: 150.95 },
    onetime_60: { name: '60 Pills (One-Time)', price: 255.95 }
};

// Cart
let cart = [];

function loadCart() {
    const saved = localStorage.getItem('microdos_cart');
    if (saved) {
        try {
            cart = JSON.parse(saved);
        } catch(e) {
            cart = [];
        }
    }
    renderCart();
}

function saveCart() {
    localStorage.setItem('microdos_cart', JSON.stringify(cart));
}

function addToCart(planId) {
    const quantity = parseInt(document.getElementById('qty-' + planId).value);
    const product = products[planId];

    const existingItem = cart.find(item => item.id === planId);

    if (existingItem) {
        const newQty = existingItem.quantity + quantity;
        if (newQty <= 10) {
            existingItem.quantity = newQty;
        } else {
            alert('Maximum quantity per item is 10');
            return;
        }
    } else {
        cart.push({
            id: planId,
            name: product.name,
            price: product.price,
            quantity: quantity
        });
    }

    saveCart();
    renderCart();
    showToast(product.name + ' added to cart!');
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(function() {
        toast.remove();
    }, 3000);
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    renderCart();
}

function updateCartQty(id, change) {
    const item = cart.find(item => item.id === id);
    if (!item) return;
    item.quantity += change;
    if (item.quantity < 1) {
        removeFromCart(id);
        return;
    }
    saveCart();
    renderCart();
}

function renderCart() {
    const cartItems = document.getElementById('cart-items');
    const cartBadge = document.getElementById('cart-badge');
    const cartSubtotal = document.getElementById('cart-subtotal');
    const cartTax = document.getElementById('cart-tax');
    const cartTotal = document.getElementById('cart-total');

    if (!cartItems) return;

    const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

    if (cart.length === 0) {
        cartItems.innerHTML = '<div class="text-center text-slate-400 py-8"><p>Your cart is empty</p></div>';
    } else {
        cartItems.innerHTML = cart.map(item => `
            <div class="flex items-center gap-3 mb-4 p-3 rounded-lg bg-slate-800/50">
                <div class="flex-1">
                    <p class="text-white font-semibold text-sm">${item.name}</p>
                    <p class="text-slate-400 text-sm">$${item.price.toFixed(2)} each</p>
                </div>
                <div class="flex items-center gap-1">
                    <button onclick="updateCartQty('${item.id}', -1)" class="w-7 h-7 rounded bg-slate-700 text-white flex items-center justify-center hover:bg-slate-600">-</button>
                    <span class="text-white w-6 text-center text-sm">${item.quantity}</span>
                    <button onclick="updateCartQty('${item.id}', 1)" class="w-7 h-7 rounded bg-slate-700 text-white flex items-center justify-center hover:bg-slate-600">+</button>
                </div>
                <button onclick="removeFromCart('${item.id}')" class="text-slate-400 hover:text-red-400 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
        `).join('');
    }

    if (cartBadge) {
        cartBadge.textContent = totalQty;
        cartBadge.classList.toggle('hidden', totalQty === 0);
    }
    if (cartSubtotal) cartSubtotal.textContent = '$' + subtotal.toFixed(2);
    if (cartTax) cartTax.textContent = '$0.00';
    if (cartTotal) cartTotal.textContent = '$' + subtotal.toFixed(2);
}

function openCart() {
    document.getElementById('cart-drawer').classList.add('open');
    document.getElementById('cart-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeCart() {
    document.getElementById('cart-drawer').classList.remove('open');
    document.getElementById('cart-overlay').classList.remove('open');
    document.body.style.overflow = '';
}

function proceedToCheckout() {
    closeCart();
    document.getElementById('checkout').classList.remove('hidden');
    document.getElementById('checkout').scrollIntoView({ behavior: 'smooth' });
    updateCheckoutSummary();
}

function updateCheckoutSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const orderContent = document.getElementById('order-summary-content');
    if (orderContent) {
        if (cart.length === 0) {
            orderContent.innerHTML = '<p class="text-slate-400">No items in cart</p>';
        } else {
            orderContent.innerHTML = cart.map(item => `
                <div class="flex justify-between text-sm">
                    <span>${item.name} x${item.quantity}</span>
                    <span>$${(item.price * item.quantity).toFixed(2)}</span>
                </div>
            `).join('');
        }
    }

    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTax = document.getElementById('summary-tax');
    const summaryTotal = document.getElementById('summary-total');

    const shippingState = document.getElementById('state')?.value || '';
    const taxRate = stateTaxRates[shippingState] || 0;
    const tax = Math.round(subtotal * taxRate * 100) / 100;
    const total = subtotal + tax;

    if (summarySubtotal) summarySubtotal.textContent = '$' + subtotal.toFixed(2);
    if (summaryTax) summaryTax.textContent = '$' + tax.toFixed(2);
    if (summaryTotal) summaryTotal.textContent = '$' + total.toFixed(2);
}

function toggleBillingAddress() {
    const checkbox = document.getElementById('same-billing');
    const section = document.getElementById('billing-address-section');
    const inputs = section.querySelectorAll('input, select');
    if (checkbox.checked) {
        section.classList.add('hidden');
        inputs.forEach(input => input.removeAttribute('required'));
    } else {
        section.classList.remove('hidden');
        inputs.forEach(input => input.setAttribute('required', 'required'));
    }
}

// Quantity Management
function adjustQuantity(planId, change) {
    const input = document.getElementById('qty-' + planId);
    if (!input) return;
    let value = parseInt(input.value) + change;
    value = Math.max(1, Math.min(10, value));
    input.value = value;
}

// Video Rotator
function initVideoRotator() {
    const videoData = [
        { videoId: 'MOBdkkeXLto', title: 'One Pill. One Dose. <span class="gradient-text">Pure Clarity.</span>', subtitle: 'Legal, fast-acting psychedelic exploration.' },
        { videoId: 'C5XF8eekB50', title: 'Unlock Creative Flow. <span class="gradient-text">Elevate Your Senses.</span>', subtitle: 'Tap into a higher state of focus and inspiration.' },
        { videoId: 'xY9Z-HNTbso', title: 'Expand Your Mind. <span class="gradient-text">Discover New Worlds.</span>', subtitle: 'Journey through vibrant landscapes of thought.' },
        { videoId: 'ZZWG4DzE8jw', title: 'One Pill. One Dose. <span class="gradient-text">Pure Clarity.</span>', subtitle: 'Legal, fast-acting psychedelic exploration.' },
        { videoId: 'tb5EjQuoBug', title: 'Unlock Creative Flow. <span class="gradient-text">Elevate Your Senses.</span>', subtitle: 'Tap into a higher state of focus and inspiration.' },
        { videoId: 'hD-6Gk6_UQ0', title: 'Expand Your Mind. <span class="gradient-text">Discover New Worlds.</span>', subtitle: 'Journey through vibrant landscapes of thought.' }
    ];

    let index = 0;
    const container = document.getElementById('video-container');
    const title = document.getElementById('hero-title');
    const sub = document.getElementById('hero-subtitle');
    const dots = document.getElementById('video-dots');

    if (!container || !title || !sub || !dots) return;

    // Create wrapper styles for thumbnail + play button
    if (!document.getElementById('video-thumb-styles')) {
        const style = document.createElement('style');
        style.id = 'video-thumb-styles';
        style.textContent = `
            .video-slide { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; transition: opacity 0.5s ease; z-index: 1; }
            .video-slide.active { opacity: 1; z-index: 2; }
            .video-thumb-wrap { position: relative; width: 100%; height: 100%; cursor: pointer; background: #0a0514; border-radius: 8px; overflow: hidden; }
            .video-thumb-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
            .video-play-overlay { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; background: rgba(10,5,20,0.3); transition: background 0.3s ease; }
            .video-thumb-wrap:hover .video-play-overlay { background: rgba(10,5,20,0.15); }
            .video-play-btn { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #44f80c, #9a02d0, #ff66c4); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 30px rgba(154,2,208,0.5); transition: transform 0.3s ease, box-shadow 0.3s ease; }
            .video-thumb-wrap:hover .video-play-btn { transform: scale(1.1); box-shadow: 0 6px 40px rgba(154,2,208,0.7); }
            .video-play-btn svg { width: 28px; height: 28px; fill: white; margin-left: 4px; }
            .video-iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none; border-radius: 8px; }
        `;
        document.head.appendChild(style);
    }

    videoData.forEach((d, i) => {
        // Create slide wrapper
        const slide = document.createElement('div');
        slide.className = 'video-slide' + (i === 0 ? ' active' : '');
        slide.dataset.index = i;

        // Create thumbnail wrapper
        const thumbWrap = document.createElement('div');
        thumbWrap.className = 'video-thumb-wrap';

        // Thumbnail image from YouTube
        const thumb = document.createElement('img');
        thumb.src = `https://img.youtube.com/vi/${d.videoId}/hqdefault.jpg`;
        thumb.alt = d.title;
        thumb.loading = i === 0 ? 'eager' : 'lazy';
        thumbWrap.appendChild(thumb);

        // Play button overlay
        const overlay = document.createElement('div');
        overlay.className = 'video-play-overlay';
        overlay.innerHTML = `
            <div class="video-play-btn">
                <svg viewBox="0 0 24 24"><polygon points="8,5 8,19 20,12"></polygon></svg>
            </div>
        `;
        thumbWrap.appendChild(overlay);

        slide.appendChild(thumbWrap);
        container.appendChild(slide);
        d.slide = slide;
        d.thumbWrap = thumbWrap;

        // Click handler: replace thumbnail with iframe
        thumbWrap.addEventListener('click', () => {
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube.com/embed/${d.videoId}?autoplay=1&mute=0&loop=1&playlist=${d.videoId}&controls=0&modestbranding=1&rel=0&iv_load_policy=3`;
            iframe.className = 'video-iframe';
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
            iframe.setAttribute('allowfullscreen', '');
            slide.removeChild(thumbWrap);
            slide.appendChild(iframe);
        });

        // Dot
        const dot = document.createElement('button');
        dot.className = 'video-dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('aria-label', `Slide ${i + 1}`);
        dot.onclick = () => show(i);
        dots.appendChild(dot);
    });

    function show(n) {
        index = n;
        title.style.opacity = 0;
        sub.style.opacity = 0;
        setTimeout(() => {
            title.innerHTML = videoData[n].title;
            sub.textContent = videoData[n].subtitle;
            title.style.opacity = 1;
            sub.style.opacity = 1;
            videoData.forEach((v, i) => v.slide.classList.toggle('active', i === n));
            Array.from(dots.children).forEach((d, i) => d.classList.toggle('active', i === n));
        }, 500);
    }

    setInterval(() => show((index + 1) % videoData.length), 8000);
}

// Reviews
function initReviews() {
    const reviews = [
        { name: "Sarah M.", text: "This has completely replaced my morning coffee. Clean energy and zero anxiety." },
        { name: "David K.", text: "The precision dosing is a game-changer. I know exactly what to expect every time." },
        { name: "Elena R.", text: "Finally, a reliable way to enhance my focus without the stomach issues." },
        { name: "Marcus T.", text: "Incredible clarity. The 2mg dose is the perfect sweet spot for my creative work." },
        { name: "Jenny L.", text: "Discreet packaging and fast shipping. The product itself exceeded expectations." },
        { name: "Sarah K.", text: "I was skeptical, but the trial sold me. It's exactly as described - a subtle lift." },
        { name: "Mike D.", text: "The product is top-notch. Discreet shipping is a huge plus." },
        { name: "Jess R.", text: "A perfect tool for deep work sessions. Helps me get into a flow state much faster." }
    ];

    const carousel = document.getElementById('testimonial-carousel');
    if (!carousel) return;

    function createCardHTML(r) {
        return `
            <div class="testimonial-card">
                <div class="card-bg p-6 rounded-xl border border-slate-800 h-full flex flex-col justify-between shadow-lg">
                    <div>
                        <div class="text-sky-400 mb-4 text-xl">★★★★★</div>
                        <p class="text-slate-300 italic mb-4 leading-relaxed">"${r.text}"</p>
                    </div>
                    <p class="text-white font-semibold text-sm">- ${r.name}</p>
                </div>
            </div>
        `;
    }

    const allReviews = [...reviews, ...reviews];
    carousel.innerHTML = allReviews.map(createCardHTML).join('');
}

// Pricing Toggle
function initPricingToggle() {
    const btnProtocol = document.getElementById('btn-protocol');
    const btnOneTime = document.getElementById('btn-onetime');
    const contentProtocol = document.getElementById('content-protocol');
    const contentOneTime = document.getElementById('content-onetime');
    const toggleBg = document.getElementById('toggle-bg');

    if (!btnProtocol || !btnOneTime) return;

    btnProtocol.onclick = () => {
        contentProtocol.classList.remove('hidden');
        contentProtocol.style.opacity = '1';
        contentOneTime.classList.add('hidden');
        contentOneTime.style.opacity = '0';
        btnProtocol.style.color = '#ffffff';
        btnOneTime.style.color = '#94a3b8';
        if (toggleBg) toggleBg.style.transform = 'translateX(0)';
    };

    btnOneTime.onclick = () => {
        contentOneTime.classList.remove('hidden');
        contentOneTime.style.opacity = '1';
        contentProtocol.classList.add('hidden');
        contentProtocol.style.opacity = '0';
        btnOneTime.style.color = '#ffffff';
        btnProtocol.style.color = '#94a3b8';
        if (toggleBg) toggleBg.style.transform = 'translateX(100%)';
    };
}

// Checkout
function initCheckout() {
    let currentStep = 1;

    const cardNumberEl = document.getElementById('card-number');
    const expiryDateEl = document.getElementById('expiry-date');
    const cvcEl = document.getElementById('cvc');

    if (cardNumberEl && typeof IMask !== 'undefined') IMask(cardNumberEl, { mask: '0000 0000 0000 0000' });
    if (expiryDateEl && typeof IMask !== 'undefined') IMask(expiryDateEl, { mask: 'MM / YY' });
    if (cvcEl && typeof IMask !== 'undefined') IMask(cvcEl, { mask: '0000' });

    document.getElementById('to-step-2').onclick = () => {
        const requiredFields = document.getElementById('step-1').querySelectorAll('[required]');
        let valid = true;
        requiredFields.forEach(f => {
            if (!f.value) {
                f.classList.add('border-red-500');
                valid = false;
            } else {
                f.classList.remove('border-red-500');
            }
        });

        if (!valid) return;

        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
        currentStep = 2;
        updateSteps();
    };

    document.getElementById('to-step-3').onclick = () => {
        const requiredFields = document.getElementById('step-2').querySelectorAll('[required]');
        let valid = true;
        requiredFields.forEach(f => {
            if (!f.value) {
                f.classList.add('border-red-500');
                valid = false;
            } else {
                f.classList.remove('border-red-500');
            }
        });

        if (!valid) return;

        updateConfirmationSummary();

        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.remove('hidden');
        currentStep = 3;
        updateSteps();
    };

    document.getElementById('back-to-step-1').onclick = () => {
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-1').classList.remove('hidden');
        currentStep = 1;
        updateSteps();
    };

    document.getElementById('back-to-step-2').onclick = () => {
        document.getElementById('step-3').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
        currentStep = 2;
        updateSteps();
    };

    function updateSteps() {
        for (let i = 1; i <= 3; i++) {
            const el = document.getElementById(`step-${i}-indicator`);
            if (el) {
                el.classList.toggle('step-active', i === currentStep);
                el.classList.toggle('step-inactive', i !== currentStep);
            }
        }
    }

    document.getElementById('checkout-form').onsubmit = (e) => {
        e.preventDefault();

        const ageVerified = document.getElementById('ageVerified').checked;
        const legalAccepted = document.getElementById('legalAccepted').checked;

        if (!ageVerified || !legalAccepted) {
            alert('Please verify your age and accept the terms to continue.');
            return;
        }

        const btn = document.getElementById('confirm-purchase');
        btn.disabled = true;
        btn.querySelector('.spinner').classList.remove('hidden');
        btn.querySelector('.btn-text').classList.add('hidden');

        setTimeout(() => {
            document.getElementById('checkout-container').classList.add('hidden');
            document.getElementById('success-message').classList.remove('hidden');
            cart = [];
            saveCart();
            renderCart();
        }, 1500);
    };

    document.getElementById('reset-checkout-btn').onclick = () => {
        location.reload();
    };

    function updateConfirmationSummary() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const shippingState = document.getElementById('state').value;
        const taxRate = stateTaxRates[shippingState] || 0;
        const tax = Math.round(subtotal * taxRate * 100) / 100;
        const total = subtotal + tax;

        const orderItems = cart.map(item =>
            `${item.name} \u00d7 ${item.quantity}: $${(item.price * item.quantity).toFixed(2)}`
        ).join('<br>');

        document.getElementById('confirm-summary-text').innerHTML = `
            <p class="font-semibold mb-2">Order Items:</p>
            <p class="text-sm mb-4">${orderItems}</p>
            <p>Subtotal: $${subtotal.toFixed(2)}</p>
            <p>Tax (${(taxRate * 100).toFixed(2)}%): $${tax.toFixed(2)}</p>
            <p>Shipping: FREE</p>
            <p class="font-bold text-white mt-2">Total: $${total.toFixed(2)}</p>
        `;

        // Shipping address
        const firstName = document.getElementById('first-name').value;
        const lastName = document.getElementById('last-name').value;
        const address = document.getElementById('address').value;
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const zip = document.getElementById('zip').value;

        document.getElementById('confirm-shipping-address').innerHTML = `
            <p class="font-semibold">Shipping Address:</p>
            <p>${firstName} ${lastName}</p>
            <p>${address}</p>
            <p>${city}, ${state} ${zip}</p>
        `;

        // Billing address
        const sameBilling = document.getElementById('same-billing').checked;
        const billingSection = document.getElementById('confirm-billing-address');

        if (sameBilling) {
            billingSection.classList.add('hidden');
        } else {
            billingSection.classList.remove('hidden');
            const billingName = document.getElementById('billing-name').value;
            const billingAddr = document.getElementById('billing-address').value;
            const billingCity = document.getElementById('billing-city').value;
            const billingState = document.getElementById('billing-state').value;
            const billingZip = document.getElementById('billing-zip').value;

            billingSection.innerHTML = `
                <p class="font-semibold">Billing Address:</p>
                <p>${billingName}</p>
                <p>${billingAddr}</p>
                <p>${billingCity}, ${billingState} ${billingZip}</p>
            `;
        }
    }
}

// FAQ Accordion
function initFaqAccordion() {
    const faqContainer = document.getElementById('faq-container');
    if (!faqContainer) return;

    faqContainer.addEventListener('click', (e) => {
        const questionButton = e.target.closest('.faq-question');
        if (!questionButton) return;

        const answer = document.getElementById(questionButton.getAttribute('aria-controls'));
        const isExpanded = questionButton.getAttribute('aria-expanded') === 'true';

        faqContainer.querySelectorAll('.faq-question').forEach(btn => {
            if (btn !== questionButton) {
                btn.setAttribute('aria-expanded', 'false');
                btn.nextElementSibling.classList.add('hidden');
                btn.querySelector('.faq-arrow').classList.remove('rotate-180');
            }
        });

        questionButton.setAttribute('aria-expanded', !isExpanded);
        answer.classList.toggle('hidden');
        questionButton.querySelector('.faq-arrow').classList.toggle('rotate-180');
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadCart();

    // Mobile Menu
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.onclick = () => mobileMenu.classList.toggle('hidden');
    }

    // Video Rotator
    initVideoRotator();

    // Reviews
    initReviews();

    // Pricing Toggle
    initPricingToggle();

    // Checkout
    initCheckout();

    // FAQ
    initFaqAccordion();

    // Update tax when state changes
    document.getElementById('state')?.addEventListener('change', updateCheckoutSummary);

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Header scroll effect
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 100) {
                header.style.background = 'rgba(10, 5, 20, 0.98)';
            } else {
                header.style.background = 'rgba(10, 5, 20, 0.95)';
            }
        });
    }
});
