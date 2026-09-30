<?php
/**
 * Block: Resource Downloads.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'resource_downloads',
	__( 'Resource Downloads', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_resource_downloads_heading',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => __( 'Latest Resource Guides', 'meat-science' ),
		),
		array(
			'key'          => 'field_meat_science_resource_downloads_items',
			'label'        => __( 'Download Items', 'meat-science' ),
			'name'         => 'items',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => __( 'Add Download Item', 'meat-science' ),
			'sub_fields'   => array(
				array(
					'key'      => 'field_meat_science_resource_downloads_item_title',
					'label'    => __( 'Title', 'meat-science' ),
					'name'     => 'title',
					'type'     => 'text',
					'required' => 1,
				),
				array(
					'key'           => 'field_meat_science_resource_downloads_item_type',
					'label'         => __( 'File Type', 'meat-science' ),
					'name'          => 'type',
					'type'          => 'text',
					'default_value' => __( 'PDF', 'meat-science' ),
				),
				array(
					'key'   => 'field_meat_science_resource_downloads_item_size',
					'label' => __( 'File Size', 'meat-science' ),
					'name'  => 'size',
					'type'  => 'text',
				),
				array(
					'key'           => 'field_meat_science_resource_downloads_item_link',
					'label'         => __( 'Download Link', 'meat-science' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
			),
		),
	),
	'meat_science_block_resource_downloads_render'
);

/**
 * Render the "Resource Downloads" block.
 */
function meat_science_block_resource_downloads_render() {
	$heading = trim( (string) get_sub_field( 'heading' ) );
	$items   = get_sub_field( 'items' );
	$items   = array_values(
		array_filter(
			is_array( $items ) ? $items : array(),
			static function ( $item ) {
				return is_array( $item ) && ! empty( $item['title'] );
			}
		)
	);

	if ( ! $items ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-resource-downloads" aria-label="<?php echo esc_attr( $heading ? $heading : __( 'Resource Downloads', 'meat-science' ) ); ?>">
		<?php if ( '' !== $heading ) : ?>
			<h2 class="meat-science-resource-downloads__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="meat-science-resource-downloads__table-wrapper">
			<table class="meat-science-resource-downloads__table">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Title', 'meat-science' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Type', 'meat-science' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Size', 'meat-science' ); ?></th>
						<th scope="col"><span class="screen-reader-text"><?php esc_html_e( 'Download', 'meat-science' ); ?></span></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $items as $item ) : ?>
						<?php
						$title = trim( (string) $item['title'] );
						$type  = trim( (string) ( isset( $item['type'] ) ? $item['type'] : '' ) );
						$size  = trim( (string) ( isset( $item['size'] ) ? $item['size'] : '' ) );
						$link  = meat_science_normalize_link( isset( $item['link'] ) ? $item['link'] : array() );
						?>
						<tr>
							<th scope="row" data-label="<?php esc_attr_e( 'Title', 'meat-science' ); ?>"><?php echo esc_html( $title ); ?></th>
							<td data-label="<?php esc_attr_e( 'Type', 'meat-science' ); ?>"><?php echo esc_html( $type ); ?></td>
							<td data-label="<?php esc_attr_e( 'Size', 'meat-science' ); ?>"><?php echo esc_html( $size ); ?></td>
							<td class="meat-science-resource-downloads__action">
								<?php if ( '' !== $link['url'] ) : ?>
									<a class="meat-science-resource-downloads__button" href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>"<?php echo '_blank' === $link['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
										<?php esc_html_e( 'Download', 'meat-science' ); ?>
									</a>
								<?php else : ?>
									<span class="meat-science-resource-downloads__button meat-science-resource-downloads__button--pending" aria-disabled="true">
										<?php esc_html_e( 'Download', 'meat-science' ); ?>
									</span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</section>
	<?php
}
