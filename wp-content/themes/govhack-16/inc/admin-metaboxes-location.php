<?php

/**
 * Location meta box
 */

// Location metabox
function gh_location_add_meta_boxes() {
    add_meta_box(
        'gh-location-meta',      // Unique ID
        esc_html__( 'Location metadata', 'example' ),    // Title
        'gh_location_meta_box',   // Callback function
        'locations'         // Admin page (or post type)
    );
}
add_action( 'add_meta_boxes', 'gh_location_add_meta_boxes' );

function gh_location_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_location_nonce' ); ?>
    <p><label for="gh-location-meta-latlong"><?php _e( "Lat/Long co-ordinates, separated with a comma", 'sequential' ); ?></label> <code>map_latlong</code></p>
    <p><input class="widefat" type="text" name="map_latlong" id="gh-location-meta-latlong" value="<?php echo esc_attr( get_post_meta( $object->ID, 'map_latlong', true ) ); ?>" size="30" /></p>
    <p><a href="#" id="gh-location-latlong-check">Check location</a> (Not working at the moment)
    <br/><span style="opacity: .7;" id="gh-location-latlong-check-result"></span></p>
    <script>
    (function($){
        window.ghLatLongCheckCallback = function(result){
            console.log(result);
            if (result instanceof Array){
                if (result.length > 0){
                    var thefirstone = result[0];
                    var desc = `${thefirstone.name}, ${thefirstone.locality} ${thefirstone.postcode} + ${result.length} more results`;
                    $('#gh-location-latlong-check-result').html(desc);
                }
                else {
                    $('#gh-location-latlong-check-result').html('Nothing found');
                }
            }
            else {
                $('#gh-location-latlong-check-result').html('<pre>' + result + '</pre>');
            }
        };
        $(function(){
            $('#gh-location-latlong-check').click(function(){
                $('#gh-location-latlong-check-result').html('');
                var coords = $('#gh-location-meta-latlong').val().split(',');
                if (coords.length < 2){
                    $('#gh-location-latlong-check-result').html('No comma found in coord value');                
                    return;
                }
                $.ajax({
                    type: 'GET',
                    url: 'http://v0.postcodeapi.com.au/radius.json', 
                    jsonpCallback: 'ghLatLongCheckCallback',
                    data: {
                        latitude: $.trim(coords[0]),
                        longitude: $.trim(coords[1]),
                        distance: 2000
                    }, 
                    dataType: 'jsonp',
                    crossDomain: true,
                    success: function(result){
                        window.ghLatLongCheckCallback(result);
                    },
                    error: function(err){
                        console.warn(err);
                        $('#gh-location-latlong-check-result').html('ERROR');
                    }
                });
                return false;
            });
        });
    }(jQuery));
    </script>
<?php    
}

function gh_location_meta_box_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'gh_location_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_location_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'map_latlong' ] ) ) {
        update_post_meta( $post_id, 'map_latlong', sanitize_text_field( $_POST[ 'map_latlong' ] ) );
    }
 
}
add_action( 'save_post', 'gh_location_meta_box_save' );
