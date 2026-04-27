<?php
/**
 * Template Name: Articles and Studies
 *
 * @package microDOS4U
 */

get_header();
?>

<section class="hero py-5">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title gradient-text">Articles & Studies</h1>
            <p class="hero-subtitle">Research and resources on microdosing, psychedelics, and cognitive enhancement.</p>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php
        $articles = new WP_Query(array(
            'post_type'      => 'post',
            'posts_per_page' => 12,
            'category_name'  => 'articles',
        ));
        
        if ($articles->have_posts()) : ?>
            <div class="features-grid">
                <?php while ($articles->have_posts()) : $articles->the_post(); ?>
                    <article class="card">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-secondary mt-2">Read More</a>
                    </article>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php else : ?>
            <div class="text-center">
                <p>Articles coming soon. Check back for research updates.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php
get_footer();
