<?php
/**
 * Template Part: Work Card
 *
 * @package DevPortfolio
 */

$technologies = devportfolio_parse_lines( get_field( 'work_technologies' ) );
$categories   = get_the_terms( get_the_ID(), 'work_category' );
?>

<div class="work-item">
    <a href="<?php the_permalink(); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        <?php else : ?>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-work.jpg" alt="<?php echo esc_attr(get_the_title()); ?>">
        <?php endif; ?>
        <div class="work-overlay"><?php the_title(); ?></div>
    </a>
</div>
