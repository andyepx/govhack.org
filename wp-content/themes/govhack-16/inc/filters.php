<?php

/**
 * ADD FILTER: Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function gh_body_classes( $classes ) {        

    // Adds a class of full-width-layout to all page layouts
	if ( is_single()
            || is_home()
            || is_search()
            || is_page_template('page-templates/hero-page.php') 
            || is_page_template('page-templates/locations-page.php')
            || is_page_template('page-templates/region-index-page.php')
            || is_page_template('page-templates/region-page.php')
            || is_page_template('page-templates/sponsor-logos.php')
            || is_page_template('page-templates/full-width-page.php') ) {
        $classes[] = 'full-width-layout';
	}
    
    // adds extra spacing to a select few pages
    if ( is_single()
            || is_home()
            // || is_page_template('page-templates/hero-page.php') 
            // || is_page_template('page-templates/locations-page.php')
            || is_page_template('page-templates/region-index-page.php') ) {
        $classes[] = 'extra-spacing';            
    }
    
    // Add a class to make the map display by default
    if ( is_page_template('page-templates/locations-page.php') ){
        $classes[] = 'gh-map-visible';
    }
    
    // Inform the stylesheet that this is a landing page
    if ( is_page_template('page-templates/marketing-landing.php') ){
        $classes[] = 'gh-landing';
        $classes[] = 'extra-spacing';
        $classes[] = 'full-width-layout';
    }
        
    // Remove sidebars on the following pages
    if ( is_search() || is_archive() ){
        $classes[] = 'no-sidebar';
        // $classes[] = 'extra-spacing';
    }
   
    // Make the home extra spaced
    if ( is_home() ){
        $classes[] = 'full-width-layout';
        $classes[] = 'extra-spacing';
    }
    
    // SPONSORS: Boxed layout for states 
    if ( is_home() || is_page() ){
        $classes[] = 'sponsors-boxed';
    }

    // Check for special query vars
    if ( gh_is_minimalistic() ){
        $classes[] = 'no-header-footer';
    }
    
    // To pad for the site switcher
    if ( wp_script_is( 'gh-site-switcher', 'done' ) ){
        $classes[] = 'ghss-block-padded ghss-hidden-mobile';        
    }
    
    // Environment-specific admin bar color
    if ( defined('GH_ENV') ){
        if ( strtolower(GH_ENV) === 'prod' ){
            $classes[] = 'env-prod';
        }
        else {
            $classes[] = 'env-' . htmlentities(GH_ENV);
            $classes[] = 'env-nonprod';
        }
    }
    
	return $classes;
}
add_filter( 'body_class', 'gh_body_classes' );

// Make the header blank, if it's the landing page
function gh_landing_page_blank_header ($default) {
    if ( ! $default ){
        return $default;
    }
    if ( is_search() ){
        return false;
    }
    if ( is_page_template( 'page-templates/marketing-landing.php' ) ){
        return false;
    }
    return true;
}
add_filter( 'header_search', 'gh_landing_page_blank_header');
add_filter( 'header_tiles', 'gh_landing_page_blank_header');


// Filter landing page stuff into striations
function gh_striate ($content){

    $stratum_prefix = '<section class="%s"><div class="wrapper">';
    $stratum_postfix = '</div></section>';
    
    // CHANGE HERE if necessary
    $stratum_prefix_initial_classes = ['content-area'];
    
    $count = 0;
    $even_count = false;
    $content = preg_replace_callback('#<hr\s*/?>#', function ($matches) use (&$even_count, $stratum_prefix_initial_classes, $stratum_prefix, $stratum_postfix) {
        if ( ! $even_count ){
            $stratum_prefix_initial_classes[] = 'content-area-gray';
        }
        $even_count = $even_count ? 0 : 1;      // swap
        return $stratum_postfix . sprintf($stratum_prefix, implode(' ', $stratum_prefix_initial_classes));
    }, $content, -1, $count);
    
    // dd($content);
    // dd($count);

    $stratum_prefix_initial = sprintf($stratum_prefix, implode(' ', $stratum_prefix_initial_classes));
    $stratum_postfix_final = $stratum_postfix;
    $content = sprintf('%s%s%s', $stratum_prefix_initial, $content, $stratum_postfix_final);
    
    return $content;
    
}