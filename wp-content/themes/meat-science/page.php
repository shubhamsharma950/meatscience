<?php
/**
 * Default page template.
 *
 * @package MeatScience
 */

get_header();

$is_membership = meat_science_is_membership_page();
if ( $is_membership ) :
	?>
	<nav class="meat-science-membership-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'meat-science' ); ?>">
		<ol>
			<?php foreach ( meat_science_page_banner_breadcrumb( get_queried_object_id() ) as $crumb ) : ?>
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
	<?php
endif;

if ( $is_membership ) :
	?>
<main class="meat-science-membership-main">
	<?php $has_builder = meat_science_render_page_sections( 0, array( 'page_banner' ) ); ?>
<?php else : ?>
	<?php $has_builder = meat_science_render_page_sections(); ?>
<main class="meat-science-main">
<?php endif; ?>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php if ( ! $has_builder ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'meat-science-entry' ); ?>>
				<h1 class="meat-science-entry__title"><?php the_title(); ?></h1>
				<div class="meat-science-entry__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
