/**
 * microDOS(2) Affiliate Dashboard Tour
 * =====================================
 * An interactive Shepherd.js walkthrough for AffiliateWP.
 * Works with BOTH the Affiliate Portal (sidebar UI) and the old tabbed interface.
 *
 * Features:
 * - Auto-detects Affiliate Portal vs old tabbed interface
 * - Adapts tour steps to match the detected UI
 * - Auto-launches on first visit, stores state in localStorage
 * - Accessible via "Take a Tour" button and floating help icon
 * - Dark themed to match microDOS(2) brand
 *
 * @version 2.0.0
 * @package microDOS4U
 */
(function() {
    'use strict';

    // ============================================
    // CONFIGURATION
    // ============================================
    var CONFIG = {
        STORAGE_KEY_COMPLETED: 'microdos_tour_completed',
        STORAGE_KEY_SKIPPED:   'microdos_tour_skipped',
        STORAGE_KEY_DISMISSED: 'microdos_help_dismissed_at',
        STORAGE_KEY_TOUR_STEP: 'microdos_tour_step',
        AUTO_LAUNCH_DELAY: 1200,
        HELP_BUTTON_HIDE_DAYS: 30,
    };

    // ============================================
    // DETECTION: Portal vs Old Tabbed Interface
    // ============================================
    var _isPortal = null;

    function isPortal() {
        if (_isPortal !== null) return _isPortal;
        _isPortal = !!document.querySelector('.affwp-portal, .affiliate-portal, .affwp-portal-sidebar, [class*="portal-sidebar"]');
        return _isPortal;
    }

    function isOldTabbed() {
        return !!document.querySelector('.affwp-tabs, .affwp-tab-wrapper, .affwp-wrap');
    }

    function isAffiliateDashboard() {
        return isPortal() || isOldTabbed() ||
            window.location.pathname.indexOf('affiliate') !== -1;
    }

    function isMainDashboardTab() {
        // Portal: check if we're on the main/home dashboard (not a sub-page)
        if (isPortal()) {
            // In Portal, check URL hash or path for specific tabs
            var hash = window.location.hash;
            return !hash || hash === '#/' || hash === '';
        }
        // Old tabbed: no ?tab= parameter
        return !window.location.search.match(/[?&]tab=/);
    }

    // ============================================
    // LOCALSTORAGE HELPERS
    // ============================================
    function storageGet(key) {
        try { return localStorage.getItem(key); } catch (e) { return null; }
    }
    function storageSet(key, value) {
        try { localStorage.setItem(key, value); } catch (e) {}
    }
    function storageRemove(key) {
        try { localStorage.removeItem(key); } catch (e) {}
    }
    function shouldAutoLaunch() {
        if (storageGet(CONFIG.STORAGE_KEY_COMPLETED)) return false;
        if (storageGet(CONFIG.STORAGE_KEY_SKIPPED)) return false;
        return true;
    }

    // ============================================
    // FLOATING HELP BUTTON
    // ============================================
    function injectFloatingHelpButton() {
        var dismissedAt = storageGet(CONFIG.STORAGE_KEY_DISMISSED);
        if (dismissedAt) {
            var daysSince = (Date.now() - parseInt(dismissedAt, 10)) / (1000 * 60 * 60 * 24);
            if (daysSince < CONFIG.HELP_BUTTON_HIDE_DAYS) return;
        }
        if (window.location.pathname.indexOf('affiliate-dashboard-guide') !== -1) return;
        if (document.getElementById('microdos-floating-help')) return;

        var wrapper = document.createElement('div');
        wrapper.id = 'microdos-floating-help';
        wrapper.innerHTML =
            '<button id="microdos-help-btn" title="Need Help? Take a tour" aria-label="Need Help? Take a tour">' +
                '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">' +
                    '<circle cx="12" cy="12" r="10"/>' +
                    '<path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>' +
                    '<line x1="12" y1="17" x2="12.01" y2="17"/>' +
                '</svg>' +
            '</button>' +
            '<button id="microdos-help-close" title="Dismiss" aria-label="Dismiss">' +
                '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">' +
                    '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>' +
                '</svg>' +
            '</button>';

        var style = document.createElement('style');
        style.textContent =
            '#microdos-floating-help{position:fixed;bottom:24px;right:24px;z-index:9999;display:flex;align-items:center;gap:8px;}' +
            '#microdos-help-btn{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#44f80c,#3ad60a);color:#0a0514;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(68,248,12,0.3);transition:transform .2s,box-shadow .2s;}' +
            '#microdos-help-btn:hover{transform:scale(1.08);box-shadow:0 6px 24px rgba(68,248,12,0.45);}' +
            '#microdos-help-close{width:24px;height:24px;border-radius:50%;background:rgba(100,116,139,0.2);color:#94a3b8;border:1px solid rgba(100,116,139,0.3);cursor:pointer;display:flex;align-items:center;justify-content:center;opacity:.7;transition:opacity .2s;padding:0;}' +
            '#microdos-help-close:hover{opacity:1;background:rgba(239,68,68,0.15);color:#ef4444;}';

        document.head.appendChild(style);
        document.body.appendChild(wrapper);

        document.getElementById('microdos-help-btn').addEventListener('click', function() {
            launchTour(true);
        });
        document.getElementById('microdos-help-close').addEventListener('click', function(e) {
            e.stopPropagation();
            wrapper.style.opacity = '0';
            wrapper.style.transform = 'translateY(10px)';
            setTimeout(function() { wrapper.remove(); }, 300);
            storageSet(CONFIG.STORAGE_KEY_DISMISSED, Date.now().toString());
        });
    }

    // ============================================
    // SHEPHERD THEME INJECTION
    // ============================================
    function injectShepherdTheme() {
        if (document.getElementById('microdos-shepherd-theme')) return;
        var css =
            '.shepherd-element{background:#150f24!important;border:1px solid #1f2b47!important;border-radius:12px!important;box-shadow:0 16px 48px rgba(0,0,0,0.5)!important;color:#d1d5db!important;max-width:380px!important;}' +
            '.shepherd-text{color:#d1d5db!important;font-size:14px!important;line-height:1.6!important;padding:20px 24px 0!important;}' +
            '.shepherd-text h3{color:#fff!important;font-size:16px!important;font-weight:700!important;margin:0 0 10px!important;}' +
            '.shepherd-text p{margin:0 0 12px!important;}' +
            '.shepherd-text p:last-child{margin-bottom:0!important;}' +
            '.shepherd-footer{padding:16px 24px 20px!important;display:flex;justify-content:space-between;align-items:center;}' +
            '.shepherd-button{padding:8px 18px!important;border-radius:6px!important;font-size:13px!important;font-weight:600!important;cursor:pointer!important;transition:opacity .2s!important;}' +
            '.shepherd-button:hover{opacity:.85!important;}' +
            '.shepherd-button.shepherd-button-primary{background:#44f80c!important;color:#0a0514!important;border:none!important;}' +
            '.shepherd-button:not(.shepherd-button-primary){background:transparent!important;color:#94a3b8!important;border:1px solid #1f2b47!important;}' +
            '.shepherd-button:not(.shepherd-button-primary):hover{background:rgba(68,248,12,.05)!important;color:#44f80c!important;}' +
            '.shepherd-cancel-icon{color:#64748b!important;font-size:20px!important;top:12px!important;right:12px!important;}' +
            '.shepherd-cancel-icon:hover{color:#ef4444!important;}' +
            '.shepherd-arrow::before{background:#150f24!important;border:1px solid #1f2b47!important;}' +
            '.shepherd-has-title .shepherd-content .shepherd-header{background:transparent!important;padding:20px 24px 0!important;}' +
            '.shepherd-title{color:#44f80c!important;font-size:16px!important;font-weight:700!important;}' +
            '.shepherd-progress{color:#64748b!important;font-size:12px!important;margin-right:auto;padding-right:12px;}';
        var style = document.createElement('style');
        style.id = 'microdos-shepherd-theme';
        style.textContent = css;
        document.head.appendChild(style);
    }

    // ============================================
    // TOUR STEP DEFINITIONS
    // ============================================
    function getTourSteps() {
        var guideUrl = window.microDOSPortalData ? window.microDOSPortalData.guideUrl : '/affiliate-dashboard-guide/';

        // Helper: get selector that works for both Portal and old interface
        function sel(portalSel, oldSel) {
            return isPortal() ? portalSel : oldSel;
        }

        // Portal uses sidebar nav items, old interface uses tabs
        var steps = [
            {
                id: 'step-welcome',
                title: 'Welcome to Your Dashboard!',
                text: '<p>This is your affiliate command center. Every stat, chart, and tool you need is here. Let us show you around in <strong>10 quick steps</strong>.</p>',
                attachTo: { element: sel('.affwp-portal-content, .affwp-portal-main, .portal-content', '.affwp-wrap, .affwp-tab-content'), on: 'bottom' },
                buttons: [
                    { text: 'Skip Tour', action: function() { skipTour(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Start Tour →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-referral-url',
                title: 'Your Referral Link',
                text: '<p>This is your money link. Copy it and share it anywhere — social media, email, blog, QR code. When someone clicks and buys within 45 days, you earn <strong>20% commission</strong>.</p>',
                attachTo: { element: sel('.affwp-portal-content .affwp-referral-url, .affwp-portal-content .affwp-url', '.affwp-referral-url, .affwp-url'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-navigation',
                title: 'Dashboard Navigation',
                text: '<p>Use the ' + (isPortal() ? 'sidebar' : 'tabs') + ' to explore different sections:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><strong>Dashboard/Home</strong> — Your stats overview</li>' +
                      '<li><strong>Referral URLs</strong> — Custom links & QR codes</li>' +
                      '<li><strong>Statistics</strong> — Detailed numbers</li>' +
                      '<li><strong>Graphs</strong> — Visual trends</li>' +
                      '<li><strong>Referrals</strong> — Your sales & statuses</li>' +
                      '<li><strong>Creatives</strong> — Banners & ads</li>' +
                      '<li><strong>Payouts</strong> — Payments</li>' +
                      '<li><strong>Settings</strong> — Profile & payment email</li>' +
                      '</ul>',
                attachTo: { element: sel('.affwp-portal-sidebar, .portal-sidebar', '.affwp-tabs, .affwp-tab-wrapper'), on: 'right' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-stats',
                title: 'Your Stats',
                text: '<p>Your performance at a glance:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><strong>Earnings</strong> — Total money earned</li>' +
                      '<li><strong>Paid</strong> — Already sent to you</li>' +
                      '<li><strong>Unpaid</strong> — Coming next payout</li>' +
                      '<li><strong>Conversion Rate</strong> — Clicks that bought</li>' +
                      '</ul>',
                attachTo: { element: sel('.affwp-portal-content .affwp-stats, .affwp-portal-content [class*="stat"]', '.affwp-stats, .affwp-dashboard-stats'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-referrals',
                title: 'Referral Statuses',
                text: '<p>Every sale goes through statuses:</p>' +
                      '<ul style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li><span style="color:#ffaa00">● Pending</span> — Order processing (24-48h)</li>' +
                      '<li><span style="color:#60a5fa">● Unpaid</span> — Confirmed, awaiting payout</li>' +
                      '<li><span style="color:#44f80c">● Paid</span> — Money sent to you</li>' +
                      '<li><span style="color:#ef4444">● Rejected</span> — Refunded or cancelled</li>' +
                      '</ul>',
                attachTo: { element: sel('.affwp-portal-content .affwp-referrals, .affwp-portal-content [class*="referral"]', '.affwp-referrals'), on: 'top' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-creatives',
                title: 'Marketing Materials',
                text: '<p>Pre-made banners and text ads with your link <strong>already built in</strong>. Click "Copy Link" to grab the code, then paste into your social post or email. No design work needed.</p>',
                attachTo: { element: sel('.affwp-portal-content .affwp-creatives', '.affwp-creatives'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-visits',
                title: 'Tracking Visits',
                text: '<p>See who clicked your link and where they came from. Check this 24 hours after posting to see which platforms drive the most traffic.</p>',
                attachTo: { element: sel('.affwp-portal-content .affwp-visits', '.affwp-visits'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-graphs',
                title: 'Growth Graphs',
                text: '<p>Watch your earnings and referral count grow over time. Use date filters to spot trends. Spikes usually happen right after you post on social media.</p>',
                attachTo: { element: sel('.affwp-portal-content .affwp-graphs', '.affwp-graphs'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-payouts',
                title: 'Getting Paid',
                text: '<p>Payouts happen automatically on the <strong>1st of every month</strong> via PayPal. You need at least <strong>$50</strong> to trigger a payout. Make sure your payment email is correct, and submit your <strong>W-9</strong> (US affiliates).</p>',
                attachTo: { element: sel('.affwp-portal-content .affwp-payouts', '.affwp-payouts'), on: 'bottom' },
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Next →', action: function() { Shepherd.activeTour.next(); }, classes: 'shepherd-button-primary' }
                ]
            },
            {
                id: 'step-finish',
                title: 'You Are Ready!',
                text: '<p>That is everything. Here is your quick start:</p>' +
                      '<ol style="margin:8px 0;padding-left:18px;font-size:13px;">' +
                      '<li>Copy your referral link</li>' +
                      '<li>Grab a banner from Creatives</li>' +
                      '<li>Post with a personal recommendation</li>' +
                      '<li>Check Visits tomorrow</li>' +
                      '</ol>' +
                      '<p style="margin-top:10px;font-size:13px;">Need a refresher? Visit the <a href="' + guideUrl + '" style="color:#44f80c;font-weight:600;">Dashboard Guide</a> anytime.</p>',
                buttons: [
                    { text: '← Back', action: function() { Shepherd.activeTour.back(); }, classes: 'shepherd-button-secondary' },
                    { text: 'Done!', action: function() { completeTour(); }, classes: 'shepherd-button-primary' }
                ]
            }
        ];

        return steps;
    }

    // ============================================
    // TOUR LIFECYCLE
    // ============================================
    var tour = null;

    function buildTour() {
        injectShepherdTheme();
        tour = new Shepherd.Tour({
            defaultStepOptions: {
                cancelIcon: { enabled: true },
                scrollTo: { behavior: 'smooth', block: 'center' },
                when: {
                    show: function() {
                        var currentStep = tour.steps.indexOf(tour.getCurrentStep()) + 1;
                        var totalSteps = tour.steps.length;
                        var progressEl = document.createElement('span');
                        progressEl.className = 'shepherd-progress';
                        progressEl.textContent = currentStep + ' / ' + totalSteps;
                        var footer = document.querySelector('.shepherd-footer');
                        if (footer) {
                            var existing = footer.querySelector('.shepherd-progress');
                            if (existing) existing.remove();
                            footer.insertBefore(progressEl, footer.firstChild);
                        }
                        storageSet(CONFIG.STORAGE_KEY_TOUR_STEP, currentStep.toString());
                    }
                }
            },
            useModalOverlay: true
        });
        var steps = getTourSteps();
        steps.forEach(function(step) {
            if (step.attachTo && step.attachTo.element) {
                var el = document.querySelector(step.attachTo.element);
                if (!el) delete step.attachTo;
            }
            tour.addStep(step);
        });
        tour.on('cancel', function() {
            if (!storageGet(CONFIG.STORAGE_KEY_COMPLETED)) {
                storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
            }
            storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        });
        return tour;
    }

    window.microDOSAffiliateTour = {
        launch: function(userInitiated) {
            if (typeof Shepherd === 'undefined') { console.warn('[microDOS Tour] Shepherd.js not loaded'); return; }
            if (tour) { tour.complete(); tour = null; }
            if (userInitiated) {
                storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
                storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
            }
            buildTour();
            tour.start();
        },
        reset: function() {
            storageRemove(CONFIG.STORAGE_KEY_COMPLETED);
            storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
            storageRemove(CONFIG.STORAGE_KEY_DISMISSED);
            storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
            console.log('[microDOS Tour] Reset. Refresh to start over.');
        }
    };

    function completeTour() {
        storageSet(CONFIG.STORAGE_KEY_COMPLETED, 'true');
        storageRemove(CONFIG.STORAGE_KEY_SKIPPED);
        storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        if (tour) tour.complete();
    }

    function skipTour() {
        storageSet(CONFIG.STORAGE_KEY_SKIPPED, 'true');
        storageRemove(CONFIG.STORAGE_KEY_TOUR_STEP);
        if (tour) tour.complete();
    }

    // ============================================
    // INIT
    // ============================================
    function init() {
        if (!isAffiliateDashboard()) return;
        injectFloatingHelpButton();
        if (shouldAutoLaunch() && isMainDashboardTab()) {
            setTimeout(function() {
                if (isMainDashboardTab()) launchTour(false);
            }, CONFIG.AUTO_LAUNCH_DELAY);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
