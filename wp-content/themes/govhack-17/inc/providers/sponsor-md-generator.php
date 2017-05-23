<?php

//==========================================
// WordPress Options Page: Sponsor Generator
//==========================================

function gh_admin_render_sponsor_generator_page(){
?>
<div class="wrap nosubsub">
    <h1>Sponsor .md Generator</h1>
</div>


<div class="wrap">
    <?php if (defined('GH_SPONSOR_PROCESSOR_ENDPOINT')): ?>
    <h3>Sponsor Processing API</h3>
    <?php 
    // Health check on GH_SPONSOR_PROCESSOR_ENDPOINT
    $health_check = wp_remote_get( GH_SPONSOR_PROCESSOR_ENDPOINT . '/_health' );
    if (is_wp_error($health_check)){
        $traffic_light_status = 'Error';
        $traffic_light_color = 'firebrick'; 
    }
    else {
        $traffic_light_status = "{$health_check['response']['code']} {$health_check['response']['message']}";
        $traffic_light_color = $health_check['response']['code'] == 200 ? 'seagreen' : 'firebrick';        
    }
    ?>
    <p>
        Endpoint is: 
        <code><?php echo GH_SPONSOR_PROCESSOR_ENDPOINT ?></code> 
        <span style="display: inline-block; vertical-align: -.2em; height: 14px; width: 14px; background: <?php echo $traffic_light_color ?>;"></span> 
        <?php echo $traffic_light_status ?>
    </p>
    <p><strong>What does this do?</strong> It will grab the sponsors in this WordPress instance, and ship them off for conversion to Portal frontmatter markdown. In Github it'll create a new branch <code>auto/sponsors</code>, which can then be merged back into master via Pull Request.</p>
    <form action="<?php echo home_url('/wp-admin/admin-post.php')?>" method="post">
        <?php wp_nonce_field( basename( __FILE__ ), 'sponsor_crunch_init_nonce' ); ?>
        <input type="hidden" name="action" value="sponsor_crunch_init">
        <button class="button button-primary" type="submit">Generate .md files</button>
    </form>
    <?php else: ?>
    <div id="message-warning-no-endpoint" class="notice notice-warning">
        <p>No endpoint is defined for the Sponsor Processing API. Please define the endpoint at <code>GH_SPONSOR_PROCESSOR_ENDPOINT</code> in the config file.</p>
    </div>
    <?php endif;?>
    
    <?php if ( $sponsor_crunch_resp = get_transient('sponsor_crunch_resp') ): ?>
    <div id="message-success-processed" class="notice notice-success">
        <p>Sponsors processed successfully</p>
    </div>
    <h4>Response:</h4>
    <pre style="padding: 20px; background: white;"><?php echo is_array($sponsor_crunch_resp) ? print_r($sponsor_crunch_resp, true) : $sponsor_crunch_resp ?></pre>
    <?php delete_transient('sponsor_crunch_resp') ?>
    <?php endif;?>
    <?php if ( $sponsor_crunch_resp_error = get_transient('sponsor_crunch_resp_error') ): ?>
    <div id="message-error-cannot-process" class="notice notice-error">
        <p>Could not process sponsors</p>
    </div>
    <h4>Last Error Response:</h4>
    <pre style="padding: 20px; background: #fbe9e9;"><?php echo is_array($sponsor_crunch_resp_error) ? print_r($sponsor_crunch_resp_error, true) : $sponsor_crunch_resp_error ?></pre>
    <?php delete_transient('sponsor_crunch_resp_error') ?>
    <?php endif;?>
</div>
<?php    
}

function gh_admin_setup_sponsor_generator_page(){
    add_submenu_page ('edit.php?post_type=sponsor', __( 'Portal .md Generator', 'sequential'), __( 'Portal .md Generator', 'sequential'), 'manage_options', 'sponsor_generator', 'gh_admin_render_sponsor_generator_page');
}
add_action( 'admin_menu', 'gh_admin_setup_sponsor_generator_page' );


function gh_admin_sponsor_crunch_init(){
        
    if ( ! (isset( $_POST[ 'sponsor_crunch_init_nonce' ] ) && wp_verify_nonce( $_POST[ 'sponsor_crunch_init_nonce' ], basename( __FILE__ ) ) ) ) {
        return;
    }
    
    if ( ! defined('GH_SPONSOR_PROCESSOR_ENDPOINT') ){
        return;
    }
    
    // Grab all the sponsors information known to man
    $sponsors_query_args = [ 
        'post_type' => 'sponsor', 
        'order' => 'ASC',
        'posts_per_page' => -1,
        // 'tax_query' => [ [ 'taxonomy' => $tax_type, 'field' => 'slug', 'terms' => $class->slug ] ],
    ];
    $sponsors_query = get_posts($sponsors_query_args);
    
    // Mash some more data into each sponsor
    foreach ($sponsors_query as &$sponsor){
        $sponsor->image = get_the_post_thumbnail_url($sponsor->ID);
        
        $sponsor->meta = get_post_meta($sponsor->ID, null, true);
        $sponsor->meta_link_portal = get_post_meta($sponsor->ID, 'link_portal', true);
        $sponsor->meta_link_sponsor = get_post_meta($sponsor->ID, 'link_sponsor', true);
        $sponsor->meta_sponsor_portal_type = get_post_meta($sponsor->ID, 'sponsor_portal_type', true);
        
        $sponsor->national_types = wp_get_post_terms($sponsor->ID, 'national_sponsor_type');
        $sponsor->state_types = wp_get_post_terms($sponsor->ID, 'state_sponsor_type');
        $sponsor->local_types = wp_get_post_terms($sponsor->ID, 'local_sponsor_type');
        
        if ( $sponsor_region_id = get_post_meta($sponsor->ID, 'sponsor_region_id', true) ){
            if ( $sponsor_region_post = get_post( $sponsor_region_id ) ){
                $sponsor->region = $sponsor_region_post->post_name;
                $sponsor->region_title = $sponsor_region_post->post_title;
            }
        }
        
        if ( ($sponsor_location_gids = get_post_meta($sponsor->ID, 'sponsor_portal_location_gids')) && !empty($sponsor_location_gids) ){
            // $sponsor_location_gids_list = explode(',', $sponsor_location_gids);
            $sponsor_location_gids_list = $sponsor_location_gids[0];
            $sponsor->locations = $sponsor_location_gids_list;
        }

    }

    // Serialize it and ship it off to the crunching endpoint
    $resp = wp_remote_post( GH_SPONSOR_PROCESSOR_ENDPOINT, [
        'timeout' => 60,
        'headers' => [ 'Content-Type' => 'application/json' ],
        'body' => json_encode($sponsors_query),
    ]);
    
    // wp_die(json_encode($sponsors_query));
    
    if ( is_wp_error($resp) ){
        set_transient('sponsor_crunch_resp_err', json_encode($resp->get_error_messages()));
    }
    else {
        $status  = '========================================' . PHP_EOL;
        $status .= 'Response: ' . $resp['response']['code'] . ' ' . $resp['response']['message'] . PHP_EOL;
        $status .= '========================================' . PHP_EOL;
        set_transient('sponsor_crunch_resp', $status . PHP_EOL . $resp['body']);
    }
    
    // die(print_r($resp, true));
    
    wp_redirect( admin_url('edit.php?post_type=sponsor&page=sponsor_generator') );
}
add_action( 'admin_post_sponsor_crunch_init', 'gh_admin_sponsor_crunch_init' );
