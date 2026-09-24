<?php
/**
 * ACF-managed utility header for Meat Science.
 *
 * @package MeatScienceHeader
 */

defined( 'ABSPATH' ) || exit;

define( 'MEATSCIENCE_HEADER_PATH', __DIR__ . '/meatscience-header' );
define( 'MEATSCIENCE_HEADER_URL', content_url( '/mu-plugins/meatscience-header' ) );

/**
 * Register the header settings page and its local ACF field group.
 */
function meatscience_register_header_fields() {
	if ( ! function_exists( 'acf_add_options_page' ) || ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => __( 'Header Settings', 'meatscience' ),
			'menu_title' => __( 'Header Settings', 'meatscience' ),
			'menu_slug'  => 'meatscience-header-settings',
			'capability' => 'manage_options',
			'redirect'   => false,
			'icon_url'   => 'dashicons-menu-alt3',
		)
	);

	acf_add_local_field_group(
		array(
			'key'      => 'group_meatscience_header',
			'title'    => __( 'Header Bar', 'meatscience' ),
			'fields'   => array(
				array(
					'key'   => 'field_meatscience_header_colors',
					'label' => __( 'Header Colors', 'meatscience' ),
					'name'  => '',
					'type'  => 'accordion',
				),
				meatscience_color_field( 'header_background_color', __( 'Header Background Color', 'meatscience' ), '#ed1b3b' ),
				meatscience_color_field( 'menu_text_color', __( 'Menu Text Color', 'meatscience' ), '#ffffff' ),
				meatscience_color_field( 'menu_hover_color', __( 'Menu Hover Color', 'meatscience' ), '#ffd5dc' ),
				meatscience_color_field( 'active_menu_color', __( 'Active Menu Color', 'meatscience' ), '#ffffff' ),
				meatscience_color_field( 'social_icon_color', __( 'Social Icon Color', 'meatscience' ), '#ffffff' ),
				meatscience_color_field( 'social_icon_hover_color', __( 'Social Icon Hover Color', 'meatscience' ), '#ffd5dc' ),
				array(
					'key'          => 'field_meatscience_menu_links',
					'label'        => __( 'Menu Links', 'meatscience' ),
					'name'         => 'menu_links',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => __( 'Add Link', 'meatscience' ),
					'sub_fields'  => array(
						array(
							'key'      => 'field_meatscience_link_title',
							'label'    => __( 'Link Title', 'meatscience' ),
							'name'     => 'link_title',
							'type'     => 'text',
							'required' => 1,
						),
						array(
							'key'      => 'field_meatscience_link_url',
							'label'    => __( 'Link URL', 'meatscience' ),
							'name'     => 'link_url',
							'type'     => 'url',
							'required' => 1,
						),
						array(
							'key'           => 'field_meatscience_link_target',
							'label'         => __( 'Open In', 'meatscience' ),
							'name'          => 'open_in',
							'type'          => 'select',
							'choices'       => array(
								'_self'  => __( 'Same Window', 'meatscience' ),
								'_blank' => __( 'New Tab', 'meatscience' ),
							),
							'default_value' => '_self',
							'return_format' => 'value',
						),
						array(
							'key'           => 'field_meatscience_link_icon',
							'label'         => __( 'Optional Link Icon', 'meatscience' ),
							'name'          => 'link_icon',
							'type'          => 'icon_picker',
							'return_format' => 'array',
							'library'       => 'dashicons',
						),
					),
				),
				array(
					'key'          => 'field_meatscience_social_links',
					'label'        => __( 'Social Links', 'meatscience' ),
					'name'         => 'social_links',
					'type'         => 'repeater',
					'layout'       => 'table',
					'button_label' => __( 'Add Social Icon', 'meatscience' ),
					'sub_fields'  => array(
						array(
							'key'           => 'field_meatscience_social_icon',
							'label'         => __( 'Social Icon', 'meatscience' ),
							'name'          => 'social_icon',
							'type'          => 'icon_picker',
							'required'      => 1,
							'return_format' => 'array',
							'library'       => 'dashicons',
						),
						array(
							'key'      => 'field_meatscience_social_url',
							'label'    => __( 'Social URL', 'meatscience' ),
							'name'     => 'social_url',
							'type'     => 'url',
							'required' => 1,
						),
						array(
							'key'           => 'field_meatscience_social_target',
							'label'         => __( 'Open In', 'meatscience' ),
							'name'          => 'open_in',
							'type'          => 'select',
							'choices'       => array(
								'_self'  => __( 'Same Window', 'meatscience' ),
								'_blank' => __( 'New Tab', 'meatscience' ),
							),
							'default_value' => '_blank',
							'return_format' => 'value',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'meatscience-header-settings',
					),
				),
			),
			'menu_order' => 0,
			'position'   => 'normal',
			'style'      => 'default',
			'active'     => true,
		)
	);
}
add_action( 'acf/init', 'meatscience_register_header_fields' );

/**
 * Build a reusable ACF color picker field definition.
 *
 * @param string $name Field name.
 * @param string $label Field label.
 * @param string $default Default color.
 * @return array<string, mixed>
 */
