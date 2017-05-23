<?php
/**
 * Template Name: GH Region Index Page
 *
 * @package Sequential
 */
 
get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">
            <?php while ( have_posts() ) : the_post(); ?>
            <div class="wrapper">
                <?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' ); ?>
            </div><!-- .wrapper -->
        
            <section class="content-area content-area-gray">
                <div class="gh-tiles wrapper">
                    <?php get_template_part( 'region', 'tiles' ); ?>
                </div><!-- .wrapper -->
            </section>

            <?php if ( $banner_date_heading = get_theme_mod('gh_tile_show_registration_links' , false) ): ?> 
            <div class="content-area gh-registration-quicklinks full-width">
                <div class="wrapper">
                    <?php get_template_part('home', 'registrationlinks'); ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="wrapper">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div><!-- .entry-content -->
                <?php edit_post_link( esc_html__( 'Edit', 'sequential' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
            </div><!-- .wrapper -->                
                
            <?php endwhile; // end of the loop. ?>
		</main><!-- #main -->

	</div><!-- #primary -->

<?php get_footer(); ?>