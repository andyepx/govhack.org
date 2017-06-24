<?php

/**
 * Location meta box
 */

// Location metabox
function gh_location_add_meta_boxes()
{
    add_meta_box(
        'gh-location-meta',      // Unique ID
        esc_html__('Location metadata', 'example'),    // Title
        'gh_location_meta_box',   // Callback function
        'locations'         // Admin page (or post type)
    );
}

add_action('add_meta_boxes', 'gh_location_add_meta_boxes');

function gh_location_meta_box($object)
{
    wp_nonce_field(basename(__FILE__), 'gh_location_nonce'); ?>

    <p><label for="region"><?php _e("Region", 'sequential'); ?></label> <code>region</code></p>
    <p>
        <select name="region" id="region">
            <option value="act" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'act' ? 'selected' : ''; ?>>
                Australian Capital Territory
            </option>
            <option value="nsw" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'nsw' ? 'selected' : ''; ?>>
                New South Wales
            </option>
            <option value="nt" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'nt' ? 'selected' : ''; ?>>
                Northern Territory
            </option>
            <option value="nz" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'nz' ? 'selected' : ''; ?>>
                New Zealand
            </option>
            <option value="qld" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'qld' ? 'selected' : ''; ?>>
                Queensland
            </option>
            <option value="sa" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'sa' ? 'selected' : ''; ?>>
                South Australia
            </option>
            <option value="tas" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'tas' ? 'selected' : ''; ?>>
                Tasmania
            </option>
            <option value="vic" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'vic' ? 'selected' : ''; ?>>
                Victoria
            </option>
            <option value="wa" <?php echo esc_attr(get_post_meta($object->ID, 'region', true)) == 'wa' ? 'selected' : ''; ?>>
                Western Australia
            </option>
        </select>
    </p>

    <p><label for="email"><?php _e("Email", 'sequential'); ?></label> <code>email</code></p>
    <p><input style="width: 100%;" type="email" id="email" name="email"
              value="<?php echo esc_attr(get_post_meta($object->ID, 'email', true)); ?>"></p>

    <p><label for="twitter"><?php _e("Twitter", 'sequential'); ?></label> <code>twitter</code></p>
    <p><input style="width: 100%;" type="twitter" id="twitter" name="twitter"
              value="<?php echo esc_attr(get_post_meta($object->ID, 'email', true)); ?>"></p>

    <h3>Venue information</h3>

    <p><label for="venue_name"><?php _e("Name", 'sequential'); ?></label> <code>venue_name</code></p>
    <p><textarea style="width: 100%;" id="venue_name"
                 name="venue_name"><?php echo esc_attr(get_post_meta($object->ID, 'venue_name', true)); ?></textarea>
    </p>

    <p><label for="venue_address"><?php _e("Address", 'sequential'); ?></label> <code>venue_address</code></p>
    <p><textarea style="width: 100%;" id="venue_address"
                 name="venue_address"><?php echo esc_attr(get_post_meta($object->ID, 'venue_address', true)); ?></textarea>
    </p>

    <p><label for="venue_host"><?php _e("Host", 'sequential'); ?></label> <code>venue_host</code></p>
    <p>
        <input style="width: 100%;" type="text" id="venue_host" name="venue_host"
               value="<?php echo esc_attr(get_post_meta($object->ID, 'venue_host', true)); ?>">
    </p>

    <p><label for="venue_team"><?php _e("Team", 'sequential'); ?></label> <code>venue_team</code></p>
    <p>
        <input style="width: 100%;" type="text" id="venue_team" name="venue_team"
               value="<?php echo esc_attr(get_post_meta($object->ID, 'venue_team', true)); ?>">
    </p>

    <p><label for="venue_accessibility"><?php _e("Accessibility", 'sequential'); ?></label>
        <code>venue_accessibility</code>
    </p>
    <p><textarea style="width: 100%;" id="venue_accessibility"
                 name="venue_accessibility"><?php echo esc_attr(get_post_meta($object->ID, 'venue_accessibility', true)); ?></textarea>
    </p>

    <p><label for="venue_under_18"><?php _e("Under 18", 'sequential'); ?></label> <code>venue_under_18</code></p>
    <p><textarea style="width: 100%;" id="venue_under_18"
                 name="venue_under_18"><?php echo esc_attr(get_post_meta($object->ID, 'venue_under_18', true)); ?></textarea>
    </p>

    <p><label for="venue_capacity"><?php _e("Capacity", 'sequential'); ?></label> <code>venue_capacity</code></p>
    <p>
        <input style="width: 100%;" type="number" id="venue_capacity" name="venue_capacity"
               value="<?php echo esc_attr(get_post_meta($object->ID, 'venue_capacity', true)); ?>">
    </p>

    <p><label for="venue_parking"><?php _e("Parking", 'sequential'); ?></label> <code>venue_parking</code></p>
    <p><textarea style="width: 100%;" id="venue_parking"
                 name="venue_parking"><?php echo esc_attr(get_post_meta($object->ID, 'venue_parking', true)); ?></textarea>
    </p>

    <p><label for="venue_public_transport"><?php _e("Public Transport", 'sequential'); ?></label>
        <code>venue_public_transport</code></p>
    <p><textarea style="width: 100%;" id="venue_public_transport"
                 name="venue_public_transport"><?php echo esc_attr(get_post_meta($object->ID, 'venue_public_transport', true)); ?></textarea>
    </p>

    <p><label for="venue_public_transport_last"><?php _e("Public Transport Last", 'sequential'); ?></label> <code>venue_public_transport_last</code>
    </p>
    <p><textarea style="width: 100%;" id="venue_public_transport_last"
                 name="venue_public_transport_last"><?php echo esc_attr(get_post_meta($object->ID, 'venue_public_transport_last', true)); ?></textarea>
    </p>

    <p><label for="times"><?php _e("Times", 'sequential'); ?></label> <code>times</code></p>
    <p><textarea style="width: 100%;" id="times"
                 name="times"><?php echo esc_attr(get_post_meta($object->ID, 'times', true)); ?></textarea></p>

    <p><label for="catering"><?php _e("Catering", 'sequential'); ?></label> <code>catering</code></p>
    <p><textarea style="width: 100%;" id="catering"
                 name="catering"><?php echo esc_attr(get_post_meta($object->ID, 'catering', true)); ?></textarea></p>

    <p>
        <label for="gh-location-meta-latlong"><?php _e("Lat/Long co-ordinates, separated with a comma", 'sequential'); ?></label>
        <code>map_latlong</code></p>
    <p>
        <input style="width: 100%;" class="widefat" type="text" name="map_latlong" id="gh-location-meta-latlong"
               value="<?php echo esc_attr(get_post_meta($object->ID, 'map_latlong', true)); ?>" size="30"/>
    </p>

    <?php
}

