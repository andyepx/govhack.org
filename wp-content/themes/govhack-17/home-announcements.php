<?php
/**
 * THE HOMEPAGE ANNOUNCEMENT TILE SECTION
 * Renders announcement tiles (yes actually just another type of tile)
 */
 
$ann_tiles_query_args = [ 
    'post_type' => 'tiles', 
    'post_status' => ['publish'], 
    'posts_per_page' => 999, 
    'orderby' => 'menu_order', 
    'order' => 'DESC',
    'tax_query'=> [
        [ 'taxonomy' => 'tiles_category', 'field' => 'slug', 'terms' => 'announcement' ]
    ]
];

// Yup just render them here and now
// Dirtyyyy just how a bad coder likes it
global $post;
$announcements = get_posts($ann_tiles_query_args); 
foreach ( $announcements as $post ) : setup_postdata( $post ); ?>
    <article id="post-<?php the_ID(); ?>" class="announcement"  <?php // post_class( 'announcement' ); ?>>
    <header><?php echo get_the_date(); ?>: <?php the_title() ?></header>
    <div class="entry-content">
        <?php 
        $announcement_content = get_post_meta( get_the_id() , 'tile_announcement_content', true); 
        echo do_shortcode($announcement_content);
        ?>
    </div>
    </article>
<?php endforeach; // end of the loop.
wp_reset_postdata();