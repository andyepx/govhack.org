<?php
/**
 * Template Name: GH Sponsor Logos
 *
 * @package Sequential
 */
 
get_header(); ?>

    <?php // gh_simple_breadcrumb([ "wrapped" => true ]); ?> 

	<div id="primary" class="content-area">
        <div class="wrapper">
        <?php while ( have_posts() ) : the_post(); ?>
            <?php // the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header>' ); ?>
            <div class="entry-content">
                <?php // the_content(); ?>
            </div>
            <?php edit_post_link( esc_html__( 'Edit', 'sequential' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer>' ); ?>
        <?php endwhile; // end of the loop. ?>
        </div>

        <div class="gh-sponsors centered">
            <div class="wrapper">
                <h2 class="gh-sponsors-header">GovHack 2017 National Sponsors</h2>
                <?php get_template_part('home', 'sponsors'); ?>    
            </div>
        </div>

    </div><!-- #primary -->

    <?php 
    // Retrieve all pages that represent a state
    $regional_sponsorship_classes = function_exists('gh_get_sponsorship_level') ? gh_get_sponsorship_level('state')->classes : [];
    $region_parent_page_id = gh_get_region_parent_page_id();
    $region_pages = [];
    if ( !empty( $region_parent_page_id ) ){
        $region_pages = get_pages([
            'parent'         => $region_parent_page_id,
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'sort_column'    => 'menu_order'
        ]);
    }
    ?>

    <?php foreach( $region_pages as $index => $post ): setup_postdata( $post ); ?>
    <?php if ( ($index % 2) === 0 ): ?>
    <section class="content-area content-area-gray">
    <?php else: ?>
    <section class="content-area">
    <?php endif; ?>
        <div class="gh-sponsors centered">
            <div class="wrapper">
                <h1><?php the_title() ?></h1>
                <?php
                gh_render_sponsors( 'state_sponsor_type', $regional_sponsorship_classes, get_the_ID() );
                ?>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); endforeach; ?>
    
<?php get_footer(); ?>