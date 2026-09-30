<?php
/**
 * Block: Partner Logo Marquee.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'partner_marquee',
	__( 'Partner Logo Marquee', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_partner_marquee_heading',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'text',
			'default_value' => __( 'Our Trusted Partners', 'meat-science' ),
		),
		array(
			'key'          => 'field_meat_science_partner_marquee_logos',
			'label'        => __( 'Partner Logos', 'meat-science' ),
			'name'         => 'logos',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => __( 'Add Partner', 'meat-science' ),
			'min'          => 1,
			'sub_fields'   => array(
				array(
					'key'           => 'field_meat_science_partner_marquee_logo_image',
					'label'         => __( 'Logo', 'meat-science' ),
					'name'          => 'image',
					'type'          => 'image',
					'required'      => 1,
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_meat_science_partner_marquee_logo_link',
					'label'         => __( 'Redirect Link', 'meat-science' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
			),
		),
		array(
			'key'           => 'field_meat_science_partner_marquee_speed',
			'label'         => __( 'Rotation Duration', 'meat-science' ),
			'name'          => 'duration',
			'type'          => 'number',
			'instructions'  => __( 'Seconds for one complete rotation. A larger number moves more slowly.', 'meat-science' ),
			'default_value' => 28,
			'min'           => 10,
			'max'           => 120,
			'step'          => 1,
		),
		array(
			'key'           => 'field_meat_science_partner_marquee_all_link',
			'label'         => __( 'View All Link', 'meat-science' ),
			'name'          => 'all_link',
			'type'          => 'link',
			'return_format' => 'array',
		),
	),
	'meat_science_block_partner_marquee_render'
);

/**
 * Render one partner logo group.
 *
 * @param array $logos       Partner logo rows.
 * @param bool  $is_duplicate Whether this is the visually duplicated group.
 */
function meat_science_partner_marquee_render_group( $logos, $is_duplicate = false ) {
	?>
	<div class="meat-science-partner-marquee__group"<?php echo $is_duplicate ? ' aria-hidden="true"' : ''; ?>>
		<?php foreach ( $logos as $logo ) : ?>
			<?php
			$image_id = (int) $logo['image'];
			$link     = meat_science_normalize_link( $logo['link'] ?? array() );
			$image    = wp_get_attachment_image(
				$image_id,
				'medium',
				false,
				array(
					'class'   => 'meat-science-partner-marquee__image',
					'loading' => 'lazy',
				)
			);

			if ( ! $image ) {
				continue;
			}
			?>
			<div class="meat-science-partner-marquee__item">
				<?php if ( '' !== $link['url'] ) : ?>
					<a class="meat-science-partner-marquee__logo-link" href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>"<?php echo '_blank' === $link['target'] ? ' rel="noopener noreferrer"' : ''; ?><?php echo '' !== $link['title'] ? ' aria-label="' . esc_attr( $link['title'] ) . '"' : ''; ?><?php echo $is_duplicate ? ' tabindex="-1"' : ''; ?>>
						<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php else : ?>
					<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render the "Partner Logo Marquee" block.
 */
function meat_science_block_partner_marquee_render() {
	$heading  = trim( (string) get_sub_field( 'heading' ) );
	$logos    = get_sub_field( 'logos' );
	$duration = (int) get_sub_field( 'duration' );
	$duration = min( 120, max( 10, $duration ? $duration : 28 ) );
	$all_link = meat_science_normalize_link( get_sub_field( 'all_link' ) );
	$logos    = array_values(
		array_filter(
			is_array( $logos ) ? $logos : array(),
			static function ( $logo ) {
				return is_array( $logo ) && ! empty( $logo['image'] );
			}
		)
	);

	if ( ! $logos ) {
		return;
	}
	?>
	<section class="meat-science-partner-marquee" style="--partner-marquee-duration: <?php echo esc_attr( $duration ); ?>s;" aria-label="<?php echo esc_attr( $heading ? $heading : __( 'Our partners', 'meat-science' ) ); ?>">
		<?php if ( '' !== $heading ) : ?>
			<h2 class="meat-science-partner-marquee__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="meat-science-partner-marquee__viewport">
			<div class="meat-science-partner-marquee__track">
				<?php meat_science_partner_marquee_render_group( $logos ); ?>
				<?php meat_science_partner_marquee_render_group( $logos, true ); ?>
			</div>
		</div>
		<?php if ( '' !== $all_link['url'] && '' !== $all_link['title'] ) : ?>
			<a class="meat-science-partner-marquee__all-link" href="<?php echo esc_url( $all_link['url'] ); ?>" target="<?php echo esc_attr( $all_link['target'] ); ?>"<?php echo '_blank' === $all_link['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
				<span><?php echo esc_html( $all_link['title'] ); ?></span>
				<span aria-hidden="true">&rarr;</span>
			</a>
		<?php endif; ?>
	</section>
	<?php
}
