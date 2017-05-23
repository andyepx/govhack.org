<?php

/**
 * Page meta boxes
 */
function gh_page_add_meta_boxes() {
    add_meta_box(
        'gh-page-hero-meta',      // Unique ID
        '<span class="dashicons dashicons-welcome-add-page"></span>' . esc_html__( 'GH page hero unit', 'example' ),    // Title
        'gh_page_hero_meta_box',   // Callback function
        'page'         // Admin page (or post type)
    );
    add_meta_box(
        'gh-page-redirect-meta',      // Unique ID
        '<span class="dashicons dashicons-welcome-add-page"></span>' . esc_html__( 'GH page redirect', 'example' ),    // Title
        'gh_page_redirect_meta_box',   // Callback function
        'page'         // Admin page (or post type)
    );
    add_meta_box(
        'gh-page-show-sponsors-meta',      // Unique ID
        '<span class="dashicons dashicons-welcome-add-page"></span>' . esc_html__( 'Show GH sponsors', 'example' ),    // Title
        'gh_page_show_sponsors_meta_box',   // Callback function
        'page'         // Admin page (or post type)
    );
}
add_action( 'add_meta_boxes', 'gh_page_add_meta_boxes' );

/**
 * Page meta box - Render for Hero Unit
 */
function gh_page_hero_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_page_hero_nonce' ); ?>
    <fieldset style="border: 1px solid gainsboro; padding: 10px;">
        <legend style="padding: 5px 10px; border: 1px solid gainsboro;">
            <span class="dashicons dashicons-format-quote"></span>
            <strong>Hero fields</strong> 
            <em>Displayed on certain page templates</em>
        </legend>

        <label for="gh-page-meta-hero-subtitle"><?php _e( "Hero unit subtitle", 'sequential' ); ?></label>
        <p><input class="widefat" type="text" name="hero_subtitle" id="gh-page-meta-hero-subtitle" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hero_subtitle', true ) ); ?>"/></p>
        
        <label for="gh-page-meta-hero-cta-label"><?php _e( "Hero unit CTA label", 'sequential' ); ?></label>
        <p><input class="widefat" type="text" name="hero_cta_label" id="gh-page-meta-hero-cta-label" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hero_cta_label', true ) ); ?>"/></p>
        
        <label for="gh-page-meta-hero-cta-url"><?php _e( "Hero unit CTA URL", 'sequential' ); ?></label>
        <p><input class="widefat" type="text" name="hero_cta_url" id="gh-page-meta-hero-cta-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hero_cta_url', true ) ); ?>"/></p>
                
        <label for="gh-page-meta-hero-cta-targetblank">
            <input type="checkbox" name="hero_cta_targetblank" id="gh-page-meta-hero-cta-targetblank" value="yes" <?php checked( get_post_meta( $object->ID, 'hero_cta_targetblank', true ), 'yes' ); ?>/>
            <?php _e( "Open in new tab?", 'sequential' );  ?>
        </label>

    </fieldset>
<?php 
}


/**
 * Page meta box - Render for Show Sponsors
 */
function gh_page_show_sponsors_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_page_showsponsors_nonce' ); 
    $region_parent_page_id = gh_get_region_parent_page_id(); ?>
    <p><em>Note: Only certain page templates will render sponsors.</em></p>
    <p><label for="gh-page-meta-show-sponsors-regional">
        <input type="checkbox" name="gh_show_sponsors_regional" id="gh-page-meta-show-sponsors-regional" value="yes" <?php checked( get_post_meta( $object->ID, 'gh_show_sponsors_regional', true ), 'yes' ); ?>/>
        <?php _e( "Show regional sponsors?", 'sequential' );  ?>
    </label></p>
    <div id="gh-page-meta-show-sponsors-for-region-id-container">
        <label for="gh-page-meta-show-sponsors-for-region-id"><?php _e( "Select the region", '' ); ?></label>
        <p><?php wp_dropdown_pages([ 'id' => 'gh-page-meta-show-sponsors-for-region-id', 'class' => 'widefat', 'name' => 'gh_show_sponsors_for_region_id', 'child_of' => $region_parent_page_id, 'selected' => get_post_meta( $object->ID, 'gh_show_sponsors_for_region_id', true ), 'show_option_none' => '-- N/A --' ]) ?></p>
    </div>
    <hr>
    <p><label for="gh-page-meta-show-sponsors-national">
        <input type="checkbox" name="gh_show_sponsors_national" id="gh-page-meta-show-sponsors-national" value="yes" <?php checked( get_post_meta( $object->ID, 'gh_show_sponsors_national', true ), 'yes' ); ?>/>
        <?php _e( "Show national sponsors?", 'sequential' );  ?>
    </label></p>
    <script>
    (function(){
        var showSponsorsRegionalCheckbox = document.querySelector('#gh-page-meta-show-sponsors-regional');
        toggleRegionIdContainer.call(showSponsorsRegionalCheckbox);
        showSponsorsRegionalCheckbox.addEventListener('click', toggleRegionIdContainer);
        function toggleRegionIdContainer(){
            document.querySelector('#gh-page-meta-show-sponsors-for-region-id-container').style.display = ( this.checked ? 'block' : 'none' );
        }
    }());
    </script>
