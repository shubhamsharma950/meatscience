<?php
/**
 * Block: Video Feature.
 *
 * Self-contained: registers its fields, renders the block, and also owns
 * the shared video popup modal markup (only printed in the footer when a
 * Video Feature block actually needs it).
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'video_feature',
	__( 'Video Feature', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_video_feature_image',
			'label'         => __( 'Image', 'meat-science' ),
			'name'          => 'image',
			'type'          => 'image',
			'required'      => 1,
			'return_format' => 'id',
			'preview_size'  => 'large',
			'library'       => 'all',
		),
		array(
			'key'          => 'field_meat_science_video_feature_video_url',
			'label'        => __( 'Video URL', 'meat-science' ),
			'name'         => 'video_url',
			'type'         => 'url',
			'instructions' => __( 'YouTube, Vimeo, or a direct MP4 file link. Plays in a popup when the play button is clicked.', 'meat-science' ),
			'required'     => 1,
		),
		array(
			'key'           => 'field_meat_science_video_feature_badge',
			'label'         => __( 'Badge Heading', 'meat-science' ),
			'name'          => 'badge',
			'type'          => 'text',
			'instructions'  => __( 'Short heading shown in the red badge.', 'meat-science' ),
			'required'      => 1,
			'default_value' => __( 'The Core Purpose Of AMSA', 'meat-science' ),
		),
		array(
			'key'       => 'field_meat_science_video_feature_description',
			'label'     => __( 'Description', 'meat-science' ),
			'name'      => 'description',
			'type'      => 'textarea',
			'rows'      => 3,
			'new_lines' => 'br',
		),
		array(
			'key'           => 'field_meat_science_video_feature_list_heading',
			'label'         => __( 'List Box Heading', 'meat-science' ),
			'name'          => 'list_heading',
			'type'          => 'text',
			'default_value' => __( 'Our Core Strategies', 'meat-science' ),
		),
		array(
			'key'          => 'field_meat_science_video_feature_list_items',
			'label'        => __( 'List Items', 'meat-science' ),
			'name'         => 'list_items',
			'type'         => 'repeater',
			'layout'       => 'table',
			'button_label' => __( 'Add Item', 'meat-science' ),
			'sub_fields'   => array(
				array(
					'key'      => 'field_meat_science_video_feature_list_item_text',
					'label'    => __( 'Text', 'meat-science' ),
					'name'     => 'text',
					'type'     => 'text',
					'required' => 1,
				),
			),
		),
	),
	'meat_science_block_video_feature_render'
);

/**
 * Convert a YouTube, Vimeo, or direct file URL into a popup-ready embed URL.
 *
 * @param string $url Raw video URL entered in ACF.
 * @return array{type:string,src:string} Embed type ("iframe" or "video") and source URL.
 */
function meat_science_video_feature_get_embed( $url ) {
	$url = trim( (string) $url );

	if ( '' === $url ) {
		return array(
			'type' => '',
			'src'  => '',
		);
	}

	// YouTube.
	if ( preg_match( '#(?:youtube\.com/(?:watch\?v=|shorts/|embed/)|youtu\.be/)([\w-]+)#i', $url, $matches ) ) {
		return array(
			'type' => 'iframe',
			'src'  => 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0',
		);
	}

	// Vimeo.
	if ( preg_match( '#vimeo\.com/(?:video/)?(\d+)#i', $url, $matches ) ) {
		return array(
			'type' => 'iframe',
			'src'  => 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1',
		);
	}

	// Direct video file (mp4/webm/ogg).
	if ( preg_match( '#\.(mp4|webm|ogg)(\?.*)?$#i', $url ) ) {
		return array(
			'type' => 'video',
			'src'  => $url,
		);
	}

	// Fallback: try WordPress oEmbed-style iframe embed for any other provider.
	return array(
		'type' => 'iframe',
		'src'  => $url,
	);
}

/**
 * Flag that the video popup modal markup/assets are needed on this page load.
 */
function meat_science_video_feature_enqueue_modal_assets() {
	add_filter( 'meat_science_needs_video_modal', '__return_true' );
}

/**
 * Render the "Video Feature" block: image with play button, video popup,
 * a badge heading, description, and a checklist box.
 */
function meat_science_block_video_feature_render() {
	$image_id     = (int) get_sub_field( 'image' );
	$video_url    = trim( (string) get_sub_field( 'video_url' ) );
	$badge        = trim( (string) get_sub_field( 'badge' ) );
	$description  = trim( (string) get_sub_field( 'description' ) );
	$list_heading = trim( (string) get_sub_field( 'list_heading' ) );
	$list_items   = get_sub_field( 'list_items' );
	$list_items   = array_values(
		array_filter(
			is_array( $list_items ) ? $list_items : array(),
			static function ( $item ) {
				return is_array( $item ) && '' !== trim( (string) ( $item['text'] ?? '' ) );
			}
		)
	);

	if ( ! $image_id || '' === $video_url ) {
		return;
	}

	$embed = meat_science_video_feature_get_embed( $video_url );

	if ( '' === $embed['src'] ) {
		return;
	}

	meat_science_video_feature_enqueue_modal_assets();
	?>
	<section class="meat-science-builder__block meat-science-video-feature">
		<div class="meat-science-video-feature__media">
			<div class="meat-science-video-feature__frame">
				<?php
				echo wp_get_attachment_image(
					$image_id,
					'large',
					false,
					array(
						'class'   => 'meat-science-video-feature__image',
						'loading' => 'lazy',
					)
				);
				?>
				<button class="meat-science-video-feature__play" type="button" data-video-trigger data-video-type="<?php echo esc_attr( $embed['type'] ); ?>" data-video-src="<?php echo esc_url( $embed['src'] ); ?>" aria-label="<?php esc_attr_e( 'Play video', 'meat-science' ); ?>">
					<span aria-hidden="true">&#9658;</span>
				</button>
			</div>
		</div>
		<div class="meat-science-video-feature__content">
			<?php if ( '' !== $badge ) : ?>
				<div class="meat-science-video-feature__badge"><?php echo esc_html( $badge ); ?></div>
			<?php endif; ?>
			<?php if ( '' !== $description ) : ?>
				<div class="meat-science-video-feature__description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
			<?php if ( $list_items ) : ?>
				<div class="meat-science-video-feature__list-box">
					<?php if ( '' !== $list_heading ) : ?>
						<h3 class="meat-science-video-feature__list-heading"><?php echo esc_html( $list_heading ); ?></h3>
					<?php endif; ?>
					<ul class="meat-science-video-feature__list">
						<?php foreach ( $list_items as $item ) : ?>
							<li class="meat-science-video-feature__list-item">
								<span class="meat-science-video-feature__list-icon" aria-hidden="true">&#10003;</span>
								<span><?php echo esc_html( trim( (string) $item['text'] ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}

/**
 * Output the shared video popup modal markup once in the footer, only when needed.
 */
function meat_science_video_feature_render_modal() {
	if ( ! apply_filters( 'meat_science_needs_video_modal', false ) ) {
		return;
	}
	?>
	<div class="meat-science-video-modal" data-video-modal hidden>
		<div class="meat-science-video-modal__overlay" data-video-modal-close></div>
		<div class="meat-science-video-modal__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Video player', 'meat-science' ); ?>">
			<button class="meat-science-video-modal__close" type="button" data-video-modal-close aria-label="<?php esc_attr_e( 'Close video', 'meat-science' ); ?>">&times;</button>
			<div class="meat-science-video-modal__body" data-video-modal-body></div>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'meat_science_video_feature_render_modal' );
