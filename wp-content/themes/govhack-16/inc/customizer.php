<?php
/**
 * ==================================
 * GovHack 16 customizer
 * ==================================
 *
 * @package Sequential
 */

 
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function gh_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    
    
	/* Show Tagline */
	// $wp_customize->add_setting( 'sequential_tagline', array(
		// 'default'           => '',
		// 'sanitize_callback' => 'sequential_sanitize_checkbox',
		// 'transport'         => 'postMessage',
	// ) );
	// $wp_customize->add_control( 'sequential_tagline', array(
		// 'label'             => esc_html__( 'Display tagline below site title', 'sequential' ),
		// 'section'           => 'gh_theme_options',
		// 'priority'          => 10,
		// 'type'              => 'checkbox',
		// 'active_callback'   => 'sequential_is_header_text_shown',
	// ) );
    
    
    if ( function_exists('gh_customize_banner_register') ){
        gh_customize_banner_register($wp_customize, 10);        
    }
    if ( function_exists('gh_customize_blurb_register') ){
        gh_customize_blurb_register($wp_customize, 11);        
    }
    if ( function_exists('gh_customize_social_register') ){
        gh_customize_social_register($wp_customize, 12);
    }
    if ( function_exists('gh_customize_tiles_register') ){
        gh_customize_tiles_register($wp_customize, 13);        
    }

}

function gh_customize_banner_register( $wp_customize, $priority = 10 ){
    
    /* Theme Options */
	$wp_customize->add_section( 'gh_banner_options', array(
		'title'             => esc_html__( 'GH Home Page Banner', 'sequential' ),
		'priority'          => 10,
	) );
    
    /* Show Alt banner */
	// $wp_customize->add_setting( 'gh_tile_show_alt_banner', array(
		// 'default'           => '',
	// ) );
	// $wp_customize->add_control( 'gh_tile_show_alt_banner', array(
		// 'label'             => esc_html__( 'Show alternate banner', 'sequential' ),
		// 'section'           => 'gh_banner_options',
		// 'priority'          => 20,
		// 'type'              => 'checkbox'
	// ) );
    
    // Choose a banner template
    // NOTE OCT 2016: Renamed `banner` to `hero`
    $dirname = 'hero';
    $banner_partials = glob(__DIR__ . "/../$dirname/*");
    $banner_choices = [0 => '-- Use the default --'];
    foreach ($banner_partials as $partial_path){
        $partial_filename_ext = basename($partial_path);
        $partial_filename = pathinfo($partial_filename_ext, PATHINFO_FILENAME);
        $partial_filename_parts = explode('-', $partial_filename);
        $banner_choices["$dirname/$partial_filename"] = implode(', ', [array_shift($partial_filename_parts), implode('-', $partial_filename_parts)]);
    }
	$wp_customize->add_setting( 'gh_chosen_banner_template', array(
		'default'           => '',
	) );
	$wp_customize->add_control( 'gh_chosen_banner_template', array(
		'label'             => esc_html__( 'Choose a banner template', 'sequential' ),
		'section'           => 'gh_banner_options',
		'priority'          => 20,
		'type'              => 'select',
        'choices'           => $banner_choices,
		'description'       => "Files must be created under <code>'$dirname'</code>",
	) );
    
    
    /* Date tagline */
	$wp_customize->add_setting( 'gh_date_heading_content', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'gh_date_heading_content', array(
		'label'             => esc_html__( 'Event date heading', 'sequential' ),
		'section'           => 'gh_banner_options',
		'priority'          => 30,
    ) );

	/* Banner message */
	$wp_customize->add_setting( 'gh_banner_content', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'gh_banner_content', array(
		'label'             => esc_html__( 'Banner text', 'sequential' ),
		'section'           => 'gh_banner_options',
		'priority'          => 40,
		'type'              => 'textarea',
		'description'     => 'The big white words, if blank, uses default.',
	) );
    
    // Banner buttons 
    $gh_banner_num_buttons = 2;
    for ($i = 1; $i <= $gh_banner_num_buttons; $i++){

        // Banner button text
        $wp_customize->add_setting( 'gh_banner_button_'.$i.'_label', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( 'gh_banner_button_'.$i.'_label', array(
            'label'             => esc_html__( "Banner button $i text", 'sequential' ),
            'section'           => 'gh_banner_options',
            'priority'          => 40 + (10 * $i),
            'type'              => 'textarea',
        ) );

        // Toggle to display as naked button (no <a> tags)
        $wp_customize->add_setting( 'gh_banner_button_'.$i.'_naked', array(
            'default'           => '',
        ) );
        $wp_customize->add_control( 'gh_banner_button_'.$i.'_naked', array(
            'label'             => esc_html__( 'Render without link markup', 'sequential' ),
            'section'           => 'gh_banner_options',
            'priority'          => 40 + (10 * $i + 1),
            'type'              => 'checkbox',
            'description'       => 'Disables the <code>&lt;a&gt;</code>link fields below.',
        ) );

        // Banner button link 
        $wp_customize->add_setting( 'gh_banner_button_'.$i.'_link', array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post',
    		'transport'         => 'postMessage',
        ) );
        $wp_customize->add_control( 'gh_banner_button_'.$i.'_link', array(
            'label'             => esc_html__( "Banner button $i link", 'sequential' ),
            'section'           => 'gh_banner_options',
            'priority'          => 40 + (10 * $i + 2),
            'type'              => 'text',
            // 'description'           => 'Takes priority over page',
        ) );
        
        // Banner button page (page is only used as fallback if no link selected)
        $wp_customize->add_setting( 'gh_banner_button_'.$i.'_page', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
        ) );
        $wp_customize->add_control( 'gh_banner_button_'.$i.'_page', array(
            'label'             => esc_html__( "Banner button $i page (fallback)", 'sequential' ),
            'section'           => 'gh_banner_options',
            'priority'          => 40 + (10 * $i + 3),
            'type'              => 'dropdown-pages',
            'description'       => 'Only used if no link is provided',
        ) );
        
    }
    
}

