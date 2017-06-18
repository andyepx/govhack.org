<?php
/**
 * Template Name: GH Locations Page
 *
 * @package Sequential
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); $location_page_title = get_the_title(); endwhile; ?>

<div id="primary">
    <header class="hero location-hero" role="banner">
        <div class="wrapper location-heading-wrapper">
            <?php gh_row_open() ?>
            <?php gh_col_open(1, 2, ['locations-page-first-column']) ?>
                <h1 class="hero-title"><?php echo $location_page_title ?></h1>
            </div><!-- .locations-page-first-column -->
            <?php gh_col_open(1, 2, ['locations-page-second-column']) ?>
                <aside class="hero-secondary">
                    <h2 class="hero-subtitle"><span id="location-total-count" class="counter total-counter"></span> locations and counting</h2>
                    <dl class="location-stat"><dt id="location-official-count" class="counter official-counter"></dt> <dd>official event locations</dd></dl>
                    +
                    <dl class="location-stat"><dt id="location-node-count" class="counter node-counter"></dt> <dd>node event locations</dd></dl>
                    <!-- <div class="location-type-chooser-wrapper">
                        <select id="#location-type-chooser" class="location-type-chooser">
                            <option value="all-events">All event types</option>
                            <option value="official">Official events</option>
                            <option value="node">Node events</option>
                        </select>
                    </div> -->
                </aside>
            </div><!-- .locations-page-second-column -->
            <?php gh_row_close() ?>
        </div>
    </header>

    <div id="gh-location-map" class="location-map"></div>
    
    <section class="location-finder-content-area">
        <div class="wrapper">
            <nav id="location-finder-nav" class="location-finder-nav">
                <a href="#" id="map-toggle" class="map-toggle">Show/hide map</a>
                <p><?php echo __('Filter events by location', 'sequential') ?> </p>
                <!-- <a class="button-minimal location-official" role="button"><span id="toggle-official-locations" class="fa fa-check-square" aria-hidden="true" ></span> Official</a>
                <a class="button-minimal location-node" role="button"><span id="toggle-node-locations" class="fa fa-check-square" aria-hidden="true"></span> Node</a>
                | -->
                <div class="location-area-button-wrapper"></div>
            </nav>
        </div>    
    </section>

    <?php
    $loc_parser = gh_get_location_parser();
    if ( $loc_parser->is_sourced_locally() && $loc_parser->parse_locations() ){
        
        // The locally retrieved stuff
        
        global $post;
        $locations_column_cache = $loc_parser->get_locations_locally( 'columns' ); ?>
    <main id="main" class="site-main content-area gh-locations-locally-retrieved" role="main">
        <div class="wrapper">
        <?php gh_row_open(); foreach ( $locations_column_cache as $locations_column): ?>
            <?php gh_col_open(1, $loc_parser::$display_columns) ?>
            <?php foreach ($locations_column as $loc): setup_postdata( $post =& $loc['obj'] ); ?>
                <article class="location-card">
                    <header class="card-header">
                        <h3 class="card-heading"><?php the_title() ?></h3>
                        <h4 class="card-subheading"><?= $loc['type'] ?></h4>
                    </header>
                    <?php if (has_excerpt()): ?>
                    <div class="entry-content">
                        <?php the_excerpt() ?>
                    </div>
                    <?php endif; ?>
                    <footer class="card-footer">
                        <a class="button-minimal location-<?= $loc['slug'] ?>" href="<?php the_permalink() ?>" target="_blank">View info</a>
                    </footer>
                </article>                
            <?php endforeach; wp_reset_postdata(); ?>
            </div><!-- .column-* -->
        <?php endforeach; gh_row_close(); ?>
        </div><!-- .wrapper -->
    </main><!-- #main -->
    <?php
    }
    elseif ( $loc_parser->is_sourced_remotely() && $loc_parser->parse_locations() ) { 
    
        // The Remotely retrieved stuff
        
        $locations_by_region = $loc_parser->get_locations_remotely( 'regions' ); ?>
    <main id="main" class="site-main content-area gh-locations-remotely-retrieved" role="main">
        <div class="wrapper">
            <?php foreach ($locations_by_region as $region_name => $locations_in_region): ?>
            <?php $region_chunk_size = max(1, ceil(count($locations_in_region) / $loc_parser::$display_columns)); ?>
            <section class="location-region filter-hidden filter-default-display" data-region="<?= $region_name ?>">
                <h3 class="location-region-name"><?= $loc_parser->decode_region_name($region_name) ?></h3>
                <?php gh_row_open(); foreach ( array_chunk($locations_in_region, $region_chunk_size) as $locations_column): ?>
                <?php gh_col_open(1, $loc_parser::$display_columns) ?>
                <?php foreach ($locations_column as $loc): ?>
                    <article class="location-card" data-region="<?= $loc->region ?>" data-event-type="<?= $loc->type_clean ?>">
                        <header class="card-header">
                            <h3 class="card-heading"><?= $loc->name ?></h3>
                            <h4 class="card-subheading"><?= strtoupper($loc->region), ' ', $loc->type ?></h4>
                        </header>
                        <footer class="card-footer">
                            <a class="button-minimal location-<?= $loc->type_clean ?>" href="<?= esc_url($loc->url) ?>">Info</a>
                            <?php if ( property_exists($loc, 'eventbrite') ): ?>
                            <a class="button-minimal location-<?= $loc->type_clean ?>" href="<?= esc_url($loc->eventbrite) ?>" target="_blank">Tickets</a>
                            <?php endif; ?>
                        </footer>
                    </article>
                <?php endforeach;  // end column ?>
                </div><!-- .column-* -->
            <?php endforeach; gh_row_close(); // end chunked ?>
            </section><!-- .location-region -->
        <?php endforeach; // end each region ?>
        </div><!-- .wrapper -->
    </main><!-- #main -->   
    <?php 
    } ?>
    <script>
    // Irrespective of loc_parser type, the counts should have been produced and available
    var GH = GH || {};
    GH.locationFinder = {
        
        bootstrapElementId: 'gh-location-map',
        regions: JSON.parse('<?= json_encode( $loc_parser->get_regions() ) ?>'),
        registerMarkers: function(L){
            
            var markers = {};
            var mapIcons = {
                official: L.icon({ iconUrl: '<?php gh_location_pin_url() ?>', iconSize: [32, 37] }),
                'official-event': L.icon({ iconUrl: '<?php gh_location_pin_url() ?>', iconSize: [32, 37] }),
                node: L.icon({ iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37] }),        
                'maker-node': L.icon({ iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37] }),
                'theme-node': L.icon({ iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37] }),
                'youth-node': L.icon({ iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37] })
            };
            
            // I make my scripting blocks cryptic on purpose.
            // Sorry, this is actually fucking unreadable
            <?php if ($loc_parser->is_sourced_locally()): foreach ($loc_parser->get_locations_locally( 'cards' ) as $loc): $popupName = addslashes( $loc['title'] . ( $loc['type_clean'] == 'official' ? '' : ' (' . $loc['type'] . ')' ) ); ?>
            (markers.all = (Array.isArray(markers.all) ? markers.all : [])) && markers['all'].push(L.marker([<?= $loc['lat'] ?>,<?= $loc['long'] ?>], { icon: mapIcons['<?= $loc['slug'] ?>'] || mapIcons.node }).bindPopup('<?= $popupName ?>'));
            <?php endforeach; endif; ?>
            <?php if ($loc_parser->is_sourced_remotely()): foreach ($loc_parser->get_locations_remotely( 'cards' ) as $loc): $popupName = addslashes( $loc->name . ( $loc->type_clean == 'official' ? '' : ' (' . $loc->type . ')' ) ); ?>
            (function(){
                if ( ! Array.isArray(markers['<?= $loc->region ?>'])){
                    markers['<?= $loc->region ?>'] = [];
                }
                markers['<?= $loc->region ?>'].push(L.marker([<?= $loc->location->lat ?>,<?= $loc->location->lon ?>], { icon: mapIcons['<?= $loc->type_clean ?>'] || mapIcons.node }).bindPopup('<?= $popupName ?>'));                
            }());
            <?php endforeach; endif; ?>
        
            return markers;
            
        }
        
    };
    
    try {
        document.getElementById('location-total-count').innerHTML = '<?= $loc_parser->total_count; ?>';
        document.getElementById('location-node-count').innerHTML = '<?= $loc_parser->node_count; ?>';
        document.getElementById('location-official-count').innerHTML = '<?= $loc_parser->official_count; ?>';        
    }
    catch (err){
        console.warn(err);
    }
    
    </script>
    
    <!-- Our clone host... -->
    <a id="location-toggle-button-clone-source" style="display: none;" class="button-minimal" role="button" data-checked="false"><span class="fa fa-square" aria-hidden="true"></span> <span class="button-label"></span> <span class="button-label-secondary"></span></a>
       
</div><!-- #primary -->


<?php get_footer(); ?>