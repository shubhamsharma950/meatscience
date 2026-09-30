<?php
/**
 * Block: Page Banner.
 *
 * Full-width hero with a background image, page title and an optional
 * label button (e.g. "About Us" + "About AMSA").
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'page_banner',
	__( 'Page Banner', 'meat-science' ),
	array(
		array(
			'key'           => 'field_meat_science_page_banner_image',
			'label'         => __( 'Background Image', 'meat-science' ),
			'name'          => 'image',
			'type'          => 'image',
			'required'      => 1,
			'return_format' => 'id',
			'preview_size'  => 'large',
			'library'       => 'all',
		),
		array(
			'key'          => 'field_meat_science_page_banner_title',
			'label'        => __( 'Title', 'meat-science' ),
			'name'         => 'title',
			'type'         => 'text',
			'instructions' => __( 'Leave empty to use the page title.', 'meat-science' ),
		),
		array(
			'key'           => 'field_meat_science_page_banner_button',
			'label'         => __( 'Label Button', 'meat-science' ),
			'name'          => 'button',
			'type'          => 'link',
			'instructions'  => __( 'Optional white button shown under the title.', 'meat-science' ),
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_meat_science_page_banner_breadcrumb',
			'label'         => __( 'Show Breadcrumb', 'meat-science' ),
			'name'          => 'show_breadcrumb',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 1,
		),
	),
	'meat_science_block_page_banner_render'
);

/**
 * Build breadcrumb items (Home > ancestors > current) for a page.
 *
 * @param int $post_id Post ID.
 * @return array<int,array{title:string,url:string}>
 */
function meat_science_page_banner_breadcrumb( $post_id ) {
	$items = array(
		array(
			'title' => __( 'Home', 'meat-science' ),
			'url'   => home_url( '/' ),
		),
	);

	foreach ( array_reverse( get_post_ancestors( $post_id ) ) as $ancestor_id ) {
		$items[] = array(
			'title' => get_the_title( $ancestor_id ),
			'url'   => get_permalink( $ancestor_id ),
		);
	}

	$items[] = array(
		'title' => get_the_title( $post_id ),
		'url'   => '',
	);

	return $items;
}

/**
 * Render the "Page Banner" block.
 */
function meat_science_block_page_banner_render() {
	$image_id = (int) get_sub_field( 'image' );
	$title    = trim( (string) get_sub_field( 'title' ) );
	$title    = '' !== $title ? $title : get_the_title( get_queried_object_id() );
	$button   = meat_science_normalize_link( get_sub_field( 'button' ) );
	$crumbs   = get_sub_field( 'show_breadcrumb' ) && is_singular() ? meat_science_page_banner_breadcrumb( get_queried_object_id() ) : array();

	if ( ! $image_id && '' === $title ) {
		return;
	}
	?>
	<section class="meat-science-page-banner">
		<?php
		if ( $image_id ) {
			echo wp_get_attachment_image(
				$image_id,
				'full',
				false,
				array(
					'class'         => 'meat-science-page-banner__image',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
					'alt'           => '',
				)
			);
		}
		?>
		<div class="meat-science-page-banner__overlay" aria-hidden="true"></div>
		<div class="meat-science-page-banner__inner">
			<span class="meat-science-page-banner__accent" aria-hidden="true"></span>
			<h1 class="meat-science-page-banner__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( '' !== $button['url'] && '' !== $button['title'] ) : ?>
				<a class="meat-science-page-banner__button" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"<?php echo '_blank' === $button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
					<span><?php echo esc_html( $button['title'] ); ?></span>
					<span class="meat-science-page-banner__button-icon" aria-hidden="true">&rarr;</span>
				</a>
			<?php endif; ?>
			<?php if ( count( $crumbs ) > 1 ) : ?>
				<nav class="meat-science-page-banner__breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'meat-science' ); ?>">
					<ol>
						<?php foreach ( $crumbs as $crumb ) : ?>
							<li>
								<?php if ( '' !== $crumb['url'] ) : ?>
									<a href="<?php echo esc_url( $crumb['url'] ); ?>"><?php echo esc_html( $crumb['title'] ); ?></a>
								<?php else : ?>
									<span aria-current="page"><?php echo esc_html( $crumb['title'] ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</nav>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
