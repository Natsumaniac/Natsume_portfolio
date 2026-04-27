<?php
/**
 * Archive: Certificates
 *
 * @package DevPortfolio
 */

get_header(); ?>

<section class="section section--archive">
    <div class="container">
        <h1 class="section__title">Certificates</h1>
        <p class="section__subtitle">Professional certifications & achievements</p>

        <?php if ( have_posts() ) : ?>
            <div class="certificates-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/card', 'certificate' ); ?>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '&laquo; Prev',
                    'next_text' => 'Next &raquo;',
                ) ); ?>
            </div>
        <?php else : ?>
            <p class="no-posts">No certificates found.</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