<?php 
}


/**
 * Page meta box - Render for Redirect
 */
function gh_page_redirect_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_page_redirect_nonce' ); ?>
    <fieldset style="border: 1px solid gainsboro; padding: 10px;">
        <legend style="padding: 5px 10px; border: 1px solid gainsboro;">
            <span class="dashicons dashicons-migrate"></span>
            <strong>Redirection</strong> 
            <em>Set this page template to <strong>Redirection Page</strong> then&hellip;</em>
        </legend>
        <label for="gh-redirection-url"><?php _e( "URL", 'sequential' ); ?></label>
        <p><input class="widefat" type="text" name="gh_redirection_url" id="gh-redirection-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'gh_redirection_url', true ) ); ?>"/></p>
        <label for="gh-redirection-perm">
            <input type="checkbox" name="gh_redirect_is_perm" id="gh-redirection-perm" value="yes" <?php checked( get_post_meta( $object->ID, 'gh_redirect_is_perm', true ), 'yes' ); ?>/>
            <?php _e( "Permanent (301)?", 'sequential' );  ?>
        </label>
    </fieldset>
<?php 
}


/**
 * Page save
 */
function gh_page_meta_box_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce_hero = ( isset( $_POST[ 'gh_page_hero_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_page_hero_nonce' ], basename( __FILE__ ) ) );
    $is_valid_nonce_redirect = ( isset( $_POST[ 'gh_page_redirect_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_page_redirect_nonce' ], basename( __FILE__ ) ) );
    $is_valid_nonce_showsponsors = ( isset( $_POST[ 'gh_page_showsponsors_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_page_showsponsors_nonce' ], basename( __FILE__ ) ) );
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !( $is_valid_nonce_redirect || $is_valid_nonce_hero || $is_valid_nonce_showsponsors ) ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    // Hero unit
    if ( isset( $_POST[ 'hero_subtitle' ] ) ) {
        update_post_meta( $post_id, 'hero_subtitle', sanitize_text_field( $_POST[ 'hero_subtitle' ] ) );
    }
    if ( isset( $_POST[ 'hero_cta_label' ] ) ) {
        update_post_meta( $post_id, 'hero_cta_label', sanitize_text_field( $_POST[ 'hero_cta_label' ] ) );
    }
    if ( isset( $_POST[ 'hero_cta_url' ] ) ) {
        update_post_meta( $post_id, 'hero_cta_url', sanitize_text_field( $_POST[ 'hero_cta_url' ] ) );
    }
    if ( isset( $_POST[ 'hero_cta_targetblank' ] ) ) {
        update_post_meta( $post_id, 'hero_cta_targetblank', sanitize_text_field( $_POST[ 'hero_cta_targetblank' ] ) );
    }
    else {
        delete_post_meta( $post_id, 'hero_cta_targetblank' );
    }
    
    // Redirection
    if ( isset( $_POST[ 'gh_redirection_url' ] ) ) {
        update_post_meta( $post_id, 'gh_redirection_url', sanitize_text_field( $_POST[ 'gh_redirection_url' ] ) );
    }
    if ( isset( $_POST[ 'gh_redirect_is_perm' ] ) ) {
        update_post_meta( $post_id, 'gh_redirect_is_perm', sanitize_text_field( $_POST[ 'gh_redirect_is_perm' ] ) );
    }
    else {
        delete_post_meta( $post_id, 'gh_redirect_is_perm' );
    }
    
    // Show sponsors
    if ( isset( $_POST[ 'gh_show_sponsors_for_region_id' ] ) ) {
        update_post_meta( $post_id, 'gh_show_sponsors_for_region_id', sanitize_text_field( $_POST[ 'gh_show_sponsors_for_region_id' ] ) );
    }
    if ( isset( $_POST[ 'gh_show_sponsors_regional' ] ) ) {
        update_post_meta( $post_id, 'gh_show_sponsors_regional', sanitize_text_field( $_POST[ 'gh_show_sponsors_regional' ] ) );
    }
    else {
        delete_post_meta( $post_id, 'gh_show_sponsors_regional' );
    }
    if ( isset( $_POST[ 'gh_show_sponsors_national' ] ) ) {
        update_post_meta( $post_id, 'gh_show_sponsors_national', sanitize_text_field( $_POST[ 'gh_show_sponsors_national' ] ) );
    }
    else {
        delete_post_meta( $post_id, 'gh_show_sponsors_national' );
    }
 
 
}
add_action( 'save_post', 'gh_page_meta_box_save' );
