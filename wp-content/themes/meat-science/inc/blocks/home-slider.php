<?php
/**
 * Block: Homepage Slider.
 *
 * A standalone ACF field group (not a "Page Sections" flexible content
 * layout) shown only on the static front page. Self-contained: registers
 * its own fields and renders itself.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the ACF homepage slider fields.
 */
function meat_science_register_slider_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'        => 'group_meat_science_home_slider',
			'title'      => __( 'Homepage Slider', 'meat-science' ),
			'fields'     => array(
				array(
					'key'          => 'field_meat_science_slides',
					'label'        => __( 'Slides', 'meat-science' ),
					'name'         => 'meat_science_slides',
					'type'         => 'repeater',
					'layout'       => 'block',
					'button_label' => __( 'Add Slide', 'meat-science' ),
					'sub_fields'   => array(
						array(
							'key'           => 'field_meat_science_slide_image',
							'label'         => __( 'Background Image', 'meat-science' ),
							'name'          => 'image',
							'type'          => 'image',
							'required'      => 1,
							'return_format' => 'id',
							'preview_size'  => 'large',
							'library'       => 'all',
						),
						array(
							'key'   => 'field_meat_science_slide_heading',
							'label' => __( 'Heading', 'meat-science' ),
							'name'  => 'heading',
							'type'  => 'text',
						),
						array(
							'key'       => 'field_meat_science_slide_text',
							'label'     => __( 'Paragraph', 'meat-science' ),
							'name'      => 'text',
							'type'      => 'textarea',
							'rows'      => 4,
							'new_lines' => 'br',
						),
						array(
							'key'           => 'field_meat_science_slide_button',
							'label'         => __( 'Button', 'meat-science' ),
							'name'          => 'button',
							'type'          => 'link',
							'return_format' => 'array',
						),
					),
				),
			),
			'location'   => array(
				array(
					array(
						'param'    => 'page_type',
						'operator' => '==',
						'value'    => 'front_page',
					),
				),
			),
			'menu_order' => 0,
			'position'   => 'acf_after_title',
			'style'      => 'default',
			'active'     => true,
		)
	);
}
add_action( 'acf/init', 'meat_science_register_slider_fields' );

/**
 * Render the homepage slider.
 */
function meat_science_render_home_slider() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$slides = get_field( 'meat_science_slides' );
	meat_science_render_slider( $slides, __( 'Featured content', 'meat-science' ) );
}
