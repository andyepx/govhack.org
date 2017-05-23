<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sequential
 */
?>

<section class="no-results not-found">
    
	<div class="page-content">
		<?php if ( is_search() ) : ?>

			<?php get_search_form(); ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'sequential' ); ?></p>

		<?php else : ?>

			<?php get_search_form(); ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'sequential' ); ?></p>

		<?php endif; ?>

	</div><!-- .page-content -->
</section><!-- .no-results -->