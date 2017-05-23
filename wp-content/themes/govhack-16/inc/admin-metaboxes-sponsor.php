<?php

/**
 * Sponsors meta box
 */

 function gh_sponsor_add_meta_boxes() {
    add_meta_box(
        'gh-sponsor-meta',      // Unique ID
        esc_html__( 'Sponsor Options', 'example' ),    // Title
        'gh_sponsor_meta_box',   // Callback function
        'sponsor'         // Admin page (or post type)
    );
}
add_action( 'add_meta_boxes', 'gh_sponsor_add_meta_boxes' );

function gh_sponsor_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_sponsor_nonce' ); 
    
    // Grab the locations from the provider
    // We'll display these as options for the the Portal Location GID
    $loc_parser = gh_get_location_parser();
    if ( $loc_parser->is_sourced_remotely() && $loc_parser->parse_locations() ){
        $locations_by_region = $loc_parser->get_locations_remotely( 'regions' );
    }
    
    $region_parent_page_id = gh_get_region_parent_page_id();
    
    ?>
    <p><em>Note: Refer to the Help tab at the top for a list of fields to fill in. </em></p>
    <p><label for="gh-sponsor-meta-sponsor-link"><?php _e( "Link to sponsor website", 'sequential' ); ?></label> <!--<code>link_sponsor</code>--></p>
    <p><input class="widefat" type="text" name="link_sponsor" id="gh-sponsor-meta-sponsor-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'link_sponsor', true ) ); ?>" placeholder="Fully qualified URL" /></p>
    
    <!--
    <p><label for="gh-sponsor-meta-portal-link"><?php _e( "Link to Jabberwocky", 'sequential' ); ?></label> </p>
    <p><input class="widefat" type="text" name="link_portal" id="gh-sponsor-meta-portal-link" value="<?php echo esc_attr( get_post_meta( $object->ID, 'link_portal', true ) ); ?>" placeholder="Fully qualified URL"/></p>
    -->
    
    <hr>
    <p><label for="gh-sponsor-meta-region"><?php _e( "Sponsored region (only useful if state/local sponsor)", 'sequential' ); ?></label> <!--<code>sponsor_region</code>--></p>
    <p><?php wp_dropdown_pages([ 'id' => 'gh-sponsor-meta-region', 'class' => 'widefat', 'name' => 'sponsor_region_id', 'child_of' => $region_parent_page_id, 'selected' => get_post_meta( $object->ID, 'sponsor_region_id', true ), 'show_option_none' => '-- N/A --' ]) ?></p>
    
    <hr>
    <p><label for="gh-sponsor-meta-portal-location-gids"><?php _e( "Portal Event IDs (only useful if a local sponsor)", 'sequential' ); ?></label></p>
    <?php 
    $sponsor_portal_location_gids = get_post_meta( $object->ID, 'sponsor_portal_location_gids' );
    $sponsor_portal_location_gids = !empty( $sponsor_portal_location_gids ) ? $sponsor_portal_location_gids[0] : [];
    if ( isset($locations_by_region) ): ?>
    <p class="howto">Current: <?php 
        echo empty( $sponsor_portal_location_gids ) 
            ? '(nothing)' 
            : esc_attr( implode(', ', $sponsor_portal_location_gids) ); 
    ?></p>
    <p>
        <select id="gh-sponsor-meta-portal-location-gids" name="sponsor_portal_location_gids[]" class="widefat" multiple size="6">
            <option value="">-- N/A --</option>
            <?php foreach ( $locations_by_region as $region_name => $locations_in_region ): ?>
            <optgroup label="<?php echo strtoupper($region_name) ?>">
                <?php foreach( $locations_in_region as $loc ): ?>
                <option value="<?php echo $loc->id ?>" <?php echo in_array($loc->id, $sponsor_portal_location_gids) ? 'selected' : '' ?>><?php echo $loc->name, ' (', $loc->type_clean, ')' ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php endforeach; ?>
        </select>
    </p>
    <?php else: ?>
    <p><input id="gh-sponsor-meta-portal-location-gids" type="text" name="sponsor_portal_location_gids" placeholder="CSV list of location GIDs" class="widefat" value="<?php echo esc_attr( implode(', ', $sponsor_portal_location_gids) ); ?>"></p>
    <?php endif; ?>
        
    <p><label for="gh-sponsor-meta-portal-type"><?php _e( "Portal Type", 'sequential' ); ?></label> </p>
    <?php 
    $current_sponsor_meta_portal_type = get_post_meta( $object->ID, 'sponsor_portal_type', true ); 
    
    $portal_type_community = $portal_type_corporate = $portal_type_government = false;
    ${"portal_type_$current_sponsor_meta_portal_type"} = true;
    $is_portal_type_manual = !($portal_type_community || $portal_type_government || $portal_type_corporate);
    
    ?>
    <p>
        <select id="gh-sponsor-meta-portal-type" name="sponsor_portal_type" class="widefat">
            <option value="">-- N/A --</option>
            <option value="corporate" <?php echo $portal_type_corporate ? 'selected="selected"' : '' ?>>Corporate</option>
            <option value="government" <?php echo $portal_type_government ? 'selected="selected"' : ''; ?>>Government</option>
            <option value="community" <?php echo $portal_type_community ? 'selected="selected"' : '' ?>>Community</option>
        </select> <br>
        
        <input type="text" name="sponsor_portal_type-manual" placeholder="... or manually define a sponsor portal type" class="widefat">
    </p>
    <?php if ( $is_portal_type_manual ): ?>
    <p class="howto">Current manual sponsor type is: <?php echo empty($current_sponsor_meta_portal_type) ? '(nothing)' : $current_sponsor_meta_portal_type ?></p>
    <?php endif; ?>
    
