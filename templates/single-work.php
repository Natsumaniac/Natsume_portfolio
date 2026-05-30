<?php
/**
 * Single: Work
 *
 * @package NatsumePortfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();

$work_id        = get_the_ID();
$client         = get_field( 'work_client', $work_id );
$date           = get_field( 'work_date', $work_id );
$live_url       = get_field( 'work_url', $work_id );
$github_url     = get_field( 'work_github_url', $work_id );
$youtube_link   = get_field( 'youtube_link', $work_id );
$wattpad_link   = get_field( 'wattpad_link', $work_id );
$story_url      = get_field( 'story_url', $work_id );
$genre          = get_field( 'genre', $work_id );
$story_status   = get_field( 'story_status', $work_id );
$video_duration = get_field( 'video_duration', $work_id );
$video_type     = get_field( 'video_type', $work_id );
$design_type    = get_field( 'design_type', $work_id );
$project_role   = get_field( 'project_role', $work_id );
$project_duration = get_field( 'project_duration', $work_id );
$project_status = get_field( 'project_status', $work_id );
$project_logo   = get_field( 'project_logo', $work_id );
$short_description = get_field( 'short_description', $work_id );
$featured_work  = get_field( 'featured_work', $work_id );
$technologies   = natsume_portfolio_parse_lines( get_field( 'work_technologies', $work_id ) );
$search_query   = get_search_query();
$categories     = get_the_terms( $work_id, 'work_category' );

$portfolio_owner_name   = get_field( 'portfolio_owner_name', $work_id );
$portfolio_owner_avatar = get_field( 'portfolio_owner_avatar', $work_id );
$post_tags = get_the_terms( $work_id, 'work_tag' );

function natsume_portfolio_get_acf_image( $image_field, $size = 'large' ) {
    if ( empty( $image_field ) ) {
        return array( 'url' => '', 'alt' => '' );
    }

    if ( is_array( $image_field ) && ! empty( $image_field['url'] ) ) {
        return array(
            'url' => $image_field['url'],
            'alt' => ! empty( $image_field['alt'] ) ? $image_field['alt'] : '',
        );
    }

    if ( absint( $image_field ) ) {
        return array(
            'url' => wp_get_attachment_image_url( $image_field, $size ),
            'alt' => get_post_meta( $image_field, '_wp_attachment_image_alt', true ),
        );
    }

    return array( 'url' => esc_url_raw( $image_field ), 'alt' => '' );
}

$portfolio_owner_avatar_data = natsume_portfolio_get_acf_image( $portfolio_owner_avatar, 'thumbnail' );
$project_logo_data           = natsume_portfolio_get_acf_image( $project_logo, 'medium' );
$project_thumbnail_data      = natsume_portfolio_get_acf_image( get_field( 'project_thumbnail', $work_id ), 'portfolio-full' );
$book_cover_data             = natsume_portfolio_get_acf_image( get_field( 'book_cover', $work_id ), 'portfolio-full' );
$preview_video_field         = get_field( 'preview_video', $work_id );
$preview_video_url           = '';
if ( is_array( $preview_video_field ) && ! empty( $preview_video_field['url'] ) ) {
    $preview_video_url = $preview_video_field['url'];
} elseif ( is_string( $preview_video_field ) ) {
    $preview_video_url = $preview_video_field;
}

$category_name = '';
$category_slug = '';
$primary_category = false;
if ( $categories && ! is_wp_error( $categories ) ) {
    $primary_category = $categories[0];
    $category_name = $primary_category->name;
    $category_slug = $primary_category->slug;
}

$gallery = array();
for ( $i = 1; $i <= 6; $i++ ) {
    $gallery_image = get_field( 'work_gallery_' . $i, $work_id );
    if ( empty( $gallery_image ) ) {
        continue;
    }

    $gallery_data = natsume_portfolio_get_acf_image( $gallery_image, 'portfolio-full' );
    if ( $gallery_data['url'] ) {
        $gallery[] = $gallery_data;
    }
}

$media_source = $project_thumbnail_data['url'];
$media_poster = $project_thumbnail_data['url'];
$media_type = 'image';

$category_key = strtolower( $category_name );
if ( false !== strpos( $category_key, 'creative' ) ) {
    if ( $book_cover_data['url'] ) {
        $media_source = $book_cover_data['url'];
        $media_poster = $book_cover_data['url'];
        $media_type = 'image';
    } elseif ( $preview_video_url ) {
        $media_source = $preview_video_url;
        $media_type = 'video';
    }
} elseif ( false !== strpos( $category_key, 'graphic' ) ) {
    $media_type = 'image';
} elseif ( $preview_video_url ) {
    $media_source = $preview_video_url;
    $media_type = 'video';
}

// Category helpers
$is_web_dev = ( false !== strpos( $category_key, 'web' ) || false !== strpos( $category_key, 'system' ) || false !== strpos( $category_key, 'development' ) );
$is_mobile_app = ( false !== strpos( $category_key, 'mobile' ) );
$is_creative_writing = ( false !== strpos( $category_key, 'creative' ) || false !== strpos( $category_key, 'writing' ) );
$is_video_editing = ( false !== strpos( $category_key, 'video' ) );
$is_graphic_design = ( false !== strpos( $category_key, 'graphic' ) );

$related_query = new WP_Query( array(
    'post_type'      => 'work',
    'posts_per_page' => 8,
    'post__not_in'   => array( $work_id ),
    'orderby'        => 'date',
    'order'          => 'DESC',
) );

// Social links from Home Page ACF
$home_id = get_option('page_on_front');
$github_social  = get_field('github_url', $home_id);
$facebook_url   = get_field('facebook_url', $home_id);
$instagram_url  = get_field('instagram_url', $home_id);
$email_url      = get_field('email_url', $home_id);
?>

<article id="single-work-page" class="single-work-page">
    <div class="single-work-shell">
        <div class="single-work-topbar">
            <form class="works-search single-work-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="work-search">Search works</label>
                <input id="work-search" type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="Search works..." />
                <input type="hidden" name="post_type" value="work" />
                <button type="submit" aria-label="Search works"><i class="fa-solid fa-search"></i></button>
            </form>
        </div>

        <div class="single-work__layout">
            <main class="single-work__main">
                <section class="single-work__primary-card">
                    <div class="single-work__media-card">
                        <?php if ( 'video' === $media_type && $media_source ) : ?>
                            <?php if ( $is_web_dev || $is_mobile_app || $is_video_editing ) : ?>
                                <video class="single-work__media-player" src="<?php echo esc_url( $media_source ); ?>" poster="<?php echo esc_url( $media_poster ); ?>" controls playsinline preload="metadata"></video>
                                <div class="video-controls-aux">
                                    <label for="playback-rate">Speed</label>
                                    <select id="playback-rate" class="js-playback-rate">
                                        <option value="0.5">0.5x</option>
                                        <option value="0.75">0.75x</option>
                                        <option value="1" selected>1x</option>
                                        <option value="1.25">1.25x</option>
                                        <option value="1.5">1.5x</option>
                                        <option value="2">2x</option>
                                    </select>
                                </div>
                            <?php else : ?>
                                <video class="single-work__media-player" src="<?php echo esc_url( $media_source ); ?>" poster="<?php echo esc_url( $media_poster ); ?>" muted loop playsinline preload="metadata"></video>
                            <?php endif; ?>
                        <?php elseif ( $media_source ) : ?>
                            <img class="single-work__media-image" src="<?php echo esc_url( $media_source ); ?>" alt="<?php echo esc_attr( $category_name ? $category_name . ' project image' : get_the_title() ); ?>">
                        <?php endif; ?>
                        <?php if ( $featured_work ) : ?>
                            <span class="single-work__badge">Featured</span>
                        <?php endif; ?>
                        <?php if ( $project_logo_data['url'] ) : ?>
                            <div class="single-work__brand single-work__brand--topright" aria-hidden="true">
                                <img src="<?php echo esc_url( $project_logo_data['url'] ); ?>" alt="<?php echo esc_attr( $project_logo_data['alt'] ?: $portfolio_owner_name ); ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="single-work__details">
                        <div class="single-work__eyebrow"><?php echo esc_html( $category_name ?: 'Project' ); ?></div>
                        <h1 class="single-work__title"><?php the_title(); ?></h1>

                       <div class="single-work__author-row">

                        <div class="single-work__author-block">
                            <?php if ( $portfolio_owner_avatar_data['url'] ) : ?>
                                <div class="author-avatar">
                                    <img src="<?php echo esc_url( $portfolio_owner_avatar_data['url'] ); ?>" alt="">
                                </div>
                            <?php endif; ?>

                            <div class="author-copy">
                                <span class="author-name">
                                    <?php echo esc_html( $portfolio_owner_name ); ?>
                                </span>

                                <span class="author-meta">
                                    <?php echo esc_html( $date ); ?>
                                </span>
                            </div>
                        </div>

                        <div class="single-work__engagement">

                            <button class="engagement-btn">
                                <i class="fa-regular fa-thumbs-up"></i>
                                <span>Like</span>
                            </button>

                            <button class="engagement-btn">
                                <i class="fa-regular fa-thumbs-down"></i>
                                <span>Dislike</span>
                            </button>

                            <button class="engagement-btn js-share-project">
                                <i class="fa-solid fa-share"></i>
                                <span>Share</span>
                            </button>

                        </div>

                    </div>

                        <?php if ( $short_description ) : ?>
                            <div class="single-work__summary-wrapper">
                                <div class="single-work__summary js-summary" data-expanded="false">
                                    <?php echo wp_kses_post( wpautop( $short_description ) ); ?>
                                </div>
                                <button class="summary-toggle js-summary-toggle" type="button">See More</button>
                            </div>
                        <?php endif; ?>

                        <div class="single-work__key-values">
                            <?php if ( $client ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-building"></i><span>Client</span><strong><?php echo esc_html( $client ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $project_role ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-briefcase"></i><span>Role</span><strong><?php echo esc_html( $project_role ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $project_duration ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-calendar-days"></i><span>Duration</span><strong><?php echo esc_html( $project_duration ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $project_status ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-circle-check"></i><span>Status</span><strong><?php echo esc_html( $project_status ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $is_creative_writing && $genre ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-book-open"></i><span>Genre</span><strong><?php echo esc_html( $genre ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $is_creative_writing && $story_status ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-pen-nib"></i><span>Story Status</span><strong><?php echo esc_html( $story_status ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $is_video_editing && $video_duration ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-clock"></i><span>Video Length</span><strong><?php echo esc_html( $video_duration ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $is_video_editing && $video_type ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-film"></i><span>Video Type</span><strong><?php echo esc_html( $video_type ); ?></strong></div>
                            <?php endif; ?>
                            <?php if ( $is_graphic_design && $design_type ) : ?>
                                <div class="key-value"><i class="kv-icon fa-solid fa-palette"></i><span>Design Type</span><strong><?php echo esc_html( $design_type ); ?></strong></div>
                            <?php endif; ?>
                        </div>

                        <div class="single-work__action-row">
                            <?php if ( ( $is_web_dev || $is_mobile_app ) && $live_url ) : ?>
                                <a href="<?php echo esc_url( $live_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="Live demo">
                                    <i class="fa fa-globe"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( ( $is_web_dev || $is_mobile_app ) && $github_url ) : ?>
                                <a href="<?php echo esc_url( $github_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="GitHub">
                                    <i class="fab fa-github"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( ( $is_web_dev || $is_mobile_app || $is_video_editing ) && $youtube_link ) : ?>
                                <a href="<?php echo esc_url( $youtube_link ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( $wattpad_link ) : ?>
                                <a href="<?php echo esc_url( $wattpad_link ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="Wattpad">
                                    <i class="fas fa-book-open"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( $story_url ) : ?>
                                <a href="<?php echo esc_url( $story_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="Read Story">
                                    <i class="fas fa-feather-alt"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( $facebook_url ) : ?>
                                <a href="<?php echo esc_url( $facebook_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="Facebook">
                                    <i class="fab fa-facebook"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( $instagram_url ) : ?>
                                <a href="<?php echo esc_url( $instagram_url ); ?>" class="btn btn--ghost" target="_blank" rel="noopener" aria-label="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ( $email_url ) : ?>
                                <a href="mailto:<?php echo esc_attr( $email_url ); ?>" class="btn btn--ghost" aria-label="Email">
                                    <i class="fa fa-envelope"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <?php if ( $gallery ) : ?>
                    <section class="single-work__section single-work__gallery-section">
                        <div class="section-headline">
                            <h2>Project Gallery</h2>
                        </div>
                        <div class="gallery-grid">
                            <?php foreach ( $gallery as $index => $image ) : ?>
                                <button class="gallery-item js-gallery-item" type="button" data-index="<?php echo esc_attr( $index ); ?>" data-src="<?php echo esc_url( $image['url'] ); ?>" data-alt="<?php echo esc_attr( $image['alt'] ); ?>">
                                    <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ( $technologies ) : ?>
                    <section class="single-work__section single-work__technologies-section">
                        <div class="section-headline">
                            <h2>Technologies</h2>
                        </div>
                        <div class="tech-tags tech-tags--wrap">
                            <?php foreach ( $technologies as $tech ) : ?>
                                <span class="tech-tag"><?php echo esc_html( $tech ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ( $post_tags && ! is_wp_error( $post_tags ) ) : ?>
                    <section class="single-work__section single-work__technologies-section">
                        <div class="section-headline">
                            <h2><i class="fa-solid fa-tags" aria-hidden="true" style="margin-right:8px"></i>Tags</h2>
                        </div>
                        <div class="tech-tags tech-tags--wrap">
                            <?php foreach ( $post_tags as $tag ) : ?>
                                <span class="tech-tag"><?php echo esc_html( $tag->name ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </main>

            <aside class="single-work__sidebar">
                <div class="sidebar-panel">
                    <div class="sidebar-panel__header">
                        <span class="sidebar-label">Recommended</span>
                        <h3>Latest works</h3>
                    </div>

                    <?php if ( $related_query->have_posts() ) : ?>
                        <div class="sidebar-projects">
                            <?php while ( $related_query->have_posts() ) : $related_query->the_post();
                                $related_id = get_the_ID();
                                $card_categories = get_the_terms( $related_id, 'work_category' );
                                $card_category = $card_categories && ! is_wp_error( $card_categories ) ? $card_categories[0]->name : '';
                                $card_author_name = get_field( 'portfolio_owner_name', $related_id );
                                $card_avatar = natsume_portfolio_get_acf_image( get_field( 'portfolio_owner_avatar', $related_id ), 'thumbnail' );
                                $card_thumbnail = natsume_portfolio_get_acf_image( get_field( 'project_thumbnail', $related_id ), 'medium' );
                                $card_preview = get_field( 'preview_video', $related_id );
                                $card_preview_url = '';
                                if ( is_array( $card_preview ) && ! empty( $card_preview['url'] ) ) {
                                    $card_preview_url = $card_preview['url'];
                                } elseif ( is_string( $card_preview ) ) {
                                    $card_preview_url = $card_preview;
                                }
                                $card_date = get_field( 'work_date', $related_id );
                                $card_short = get_field( 'short_description', $related_id );
                                $card_excerpt = wp_trim_words( wp_strip_all_tags( $card_short ?: get_the_excerpt( $related_id ) ), 20, '...' );
                            ?>
                                <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>" class="sidebar-card" data-sidebar-video="<?php echo esc_attr( $card_preview_url ? 'true' : 'false' ); ?>">
                                    <div class="sidebar-card__thumb-wrap">
                                        <?php if ( $card_preview_url ) : ?>
                                            <video class="sidebar-card__video" src="<?php echo esc_url( $card_preview_url ); ?>" muted loop playsinline preload="metadata" poster="<?php echo esc_url( $card_thumbnail['url'] ); ?>"></video>
                                        <?php endif; ?>
                                        <?php if ( $card_thumbnail['url'] ) : ?>
                                            <img class="sidebar-card__thumb" src="<?php echo esc_url( $card_thumbnail['url'] ); ?>" alt="<?php echo esc_attr( $card_thumbnail['alt'] ?: get_the_title( $related_id ) ); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="sidebar-card__body">
                                        <?php if ( $card_category ) : ?>
                                            <span class="sidebar-card__category"><?php echo esc_html( $card_category ); ?></span>
                                        <?php endif; ?>
                                        <h4 class="sidebar-card__title"><?php echo esc_html( get_the_title( $related_id ) ); ?></h4>
                                        <p class="sidebar-card__excerpt"><?php echo esc_html( $card_excerpt ); ?></p>
                                        <div class="sidebar-card__meta">
                                            <?php if ( $card_avatar['url'] ) : ?>
                                                <img class="sidebar-card__avatar" src="<?php echo esc_url( $card_avatar['url'] ); ?>" alt="<?php echo esc_attr( $card_avatar['alt'] ?: $card_author_name ); ?>">
                                            <?php endif; ?>
                                            <div class="sidebar-card__text">
                                                <?php if ( $card_author_name ) : ?>
                                                    <span class="sidebar-card__author"><?php echo esc_html( $card_author_name ); ?></span>
                                                <?php endif; ?>
                                                <?php if ( $card_date ) : ?>
                                                    <span class="sidebar-card__date"><?php echo esc_html( $card_date ); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    <?php else : ?>
                        <p class="sidebar-empty">No recommendations available yet.</p>
                    <?php endif; ?>
                </div>
            </aside>
        </div>
    </div>

    <div class="lightbox js-lightbox" aria-hidden="true">
        <button class="lightbox__close js-lightbox-close" type="button" aria-label="Close gallery"></button>
        <div class="lightbox__content">
            <button class="lightbox__nav lightbox__nav--prev js-lightbox-prev" type="button" aria-label="Previous image">&larr;</button>
            <img class="lightbox__image" src="" alt="">
            <button class="lightbox__nav lightbox__nav--next js-lightbox-next" type="button" aria-label="Next image">&rarr;</button>
        </div>
    </div>
</article>
<?php get_footer(); ?>
