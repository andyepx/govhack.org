<?php
/**
 * Register sidebars for every region
 *
 * @package Sequential
 */ 

function gh_region_widgets_init() {
    
    // Lookup all the region tiles, 
    // if ( ! post_type_exists('tiles') ){
        // error_log('[WARN] Tiles content type doesnt exist');
        // return;
    // }
    
    // global $post;
    
    // $tiles_query_args = [ 
        // 'post_type' => 'tiles', 
        // 'post_status' => ['publish'], 
        // 'posts_per_page' => 999, 
        // 'orderby' => 'menu_order', 
        // 'order' => 'ASC',
        // 'tax_query'=> [
            // [ 'taxonomy' => 'tiles_category', 'field' => 'slug', 'terms' => 'region' ]
        // ]
    // ];
    
    // $region_tiles = get_posts($tiles_query_args);
    // $regions = [
        // ( object )[ 'slug' => 'nsw', 'title' => 'New South Wales' ],
        // ( object )[ 'slug' => 'qld', 'title' => 'Queensland' ],
        // ( object )[ 'slug' => 'vic', 'title' => 'Victoria' ],
        // ( object )[ 'slug' => 'wa', 'title' => 'Western Australia' ],
        // ( object )[ 'slug' => 'sa', 'title' => 'South Australia' ],
        // ( object )[ 'slug' => 'tas', 'title' => 'Tasmania' ],
        // ( object )[ 'slug' => 'act', 'title' => 'ACT' ],
        // ( object )[ 'slug' => 'nz', 'title' => 'New Zealand' ],
    // ];
    
    // Appears that get_posts functionality isnt all there yet
    global $wpdb;
    $tax = 'tiles_category';
    $slug = 'region';
    $regions = $wpdb->get_results("SELECT p.post_title as title, p.post_name as slug FROM {$wpdb->posts} p inner join {$wpdb->term_relationships} tr on tr.object_id = p.ID inner join {$wpdb->term_taxonomy} tt on tr.term_taxonomy_id = tt.term_taxonomy_id and tt.taxonomy = '$tax' inner join {$wpdb->terms} t on tt.term_id = t.term_id and t.slug = '$slug' where p.post_type = 'tiles' and p.post_status='publish'");
  
    foreach ($regions as $region){
    
        register_sidebar( array(
            'name'          => esc_html__( 'Region ' . $region->title, 'sequential' ),
            'id'            => 'region-sidebar-'.$region->slug,
            'description'   => 'Sidebar for '.$region->title. ' region page',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h1 class="widget-title">',
            'after_title'   => '</h1>',
        ) );
        
    }
    
}
add_action( 'widgets_init', 'gh_region_widgets_init' );
