<?php
/**
 * Block: Latest Content Slider (CPT Slider).
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

/**
 * Build a list of public post types that can be used as a slider source.
 *
 * @return array<string,string> Post type name => label.
 */
function meat_science_cpt_slider_get_selectable_post_types() {
	$post_types = get_post_types( array( 'public' => true ), 'objects' );
	$excluded   = array( 'attachment', 'page' );
	$choices    = array();

	foreach ( $post_types as $post_type ) {
		if ( in_array( $post_type->name, $excluded, true ) ) {
			continue;
		}

		$choices[ $post_type->name ] = $post_type->labels->singular_name;
	}

	if ( ! $choices ) {
		$choices['post'] = __( 'Post', 'meat-science' );
	}

	return $choices;
}

Meat_Science_Blocks::register(
	'cpt_slider',
	__( 'Latest Content Slider', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_cpt_slider_heading',
			'label'         => __( 'Heading', 'meat-science' ),
			'name'          => 'heading',
			'type'          => 'text',
			'instructions'  => __( 'Manually written heading shown above the slider.', 'meat-science' ),
			'required'      => 1,
			'default_value' => __( 'Latest Resources', 'meat-science' ),
		),
		array(
			'key'           => 'field_meat_science_cpt_slider_post_type',
			'label'         => __( 'Content Type', 'meat-science' ),
			'name'          => 'post_type',
			'type'          => 'select',
			'instructions'  => __( 'Choose which post type to pull the slides from (e.g. Post, News, or any other public content type).', 'meat-science' ),
			'choices'       => meat_science_cpt_slider_get_selectable_post_types(),
			'default_value' => 'post',
			'allow_null'    => 0,
			'multiple'      => 0,
			'ui'            => 1,
		),
		array(
			'key'           => 'field_meat_science_cpt_slider_count',
			'label'         => __( 'Number of Posts', 'meat-science' ),
			'name'          => 'posts_count',
			'type'          => 'number',
			'instructions'  => __( 'How many of the most recent items to show in the slider.', 'meat-science' ),
			'default_value' => 6,
			'min'           => 1,
			'max'           => 20,
			'step'          => 1,
			'required'      => 1,
		),
	),
	'meat_science_block_cpt_slider_render'
);

/**
 * Find a display label for a post's primary public taxonomy term, if any.
 *
 * @param int    $post_id   Post ID.
 * @param string $post_type Post type name.
 * @return string
 */
function meat_science_cpt_slider_get_primary_term_label( $post_id, $post_type ) {
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );

	foreach ( $taxonomies as $taxonomy ) {
		if ( empty( $taxonomy->public ) ) {
			continue;
		}

		$terms = get_the_terms( $post_id, $taxonomy->name );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			return $terms[0]->name;
		}
	}

	return '';
}

/**
 * Render the "Latest Content Slider" block.
 */
function meat_science_block_cpt_slider_render() {
	$heading     = trim( (string) get_sub_field( 'heading' ) );
	$post_type   = (string) get_sub_field( 'post_type' );
	$posts_count = (int) get_sub_field( 'posts_count' );
	$posts_count = $posts_count > 0 ? $posts_count : 6;

	if ( ! $post_type || ! post_type_exists( $post_type ) ) {
		return;
	}

	$query = new WP_Query(
		array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => $posts_count,
			'orderby'             => 'date',
			'order'               => 'DESC',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	if ( ! $query->have_posts() ) {
		wp_reset_postdata();
		return;
	}
	?>
	<section class="meat-science-builder__block meat-science-cpt-slider" aria-label="<?php echo esc_attr( $heading ? $heading : __( 'Latest content', 'meat-science' ) ); ?>">
		<?php if ( '' !== $heading ) : ?>
			<h2 class="meat-science-resource-library__heading meat-science-cpt-slider__heading"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<div class="meat-science-cpt-slider__wrapper">
			<button class="meat-science-cpt-slider__arrow meat-science-cpt-slider__arrow--previous" type="button" data-cpt-slider-previous aria-label="<?php esc_attr_e( 'Previous', 'meat-science' ); ?>">
				<span aria-hidden="true">&#10094;</span>
			</button>
			<div class="meat-science-cpt-slider__track" data-cpt-slider-track>
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$term_label = meat_science_cpt_slider_get_primary_term_label( get_the_ID(), $post_type );
					?>
					<article class="meat-science-cpt-slider__card">
						<a class="meat-science-cpt-slider__card-link" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="meat-science-cpt-slider__image-wrap">
									<?php the_post_thumbnail( 'medium_large', array( 'class' => 'meat-science-cpt-slider__image', 'loading' => 'lazy' ) ); ?>
								</div>
							<?php endif; ?>
							<div class="meat-science-cpt-slider__body">
								<?php if ( '' !== $term_label ) : ?>
									<span class="meat-science-cpt-slider__category"><?php echo esc_html( $term_label ); ?></span>
								<?php endif; ?>
								<h3 class="meat-science-cpt-slider__title"><?php the_title(); ?></h3>
								<p class="meat-science-cpt-slider__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>
			<button class="meat-science-cpt-slider__arrow meat-science-cpt-slider__arrow--next" type="button" data-cpt-slider-next aria-label="<?php esc_attr_e( 'Next', 'meat-science' ); ?>">
				<span aria-hidden="true">&#10095;</span>
			</button>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
