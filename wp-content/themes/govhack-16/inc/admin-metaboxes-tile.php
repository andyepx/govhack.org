<?php

/**
 * Homepage tiles meta box
 */

// tile actions metabox
function gh_tile_add_meta_boxes() {
    add_meta_box(
        'gh-tile-actions-meta',      // Unique ID
        '<span class="dashicons dashicons-screenoptions"></span> ' . esc_html__( 'Tile Actions', 'example' ),    // Title
        'gh_tile_actions_meta_box',   // Callback function
        'tiles'         // Admin page (or post type)
    );
    add_meta_box(
        'gh-tile-display-meta',      // Unique ID
        '<span class="dashicons dashicons-screenoptions"></span> ' . esc_html__( 'Tile Display', 'example' ),    // Title
        'gh_tile_display_meta_box',   // Callback function
        'tiles'         // Admin page (or post type)
    );
}
add_action( 'add_meta_boxes', 'gh_tile_add_meta_boxes' );

function gh_tile_actions_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_tile_nonce' ); ?>
    <fieldset id="gh-tile-settings-1">
        <strong><label for="gh-tile-settings-linked-page-id"><?php _e( "Linked page", 'sequential' ); ?></label> <code>linked_page_id</code></strong>
        <p><?php wp_dropdown_pages([ 'id' => 'gh-tile-settings-linked-page-id', 'class' => 'widefat', 'name' => 'linked_page_id', 'selected' => get_post_meta( $object->ID, 'linked_page_id', true ), 'show_option_none' => '-- None --' ]) ?></p>
        <!-- <strong><label for="gh-tile-settings-linked-page-slug"><?php _e( "Linked page slug (fallback only)", 'sequential' ); ?></label> <code>linked_page_slug</code></strong>
        <p><input class="widefat" type="text" name="linked_page_slug" id="gh-tile-settings-linked-page-slug" value="<?php echo esc_attr( get_post_meta( $object->ID, 'linked_page_slug', true ) ); ?>" size="30" /></p> -->
        <p><strong>Featherlight Lightbox</strong> [<a href="https://github.com/noelboss/featherlight#examples" target="_blank">?</a>]</p>
        <p>
            <label for="gh-tile-settings-use-featherlight">
                <input type="checkbox" name="use_featherlight_iframe" id="gh-tile-settings-use-featherlight" value="yes" <?php checked( get_post_meta( $object->ID, 'use_featherlight_iframe', true ), 'yes' ); ?>/> 
                Display in Featherlight iframe lightbox?
            </label>
        </p> 
    </fieldset>
<?php
}

function gh_tile_display_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_tile_nonce' ); ?>
    <div style="font-style: italic;">
        To change the tile background image, upload a 'Featured Image', then style the tile using the <code style="font-style: normal;">Tile extra css</code> field.
    </div>
    <fieldset id="gh-tile-settings-2">
        <p><strong><label for="gh-tile-settings-color"><?php _e( "Tile extra classnames", 'sequential' ); ?></label></strong> <code>tile_color</code></p>
        <p>
            <input type="text" name="tile_color" id="gh-tile-settings-color" value="<?php echo esc_attr( get_post_meta( $object->ID, 'tile_color', true ) ); ?>" style="width: 50%;"/>
            <span style="width: 40%; float: right; text-align: right;">
                Or pick a preset: <select id="gh-tile-settings-color-chooser">
                    <option value="">-- Pick one --</option>
                    <option value="pink"><code>pink</code></option>
                    <option value="blue"><code>blue</code></option>
                    <option value="white-bordered"><code>white-bordered</code></option>
                    <option value="grey"><code>grey</code></option>
                    <option value="lightgrey"><code>lightgrey</code></option>
                    <option value="darkgrey"><code>darkgrey</code></option>
                </select>
            </span>
            <script>
            (function($){
                $(function(){
                    $('#gh-tile-settings-color-chooser').change(function(){
                        $('#gh-tile-settings-color').val($(this).val());
                    });
                });
            }(jQuery));
            </script>
        </p>
        <p><strong><label for="gh-tile-settings-css"><?php _e( "Tile extra css", 'sequential' ); ?></label> <code>tile_css</code></strong> e.g. <code>color: black; background: red;</code></p>
        <p><textarea class="widefat" type="text" name="tile_css" id="gh-location-meta-latlong" rows="3"><?php echo esc_attr( get_post_meta( $object->ID, 'tile_css', true ) ); ?></textarea></p>
    </fieldset>
