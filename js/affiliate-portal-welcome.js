/**
 * microDOS(2) Affiliate Portal Integration
 * =========================================
 * Injects menu links into the Affiliate Portal sidebar and
 * displays a Getting Started welcome panel on the dashboard.
 * Works with any AffiliateWP version — no PHP filters needed.
 */
(function() {
    'use strict';

    // Config from PHP
    var DATA = window.microDOSPortalData || {};
    var GUIDE_URL = DATA.guideUrl || '/affiliate-dashboard-guide/';
    var MG_URL = DATA.mgUrl || '/marketing-guide/';
    var REFERRAL_URL = DATA.referralUrl || '';

    // =========================
    // 1. INJECT SIDEBAR LINKS
    // =========================
    function injectSidebarLinks() {
        // Already injected?
        if (document.getElementById('microdos-sidebar-links')) return;

        // Find the sidebar navigation
        var sidebar = findSidebarNav();
        if (!sidebar) {
            console.log('[microDOS] Sidebar nav not found, retrying...');
            setTimeout(injectSidebarLinks, 500);
            return;
        }

        // Create container
        var container = document.createElement('div');
        container.id = 'microdos-sidebar-links';
        container.style.cssText = 'margin-top:16px;padding-top:12px;border-top:1px solid rgba(255,255,255,0.06);';

        // Add Dashboard Guide link
        if (GUIDE_URL) {
            container.appendChild(createSidebarLink(
                GUIDE_URL,
                'Dashboard Guide',
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
                'dashboard-guide'
            ));
        }

        // Add Marketing Guide link
        if (MG_URL) {
            container.appendChild(createSidebarLink(
                MG_URL,
                'Marketing Guide',
                '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
                'marketing-guide'
            ));
        }

        // Insert at bottom of sidebar
        sidebar.appendChild(container);
        console.log('[microDOS] Sidebar links injected');
    }

    function findSidebarNav() {
        // Try multiple selectors for the sidebar navigation area
        var selectors = [
            '.affwp-portal-sidebar nav',
            '.affiliate-portal-sidebar nav',
            '.portal-sidebar nav',
            '[class*="portal-sidebar"] nav',
            '.affwp-portal-sidebar',
            '.affiliate-portal-sidebar',
            '.portal-sidebar',
            'aside nav',
            'aside',
            '.sidebar nav',
            '.sidebar'
        ];

        for (var i = 0; i < selectors.length; i++) {
            var el = document.querySelector(selectors[i]);
            if (el) return el;
        }

        // Fallback: find the sidebar by looking for known menu items
        var dashboardLink = findElementByText('Dashboard', 'a');
        if (dashboardLink) {
            // Walk up to find the parent container
            var parent = dashboardLink.parentElement;
            while (parent && parent.tagName !== 'BODY') {
                if (parent.tagName === 'ASIDE' || parent.tagName === 'NAV' ||
                    parent.classList.contains('sidebar') ||
                    parent.classList.contains('portal-sidebar') ||
                    parent.classList.contains('affwp-portal-sidebar')) {
                    return parent;
                }
                parent = parent.parentElement;
            }
        }

        return null;
    }

    function findElementByText(text, tag) {
        var elements = document.querySelectorAll(tag);
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].textContent.trim() === text) {
                return elements[i];
            }
        }
        return null;
    }

    function createSidebarLink(href, text, iconHtml, id) {
        // Get an existing link to copy styles from
        var existingLink = document.querySelector('.affwp-portal-sidebar a, .affiliate-portal-sidebar a, .portal-sidebar a, aside a');
        var computedStyle = existingLink ? window.getComputedStyle(existingLink) : null;

        var link = document.createElement('a');
        link.href = href;
        link.id = 'microdos-link-' + id;
        link.style.cssText = 'display:flex;align-items:center;gap:12px;padding:10px 20px;color:#94a3b8;text-decoration:none;font-size:14px;font-weight:500;transition:all 0.2s;border-radius:6px;margin:2px 8px;';

        link.innerHTML = '<span style="flex-shrink:0;opacity:0.7;">' + iconHtml + '</span><span>' + text + '</span>';

        // Hover effects
        link.addEventListener('mouseenter', function() {
            link.style.backgroundColor = 'rgba(68,248,12,0.06)';
            link.style.color = '#44f80c';
        });
        link.addEventListener('mouseleave', function() {
            link.style.backgroundColor = 'transparent';
            link.style.color = '#94a3b8';
        });

        // Copy click behavior from existing links if possible
        if (existingLink) {
            // Some portals use JS navigation - check if existing links have onclick handlers
            var existingOnclick = existingLink.getAttribute('onclick');
            if (existingOnclick) {
                // Let the browser handle it normally (our link has a real href)
            }
        }

        return link;
    }

    // =========================
    // 2. INJECT WELCOME PANEL
    // =========================
    function injectWelcomePanel() {
        if (document.getElementById('microdos-welcome-panel')) return;

        var content = findContentArea();
        if (!content) {
            setTimeout(injectWelcomePanel, 500);
            return;
        }

        var panel = document.createElement('div');
        panel.id = 'microdos-welcome-panel';
        panel.style.cssText = 'background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:24px;margin:0 0 24px 0;font-family:inherit;position:relative;';

        panel.innerHTML = buildWelcomeHTML();

        // Insert as first child of content area
        if (content.firstChild) {
            content.insertBefore(panel, content.firstChild);
        } else {
            content.appendChild(panel);
        }

        console.log('[microDOS] Welcome panel injected');
    }

    function findContentArea() {
        var selectors = [
            '.affwp-portal-content',
            '.affiliate-portal-content',
            '.portal-content',
            '.affwp-portal-main',
            '.affiliate-portal-main',
            'main',
            '.content-area',
            '.site-main',
            'article'
        ];

        for (var i = 0; i < selectors.length; i++) {
            var el = document.querySelector(selectors[i]);
            if (el) return el;
        }

        // Fallback: find by dashboard stats cards
        var statCard = document.querySelector('[class*="referral"], [class*="stat"]');
        if (statCard) {
            var parent = statCard.parentElement;
            while (parent && parent.tagName !== 'BODY') {
                if (parent.children.length > 2) return parent;
                parent = parent.parentElement;
            }
        }

        return null;
    }

    function buildWelcomeHTML() {
        var refDisplay = REFERRAL_URL || 'Your referral link will appear here';

        return '<button id="mcd-dismiss" title="Hide" style="position:absolute;top:12px;right:12px;background:none;border:1px solid #cbd5e1;color:#94a3b8;border-radius:50%;width:28px;height:28px;cursor:pointer;font-size:16px;line-height:1;display:flex;align-items:center;justify-content:center;padding:0;">&times;</button>' +

        '<h3 style="margin:0 0 16px;font-size:18px;font-weight:700;color:#0f172a;">Getting Started as a microDOS(2) Affiliate</h3>' +

        '<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px 18px;margin-bottom:20px;">' +
            '<strong style="color:#44f80c;font-size:12px;text-transform:uppercase;letter-spacing:0.05em;">Your Referral Link</strong>' +
            '<p style="color:#64748b;font-size:13px;margin:6px 0 10px;">Share this link everywhere. When someone clicks and buys, you earn 20%.</p>' +
            '<code id="mcd-ref-url" style="display:block;background:#f1f5f9;color:#0f172a;padding:10px 14px;border-radius:6px;font-size:13px;word-break:break-all;margin:0 0 10px;font-family:monospace;">' + escapeHtml(refDisplay) + '</code>' +
            '<button onclick="copyMcdRef(this)" style="padding:8px 18px;background:#44f80c;color:#0a0514;border:none;border-radius:6px;font-size:13px;font-weight:600;cursor:pointer;">Copy Link</button>' +
        '</div>' +

        '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">' +
            '<div style="background:#f8fafc;padding:12px;border-radius:6px;">' +
                '<strong style="color:#0f172a;font-size:13px;">How It Works</strong>' +
                '<ol style="color:#64748b;font-size:12px;line-height:1.6;padding-left:16px;margin:8px 0 0;">' +
                    '<li>Share your link</li><li>Someone clicks</li><li>They buy within 45 days</li><li>You earn 20%</li>' +
                '</ol>' +
            '</div>' +
            '<div style="background:#f8fafc;padding:12px;border-radius:6px;">' +
                '<strong style="color:#0f172a;font-size:13px;">Quick Start</strong>' +
                '<ol style="color:#64748b;font-size:12px;line-height:1.6;padding-left:16px;margin:8px 0 0;">' +
                    '<li>Copy your link</li><li>Grab a banner from Creatives</li><li>Post with a recommendation</li><li>Check Visits tomorrow</li>' +
                '</ol>' +
            '</div>' +
        '</div>' +

        '<div style="display:flex;gap:12px;flex-wrap:wrap;">' +
            '<button onclick="launchMcdTour()" style="padding:10px 20px;background:#44f80c;color:#0a0514;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>' +
                'Take a Tour' +
            '</button>' +
            '<a href="' + escapeHtml(GUIDE_URL) + '" style="padding:10px 20px;background:#ff66c4;color:#fff;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>' +
                'Dashboard Guide' +
            '</a>' +
            (MG_URL ? '<a href="' + escapeHtml(MG_URL) + '" style="padding:10px 20px;background:#9a02d0;color:#fff;font-weight:700;font-size:13px;border:none;border-radius:8px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">' +
                '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>' +
                'Marketing Guide' +
            '</a>' : '') +
        '</div>';
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // =========================
    // 3. GLOBAL FUNCTIONS
    // =========================

    window.copyMcdRef = function(btn) {
        var url = document.getElementById('mcd-ref-url').textContent;
        navigator.clipboard.writeText(url).then(function() {
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
        }, function() {
            var ta = document.createElement('textarea');
            ta.value = url;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
            btn.textContent = 'Copied!';
            setTimeout(function() { btn.textContent = 'Copy Link'; }, 2000);
        });
    };

    window.launchMcdTour = function() {
        if (window.microDOSAffiliateTour && window.microDOSAffiliateTour.launch) {
            window.microDOSAffiliateTour.launch(true);
        } else {
            alert('Tour is loading... Please try again in a moment.');
        }
    };

    // =========================
    // INIT
    // =========================
    function init() {
        var path = window.location.pathname;
        var isAffiliate = path.indexOf('affiliate') !== -1 ||
                          path.indexOf('portal') !== -1 ||
                          document.querySelector('.affwp-portal') ||
                          document.querySelector('.affiliate-portal');

        if (!isAffiliate) return;

        injectSidebarLinks();

        // Only show welcome panel on main dashboard page
        var isMainDash = !window.location.search.match(/[?&]tab=/) &&
                         !window.location.hash.match(/tab/);
        if (isMainDash) {
            injectWelcomePanel();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Retry for AJAX-loaded content
    setTimeout(init, 500);
    setTimeout(init, 1500);
})();
