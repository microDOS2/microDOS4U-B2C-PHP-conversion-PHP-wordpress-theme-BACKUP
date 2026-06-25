<?php
/**
 * The template for displaying archive pages
 *
 * Used for category, tag, author, date, and taxonomy archives.
 *
 * @package microDOS4U
 */

get_header();
?>

<div class="archive-page" style="background-color: #0a0514; min-height: 100vh;">
    <div class="container mx-auto px-4 py-12 max-w-5xl">

        <!-- Archive Header -->
        <div class="archive-header mb-10 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <?php
            if (is_category()) :
                ?>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <span style="color: #44f80c;">Category:</span> <?php single_cat_title(); ?>
                </h1>
                <?php if (category_description()) : ?>
                    <p class="text-slate-400"><?php echo category_description(); ?></p>
                <?php endif; ?>

            <?php elseif (is_tag()) : ?>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <span style="color: #9a02d0;">Tag:</span> <?php single_tag_title(); ?>
                </h1>
                <?php if (tag_description()) : ?>
                    <p class="text-slate-400"><?php echo tag_description(); ?></p>
                <?php endif; ?>

            <?php elseif (is_author()) : ?>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <span style="color: #ff66c4;">Author:</span> <?php the_author(); ?>
                </h1>

            <?php elseif (is_date()) : ?>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <span style="color: #38bdf8;">Archive:</span> <?php the_archive_title(); ?>
                </h1>

            <?php else : ?>
                <h1 class="text-3xl font-bold text-white mb-2">
                    <?php the_archive_title(); ?>
                </h1>
            <?php endif; ?>
        </div>

        <!-- Posts Grid -->
        <?php if (have_posts()) : ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="rounded-lg overflow-hidden" style="background-color: #150f24; border: 1px solid #1f2b47;">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover', 'style' => 'display: block;')); ?>
                            </a>
                        <?php endif; ?>
                        <div class="p-5">
                            <h2 class="text-lg font-bold text-white mb-2">
                                <a href="<?php the_permalink(); ?>" style="color: #ffffff; text-decoration: none;">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <p class="text-slate-400 text-sm mb-3">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </p>
                            <div class="flex items-center justify-between text-xs" style="color: #64748b;">
                                <span><?php echo get_the_date(); ?></span>
                                <a href="<?php the_permalink(); ?>" style="color: #44f80c;">Read More &rarr;</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex justify-center">
                <?php
                echo paginate_links(array(
                    'prev_text' => '&larr; Previous',
                    'next_text' => 'Next &rarr;',
                    'type'      => 'list',
                ));
                ?>
            </div>

        <?php else : ?>
            <div class="text-center py-16" style="background-color: #150f24; border: 1px solid #1f2b47; border-radius: 12px;">
                <p class="text-xl text-white mb-2">No posts found.</p>
                <p class="text-slate-400">Check back later for new content.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
