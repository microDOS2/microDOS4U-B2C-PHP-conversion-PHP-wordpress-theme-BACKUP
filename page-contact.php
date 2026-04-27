<?php
/**
 * Template Name: Contact
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="hero py-5">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Contact <span class="brand-micro">micro</span><span class="brand-dos">DOS</span><span class="brand-two">(2)</span></h1>
            <p class="hero-subtitle">We are here to answer your questions.</p>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="features-grid">
            <div class="card">
                <h3>Email Us</h3>
                <p>For general inquiries, partnerships, or support:</p>
                <p><a href="mailto:microDOS4U@gmail.com">microDOS4U@gmail.com</a></p>
            </div>
            <div class="card">
                <h3>Customer Support</h3>
                <p>Have a question about your order or subscription?</p>
                <p><a href="mailto:microDOS4U@gmail.com">microDOS4U@gmail.com</a></p>
            </div>
            <div class="card">
                <h3>Research Inquiries</h3>
                <p>For academic or institutional research partnerships:</p>
                <p><a href="mailto:microDOS4U@gmail.com">microDOS4U@gmail.com</a></p>
            </div>
        </div>

        <div class="card mt-3">
            <h3 class="text-center mb-2">Send a Message</h3>
            <?php
            // Simple contact form placeholder
            // In production, use a form plugin like Contact Form 7 or WPForms
            ?>
            <form style="max-width: 600px; margin: 0 auto;">
                <div class="mb-2">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required>
                </div>
                <div class="mb-2">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required>
                </div>
                <div class="mb-2">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="How can we help?">
                </div>
                <div class="mb-2">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Your message..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php
get_footer();
