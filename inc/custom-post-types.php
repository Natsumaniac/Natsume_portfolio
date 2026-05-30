<?php
/**
 * Custom Post Types Registration
 *
 * Registers: Works, Skills, Certificates
 * Also registers: Work Category taxonomy
 *
 * @package NatsumePortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'natsume_portfolio_register_post_types' );
add_action( 'init', 'natsume_portfolio_register_taxonomies' );

/**
 * Register Custom Post Types
 */
function natsume_portfolio_register_post_types() {

    // ─── Works (Portfolio Projects) ─────────────────────────────────
    register_post_type( 'work', array(
        'labels' => array(
            'name'               => __( 'Works', 'natsume-portfolio' ),
            'singular_name'      => __( 'Work', 'natsume-portfolio' ),
            'add_new'            => __( 'Add New Work', 'natsume-portfolio' ),
            'add_new_item'       => __( 'Add New Work', 'natsume-portfolio' ),
            'edit_item'          => __( 'Edit Work', 'natsume-portfolio' ),
            'view_item'          => __( 'View Work', 'natsume-portfolio' ),
            'all_items'          => __( 'All Works', 'natsume-portfolio' ),
            'search_items'       => __( 'Search Works', 'natsume-portfolio' ),
            'not_found'          => __( 'No works found.', 'natsume-portfolio' ),
            'not_found_in_trash' => __( 'No works found in Trash.', 'natsume-portfolio' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'works' ),
        'menu_icon'          => 'dashicons-portfolio',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'       => true,
        'menu_position'      => 5,
    ) );

    // ─── Skills ─────────────────────────────────────────────────────
    register_post_type( 'skill', array(
        'labels' => array(
            'name'               => __( 'Skills', 'natsume-portfolio' ),
            'singular_name'      => __( 'Skill', 'natsume-portfolio' ),
            'add_new'            => __( 'Add New Skill', 'natsume-portfolio' ),
            'add_new_item'       => __( 'Add New Skill', 'natsume-portfolio' ),
            'edit_item'          => __( 'Edit Skill', 'natsume-portfolio' ),
            'view_item'          => __( 'View Skill', 'natsume-portfolio' ),
            'all_items'          => __( 'All Skills', 'natsume-portfolio' ),
            'search_items'       => __( 'Search Skills', 'natsume-portfolio' ),
            'not_found'          => __( 'No skills found.', 'natsume-portfolio' ),
            'not_found_in_trash' => __( 'No skills found in Trash.', 'natsume-portfolio' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'skills' ),
        'menu_icon'          => 'dashicons-lightbulb',
        'supports'           => array( 'title', 'thumbnail' ),
        'show_in_rest'       => true,
        'menu_position'      => 6,
    ) );

    // ─── Certificates ───────────────────────────────────────────────
    register_post_type( 'certificate', array(
        'labels' => array(
            'name'               => __( 'Certificates', 'natsume-portfolio' ),
            'singular_name'      => __( 'Certificate', 'natsume-portfolio' ),
            'add_new'            => __( 'Add New Certificate', 'natsume-portfolio' ),
            'add_new_item'       => __( 'Add New Certificate', 'natsume-portfolio' ),
            'edit_item'          => __( 'Edit Certificate', 'natsume-portfolio' ),
            'view_item'          => __( 'View Certificate', 'natsume-portfolio' ),
            'all_items'          => __( 'All Certificates', 'natsume-portfolio' ),
            'search_items'       => __( 'Search Certificates', 'natsume-portfolio' ),
            'not_found'          => __( 'No certificates found.', 'natsume-portfolio' ),
            'not_found_in_trash' => __( 'No certificates found in Trash.', 'natsume-portfolio' ),
        ),
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'certificates' ),
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array( 'title', 'thumbnail' ),
        'show_in_rest'       => true,
        'menu_position'      => 7,
    ) );
}

/**
 * Register Taxonomies
 */
function natsume_portfolio_register_taxonomies() {

    // ─── Work Category ──────────────────────────────────────────────
    register_taxonomy( 'work_category', 'work', array(
        'labels' => array(
            'name'          => __( 'Work Categories', 'natsume-portfolio' ),
            'singular_name' => __( 'Work Category', 'natsume-portfolio' ),
            'add_new_item'  => __( 'Add New Category', 'natsume-portfolio' ),
            'edit_item'     => __( 'Edit Category', 'natsume-portfolio' ),
            'all_items'     => __( 'All Categories', 'natsume-portfolio' ),
            'search_items'  => __( 'Search Categories', 'natsume-portfolio' ),
        ),
        'public'            => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'work-category' ),
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'show_ui'           => true,
    ) );

    // ─── Work Tags ──────────────────────────────────────────────────
    register_taxonomy( 'work_tag', 'work', array(
        'labels' => array(
            'name'          => __( 'Work Tags', 'natsume-portfolio' ),
            'singular_name' => __( 'Work Tag', 'natsume-portfolio' ),
            'search_items'  => __( 'Search Work Tags', 'natsume-portfolio' ),
            'all_items'     => __( 'All Work Tags', 'natsume-portfolio' ),
            'edit_item'     => __( 'Edit Work Tag', 'natsume-portfolio' ),
            'add_new_item'  => __( 'Add New Work Tag', 'natsume-portfolio' ),
            'new_item_name' => __( 'New Work Tag Name', 'natsume-portfolio' ),
            'menu_name'     => __( 'Work Tags', 'natsume-portfolio' ),
        ),
        'public'            => true,
        'hierarchical'      => false,
        'rewrite'           => array( 'slug' => 'work-tag' ),
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'show_ui'           => true,
    ) );

    // Create default terms if they don't already exist.
    $natsume_default_work_terms = array(
        'Web System Development',
        'Mobile App Development',
        'Creative Writing',
        'Video Editing',
        'Graphic Design',
    );

    foreach ( $natsume_default_work_terms as $natsume_term ) {
        if ( ! term_exists( $natsume_term, 'work_category' ) ) {
            wp_insert_term( $natsume_term, 'work_category', array( 'slug' => sanitize_title( $natsume_term ) ) );
        }
    }

    // ─── Skill Category (e.g., Frontend, Backend, Tools) ────────────
    register_taxonomy( 'skill_category', 'skill', array(
        'labels' => array(
            'name'          => __( 'Skill Categories', 'natsume-portfolio' ),
            'singular_name' => __( 'Skill Category', 'natsume-portfolio' ),
            'add_new_item'  => __( 'Add New Category', 'natsume-portfolio' ),
        ),
        'public'            => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'skill-category' ),
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ) );
}

/**
 * Flush rewrite rules on theme activation
 */
add_action( 'after_switch_theme', 'natsume_portfolio_flush_rewrite' );
function natsume_portfolio_flush_rewrite() {
    natsume_portfolio_register_post_types();
    natsume_portfolio_register_taxonomies();
    flush_rewrite_rules();
}
