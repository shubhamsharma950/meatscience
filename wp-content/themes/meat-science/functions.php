<?php
/**
 * Meat Science theme setup.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features and front-end assets.
 */
function meat_science_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'meat-science' ),
		)
	);
}
add_action( 'after_setup_theme', 'meat_science_setup' );

/**
 * Enqueue theme styles.
 */
function meat_science_enqueue_assets() {
	wp_enqueue_style(
		'meat-science',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'meat_science_enqueue_assets' );
