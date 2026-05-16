<?php
/**
 * Template Name: Affiliate W-9 Form
 *
 * Dedicated page for affiliates to submit their W-9 tax information.
 * Uses the [microdos_w9_form] shortcode for the form logic.
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 800px;">

        <!-- Page Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">W-9 Tax Form</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">
                Submit your W-9 information so we can process your commission payments and issue your 1099-NEC at year-end.
            </p>
        </div>

        <!-- Breadcrumb -->
        <div class="text-center mb-8">
            <nav style="font-size:13px;color:#94a3b8;">
                <a href="<?php echo esc_url(home_url('/affiliate-program')); ?>" style="color:#44f80c;text-decoration:none;">Affiliate Program</a>
                <span style="margin:0 8px;">&rsaquo;</span>
                <span style="color:#d1d5db;">W-9 Form</span>
            </nav>
        </div>

        <!-- W-9 Form -->
        <div class="card p-8 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <?php
            if (shortcode_exists('microdos_w9_form')) {
                echo do_shortcode('[microdos_w9_form]');
            } else {
                echo '<div style="color:#ff4444;text-align:center;padding:20px;">W-9 form system is not available. Please contact support.</div>';
            }
            ?>
        </div>

        <!-- Help / Contact -->
        <div class="mt-8 text-center">
            <p style="color:#94a3b8;font-size:13px;">
                Questions about your W-9? 
                <a href="<?php echo esc_url(home_url('/contact')); ?>" style="color:#44f80c;text-decoration:underline;">Contact us</a>
                or email <a href="mailto:support@microdos4u.com" style="color:#44f80c;text-decoration:underline;">support@microdos4u.com</a>
            </p>
        </div>

    </div>
</section>

<?php
get_footer();
