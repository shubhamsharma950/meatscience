<?php
/**
 * The theme footer.
 *
 * @package MeatScience
 */
?>
</div>
<footer class="meat-science-footer">
	<?php
	printf(
		/* translators: %s: Site name. */
		esc_html__( '%s. All rights reserved.', 'meat-science' ),
		esc_html( get_bloginfo( 'name' ) )
	);
	?>
</footer>
<?php wp_footer(); ?>
</body>
</html>
