<?php
/**
 * Natsume Portfolio - functions.php
 * Theme setup, CPT registration, ACF field groups, and asset enqueue.
 *
 * @package NatsumePortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NATSUME_PORTFOLIO_VERSION', '1.0.0' );
define( 'NATSUME_PORTFOLIO_DIR', get_template_directory() );
define( 'NATSUME_PORTFOLIO_URI', get_template_directory_uri() );

// ─── Include Modules ────────────────────────────────────────────────
require_once NATSUME_PORTFOLIO_DIR . '/inc/theme-setup.php';
require_once NATSUME_PORTFOLIO_DIR . '/inc/custom-post-types.php';
require_once NATSUME_PORTFOLIO_DIR . '/inc/enqueue.php';
require_once NATSUME_PORTFOLIO_DIR . '/inc/helpers.php';

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

// ─── Fallback Menu Function ──────────────────────────────────────────
function natsume_portfolio_fallback_menu() {
    echo '<ul class="nav-links">';
    echo '<li><a href="' . esc_url(home_url('/about/')) . '"><i class="fas fa-user"></i> About</a></li>';
    echo '<li><a href="' . esc_url(home_url('/works/')) . '"><i class="fas fa-briefcase"></i> Works</a></li>';
    echo '<li><a href="' . esc_url(home_url('/skills/')) . '"><i class="fas fa-bolt"></i> Skills</a></li>';
    echo '<li><a href="' . esc_url(home_url('/certificates/')) . '"><i class="fas fa-certificate"></i> Certificates</a></li>';
    echo '<li><a href="' . esc_url(home_url('/contact/')) . '"><i class="fas fa-envelope"></i> Contact</a></li>';
    echo '</ul>';
}

// ─── ACF Theme Option Pages ─────────────────────────────────────────
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug'  => 'theme-settings',
        'capability' => 'edit_posts',
        'redirect'   => false
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Global Header',
        'menu_title'  => 'Global Header',
        'parent_slug' => 'theme-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Global Footer',
        'menu_title'  => 'Global Footer',
        'parent_slug' => 'theme-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'  => 'Global Social Links',
        'menu_title'  => 'Global Social Links',
        'parent_slug' => 'theme-settings',
    ));
}