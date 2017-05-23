<?php

//===================================================
// WordPress Options Page: Homepage rendering options
//===================================================

function gh_admin_render_homepage_opts_page(){
?>
<div class="wrap nosubsub">
    <h1>Homepage Options</h1>
</div>

<div class="wrap">
    <form action="<?php echo home_url('/wp-admin/admin-post.php')?>" method="post">
        <?php wp_nonce_field( basename( __FILE__ ), 'homepage_opts_nonce' ); ?>

        <h3>Homepage state awards definitions</h3>
        <p>Configure the state awards layout in the State Awards homepage hero unit.</p>
        <div>
            <textarea name="homepage_stateawards_config" rows="20" style="width: 100%; max-width: 100%; min-height: 300px; font-family: monospace!important;">
                <?php echo get_option( 'gh_homepage_stateawards_config' ) ?>
            </textarea>
        </div>

        <input type="hidden" name="action" value="homepage_opts_save">
        <button class="button button-primary" type="submit">Save</button>

    </form>
    
    <?php if ( $homepage_opts_save_resp = get_transient('homepage_opts_save_resp') ): ?>
    <div id="message-success-saved" class="notice notice-success">
        <p><?php echo $homepage_opts_save_resp ?></p>
    </div>
    <?php delete_transient('homepage_opts_save_resp') ?>
    <?php endif;?>
    <?php if ( $homepage_opts_save_resp_error = get_transient('homepage_opts_save_resp_error') ): ?>
    <div id="message-error-cannot-save" class="notice notice-error">
        <p><?php echo $homepage_opts_save_resp_error ?></p>
    </div>
    <?php delete_transient('homepage_opts_save_resp_error') ?>
    <?php endif;?>
</div>

<?php
}

function gh_admin_setup_homepage_opts_page(){
    add_menu_page (__( 'Homepage Options', 'sequential'), __( 'Homepage', 'sequential'), 'manage_options', 'homepage_opts', 'gh_admin_render_homepage_opts_page', 'dashicons-admin-home', '99');
}
add_action( 'admin_menu', 'gh_admin_setup_homepage_opts_page' );

function gh_admin_homepage_opts_save() {
        
    if ( ! (isset( $_POST[ 'homepage_opts_nonce' ] ) && wp_verify_nonce( $_POST[ 'homepage_opts_nonce' ], basename( __FILE__ ) ) ) ) {
        set_transient( 'homepage_opts_save_resp_error', 'Nonce check failed' );
        wp_redirect( admin_url('admin.php?page=homepage_opts') );     // TODO
        return;
    }
    
    if ( isset( $_POST[ 'homepage_stateawards_config' ] )){

        $stateawards_config = trim(stripslashes($_POST[ 'homepage_stateawards_config' ]));
        
        json_decode($stateawards_config);
        if ( json_last_error() === JSON_ERROR_NONE ){
            update_option( 'gh_homepage_stateawards_config', json_encode( json_decode($stateawards_config), JSON_PRETTY_PRINT) );
            set_transient( 'homepage_opts_save_resp', 'Sponsor options saved.' );
        }
        else {
            set_transient( 'homepage_opts_save_resp_error', 'Failed to save, invalid JSON. Error: ' . json_last_error_msg() );
        }

    }
    
    wp_redirect( admin_url('admin.php?page=homepage_opts') );     // TODO
}
add_action( 'admin_post_homepage_opts_save', 'gh_admin_homepage_opts_save' );
