<?php
/**
 * Facilitate some open graph tags
 * @AY
 */
 
/**
 * Homepage
 */
function gh_opengraph_homepage() {
    if ( is_home() ){ 
?>
<meta property="og:title" content="GovHack: 28-30 July 2017" />
<meta property="og:type" content="website" />
<meta property="og:description" content="Designer? Hacker? Scientist? Producer? GovHack is back, pre-loaded with open data, across over 40 locations in Australia and NZ. This is a non-profit event proudly run by volunteers, and made possible by our sponsors." />
<meta property="og:url" content="<?php echo home_url('/') ?>" />
<meta property="og:image" content="<?php echo home_url('/wp-content/themes/govhack-17/img/govhack-opengraph.jpg') ?>" />
<meta property="og:image:width" content="600" />
<meta property="og:image:height" content="400" />
<?php
    }
}
add_action('wp_head', 'gh_opengraph_homepage');


/**
 * Marketing landing pages
 */
function gh_opengraph_landingpage() {
    if ( is_page_template('page-templates/marketing-landing.php') ){ 
?>
<meta property="og:image" content="<?php echo home_url('/wp-content/themes/govhack-17/img/govhack-opengraph.jpg') ?>" />
<meta property="og:image:width" content="600" />
<meta property="og:image:height" content="400" />
<?php
    }
}
add_action('wp_head', 'gh_opengraph_landingpage');