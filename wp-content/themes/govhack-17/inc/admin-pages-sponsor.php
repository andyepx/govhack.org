<?php

// Note: Code for Sponsor md generator is in /inc/providers/sponsor-md-generator.php

//==================================================
// WordPress Options Page: Sponsor Rendering Options
//==================================================

// ** Notice Types **
// notice notice-success
// notice notice-warning
// notice notice-error

function gh_admin_render_sponsor_renderopts_page(){
?>
<div class="wrap nosubsub">
    <h1>Sponsor Options</h1>
</div>

<div class="wrap">
    <form action="<?php echo home_url('/wp-admin/admin-post.php')?>" method="post">
        <?php wp_nonce_field( basename( __FILE__ ), 'sponsor_opts_nonce' ); ?>

        <h3>Region parent page (index page)</h3>
        <p>Used to calculate regions for sponsors (based off sub pages)</p>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="gh-sponsor-logos-page-id">Sponsor logos page</label></th>
                    <td>
                        <?php wp_dropdown_pages([ 
                            'id' => 'gh-sponsor-logos-page-id', 
                            'name' => 'sponsor_logos_page_id', 
                            'selected' => get_option( 'gh_sponsor_logos_page_id' ), 
                            'show_option_none' => '-- N/A --',
                        ]) ?>
                        <p class="description">Choose the page which contains the sponsor logos.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="gh-region-parent-page-id">Regions index page</label></th>
                    <td>
                        <?php wp_dropdown_pages([ 
                            'id' => 'gh-region-parent-page-id', 
                            'name' => 'region_parent_page_id', 
                            'selected' => get_option( 'gh_sponsor_region_parent_page_id' ), 
                            'show_option_none' => '-- N/A --',
                        ]) ?>
                        <p class="description">Choose the parent (index) page which contains child region pages.</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <input type="hidden" name="action" value="sponsor_opts_save">
        <button class="button button-primary" type="submit">Save</button>

    </form>
    <form action="<?php echo home_url('/wp-admin/admin-post.php')?>" method="post">
        <?php wp_nonce_field( basename( __FILE__ ), 'sponsor_renderopts_nonce' ); ?>

        <h3>Sponsor Rendering options</h3>
        <p>Configures how the sponsor logos are laid out in terms of category, priority and # of columns. Data must be valid JSON object</p>
        <div>
            <textarea name="sponsor_renderopts" rows="20" style="width: 100%; max-width: 100%; min-height: 300px; font-family: monospace!important;"><?php echo get_option( 'gh_sponsor_renderopts' ) ?></textarea>
        </div>

        <input type="hidden" name="action" value="sponsor_renderopts_save">
        <button class="button button-primary" type="submit">Save</button>

    </form>
    
    <?php if ( $sponsor_renderopts_save_resp = get_transient('sponsor_renderopts_save_resp') ): ?>
    <div id="message-success-saved" class="notice notice-success">
        <p><?php echo $sponsor_renderopts_save_resp ?></p>
    </div>
    <?php delete_transient('sponsor_renderopts_save_resp') ?>
    <?php endif;?>
    <?php if ( $sponsor_renderopts_save_resp_error = get_transient('sponsor_renderopts_save_resp_error') ): ?>
    <div id="message-error-cannot-save" class="notice notice-error">
        <p><?php echo $sponsor_renderopts_save_resp_error ?></p>
    </div>
    <?php delete_transient('sponsor_renderopts_save_resp_error') ?>
    <?php endif;?>
</div>

<?php
}

function gh_admin_setup_sponsor_renderopts_page(){
    add_submenu_page ('edit.php?post_type=sponsor', __( 'Rendering Options', 'sequential'), __( 'Rendering Options', 'sequential'), 'manage_options', 'sponsor_renderopts', 'gh_admin_render_sponsor_renderopts_page');
}
add_action( 'admin_menu', 'gh_admin_setup_sponsor_renderopts_page' );

function gh_admin_register_sponsor_renderopts_settings() {
	// register_setting( 'gh_sponsor_settings', 'renderopts', 'intval' ); 
    add_option( 'gh_sponsor_renderopts', '{}' );
} 
add_action( 'admin_init', 'gh_admin_register_sponsor_renderopts_settings' );


function gh_admin_sponsor_opts_save() {
        
    if ( ! (isset( $_POST[ 'sponsor_opts_nonce' ] ) && wp_verify_nonce( $_POST[ 'sponsor_opts_nonce' ], basename( __FILE__ ) ) ) ) {
        set_transient( 'sponsor_renderopts_save_resp_error', 'Nonce check failed' );
        wp_redirect( admin_url('edit.php?post_type=sponsor&page=sponsor_renderopts') );
        return;
    }
    
    if ( isset( $_POST[ 'region_parent_page_id' ] )){
        update_option( 'gh_sponsor_region_parent_page_id', $_POST[ 'region_parent_page_id' ] );
        set_transient( 'sponsor_renderopts_save_resp', 'Sponsor options saved.' );
    }

    if ( isset( $_POST[ 'sponsor_logos_page_id' ] )){
        update_option( 'gh_sponsor_logos_page_id', $_POST[ 'sponsor_logos_page_id' ] );
        set_transient( 'sponsor_renderopts_save_resp', 'Sponsor options saved.' );
    }
    
    wp_redirect( admin_url('edit.php?post_type=sponsor&page=sponsor_renderopts') );
}
add_action( 'admin_post_sponsor_opts_save', 'gh_admin_sponsor_opts_save' );

function gh_admin_sponsor_renderopts_save() {
        
    if ( ! (isset( $_POST[ 'sponsor_renderopts_nonce' ] ) && wp_verify_nonce( $_POST[ 'sponsor_renderopts_nonce' ], basename( __FILE__ ) ) ) ) {
        set_transient( 'sponsor_renderopts_save_resp_error', 'Nonce check failed' );
        wp_redirect( admin_url('edit.php?post_type=sponsor&page=sponsor_renderopts') );
        return;
    }

    // $render_opts = str_replace(' ', '', $_POST[ 'sponsor_renderopts' ]);
    $render_opts = trim(stripslashes($_POST[ 'sponsor_renderopts' ]));
    // wp_die($render_opts);
    
    json_decode($render_opts);
    if ( json_last_error() === JSON_ERROR_NONE ){
        update_option( 'gh_sponsor_renderopts', json_encode( json_decode($render_opts), JSON_PRETTY_PRINT) );
        set_transient( 'sponsor_renderopts_save_resp', 'Rendering options saved.' );
    }
    else {
        set_transient( 'sponsor_renderopts_save_resp_error', 'Failed to save, invalid JSON. Error: ' . json_last_error_msg() );
    }
    
    wp_redirect( admin_url('edit.php?post_type=sponsor&page=sponsor_renderopts') );
}
add_action( 'admin_post_sponsor_renderopts_save', 'gh_admin_sponsor_renderopts_save' );
