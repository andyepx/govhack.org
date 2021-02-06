<PHP
// GovHack Wordpress Theme

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( 'twenty-twenty-one-style' ), 
        wp_get_theme()->get('Version')
    );
}


function my_plugin_add_stylesheet() {
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', false, '1.0', 'all' );
}


?>
