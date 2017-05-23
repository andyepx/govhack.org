<?php
/**
 * Template Name: Marketing Landing page
 *
 * @package Sequential
 */

add_filter('the_content', 'gh_striate', 10);
 
get_header(); ?>

    <main id="main" class="site-main" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
    
        <?php if ( has_post_thumbnail() ): ?>
        <div class="hero landing-hero" style="background-image: url('<?php echo the_post_thumbnail_url( 'full' ) ?>')">
        <?php else: ?>
        <div class="hero landing-hero">
        <?php endif; ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="wrapper">
                
                    <header class="hero-header">
                    <?php if ($heading = get_post_meta( get_the_ID(), 'hero_title', true ) ?: $heading = get_post_meta( get_the_ID(), 'hero_heading', true )): ?>
                        <h1 class="hero-heading"><?= htmlentities($heading) ?></h1>
                    <?php else: ?>
                    <h1 class="hero-heading"><?php the_title() ?></h1>
                    <?php endif; ?>
                    <?php if ($subheading = get_post_meta( get_the_ID(), 'hero_subtitle', true ) ?: $subheading = get_post_meta( get_the_ID(), 'hero_subheading', true )): ?>
                        <h2 class="hero-subheading"><?= htmlentities($subheading) ?></h2>
                    <?php endif; ?>
                    </header>
                    
                    <?php if ( $cta_url = get_post_meta( get_the_ID(), 'hero_cta_url', true ) ): ?>
                    <a class="button-minimal hero-cta" href="<?php echo esc_url( $cta_url ) ?>"><?php echo get_post_meta( get_the_ID(), 'hero_cta_label', true ) ?: 'Click here' ?></a>
                    <?php endif; ?>
                        
                </div><!-- .wrapper -->
            </article><!-- .hentry -->
        </div><!-- .hero -->

        <div id="primary" class="landing-content">
    
            <?php the_content() ?>
            
        </div><!-- #primary -->
        
        
        <?php if ( $sponsor_region_id = get_post_meta( get_the_ID(), 'gh_show_sponsors_for_region_id', true ) ): 
            global $post;
            $post = get_post( $sponsor_region_id ); 
            setup_postdata( $post ); ?>
        <aside>
            <section class="content-area gh-sponsors">
                <div class="wrapper">
                    <h2 class="gh-sponsors-header">2016 State Sponsors</h2>
                    <?php get_template_part('region', 'sponsors'); ?>            
                </div><!-- .wrapper -->
            </section><!-- .content-area -->
        </aside>
        <?php wp_reset_postdata(); endif; // regional sponsors ?>
        
        <?php if ( get_post_meta( get_the_ID(), 'gh_show_sponsors_national', true ) ): ?>
        <aside>
            <section class="content-area gh-sponsors">
                <div class="wrapper">
                    <h2 class="gh-sponsors-header">GovHack 2016 National Sponsors</h2>
                    <?php get_template_part('home', 'sponsors'); ?>                
                </div>
            </section><!-- .content-area -->
        </aside>
        <?php endif; // national sponsors ?>
    
        
        
    <?php endwhile; // end of the loop. ?>
    </main><!-- #main -->

<?php get_footer(); ?>