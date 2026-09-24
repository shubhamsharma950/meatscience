<?php
/**
 * Main template file.
 *
 * @package MeatScience
 */

get_header();
?>
<main class="meat-science-main">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'meat-science-entry' ); ?>>
				<?php if ( is_singular() ) : ?>
					<h1 class="meat-science-entry__title"><?php the_title(); ?></h1>
				<?php else : ?>
					<h2 class="meat-science-entry__title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
				<?php endif; ?>

				<?php if ( ! is_page() ) : ?>
					<p class="meat-science-entry__meta">
						<?php
						printf(
							/* translators: %s: Post date. */
							esc_html__( 'Published %s', 'meat-science' ),
							esc_html( get_the_date() )
						);
						?>
					</p>
				<?php endif; ?>

				<div class="meat-science-entry__content">
					<?php
					if ( is_singular() ) {
						the_content();
					} else {
						the_excerpt();
					}
					?>
				</div>
			</article>
		<?php endwhile; ?>
	<?php else : ?>
		<article class="meat-science-entry">
			<h1 class="meat-science-entry__title"><?php esc_html_e( 'Nothing found', 'meat-science' ); ?></h1>
			<p><?php esc_html_e( 'There is no content to display yet.', 'meat-science' ); ?></p>
		</article>
	<?php endif; ?>
</main>
<?php
get_footer();
