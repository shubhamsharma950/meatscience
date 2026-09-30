<?php
/**
 * Block: Membership Spotlight.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'membership_spotlight',
	__( 'Membership Spotlight', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_membership_spotlight_background_color',
			'label'         => __( 'Section Background Color', 'meat-science' ),
			'name'          => 'background_color',
			'type'          => 'color_picker',
			'default_value' => '#243947',
		),
		array(
			'key'           => 'field_meat_science_membership_spotlight_badge',
			'label'         => __( 'Badge Text', 'meat-science' ),
			'name'          => 'badge',
			'type'          => 'text',
			'default_value' => __( 'AMSA Membership', 'meat-science' ),
		),
		array(
			'key'           => 'field_meat_science_membership_spotlight_heading',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'textarea',
			'rows'          => 3,
			'new_lines'     => 'br',
			'default_value' => __( 'AMSA Is A Far-Reaching Conduit For Academic And Professional Collaboration And Learning.', 'meat-science' ),
		),
		array(
			'key'       => 'field_meat_science_membership_spotlight_description',
			'label'     => __( 'Description', 'meat-science' ),
			'name'      => 'description',
			'type'      => 'textarea',
			'rows'      => 4,
			'new_lines' => 'br',
		),
		array(
			'key'           => 'field_meat_science_membership_spotlight_primary_button',
			'label'         => __( 'Primary Button', 'meat-science' ),
			'name'          => 'primary_button',
			'type'          => 'link',
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_meat_science_membership_spotlight_secondary_button',
			'label'         => __( 'Secondary Button', 'meat-science' ),
			'name'          => 'secondary_button',
			'type'          => 'link',
			'return_format' => 'array',
		),
		array(
			'key'          => 'field_meat_science_membership_spotlight_cards',
			'label'        => __( 'Spotlight Cards', 'meat-science' ),
			'name'         => 'cards',
			'type'         => 'repeater',
			'layout'       => 'block',
			'button_label' => __( 'Add Spotlight Card', 'meat-science' ),
			'sub_fields'   => array(
				array(
					'key'           => 'field_meat_science_membership_spotlight_card_image',
					'label'         => __( 'Image', 'meat-science' ),
					'name'          => 'image',
					'type'          => 'image',
					'return_format' => 'id',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'      => 'field_meat_science_membership_spotlight_card_name',
					'label'    => __( 'Name', 'meat-science' ),
					'name'     => 'name',
					'type'     => 'text',
					'required' => 1,
				),
				array(
					'key'       => 'field_meat_science_membership_spotlight_card_description',
					'label'     => __( 'Description', 'meat-science' ),
					'name'      => 'description',
					'type'      => 'textarea',
					'rows'      => 3,
					'new_lines' => 'br',
				),
				array(
					'key'           => 'field_meat_science_membership_spotlight_card_link',
					'label'         => __( 'Card Link', 'meat-science' ),
					'name'          => 'link',
					'type'          => 'link',
					'return_format' => 'array',
				),
			),
		),
	),
	'meat_science_block_membership_spotlight_render'
);

/**
 * Render the "Membership Spotlight" block.
 */
