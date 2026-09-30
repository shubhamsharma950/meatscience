<?php
/**
 * Block: Call To Action.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'call_to_action',
	__( 'Call To Action', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_call_to_action_image',
			'label'         => __( 'Image', 'meat-science' ),
			'name'          => 'image',
			'type'          => 'image',
			'required'      => 1,
			'return_format' => 'id',
			'preview_size'  => 'large',
			'library'       => 'all',
		),
		array(
			'key'           => 'field_meat_science_call_to_action_eyebrow',
			'label'         => __( 'Eyebrow', 'meat-science' ),
			'name'          => 'eyebrow',
			'type'          => 'text',
			'default_value' => __( 'Make an impact', 'meat-science' ),
		),
		array(
			'key'           => 'field_meat_science_call_to_action_heading',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'text',
			'required'      => 1,
			'default_value' => __( 'Support The AMSA Development Council', 'meat-science' ),
		),
		array(
			'key'       => 'field_meat_science_call_to_action_description',
			'label'     => __( 'Description', 'meat-science' ),
			'name'      => 'description',
			'type'      => 'textarea',
			'rows'      => 3,
			'new_lines' => 'br',
		),
		array(
			'key'           => 'field_meat_science_call_to_action_button',
			'label'         => __( 'Button', 'meat-science' ),
			'name'          => 'button',
			'type'          => 'link',
			'required'      => 1,
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_meat_science_call_to_action_accent_color',
			'label'         => __( 'Accent Color', 'meat-science' ),
			'name'          => 'accent_color',
			'type'          => 'color_picker',
			'default_value' => '#ed1b3b',
		),
	),
	'meat_science_block_call_to_action_render'
);

/**
 * Render the "Call To Action" block.
 */
function meat_science_block_call_to_action_render() {
	$image_id    = (int) get_sub_field( 'image' );
	$eyebrow     = trim( (string) get_sub_field( 'eyebrow' ) );
	$heading     = trim( (string) get_sub_field( 'heading' ) );
	$description = trim( (string) get_sub_field( 'description' ) );
	$button      = meat_science_normalize_link( get_sub_field( 'button' ) );
	$accent      = sanitize_hex_color( (string) get_sub_field( 'accent_color' ) );
	$accent      = $accent ? $accent : '#ed1b3b';

	if ( ! $image_id || '' === $heading || '' === $button['url'] || '' === $button['title'] ) {
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-call-to-action" style="--call-to-action-accent: <?php echo esc_attr( $accent ); ?>;">
		<div class="meat-science-call-to-action__card">
			<div class="meat-science-call-to-action__media">
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'large',
					false,
					array(
						'class'   => 'meat-science-call-to-action__image',
						'loading' => 'lazy',
						'sizes'   => '(max-width: 760px) 100vw, 42vw',
					)
				);
				?>
				<span class="meat-science-call-to-action__mark" aria-hidden="true">
					<span class="meat-science-call-to-action__mark-icon">&plus;</span>
				</span>
			</div>
			<div class="meat-science-call-to-action__content">
				<div class="meat-science-call-to-action__copy">
					<?php if ( '' !== $eyebrow ) : ?>
						<div class="meat-science-call-to-action__eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
					<?php endif; ?>
					<h2 class="meat-science-call-to-action__heading"><?php echo esc_html( $heading ); ?></h2>
					<?php if ( '' !== $description ) : ?>
						<div class="meat-science-call-to-action__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
					<?php endif; ?>
				</div>
				<a class="meat-science-call-to-action__button" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"<?php echo '_blank' === $button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
					<span><?php echo esc_html( $button['title'] ); ?></span>
					<span class="meat-science-call-to-action__button-icon" aria-hidden="true">&rarr;</span>
				</a>
			</div>
		</div>
	</section>
	<?php
}
