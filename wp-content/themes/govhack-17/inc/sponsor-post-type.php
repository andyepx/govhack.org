<?php

// Custom post type for Sponsor
// To be included in functions.php
// Todo: Move out to its own plugin

// Creates new post type for Sponsors
if ( ! function_exists('register_sponsor_post_type') ):
function register_sponsor_post_type(){
    
    register_post_type( 'sponsors', array(
        'labels' => array(
            'name' => __( 'Sponsors' ),
            'singular_name' => __( 'Sponsor' ),
            'add_new_item' => __( 'Add new Sponsor' ),
            'edit_item' => __( 'Edit Sponsor' ),
            'new_item' => __( 'New Sponsor' ),
            'view_item' => __( 'View Sponsor' ),
            'all_items' => __( 'All Sponsors' ),
            'search_items' => __( 'Search Sponsors' ),
            'not_found' => __( 'No sponsors found' ),
            'not_found_in_trash' => __( 'No sponsors found in trash' ),
            'parent_item' => __( 'Parent Sponsor' ),
        ),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'has_archive' => false,
        'menu_position' => 4,
        'menu_icon' => '',
        'capability_type' => 'post',            // keep as post, so that normal authors can edit it
        'hierarchical' => true,
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),     // 'page-attributes'
        'rewrite' => array(
            'slug' => 'sponsors'
        ),
        'taxonomies' => array(
            'sponsorship_classes'
        )
    ));
        
    register_taxonomy( 'sponsorship_classes', 'sponsors', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( 'Sponsorship Classes', 'taxonomy general name' ),
            'singular_name' => _x( 'Sponsorship Class', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Sponsorship Classes' ),
            'all_items' => __( 'All Sponsorship Classes' ),
            'parent_item' => __( 'Parent Sponsorship Class' ),
            'parent_item_colon' => __( 'Parent Sponsorship Class:' ),
            'edit_item' => __( 'Edit Sponsorship Class' ),
            'update_item' => __( 'Update Sponsorship Class' ),
            'add_new_item' => __( 'Add New Sponsorship Class' ),
            'new_item_name' => __( 'New Sponsorship Class' ),
            'menu_name' => __( 'Sponsorship Classes' ),
        ),
        'rewrite' => array(
            'slug' => 'sponsorship-classes', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
    
}
endif;

if ( ! function_exists('register_sponsor_post_type_styles') ):
function register_sponsor_post_type_styles(){
    $customPostType = 'sponsors';
?>
<style>#adminmenu .menu-icon-<?= $customPostType ?> div.wp-menu-image:before { content: "\f337"; } </style>
<?php
}
endif;

add_action( 'init', 'register_sponsor_post_type' );
add_action( 'admin_head', 'register_sponsor_post_type_styles' );


/*===============================
 * Modify admin page table
 *===============================*/
function gh_sponsors_table_head( $defaults ) {
    // $new = [ 'sponsorship_class' => 'Sponsorship Class' ];
    unset($defaults['date']);
    $defaults['sponsorship_class'] = 'Sponsorship Class';
    $defaults['date'] = 'Date';
    return $defaults;
}
add_filter('manage_sponsors_posts_columns', 'gh_sponsors_table_head');


function gh_sponsors_table_content( $column_name, $post_id ) {

    if ($column_name == 'sponsorship_class') {
        $terms = wp_get_post_terms( $post_id, 'sponsorship_classes' );
        $term_names = [];
        array_walk( $terms, function (&$item, $key) use (&$term_names){
            $term_names[] = $item->name;
        } );
        echo implode(', ', $term_names);
        return;
    }
    
}
add_action( 'manage_sponsors_posts_custom_column', 'gh_sponsors_table_content', 10, 2 );




/*===============================
 * Utility function for getting JSON-defined 
 * sponsorship classes
 *===============================*/

if ( ! function_exists('gh_get_sponsorship_level')):
function gh_get_sponsorship_level($level = 'national', $file = 'sponsorship-classes.json'){
    try {
        $f = __DIR__ . '/../sponsorship-classes.json';
        if (file_exists($f)){
            $configFileContents = file_get_contents($f);
            $config = json_decode($configFileContents);
            if (property_exists($config, $level)){
                return $config->$level;
            }
        }
        return [];
    }
    catch (Exception $e){
        error_log($e->getMessage());
        return [];
    }
}
endif;