function gh_location_meta_box_save($post_id)
{

    // Checks save status
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['gh_location_nonce']) && wp_verify_nonce($_POST['gh_location_nonce'], basename(__FILE__))) ? 'true' : 'false';

    // Exits script depending on save status
    if ($is_autosave || $is_revision || !$is_valid_nonce) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    update_post_meta($post_id, 'map_latlong', sanitize_text_field($_POST['map_latlong']));
    update_post_meta($post_id, 'email', sanitize_text_field($_POST['email']));
    update_post_meta($post_id, 'twitter', sanitize_text_field($_POST['twitter']));
    update_post_meta($post_id, 'region', sanitize_text_field($_POST['region']));
    update_post_meta($post_id, 'venue_name', ($_POST['venue_name']));
    update_post_meta($post_id, 'venue_host', ($_POST['venue_host']));
    update_post_meta($post_id, 'venue_team', ($_POST['venue_team']));
    update_post_meta($post_id, 'venue_accessibility', ($_POST['venue_accessibility']));
    update_post_meta($post_id, 'venue_under_18', ($_POST['venue_under_18']));
    update_post_meta($post_id, 'venue_capacity', ($_POST['venue_capacity']));
    update_post_meta($post_id, 'venue_parking', ($_POST['venue_parking']));
    update_post_meta($post_id, 'venue_public_transport', ($_POST['venue_public_transport']));
    update_post_meta($post_id, 'venue_public_transport_last', ($_POST['venue_public_transport_last']));
    update_post_meta($post_id, 'times', ($_POST['times']));
    update_post_meta($post_id, 'catering', ($_POST['catering']));

}

add_action('save_post', 'gh_location_meta_box_save');
