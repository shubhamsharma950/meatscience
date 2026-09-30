<?php
/**
 * Block: Sidebar Content.
 *
 * Two-column layout: a navigation sidebar whose links are pulled from an
 * Appearance > Menus menu (optionally a single branch of it), an optional
 * donation call-out box, and a rich-text content column.
 *
 * @package MeatScience
 */

defined( 'ABSPATH' ) || exit;

Meat_Science_Blocks::register(
	'sidebar_content',
	__( 'Sidebar + Content', 'meat-science' ),
	array(
		array(
			'key'   => 'field_meat_science_sidebar_content_tab_sidebar',
			'label' => __( 'Sidebar Menu', 'meat-science' ),
			'type'  => 'tab',
		),
		array(
			'key'          => 'field_meat_science_sidebar_content_menu',
			'label'        => __( 'Menu Source', 'meat-science' ),
			'name'         => 'menu_source',
			'type'         => 'select',
			'instructions' => __( 'Pick a whole menu, or one menu item to list its sub-items. "Auto" finds the section that contains the current page.', 'meat-science' ),
			'choices'      => array(),
			'allow_null'   => 0,
			'ui'           => 1,
		),
		array(
			'key'          => 'field_meat_science_sidebar_content_title',
			'label'        => __( 'Sidebar Title', 'meat-science' ),
			'name'         => 'sidebar_title',
			'type'         => 'text',
			'instructions' => __( 'Leave empty to use the selected menu item / menu name.', 'meat-science' ),
		),
		array(
			'key'   => 'field_meat_science_sidebar_content_tab_donate',
			'label' => __( 'Call-out Box', 'meat-science' ),
			'type'  => 'tab',
		),
		array(
			'key'           => 'field_meat_science_sidebar_content_show_box',
			'label'         => __( 'Show Call-out Box', 'meat-science' ),
			'name'          => 'show_box',
			'type'          => 'true_false',
			'ui'            => 1,
			'default_value' => 1,
		),
		array(
			'key'               => 'field_meat_science_sidebar_content_box_text',
			'label'             => __( 'Text', 'meat-science' ),
			'name'              => 'box_text',
			'type'              => 'textarea',
			'rows'              => 3,
			'new_lines'         => 'br',
			'default_value'     => __( 'Make an impact on meat science. Help AMSA Meat the Future by making a gift to our long-term endowment.', 'meat-science' ),
			'conditional_logic' => array( array( array( 'field' => 'field_meat_science_sidebar_content_show_box', 'operator' => '==', 'value' => '1' ) ) ),
		),
		array(
			'key'               => 'field_meat_science_sidebar_content_box_button',
			'label'             => __( 'Button', 'meat-science' ),
			'name'              => 'box_button',
			'type'              => 'link',
			'return_format'     => 'array',
			'conditional_logic' => array( array( array( 'field' => 'field_meat_science_sidebar_content_show_box', 'operator' => '==', 'value' => '1' ) ) ),
		),
		array(
			'key'               => 'field_meat_science_sidebar_content_box_color',
			'label'             => __( 'Background Color', 'meat-science' ),
			'name'              => 'box_color',
			'type'              => 'color_picker',
			'default_value'     => '#ed1b3b',
			'conditional_logic' => array( array( array( 'field' => 'field_meat_science_sidebar_content_show_box', 'operator' => '==', 'value' => '1' ) ) ),
		),
		array(
			'key'   => 'field_meat_science_sidebar_content_tab_content',
			'label' => __( 'Content', 'meat-science' ),
			'type'  => 'tab',
		),
		array(
			'key'          => 'field_meat_science_sidebar_content_content',
			'label'        => __( 'Content', 'meat-science' ),
			'name'         => 'content',
			'type'         => 'wysiwyg',
			'tabs'         => 'all',
			'toolbar'      => 'full',
			'media_upload' => 1,
		),
	),
	'meat_science_block_sidebar_content_render'
);

/**
 * Populate the "Menu Source" select with every menu and its top-level items.
 *
 * Values are "{menu_id}" (whole menu) or "{menu_id}:{item_id}" (item's children).
 *
 * @param array $field ACF field.
 * @return array
 */
