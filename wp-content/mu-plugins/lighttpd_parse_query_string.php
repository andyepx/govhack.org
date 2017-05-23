<?php
/*
Plugin Name: Parse Query String for Lighttpd
Plugin URI: https://www.govhack.org
Description: A common approach with WordPress and Lighttpd is to map index.php to the server.error-handler-404 rule. This doesn't map $_GET... we need to intercept and map it ourselves.
Author: Alan Yeung
Version: 1.0
 */
 
if ( !function_exists('parse_query_string_lighttpd') ){
    function parse_query_string_lighttpd() { 
        if (!strstr($_SERVER['REQUEST_URI'],'?') or !empty($_GET)) return;
        $_SERVER['QUERY_STRING'] = parse_url($_SERVER['REQUEST_URI'])['query'];
        parse_str($_SERVER['QUERY_STRING'], $_GET); 
    }
    add_action( 'muplugins_loaded', 'parse_query_string_lighttpd' );    
}