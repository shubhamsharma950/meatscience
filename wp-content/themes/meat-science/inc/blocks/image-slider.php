<?php
/**
 * Block: Image Slider.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'image_slider',
	__( 'Image Slider', 'meat-science' ),
	array(
		array(
			'key'          => 'field_meat_science_builder_slides',
			'label'        => __( 'Slides', 'meat-science' ),
			'name'         => 'slides',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => __( 'Add Slide', 'meat-science' ),
			'sub_fields'   => array(
				array(
					'key'           => 'field_meat_science_builder_slide_image',
					'label'         => __( 'Background Image', 'meat-science' ),
					'name'          => 'image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'large',
					'library'       => 'all',
				),
				array(
					'key'   => 'field_meat_science_builder_slide_heading',
					'label' => __( 'Heading', 'meat-science' ),
					'name'  => 'heading',
					'type'  => 'text',
				),
				array(
					'key'       => 'field_meat_science_builder_slide_text',
					'label'     => __( 'Paragraph', 'meat-science' ),
					'name'      => 'text',
					'type'      => 'textarea',
					'rows'      => 4,
					'new_lines' => 'br',
				),
				array(
					'key'           => 'field_meat_science_builder_slide_button',
					'label'         => __( 'Button', 'meat-science' ),
					'name'          => 'button',
					'type'          => 'link',
					'return_format' => 'array',
				),
			),
		),
	),
	'meat_science_block_image_slider_render'
);

/**
 * Render the "Image Slider" block.
 */
function meat_science_block_image_slider_render() {
	$slides = get_sub_field( 'slides' );

	if ( is_array( $slides ) && $slides ) {
		meat_science_render_slider( $slides, __( 'Featured content', 'meat-science' ), 'meat-science-slider--builder' );
	}
}
