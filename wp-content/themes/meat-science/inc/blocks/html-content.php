<?php
/**
 * Block: HTML Content.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'html_content',
	__( 'HTML Content', 'meat-science' ),
	array(
		array(
			'key'          => 'field_meat_science_html_content',
			'label'        => __( 'Content', 'meat-science' ),
			'name'         => 'content',
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'full',
			'media_upload' => 1,
		),
	),
	'meat_science_block_html_content_render'
);

/**
 * Render the "HTML Content" block.
 */
function meat_science_block_html_content_render() {
	$content = get_sub_field( 'content' );

	if ( '' === trim( wp_strip_all_tags( (string) $content ) ) && '' === trim( (string) $content ) ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-builder__block--content">
		<div class="meat-science-builder__content">
			<?php echo wp_kses_post( $content ); ?>
		</div>
	</section>
	<?php
}
