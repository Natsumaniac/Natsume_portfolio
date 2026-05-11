<?php
/**
 * Theme Setup
 *
 * @package NatsumePortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', 'natsume_portfolio_setup' );

function natsume_portfolio_setup() {
    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable featured images
    add_theme_support( 'post-thumbnails' );

    // Custom image sizes for portfolio
    add_image_size( 'portfolio-thumb', 600, 400, true );   // Works grid
    add_image_size( 'portfolio-full', 1200, 800, false );   // Works single
    add_image_size( 'certificate-thumb', 400, 300, true );  // Certificate grid
    add_image_size( 'skill-icon', 120, 120, true );         // Skill icon

    // Register navigation menus
    register_nav_menus( array(
        'primary'   => __( 'Primary Navigation', 'natsume-portfolio' ),
        'footer'    => __( 'Footer Navigation', 'natsume-portfolio' ),
    ) );

    // HTML5 support
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Custom logo support
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
}
