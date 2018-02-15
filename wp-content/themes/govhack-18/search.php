<?php
/**
 * The template for displaying search results pages.
 *
 * @package Sequential
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php if ( have_posts() ) : ?>
            <div class="wrapper">

                <?php get_search_form(); ?>

                <?php /* Start the Loop */ ?>
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php
                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'content', 'search' );
                    ?>

                <?php endwhile; ?>

                <?php sequential_paging_nav(); ?>

            </div><!-- .wrapper -->
            <?php else : ?>
            <div class="wrapper">

                <?php get_template_part( 'content', 'none' ); ?>
                
            </div><!-- .wrapper -->
            <section class="content-area content-area-gray">
                <div class="gh-tiles wrapper">
                    <?php get_template_part( 'region', 'tiles' ); ?>
                </div><!-- .wrapper -->
            </section>
            <?php endif; ?>
		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>