function meat_science_sidebar_content_menu_choices( $field ) {
	$choices = array( '' => __( 'Auto (section containing the current page)', 'meat-science' ) );

	foreach ( wp_get_nav_menus() as $menu ) {
		$choices[ (string) $menu->term_id ] = sprintf( __( '%s (top-level items)', 'meat-science' ), $menu->name );

		foreach ( (array) wp_get_nav_menu_items( $menu->term_id ) as $item ) {
			if ( 0 === (int) $item->menu_item_parent ) {
				$choices[ $menu->term_id . ':' . $item->ID ] = $menu->name . ' › ' . $item->title;
			}
		}
	}

	$field['choices'] = $choices;

	return $field;
}
add_filter( 'acf/load_field/key=field_meat_science_sidebar_content_menu', 'meat_science_sidebar_content_menu_choices' );

/**
 * Normalize a URL to a comparable path (no host, no trailing slash, lower case).
 *
 * @param string $url URL.
 * @return string
 */
function meat_science_sidebar_content_url_path( $url ) {
	$path = wp_parse_url( (string) $url, PHP_URL_PATH );

	return strtolower( untrailingslashit( $path ? $path : '/' ) );
}

/**
 * Whether a nav menu item points at the page currently being viewed.
 *
 * @param WP_Post $item Nav menu item.
 * @return bool
 */
