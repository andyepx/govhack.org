<?php
/**
 * The Sidebar containing the region (state) widget area.
 *
 * @package Sequential
 */

if ( ! is_active_sidebar( 'region-sidebar-' . $post->post_name ) ) {
	return;
}
?>

<div id="secondary" class="widget-area gh-region-widget-area" role="complementary">
    <?php dynamic_sidebar( 'region-sidebar-' . $post->post_name ); ?>
</div><!-- #secondary -->
