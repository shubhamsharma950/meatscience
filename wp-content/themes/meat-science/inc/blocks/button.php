<?php
/**
 * Block: Button.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'button',
	__( 'Button', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_builder_button',
			'label'         => __( 'Button Link', 'meat-science' ),
			'name'          => 'button',
			'type'          => 'link',
			'return_format' => 'array',
		),
	),
	'meat_science_block_button_render'
);

/**
 * Render the "Button" block.
 */
function meat_science_block_button_render() {
	$button = meat_science_normalize_link( get_sub_field( 'button' ) );

	if ( '' === $button['url'] || '' === $button['title'] ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-builder__block--button">
		<a class="meat-science-builder__button" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"<?php echo '_blank' === $button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
			<?php echo esc_html( $button['title'] ); ?>
		</a>
	</section>
	<?php
}
