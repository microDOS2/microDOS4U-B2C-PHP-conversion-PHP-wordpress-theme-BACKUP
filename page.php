<?php
/**
 * Template Name: Default Page
 * 
 * The template for displaying all standard WordPress pages.
 * 
 * @package microDOS4U
 */

get_header();
?>

<section class="py-20" style="background-color: rgba(10, 5, 20, 0.7) !important; min-height: 60vh;">
    <div class="container mx-auto px-4 sm:px-6" style="max-width: 1100px;">

        <!-- Page Title -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4"><?php the_title(); ?></h1>
        </div>

        <!-- Page Content -->
        <div class="card p-8 rounded-lg" style="background-color: #150f24 !important; border: 1px solid #1f2b47;">
            <div style="color: #94a3b8; line-height: 1.7;">
                <?php
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
                        the_content();
                    endwhile;
                endif;
                ?>
            </div>
        </div>

    </div>
</section>

<?php
get_footer();
