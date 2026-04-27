<?php
/**
 * Enqueue Styles & Scripts
 *
 * @package DevPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'devportfolio_enqueue_assets' );

function devportfolio_enqueue_assets() {

    // ─── Google Fonts ───────────────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap',
        array(),
        null
    );

    // ─── Font Awesome Icons ────────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        array(),
        null
    );

    // ─── Main Theme Stylesheet ──────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-main',
        DEV_PORTFOLIO_URI . '/assets/css/main.css',
        array(),
        DEV_PORTFOLIO_VERSION
    );

    // ─── Header Stylesheet ───────────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-header',
        DEV_PORTFOLIO_URI . '/assets/css/header.css',
        array(),
        DEV_PORTFOLIO_VERSION
    );

    // ─── Home Stylesheet ─────────────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-home',
        DEV_PORTFOLIO_URI . '/assets/css/home.css',
        array(),
        DEV_PORTFOLIO_VERSION
    );

    // ─── Components Stylesheet ────────────────────────────────────────
    wp_enqueue_style(
        'devportfolio-components',
        DEV_PORTFOLIO_URI . '/assets/css/components.css',
        array(),
        DEV_PORTFOLIO_VERSION
    );

    // ─── Main JS ────────────────────────────────────────────────────
    wp_enqueue_script(
        'devportfolio-main',
        DEV_PORTFOLIO_URI . '/assets/js/main.js',
        array(),
        DEV_PORTFOLIO_VERSION,
        true
    );

    // ─── Typing Animation JS ─────────────────────────────────────────
    wp_enqueue_script(
        'devportfolio-typing',
        DEV_PORTFOLIO_URI . '/assets/js/typing.js',
        array(),
        DEV_PORTFOLIO_VERSION,
        true
    );

    // ─── Navigation Active JS ─────────────────────────────────────────
    wp_enqueue_script(
        'devportfolio-nav-active',
        DEV_PORTFOLIO_URI . '/assets/js/nav-active.js',
        array(),
        DEV_PORTFOLIO_VERSION,
        true
    );

    // ─── Works Slider JS ─────────────────────────────────────────────
    wp_enqueue_script(
        'devportfolio-work',
        DEV_PORTFOLIO_URI . '/assets/js/work.js',
        array(),
        DEV_PORTFOLIO_VERSION,
        true
    );

    // ─── Certificate JS ───────────────────────────────────────────────
    wp_enqueue_script(
        'devportfolio-cert',
        DEV_PORTFOLIO_URI . '/assets/js/cert.js',
        array(),
        DEV_PORTFOLIO_VERSION,
        true
    );

    // ─── Page Styles (About Page) ──────────────────────────────────────
    if (is_page_template('templates/page-about.php')) {
        wp_enqueue_style(
            'devportfolio-page',
            DEV_PORTFOLIO_URI . '/assets/css/page.css',
            array(),
            DEV_PORTFOLIO_VERSION
        );
    }
}
