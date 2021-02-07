<?php
/**
 * Customizer settings for this theme.
 */

if ( ! class_exists( 'Twenty_Twenty_One_Customize' ) ) {
	class Twenty_Twenty_One_Customize {
		public function __construct() {
			add_action( 'customize_register', array( $this, 'register' ) );
		}
		public function register( $wp_customize ) {

			$colors_section = $wp_customize->get_section( 'colors' );
			// if ( is_object( $colors_section ) ) {
				$colors_section->title = __( 'Colours & Dark Mode', 'govhack.org' );
			// }

			// Change site-title & description to postMessage.
			$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage'; // @phpstan-ignore-line. Assume that this setting exists.
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage'; // @phpstan-ignore-line. Assume that this setting exists.

			// Add partial for blogname.
			$wp_customize->selective_refresh->add_partial(
				'blogname',
				array(
					'selector'        => '.site-title',
					'render_callback' => array( $this, 'partial_blogname' ),
				)
			);

			// Add partial for blogdescription.
			$wp_customize->selective_refresh->add_partial(
				'blogdescription',
				array(
					'selector'        => '.site-description',
					'render_callback' => array( $this, 'partial_blogdescription' ),
				)
			);

			// Add "display_title_and_tagline" setting for displaying the site-title & tagline.
			$wp_customize->add_setting(
				'display_title_and_tagline',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => true,
					'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
				)
			);

			// Add control for the "display_title_and_tagline" setting.
			$wp_customize->add_control(
				'display_title_and_tagline',
				array(
					'type'    => 'checkbox',
					'section' => 'title_tagline',
					'label'   => esc_html__( 'Display Site Title & Tagline', 'govhack.org' ),
				)
			);

			/**
			 * Add excerpt or full text selector to customizer
			 */
			$wp_customize->add_section(
				'excerpt_settings',
				array(
					'title'    => esc_html__( 'Excerpt Settings', 'govhack.org' ),
					'priority' => 120,
				)
			);

			$wp_customize->add_setting(
				'display_excerpt_or_full_post',
				array(
					'capability'        => 'edit_theme_options',
					'default'           => 'excerpt',
					'sanitize_callback' => function( $value ) {
						return 'excerpt' === $value || 'full' === $value ? $value : 'excerpt';
					},
				)
			);

			$wp_customize->add_control(
				'display_excerpt_or_full_post',
				array(
					'type'    => 'radio',
					'section' => 'excerpt_settings',
					'label'   => esc_html__( 'On Archive Pages, posts show:', 'govhack.org' ),
					'choices' => array(
						'excerpt' => esc_html__( 'Summary', 'govhack.org' ),
						'full'    => esc_html__( 'Full text', 'govhack.org' ),
					),
				)
			);

			// Background color.
			// Include the custom control class.
			include_once get_template_directory() . '/classes/class-twenty-twenty-one-customize-color-control.php' ; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Register the custom control.
			$wp_customize->register_control_type( 'Twenty_Twenty_One_Customize_Color_Control' );

			// Get the palette from theme-supports.
			$palette = get_theme_support( 'editor-color-palette' );

			// Build the colors array from theme-support.
			$colors = array();
			if ( isset( $palette[0] ) && is_array( $palette[0] ) ) {
				foreach ( $palette[0] as $palette_color ) {
					$colors[] = $palette_color['color'];
				}
			}

			// Add the control. Overrides the default background-color control.
			$wp_customize->add_control(
				new Twenty_Twenty_One_Customize_Color_Control(
					$wp_customize,
					'background_color',
					array(
						'label'   => esc_html_x( 'Background colour', 'Customizer control', 'govhack.org' ),
						'section' => 'colors',
						'palette' => $colors
					)
				)
			);

			$wp_customize->add_setting( 'primary_color_one' , array(
 				'default'   => '#ca1e56',
 				'transport' => 'refresh',
			) );

			$wp_customize->add_control( 
				new Twenty_Twenty_One_Customize_Color_Control( 
					$wp_customize, 
					'primary_color_one', 
					array(
						'label'      => esc_html_x( 'Primary Colour One', 'Customizer control', 'govhack.org' ),
						'section'    => 'colors',
						'palette'    => $colors
					)
				) 
			);


			$wp_customize->add_setting( 'primary_color_two' , array(
 				'default'   => '#4c9ad2',
 				'transport' => 'refresh',
			) );

			$wp_customize->add_control(
				new Twenty_Twenty_One_Customize_Color_Control(
					$wp_customize,
					'primary_color_two',
					array(
						'label'      => esc_html_x( 'Primary Colour Two', 'Customizer control', 'govhack.org' ),
						'section'    => 'colors',
						'palette'    => $colors
					)
				)
			);

			$wp_customize->add_setting( 'primary_color_shade_one' , array(
 				'default'   => '#9c1040',
 				'transport' => 'refresh',
			) );

			$wp_customize->add_control(
				new Twenty_Twenty_One_Customize_Color_Control(
					$wp_customize,
					'primary_color_shade_one',
					array(
						'label'      => esc_html_x( 'Contrast Primary Colour Shade One', 'Customizer control', 'govhack.org' ),
						'section'    => 'colors',
						'palette'    => $colors
					)
				)
			);

			$wp_customize->add_setting( 'primary_color_shade_two' , array(
 				'default'   => '#3c7cab',
 				'transport' => 'refresh',
			) );

			$wp_customize->add_control(
				new Twenty_Twenty_One_Customize_Color_Control(
					$wp_customize,
					'primary_color_shade_two',
					array(
						'label'      => esc_html_x( 'Contrast Primary Colour Shade Two', 'Customizer control', 'govhack.org' ),
						'section'    => 'colors',
						'palette'    => $colors
					)
				)
			);

			$wp_customize->add_section( 
				'branding', 
				array(
					'title'      => __( 'Branding', 'govhack.org' ),
					'priority'   => 1,
				)
			);

			$wp_customize->add_setting( 
				'link_color' , 
				array(
		       			'default'   => '#000000',
        				'transport' => 'refresh',
				) 
			);

			$wp_customize->add_control( 
				new Twenty_Twenty_One_Customize_Color_Control( 
					$wp_customize, 
					'link_color', 
					array(
						'label'      => __( 'Placeholder Color', 'govhack.org' ),
						'section'    => 'branding',
						'settings'   => 'link_color',
					) 
				) 
			);


		}
		public static function sanitize_checkbox( $checked = null ) {
			return (bool) isset( $checked ) && true === $checked;
		
		}

		public function partial_blogname() {
			bloginfo( 'name' );
		}

		public function partial_blogdescription() {
			bloginfo( 'description' );
		}
	}
}

?>
