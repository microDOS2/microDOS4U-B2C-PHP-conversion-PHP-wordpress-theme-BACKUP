<?php
/**
 * Template Name: Contact
 *
 * @package microDOS4U
 */

get_header();

<nav class="main-navigation hidden md:flex items-center space-x-8">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="text-slate-300 hover:text-white transition">Home</a>
            <a href="<?php echo esc_url(home_url('/articles-studies')); ?>" class="text-slate-300 hover:text-white transition">Articles & Studies</a>
            <a href="<?php echo esc_url(home_url('/metocin-info')); ?>" class="text-slate-300 hover:text-white transition">Metocin Info</a>
            <a href="<?php echo esc_url(home_url('/dosage-guide')); ?>" class="text-slate-300 hover:text-white transition">Dosage Guide</a>
        </nav>
?>

<main class="flex-grow container mx-auto px-6 py-12 md:py-20">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Contact <span class="gradient-text">microDOS(2)</span></h1>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">Have questions about your order, shipping, or how Metocin works? Our support team is here to ensure your journey is seamless and discreet.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Contact Info Sidebar -->
            <div class="space-y-8">
                <div>
                    <h3 class="text-white font-bold text-xl mb-4">Support Channels</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="bg-sky-500/10 p-2 rounded-lg text-sky-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Email Us</p>
                                <a href="mailto:microDOS4U@gmail.com" class="text-slate-400 hover:text-sky-400 transition-colors">microDOS4U@gmail.com</a>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="bg-green-500/10 p-2 rounded-lg text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-white font-semibold">Response Time</p>
                                <p class="text-slate-400">Usually within 24-48 hours</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 rounded-xl border border-slate-800" style="background-color: #150f24 !important;">
                    <h4 class="text-white font-bold mb-2">Discreet Inquiries</h4>
                    <p class="text-sm text-slate-400">All communications are private. We do not share customer information with any third parties or marketing lists.</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="md:col-span-2">
                <div id="contact-form-container" class="card-bg rounded-2xl border border-slate-800 p-8 shadow-2xl">
                    <form id="contact-form" class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-300 mb-2">Full Name</label>
                                <input type="text" id="name" name="name" class="input-field" placeholder="John Doe" required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-300 mb-2">Email Address</label>
                                <input type="email" id="email" name="email" class="input-field" placeholder="john@example.com" required>
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-semibold text-slate-300 mb-2">Subject</label>
                            <select id="subject" name="subject" class="input-field">
                                <option value="Order Status">Order Status</option>
                                <option value="Shipping Query">Shipping Query</option>
                                <option value="Product Question">Product Question</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-slate-300 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" class="input-field" placeholder="How can we help you?" required></textarea>
                        </div>
                        <button type="submit" id="submit-btn" class="w-full btn-primary text-white font-bold py-4 rounded-lg">
                            <span id="btn-text">Send Message</span>
                            <span id="btn-loader" class="spinner hidden"></span>
                        </button>
                    </form>

                    <!-- Success Message -->
                    <div id="success-message" class="hidden text-center py-10">
                        <div class="w-20 h-20 bg-green-500/10 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">Message Sent</h3>
                        <p class="text-slate-400 mb-8">Thank you for reaching out. We've received your inquiry and will get back to you soon.</p>
                        <button onclick="resetForm()" class="text-sky-400 hover:text-sky-300 font-semibold underline">Send another message</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Form Handling
    const contactForm = document.getElementById('contact-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    const successMessage = document.getElementById('success-message');

    contactForm.onsubmit = function(e) {
        e.preventDefault();

        // UI State: Loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoader.classList.remove('hidden');

        // Simulate Network Request
        setTimeout(function() {
            contactForm.classList.add('hidden');
            successMessage.classList.remove('hidden');
        }, 1500);
    };

    function resetForm() {
        contactForm.reset();
        contactForm.classList.remove('hidden');
        successMessage.classList.add('hidden');
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoader.classList.add('hidden');
    }
</script>

<?php
get_footer();
?>
