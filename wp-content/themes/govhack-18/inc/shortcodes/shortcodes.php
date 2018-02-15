<?php

// Ensure widget text gets shortcoded too:
add_filter('widget_text', 'do_shortcode');


//=========================================
// [AddEvent buttons](http://addevent.com)
//=========================================

function gh_addevent_btn($opts, $content = ''){
    if ( function_exists('addevent_button') ){
        addevent_button();
    }
}
if ( ! shortcode_exists('addevent_btn') ):
add_shortcode('addevent_btn', 'gh_addevent_btn');
endif;