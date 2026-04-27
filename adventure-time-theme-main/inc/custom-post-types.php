<?php
/**
 * Custom Post Types Registration
 *
 * Registers: Works, Skills, Certificates
 * Also registers: Work Category taxonomy
 *
 * @package DevPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'devportfolio_register_post_types' );
add_action( 'init', 'devportfolio_register_taxonomies' );

/**
 * Register Custom Post Types
 */
function devportfolio_register_post_types() {

    // ─── Works (Portfolio Projects) ─────────────────────────────────
    register_post_type( 'work', array(
        'labels' => array(
            'name'               => __( 'Works', 'dev-portfolio' ),
            'singular_name'      => __( 'Work', 'dev-portfolio' ),
            'add_new'            => __( 'Add New Work', 'dev-portfolio' ),
            'add_new_item'       => __( 'Add New Work', 'dev-portfolio' ),
            'edit_item'          => __( 'Edit Work', 'dev-portfolio' ),
            'view_item'          => __( 'View Work', 'dev-portfolio' ),
            'all_items'          => __( 'All Works', 'dev-portfolio' ),
            'search_items'       => __( 'Search Works', 'dev-portfolio' ),
            'not_found'          => __( 'No works found.', 'dev-portfolio' ),
            'not_found_in_trash' => __( 'No works found in Trash.', 'dev-portfolio' ),
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
            'name'               => __( 'Skills', 'dev-portfolio' ),
            'singular_name'      => __( 'Skill', 'dev-portfolio' ),
            'add_new'            => __( 'Add New Skill', 'dev-portfolio' ),
            'add_new_item'       => __( 'Add New Skill', 'dev-portfolio' ),
            'edit_item'          => __( 'Edit Skill', 'dev-portfolio' ),
            'view_item'          => __( 'View Skill', 'dev-portfolio' ),
            'all_items'          => __( 'All Skills', 'dev-portfolio' ),
            'search_items'       => __( 'Search Skills', 'dev-portfolio' ),
            'not_found'          => __( 'No skills found.', 'dev-portfolio' ),
            'not_found_in_trash' => __( 'No skills found in Trash.', 'dev-portfolio' ),
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
            'name'               => __( 'Certificates', 'dev-portfolio' ),
            'singular_name'      => __( 'Certificate', 'dev-portfolio' ),
            'add_new'            => __( 'Add New Certificate', 'dev-portfolio' ),
            'add_new_item'       => __( 'Add New Certificate', 'dev-portfolio' ),
            'edit_item'          => __( 'Edit Certificate', 'dev-portfolio' ),
            'view_item'          => __( 'View Certificate', 'dev-portfolio' ),
            'all_items'          => __( 'All Certificates', 'dev-portfolio' ),
            'search_items'       => __( 'Search Certificates', 'dev-portfolio' ),
            'not_found'          => __( 'No certificates found.', 'dev-portfolio' ),
            'not_found_in_trash' => __( 'No certificates found in Trash.', 'dev-portfolio' ),
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
function devportfolio_register_taxonomies() {

    // ─── Work Category ──────────────────────────────────────────────
    register_taxonomy( 'work_category', 'work', array(
        'labels' => array(
            'name'          => __( 'Work Categories', 'dev-portfolio' ),
            'singular_name' => __( 'Work Category', 'dev-portfolio' ),
            'add_new_item'  => __( 'Add New Category', 'dev-portfolio' ),
            'edit_item'     => __( 'Edit Category', 'dev-portfolio' ),
            'all_items'     => __( 'All Categories', 'dev-portfolio' ),
            'search_items'  => __( 'Search Categories', 'dev-portfolio' ),
        ),
        'public'            => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'work-category' ),
        'show_in_rest'      => true,
        'show_admin_column' => true,
    ) );

    // ─── Skill Category (e.g., Frontend, Backend, Tools) ────────────
    register_taxonomy( 'skill_category', 'skill', array(
        'labels' => array(
            'name'          => __( 'Skill Categories', 'dev-portfolio' ),
            'singular_name' => __( 'Skill Category', 'dev-portfolio' ),
            'add_new_item'  => __( 'Add New Category', 'dev-portfolio' ),
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
add_action( 'after_switch_theme', 'devportfolio_flush_rewrite' );
function devportfolio_flush_rewrite() {
    devportfolio_register_post_types();
    devportfolio_register_taxonomies();
    flush_rewrite_rules();
}
