<?php
/**
 * Registry for the "Page Sections" flexible content blocks.
 *
 * Every block lives in its own file inside inc/blocks/ and registers
 * itself here. Nothing else needs to be edited to add, remove, or change
 * a block, so blocks never conflict with one another.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

final class Meat_Science_Blocks {

	/**
	 * Registered blocks, keyed by their flexible content layout name.
	 *
	 * @var array<string,array{layout:array,render:callable}>
	 */
	private static $blocks = array();

	/**
	 * Register a page-builder block.
	 *
	 * @param string   $name   Unique block name (used as the flexible content layout "name").
	 * @param string   $label  Human readable label shown in the ACF admin UI.
	 * @param array    $fields ACF `sub_fields` array for this layout.
	 * @param callable $render Callback that outputs the block markup. It receives no
	 *                         arguments; call get_sub_field()/get_field() inside it, since
	 *                         it always runs while ACF's current row is this block.
	 * @return void
	 */
	public static function register( $name, $label, array $fields, callable $render ) {
		self::$blocks[ $name ] = array(
			'layout' => array(
				'key'        => 'layout_meat_science_' . $name,
				'name'       => $name,
				'label'      => $label,
				'sub_fields' => $fields,
			),
			'render' => $render,
		);
	}

	/**
	 * Get the ACF flexible content `layouts` array built from every registered block.
	 *
	 * @return array
	 */
	public static function get_layouts() {
		$layouts = array();

		foreach ( self::$blocks as $block ) {
			$layouts[ $block['layout']['key'] ] = $block['layout'];
		}

		return $layouts;
	}

	/**
	 * Render the block matching a flexible content layout name, if one is registered.
	 *
	 * @param string $layout_name Value returned by get_row_layout().
	 * @return void
	 */
	public static function render( $layout_name ) {
		if ( isset( self::$blocks[ $layout_name ] ) ) {
			call_user_func( self::$blocks[ $layout_name ]['render'] );
		}
	}
}
