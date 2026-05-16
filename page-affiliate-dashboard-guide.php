<?php
/**
 * Template Name: Affiliate Dashboard Guide
 *
 * A comprehensive visual guide to the microDOS(2) affiliate dashboard.
 * Teaches affiliates what every section does and how to use it.
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main">

<style>
/* ============================================
   AFFILIATE DASHBOARD GUIDE — STYLES
   ============================================ */
.mcd-guide {
    background: #0a0514;
    min-height: 100vh;
    color: #d1d5db;
    font-family: inherit;
}
.mcd-guide__container {
    max-width: 960px;
    margin: 0 auto;
    padding: 40px 24px 80px;
}
.mcd-guide__header {
    text-align: center;
    padding: 40px 0 32px;
    border-bottom: 1px solid #1f2b47;
    margin-bottom: 40px;
}
.mcd-guide__header h1 {
    color: #fff;
    font-size: 32px;
    font-weight: 800;
    margin: 0 0 12px;
    letter-spacing: -0.02em;
}
.mcd-guide__header h1 span.micro { color: #44f80c; }
.mcd-guide__header h1 span.dos   { color: #9a02d0; }
.mcd-guide__header h1 span.two   { color: #ff66c4; }
.mcd-guide__subtitle {
    color: #94a3b8;
    font-size: 16px;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

/* Section styling */
.mcd-guide__section {
    margin-bottom: 56px;
    scroll-margin-top: 24px;
}
.mcd-guide__section-title {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    margin: 0 0 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.mcd-guide__section-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #44f80c;
    color: #0a0514;
    font-size: 14px;
    font-weight: 800;
    flex-shrink: 0;
}
.mcd-guide__section-desc {
    color: #94a3b8;
    font-size: 15px;
    line-height: 1.6;
    margin: 0 0 20px;
    padding-left: 42px;
}

/* Mockup frame */
.mcd-guide__mockup {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.mcd-guide__mockup img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    display: block;
}
.mcd-guide__mockup-caption {
    text-align: center;
    color: #64748b;
    font-size: 12px;
    margin-top: 10px;
    font-style: italic;
}

/* Explanation text */
.mcd-guide__explanation {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    padding: 20px 24px;
    margin-bottom: 16px;
}
.mcd-guide__explanation p {
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.7;
    margin: 0 0 12px;
}
.mcd-guide__explanation p:last-child { margin-bottom: 0; }
.mcd-guide__explanation strong {
    color: #e2e8f0;
    font-weight: 600;
}
.mcd-guide__explanation ul {
    margin: 8px 0;
    padding-left: 20px;
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.7;
}
.mcd-guide__explanation li { margin-bottom: 6px; }
.mcd-guide__explanation code {
    background: rgba(68,248,12,0.08);
    color: #44f80c;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 13px;
    font-family: monospace;
}

/* Tip boxes */
.mcd-guide__tip {
    background: rgba(68,248,12,0.05);
    border-left: 3px solid #44f80c;
    border-radius: 0 8px 8px 0;
    padding: 14px 18px;
    margin: 14px 0;
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.6;
}
.mcd-guide__tip strong {
    color: #44f80c;
}
.mcd-guide__tip--warning {
    background: rgba(255,170,0,0.05);
    border-left-color: #ffaa00;
}
.mcd-guide__tip--warning strong { color: #ffaa00; }
.mcd-guide__tip--info {
    background: rgba(56,189,248,0.05);
    border-left-color: #38bdf8;
}
.mcd-guide__tip--info strong { color: #38bdf8; }

/* Navigation between sections */
.mcd-guide__nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #1f2b47;
}
.mcd-guide__nav a {
    color: #44f80c;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity 0.2s;
}
.mcd-guide__nav a:hover { opacity: 0.8; }

/* Flow diagram for referral statuses */
.mcd-guide__flow {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin: 20px 0;
    padding: 20px;
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    justify-content: center;
}
.mcd-guide__flow-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
    min-width: 90px;
    text-align: center;
}
.mcd-guide__flow-step .label {
    font-size: 11px;
    color: #64748b;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.mcd-guide__flow-step .value {
    font-weight: 700;
    color: #e2e8f0;
}
.mcd-guide__flow-step--visit { background: rgba(56,189,248,0.1); border: 1px solid rgba(56,189,248,0.3); }
.mcd-guide__flow-step--visit .value { color: #38bdf8; }
.mcd-guide__flow-step--cookie { background: rgba(154,2,208,0.1); border: 1px solid rgba(154,2,208,0.3); }
.mcd-guide__flow-step--cookie .value { color: #c084fc; }
.mcd-guide__flow-step--purchase { background: rgba(255,102,196,0.1); border: 1px solid rgba(255,102,196,0.3); }
.mcd-guide__flow-step--purchase .value { color: #ff66c4; }
.mcd-guide__flow-step--pending { background: rgba(255,170,0,0.1); border: 1px solid rgba(255,170,0,0.3); }
.mcd-guide__flow-step--pending .value { color: #ffaa00; }
.mcd-guide__flow-step--unpaid { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); }
.mcd-guide__flow-step--unpaid .value { color: #60a5fa; }
.mcd-guide__flow-step--paid { background: rgba(68,248,12,0.1); border: 1px solid rgba(68,248,12,0.3); }
.mcd-guide__flow-step--paid .value { color: #44f80c; }
.mcd-guide__flow-arrow {
    color: #475569;
    font-size: 18px;
    font-weight: 700;
}

/* Status badges key */
.mcd-guide__badges {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin: 16px 0;
}
.mcd-guide__badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
}
.mcd-guide__badge--pending { background: rgba(255,170,0,0.15); color: #ffaa00; }
.mcd-guide__badge--unpaid { background: rgba(59,130,246,0.15); color: #60a5fa; }
.mcd-guide__badge--paid { background: rgba(68,248,12,0.15); color: #44f80c; }
.mcd-guide__badge--rejected { background: rgba(239,68,68,0.15); color: #ef4444; }

/* FAQ Accordion */
.mcd-guide__faq-item {
    border: 1px solid #1f2b47;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
    background: #150f24;
}
.mcd-guide__faq-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    cursor: pointer;
    color: #e2e8f0;
    font-size: 15px;
    font-weight: 600;
    transition: background 0.2s;
    user-select: none;
}
.mcd-guide__faq-toggle:hover { background: rgba(68,248,12,0.04); }
.mcd-guide__faq-toggle::after {
    content: '+';
    font-size: 20px;
    color: #44f80c;
    font-weight: 400;
    transition: transform 0.2s;
}
.mcd-guide__faq-item.active .mcd-guide__faq-toggle::after {
    content: '−';
}
.mcd-guide__faq-content {
    display: none;
    padding: 0 20px 16px;
    color: #94a3b8;
    font-size: 14px;
    line-height: 1.7;
}
.mcd-guide__faq-item.active .mcd-guide__faq-content {
    display: block;
}
.mcd-guide__faq-content p { margin: 0 0 10px; }
.mcd-guide__faq-content p:last-child { margin-bottom: 0; }

/* Back to dashboard CTA */
.mcd-guide__cta {
    text-align: center;
    padding: 40px;
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 12px;
    margin-top: 40px;
}
.mcd-guide__cta h3 {
    color: #fff;
    font-size: 20px;
    margin: 0 0 12px;
}
.mcd-guide__cta p {
    color: #94a3b8;
    font-size: 15px;
    margin: 0 0 20px;
}
.mcd-guide__btn {
    display: inline-block;
    padding: 14px 32px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    transition: opacity 0.2s;
}
.mcd-guide__btn:hover { opacity: 0.85; }
.mcd-guide__btn--primary {
    background: #44f80c;
    color: #0a0514;
}
.mcd-guide__btn--secondary {
    background: #ff66c4;
    color: #fff;
    margin-left: 12px;
}

/* Table of contents */
.mcd-guide__toc {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    padding: 24px;
    margin-bottom: 40px;
}
.mcd-guide__toc h3 {
    color: #fff;
    font-size: 16px;
    margin: 0 0 16px;
}
.mcd-guide__toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    columns: 2;
    column-gap: 24px;
}
.mcd-guide__toc-list li {
    margin-bottom: 8px;
    break-inside: avoid;
}
.mcd-guide__toc-list a {
    color: #94a3b8;
    text-decoration: none;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.2s;
}
.mcd-guide__toc-list a:hover { color: #44f80c; }
.mcd-guide__toc-list a .num {
    color: #44f80c;
    font-weight: 700;
    font-size: 12px;
    min-width: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .mcd-guide__header h1 { font-size: 24px; }
    .mcd-guide__section-title { font-size: 18px; }
    .mcd-guide__section-desc { padding-left: 0; }
    .mcd-guide__toc-list { columns: 1; }
    .mcd-guide__flow { flex-direction: column; }
    .mcd-guide__flow-arrow { transform: rotate(90deg); }
    .mcd-guide__nav { flex-direction: column; gap: 12px; }
    .mcd-guide__btn--secondary { margin-left: 0; margin-top: 10px; }
    .mcd-guide__cta { padding: 24px; }
    .mcd-guide__badges { flex-direction: column; }
}
/* Numbered legend grid */
.mcd-guide__number-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin: 20px 0;
}
@media (min-width: 768px) {
    .mcd-guide__number-grid {
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
}
.mcd-guide__number-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 16px;
    background: rgba(68, 248, 12, 0.05);
    border: 1px solid rgba(68, 248, 12, 0.15);
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
    color: #a0b3d6;
}
.mcd-guide__number-circle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    min-width: 28px;
    background: #ff6b35;
    color: white;
    font-weight: 700;
    font-size: 14px;
    border-radius: 50%;
    border: 2px solid white;
}
.mcd-guide__number-item strong {
    color: #e2e8f0;
}
</style>

<div class="mcd-guide">
    <div class="mcd-guide__container">

        <!-- ========== HEADER ========== -->
        <div class="mcd-guide__header">
            <h1>
                <span class="micro">micro</span><span class="dos">DOS</span><span class="two">(2)</span>
                Affiliate Dashboard Guide
            </h1>
            <p class="mcd-guide__subtitle">
                Everything your dashboard does — explained visually. 
                No guesswork, no support tickets. Learn how to read your stats, 
                track your referrals, and get paid.
            </p>
        </div>

        <!-- ========== TABLE OF CONTENTS ========== -->
        <div class="mcd-guide__toc">
            <h3>What You Will Learn</h3>
            <ul class="mcd-guide__toc-list">
                <li><a href="#section-overview"><span class="num">1.</span> Dashboard Overview</a></li>
                <li><a href="#section-urls"><span class="num">2.</span> Your Referral URL</a></li>
                <li><a href="#section-stats"><span class="num">3.</span> Reading Statistics</a></li>
                <li><a href="#section-graphs"><span class="num">4.</span> Understanding Graphs</a></li>
                <li><a href="#section-referrals"><span class="num">5.</span> Referral Statuses</a></li>
                <li><a href="#section-visits"><span class="num">6.</span> Tracking Visits</a></li>
                <li><a href="#section-creatives"><span class="num">7.</span> Using Creatives</a></li>
                <li><a href="#section-payouts"><span class="num">8.</span> Getting Paid</a></li>
                <li><a href="#section-settings"><span class="num">9.</span> Account Settings</a></li>
                <li><a href="#section-faq"><span class="num">10.</span> Quick FAQ</a></li>
            </ul>
        </div>

        <!-- ========== SECTION 1: DASHBOARD OVERVIEW ========== -->
        <section class="mcd-guide__section" id="section-overview">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">1</span>
                Dashboard Overview
            </h2>
            <p class="mcd-guide__section-desc">Your home base. This is what you see every time you log in.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-dashboard-overview.jpg" alt="Dashboard overview screenshot with numbered callouts">
                <p class="mcd-guide__mockup-caption">Your dashboard at a glance — every section labeled</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>When you log into your affiliate dashboard, you land on the <strong>Dashboard</strong> tab. This is your command center. Every other tab feeds data into what you see here.</p>
                <p>Here is what each number on the screenshot means:</p>
                <div class="mcd-guide__number-grid">
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">1</span>
                        <strong>Unpaid Earnings</strong> — Total commission you have earned but not yet been paid. Becomes payable on the 1st of the month if you hit the $50 minimum.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">2</span>
                        <strong>Paid Referrals</strong> — Number of confirmed purchases that have been paid out to you.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">3</span>
                        <strong>Unpaid Referrals</strong> — Confirmed purchases waiting for the next payout cycle.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">4</span>
                        <strong>Conversion Rate</strong> — Percentage of clicks that turned into purchases. Above 3% means your audience is highly engaged.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">5</span>
                        <strong>Navigation Tabs</strong> — Click any tab to drill into detailed data. Dashboard (home), Affiliate URLs (your links), Statistics (numbers), Graphs (charts), Referrals (transactions), Visits (clicks), Payouts (payments), Settings (profile).
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">6</span>
                        <strong>Your Referral URL</strong> — Your unique tracking link. When someone clicks it and buys, you get credit. The 45-day cookie is set automatically.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">7</span>
                        <strong>Copy Button</strong> — One-click copy your referral link to clipboard. Paste it anywhere — social media, email, blog posts.
                    </div>
                    <div class="mcd-guide__number-item">
                        <span class="mcd-guide__number-circle">8</span>
                        <strong>QR Code</strong> — Generate a scannable QR code for in-person sharing. Download it for business cards, flyers, posters, or events.
                    </div>
                </div>
            </div>

            <div class="mcd-guide__tip">
                <strong>Pro tip:</strong> Bookmark your dashboard page so you can check stats in one click. The URL never changes.
            </div>

            <div class="mcd-guide__nav">
                <span></span>
                <a href="#section-urls">Next: Your Referral URL →</a>
            </div>
        </section>

        <!-- ========== SECTION 2: YOUR REFERRAL URL ========== -->
        <section class="mcd-guide__section" id="section-urls">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">2</span>
                Your Referral URL
            </h2>
            <p class="mcd-guide__section-desc">This is the link that earns you money. Learn how to find it, copy it, and create custom links.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-affiliate-urls.jpg" alt="Affiliate URLs tab screenshot">
                <p class="mcd-guide__mockup-caption">The Affiliate URLs tab — your link toolkit</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Affiliate URLs</strong> tab to access your referral link tools. This tab is your starting point for every share.</p>
                <p><strong>Your default referral link</strong> looks like <code>https://microdos4u.com/ref/johndoe</code> or <code>https://microdos4u.com/?ref=123</code>. This link contains a tracking cookie that lasts <strong>45 days</strong>. If someone clicks and buys within 45 days, you get the commission.</p>
                <p><strong>Social sharing buttons</strong> let you share directly to Facebook, X/Twitter, LinkedIn, or Email with one click. Your referral link is automatically included.</p>
                <p><strong>QR Code:</strong> Click the QR Code button to generate a scannable code. Download it and use on business cards, flyers, posters, or in-person events. Anyone who scans it visits your referral link.</p>
                <p><strong>Generate Custom URL:</strong> Want to link directly to a product page? Paste any URL on your site (like <code>/product/microdos2</code>) and click Generate. You will get a custom referral link that sends people directly to that page — with your tracking still attached.</p>
            </div>

            <div class="mcd-guide__tip">
                <strong>Pro tip:</strong> Create a custom URL for the product page, not just the homepage. Direct product links convert 2-3x better because visitors land exactly where they expect.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-overview">← Dashboard Overview</a>
                <a href="#section-stats">Next: Reading Statistics →</a>
            </div>
        </section>

        <!-- ========== SECTION 3: READING STATISTICS ========== -->
        <section class="mcd-guide__section" id="section-stats">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">3</span>
                Reading Statistics
            </h2>
            <p class="mcd-guide__section-desc">Your numbers, decoded. Learn what each stat means and where it comes from.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-statistics.jpg" alt="Statistics tab screenshot">
                <p class="mcd-guide__mockup-caption">The Statistics tab — your performance in numbers</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Statistics</strong> tab for a detailed breakdown of your performance. This is where the story behind your earnings lives.</p>
                <p><strong>Total Earnings:</strong> Every dollar you have ever earned — paid and unpaid combined. This is your lifetime number.</p>
                <p><strong>Paid Earnings:</strong> Money that has already been sent to you. This matches your PayPal or bank records.</p>
                <p><strong>Unpaid Earnings:</strong> Money you have earned but has not been paid yet. These become payable on the 1st of the month if you have met the $50 minimum.</p>
                <p><strong>Total Referrals:</strong> Every purchase made through your link — paid, unpaid, pending, and rejected combined.</p>
                <p><strong>Paid Referrals:</strong> Purchases that have been confirmed and paid out to you.</p>
                <p><strong>Unpaid Referrals:</strong> Confirmed purchases that are waiting for the next payout cycle.</p>
                <p><strong>Conversion Rate:</strong> The percentage of clicks that turn into purchases. Industry average is 1-3%. Above 3% means your audience is highly engaged.</p>
            </div>

            <div class="mcd-guide__tip">
                <strong>Pro tip:</strong> If your conversion rate is below 1%, try sharing your link with a more targeted audience or adding a personal recommendation. Generic links convert far worse than endorsed ones.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-urls">← Your Referral URL</a>
                <a href="#section-graphs">Next: Understanding Graphs →</a>
            </div>
        </section>

        <!-- ========== SECTION 4: UNDERSTANDING GRAPHS ========== -->
        <section class="mcd-guide__section" id="section-graphs">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">4</span>
                Understanding Graphs
            </h2>
            <p class="mcd-guide__section-desc">Visualize your growth. Learn to read the charts and spot trends.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-graphs.jpg" alt="Graphs tab screenshot">
                <p class="mcd-guide__mockup-caption">The Graphs tab — watch your earnings grow visually</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Graphs</strong> tab to see your performance over time. Two charts are displayed side by side.</p>
                <p><strong>Earnings Over Time (left chart):</strong> A line graph showing your daily or cumulative earnings. The green line trending upward means you are growing. Flat periods are normal — they just mean no sales happened during those days.</p>
                <p><strong>Referrals Over Time (right chart):</strong> A line graph showing how many purchases came through your link each day. Spikes usually happen right after you post on social media. Use this to see which platforms drive the most sales.</p>
                <p><strong>Time period filters:</strong> Click Today, Yesterday, This Week, Last 7 Days, This Month, or Last 30 Days to change the date range. Start with "This Month" for the best overview.</p>
            </div>

            <div class="mcd-guide__tip--warning">
                <strong>Important:</strong> Your graphs will be empty when you first start. That is completely normal. After your first referral, you will see data appear. Most affiliates see their first graph entries within 1-3 days of sharing their link.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-stats">← Reading Statistics</a>
                <a href="#section-referrals">Next: Referral Statuses →</a>
            </div>
        </section>

        <!-- ========== SECTION 5: REFERRAL STATUSES ========== -->
        <section class="mcd-guide__section" id="section-referrals">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">5</span>
                Referral Statuses
            </h2>
            <p class="mcd-guide__section-desc">Understand the lifecycle of every referral — from click to payday.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-referrals.jpg" alt="Referrals tab screenshot">
                <p class="mcd-guide__mockup-caption">The Referrals tab — every sale tracked with a status</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Referrals</strong> tab to see every purchase made through your link. Each row is a single transaction. The status tells you exactly where it is in the payment pipeline.</p>

                <div class="mcd-guide__badges">
                    <span class="mcd-guide__badge mcd-guide__badge--pending">● Pending</span>
                    <span class="mcd-guide__badge mcd-guide__badge--unpaid">● Unpaid</span>
                    <span class="mcd-guide__badge mcd-guide__badge--paid">● Paid</span>
                    <span class="mcd-guide__badge mcd-guide__badge--rejected">● Rejected</span>
                </div>

                <p><strong>Pending:</strong> The customer placed an order, but it is still being processed. This usually means the payment is being verified or the product is in a holding period. Most orders move from Pending to Unpaid within 24-48 hours.</p>
                <p><strong>Unpaid:</strong> The order is confirmed and your commission is earned, but it has not been paid out yet. All Unpaid referrals are batched together and paid on the 1st of the month (if you have reached the $50 minimum).</p>
                <p><strong>Paid:</strong> The money has been sent to your PayPal or bank account. Check your payment method to confirm receipt.</p>
                <p><strong>Rejected:</strong> The order was refunded, cancelled, or flagged as fraudulent. You do not earn commission on rejected referrals. This is rare — most legitimate referrals are approved.</p>
            </div>

            <!-- Referral Flow Diagram -->
            <div class="mcd-guide__explanation">
                <p><strong>The full journey:</strong> Here is what happens from the moment someone clicks your link until you get paid.</p>
                <div class="mcd-guide__flow">
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--visit">
                        <span class="label">Step 1</span>
                        <span class="value">Click</span>
                    </div>
                    <span class="mcd-guide__flow-arrow">→</span>
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--cookie">
                        <span class="label">Step 2</span>
                        <span class="value">Cookie Set</span>
                    </div>
                    <span class="mcd-guide__flow-arrow">→</span>
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--purchase">
                        <span class="label">Step 3</span>
                        <span class="value">Purchase</span>
                    </div>
                    <span class="mcd-guide__flow-arrow">→</span>
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--pending">
                        <span class="label">Step 4</span>
                        <span class="value">Pending</span>
                    </div>
                    <span class="mcd-guide__flow-arrow">→</span>
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--unpaid">
                        <span class="label">Step 5</span>
                        <span class="value">Unpaid</span>
                    </div>
                    <span class="mcd-guide__flow-arrow">→</span>
                    <div class="mcd-guide__flow-step mcd-guide__flow-step--paid">
                        <span class="label">Step 6</span>
                        <span class="value">Paid</span>
                    </div>
                </div>
                <p style="text-align:center; font-size:13px; color:#64748b; margin-top:4px;">45-day cookie · 24-48h review · Monthly payout on the 1st</p>
            </div>

            <div class="mcd-guide__tip--info">
                <strong>Note:</strong> A referral can only be tracked if the customer clicks your link <em>before</em> purchasing. If they visit the site directly without clicking your link first, the sale cannot be attributed to you.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-graphs">← Understanding Graphs</a>
                <a href="#section-visits">Next: Tracking Visits →</a>
            </div>
        </section>

        <!-- ========== SECTION 6: TRACKING VISITS ========== -->
        <section class="mcd-guide__section" id="section-visits">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">6</span>
                Tracking Visits
            </h2>
            <p class="mcd-guide__section-desc">See who is clicking your link and where they are coming from.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-visits.jpg" alt="Visits tab screenshot">
                <p class="mcd-guide__mockup-caption">The Visits tab — every click tracked in detail</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Visits</strong> tab to see every click on your referral link. This helps you understand which platforms and posts are driving traffic.</p>
                <p><strong>Total Visits:</strong> The total number of times your link has been clicked. This includes repeat clicks from the same person.</p>
                <p><strong>Unique Visitors:</strong> The number of different people who clicked your link. This is always lower than Total Visits because some people click multiple times.</p>
                <p><strong>Converted:</strong> How many clicks turned into actual purchases. The ratio of Converted to Total Visits is your conversion rate.</p>
                <p><strong>Landing Page:</strong> Which page on the site the visitor landed on (usually the homepage or product page).</p>
                <p><strong>Referring URL:</strong> Where the click came from — Instagram, X/Twitter, Facebook, email, or "direct" (typed in manually).</p>
                <p>Use this data to optimize: if Instagram drives 80% of your clicks, double down there. If a platform sends lots of clicks but zero conversions, your audience there might not be a good fit.</p>
            </div>

            <div class="mcd-guide__tip">
                <strong>Pro tip:</strong> Check your Visits tab 24 hours after posting. If you see zero visits, your post might not have been seen. Try a different time of day or a different platform.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-referrals">← Referral Statuses</a>
                <a href="#section-creatives">Next: Using Creatives →</a>
            </div>
        </section>

        <!-- ========== SECTION 7: USING CREATIVES ========== -->
        <section class="mcd-guide__section" id="section-creatives">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">7</span>
                Using Creatives
            </h2>
            <p class="mcd-guide__section-desc">Pre-made banners and ads — ready to share with your link already built in.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-creatives.jpg" alt="Creatives tab screenshot">
                <p class="mcd-guide__mockup-caption">The Creatives tab — marketing materials ready to go</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Creatives</strong> tab to access pre-made marketing materials. These are banners, images, and text links provided by the microDOS(2) team — with your referral link <em>already embedded</em>.</p>
                <p><strong>Image Banners:</strong> Visual ads in standard sizes (300x250, 728x90, 400x400). Click "View" to see the full image, then save it to your device. Upload it directly to Instagram, Facebook, or your blog. Your referral link is already part of the image destination.</p>
                <p><strong>Text Links:</strong> Pre-written text ads with your referral link attached. Click "Copy Link" to copy the HTML or plain text, then paste it into emails, social posts, or website sidebars.</p>
                <p><strong>Type badges:</strong> Each card has a badge showing "Image Banner" or "Text Link" so you know what you are getting before you click.</p>
                <p><strong>Copy feedback:</strong> When you click "Copy Link," a green confirmation message appears telling you the code was copied. Just paste it where you want it.</p>
            </div>

            <div class="mcd-guide__tip">
                <strong>Pro tip:</strong> Use creatives as a starting point, but add your own personal sentence about why you recommend the product. Personal recommendations with a creative image convert 3-5x better than the creative alone.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-visits">← Tracking Visits</a>
                <a href="#section-payouts">Next: Getting Paid →</a>
            </div>
        </section>

        <!-- ========== SECTION 8: GETTING PAID ========== -->
        <section class="mcd-guide__section" id="section-payouts">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">8</span>
                Getting Paid
            </h2>
            <p class="mcd-guide__section-desc">How, when, and how much. Everything about commission payouts.</p>

            <div class="mcd-guide__mockup">
                <img src="<?php echo get_template_directory_uri(); ?>/images/mockups/mockup-payouts.jpg" alt="Payouts tab screenshot">
                <p class="mcd-guide__mockup-caption">The Payouts tab — your payment history and next estimate</p>
            </div>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Payouts</strong> tab to see your payment history and estimated next payout. This is where money becomes real.</p>
                <p><strong>Total Paid:</strong> Every dollar that has been sent to your PayPal or bank account. This should match your payment records.</p>
                <p><strong>Next Payout Estimate:</strong> How much you are on track to receive in the next payout cycle. This is the sum of all your "Unpaid" referrals. It updates in real time as new sales come in.</p>
                <p><strong>Payout schedule:</strong> Commissions are paid on the <strong>1st of every month</strong>, automatically. There is no need to request a payout.</p>
                <p><strong>Minimum threshold:</strong> You must have at least <strong>$50</strong> in unpaid earnings to trigger a payout. If you have less than $50, the balance rolls over to the next month.</p>
                <p><strong>Payment methods:</strong> Payouts are sent via PayPal to the email address in your Settings tab. Make sure your payment email is correct.</p>
                <p><strong>W-9 Requirement:</strong> US-based affiliates must submit a completed W-9 form before receiving payouts. You will see an alert on your dashboard if this is needed. <a href="/affiliate-w9" style="color:#44f80c;">Submit your W-9 here →</a></p>
            </div>

            <div class="mcd-guide__tip--warning">
                <strong>Important:</strong> If your W-9 is not submitted, your payouts will be held until it is completed. This is a US tax law requirement for all affiliates earning $600+ per year.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-creatives">← Using Creatives</a>
                <a href="#section-settings">Next: Account Settings →</a>
            </div>
        </section>

        <!-- ========== SECTION 9: ACCOUNT SETTINGS ========== -->
        <section class="mcd-guide__section" id="section-settings">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">9</span>
                Account Settings
            </h2>
            <p class="mcd-guide__section-desc">Keep your payment info and profile up to date.</p>

            <div class="mcd-guide__explanation">
                <p>Click the <strong>Settings</strong> tab to manage your affiliate profile and payment information.</p>
                <p><strong>Payment Email:</strong> This is where your payouts are sent. It defaults to your WordPress account email, but you can change it here if you want commissions sent to a different PayPal address. <strong>Always verify this is correct</strong> — if it is wrong, your payments will bounce.</p>
                <p><strong>Name and Profile:</strong> Update your display name and contact info. This does not affect your referral link or tracking.</p>
                <p><strong>Change Password:</strong> Update your login password from this section.</p>
            </div>

            <div class="mcd-guide__tip--warning">
                <strong>Before your first payout:</strong> Double-check that your payment email matches your PayPal account. A typo here means your payment will fail and you will have to wait until the next cycle.
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-payouts">← Getting Paid</a>
                <a href="#section-faq">Next: Quick FAQ →</a>
            </div>
        </section>

        <!-- ========== SECTION 10: QUICK FAQ ========== -->
        <section class="mcd-guide__section" id="section-faq">
            <h2 class="mcd-guide__section-title">
                <span class="mcd-guide__section-number">10</span>
                Quick FAQ
            </h2>
            <p class="mcd-guide__section-desc">The most common questions — answered fast.</p>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Why are all my stats at zero?</div>
                <div class="mcd-guide__faq-content">
                    <p>That is completely normal when you first start. Your stats only populate after you share your link and people begin clicking and purchasing. Most affiliates see their first visits within 24 hours of sharing, and their first referral within 1-7 days. Your numbers will grow as you share consistently.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">What does "Pending" mean on my referral?</div>
                <div class="mcd-guide__faq-content">
                    <p>Pending means the order was placed but is still being processed. This usually takes 24-48 hours while payment is verified. Once confirmed, the status changes to "Unpaid." If the order is cancelled or refunded, it becomes "Rejected."</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">When will I get paid?</div>
                <div class="mcd-guide__faq-content">
                    <p>Payouts are sent automatically on the <strong>15th of every month</strong> via PayPal. You must have at least <strong>$50</strong> in unpaid earnings to receive a payout. If you are below $50, your balance rolls over to the next month. Make sure your <a href="#section-settings">payment email</a> is correct and your <a href="/affiliate-w9">W-9 is submitted</a> (US affiliates).</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Why was my referral rejected?</div>
                <div class="mcd-guide__faq-content">
                    <p>Referrals are rejected when the associated order is refunded, cancelled, or flagged as fraudulent. This is rare for legitimate purchases. Rejected referrals do not affect your account standing — they simply mean no commission was earned on that specific transaction.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Can I change my referral link?</div>
                <div class="mcd-guide__faq-content">
                    <p>Your default referral link is based on your username and cannot be changed. However, you can create <strong>custom URLs</strong> in the <a href="#section-urls">Affiliate URLs</a> tab that link to specific product pages. These still track to your account.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">How long does the tracking cookie last?</div>
                <div class="mcd-guide__faq-content">
                    <p>The tracking cookie lasts <strong>45 days</strong>. This means if someone clicks your link and buys anytime within the next 45 days, you get the commission — even if they close the browser and come back later. If they clear their cookies or use a different device, the tracking may not work.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Why is my conversion rate low?</div>
                <div class="mcd-guide__faq-content">
                    <p>A "low" conversion rate depends on context. The industry average for affiliate marketing is 1-3%. Below 1% usually means either: (1) your audience is not a good match for the product, (2) you are not adding a personal recommendation, or (3) you are sharing in places where people are not in a buying mindset. Try sharing with a more targeted audience and always include a personal endorsement.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Do I need to submit a W-9?</div>
                <div class="mcd-guide__faq-content">
                    <p><strong>Yes — if you are a US-based affiliate.</strong> US tax law requires us to collect a W-9 form from all affiliates before we can issue payments totaling $600 or more in a calendar year. You will see a prominent alert on your dashboard if you need to submit one. <a href="/affiliate-w9" style="color:#44f80c;">Submit your W-9 here →</a> International affiliates do not need a W-9 but may need to submit a W-8BEN.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">Can I use my own referral link to buy?</div>
                <div class="mcd-guide__faq-content">
                    <p>No. Self-referrals are against the <a href="/affiliate-terms" style="color:#ff66c4;">Affiliate Terms</a> and will be rejected. The program is designed for referring others, not for personal discounts. Attempted self-referrals may result in account termination.</p>
                </div>
            </div>

            <div class="mcd-guide__faq-item">
                <div class="mcd-guide__faq-toggle">What platforms work best for sharing?</div>
                <div class="mcd-guide__faq-content">
                    <p>It depends on your audience. Instagram and TikTok work well for visual product demos. X/Twitter is great for quick recommendations with personality. Facebook Groups reach niche communities. Email newsletters convert highest because the audience already trusts you. Check your <a href="#section-visits">Visits tab</a> to see which platforms drive the most clicks and conversions for you.</p>
                </div>
            </div>

            <div class="mcd-guide__nav">
                <a href="#section-settings">← Account Settings</a>
                <span></span>
            </div>
        </section>

        <!-- ========== CTA: BACK TO DASHBOARD ========== -->
        <div class="mcd-guide__cta">
            <h3>Ready to Put This Into Action?</h3>
            <p>Your dashboard is waiting. Copy your link, pick a platform, and make your first post.</p>
            <?php if (function_exists('affwp_get_affiliate_area_page_url')) : ?>
                <a href="<?php echo esc_url(affwp_get_affiliate_area_page_url()); ?>" class="mcd-guide__btn mcd-guide__btn--primary">Go to My Dashboard →</a>
            <?php else : ?>
                <a href="/affiliate-area" class="mcd-guide__btn mcd-guide__btn--primary">Go to My Dashboard →</a>
            <?php endif; ?>
            <a href="/marketing-guide" class="mcd-guide__btn mcd-guide__btn--secondary">View Marketing Guide</a>
        </div>

    </div><!-- /container -->
</div><!-- /mcd-guide -->

<script>
// FAQ Accordion
(function() {
    const items = document.querySelectorAll('.mcd-guide__faq-item');
    items.forEach(function(item) {
        const toggle = item.querySelector('.mcd-guide__faq-toggle');
        toggle.addEventListener('click', function() {
            // Close others
            items.forEach(function(other) {
                if (other !== item) other.classList.remove('active');
            });
            // Toggle this one
            item.classList.toggle('active');
        });
    });
})();
</script>

</main>

<?php
get_footer();
