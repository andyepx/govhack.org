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
                    official: L.icon({iconUrl: '/wp-content/themes/govhack-17/img/redpin.png', iconSize: [32, 37]}),
                    node: L.icon({iconUrl: '/wp-content/themes/govhack-17/img/bluepin.png', iconSize: [32, 37]}),
                    'maker-node': L.icon({
                        iconUrl: '/wp-content/themes/govhack-17/img/bluepin.png',
                        iconSize: [32, 37]
                    }),
                    'theme-node': L.icon({
                        iconUrl: '/wp-content/themes/govhack-17/img/bluepin.png',
                        iconSize: [32, 37]
                    }),
                    'youth-node': L.icon({iconUrl: '/wp-content/themes/govhack-17/img/bluepin.png', iconSize: [32, 37]})
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

<?php while (have_posts()) : the_post(); ?>
    <div class="hero region-hero">
        <div class="wrapper">

            <div class="entry-written-content">

                <header class="hero-header">
                    <h1 class="hero-heading"><?php the_title(); ?></h1>
                </header>

            </div><!-- .entry-written-content -->
        </div><!-- .wrapper -->
    </div>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div class="wrapper">

                <?php the_content(); ?>

                <?php $custom_fields = get_post_custom(); ?>

                <?php if ((array_key_exists('email', $custom_fields) && strlen($custom_fields['email'][0]) > 0) || (array_key_exists('twitter', $custom_fields) && strlen($custom_fields['twitter'][0]) > 0)) { ?>
                    <h3>Contact</h3>
                    <p>
                        <?php echo (array_key_exists('email', $custom_fields) && strlen($custom_fields['email'][0]) > 0) ? $custom_fields['email'][0] : "" ?>
                        <br>
                        <?php echo (array_key_exists('twitter', $custom_fields) && strlen($custom_fields['twitter'][0]) > 0) ?
                            "<a href='//twitter.com/" . $custom_fields['twitter'][0] . "' target='_blank'>" . $custom_fields['twitter'][0] . "</a>" : "" ?>
                    </p>
                <?php } ?>

                <h3>Venue</h3>
                <p>
                <div id="gh-location-map" class="location-map" style="width: 100%; min-height: 20vh;"></div>
                <script type="text/javascript">
                    <?php $ll = explode(',', $custom_fields['map_latlong'][0]); ?>
                    location_map([<?php echo $ll[0] ?>, <?php echo $ll[1] ?>]);
                </script>
                </p>

                <?php
                $showName = (array_key_exists('venue_name', $custom_fields) && strlen($custom_fields['venue_name'][0]) > 0);
                $showAddress = (array_key_exists('venue_address', $custom_fields) && strlen($custom_fields['venue_address'][0]) > 0);
                if ($showName || $showAddress) { ?>
                    <p>
                        <b>Name & Address: </b>
                        <br>
                        <?php echo $showName ? nl2br($custom_fields['venue_name'][0]) : "" ?>
                        <br>
                        <em><?php echo $showAddress ? nl2br($custom_fields['venue_address'][0]) : "" ?></em>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_host', $custom_fields) && strlen($custom_fields['venue_host'][0]) > 0) { ?>
                    <p>
                        <b>Host: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_host'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_team', $custom_fields) && strlen($custom_fields['venue_team'][0]) > 0) { ?>
                    <p>
                        <b>Team: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_team'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_accessibility', $custom_fields) && strlen($custom_fields['venue_accessibility'][0]) > 0) { ?>
                    <p>
                        <b>Accessibility: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_accessibility'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_under_18', $custom_fields) && strlen($custom_fields['venue_under_18'][0]) > 0) { ?>
                    <p>
                        <b>Under 18: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_under_18'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_capacity', $custom_fields) && strlen($custom_fields['venue_capacity'][0]) > 0) { ?>
                    <p>
                        <b>Capacity: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_capacity'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_parking', $custom_fields) && strlen($custom_fields['venue_parking'][0]) > 0) { ?>
                    <p>
                        <b>Parking: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_parking'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_public_transport', $custom_fields) && strlen($custom_fields['venue_public_transport'][0]) > 0) { ?>
                    <p>
                        <b>Public transport: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_public_transport'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('venue_public_transport_last', $custom_fields) && strlen($custom_fields['venue_public_transport_last'][0]) > 0) { ?>
                    <p>
                        <b>Public transport last: </b>
                        <br>
                        <?php echo nl2br($custom_fields['venue_public_transport_last'][0]) ?>
                    </p>
                <?php } ?>
                <?php if (array_key_exists('times', $custom_fields) && strlen($custom_fields['times'][0]) > 0) { ?>
                    <p>
                        <b>Opening times: </b>
                        <br>
                        <?php echo nl2br($custom_fields['times'][0]) ?>
                    </p>
                <?php } ?>

                <?php get_template_part('location', 'mentors'); ?>

                <?php get_template_part('location', 'sponsors'); ?>


            </div><!-- .wrapper -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php endwhile; // end of the loop. ?>

<?php // get_sidebar(); ?>
<?php get_footer(); ?>