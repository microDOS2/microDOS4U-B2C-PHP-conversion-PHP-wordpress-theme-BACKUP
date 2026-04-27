<?php
/**
 * The Template for displaying all single products
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="py-5">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            wc_get_template_part('content', 'single-product');
        endwhile;
        ?>
    </div>
</section>

<?php
get_footer();
