<?php
//=========================================
// Profiles shortcodes
// For the GOT profile page profiles
//=========================================

/**
 * Create a profile listing out of a shortcode
 * Uses specific CSS -- see scss/partials/_profile.scss
 */
function gh_sc_profile($opts, $content = ''){
    $args = shortcode_atts( [
		'before' => '',
		'after' => '',
		'class_names' => '',
        // 'color' => '',
        // 'colour' => '',
        // 'type' => 'full',
        // 'target' => '_self',
        // 'inline' => 'false'
	], $opts);
    
    $profile_html = '';
    $classNames = [];

    // Extra classnames
    if (!empty($args['class_names'])){
        foreach (explode(' ', $args['class_names']) as $className){
            $classNames[] = $className;
        }
    }
    
    // Any before/after e.g. there might be wrappers
    if (!empty($args['before'])){
        $profile_html .= esc_html($args['before']);
    }
    
    $profile_html .= '<article class="gh-profile '. esc_attr(implode(' ', $classNames)) .'">';
    $profile_html .= do_shortcode($content);
    $profile_html .= '</article>';
    
    // Any before/after e.g. there might be wrappers
    if (!empty($args['after'])){
        $profile_html .= esc_html($args['after']);
    }
    
    return $profile_html;
    
}
if ( ! shortcode_exists('profile') ):
add_shortcode('profile', 'gh_sc_profile');
endif;