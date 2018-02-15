<?php
//==========================
// Locations - stuff that locations page
// needs in order to function
// @AY
//==========================

function gh_get_location_parser(){
    global $gh_location_parser;
    if ( ! isset($gh_location_parser) ) {
//        print_r(__DIR__);
        require_once __DIR__ . '/providers/class-location-parser.php';
        $gh_location_parser = new GH_Location_Parser();
    }
    return $gh_location_parser;
}

function gh_get_location_pin_url($type = 'official', $themeSubpath = '/img'){
    $path = get_stylesheet_directory_uri();
    if ('official' === $type){
        return $path . $themeSubpath . '/redpin.png';
    }
    else {
        return $path . $themeSubpath . '/bluepin.png';
    }
}

function gh_location_pin_url($type = 'official'){
    echo gh_get_location_pin_url($type);
}