<?php    
}

function gh_sponsor_meta_box_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'gh_sponsor_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_sponsor_nonce' ], basename( __FILE__ ) ) );
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    
    // Validation: If a Portal Event ID is provided, but the state is not set, then error out.
    if ( isset( $_POST[ 'sponsor_portal_location_gids' ] ) && empty( $_POST[ 'sponsor_region_id' ] ) ){
        set_transient( 'sponsor_region_id_required', 'A sponsor region must be selected, if also selecting a Portal event ID (make sure both fields have values)' );
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'sponsor_region_id' ] ) ) {
        update_post_meta( $post_id, 'sponsor_region_id', sanitize_text_field( $_POST[ 'sponsor_region_id' ] ) );
    }
    if( isset( $_POST[ 'link_portal' ] ) ) {
        update_post_meta( $post_id, 'link_portal', sanitize_text_field( $_POST[ 'link_portal' ] ) );
    }
    if( isset( $_POST[ 'link_sponsor' ] ) ) {
        update_post_meta( $post_id, 'link_sponsor', sanitize_text_field( $_POST[ 'link_sponsor' ] ) );
    }
    if( isset( $_POST[ 'sponsor_portal_location_gids' ] ) ) {
        $sponsor_portal_location_gids = is_array( $_POST[ 'sponsor_portal_location_gids' ] ) 
            ? $_POST[ 'sponsor_portal_location_gids' ] 
            : explode( ',', strval( $_POST[ 'sponsor_portal_location_gids' ] ) );
        $sponsor_portal_location_gids = array_map( 'sanitize_text_field', $sponsor_portal_location_gids );
        update_post_meta( $post_id, 'sponsor_portal_location_gids', $sponsor_portal_location_gids );
    }
    if( !empty( $_POST[ 'sponsor_portal_type-manual' ] ) ) {
        update_post_meta( $post_id, 'sponsor_portal_type', sanitize_text_field( $_POST[ 'sponsor_portal_type-manual' ] ) );
    }
    elseif( !empty( $_POST[ 'sponsor_portal_type' ] ) ) {
        update_post_meta( $post_id, 'sponsor_portal_type', sanitize_text_field( $_POST[ 'sponsor_portal_type' ] ) );
    }
 
}
add_action( 'save_post', 'gh_sponsor_meta_box_save' );

/**
 * Sponsors notice in edit post page, if validation failed
 */
function gh_admin_sponsor_admin_notices(){
    if ( $message = get_transient( 'sponsor_region_id_required' ) ): ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php echo wp_kses($message, [ 
            'a' => [ 'href' => [], 'title' => [] ],
            'br' => [],
            'em' => [],
            'strong' => [],
        ]) ?></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
    </div>
    <?php 
    delete_transient( 'sponsor_region_id_required' );
    endif;
}
add_action( 'admin_notices', 'gh_admin_sponsor_admin_notices' );

/**
 * Sponsors Contextual Help Tab
 */
function gh_admin_sponsor_ctx_help() {
    
    $sponsors_field_guide = <<<'SPONSORS'
<h4>Aide memoire</h4>
<ol id="ghadmin-sponsors-field-guide">
    <li>Add full name of sponsor in "Enter Title Here".</li>
    <li>Categorise this sponsor logo (national, state or local sponsor category).</li>
    <li>Add a logo image via "Set featured image"</li>
    <li>Add a URL for the sponsor's organisation</li>
    <li>Link to the sponsor's region (if required, for state sponsorships)</li>
    <li>Link to Portal Event IDs (if required, for event sponsorships)</li>
</ol>
SPONSORS;
    
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'sponsor' ){
        $screen = get_current_screen();
        $screen->add_help_tab( array(
            'id'	=> 'sponsors_form_field_reference',
            'title'	=> __('Sponsors form fields'),
            'content'	=> $sponsors_field_guide,
        ) );        
    }
}
add_action('load-post-new.php', 'gh_admin_sponsor_ctx_help');
