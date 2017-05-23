<?php

/**
 * FILTER: Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function gh_body_classes( $classes ) {        

    // Adds a class of full-width-layout to all page layouts
	if ( is_single()
            || is_home()
            || is_page_template('page-templates/hero-page.php') 
            || is_page_template('page-templates/locations-page.php')
            || is_page_template('page-templates/region-index-page.php')
            || is_page_template('page-templates/region-page.php')
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
    
    // Set map display to default
    if ( is_page_template('page-templates/locations-page.php') ){
        $classes[] = 'gh-map-visible';
    }
        
    if ( is_search() || is_archive() ){
        $classes[] = 'no-sidebar';
        // $classes[] = 'extra-spacing';
    }
   
    // Make the home extra spaced
    if ( is_home() ){
        $classes[] = 'full-width-layout';
        $classes[] = 'extra-spacing';
    }
    
    // To pad for the site switcher
    $classes[] = 'with-gh-ss';
    
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


//
// AddEvent button enqueue
// see /inc/template-tags.php for the tag which adds the AddEvent snippet
//
function gh_addevent_button_enqueue(){
    if ( is_home() ){
    }
    // <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js" async defer></script>
}
add_action('wp_enqueue_scripts', 'gh_addevent_button_enqueue', 50);

