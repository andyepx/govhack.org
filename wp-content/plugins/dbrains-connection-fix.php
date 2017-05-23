<?php 
/* 
Plugin Name: DBI Connection Fix 
Author: Delicious Brains 
Author URI: http://deliciousbrains.com 
*/ 
function dbi_connection( $transports, $args, $url ) {     
  if ( false !== strpos( $url, 'deliciousbrains.com' ) ) {        
    // Use streams as cURL results in connection refused         
    return array( 'streams', 'curl' );     
  }      
  return $transports; 
} 
add_filter( 'http_api_transports', 'dbi_connection', 10, 3 );