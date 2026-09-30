<?php
/**
 * Block: Heading Element.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'heading_element',
	__( 'Heading Element', 'meat-science' ),
	array(
		array(
			'key'   => 'field_meat_science_heading_text',
			'label' => __( 'Heading', 'meat-science' ),
			'name'  => 'heading',
			'type'  => 'text',
		),
	),
	'meat_science_block_heading_render'
);

/**
 * Render the "Heading Element" block.
 */
function meat_science_block_heading_render() {
	$heading = trim( (string) get_sub_field( 'heading' ) );

	if ( '' === $heading ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-builder__block--heading">
		<h2 class="meat-science-builder__heading"><?php echo esc_html( $heading ); ?></h2>
	</section>
	<?php
}
