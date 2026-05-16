/**
 * microDOS(2) Affiliate Creatives Enhancement
 * - Adds instructional header to creatives page
 * - Improves Copy button UX with feedback and tooltips
 * - Shows contextual help based on creative type
 */
(function() {
    'use strict';

    // Only run on the creatives page
    if (!document.querySelector('.affwp-creatives') && !window.location.href.includes('/creatives/')) {
        return;
    }

    // ============================================================
    // 1. INJECT INSTRUCTIONAL HEADER
    // ============================================================
    function injectInstructions() {
        var container = document.querySelector('.affwp-creatives');
        if (!container) return;

        // Check if already injected
        if (container.querySelector('.affwp-creatives-instructions')) return;

        var instructionsHTML = 
            '<div class="affwp-creatives-instructions">' +
                '<h3>How to Use Your Marketing Materials</h3>' +
                '<p>Each card below contains a marketing asset with your unique referral link already embedded. Choose a method:</p>' +
                '<ol>' +
                    '<li><strong>View</strong> — See the full-size image or preview the creative</li>' +
                    '<li><strong>Copy Link</strong> — Copies the code with your personal referral URL. Paste it into social media posts, emails, websites, or messages</li>' +
                '</ol>' +
                '<a href="/marketing-guide/" class="guide-link">Need help? Read the full Marketing Guide for step-by-step instructions</a>' +
            '</div>';

        var div = document.createElement('div');
        div.innerHTML = instructionsHTML;
        container.insertBefore(div.firstElementChild, container.firstChild);
    }

    // ============================================================
    // 2. ENHANCE COPY BUTTONS
    // ============================================================
    function enhanceCopyButtons() {
        var buttons = document.querySelectorAll('.affwp-copy-creative');

        buttons.forEach(function(button) {
            // Skip if already enhanced
            if (button.dataset.enhanced) return;
            button.dataset.enhanced = 'true';

            // Change label to be more descriptive
            var originalText = button.innerHTML;
            button.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg> Copy Link';

            // Add tooltip
            button.classList.add('microdos-tooltip');
            button.setAttribute('data-tooltip', 'Copies code with your referral URL');

            // Override click for better feedback
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var code = button.getAttribute('data-code') || button.getAttribute('href');

                if (code) {
                    copyToClipboard(code, button);
                }

                return false;
            });
        });
    }

    // ============================================================
    // 3. COPY TO CLIPBOARD WITH FEEDBACK
    // ============================================================
    function copyToClipboard(text, button) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(function() {
                showCopiedFeedback(button);
            }).catch(function() {
                fallbackCopy(text, button);
            });
        } else {
            fallbackCopy(text, button);
        }
    }

    function fallbackCopy(text, button) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();

        try {
            document.execCommand('copy');
            showCopiedFeedback(button);
        } catch (err) {
            showToast('Could not copy. Please try again.', 'error');
        }

        document.body.removeChild(textarea);
    }

    function showCopiedFeedback(button) {
        var originalHTML = button.innerHTML;
        button.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"></path></svg> Copied!';
        button.style.background = 'rgba(16, 185, 129, 0.2)';
        button.style.color = '#10b981';

        showToast('Code copied! Paste it into your post, email, or website.', 'success');

        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.style.background = '';
            button.style.color = '';
        }, 2500);
    }

    // ============================================================
    // 4. TOAST NOTIFICATION
    // ============================================================
    function showToast(message, type) {
        type = type || 'success';

        var existing = document.querySelector('.microdos-toast');
        if (existing) existing.remove();

        var toast = document.createElement('div');
        toast.className = 'microdos-toast ' + type;
        toast.textContent = message;

        if (type === 'error') {
            toast.style.background = '#ef4444';
            toast.style.boxShadow = '0 8px 32px rgba(239, 68, 68, 0.3)';
        }

        document.body.appendChild(toast);

        // Trigger reflow
        void toast.offsetWidth;

        requestAnimationFrame(function() {
            toast.classList.add('show');
        });

        setTimeout(function() {
            toast.classList.remove('show');
            setTimeout(function() {
                if (toast.parentNode) toast.parentNode.removeChild(toast);
            }, 300);
        }, 4000);
    }

    // ============================================================
    // 5. ADD TYPE BADGES TO CREATIVE CARDS
    // ============================================================
    function addTypeBadges() {
        var cards = document.querySelectorAll('.affwp-creative');

        cards.forEach(function(card) {
            if (card.querySelector('.affwp-creative-type-badge')) return;

            var imageWrap = card.querySelector('.affwp-creative-image');
            var textWrap = card.querySelector('.affwp-creative-text');

            var badge = document.createElement('div');
            badge.className = 'affwp-creative-type-badge';

            if (imageWrap) {
                badge.classList.add('image');
                badge.textContent = 'Image Banner';
            } else if (textWrap) {
                badge.classList.add('text');
                badge.textContent = 'Text Link';
            } else {
                badge.classList.add('image');
                badge.textContent = 'Creative';
            }

            card.insertBefore(badge, card.firstChild);
        });
    }

    // ============================================================
    // INIT
    // ============================================================
    function init() {
        injectInstructions();
        enhanceCopyButtons();
        addTypeBadges();

        // Re-run on dynamic content changes (AJAX pagination)
        var observer = new MutationObserver(function(mutations) {
            enhanceCopyButtons();
            addTypeBadges();
        });

        var container = document.querySelector('.affwp-creatives');
        if (container) {
            observer.observe(container, { childList: true, subtree: true });
        }
    }

    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();