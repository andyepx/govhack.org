<?php
// Custom post type for Location
// To be included in functions.php
// Todo: Move out to its own plugin

// Creates new post type for Locations
if (!function_exists('register_location_post_type')):
    function register_location_post_type()
    {

        register_taxonomy('location_type', 'product', array(
            'hierarchical' => true,
            'labels' => array(
                'name' => _x('Location Types', 'taxonomy general name'),
                'singular_name' => _x('Location Type', 'taxonomy singular name'),
                'search_items' => __('Search Location Types'),
                'all_items' => __('All Location Types'),
                'parent_item' => __('Parent Location Type'),
                'parent_item_colon' => __('Parent Location Type:'),
                'edit_item' => __('Edit Location Type'),
                'update_item' => __('Update Location Type'),
                'add_new_item' => __('Add New Location Type'),
                'new_item_name' => __('New Location Type'),
                'menu_name' => __('Location Type'),
            ),
            'rewrite' => array(
                'slug' => 'location-types', // This controls the base slug that will display before each term
                'with_front' => false, // Don't display the category base before "/locations/"
            ),
        ));

        register_post_type('locations', array(
            'labels' => array(
                'name' => __('Locations'),
                'singular_name' => __('Location'),
                'add_new_item' => __('Add new Location'),
                'edit_item' => __('Edit Location'),
                'new_item' => __('New Location'),
                'view_item' => __('View Location'),
                'all_items' => __('All Locations'),
                'search_items' => __('Search Locations'),
                'not_found' => __('No locations found'),
                'not_found_in_trash' => __('No locations found in trash'),
                'parent_item' => __('Parent Location'),
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'has_archive' => false,
            'menu_position' => 4,
            'menu_icon' => '',
            'capability_type' => 'post',
            'hierarchical' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'rewrite' => array(
                'slug' => 'locations',
                'with_front' => false
            ),
            'taxonomies' => array(
                'location_type',
                'mentor'
            )
        ));

    }
endif;

if (!function_exists('register_location_post_type_styles')):
    function register_location_post_type_styles()
    {
        $customPostType = 'locations';
        ?>
        <style>
            #adminmenu .menu-icon-<?= $customPostType ?> div.wp-menu-image:before {
                content: "\f230";
            }
        </style>
        <?php
    }
endif;

add_action('init', 'register_location_post_type');
add_action('admin_head', 'register_location_post_type_styles');

