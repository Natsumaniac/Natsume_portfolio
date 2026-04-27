<?php
/**
 * Dev Portfolio - functions.php
 * Theme setup, CPT registration, ACF field groups, and asset enqueue.
 *
 * @package DevPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DEV_PORTFOLIO_VERSION', '1.0.0' );
define( 'DEV_PORTFOLIO_DIR', get_template_directory() );
define( 'DEV_PORTFOLIO_URI', get_template_directory_uri() );

// ─── Include Modules ────────────────────────────────────────────────
require_once DEV_PORTFOLIO_DIR . '/inc/theme-setup.php';
require_once DEV_PORTFOLIO_DIR . '/inc/custom-post-types.php';
require_once DEV_PORTFOLIO_DIR . '/inc/enqueue.php';
require_once DEV_PORTFOLIO_DIR . '/inc/helpers.php';

// ─── Add Icons to Navigation Menu ──────────────────────────────────
add_filter('wp_nav_menu_objects', 'add_nav_menu_icons', 10, 2);
function add_nav_menu_icons($items, $args) {
    if ($args->theme_location === 'primary') {
        $icons = array(
            'About' => '<i class="fas fa-user"></i>',
            'Works' => '<i class="fas fa-briefcase"></i>',
            'Skills' => '<i class="fas fa-bolt"></i>',
            'Certificates' => '<i class="fas fa-certificate"></i>',
            'Contact' => '<i class="fas fa-envelope"></i>',
        );
        
        foreach ($items as $item) {
            $title = $item->title;
            if (isset($icons[$title])) {
                $item->title = $icons[$title] . ' ' . $title;
            }
        }
    }
    return $items;
}

// ─── Add Body Classes for Page Templates ────────────────────────────
add_filter('body_class', 'add_page_template_body_class');
function add_page_template_body_class($classes) {
    if (is_page_template('templates/page-about.php')) {
        $classes[] = 'page-template-about';
    }
    return $classes;
}
