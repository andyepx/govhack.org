<?php
/**
 * @package Sequential
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result' ); ?>>

	<header class="search-header">
		<?php 
        the_title( sprintf( '<h5 class="search-title"><a href="%s" rel="bookmark">', esc_url( sequential_get_link_url() ) ), '</a></h5>' ); 
        ?>

        <div class="search-meta">
        <?php
        gh_breadcrumb();
        echo ' &mdash; ';
        
        echo '<span class="search-result-url">';
        the_permalink(); 
        echo '</span>';
        
        ?>
        </div>
        
	</header><!-- .entry-header -->
    
	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->
    
</article><!-- #post-## -->