function meat_science_block_membership_spotlight_render() {
	$background_color = sanitize_hex_color( (string) get_sub_field( 'background_color' ) );
	$background_color = $background_color ? $background_color : '#243947';
	$badge             = trim( (string) get_sub_field( 'badge' ) );
	$heading           = trim( (string) get_sub_field( 'heading' ) );
	$description       = trim( (string) get_sub_field( 'description' ) );
	$primary_button    = meat_science_normalize_link( get_sub_field( 'primary_button' ) );
	$secondary_button  = meat_science_normalize_link( get_sub_field( 'secondary_button' ) );
	$cards             = get_sub_field( 'cards' );
	$cards             = array_values(
		array_filter(
			is_array( $cards ) ? $cards : array(),
			static function ( $card ) {
				return is_array( $card ) && '' !== trim( (string) ( $card['name'] ?? '' ) );
			}
		)
	);

	if ( '' === $badge && '' === $heading && '' === $description && '' === $primary_button['url'] && '' === $secondary_button['url'] && ! $cards ) {
		return;
	}
	?>
	<section class="meat-science-membership-spotlight" style="--membership-spotlight-bg: <?php echo esc_attr( $background_color ); ?>;">
		<div class="meat-science-membership-spotlight__inner">
			<div class="meat-science-membership-spotlight__intro">
				<div class="meat-science-membership-spotlight__headline">
					<?php if ( '' !== $badge ) : ?>
						<div class="meat-science-membership-spotlight__badge"><?php echo esc_html( $badge ); ?></div>
					<?php endif; ?>
					<?php if ( '' !== $heading ) : ?>
						<h2 class="meat-science-membership-spotlight__heading"><?php echo wp_kses_post( nl2br( esc_html( $heading ) ) ); ?></h2>
					<?php endif; ?>
				</div>
				<div class="meat-science-membership-spotlight__copy">
					<?php if ( '' !== $description ) : ?>
						<div class="meat-science-membership-spotlight__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
					<?php endif; ?>
					<?php if ( ( '' !== $primary_button['url'] && '' !== $primary_button['title'] ) || ( '' !== $secondary_button['url'] && '' !== $secondary_button['title'] ) ) : ?>
						<div class="meat-science-membership-spotlight__actions">
							<?php if ( '' !== $primary_button['url'] && '' !== $primary_button['title'] ) : ?>
								<a class="meat-science-membership-spotlight__button meat-science-membership-spotlight__button--primary" href="<?php echo esc_url( $primary_button['url'] ); ?>" target="<?php echo esc_attr( $primary_button['target'] ); ?>"<?php echo '_blank' === $primary_button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
									<span class="meat-science-membership-spotlight__button-text"><?php echo esc_html( $primary_button['title'] ); ?></span>
									<span class="meat-science-membership-spotlight__button-icon" aria-hidden="true">&rarr;</span>
								</a>
							<?php endif; ?>
							<?php if ( '' !== $secondary_button['url'] && '' !== $secondary_button['title'] ) : ?>
								<a class="meat-science-membership-spotlight__button meat-science-membership-spotlight__button--secondary" href="<?php echo esc_url( $secondary_button['url'] ); ?>" target="<?php echo esc_attr( $secondary_button['target'] ); ?>"<?php echo '_blank' === $secondary_button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
									<span class="meat-science-membership-spotlight__button-text"><?php echo esc_html( $secondary_button['title'] ); ?></span>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $cards ) : ?>
				<div class="meat-science-membership-spotlight__cards">
					<?php foreach ( $cards as $card ) : ?>
						<?php
						$image_id         = isset( $card['image'] ) ? (int) $card['image'] : 0;
						$card_name        = trim( (string) $card['name'] );
						$card_description = trim( (string) ( $card['description'] ?? '' ) );
						$card_link        = meat_science_normalize_link( $card['link'] ?? array() );
						?>
						<article class="meat-science-membership-spotlight__card">
							<?php if ( $image_id ) : ?>
								<?php
								echo wp_get_attachment_image(
									$image_id,
									'medium',
									false,
									array(
										'class'   => 'meat-science-membership-spotlight__card-image',
										'loading' => 'lazy',
									)
								);
								?>
							<?php endif; ?>
							<h3 class="meat-science-membership-spotlight__card-title"><?php echo esc_html( $card_name ); ?></h3>
							<?php if ( '' !== $card_description ) : ?>
								<div class="meat-science-membership-spotlight__card-description"><?php echo wp_kses_post( wpautop( $card_description ) ); ?></div>
							<?php endif; ?>
							<?php if ( '' !== $card_link['url'] ) : ?>
								<a class="meat-science-membership-spotlight__card-link" href="<?php echo esc_url( $card_link['url'] ); ?>" target="<?php echo esc_attr( $card_link['target'] ); ?>"<?php echo '_blank' === $card_link['target'] ? ' rel="noopener noreferrer"' : ''; ?> aria-label="<?php echo esc_attr( sprintf( __( 'Read more about %s', 'meat-science' ), $card_name ) ); ?>">
									<span class="meat-science-membership-spotlight__card-link-icon" aria-hidden="true">&rarr;</span>
									<span class="meat-science-membership-spotlight__card-link-text"><?php esc_html_e( 'Explore More', 'meat-science' ); ?></span>
								</a>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
