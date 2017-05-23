<?php

// Custom post type for Sponsor
// To be included in functions.php
// Todo: Move out to its own plugin

// Creates new post type for Sponsors
if ( ! function_exists('register_sponsor_post_type') ):
function register_sponsor_post_type(){
    
    register_post_type( 'sponsor', array(
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
        'supports' => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),     // 'page-attributes'
        'rewrite' => array(
            'slug' => 'sponsors'
        ),
        'taxonomies' => array(
            'national_sponsor_types',
            'state_sponsor_types',
        )
    ));
        
    register_taxonomy( 'national_sponsor_type', 'sponsor', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( 'National Sponsor Types', 'taxonomy general name' ),
            'singular_name' => _x( 'National Sponsor Type', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search National Sponsor Types' ),
            'all_items' => __( 'All National Sponsor Types' ),
            'parent_item' => __( 'Parent National Sponsor Type' ),
            'parent_item_colon' => __( 'Parent National Sponsor Type:' ),
            'edit_item' => __( 'Edit National Sponsor Type' ),
            'update_item' => __( 'Update National Sponsor Type' ),
            'add_new_item' => __( 'Add National New Sponsor Type' ),
            'new_item_name' => __( 'New National Sponsor Type' ),
            'menu_name' => __( 'National Sponsor Types' ),
        ),
        'rewrite' => array(
            'slug' => 'national-sponsor-types', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
    
    /** 
     * State sponsor types hierarchy
     */
    register_taxonomy( 'state_sponsor_type', 'sponsor', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( 'State Sponsor Types', 'taxonomy general name' ),
            'singular_name' => _x( 'State Sponsor Type', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search State Sponsor Types' ),
            'all_items' => __( 'All State Sponsor Types' ),
            'parent_item' => __( 'Parent State Sponsor Type' ),
            'parent_item_colon' => __( 'Parent State Sponsor Type:' ),
            'edit_item' => __( 'Edit State Sponsor Type' ),
            'update_item' => __( 'Update State Sponsor Type' ),
            'add_new_item' => __( 'Add New State Sponsor Type' ),
            'new_item_name' => __( 'New State Sponsor Type' ),
            'menu_name' => __( 'State Sponsor Types' ),
        ),
        'rewrite' => array(
            'slug' => 'state-sponsor-type', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
        
    /** 
     * State sponsor types hierarchy
     */
    register_taxonomy( 'local_sponsor_type', 'sponsor', array(
        'hierarchical' => true,
        'labels' => array(
            'name' => _x( 'Local Sponsor Types', 'taxonomy general name' ),
            'singular_name' => _x( 'Local Sponsor Type', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Local Sponsor Types' ),
            'all_items' => __( 'All Local Sponsor Types' ),
            'parent_item' => __( 'Parent Local Sponsor Type' ),
            'parent_item_colon' => __( 'Parent Local Sponsor Type:' ),
            'edit_item' => __( 'Edit Local Sponsor Type' ),
            'update_item' => __( 'Update Local Sponsor Type' ),
            'add_new_item' => __( 'Add New Local Sponsor Type' ),
            'new_item_name' => __( 'New Local Sponsor Type' ),
            'menu_name' => __( 'Local Sponsor Types' ),
        ),
        'rewrite' => array(
            'slug' => 'local-sponsor-type', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
        ),
    ));
    
}
endif;

if ( ! function_exists('register_sponsor_post_type_styles') ):
function register_sponsor_post_type_styles(){
    $customPostType = 'sponsor';
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
function gh_sponsor_table_head( $defaults ) {
    // $new = [ 'sponsorship_class' => 'Sponsorship Class' ];
    unset($defaults['date']);
    $defaults['sponsorship_class'] = 'Sponsorship Type';
    // $defaults['sponsorship_region'] = 'Region';
    $defaults['date'] = 'Date';
    return $defaults;
}
add_filter('manage_sponsor_posts_columns', 'gh_sponsor_table_head');


function gh_sponsor_table_content( $column_name, $post_id ) {

    if ($column_name == 'sponsorship_class') {
        $national_terms = wp_get_post_terms( $post_id, 'national_sponsor_type' );
        $state_terms = wp_get_post_terms( $post_id, 'state_sponsor_type' );
        $local_terms = wp_get_post_terms( $post_id, 'local_sponsor_type' );
        // $all_terms = array_merge($national_terms, $state_terms);
        $region_name = '';
        if ( $sponsor_region_id = get_post_meta($post_id, 'sponsor_region_id', true) ){
            if ( $sponsor_region_post = get_post( $sponsor_region_id ) ){
                $region_name = strtoupper($sponsor_region_post->post_name);
            }
        }
        $term_names = [];
        array_walk( $national_terms, function (&$item, $key) use (&$term_names) {
            $term_names[] = "National &raquo; {$item->name}";
        } );
        array_walk( $state_terms, function (&$item, $key) use (&$term_names, $region_name) {
            $term_names[] = "State ($region_name) &raquo; {$item->name}";
        } );
        array_walk( $local_terms, function (&$item, $key) use (&$term_names, $region_name) {
            $term_names[] = "Local ($region_name) &raquo; {$item->name}";
        } );
        echo implode('<br>', $term_names);
        return;
    }
    
    // if ($column_name == 'sponsorship_region') {
        // $region_post_id = wp_get_post_meta( $post_id, 'sponsor_region_id', true );
        // array_walk( $region_terms, function (&$item, $key) use (&$term_names) {
            // $term_names[] = "{$item->name}";
        // } );
        // echo implode(', ', $term_names);
        // return;
    // }
    
}
add_action( 'manage_sponsor_posts_custom_column', 'gh_sponsor_table_content', 10, 2 );


/*==========================================
 * Generate a markdown file for Portal
 * Relies on an external endpoint to do the 
 * crunching
 *==========================================*/

function gh_sponsor_generate_md( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'gh_tile_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_tile_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Go.
    // if ( function_exists('gh_admin_sponsor_crunch_init') ){
        // gh_admin_sponsor_crunch_init();
    // }
 
}
add_action( 'save_post', 'gh_sponsor_generate_md', 99 );