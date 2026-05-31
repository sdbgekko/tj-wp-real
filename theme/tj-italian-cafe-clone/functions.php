<?php
/**
 * TJ's Italian Cafe Clone — Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TJ_THEME_VERSION', '1.0.0' );
define( 'TJ_THEME_URI', get_template_directory_uri() );

// ---------------------------------------------------------------
// Theme Setup
// ---------------------------------------------------------------

function tj_setup() {
    // Translations
    load_theme_textdomain( 'tj-italian-cafe', get_template_directory() . '/languages' );

    // Block editor stylesheet
    add_editor_style( 'assets/css/editor.css' );

    // Supported features
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 300,
        'flex-width'  => true,
        'flex-height' => true,
    ] );

    // Nav menus
    register_nav_menus( [
        'primary'   => __( 'Primary Navigation', 'tj-italian-cafe' ),
        'footer'    => __( 'Footer Navigation', 'tj-italian-cafe' ),
    ] );
}
add_action( 'after_setup_theme', 'tj_setup' );

// ---------------------------------------------------------------
// Enqueue Styles + Scripts
// ---------------------------------------------------------------

function tj_enqueue_assets() {
    // Main stylesheet (includes Google Fonts import)
    wp_enqueue_style(
        'tj-main',
        get_stylesheet_uri(),
        [],
        TJ_THEME_VERSION
    );

    // Mobile nav toggle script
    wp_enqueue_script(
        'tj-nav',
        TJ_THEME_URI . '/assets/js/nav.js',
        [],
        TJ_THEME_VERSION,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'tj_enqueue_assets' );

// ---------------------------------------------------------------
// Widget Areas
// ---------------------------------------------------------------

function tj_register_widgets() {
    register_sidebar( [
        'name'          => __( 'Footer Widget Area', 'tj-italian-cafe' ),
        'id'            => 'footer-widgets',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="footer-widget__title">',
        'after_title'   => '</h4>',
    ] );
}
add_action( 'widgets_init', 'tj_register_widgets' );

// ---------------------------------------------------------------
// Block Patterns
// ---------------------------------------------------------------

function tj_register_block_patterns() {
    register_block_pattern_category( 'tj-cafe', [
        'label' => __( "TJ's Cafe", 'tj-italian-cafe' ),
    ] );
}
add_action( 'init', 'tj_register_block_patterns' );

// ---------------------------------------------------------------
// Custom Page Title Support
// ---------------------------------------------------------------

function tj_wp_title( $title ) {
    global $page, $paged;
    if ( is_feed() ) return $title;
    $title .= get_bloginfo( 'name', 'display' );
    return $title;
}

// ---------------------------------------------------------------
// Remove unnecessary HEAD junk
// ---------------------------------------------------------------

remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// ---------------------------------------------------------------
// Excerpt length
// ---------------------------------------------------------------

function tj_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'tj_excerpt_length', 999 );
