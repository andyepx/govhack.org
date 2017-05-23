<?php
// Read from a theme mod... 
// Or a sensible default
$tiles_query_posts_per_row = get_theme_mod('gh_tile_columns', 3); 

// Only include where taxonomy=tiles_category, term=homepage
$tiles_query_args = [ 
    'post_type' => 'tiles', 
    'post_status' => ['publish'], 
    'posts_per_page' => 999, 
    'orderby' => 'menu_order', 
    'order' => 'ASC',
    'tax_query'=> [
        [ 'taxonomy' => 'tiles_category', 'field' => 'slug', 'terms' => 'homepage' ]
    ]
];

// We are using query_posts BECAUSE we use this to drive a downstream template
// ( in this case, it's hardcoded as ('content', 'tiles') )
// and query_posts is a good way of altering the main loop to allow us to do so.
query_posts($tiles_query_args);
set_query_var('tiles_per_row', $tiles_query_posts_per_row);
get_template_part( 'content', 'tiles' );
wp_reset_query();
