<?php
/**
 * GH16 functions and definitions.
 * Extends Sequential functions and definitions.
 *
 * README
 * + Custom post types should be added via plugins (sponsors, locations, etc)
 * + Uses (phpscss by leafo)[http://leafo.github.io/scssphp/] to parse scss
 * + Action hooks, filters etc are in `/inc/actions.php`, including body class filters
 * + Shortcodes are in `/inc/shortcodes/*`
 * + Custom admin metaboxes are in `/inc/admin-metaboxes.php`
 * + Custom post types are also in `/inc/`
 *
 * @package gh16
 */

// stop the admin menu notices
if (!function_exists('remove_theme_plugin_enhancements')):
    function remove_theme_plugin_enhancements()
    {
        remove_action('admin_head', array('Theme_Plugin_Enhancements', 'init'));
    }
endif;
add_action('admin_head', 'remove_theme_plugin_enhancements', 1);

/**
 * Extend the customizer
 */
// require get_stylesheet_directory() . '/inc/customizer-ext.php';

// Deregister the default post menu within the admin menu
// if ( ! function_exists( 'unregister_default_post_type' ) ) :
// function unregister_default_post_type() {
// global $wp_post_types;
// if ( isset( $wp_post_types[ 'post' ] ) ) {
// unset( $wp_post_types[ 'post' ] );
// remove_menu_page( 'edit.php' );
// }
// }
// add_action( 'admin_init', 'unregister_default_post_type', 11 );
// endif;


/**
 * Kill off the unwanted parent themes
 */

function gh_remove_parent_theme_page_templates($templates)
{
    unset($templates['front-page.php']);
    unset($templates['page-templates/front-page.php']);
    // echo '<pre>'. print_r($templates, true) . '</pre>';
    return $templates;
}

add_filter('theme_page_templates', 'gh_remove_parent_theme_page_templates', 99);


/**
 * Enqueue scripts and styles.
 */
