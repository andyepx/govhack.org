<?php
// Custom post type for Home Tiles
// To be included in functions.php
// Todo: Move out to its own plugin

// Creates new post type for Tiles
if ( ! function_exists('register_tile_post_type') ):
function register_tile_post_type(){

    register_post_type( 'tiles', array(
        'labels' => array(
            'name' => __( 'Tiles' ),
            'singular_name' => __( 'Tile' ),
            'add_new_item' => __( 'Add new Tile' ),
            'edit_item' => __( 'Edit Tile' ),
            'new_item' => __( 'New Tile' ),
            'view_item' => __( 'View Tile' ),
            'all_items' => __( 'All Tiles' ),
            'search_items' => __( 'Search Tiles' ),
            'not_found' => __( 'No tiles found' ),
            'not_found_in_trash' => __( 'No tiles found in trash' ),
            'parent_item' => __( 'Parent Tile' ),
            'featured_image' => __( 'Background image' ),
            'set_featured_image' => __( 'Set background image' ),
            'remove_featured_image' => __( 'Remove background image' ),
            'use_featured_image' => __( 'Use background image' ),
        ),
        'public' => false,
        'publicly_queryable'  => false,
        'show_ui' => true,
        // 'show_in_menu' => 'themes.php',
        'exclude_from_search' => true,
        'has_archive' => false,
        'menu_position' => 100,
        'menu_icon' => '',
        'capability_type' => ['tile', 'tiles'],
        'map_meta_cap' => true,
        // 'hierarchical' => true,
        'supports' => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'page-attributes' ),
        'taxonomies' => array()
    ));
        
    // AARGH GRAMMER 
    register_taxonomy( 'tiles_category', 'tiles', array(
        'hierarchical' => true,
        'sort' => true,
        'labels' => array(
            'name' => _x( 'Tile Categories', 'taxonomy general name' ),
            'singular_name' => _x( 'Tile Category', 'taxonomy singular name' ),
            'search_items' =>  __( 'Search Tile Categories' ),
            'all_items' => __( 'All Tile Categories' ),
            'parent_item' => __( 'Parent Tile Category' ),
            'parent_item_colon' => __( 'Parent Tile Category:' ),
            'edit_item' => __( 'Edit Tile Category' ),
            'update_item' => __( 'Update Tile Category' ),
            'add_new_item' => __( 'Add New Tile Category' ),
            'new_item_name' => __( 'New Tile Category' ),
            'menu_name' => __( 'Categories' ),
        ),
        'rewrite' => array(
            'slug' => 'tile-categories', // This controls the base slug that will display before each term
            'with_front' => false, // Don't display the category base before "/locations/"
            // 'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
        ),
        'capabilities' => array(
            'assign_terms' => 'manage_categories',
        )
    ));
    
    // Make some persistent terms
    // Homepage tiles appear on the homepage
    if ( ! term_exists('Homepage') ){
        wp_insert_term('Homepage', 'tiles_category', [
            'description'=> 'These tiles appear on the home page',
            'slug' => 'homepage',
        ]);        
    }
    // Header tiles appear in the common header (the slanty things)
    if ( ! term_exists('Header') ){
        wp_insert_term('Header', 'tiles_category', [
            'description'=> 'These tiles appear in the common header. Max 3 will be displayed.',
            'slug' => 'header',
        ]);
    }
    // Region tiles appear on the regions page
    if ( ! term_exists('Region') ){
        wp_insert_term('Region', 'tiles_category', [
            'description'=> 'These tiles appear on the states/regions homepage',
            'slug' => 'region',
            // 'alias_of' => 'region',
        ]);
    }
    
}
endif;

if ( ! function_exists('register_tile_post_type_styles') ):
function register_tile_post_type_styles(){
    $customPostType = 'tiles';
?>
<style>#adminmenu .menu-icon-<?= $customPostType ?> div.wp-menu-image:before { content: "\f180"; } </style>
<?php
}
endif;

add_action( 'init', 'register_tile_post_type' );
add_action( 'admin_head', 'register_tile_post_type_styles' );


/*===============================
 * Modify admin page table
 *===============================*/
function gh_tiles_table_head( $defaults ) {
    unset($defaults['date']);
    $defaults['tile_page_link']  = 'Linked to page';
    $defaults['tile_category']  = 'Tile Type';
    $defaults['tile_color']  = 'Back Color';
    $defaults['date'] = 'Date';
    return $defaults;
}
add_filter('manage_tiles_posts_columns', 'gh_tiles_table_head');


function gh_tiles_table_content( $column_name, $post_id ) {

    if ($column_name == 'tile_category') {
        $terms = wp_get_post_terms( $post_id, 'tiles_category' );
        $term_names = [];
        array_walk( $terms, function (&$item, $key) use (&$term_names){
            switch ($item->name){
                case 'Region':
                case 'region':
                    $colors = ['rebeccapurple', 'white'];
                    break;
                case 'Homepage':
                case 'homepage':
                    $colors = ['dodgerblue', 'white'];
                    break;
                case 'Header':
                case 'header':
                    $colors = ['steelblue', 'white'];
                    break;                
                case 'Announcement':
                case 'announcement':
                    $colors = ['#d35400', 'white'];
                    break;
                default:
                    $colors = ['inherit', 'inherit'];
            }
            
            $term_names[] = '<span style="display: inline-block; padding: 2px 5px; background: ' . $colors[0] . '; color: white;">' . $item->name . '</span>';                
        } );
        echo implode(', ', $term_names);
        return;
    }
    
    if ($column_name == 'tile_color'){
        echo '<code>' . get_post_meta( $post_id, 'tile_color', true ) . '</code>';
        return;
    }
    
    if ($column_name == 'tile_page_link'){
        $linked_page_id = get_post_meta( $post_id, 'linked_page_id', true );
        if ($linked_page_id){
            echo '<a href="' . get_permalink( $linked_page_id ) . '">' . get_the_title( $linked_page_id ) . '</a>';
            return;
        }
        $linked_page_slug = get_post_meta( $post_id, 'linked_page_slug', true );
        if ($linked_page_slug){
            echo '<code>' . $linked_page_slug . '</code>';
            return;
        }
    }

}
add_action( 'manage_tiles_posts_custom_column', 'gh_tiles_table_content', 10, 2 );


/*===============================
 * Filter to add some potential to tile renderer
 *===============================*/
function add_tile_query_vars_filter( $vars ){
  $vars[] = 'tiles_per_row';
  return $vars;
}
add_filter( 'query_vars', 'add_tile_query_vars_filter' );