function gh_customize_blurb_register( $wp_customize, $priority = 10 ){
    
    /* Theme Options */
	$wp_customize->add_section( 'gh_blurb_options', array(
		'title'             => esc_html__( 'GH Home Page About', 'sequential' ),
		'priority'          => $priority,
	) );
    
    /* Show Home Page About */
	$wp_customize->add_setting( 'gh_tile_show_blurb', array(
		'default'           => '',
	) );
	$wp_customize->add_control( 'gh_tile_show_blurb', array(
		'label'             => esc_html__( 'Show About section', 'sequential' ),
		'section'           => 'gh_blurb_options',
		'priority'          => 30,
		'type'              => 'checkbox'
	) );
    
    /* What's GovHack Title */
	$wp_customize->add_setting( 'gh_blurb_title', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'gh_blurb_title', array(
		'label'             => esc_html__( 'Blurb title', 'sequential' ),
		'section'           => 'gh_blurb_options',
		'priority'          => 50,
		'type'              => 'text',
	) );
    
    /* What's GovHack Content */
	$wp_customize->add_setting( 'gh_blurb_content', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'gh_blurb_content', array(
		'label'             => esc_html__( 'Blurb description', 'sequential' ),
		'section'           => 'gh_blurb_options',
		'priority'          => 55,
		'type'              => 'textarea',
	) );
    
    /* Video embed caption */
	$wp_customize->add_setting( 'gh_blurb_video_caption', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'gh_blurb_video_caption', array(
		'label'             => esc_html__( 'Video caption', 'sequential' ),
		'section'           => 'gh_blurb_options',
		'priority'          => 59,
		'type'              => 'text',
	) );
    
    /* Video embed URL */
	$wp_customize->add_setting( 'gh_blurb_video_embed_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( 'gh_blurb_video_embed_url', array(
		'label'             => esc_html__( 'Video embed URL', 'sequential' ),
		'section'           => 'gh_blurb_options',
		'priority'          => 58,
		'type'              => 'text',
	) );
}