<?php
}

function gh_tile_meta_box_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'gh_tile_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_tile_nonce' ], basename( __FILE__ ) ) );
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'linked_page_id' ] ) ) {
        update_post_meta( $post_id, 'linked_page_id', sanitize_text_field( $_POST[ 'linked_page_id' ] ) );
    }
    if( isset( $_POST[ 'linked_page_slug' ] ) ) {
        update_post_meta( $post_id, 'linked_page_slug', sanitize_text_field( $_POST[ 'linked_page_slug' ] ) );
    }
    if( isset( $_POST[ 'link_externally' ] ) ) {
        update_post_meta( $post_id, 'link_externally', sanitize_text_field( $_POST[ 'link_externally' ] ) );
    }
    if( isset( $_POST[ 'tile_color' ] ) ) {
        update_post_meta( $post_id, 'tile_color', sanitize_text_field( $_POST[ 'tile_color' ] ) );
    }
    if( isset( $_POST[ 'tile_css' ] ) ) {
        update_post_meta( $post_id, 'tile_css', sanitize_text_field( $_POST[ 'tile_css' ] ) );
    }
    if( isset( $_POST[ 'use_featherlight' ] ) ) {
        update_post_meta( $post_id, 'use_featherlight', sanitize_text_field( $_POST[ 'use_featherlight' ] ) );
    }    
    if( isset( $_POST[ 'use_featherlight_iframe' ] ) ) {
        update_post_meta( $post_id, 'use_featherlight_iframe', sanitize_text_field( $_POST[ 'use_featherlight_iframe' ] ) );
    }
    if( isset( $_POST[ 'use_featherlight_value' ] ) ) {
        update_post_meta( $post_id, 'use_featherlight_value', sanitize_text_field( $_POST[ 'use_featherlight_value' ] ) );
    }
    
}
add_action( 'save_post', 'gh_tile_meta_box_save' );




/*
 * Announcement Tile metabox
 */
function gh_ann_tile_add_meta_boxes() {
    add_meta_box(
        'gh-ann-tile-meta',      // Unique ID
        '<span class="dashicons dashicons-screenoptions"></span> ' . esc_html__( 'Announcement Tile contents', 'example' ),    // Title
        'gh_ann_tile_meta_box',   // Callback function
        'tiles'         // Admin page (or post type)
    );
}
add_action( 'add_meta_boxes', 'gh_ann_tile_add_meta_boxes' );

function gh_ann_tile_meta_box( $object ){
    wp_nonce_field( basename( __FILE__ ), 'gh_ann_tile_nonce' ); ?>
    <fieldset id="gh-tile-settings-announcement-1">
        <p><label for="ghtilesettingsannouncement"><em>Add the content that would display in an announcement tile.</em></label></p>
        <?php 
        $tile_announcement_content = get_post_meta( $object->ID, 'tile_announcement_content', true );
        wp_editor($tile_announcement_content, 'ghtilesettingsannouncement', array(
            'wpautop' => false,
            'media_buttons' => true,
            'textarea_name' => 'tile_announcement_content',
            'textarea_rows' => 3,
            'teeny' => true,
        )); 
        ?>
    </fieldset>
    <fieldset id="gh-tile-settings-announcement-2">
        <code>Todo: checkbox to make it act like a tile rather than a banner</code>
        <?php //  get_post_meta( $object->ID, 'tile_announcement_content', true );
        // <input type="checkbox" name="meta-checkbox" id="meta-checkbox" value="yes" <?php if ( isset ( $prfx_stored_meta['meta-checkbox'] ) ) checked( $prfx_stored_meta['meta-checkbox'][0], 'yes' ); ?>
    </fieldset>
<?php    
}

function gh_ann_tile_meta_box_save( $post_id ) {
 
    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'gh_ann_tile_nonce' ] ) && wp_verify_nonce( $_POST[ 'gh_ann_tile_nonce' ], basename( __FILE__ ) ) );
 
    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
 
    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'tile_announcement_content' ] ) ) {
        $kses_allow = [
            'a' => [
                'href' => [],
                'target' => [],
                'title' => [],
            ],
            'br' => [],
            'em' => [],
            'strong' => [],
        ];
        update_post_meta( $post_id, 'tile_announcement_content', wp_kses( $_POST[ 'tile_announcement_content' ], $kses_allow ) );
    }

}
add_action( 'save_post', 'gh_ann_tile_meta_box_save' );


