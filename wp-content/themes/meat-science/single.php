<?php
/**
 * Default single template.
 *
 * @package MeatScience
 */

get_header();

$has_builder = meat_science_render_page_sections();
?>
<main class="meat-science-main">
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
