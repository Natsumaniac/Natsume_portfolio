<?php
/**
 * Theme Helper Functions
 *
 * @package NatsumePortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Parse a textarea ACF field into an array (one item per line).
 * Useful for technologies, tags, etc. without Repeater.
 *
 * @param string $field_value Raw textarea value.
 * @return array
 */
function natsume_portfolio_parse_lines( $field_value ) {
    if ( empty( $field_value ) ) {
        return array();
    }
    $lines = explode( "\n", $field_value );
    return array_filter( array_map( 'trim', $lines ) );
}

/**
 * Get gallery images for a Work post (free ACF workaround).
 * Returns an array of image arrays from individual gallery fields.
 *
 * @param int $post_id
 * @return array
 */
function natsume_portfolio_get_work_gallery( $post_id = 0 ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    $gallery = array();
    for ( $i = 1; $i <= 3; $i++ ) {
        $img = get_field( 'work_gallery_' . $i, $post_id );
        if ( $img ) {
            $gallery[] = $img;
        }
    }
    return $gallery;
}

/**
 * Get proficiency color class based on level.
 *
 * @param string $level
 * @return string CSS class name
 */
function natsume_portfolio_proficiency_color( $level ) {
    $map = array(
        'beginner'     => 'proficiency--beginner',
        'intermediate' => 'proficiency--intermediate',
        'advanced'     => 'proficiency--advanced',
        'expert'       => 'proficiency--expert',
    );
    return isset( $map[ $level ] ) ? $map[ $level ] : '';
}

/**
 * Custom excerpt with character limit.
 *
 * @param int $limit Character count.
 * @return string
 */
function natsume_portfolio_excerpt( $limit = 120 ) {
    $excerpt = get_the_excerpt();
    if ( strlen( $excerpt ) > $limit ) {
        $excerpt = substr( $excerpt, 0, $limit ) . '&hellip;';
    }
    return $excerpt;
}

/**
 * Resolve the public Resume Viewer page URL.
 *
 * @return string
 */
function natsume_portfolio_get_resume_viewer_url() {
    $resume_page = get_page_by_path( 'resume', OBJECT, 'page' );

    return $resume_page ? get_permalink( $resume_page ) : home_url( '/resume/' );
}

/**
 * Resolve the resume file used by the Resume Viewer.
 * Prefers the front-page hero PDF, then the About page file.
 *
 * @return array{url:string,source:string,title:string,filename:string}
 */
function natsume_portfolio_get_resume_file_data() {
    $sources = array(
        array(
            'post_id' => (int) get_option( 'page_on_front' ),
            'field'   => 'hero_resume',
            'source'  => 'front_page',
        ),
    );

    $about_page = get_page_by_path( 'about', OBJECT, 'page' );
    if ( $about_page instanceof WP_Post ) {
        $sources[] = array(
            'post_id' => (int) $about_page->ID,
            'field'   => 'resume_file',
            'source'  => 'about_page',
        );
    }

    foreach ( $sources as $source ) {
        if ( empty( $source['post_id'] ) ) {
            continue;
        }

        $resume = get_field( $source['field'], $source['post_id'] );
        $url    = '';
        $title  = '';
        $file   = '';

        if ( is_array( $resume ) ) {
            if ( ! empty( $resume['url'] ) ) {
                $url = esc_url_raw( $resume['url'] );
            }

            if ( ! empty( $resume['title'] ) ) {
                $title = (string) $resume['title'];
            }

            if ( ! empty( $resume['filename'] ) ) {
                $file = (string) $resume['filename'];
            }
        } elseif ( is_numeric( $resume ) ) {
            $url = wp_get_attachment_url( (int) $resume );
            $title = get_the_title( (int) $resume );
            $attached_file = get_attached_file( (int) $resume );
            $file = $attached_file ? basename( $attached_file ) : '';
        } elseif ( is_string( $resume ) ) {
            $url = esc_url_raw( $resume );
            $parsed_path = wp_parse_url( $resume, PHP_URL_PATH );
            $file = $parsed_path ? basename( $parsed_path ) : '';
        }

        if ( $url ) {
            return array(
                'url'      => $url,
                'source'   => $source['source'],
                'title'    => $title ? $title : 'Resume PDF',
                'filename' => $file ? $file : 'resume.pdf',
            );
        }
    }

    return array(
        'url'      => '',
        'source'   => '',
        'title'    => '',
        'filename' => '',
    );
}

/**
 * Render a subtle futuristic transition between major content sections.
 *
 * @param string $variant Optional visual variant class.
 * @return void
 */
function natsume_portfolio_render_section_transition( $variant = 'section-transition--grid' ) {
    $classes = array( 'section-transition', sanitize_html_class( $variant ) );
    ?>
    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" aria-hidden="true">
        <span class="section-transition__rail section-transition__rail--left"></span>
        <span class="section-transition__node"></span>
        <span class="section-transition__rail section-transition__rail--right"></span>
    </div>
    <?php
}
