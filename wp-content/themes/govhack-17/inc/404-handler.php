<?php
/**
 * A custom 404 handler for GovHack
 * In case something isn't found, then it searches for it within archive.govhack.org
 */
 
function gh_custom_404(){
    if ( is_404() ){
        
        $gh_archive = defined('GH_ARCHIVE_URL') ? GH_ARCHIVE_URL : 'http://archive.govhack.org';
        
        /** Launch a test to the archive to see if its 2XX, if so then redirect it there **/
        $hypothesized_dest = $gh_archive . $_SERVER[ 'REQUEST_URI' ];
        $response = wp_remote_get($hypothesized_dest);
        if( is_array($response) ) {
            if (array_key_exists('response', $response) && 200 == $response['response']['code']){
                wp_redirect($hypothesized_dest);
                exit;                
            }
        }
        
        /// It's irrelevant if it's a WP_Error, because in this case we'd show our own 404 screen anyway
        
        // elseif ( is_wp_error($response) ){
            // dd($response);
            // exit;
        // }
    }
    
}
add_action('template_redirect', 'gh_custom_404');

// Stop WP turning govhack.org/go --> govhack.org/sponsors/google
// Which effectively prevents the execution of a function called redirect_guess_404_permalink()
function no_redirect_on_404($redirect_url){
    if ( is_404() ) return false; 
    return $redirect_url;
}
add_filter('redirect_canonical', 'no_redirect_on_404');