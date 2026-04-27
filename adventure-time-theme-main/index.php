<?php
/**
 * Fallback Index Template
 *
 * @package DevPortfolio
 */

get_header(); ?>

<section class="section">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="entry">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <div class="entry__excerpt"><?php the_excerpt(); ?></div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p>Nothing found.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