function meatscience_color_field( $name, $label, $default ) {
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
 * Enqueue the front-end header styles.
 */
function meatscience_enqueue_header_assets() {
	if ( is_admin() ) {
		return;
	}

	wp_enqueue_style(
		'meatscience-header',
		MEATSCIENCE_HEADER_URL . '/header.css',
		array( 'dashicons' ),
		'1.0.0'
	);
}
add_action( 'wp_enqueue_scripts', 'meatscience_enqueue_header_assets' );

/**
 * Render one ACF icon value, supporting the icon picker array and string formats.
 *
 * @param mixed $icon ACF icon value.
 * @return string
 */
function meatscience_render_icon( $icon ) {
	$class = '';

	if ( is_array( $icon ) ) {
		$class = isset( $icon['class'] ) ? $icon['class'] : '';
	} elseif ( is_string( $icon ) ) {
		$class = $icon;
	}

	if ( '' === $class ) {
		return '';
	}

	return '<span class="' . esc_attr( $class ) . '" aria-hidden="true"></span>';
}

/**
 * Render the ACF-managed header immediately after the opening body tag.
 */
function meatscience_render_header() {
	if ( ! function_exists( 'get_field' ) ) {
		return;
	}

	$colors = array(
		'background'  => sanitize_hex_color( get_field( 'header_background_color', 'option' ) ) ?: '#ed1b3b',
		'text'        => sanitize_hex_color( get_field( 'menu_text_color', 'option' ) ) ?: '#ffffff',
		'hover'       => sanitize_hex_color( get_field( 'menu_hover_color', 'option' ) ) ?: '#ffd5dc',
		'active'      => sanitize_hex_color( get_field( 'active_menu_color', 'option' ) ) ?: '#ffffff',
		'social'      => sanitize_hex_color( get_field( 'social_icon_color', 'option' ) ) ?: '#ffffff',
		'social_hover' => sanitize_hex_color( get_field( 'social_icon_hover_color', 'option' ) ) ?: '#ffd5dc',
	);
	$menu_links   = get_field( 'menu_links', 'option' );
	$social_links = get_field( 'social_links', 'option' );

	if ( ! is_array( $menu_links ) ) {
		$menu_links = array();
	}
	if ( ! is_array( $social_links ) ) {
		$social_links = array();
	}
	?>
	<header class="meatscience-header" style="<?php echo esc_attr( '--ms-header-bg:' . $colors['background'] . ';--ms-header-text:' . $colors['text'] . ';--ms-header-hover:' . $colors['hover'] . ';--ms-header-active:' . $colors['active'] . ';--ms-social:' . $colors['social'] . ';--ms-social-hover:' . $colors['social_hover'] . ';' ); ?>">
		<div class="meatscience-header__inner">
			<button class="meatscience-header__toggle" type="button" aria-expanded="false" aria-controls="meatscience-header-menu">
				<span class="screen-reader-text"><?php esc_html_e( 'Toggle header menu', 'meatscience' ); ?></span>
				<span class="dashicons dashicons-menu" aria-hidden="true"></span>
			</button>
			<nav id="meatscience-header-menu" class="meatscience-header__menu" aria-label="<?php esc_attr_e( 'Header menu', 'meatscience' ); ?>">
				<?php foreach ( $menu_links as $link ) : ?>
					<?php
					$title  = isset( $link['link_title'] ) ? $link['link_title'] : '';
					$url    = isset( $link['link_url'] ) ? $link['link_url'] : '';
					$target = ( isset( $link['open_in'] ) && '_blank' === $link['open_in'] ) ? '_blank' : '_self';
					if ( '' === $title || '' === $url ) {
						continue;
					}
					$is_active = wp_parse_url( $url, PHP_URL_PATH ) === wp_parse_url( add_query_arg( array() ), PHP_URL_PATH );
					?>
					<a class="meatscience-header__link<?php echo $is_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?>>
						<?php echo meatscience_render_icon( isset( $link['link_icon'] ) ? $link['link_icon'] : '' ); ?>
						<span><?php echo esc_html( $title ); ?></span>
					</a>
				<?php endforeach; ?>
			</nav>
			<?php if ( $social_links ) : ?>
				<div class="meatscience-header__social" aria-label="<?php esc_attr_e( 'Social links', 'meatscience' ); ?>">
					<?php foreach ( $social_links as $social ) : ?>
						<?php
						$url    = isset( $social['social_url'] ) ? $social['social_url'] : '';
						$target = ( isset( $social['open_in'] ) && '_self' === $social['open_in'] ) ? '_self' : '_blank';
						$icon   = meatscience_render_icon( isset( $social['social_icon'] ) ? $social['social_icon'] : '' );
						if ( '' === $url || '' === $icon ) {
							continue;
						}
						?>
						<a class="meatscience-header__social-link" href="<?php echo esc_url( $url ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?>>
							<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icon class is escaped in meatscience_render_icon(). ?>
							<span class="screen-reader-text"><?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ?: __( 'Social link', 'meatscience' ) ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</header>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			var toggle = document.querySelector('.meatscience-header__toggle');
			var menu = document.getElementById('meatscience-header-menu');
			if (!toggle || !menu) return;
			toggle.addEventListener('click', function () {
				var expanded = toggle.getAttribute('aria-expanded') === 'true';
				toggle.setAttribute('aria-expanded', String(!expanded));
				menu.classList.toggle('is-open', !expanded);
			});
		});
	</script>
	<?php
}
add_action( 'wp_body_open', 'meatscience_render_header', 5 );
