<?php
/**
 * Front page template.
 *
 * @package MeatScience
 */

get_header();

$has_builder = meat_science_render_page_sections();
if ( ! $has_builder ) {
	meat_science_render_home_slider();
}
?>
<main class="meat-science-main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<?php if ( ! $has_builder && trim( get_the_content() ) ) : ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'meat-science-entry' ); ?>>
				<div class="meat-science-entry__content">
					<?php the_content(); ?>
				</div>
			</article>
		<?php endif; ?>
	<?php endwhile; ?>
</main>
<?php
get_footer();
