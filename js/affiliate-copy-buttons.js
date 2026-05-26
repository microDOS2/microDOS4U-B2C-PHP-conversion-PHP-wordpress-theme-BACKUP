/**
 * microDOS4U Affiliate Creative Copy Buttons
 *
 * Handles 3 copy actions per creative:
 *  - Copy Image URL (plain text)
 *  - Copy My Link (plain text)
 *  - Copy for Email (text/html MIME type - renders as clickable image in Gmail/Outlook)
 *
 * @package microDOS4U
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    var buttons = document.querySelectorAll('.microdos-copy-btn');

    buttons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var uid    = this.getAttribute('data-uid');
            var type   = uid.split('-').pop(); // 'img', 'link', or 'email'
            var dataEl = document.getElementById(uid + '-data');

            if (!dataEl) return;

            var content = dataEl.textContent.trim();
            if (!content) return;

            var feedback = document.getElementById(uid.replace(type, 'feedback'));

            if (type === 'email') {
                // Rich HTML copy - pastes as clickable image in Gmail/Outlook
                copyRichHtml(content, uid, feedback);
            } else {
                // Plain text copy
                copyPlainText(content, uid, feedback);
            }
        });
    });

    /**
     * Copy plain text to clipboard
     */
    function copyPlainText(text, uid, feedbackEl) {
        navigator.clipboard.writeText(text).then(function() {
            showFeedback(feedbackEl);
        }).catch(function(err) {
            console.error('Copy failed:', err);
            fallbackCopy(text);
        });
    }

    /**
     * Copy rich HTML (text/html MIME type) to clipboard
     * When pasted into Gmail/Outlook compose, renders as clickable image
     */
    function copyRichHtml(htmlString, uid, feedbackEl) {
        // Build a plain text fallback message
        var fallbackText = 'Check out microDOS(2): ' + document.querySelector('[id$="' + uid.replace('email', 'link') + '-data"]').textContent.trim();

        try {
            var htmlBlob = new Blob([htmlString], { type: 'text/html' });
            var textBlob = new Blob([fallbackText], { type: 'text/plain' });

            var item = new ClipboardItem({
                'text/html': htmlBlob,
                'text/plain': textBlob
            });

            navigator.clipboard.write([item]).then(function() {
                showFeedback(feedbackEl);
            }).catch(function(err) {
                console.error('Rich copy failed, falling back:', err);
                navigator.clipboard.writeText(htmlString).then(function() {
                    showFeedback(feedbackEl);
                });
            });
        } catch (e) {
            // ClipboardItem not supported - fallback to plain text copy of HTML
            navigator.clipboard.writeText(htmlString).then(function() {
                showFeedback(feedbackEl);
            });
        }
    }

    /**
     * Show "Copied!" feedback briefly
     */
    function showFeedback(el) {
        if (!el) return;
        el.style.display = 'inline-flex';
        setTimeout(function() {
            el.style.display = 'none';
        }, 2000);
    }

    /**
     * Fallback copy for older browsers
     */
    function fallbackCopy(text) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
        } catch (e) {
            console.error('Fallback copy failed:', e);
        }
        document.body.removeChild(textarea);
    }
});