function gh_customize_social_register( $wp_customize, $priority = 10 ){
    
    /* Theme Options */
	$wp_customize->add_section( 'gh_social_options', array(
		'title'             => esc_html__( 'GH Home Page Social', 'sequential' ),
		'priority'          => $priority,
	) );
    
    /* Twitter */
	$wp_customize->add_setting( 'gh_social_twitter', array(
		'default'           => 'https://twitter.com/',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( 'gh_social_twitter', array(
		'label'             => esc_html__( 'Twitter URL', 'sequential' ),
		'section'           => 'gh_social_options',
		'priority'          => 50,
		'type'              => 'text',
	) );
    
    /* Facebook */
	$wp_customize->add_setting( 'gh_social_facebook', array(
		'default'           => 'https://www.facebook.com',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( 'gh_social_facebook', array(
		'label'             => esc_html__( 'Facebook URL', 'sequential' ),
		'section'           => 'gh_social_options',
		'priority'          => 55,
		'type'              => 'text',
	) );
    
    /* Slack */
	$wp_customize->add_setting( 'gh_social_slack', array(
		'default'           => 'https://slack.com/',
		'sanitize_callback' => 'esc_url',
	) );
	$wp_customize->add_control( 'gh_social_slack', array(
		'label'             => esc_html__( 'Slack URL', 'sequential' ),
		'section'           => 'gh_social_options',
		'priority'          => 60,
		'type'              => 'text',
	) );
    
}

function gh_customize_tiles_register( $wp_customize, $priority = 10 ){
    
    /* Theme Options */
	$wp_customize->add_section( 'gh_tiles_options', array(
		'title'             => esc_html__( 'GH Tiles options', 'sequential' ),
		'priority'          => $priority,
	) );

    /* Show last year notice */
	$wp_customize->add_setting( 'gh_tile_show_lastyear_notice', array(
		'default'           => '',
	) );
	$wp_customize->add_control( 'gh_tile_show_lastyear_notice', array(
		'label'             => esc_html__( 'Show last year notice', 'sequential' ),
		'section'           => 'gh_tiles_options',
		'priority'          => 30,
		'type'              => 'checkbox'
	) );
    
    /* Show announcements */
	$wp_customize->add_setting( 'gh_tile_show_announcements', array(
		'default'           => '',
	) );
	$wp_customize->add_control( 'gh_tile_show_announcements', array(
		'label'             => esc_html__( 'Show announcements', 'sequential' ),
		'section'           => 'gh_tiles_options',
		'priority'          => 30,
		'type'              => 'checkbox'
	) );
    
    /* Show registration quick links */
	$wp_customize->add_setting( 'gh_tile_show_registration_links', array(
		'default'           => '',
	) );
	$wp_customize->add_control( 'gh_tile_show_registration_links', array(
		'label'             => esc_html__( 'Show registration quick links', 'sequential' ),
		'section'           => 'gh_tiles_options',
		'priority'          => 30,
		'type'              => 'checkbox'
	) );
    
    /* Tile column count */
	$wp_customize->add_setting( 'gh_tile_columns', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'gh_tile_columns', array(
		'label'             => esc_html__( 'Tile columns', 'sequential' ),
		'section'           => 'gh_tiles_options',
		'priority'          => 40,
		'type'              => 'select',
        'choices'        => ['2'=>'2','3'=>'3','4'=>'4','6'=>'6']
	) );
    
}


// Kill off the old one
remove_action( 'customize_register', 'sequential_customize_register' );     // doesnt work.. probably due to loading priority
add_action( 'customize_register', 'gh_customize_register' );