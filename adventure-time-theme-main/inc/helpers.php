<?php
/**
 * Theme Helper Functions
 *
 * @package DevPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Parse a textarea ACF field into an array (one item per line).
 * Useful for technologies, tags, etc. without Repeater.
 *
 * @param string $field_value Raw textarea value.
 * @return array
 */
function devportfolio_parse_lines( $field_value ) {
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
function devportfolio_get_work_gallery( $post_id = 0 ) {
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
function devportfolio_proficiency_color( $level ) {
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
function devportfolio_excerpt( $limit = 120 ) {
    $excerpt = get_the_excerpt();
    if ( strlen( $excerpt ) > $limit ) {
        $excerpt = substr( $excerpt, 0, $limit ) . '&hellip;';
    }
    return $excerpt;
}
