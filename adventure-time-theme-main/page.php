<?php
/**
 * Default Page Template
 *
 * @package DevPortfolio
 */

get_header(); ?>

<section class="section">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <h1 class="section__title"><?php the_title(); ?></h1>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php get_footer(); ?>
