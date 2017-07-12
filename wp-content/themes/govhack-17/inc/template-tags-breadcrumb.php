<?php
/**
 * Breadcrumbs grew out so large, they get their own file
 * @package GH16
 */

// Breadcrumb hook

function gh_breadcrumb($opts = [])
{
    do_action('gh_breadcrumb', $opts);
}

// A wrapper for Simple Breadcrumb 
function gh_simple_breadcrumb($opts = [])
{

    $classNames = [];
    $wrapped = false;
    if (is_array($opts)) {
        if (isset($opts['wrapped'])) {
            $wrapped = ( bool )$opts['wrapped'];
            if (is_array($opts['classNames'])) {
                $classNames = implode(' ', $opts['classNames']);
            } else {
                $classNames = $opts['classNames'];
            }
        }
    } elseif (is_bool($opts)) {
        $wrapped = $opts;
    }

    $classNames[] = 'breadcrumb-wrapper';
    if ($wrapped) $classNames[] = 'wrapped';

    if (function_exists('simple_breadcrumb')) {
        echo simple_breadcrumb($classNames);
    }
}

add_action('gh_breadcrumb', 'gh_simple_breadcrumb');

/*
// Kindly customized from::
// Plugin Name: Really Simple Breadcrumb
// Plugin URI: http://www.christophweil.de
// Description: This is a really simple WP Plugin which lets you use Breadcrumbs for Pages!
// Version: 1.0.2
// Author: Christoph Weil
// Author URI: http://www.christophweil.de
// Update Server: 
// Min WP Version: 3.2.1
// Max WP Version: 
*/

if (!function_exists('simple_breadcrumb')):
    function simple_breadcrumb($classNames = [], $sep = '&raquo;')
    {
        global $post;
        $separator = " $sep "; // Simply change the separator to what ever you need e.g. / or >

        $string = '<div class="breadcrumb ' . implode(' ', $classNames) . '">';
        if (!is_front_page()) {

            $home_link = get_bloginfo('name');

            // Prefer to use a home icon
            if (class_exists('Better_Font_Awesome_Library') && wp_style_is(Better_Font_Awesome_Library::SLUG . '-font-awesome', 'done')) {
                $home_link = '<span class="fa fa-home" aria-hidden="true"></span>';
            }

            if (is_search()) {
                $string .= $home_link . $separator;
            } else {
                $string .= '<a href="' . get_option('home') . '">';
                $string .= $home_link;
                $string .= "</a> " . $separator;
            }

            if (is_category() || is_single()) {
                the_category(', ');
                if (is_single()) {
                    $string .= $separator;
                    $string .= get_the_title();
                }
            } elseif ((is_page() || is_search()) && $post->post_parent) {
                $home = get_page(get_option('page_on_front'));
                for ($i = count($post->ancestors) - 1; $i >= 0; $i--) {
                    if (($home->ID) != ($post->ancestors[$i])) {
                        if (is_search()) {
                            $string .= get_the_title($post->ancestors[$i]) . $separator;
                        } else {
                            $meta = get_post_meta($post->ancestors[$i], 'handbook_section', true);
                            if (empty($meta)) {
                                $string .= '<a href="' . get_permalink($post->ancestors[$i]) . '">';
                                $string .= get_the_title($post->ancestors[$i]);
                                $string .= "</a>" . $separator;
                            }
                        }
                    }
                }
                $string .= get_the_title();
            } elseif (is_page() || is_search()) {
                $string .= get_the_title();
            } elseif (is_404()) {
                $string .= "404";
            }
        } else {
            $string .= get_bloginfo('name');
        }
        $string .= '</div>';
        return $string;
    }
endif;