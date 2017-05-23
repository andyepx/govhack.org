<?php
/**
 * Template Name: Redirection Page
 *
 * @package Sequential
 */
 
$redirect_http_status = get_post_meta( get_the_ID(), 'gh_redirect_is_perm', true ) == 'yes' ? 301 : 302;
 
if ($redirect_to = get_post_meta( get_the_ID(), 'gh_redirection_url', true )){
    wp_redirect( $redirect_to, $redirect_http_status );
    exit;
}
    
wp_redirect( home_url(), $redirect_http_status );
exit;
