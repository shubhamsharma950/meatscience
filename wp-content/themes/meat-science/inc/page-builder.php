<?php
/**
 * Assembles the "Page Sections" flexible content field from every block
 * registered in inc/blocks/, and renders the sections on the front end.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register reusable ACF page sections (flexible content field).
 *
 * The list of available layouts comes entirely from Meat_Science_Blocks,
 * so this function never needs to change when blocks are added or removed.
 */
function meat_science_register_page_sections_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		array(
			'key'        => 'group_meat_science_page_sections',
			'title'      => __( 'Page Content Section', 'meat-science' ),
			'fields'     => array(
				array(
					'key'          => 'field_meat_science_page_sections',
					'label'        => __( 'Page Sections', 'meat-science' ),
					'name'         => 'meat_science_page_sections',
					'type'         => 'flexible_content',
					'button_label' => __( 'Add Section', 'meat-science' ),
					'layouts'      => Meat_Science_Blocks::get_layouts(),
				),
			),
			'location'   => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'page',
					),
				),
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'post',
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
add_action( 'acf/init', 'meat_science_register_page_sections_fields' );

/**
 * Render reusable flexible page sections.
 *
 * @param int      $post_id         Post ID.
 * @param string[] $excluded_layouts Layouts to omit from the front end.
 * @return bool
 */
function meat_science_render_page_sections( $post_id = 0, $excluded_layouts = array() ) {
	if ( ! function_exists( 'have_rows' ) ) {
		return false;
	}

	$post_id = $post_id ? (int) $post_id : (int) get_queried_object_id();
	if ( ! $post_id || ! have_rows( 'meat_science_page_sections', $post_id ) ) {
		return false;
	}

	$rendered = false;
	?>
	<div class="meat-science-builder">
		<?php
		while ( have_rows( 'meat_science_page_sections', $post_id ) ) :
			the_row();
			if ( in_array( get_row_layout(), $excluded_layouts, true ) ) {
				continue;
			}
			$rendered = true;
			Meat_Science_Blocks::render( get_row_layout() );
		endwhile;
		?>
	</div>
	<?php

	return $rendered;
}
