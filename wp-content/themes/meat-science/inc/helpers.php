<?php
/**
 * Small utilities shared by more than one block.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether the current page belongs to the Membership section.
 *
 * @return bool
 */
function meat_science_is_membership_page() {
	$page = get_queried_object();
	if ( ! $page instanceof WP_Post || 'page' !== $page->post_type ) {
		return false;
	}

	foreach ( array_merge( array( $page->ID ), get_post_ancestors( $page->ID ) ) as $id ) {
		$ancestor = get_post( $id );
		if ( $ancestor && 'membership' === $ancestor->post_name && 0 === (int) $ancestor->post_parent ) {
			return true;
		}
	}

	return false;
}

/**
 * Normalize an ACF link field.
 *
 * @param mixed $link Link field value.
 * @return array{url:string,title:string,target:string}
 */
function meat_science_normalize_link( $link ) {
	if ( ! is_array( $link ) ) {
		return array(
			'url'    => '',
			'title'  => '',
			'target' => '_self',
		);
	}

	return array(
		'url'    => isset( $link['url'] ) ? trim( (string) $link['url'] ) : '',
		'title'  => isset( $link['title'] ) ? trim( (string) $link['title'] ) : '',
		'target' => ( isset( $link['target'] ) && '_blank' === $link['target'] ) ? '_blank' : '_self',
	);
}

/**
 * Render a reusable slider instance. Used by both the homepage slider block
 * and the "Image Slider" page-section block.
 *
 * @param array  $slides Slide rows.
 * @param string $aria_label Accessible label.
 * @param string $class_suffix Extra class appended to the slider.
 * @return void
 */
function meat_science_render_slider( $slides, $aria_label = '', $class_suffix = '' ) {
	$slides = array_values(
		array_filter(
			is_array( $slides ) ? $slides : array(),
			static function ( $slide ) {
				return is_array( $slide ) && ! empty( $slide['image'] );
			}
		)
	);

	if ( ! $slides ) {
		return;
	}

	$has_multiple_slides = count( $slides ) > 1;
	$slider_class        = 'meat-science-slider';
	if ( '' !== $class_suffix ) {
		$slider_class .= ' ' . sanitize_html_class( $class_suffix );
	}
	?>
	<section class="<?php echo esc_attr( $slider_class ); ?>" aria-label="<?php echo esc_attr( $aria_label ? $aria_label : __( 'Featured content', 'meat-science' ) ); ?>" data-slider<?php echo $has_multiple_slides ? ' data-autoplay="6000"' : ''; ?>>
		<div class="meat-science-slider__track">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$image_id      = (int) $slide['image'];
				$heading       = isset( $slide['heading'] ) ? trim( (string) $slide['heading'] ) : '';
				$text          = isset( $slide['text'] ) ? trim( (string) $slide['text'] ) : '';
				$button        = meat_science_normalize_link( isset( $slide['button'] ) ? $slide['button'] : array() );
				$has_content   = '' !== $heading || '' !== $text || ( '' !== $button['url'] && '' !== $button['title'] );
				?>
				<article class="meat-science-slider__slide<?php echo 0 === $index ? ' is-active' : ''; ?>" data-slide aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>">
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'full',
						false,
						array(
							'class'         => 'meat-science-slider__image',
							'loading'       => 0 === $index ? 'eager' : 'lazy',
							'fetchpriority' => 0 === $index ? 'high' : 'auto',
							'sizes'         => '100vw',
						)
					);
					?>
					<?php if ( $has_content ) : ?>
						<div class="meat-science-slider__overlay" aria-hidden="true"></div>
						<div class="meat-science-slider__inner">
							<div class="meat-science-slider__content">
								<?php if ( '' !== $heading ) : ?>
									<h2 class="meat-science-slider__heading"><?php echo esc_html( $heading ); ?></h2>
								<?php endif; ?>
								<?php if ( '' !== $text ) : ?>
									<div class="meat-science-slider__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
								<?php endif; ?>
								<?php if ( '' !== $button['url'] && '' !== $button['title'] ) : ?>
									<a class="meat-science-slider__button" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"<?php echo '_blank' === $button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
										<?php echo esc_html( $button['title'] ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<?php if ( $has_multiple_slides ) : ?>
			<button class="meat-science-slider__arrow meat-science-slider__arrow--previous" type="button" data-slider-previous aria-label="<?php esc_attr_e( 'Previous slide', 'meat-science' ); ?>">
				<span aria-hidden="true">&#10094;</span>
			</button>
			<button class="meat-science-slider__arrow meat-science-slider__arrow--next" type="button" data-slider-next aria-label="<?php esc_attr_e( 'Next slide', 'meat-science' ); ?>">
				<span aria-hidden="true">&#10095;</span>
			</button>
			<div class="meat-science-slider__dots" aria-label="<?php esc_attr_e( 'Choose a slide', 'meat-science' ); ?>">
				<?php foreach ( $slides as $index => $slide ) : ?>
					<button class="meat-science-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" type="button" data-slider-dot="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show slide %d', 'meat-science' ), $index + 1 ) ); ?>" aria-current="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>
	<?php
}
