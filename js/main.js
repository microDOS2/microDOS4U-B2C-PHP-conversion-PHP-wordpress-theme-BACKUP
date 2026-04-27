/**
 * microDOS4U Theme Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {

    // Mobile menu toggle
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNav = document.querySelector('.main-navigation');

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function() {
            mainNav.classList.toggle('active');
            const isOpen = mainNav.classList.contains('active');
            menuToggle.setAttribute('aria-expanded', isOpen);
        });
    }

    // FAQ accordion
    const faqItems = document.querySelectorAll('.faq-item');
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        if (question) {
            question.addEventListener('click', () => {
                // Close other items
                faqItems.forEach(other => {
                    if (other !== item) other.classList.remove('active');
                });
                // Toggle current
                item.classList.toggle('active');
            });
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Header scroll effect
    const header = document.querySelector('.site-header');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 100) {
            header.style.background = 'rgba(10, 5, 20, 0.98)';
        } else {
            header.style.background = 'rgba(10, 5, 20, 0.95)';
        }

        lastScroll = currentScroll;
    });

    // Animate elements on scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.card, .pricing-card').forEach(el => {
        observer.observe(el);
    });

    // Hero Typewriter Effect
    const phrases = [
        "Your Mind, Enhanced.",
        "Clarity in Every Dose.",
        "Your Journey, Optimized.",
        "Unlock Your Potential.",
        "Focus Without Compromise.",
        "Precision Psychedelics, Simplified."
    ];

    const heroTitle = document.getElementById('hero-title');
    if (heroTitle) {
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 100;

        function typeEffect() {
            const currentPhrase = phrases[phraseIndex];

            if (isDeleting) {
                heroTitle.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 50;
            } else {
                heroTitle.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 100;
            }

            if (!isDeleting && charIndex === currentPhrase.length) {
                isDeleting = true;
                typeSpeed = 2000; // Pause at end
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typeSpeed = 500; // Pause before typing next
            }

            setTimeout(typeEffect, typeSpeed);
        }

        typeEffect();
    }

    // Purchase tab toggle (Protocol vs One-Time)
    window.switchPurchaseTab = function(tab) {
        const protocolContainer = document.getElementById('protocol-container');
        const onetimeContainer = document.getElementById('onetime-container');
        const btnProtocol = document.getElementById('btn-protocol');
        const btnOnetime = document.getElementById('btn-onetime');
        
        if (!protocolContainer || !onetimeContainer) return;
        
        if (tab === 'protocol') {
            protocolContainer.classList.remove('hidden');
            onetimeContainer.classList.add('hidden');
            btnProtocol.style.backgroundColor = '#1a1329';
            btnProtocol.style.color = '#ffffff';
            btnOnetime.style.backgroundColor = 'transparent';
            btnOnetime.style.color = '#94a3b8';
        } else {
            protocolContainer.classList.add('hidden');
            onetimeContainer.classList.remove('hidden');
            btnOnetime.style.backgroundColor = '#1a1329';
            btnOnetime.style.color = '#ffffff';
            btnProtocol.style.backgroundColor = 'transparent';
            btnProtocol.style.color = '#94a3b8';
        }
    };

});
