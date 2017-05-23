<?php
/**
 * Template Name: Archives
 *
 * @package Sequential
 */
 
get_header(); ?>

    <?php // gh_simple_breadcrumb([ "wrapped" => true ]); ?> 

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">
            <div class="wrapper">
                <ul>
					<?php wp_get_archives('type=monthly'); ?>
                    
                </ul>
            </div><!-- .wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>