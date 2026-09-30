<?php
/**
 * ACF-managed footer settings and renderer for Meat Science.
 *
 * @package MeatScienceFooter
 */

defined( 'ABSPATH' ) || exit;

define( 'MEATSCIENCE_FOOTER_URL', content_url( '/mu-plugins/meatscience-footer' ) );

/**
 * Register the Footer Settings options page and its fields.
 *
 * @return void
 */
function meatscience_register_footer_fields() {
	if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Footer Settings', 'meatscience' ),
			'menu_title' => __( 'Footer Settings', 'meatscience' ),
			'menu_slug'  => 'meatscience-footer-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'icon_url'   => 'dashicons-editor-kitchensink',
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_meatscience_footer',
			'title'    => __( 'Footer', 'meatscience' ),
			'fields'   => array(
				array(
					'key'   => 'field_meatscience_footer_branding',
					'label' => __( 'Branding', 'meatscience' ),
					'name'  => '',
					'type'  => 'accordion',
					'open'  => 1,
				),
				array(
					'key'           => 'field_meatscience_footer_logo',
					'label'         => __( 'Footer Logo', 'meatscience' ),
					'name'          => 'footer_logo',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				array(
					'key'           => 'field_meatscience_footer_logo_caption',
					'label'         => __( 'Logo Caption', 'meatscience' ),
					'name'          => 'footer_logo_caption',
					'type'          => 'text',
					'default_value' => __( 'by American Meat Science Association', 'meatscience' ),
				),
				array(
					'key'   => 'field_meatscience_footer_quick_links',
					'label' => __( 'Quick Links', 'meatscience' ),
					'name'  => '',
					'type'  => 'accordion',
				),
				array(
					'key'     => 'field_meatscience_footer_quick_links_menu',
					'label'   => __( 'Quick Links Menu', 'meatscience' ),
					'name'    => 'footer_quick_links_menu',
					'type'    => 'select',
					'choices' => meatscience_footer_get_menu_choices(),
					'ui'      => 1,
					'instructions' => __( 'Create or edit menus in Appearance > Menus, then choose the menu to display as footer Quick Links.', 'meatscience' ),
				),
				array(
					'key'           => 'field_meatscience_footer_quick_links_label',
					'label'         => __( 'Quick Links Accessible Label', 'meatscience' ),
					'name'          => 'footer_quick_links_label',
					'type'          => 'text',
					'default_value' => __( 'Footer quick links', 'meatscience' ),
				),
				array(
					'key'   => 'field_meatscience_footer_content',
					'label' => __( 'Footer Content', 'meatscience' ),
					'name'  => '',
					'type'  => 'accordion',
				),
				array(
					'key'           => 'field_meatscience_footer_copyright',
					'label'         => __( 'Copyright Text', 'meatscience' ),
					'name'          => 'footer_copyright',
					'type'          => 'text',
					'default_value' => __( '© {year} American Meat Science Association. THE MEAT LOCKER and its logo are service marks of AMSA.', 'meatscience' ),
					'instructions'  => __( 'Use {year} to insert the current year automatically.', 'meatscience' ),
				),
				array(
					'key'           => 'field_meatscience_footer_disclaimer',
					'label'         => __( 'Disclaimer', 'meatscience' ),
					'name'          => 'footer_disclaimer',
					'type'          => 'textarea',
					'rows'          => 3,
					'new_lines'     => 'br',
					'default_value' => __( 'The information presented on this site is compiled from sources and documents believed to be reliable and represents the best professional judgment of American Meat Science Association (AMSA). However, the accuracy of the information presented cannot be guaranteed, nor is the responsibility assumed by AMSA for any damages resulting from inaccuracies or omissions.', 'meatscience' ),
				),
				array(
					'key'   => 'field_meatscience_footer_appearance',
					'label' => __( 'Appearance', 'meatscience' ),
					'name'  => '',
					'type'  => 'accordion',
				),
				meatscience_footer_color_field( 'footer_background_color', __( 'Background Color', 'meatscience' ), '#1d1d1d' ),
				array(
					'key'           => 'field_meatscience_footer_background_image',
					'label'         => __( 'Background Image', 'meatscience' ),
					'name'          => 'footer_background_image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
				),
				meatscience_footer_color_field( 'footer_text_color', __( 'Text Color', 'meatscience' ), '#ffffff' ),
				meatscience_footer_color_field( 'footer_link_color', __( 'Quick Links Color', 'meatscience' ), '#ffffff' ),
				meatscience_footer_color_field( 'footer_link_hover_color', __( 'Quick Links Hover Color', 'meatscience' ), '#ed1b3b' ),
			),
			'location'   => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'meatscience-footer-settings',
					),
				),
			),
			'menu_order' => 1,
			'position'   => 'normal',
			'style'      => 'default',
			'active'     => true,
		)
	);
}
add_action( 'acf/init', 'meatscience_register_footer_fields' );

