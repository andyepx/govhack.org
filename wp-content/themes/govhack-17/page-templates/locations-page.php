<?php
/**
 * Template Name: GH Locations Page
 *
 * @package Sequential
 */

get_header(); ?>

<?php while (have_posts()) : the_post();
    $location_page_title = get_the_title(); endwhile; ?>

    <div id="primary">
        <header class="hero location-hero" role="banner">
            <div class="wrapper location-heading-wrapper">
                <?php gh_row_open() ?>
                <?php gh_col_open(1, 2, ['locations-page-first-column']) ?>
                <h1 class="hero-title"><?php echo $location_page_title ?></h1>
            </div><!-- .locations-page-first-column -->
            <?php gh_col_open(1, 2, ['locations-page-second-column']) ?>
            <aside class="hero-secondary">
                <h2 class="hero-subtitle"><span id="location-total-count" class="counter total-counter"></span>
                    locations and counting</h2>
                <dl class="location-stat">
                    <dt id="location-official-count" class="counter official-counter"></dt>
                    <dd>official event locations</dd>
                </dl>
                +
                <dl class="location-stat">
                    <dt id="location-node-count" class="counter node-counter"></dt>
                    <dd>node event locations</dd>
                </dl>
            </aside>
    </div><!-- .locations-page-second-column -->
<?php gh_row_close() ?>
    </div>
    </header>

    <div id="gh-location-map" class="location-map"></div>

<?php

$all_locations = gs_all_locations_list();
//print_r(sizeof($all_locations));
//print_r($all_locations);

?>


    <script>
        // Irrespective of loc_parser type, the counts should have been produced and available
        var GH = GH || {};
        GH.locationFinder = {

            bootstrapElementId: 'gh-location-map',
            regions: ['QLD', 'NSW', 'ACT', 'SA', 'TAS', 'NZ', 'NT', 'WA'],
            registerMarkers: function (L) {

                var markers = {};
                var mapIcons = {
                    official: L.icon({iconUrl: '<?php gh_location_pin_url() ?>', iconSize: [32, 37]}),
                    'official-event': L.icon({iconUrl: '<?php gh_location_pin_url() ?>', iconSize: [32, 37]}),
                    node: L.icon({iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37]}),
                    'maker-node': L.icon({iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37]}),
                    'theme-node': L.icon({iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37]}),
                    'youth-node': L.icon({iconUrl: '<?php gh_location_pin_url('node') ?>', iconSize: [32, 37]})
                };


                <?php foreach ($all_locations as $x => &$loc): $popupName = addslashes($loc['post_title']); $m = get_post_meta($loc->ID) ?>
                (markers.all = (Array.isArray(markers.all) ? markers.all : [])) && markers['all'].push(L.marker([<?= $m['lat'] ?>,<?= $m['long'] ?>], {icon: mapIcons['<?= $loc['post_title'] ?>'] || mapIcons.node}).bindPopup('<?= $popupName ?>'));
                <?php endforeach; ?>

                return markers;

            }

        };

        try {
            document.getElementById('location-total-count').innerHTML = '<?= sizeof($all_locations); ?>';
//            document.getElementById('location-node-count').innerHTML = '<?//= $loc_parser->node_count; ?>//';
//            document.getElementById('location-official-count').innerHTML = '<?//= $loc_parser->official_count; ?>//';
        }
        catch (err) {
            console.warn(err);
        }

    </script>

    <!-- Our clone host... -->
    <a id="location-toggle-button-clone-source" style="display: none;" class="button-minimal" role="button"
       data-checked="false"><span class="fa fa-square" aria-hidden="true"></span> <span class="button-label"></span>
        <span class="button-label-secondary"></span></a>

    </div><!-- #primary -->


<?php get_footer(); ?>