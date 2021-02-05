<PHP
// GovHack Wordpress Theme


function my_plugin_add_stylesheet() {
    wp_enqueue_style( 'my-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0', 'all' );
}


?>
