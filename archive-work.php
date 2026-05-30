<?php
/**
 * Archive: Works
 *
 * @package NatsumePortfolio
 */

get_header();

$categories = get_terms( array(
    'taxonomy'   => 'work_category',
    'hide_empty' => true,
) );

$search_query = get_search_query();

if ( $categories && ! is_wp_error( $categories ) ) :
    foreach ( $categories as $cat ) :
        $term_slug = strtolower( $cat->slug );
        $term_name = strtolower( $cat->name );
        $icon_class = 'fa-tag';

        if ( strpos( $term_slug, 'web' ) !== false || strpos( $term_name, 'web' ) !== false ) {
            $icon_class = 'fa-desktop';
        } elseif ( strpos( $term_slug, 'mobile' ) !== false || strpos( $term_name, 'mobile' ) !== false ) {
            $icon_class = 'fa-mobile-screen';
        } elseif ( strpos( $term_slug, 'creative' ) !== false || strpos( $term_name, 'creative' ) !== false ) {
            $icon_class = 'fa-book-open';
        } elseif ( strpos( $term_slug, 'video' ) !== false || strpos( $term_name, 'video' ) !== false ) {
            $icon_class = 'fa-film';
        } elseif ( strpos( $term_slug, 'graphic' ) !== false || strpos( $term_name, 'graphic' ) !== false ) {
            $icon_class = 'fa-palette';
        }
    endforeach;
endif;
?>

