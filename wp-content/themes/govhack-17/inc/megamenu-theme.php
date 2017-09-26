<?php

function megamenu_add_theme_default_1464429237($themes) {
    $themes["default_1464429237"] = array(
        'title' => 'Default',
        'container_background_from' => 'rgb(248, 248, 255)',
        'container_background_to' => 'rgb(245, 245, 249)',
        'container_border_radius_top_left' => '1px',
        'container_border_radius_top_right' => '1px',
        'arrow_up' => 'dash-f343',
        'arrow_down' => 'dash-f347',
        'arrow_left' => 'dash-f341',
        'arrow_right' => 'dash-f345',
        'menu_item_align' => 'right',
        'menu_item_background_from' => 'rgb(248, 248, 255)',
        'menu_item_background_to' => 'rgb(245, 245, 249)',
        'menu_item_background_hover_from' => 'rgb(51, 51, 51)',
        'menu_item_link_color' => 'rgb(51, 51, 51)',
        'menu_item_link_text_transform' => 'none',
        'menu_item_link_padding_left' => '16px',
        'menu_item_link_padding_right' => '16px',
        'menu_item_border_color' => 'rgb(255, 255, 255)',
        'menu_item_border_left' => '1px',
        'menu_item_border_right' => '1px',
        'menu_item_border_top' => '1px',
        'menu_item_border_bottom' => '1px',
        'menu_item_border_color_hover' => 'rgb(0, 0, 0)',
        'panel_background_from' => 'rgb(241, 241, 241)',
        'panel_header_border_color' => '#555',
        'panel_font_size' => '14px',
        'panel_font_color' => '#666',
        'panel_font_family' => 'inherit',
        'panel_second_level_font_color' => '#555',
        'panel_second_level_font_color_hover' => '#555',
        'panel_second_level_text_transform' => 'uppercase',
        'panel_second_level_font' => 'inherit',
        'panel_second_level_font_size' => '16px',
        'panel_second_level_font_weight' => 'bold',
        'panel_second_level_font_weight_hover' => 'bold',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_background_hover_from' => 'rgba(0,0,0,0)',
        'panel_second_level_background_hover_to' => 'rgba(0,0,0,0)',
        'panel_second_level_border_color' => '#555',
        'panel_third_level_font_color' => '#666',
        'panel_third_level_font_color_hover' => '#666',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '14px',
        'panel_third_level_background_hover_from' => 'rgba(0,0,0,0)',
        'panel_third_level_background_hover_to' => 'rgba(0,0,0,0)',
        'flyout_link_size' => '14px',
        'flyout_link_color' => '#666',
        'flyout_link_color_hover' => '#666',
        'flyout_link_family' => 'inherit',
        'flyout_link_text_transform' => 'none',
        'transitions' => 'on',
        'toggle_background_from' => '#222',
        'toggle_background_to' => '#222',
        'toggle_font_color' => 'rgb(51, 51, 51)',
        'custom_css' => '#{$wrap} #{$menu} {
        /** Custom styles should be added below this line **/
        }
        #{$wrap} {
            clear: both;
        }',
    );
    return $themes;
}
add_filter("megamenu_themes", "megamenu_add_theme_default_1464429237");