function meat_science_sidebar_content_is_current( $item ) {
	$queried_id = (int) get_queried_object_id();

	if ( $queried_id && 'post_type' === $item->type && (int) $item->object_id === $queried_id ) {
		return true;
	}

	$current   = meat_science_sidebar_content_url_path( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$item_path = meat_science_sidebar_content_url_path( $item->url );
	$home_path = meat_science_sidebar_content_url_path( home_url( '/' ) );

	if ( $item_path === $current ) {
		return true;
	}

	// Root-relative menu links ("/about-amsa") on a site installed in a sub-folder.
	return '/' !== $home_path && 0 === strpos( (string) $item->url, '/' ) && $home_path . $item_path === $current;
}

/**
 * Resolve the sidebar links and default title for a menu source value.
 *
 * @param string $source "", "{menu_id}" or "{menu_id}:{item_id}".
 * @return array{title:string,items:WP_Post[]}
 */
function meat_science_sidebar_content_resolve( $source ) {
	$source  = trim( (string) $source );
	$menu_id = 0;
	$parent  = 0;
	$title   = '';

	if ( '' !== $source ) {
		$parts   = explode( ':', $source );
		$menu_id = (int) $parts[0];
		$parent  = isset( $parts[1] ) ? (int) $parts[1] : 0;
	} else {
		// Auto: find the top-level ancestor of the current page in any menu.
		foreach ( wp_get_nav_menus() as $menu ) {
			$items = (array) wp_get_nav_menu_items( $menu->term_id );
			$by_id = array();
			foreach ( $items as $item ) {
				$by_id[ $item->ID ] = $item;
			}
			foreach ( $items as $item ) {
				if ( ! meat_science_sidebar_content_is_current( $item ) ) {
					continue;
				}
				$ancestor = $item;
				while ( (int) $ancestor->menu_item_parent && isset( $by_id[ (int) $ancestor->menu_item_parent ] ) ) {
					$ancestor = $by_id[ (int) $ancestor->menu_item_parent ];
				}
				$menu_id = (int) $menu->term_id;
				$parent  = (int) $ancestor->ID;
				break 2;
			}
		}
	}

	if ( ! $menu_id ) {
		return array( 'title' => '', 'items' => array() );
	}

	$all   = (array) wp_get_nav_menu_items( $menu_id );
	$links = array();

	foreach ( $all as $item ) {
		if ( (int) $item->ID === $parent ) {
			$title = $item->title;
		}
		if ( (int) $item->menu_item_parent === $parent ) {
			$links[] = $item;
		}
	}

	if ( ! $parent ) {
		$menu  = wp_get_nav_menu_object( $menu_id );
		$title = $menu ? $menu->name : '';
	}

	return array( 'title' => $title, 'items' => $links );
}

/**
 * Render the "Sidebar + Content" block.
 */
function meat_science_block_sidebar_content_render() {
	$nav      = meat_science_sidebar_content_resolve( get_sub_field( 'menu_source' ) );
	$title    = trim( (string) get_sub_field( 'sidebar_title' ) );
	$title    = '' !== $title ? $title : $nav['title'];
	$show_box = (bool) get_sub_field( 'show_box' );
	$box_text = trim( (string) get_sub_field( 'box_text' ) );
	$button   = meat_science_normalize_link( get_sub_field( 'box_button' ) );
	$color    = sanitize_hex_color( (string) get_sub_field( 'box_color' ) );
	$color    = $color ? $color : '#ed1b3b';
	$content  = (string) get_sub_field( 'content' );

	$has_box     = $show_box && ( '' !== $box_text || ( '' !== $button['url'] && '' !== $button['title'] ) );
	$is_membership = meat_science_is_membership_page();
	$is_membership_child = $is_membership && (bool) wp_get_post_parent_id( get_queried_object_id() );
	$has_sidebar = ! $is_membership_child && ( $nav['items'] || $has_box );
	$page_title  = '';
	if ( $is_membership ) {
		$sections   = get_field( 'meat_science_page_sections', get_queried_object_id() );
		$page_title = isset( $sections[0]['title'] ) ? trim( (string) $sections[0]['title'] ) : '';
		$page_title = '' !== $page_title ? $page_title : get_the_title( get_queried_object_id() );
	}
	?>
	<section class="meat-science-builder__block meat-science-sidebar-content<?php echo $has_sidebar ? '' : ' meat-science-sidebar-content--full'; ?><?php echo $is_membership ? ' meat-science-sidebar-content--membership' : ''; ?>">
		<?php if ( $has_sidebar ) : ?>
			<aside class="meat-science-sidebar-content__aside">
				<?php if ( $nav['items'] ) : ?>
					<nav class="meat-science-sidebar-content__nav" aria-label="<?php echo esc_attr( '' !== $title ? $title : __( 'Section menu', 'meat-science' ) ); ?>">
						<?php if ( '' !== $title ) : ?>
							<h2 class="meat-science-sidebar-content__nav-title"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>
						<ul class="meat-science-sidebar-content__nav-list">
							<?php foreach ( $nav['items'] as $item ) : ?>
								<?php
								$is_current = meat_science_sidebar_content_is_current( $item );
								$target     = '_blank' === $item->target ? '_blank' : '_self';
								?>
								<li class="meat-science-sidebar-content__nav-item">
									<a class="meat-science-sidebar-content__nav-link<?php echo $is_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( $item->url ); ?>" target="<?php echo esc_attr( $target ); ?>"<?php echo '_blank' === $target ? ' rel="noopener noreferrer"' : ''; ?><?php echo $is_current ? ' aria-current="page"' : ''; ?>>
										<span><?php echo esc_html( $item->title ); ?></span>
										<span class="meat-science-sidebar-content__nav-icon" aria-hidden="true">&rarr;</span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</nav>
				<?php endif; ?>

				<?php if ( $has_box ) : ?>
					<div class="meat-science-sidebar-content__box" style="--sidebar-box-color: <?php echo esc_attr( $color ); ?>;">
						<?php if ( '' !== $box_text ) : ?>
							<div class="meat-science-sidebar-content__box-text"><?php echo wp_kses_post( wpautop( $box_text ) ); ?></div>
						<?php endif; ?>
						<?php if ( '' !== $button['url'] && '' !== $button['title'] ) : ?>
							<a class="meat-science-sidebar-content__box-button" href="<?php echo esc_url( $button['url'] ); ?>" target="<?php echo esc_attr( $button['target'] ); ?>"<?php echo '_blank' === $button['target'] ? ' rel="noopener noreferrer"' : ''; ?>>
								<span><?php echo esc_html( $button['title'] ); ?></span>
								<span class="meat-science-sidebar-content__box-button-icon" aria-hidden="true">&rarr;</span>
							</a>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</aside>
		<?php endif; ?>

		<div class="meat-science-sidebar-content__main meat-science-builder__content">
			<?php if ( '' !== $page_title ) : ?>
				<h1 class="meat-science-membership-title"><?php echo esc_html( $page_title ); ?></h1>
			<?php endif; ?>
			<?php echo wp_kses( $content, meat_science_sidebar_content_allowed_html() ); ?>
		</div>
	</section>
	<?php
}

/**
 * Post HTML plus the embeds (iframes, video) used for webinar / video content.
 *
 * @return array
 */
function meat_science_sidebar_content_allowed_html() {
	$allowed = wp_kses_allowed_html( 'post' );

	$allowed['iframe'] = array(
		'src'             => true,
		'title'           => true,
		'width'           => true,
		'height'          => true,
		'allow'           => true,
		'allowfullscreen' => true,
		'frameborder'     => true,
		'loading'         => true,
		'referrerpolicy'  => true,
		'class'           => true,
	);
	$allowed['video']  = array(
		'src'      => true,
		'controls' => true,
		'poster'   => true,
		'preload'  => true,
		'width'    => true,
		'height'   => true,
		'class'    => true,
	);
	$allowed['source'] = array(
		'src'  => true,
		'type' => true,
	);

	return $allowed;
}
