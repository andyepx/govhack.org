<?php

// Ensure widget text gets shortcoded too:
add_filter('widget_text', 'do_shortcode');

//
// Shortcodes
//
function gh_sc_button($opts, $content = ''){
    $args = shortcode_atts( [
		'href' => '/',
        'color' => '',
        'colour' => '',
        'type' => 'full',
        'target' => '_self',
        'inline' => 'false'
	], $opts);
    
    $classNames = [];

    // Parse whether this is a full button or minimal button. Defaults to full.
    if (in_array($args['type'], ['min', 'minimal'])){
        $classNames[] = 'button-minimal';
    }
    else {
        $classNames[] = 'button';
    }

    // Parse the button color
    // Just in case, parse both the `color` and `colour` parameters
    $color = current(array_filter([$args['colour'], $args['color'], 'blue']));
    switch ($color){
        case 'pink':
            $classNames[] = 'gh-pink';
            break;
        case 'blue':
            $classNames[] = 'gh-blue';
            break;
    }
    
    $button_html = '<a class="' . implode(' ', $classNames) .'" href="'. esc_url($args['href']) .'" target="'.$args['target'].'">';
    $button_html .= $content;
    if ($args['target'] == '_blank'){
        $button_html .= ' <span class="fa fa-external-link" aria-hidden="true"></span>';
    }
    $button_html .= '</a>';
    if ($args['inline'] !== 'false'){
        return $button_html;
    }
    else {
        return '<div class="content-wrapper">' . $button_html . '</div>';
    }
}
if ( ! shortcode_exists('button') ):
add_shortcode('button', 'gh_sc_button');
endif;


function gh_sc_inline_logo($opts, $content = ''){
    return gh_get_inline_logo();
}
if ( ! shortcode_exists('logo') ):
add_shortcode('logo', 'gh_sc_inline_logo');
endif;

function gh_sc_wip($opts, $content = ''){
    echo '<blockquote class="wip-alert">';
    echo '[Work in progress!] ' . $content;
    echo '</blockquote>';
}
if ( ! shortcode_exists('wip') ):
add_shortcode('wip', 'gh_sc_wip');
endif;


function gh_sc_ext($opts, $content = ''){
    echo ' <span class="fa fa fa-external-link" aria-hidden="true"></span> ';
}
if ( ! shortcode_exists('ext') ):
add_shortcode('ext', 'gh_sc_ext');
endif;




// function nc_locations_button($opts) {
    // $args = shortcode_atts( array(
		// 'caption' => 'View locations',
        // 'wrapped' => 'true',
	// ), $opts);
    // $query = new WP_Query( [ 'pagename' => 'about/locations' ] );          // The bookings page slug is "about/locations"
    // if ( $query->have_posts($query) ){
        // $query->the_post();     // Only pop the first post
        // $button = '<a class="nc-button nc-locations-button" href="' . get_the_permalink() . '">' . $args['caption'] . '</a>';
    // }
    // else {
        // $button = '<span class="nc-locations-unavailable">Locations page unavailable</span>';
    // }
    // wp_reset_query();
    // return $args['wrapped'] == 'true' ? '<div class="nc-button-wrapper">' . $button . '</div>' : $button;
// }
// add_shortcode('locations-button', 'nc_locations_button');




