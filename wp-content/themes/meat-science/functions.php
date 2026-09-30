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
			'primary'    => __( 'Primary Menu', 'meat-science' ),
			'header_left'  => __( 'Header Left Menu', 'meat-science' ),
			'header_right' => __( 'Header Right Menu', 'meat-science' ),
		)
	);
}
add_action( 'after_setup_theme', 'meat_science_setup' );

/**
 * Enqueue theme styles.
 */
function meat_science_enqueue_assets() {
	wp_enqueue_style(
		'meat-science-font',
		'https://fonts.googleapis.com/css2?family=Asap+Condensed:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'meat-science',
		get_stylesheet_uri(),
		array( 'meat-science-font' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_style(
		'meat-science-slider',
		get_template_directory_uri() . '/assets/css/slider.css',
		array( 'meat-science' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_script(
		'meat-science-slider',
		get_template_directory_uri() . '/assets/js/slider.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'meat_science_enqueue_assets' );

/**
 * Page-builder blocks.
 *
 * - inc/class-meat-science-blocks.php : the block registry.
 * - inc/helpers.php                   : utilities shared by more than one block.
 * - inc/loader.php                    : loads every file in inc/blocks/.
 * - inc/blocks/*.php                  : one self-contained file per block
 *                                       (fields + renderer). Add a new file
 *                                       there to add a new block; nothing
 *                                       else needs to change.
 * - inc/page-builder.php              : builds the flexible content field
 *                                       from the registry and renders it.
 */
require_once get_template_directory() . '/inc/class-meat-science-blocks.php';
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/loader.php';
require_once get_template_directory() . '/inc/page-builder.php';