<section id="works-archive" class="works-archive">
    <div class="works-archive__wrap">

        <main class="works-main">
            <div class="works-topbar">

                <div class="works-intro">
                    <h1>My Works Showcase</h1>
                </div>

                <form class="works-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label class="screen-reader-text" for="work-search">Search works</label>
                    <input id="work-search" type="search" name="s" value="<?php echo esc_attr( $search_query ); ?>" placeholder="Search works..." />
                    <input type="hidden" name="post_type" value="work" />
                    <button type="submit" aria-label="Search works"><i class="fa-solid fa-search"></i></button>
                </form>

                <div class="works-filter">

                <button
                    class="works-filter-btn active"
                    data-category="all">
                    All Projects
                </button>

                <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                    <?php foreach ( $categories as $cat ) : ?>

                        <button
                            class="works-filter-btn"
                            data-category="<?php echo esc_attr( $cat->slug ); ?>">

                            <?php echo esc_html( $cat->name ); ?>

                        </button>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

            </div>

            <?php if ( have_posts() ) : ?>
                <div class="works-grid">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <?php
                        $terms          = get_the_terms( get_the_ID(), 'work_category' );
                        $category_name  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : 'Uncategorized';
                        $category_slug  = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->slug : 'uncategorized';
                        $project_image  = get_field( 'project_thumbnail' );
                        $preview_video  = get_field( 'preview_video' );
                        $book_cover     = get_field( 'book_cover' );
                        $short_desc     = get_field( 'short_description' );
                        $work_date = get_field( 'work_date' );
                        $portfolio_owner_name   = get_field( 'portfolio_owner_name' );
                        $portfolio_owner_avatar = get_field( 'portfolio_owner_avatar' );

                        $portfolio_owner_avatar_url = '';

                        if (
                            is_array($portfolio_owner_avatar)
                            && !empty($portfolio_owner_avatar['url'])
                        ) {
                            $portfolio_owner_avatar_url =
                                $portfolio_owner_avatar['url'];
                        }
                        $default_image  = '';

                        if ( is_array( $project_image ) && ! empty( $project_image['url'] ) ) {
                            $default_image = $project_image['url'];
                        } elseif ( is_string( $project_image ) && $project_image ) {
                            $default_image = $project_image;
                        } elseif ( has_post_thumbnail() ) {
                            $default_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                        } else {
                            $default_image = get_template_directory_uri() . '/assets/images/placeholder-work.jpg';
                        }

                        $preview_video_url = '';
                        if ( is_array( $preview_video ) && ! empty( $preview_video['url'] ) ) {
                            $preview_video_url = $preview_video['url'];
                        } elseif ( is_string( $preview_video ) ) {
                            $preview_video_url = $preview_video;
                        }

                        $book_cover_url = '';
                        if ( is_array( $book_cover ) && ! empty( $book_cover['url'] ) ) {
                            $book_cover_url = $book_cover['url'];
                        } elseif ( is_string( $book_cover ) ) {
                            $book_cover_url = $book_cover;
                        }

                        $short_desc = $short_desc ? $short_desc : get_the_excerpt();
                        $short_desc = wp_trim_words( wp_strip_all_tags( $short_desc ), 18, '...' );

                        $use_video = false;
                        $media_url = $default_image;

                        if ( stripos( $category_name, 'web system' ) !== false || stripos( $category_name, 'video editing' ) !== false ) {
                            $use_video = ! empty( $preview_video_url );
                        } elseif ( stripos( $category_name, 'creative writing' ) !== false && $book_cover_url ) {
                            $media_url = $book_cover_url;
                        } elseif ( stripos( $category_name, 'graphic design' ) !== false ) {
                            $media_url = $default_image;
                        }

                        // Map category to icon
                        $category_icon = 'fa-tag';
                        if ( stripos( $category_name, 'web' ) !== false ) {
                            $category_icon = 'fa-desktop';
                        } elseif ( stripos( $category_name, 'mobile' ) !== false ) {
                            $category_icon = 'fa-mobile-screen';
                        } elseif ( stripos( $category_name, 'creative' ) !== false ) {
                            $category_icon = 'fa-book-open';
                        } elseif ( stripos( $category_name, 'video' ) !== false ) {
                            $category_icon = 'fa-video';
                        } elseif ( stripos( $category_name, 'graphic' ) !== false ) {
                            $category_icon = 'fa-palette';
                        }
                        ?>

                        <article
    class="work-card work-card--<?php echo esc_attr( $category_slug ); ?>"
    data-category="<?php echo esc_attr( $category_slug ); ?>">
                            <a class="work-card__link" href="<?php the_permalink(); ?>">
                                <div class="work-card__media">
                                    <?php if ( $use_video ) : ?>
                                        <img class="work-card__poster" src="<?php echo esc_url( $default_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?> poster" loading="lazy" />
                                        <video class="work-card__video" muted loop playsinline preload="metadata" poster="<?php echo esc_url( $default_image ); ?>" data-preview>
                                            <source src="<?php echo esc_url( $preview_video_url ); ?>" type="video/mp4" />
                                            <?php esc_html_e( 'Your browser does not support video playback.', 'natsume-portfolio' ); ?>
                                        </video>
                                    <?php else : ?>
                                        <img class="work-card__poster" src="<?php echo esc_url( $media_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
                                    <?php endif; ?>
                                    <!-- Floating category icon -->
                                    <div class="work-card__category-icon">
                                        <i class="fa-solid <?php echo esc_attr( $category_icon ); ?>"></i>
                                    </div>
                                </div>

                                <div class="work-card__content">

                                <div class="work-card__body">

                                <h2 class="work-card__title">
                                <?php the_title(); ?>
                            </h2>

                            <p class="work-card__description">
                                <?php echo esc_html( $short_desc ); ?>
                            </p>
                            </div>

                            <div class="work-card__meta">

                                <div class="work-card__author">

                                    <?php if ( $portfolio_owner_avatar_url ) : ?>
                                        <img
                                            class="work-card__avatar"
                                            src="<?php echo esc_url( $portfolio_owner_avatar_url ); ?>"
                                            alt="<?php echo esc_attr( $portfolio_owner_name ); ?>">
                                    <?php endif; ?>

                                    <span class="work-card__author-name">
                                        <?php echo esc_html( $portfolio_owner_name ); ?>
                                    </span>

                                </div>

                                <?php if ( $work_date ) : ?>

                                    <div class="work-card__date">

                                        <?php
                                        echo esc_html(
                                            date_i18n(
                                                'F Y',
                                                strtotime( $work_date )
                                            )
                                        );
                                        ?>

                                    </div>

                                <?php endif; ?>

                            </div>
                                                        </a>
                        </article>
                    <?php endwhile; ?>
                </div>

                <div class="works-pagination">
                    <?php the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '&laquo; Prev',
                        'next_text' => 'Next &raquo;',
                    ) ); ?>
                </div>
            <?php else : ?>
                <div class="works-empty">
                    <div class="works-empty__inner">
                        <div class="works-empty__icon">
                            <i class="fa-solid fa-briefcase"></i>
                        </div>
                        <h3 class="works-empty__title">No projects yet</h3>
                        <p class="works-empty__subtitle">There are currently no works to display. Add projects to populate this gallery.</p>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</section>

<?php get_footer(); ?>
