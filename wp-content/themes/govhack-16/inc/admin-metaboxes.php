<?php

//=================================
// WordPress Metaboxes
//=================================


/**
 * Add a widget to the dashboard.
 * Warns users that they are on the PRODUCTION site
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function gh_dashboard_widgets() {

    wp_add_dashboard_widget(
        'gh_blurb_dashboard_widget',         // Widget slug.
        'About this WordPress site',         // Title.
        'gh_blurb_dashboard_widget_function' // Display function.
    );

    if ( defined('GH_ENV') && strtolower(GH_ENV) === 'prod' ){
        wp_add_dashboard_widget(
            'gh_prod_warning_dashboard_widget',         // Widget slug.
            'Production Warning',         // Title.
            'gh_prod_warning_dashboard_widget_function' // Display function.
        );
    }
       
}
add_action( 'wp_dashboard_setup', 'gh_dashboard_widgets' );

/**
 * For pages using the redirection template, warn them that it will be redirected.
 */
function gh_warn_redirection_template(){
    $screen = get_current_screen();
    // echo '<pre>', $screen->id, '</pre>';
    if ( is_admin() && ($screen->id == 'page') ) {
        global $post;
        // echo '<pre>', get_page_template_slug( $post->ID ), '</pre>';
        if ( get_page_template_slug( $post->ID ) == 'page-templates/redirect.php' ){
            if ( $redirection_url = get_post_meta( $post->ID, 'gh_redirection_url', true ) ){
?>
<div id="message" class="notice notice-info">
    <p><strong>Info:</strong> This page will automatically redirect to: <code><?= $redirection_url ?></code>. To change this behaviour, select a different page template or edit the value for GH Page Redirection URL.</p>
</div>          
<?php
            }
        }
    }
}
add_action( 'admin_notices', 'gh_warn_redirection_template' );


/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function gh_prod_warning_dashboard_widget_function() {
    if ( defined('GH_ENV') && strtolower(GH_ENV) === 'prod' ): ?>
<p><img src="<?php echo get_stylesheet_directory_uri() . '/img/logo.png' ?>" style="max-height: 50px;"/></p>
<article id="production-warning" style="font-size: 1.2em;">
    <h2 style="padding: 5px; font-family: monospace; background: #e74c3c; color: white;">THIS. IS. PRODUCTION!!!</h2>
    <p>Be aware that this site is updated with database migrations i.e. the WP Migrate DB Pro plugin.</p>
    <p>For regular changes, please create in <a href="<?php echo defined('GH_STAGING_URL') ? GH_STAGING_URL : 'http://dev.govhack.org' ?>" target="_blank">the staging website</a>, then push your changes to production.</p>
    <p><strong style="color: #555;">It is recommended</strong> that you only make content changes to this website under urgent circumstances. Don't forget to also change it in the staging environment.</p>
</article>
    <?php endif;
}

function gh_blurb_dashboard_widget_function(){ ?>
<p><img src="<?php echo get_stylesheet_directory_uri() . '/img/logo.png' ?>" style="max-height: 50px;"/></p>
<article>
    <h2>Sponsors</h2>
    <ul>
        <li>Use the <span class="dashicons dashicons-id-alt"></span> Sponsors type to create a new sponsor.</li>
        <li>Each sponsor should be added to at least one national, state or event category (but at time of writing, only national and state sponsors will be displayed on the site).</li>
        <li>The text description (in the main text editing box) is optional, but will be shown on the sponsor's WordPress subpage &ndash; note that sponsor subpages are generally not navigable.</li>
        <li>Sponsors can also be exported via the Sponsor Options menu item. This requires the <code>GH_SPONSOR_PROCESSOR_ENDPOINT</code> constant to be set in wp-config.php as the URL of the processor endpoint.</li>
    </ul>
</article>
<article>
    <h2>Locations</h2>
    <ul>
        <li><span class="dashicons dashicons-location"></span> Locations are usully fed from Jabberwocky, but it is possible to configure these within WordPress as a fallback (perhaps in event of disaster).</li>
        <li>The <code>GH_LOCATION_PROVIDER_METHOD</code> constant found in wp-config.php is set to <code>'portal'</code> to set retrieval from Jabberwocky. Otherwise it defaults back to using WordPress locations.<li>
    </ul>
</article>
<article>
    <h2>Homepage Tiles</h2>
    <ul>
        <li><span class="dashicons dashicons-screenoptions"></span> Tiles are displayed on the homepage. To set the order of display, alter the post dates, so that the first tile is chronologically the earliest.</li>
        <li>The tile editing screen comes with a number of options to define the tile caption, tile color, tile image, etc.<li>
        <li>Tiles can be deactivated by seting their status to something other than Published.<li>
    </ul>
</article>
<article>
    <h2>Header Tab Blocks and Region Tiles</h2>
    <ul>
        <li><span class="dashicons dashicons-screenoptions"></span> Tile type can be configured by choosing a different Tile category.</li>
        <li>Header tiles will show up as blocks to the right of the header logo.</li>
        <li>Region tiles are used on the regions index page template, and also on 404 pages.</li>
    </ul>
</article>
<article>
    <h2>Mega menu</h2>
    <ul>
        <li>To change the items within the mega menu, go to Appearance &gt; Menus and select the menu titled 'Universal menu'.<li>
    </ul>
</article>
<?php
}

