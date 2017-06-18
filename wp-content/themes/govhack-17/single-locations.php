<?php
/**
 * The template for displaying all single posts.
 *
 * @package Sequential
 */

get_header(); ?>

    <script src='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.js'></script>
    <link href='https://api.mapbox.com/mapbox.js/v3.1.1/mapbox.css' rel='stylesheet'/>
    <script>
        function location_map(locations) {
            jQuery(document).ready(function () {
                var map = L.map('gh-location-map', {
                    minZoom: 4,
                    maxBounds: new L.LatLngBounds([-1, 100], [-60, 190])
                }).setView([-28.42, 147.129], 3);
                var mapIcons = {
                    official: L.icon({iconUrl: '/resources/images/leaflet/redpin.png', iconSize: [32, 37]}),
                    node: L.icon({iconUrl: '/resources/images/leaflet/bluepin.png', iconSize: [32, 37]}),
                    'maker-node': L.icon({iconUrl: '/resources/images/leaflet/bluepin.png', iconSize: [32, 37]}),
                    'theme-node': L.icon({iconUrl: '/resources/images/leaflet/bluepin.png', iconSize: [32, 37]}),
                    'youth-node': L.icon({iconUrl: '/resources/images/leaflet/bluepin.png', iconSize: [32, 37]})
                };
                L.tileLayer('https://{s}.tiles.mapbox.com/v3/{id}/{z}/{x}/{y}.png', {
                    maxZoom: 18,
                    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                    '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                    'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                    id: 'maxious.gdb67hbh'
                }).addTo(map);

                var markers = [];
                markers.push(
                    L.marker(locations,
                        {
                            icon: mapIcons['official'] || mapIcons.node
                        }
                    ).addTo(map)
                );

                var group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds()).zoomOut(2);
            });
        };
    </script>


    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="wrapper">
                <?php while (have_posts()) : the_post(); ?>

                    <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>

                <?php $custom_fields = get_post_custom(); ?>

                    <h3>Contact</h3>
                    <p>
                        <?php echo $custom_fields['email'][0] ?>
                        <br>
                        <?php echo $custom_fields['twitter'][0] ?>
                    </p>

                    <h3>Venue</h3>
                    <p>
                        <div id="gh-location-map" class="location-map" style="width: 100%; min-height: 20vh;"></div>
                        <script type="text/javascript">
                            <?php $ll = explode(',', $custom_fields['map_latlong'][0]); ?>
                            location_map([<?php echo $ll[0] ?>, <?php echo $ll[1] ?>]);
                        </script>
                    </p>

                    <p>
                        <b>Name & Address: </b>
                        <br>
                        <?php echo $custom_fields['venue_name'][0] ?>
                        <br>
                        <em><?php echo $custom_fields['venue_address'][0] ?></em>
                    </p>
                    <p>
                        <b>Host: </b> <?php echo $custom_fields['venue_host'][0] ?>
                        <br>
                        <b>Team: </b> <?php echo $custom_fields['venue_team'][0] ?>
                    </p>
                    <p>
                        <b>Accessibility: </b>
                        <br>
                        <?php echo $custom_fields['venue_accessibility'][0] ?>
                    </p>
                    <p>
                        <b>Under 18: </b>
                        <br>
                        <?php echo $custom_fields['venue_under_18'][0] ?>
                    </p>
                    <p>
                        <b>Capacity: </b>
                        <br>
                        <?php echo $custom_fields['venue_capacity'][0] ?>
                    </p>
                    <p>
                        <b>Parking: </b>
                        <br>
                        <?php echo $custom_fields['venue_parking'][0] ?>
                    </p>
                    <p>
                        <b>Public transport: </b>
                        <br>
                        <?php echo $custom_fields['venue_public_transport'][0] ?>
                    </p>
                    <p>
                        <b>Public transport last: </b>
                        <br>
                        <?php echo $custom_fields['venue_public_transport_last'][0] ?>
                    </p>
                    <p>
                        <b>Opening times: </b>
                        <br>
                        <?php echo $custom_fields['times'][0] ?>
                    </p>


                <?php endwhile; // end of the loop. ?>
            </div><!-- .wrapper -->
        </main><!-- #main -->
    </div><!-- #primary -->

<?php // get_sidebar(); ?>
<?php get_footer(); ?>