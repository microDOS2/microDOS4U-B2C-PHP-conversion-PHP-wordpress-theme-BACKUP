<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container">
        <div class="site-logo">
            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                <span class="brand-micro">micro</span><span class="brand-dos">DOS</span><span class="brand-two">(2)</span>
            </a>
        </div>
        
        <!-- Navigation will be added per-page template -->
        
        <div class="header-actions">
            <!-- Cart Icon -->
            <button onclick="openCart()" class="relative p-2 text-slate-300 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span id="cart-badge" class="cart-badge hidden">0</span>
            </button>
            
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-md text-slate-400 hover:text-white" aria-label="Toggle Menu" style="background-color: #1a1329 !important;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" x2="20" y1="12" y2="12"></line>
                    <line x1="4" x2="20" y1="6" y2="6"></line>
                    <line x1="4" x2="20" y1="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4 space-y-2" style="background-color: #0a0514 !important;">
        <a href="#pricing" class="block text-slate-300 hover:text-white py-2">Pricing</a>
        <a href="#how-it-works" class="block text-slate-300 hover:text-white py-2">How It Works</a>
        <a href="#reviews" class="block text-slate-300 hover:text-white py-2">Reviews</a>
        <a href="#faq" class="block text-slate-300 hover:text-white py-2">FAQ</a>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="block text-slate-300 hover:text-white py-2">Contact</a>
        <a href="#pricing" class="block mt-4 w-full text-center px-6 py-3 text-white font-semibold rounded-lg shadow-md btn-primary">
            Get Started
        </a>
    </div>
</header>

<main id="main" class="site-main">
