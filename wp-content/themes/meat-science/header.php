<?php
/**
 * The theme header.
 *
 * The ACF-managed utility header is rendered by the must-use integration
 * attached to wp_body_open().
 *
 * @package MeatScience
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="meat-science-site">
