<?php
/**
 * Enqueue Styles & Scripts
 *
 * @package NatsumePortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'natsume_portfolio_enqueue_assets' );

function natsume_portfolio_enqueue_assets() {

    // ─── Google Fonts ───────────────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap',
        array(),
        null
    );

    // ─── Font Awesome Icons ────────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        array(),
        null
    );

    // ─── Main Theme Stylesheet ──────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-main',
        NATSUME_PORTFOLIO_URI . '/assets/css/main.css',
        array(),
        NATSUME_PORTFOLIO_VERSION
    );

    // ─── Header Stylesheet ───────────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-header',
        NATSUME_PORTFOLIO_URI . '/assets/css/header.css',
        array(),
        NATSUME_PORTFOLIO_VERSION
    );

    // ─── Home Stylesheet ─────────────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-home',
        NATSUME_PORTFOLIO_URI . '/assets/css/home.css',
        array(),
        NATSUME_PORTFOLIO_VERSION
    );

    // ─── Components Stylesheet ────────────────────────────────────────
    wp_enqueue_style(
        'natsume_portfolio-components',
        NATSUME_PORTFOLIO_URI . '/assets/css/components.css',
        array(),
        NATSUME_PORTFOLIO_VERSION
    );

    // ─── Main JS ────────────────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-main',
        NATSUME_PORTFOLIO_URI . '/assets/js/main.js',
        array(),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── Typing Animation JS ─────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-typing',
        NATSUME_PORTFOLIO_URI . '/assets/js/typing.js',
        array(),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── Navigation Active JS ─────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-nav-active',
        NATSUME_PORTFOLIO_URI . '/assets/js/nav-active.js',
        array(),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── Works Slider JS ─────────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-work',
        NATSUME_PORTFOLIO_URI . '/assets/js/work.js',
        array(),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── GSAP Core ───────────────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
        array(),
        null,
        true
    );

    // ─── GSAP ScrollTrigger ──────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-scrolltrigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js',
        array('natsume_portfolio-gsap'),
        null,
        true
    );

    // ─── Certificate JS ───────────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-cert',
        NATSUME_PORTFOLIO_URI . '/assets/js/cert.js',
        array('natsume_portfolio-scrolltrigger'),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── Home Page Animations ─────────────────────────────────────────
    wp_enqueue_script(
        'natsume_portfolio-home',
        NATSUME_PORTFOLIO_URI . '/assets/js/home.js',
        array('natsume_portfolio-scrolltrigger'),
        NATSUME_PORTFOLIO_VERSION,
        true
    );

    // ─── Page Styles (About Page) ──────────────────────────────────────
    if (is_page_template('templates/page-about.php')) {
        wp_enqueue_style(
            'natsume_portfolio-page',
            NATSUME_PORTFOLIO_URI . '/assets/css/about.css',
            array(),
            NATSUME_PORTFOLIO_VERSION
        );

        wp_enqueue_script(
            'natsume_portfolio-about-smoke',
            NATSUME_PORTFOLIO_URI . '/assets/js/about-smoke.js',
            array(),
            NATSUME_PORTFOLIO_VERSION,
            true
        );

        wp_enqueue_script(
            'natsume_portfolio-beyond',
            NATSUME_PORTFOLIO_URI . '/assets/js/beyond-content.js',
            array(),
            NATSUME_PORTFOLIO_VERSION,
            true
        );
    }
}
