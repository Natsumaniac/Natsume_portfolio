<?php
/**
 * Single: Work
 *
 * @package DevPortfolio
 */

get_header();

$client       = get_field( 'work_client' );
$date         = get_field( 'work_date' );
$live_url     = get_field( 'work_url' );
$github_url   = get_field( 'work_github_url' );
$technologies = devportfolio_parse_lines( get_field( 'work_technologies' ) );
$gallery      = devportfolio_get_work_gallery();
$categories   = get_the_terms( get_the_ID(), 'work_category' );
?>

<article class="section section--single-work">
    <div class="container">
        <a href="<?php echo get_post_type_archive_link( 'work' ); ?>" class="back-link">&larr; Back to Works</a>

        <header class="single-work__header">
            <h1 class="single-work__title"><?php the_title(); ?></h1>
            <div class="single-work__meta">
                <?php if ( $client ) : ?>
                    <span><strong>Client:</strong> <?php echo esc_html( $client ); ?></span>
                <?php endif; ?>
                <?php if ( $date ) : ?>
                    <span><strong>Date:</strong> <?php echo esc_html( $date ); ?></span>
                <?php endif; ?>
                <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                    <span><strong>Category:</strong> <?php echo esc_html( $categories[0]->name ); ?></span>
                <?php endif; ?>
            </div>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-work__featured">
                <?php the_post_thumbnail( 'portfolio-full' ); ?>
            </div>
        <?php endif; ?>

        <div class="single-work__grid">
            <div class="single-work__content">
                <?php the_content(); ?>
            </div>

            <aside class="single-work__sidebar">
                <?php if ( $technologies ) : ?>
                    <div class="sidebar-block">
                        <h4>Technologies</h4>
                        <div class="tech-tags">
                            <?php foreach ( $technologies as $tech ) : ?>
                                <span class="tech-tag"><?php echo esc_html( $tech ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="sidebar-block">
                    <h4>Links</h4>
                    <?php if ( $live_url ) : ?>
                        <a href="<?php echo esc_url( $live_url ); ?>" class="btn btn--primary btn--full" target="_blank" rel="noopener">View Live Site</a>
                    <?php endif; ?>
                    <?php if ( $github_url ) : ?>
                        <a href="<?php echo esc_url( $github_url ); ?>" class="btn btn--outline btn--full" target="_blank" rel="noopener">View on GitHub</a>
                    <?php endif; ?>
                </div>
            </aside>
        </div>

        <?php if ( $gallery ) : ?>
            <div class="single-work__gallery">
                <h3>Project Gallery</h3>
                <div class="gallery-grid">
                    <?php foreach ( $gallery as $img ) : ?>
                        <div class="gallery-item">
                            <img src="<?php echo esc_url( $img['sizes']['portfolio-full'] ?? $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <nav class="post-navigation">
            <?php
            $prev = get_previous_post();
            $next = get_next_post();
            ?>
            <?php if ( $prev ) : ?>
                <a href="<?php echo get_permalink( $prev ); ?>" class="nav-prev">&larr; <?php echo esc_html( $prev->post_title ); ?></a>
            <?php endif; ?>
            <?php if ( $next ) : ?>
                <a href="<?php echo get_permalink( $next ); ?>" class="nav-next"><?php echo esc_html( $next->post_title ); ?> &rarr;</a>
            <?php endif; ?>
        </nav>
    </div>
</article>

<?php get_footer(); ?>