/**
 * Return WordPress menus as choices for the Quick Links selector.
 *
 * @return array<int|string, string>
 */
function meatscience_footer_get_menu_choices() {
	$choices = array(
		'' => __( 'Select a menu', 'meatscience' ),
	);

	foreach ( wp_get_nav_menus() as $menu ) {
		$choices[ $menu->term_id ] = $menu->name;
	}

	return $choices;
}

/**
 * Build an ACF color-picker field definition.
 *
 * @param string $name Field name.
 * @param string $label Field label.
 * @param string $default Default color.
 * @return array<string, mixed>
 */
function meatscience_footer_color_field( $name, $label, $default ) {
	return array(
		'key'           => 'field_meatscience_' . $name,
		'label'         => $label,
		'name'          => $name,
		'type'          => 'color_picker',
		'default_value' => $default,
		'return_format' => 'string',
	);
}

/**
 * Enqueue front-end footer styles.
 *
 * @return void
 */
function meatscience_enqueue_footer_assets() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'meatscience-footer',
		MEATSCIENCE_FOOTER_URL . '/footer.css',
		array(),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'meatscience_enqueue_footer_assets' );

/**
 * Render the ACF-managed site footer.
 *
 * @return void
 */
function meatscience_render_footer() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$logo             = get_field( 'footer_logo', 'option' );
	$caption          = get_field( 'footer_logo_caption', 'option' );
	$quick_links_menu = get_field( 'footer_quick_links_menu', 'option' );
	$quick_links_label = get_field( 'footer_quick_links_label', 'option' );
	$copyright        = get_field( 'footer_copyright', 'option' );
	$disclaimer       = get_field( 'footer_disclaimer', 'option' );
	$background_image = get_field( 'footer_background_image', 'option' );
	$background_color = sanitize_hex_color( get_field( 'footer_background_color', 'option' ) ) ?: '#1d1d1d';
	$text_color       = sanitize_hex_color( get_field( 'footer_text_color', 'option' ) ) ?: '#ffffff';
	$link_color       = sanitize_hex_color( get_field( 'footer_link_color', 'option' ) ) ?: '#ffffff';
	$link_hover_color = sanitize_hex_color( get_field( 'footer_link_hover_color', 'option' ) ) ?: '#ed1b3b';

	if ( ! is_string( $caption ) ) {
		$caption = '';
	}
	if ( ! is_string( $quick_links_label ) || '' === $quick_links_label ) {
		$quick_links_label = __( 'Footer quick links', 'meatscience' );
	}
	if ( ! is_string( $copyright ) || '' === $copyright ) {
		$copyright = sprintf( __( '© %s %s. All rights reserved.', 'meatscience' ), wp_date( 'Y' ), get_bloginfo( 'name' ) );
	}
	if ( ! is_string( $disclaimer ) ) {
		$disclaimer = '';
	}

	$copyright = str_replace( '{year}', wp_date( 'Y' ), $copyright );
	$style     = '--ms-footer-bg:' . $background_color . ';--ms-footer-text:' . $text_color . ';--ms-footer-link:' . $link_color . ';--ms-footer-link-hover:' . $link_hover_color . ';';

	if ( is_array( $background_image ) && ! empty( $background_image['url'] ) ) {
		$style .= '--ms-footer-image:url(' . esc_url( $background_image['url'] ) . ');';
	}
	?>
	<footer class="meatscience-footer" style="<?php echo esc_attr( $style ); ?>">
		<div class="meatscience-footer__inner">
			<div class="meatscience-footer__brand">
				<?php if ( is_array( $logo ) && ! empty( $logo['url'] ) ) : ?>
					<a class="meatscience-footer__logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img class="meatscience-footer__logo" src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ?: get_bloginfo( 'name' ) ); ?>">
					</a>
				<?php endif; ?>
				<?php if ( '' !== $caption ) : ?>
					<p class="meatscience-footer__caption"><?php echo esc_html( $caption ); ?></p>
				<?php endif; ?>
			</div>
			<div class="meatscience-footer__content">
				<?php if ( $quick_links_menu ) : ?>
					<nav class="meatscience-footer__navigation" aria-label="<?php echo esc_attr( $quick_links_label ); ?>">
						<?php
						wp_nav_menu(
							array(
								'menu'        => (int) $quick_links_menu,
								'container'   => false,
								'menu_class'  => 'meatscience-footer__menu',
								'fallback_cb' => false,
								'depth'       => 1,
							)
						);
						?>
					</nav>
				<?php endif; ?>
				<p class="meatscience-footer__copyright"><?php echo esc_html( $copyright ); ?></p>
				<?php if ( '' !== $disclaimer ) : ?>
					<div class="meatscience-footer__disclaimer"><?php echo wp_kses_post( wpautop( $disclaimer ) ); ?></div>
				<?php endif; ?>
			</div>
		</div>
	</footer>
	<?php
}
