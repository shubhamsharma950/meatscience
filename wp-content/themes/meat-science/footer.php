<?php
/**
 * The theme footer.
 *
 * @package MeatScience
 */
?>
</div>
<?php
if ( function_exists( 'meatscience_render_footer' ) ) {
	meatscience_render_footer();
} else {
	?>
	<footer class="meat-science-footer">
		<?php
		printf(
			/* translators: %s: Site name. */
			esc_html__( '%s. All rights reserved.', 'meat-science' ),
			esc_html( get_bloginfo( 'name' ) )
		);
		?>
	</footer>
	<?php
}
?>
<?php wp_footer(); ?>
</body>
</html>
