<?php 
// HEADER tiles
// i.e. the 4x slanted tiles that go across the header
// More tiles for the tile god

$tiles_query_args = [ 
    'post_type' => 'tiles', 
    'post_status' => ['publish'], 
    'posts_per_page' => 3, 
    'orderby' => 'menu_order', 
    'order' => 'ASC',
    'tax_query'=> [
        [ 'taxonomy' => 'tiles_category', 'field' => 'slug', 'terms' => 'header' ]
    ]
];

$tiles = get_posts($tiles_query_args);
$counter = 1;       // Start at 1, because the logo header tile already exists

foreach ($tiles as $tile) {
    $class_names = [];
    $attrs = [];
    
    $class_names[] = 'gh-headerdevice';
    $class_names[] = 'gh-headerdevice-' . ++$counter;
    $class_names[] = $counter % 2 == 0 ? 'blue' : 'pink';
    
    $loader_blue = home_url( '/img/loading-ghblue.png' );
    $loader_pink = home_url( '/img/loading-ghpink.png' );    

    $tile_link = get_the_permalink( $tile->ID );
    if ($tile_linked_page_id = get_post_meta( $tile->ID , 'linked_page_id', true)){
        $tile_link = get_permalink($tile_linked_page_id);
    }
    elseif ($tile_linked_page_slug = get_post_meta( $tile->ID , 'linked_page_slug', true)){
        $linked_page = get_page_by_path($tile_linked_page_slug, OBJECT);
        if (is_object($linked_page)){
            $tile_link = get_permalink($linked_page->ID);
        }
    }

    if ( $use_featherlight = get_post_meta( $tile->ID , 'use_featherlight_iframe', true) ){
        $tile_link .= '?' . http_build_query([ 'display_mode' => 'content-only' ]);
        // if ( $use_featherlight_value = get_post_meta( $tile->ID , 'use_featherlight_value', true) ){
        //     $attrs[] = 'data-featherlight="' . $use_featherlight_value . '"';
        // }
        // else {
        // }
        $attrs[] = 'data-featherlight="iframe"';
        $attrs[] = 'data-featherlight-persist="true"';
        $attrs[] = 'data-featherlight-variant="lightbox-white lightbox-fullscreen"';
        $attrs[] = 'data-featherlight-loading="Just a moment please"';
    }
    
    array_walk( $class_names, 'esc_attr' );
    array_walk( $attrs, 'esc_attr' );
    
?>
<a class="<?= implode( ' ', $class_names ) ?>" href="<?= esc_url( $tile_link ) ?>" <?= implode( ' ', $attrs ); ?>>
    <span class="headline"><?= $tile->post_title ?></span>
    <span class="subheadline"><?= $tile->post_excerpt ?></span>
</a>
<?php 
}  // endforeach