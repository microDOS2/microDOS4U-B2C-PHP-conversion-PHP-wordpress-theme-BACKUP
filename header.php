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
        
        <?php
        $template = get_page_template_slug(get_queried_object_id());
        $is_story = (strpos($template, 'story') !== false);
        ?>
        <?php if (!$is_story) : ?>
        <nav class="main-navigation hidden md:flex items-center space-x-8">
            <?php if (is_front_page() || is_page_template('page-home.php') || (!is_page() && !is_singular())) : ?>
                <a href="#benefits" class="text-slate-300 hover:text-white transition">Benefits</a>
                <a href="#reviews" class="text-slate-300 hover:text-white transition">Reviews</a>
                <a href="#how-it-works" class="text-slate-300 hover:text-white transition">How It Works</a>
                <a href="#pricing" class="text-slate-300 hover:text-white transition">Pricing</a>
                <a href="#faq" class="text-slate-300 hover:text-white transition">FAQ</a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="text-slate-300 hover:text-white transition">Contact</a>
            <?php elseif (is_page('articles-studies') || is_page_template('page-articles.php')) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="<?php echo esc_url(home_url('/user-stories')); ?>" class="text-slate-300 hover:text-white transition">User Experiences</a>
                <a href="#articles" class="text-slate-300 hover:text-white transition">Articles</a>
                <a href="#studies" class="text-slate-300 hover:text-white transition">Studies</a>
            <?php elseif (is_page('dosage-guide') || is_page_template('page-dosage.php')) : ?>
                <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
                <a href="#microdosing" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="#safety" class="text-slate-300 hover:text-white transition">Safety Notes</a>
            <?php elseif (is_page('legal-disclaimer') || is_page_template('page-legal.php')) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-sky-400 hover:text-white transition font-semibold">Back to Home</a>
            <?php elseif (is_page('metocin-info') || is_page_template('page-metocin.php')) : ?>
                <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
                <a href="#safety" class="text-slate-300 hover:text-white transition">Safety Notes</a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
                <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
                <a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="text-slate-300 hover:text-white transition">Metocin Info</a>
                <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
        
        <div class="header-actions">
            <!-- Cart Icon -->
            <?php if (class_exists('WooCommerce')) : ?>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="relative p-2 text-slate-300 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="cart-count" style="display: <?php echo (WC()->cart && WC()->cart->get_cart_contents_count() > 0) ? 'inline-flex' : 'none'; ?>;"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
            </a>
            <?php else : ?>
            <button onclick="openCart()" class="relative p-2 text-slate-300 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span id="cart-badge" class="cart-badge hidden">0</span>
            </button>
            <?php endif; ?>
            
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
