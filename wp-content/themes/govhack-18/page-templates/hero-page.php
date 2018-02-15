<?php
/**
 * Template Name: GH Hero Page
 *
 * @package Sequential
 */
 
get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<div class="hero">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="wrapper">
				<?php sequential_post_thumbnail(); ?>

				<div class="entry-written-content">
                
                    <?php gh_row_open_tag() ?>
                    <div class="col-1-2">
                    </div>
                    <div class="col-1-2">
                        <header class="hero-header">
                        <?php if ($heading = get_post_meta( get_the_ID(), 'hero_title', true ) ?: $heading = get_post_meta( get_the_ID(), 'hero_heading', true )): ?>
                            <h1 class="hero-heading"><?= htmlentities($heading) ?></h1>
                        <?php endif; ?>
                        <?php if ($subheading = get_post_meta( get_the_ID(), 'hero_subtitle', true ) ?: $subheading = get_post_meta( get_the_ID(), 'hero_subheading', true )): ?>
                            <h2 class="hero-subheading"><?= htmlentities($subheading) ?></h2>
                        <?php endif; ?>
                        </header>
                    </div>
                                        
                    <?php gh_row_close_tag() ?>
                
				</div><!-- .entry-written-content -->
			</div><!-- .wrapper -->
		</article><!-- .hentry -->
	</div><!-- .hero -->

	<div id="primary" class="content-area full-width">
		<div id="content" class="site-content" role="main">
            <div class="wrapper">
                <?php get_template_part( 'content', 'page' ); ?>
            </div><!-- .wrapper -->
		</div><!-- #content -->
	</div><!-- #primary -->
    
    <?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>