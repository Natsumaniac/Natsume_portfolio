<?php
/**
 * Archive: Works
 *
 * @package DevPortfolio
 */

get_header();

$categories = get_terms( array(
    'taxonomy'   => 'work_category',
    'hide_empty' => true,
) );
?>

<section class="section section--archive">
    <div class="container">
        <h1 class="section__title">All Works</h1>
        <p class="section__subtitle">Browse my portfolio projects</p>

        <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
            <div class="filter-bar">
                <button class="filter-btn active" data-filter="*">All</button>
                <?php foreach ( $categories as $cat ) : ?>
                    <button class="filter-btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <div class="works-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/card', 'work' ); ?>
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
            <p class="no-posts">No works found. Start adding projects!</p>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