function gh_scripts()
{

    /// JS Libs
    wp_enqueue_script('lib-modernizr', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js');
    wp_enqueue_script('lib-featherlight-js', 'https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.5.0/featherlight.min.js', ['jquery']);
    wp_enqueue_script('lib-tipped-js', get_stylesheet_directory_uri() . '/lib/tipped-4.5.7-light/js/tipped/tipped.js', ['jquery', 'underscore']);

    /// CSS Libs
    // wp_enqueue_style( 'lib-skeletoncss', get_stylesheet_directory_uri() . '/lib/skeleton-2.0.4-customized/skeleton.css' );       // not using
    // wp_enqueue_style( 'lib-simplegrid', get_stylesheet_directory_uri() . '/lib/simple-grid/simplegrid.css' );                // not using
    wp_enqueue_style('lib-shelves', get_stylesheet_directory_uri() . '/lib/typeimage-shelves/shelves.css');           // Current
    wp_enqueue_style('lib-tipped-css', get_stylesheet_directory_uri() . '/lib/tipped-4.5.7-light/css/tipped/tipped.css');
    wp_enqueue_style('lib-featherlight-css', 'https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.5.0/featherlight.min.css');

    // Our magic asset-ified site switcher
    // wp_enqueue_script( 'gh-site-switcher', 'http://assets-dev.govhack.org/js/site-switcher.min.js', [] );
//    wp_enqueue_script('gh-site-switcher', 'https://assets.govhack.org/js/site-switcher.min.js', []);
    wp_enqueue_script('gh-site-switcher', '/wp-content/themes/govhack-17/js/siteswitcher.js', []);

    // custom fonts
    // wp_enqueue_style( 'gh-fonts', gh_fonts_url(), array(), null );

    // Common JS
    wp_enqueue_script('gh-common-js', get_stylesheet_directory_uri() . '/js/common.js', ['jquery', 'underscore']);

    //===============================
    // SCSS precompiled?
    //===============================

    $scss_comparator = function ($name) {
        return file_exists(get_stylesheet_directory() . "/css/$name.css")
            ? "/css/$name.css"
            : "/parse_scss.php?p=$name.scss&v=5.0";
    };

    $scss_overrides_uri = $scss_comparator('overrides');
    $scss_main_uri = $scss_comparator('main');
    $scss_landing_uri = $scss_comparator('landing');

    //===============================
    // Main Styles
    //===============================
    $parent_style = 'sequential-style';     // Include parent first...
    $child_style = 'gh-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style($child_style, get_stylesheet_directory_uri() . '/style.css', array($parent_style));
    wp_enqueue_style("$child_style-overrides", get_stylesheet_directory_uri() . $scss_overrides_uri, array($child_style));
    wp_enqueue_style("$child_style-main", get_stylesheet_directory_uri() . $scss_main_uri, array($child_style));

    if (is_page_template('page-templates/marketing-landing.php')) {
        wp_enqueue_style("$child_style-marketing", get_stylesheet_directory_uri() . $scss_landing_uri, array($child_style));
    }

    if (is_page_template('page-templates/sponsor-logos.php')) {
        wp_dequeue_script('gh-site-switcher');        // goodbye
    }

    if (is_page_template('page-templates/locations-page.php')) {
        // Enqueue some shit
        wp_enqueue_script('gh-locationfinder-js', get_stylesheet_directory_uri() . '/js/location-finder.js', ['jquery']);
        wp_enqueue_style('leaflet-css', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css');
        wp_enqueue_script('leaflet-js', 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js');
    }

    if (is_home()) {
        // Home intializer scripts
        wp_enqueue_script('gh-home-js', get_stylesheet_directory_uri() . '/js/homepage.js', ['jquery', 'underscore', 'gh-common-js']);
        // AddEvent button
        wp_enqueue_style('addevent-css', get_stylesheet_directory_uri() . '/lib/addevent/css/theme7.css');
        wp_enqueue_script('addevent-js', 'https://addevent.com/libs/atc/1.6.1/atc.min.js', [], null);
        // Slick slider
        wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css');
        wp_enqueue_style('slick-css-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css');
        wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js', [], null);
    }

}

add_action('wp_enqueue_scripts', 'gh_scripts', 5);

function gh_siteswitcher_config()
{
    ?>
    <script>var GH = {};
        GH.opts = {useDashicons: false, useFontAwesome: true};</script>
    <?php
}

add_action('wp_head', 'gh_siteswitcher_config', 2);       // bump up to go ahead of enqueues


function script_tag_defer($tag, $handle)
{
    if (in_array($handle, ['addevent-js'])) {
        return str_replace(' src', ' async defer src', $tag);
    }
    return $tag;
}

add_filter('script_loader_tag', 'script_tag_defer', 10, 2);

function show_header()
{
    $custom_fields = get_post_custom();
    if ($custom_fields) {
        foreach ($custom_fields as $k => $v) {
            if ('show_header' == $k)
                return ($v[0] == 'true');
        }
    }
    return true;
}

function show_footer()
{
    $custom_fields = get_post_custom();
    if ($custom_fields) {
        foreach ($custom_fields as $k => $v) {
            if ('show_footer' == $k)
                return ($v[0] == 'true');
        }
    }
    return true;
}

function content_additional_classes()
{
    $custom_fields = get_post_custom();
    foreach ($custom_fields as $k => $v) {
        if ('content_classes' == $k)
            return $v[0];
    }
    return "";
}


/**
 * Post Type wizardry
 */
include __DIR__ . '/inc/post-types/mentor-taxonomy.php';
include __DIR__ . '/inc/post-types/sponsor-post-type.php';
include __DIR__ . '/inc/post-types/location-post-type.php';
include __DIR__ . '/inc/post-types/tile-post-type.php';
include __DIR__ . '/inc/region-sidebars.php';

/**
 * Template Tags, shortcodes, other WordPress built-ins
 */
include __DIR__ . '/inc/template-tags.php';
include __DIR__ . '/inc/template-tags-breadcrumb.php';
include __DIR__ . '/inc/template-tags-location.php';
include __DIR__ . '/inc/template-tags-sponsors.php';
include __DIR__ . '/inc/shortcodes/shortcodes.php';
include __DIR__ . '/inc/shortcodes/shortcodes-elements.php';
include __DIR__ . '/inc/shortcodes/shortcodes-profiles.php';
include __DIR__ . '/inc/widgets.php';

/**
 * Data providers
 */
include __DIR__ . '/inc/providers/sponsorship-levels.php';

/**
 * Admin page mods
 */
include __DIR__ . '/inc/customizer.php';
include __DIR__ . '/inc/admin-metaboxes.php';
include __DIR__ . '/inc/admin-metaboxes-page.php';
include __DIR__ . '/inc/admin-metaboxes-tile.php';
include __DIR__ . '/inc/admin-metaboxes-sponsor.php';
include __DIR__ . '/inc/admin-metaboxes-location.php';     // Locations are unused thought
include __DIR__ . '/inc/admin-pages-sponsor.php';
include __DIR__ . '/inc/admin-pages-home.php';
include __DIR__ . '/inc/permissions.php';
include __DIR__ . '/inc/providers/sponsor-md-generator.php';

/**
 * Templating mods
 */
include __DIR__ . '/inc/404-handler.php';       // Some magic that crawls the archive site too
include __DIR__ . '/inc/filters.php';           // All filters go here
include __DIR__ . '/inc/open-graph.php';        // Binds Open Graph stuff

/**
 * Plugin settings
 */
// include __DIR__ . '/inc/megamenu-theme.php';

// page display mod - contentonly
function add_display_mode_query_var($vars)
{
    $vars[] = 'display_mode';
    return $vars;
}

add_filter('query_vars', 'add_display_mode_query_var');


// mime types
function more_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['eps'] = 'application/postscript';
    return $mimes;
}

add_filter('upload_mimes', 'more_mime_types');


//=== debug ====
function dd($thing, $die = false)
{
    echo '<pre>';
    var_dump($thing);
    echo '</pre>';
    if ($die) die();
}