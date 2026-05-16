<?php
/**
 * Template Name: Affiliate Terms of Use
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 800px;">

        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Affiliate Terms of Use</h1>
            <p class="text-slate-400">Last updated: <?php echo date('F Y'); ?></p>
        </div>

        <div class="card p-8 rounded-lg text-slate-300" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    the_content();
                }
            }
            ?>
        </div>

    </div>
</section>

<?php
get_footer();
