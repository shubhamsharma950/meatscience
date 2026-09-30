<?php
/**
 * Block: Resource Library.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'resource_library',
	__( 'Resource Library', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_resource_library_title',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => __( 'Resource Library', 'meat-science' ),
		),
		array(
			'key'          => 'field_meat_science_resource_library_items',
			'label'        => __( 'Library Items', 'meat-science' ),
			'name'         => 'items',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => __( 'Add Library Item', 'meat-science' ),
			'sub_fields'   => array(
				array(
					'key'           => 'field_meat_science_resource_library_item_image',
					'label'         => __( 'Image', 'meat-science' ),
					'name'          => 'image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'      => 'field_meat_science_resource_library_item_heading',
					'label'    => __( 'Heading', 'meat-science' ),
					'name'     => 'heading',
					'type'     => 'text',
					'required' => 1,
				),
				array(
					'key'           => 'field_meat_science_resource_library_item_link',
					'label'         => __( 'URL', 'meat-science' ),
					'name'          => 'link',
					'type'          => 'link',
					'required'      => 1,
					'return_format' => 'array',
				),
			),
		),
	),
	'meat_science_block_resource_library_render'
);

/**
 * Render the "Resource Library" block.
 */
function meat_science_block_resource_library_render() {
	$heading = trim( (string) get_sub_field( 'heading' ) );
	$items   = get_sub_field( 'items' );
	$items   = array_values(
		array_filter(
			is_array( $items ) ? $items : array(),
			static function ( $item ) {
				return is_array( $item ) && ! empty( $item['image'] ) && ! empty( $item['heading'] );
			}
		)
	);

	if ( ! $items ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-resource-library" aria-label="<?php echo esc_attr( $heading ? $heading : __( 'Resource Library', 'meat-science' ) ); ?>">
		<?php if ( '' !== $heading ) : ?>
			<h2 class="meat-science-resource-library__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="meat-science-resource-library__grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$image_id   = (int) $item['image'];
				$item_title = trim( (string) $item['heading'] );
				$item_link  = meat_science_normalize_link( isset( $item['link'] ) ? $item['link'] : array() );
				?>
				<article class="meat-science-resource-library__item">
					<?php if ( '' !== $item_link['url'] ) : ?>
						<a class="meat-science-resource-library__link" href="<?php echo esc_url( $item_link['url'] ); ?>" target="<?php echo esc_attr( $item_link['target'] ); ?>"<?php echo '_blank' === $item_link['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
					<?php endif; ?>
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'medium',
						false,
						array(
							'class'   => 'meat-science-resource-library__image',
							'loading' => 'lazy',
						)
					);
					?>
					<h3 class="meat-science-resource-library__item-heading"><?php echo esc_html( $item_title ); ?></h3>
					<?php if ( '' !== $item_link['url'] ) : ?>
						</a>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
}
