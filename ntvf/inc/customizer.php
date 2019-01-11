<?php
/**
 * Morrison Hotel Theme Customizer.
 *
 * @package Morrison_Hotel
 */

/**
 * Setup the Theme Customizer settings and controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function morrison_hotel_customize_register( $wp_customize ) {
	/**
	 * Custom class for saving media data in an array. Only supports the 'theme_mod' type.
	 *
	 * @author     Justin Tadlock <justin@justintadlock.com>
	 * @copyright  Copyright (c) 2015, Justin Tadlock
	 * @link       http://themehybrid.com/hybrid-core
	 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
	 */
	class JT_Customize_Setting_Image_Data extends WP_Customize_Setting {

		/**
		* Overwrites the `update()` method so we can save some extra data.
		*/
		protected function update( $value ) {

			if ( $value ) {

				$post_id = attachment_url_to_postid( $value );

				if ( $post_id ) {

					$image = wp_get_attachment_image_src( $post_id, 'full' );

					if ( $image ) {

						/* Set up a custom array of data to save. */
						$data = array(
							'url'    => esc_url_raw( $image[0] ),
							'width'  => absint( $image[1] ),
							'height' => absint( $image[2] ),
							'id'     => absint( $post_id )
						);

						set_theme_mod( "{$this->id_data[ 'base' ]}_data", $data );
					}
				}
			}

			/* No media? Remove the data mod. */
			if ( empty( $value ) || empty( $post_id ) || empty( $image ) ) {
				remove_theme_mod( "{$this->id_data[ 'base' ]}_data" );
			}

			/* Let's send this back up and let the parent class do its thing. */
			return parent::update( $value );
		}
	}

	/*--------------------------------------------------------------
	Logo & Favicon
	--------------------------------------------------------------*/

	$wp_customize->add_section(
		'morrison_hotel_new_section_logo_favicon',
		array(
			'title'      => esc_html__( 'Logo', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 50
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			new JT_Customize_Setting_Image_Data(
				$wp_customize,
				'morrison_hotel_opt_logo',
				array(
					'sanitize_callback' => 'esc_url_raw'
				)
			)
		);

		$wp_customize->add_setting(
			new JT_Customize_Setting_Image_Data(
				$wp_customize,
				'morrison_hotel_opt_logo_retina',
				array(
					'sanitize_callback' => 'esc_url_raw'
				)
			)
		);

		if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {

			$wp_customize->add_setting(
				'morrison_hotel_opt_favicon',
				array(
					'sanitize_callback' => 'esc_url_raw'
				)
			);

		}

		/*---- controls ----*/

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'desktop_logo',
				array(
					'label'       => esc_html__( 'Upload Logo', 'morrison-hotel' ),
					'section'     => 'morrison_hotel_new_section_logo_favicon',
					'settings'    => 'morrison_hotel_opt_logo',
					'priority'	  => 1
				)
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'retina_logo',
				array(
					'label'       => esc_html__( 'Upload Logo (retina)', 'morrison-hotel' ),
					'description' => esc_html__( 'Upload a double-sized logo for retina displays.', 'morrison-hotel' ),
					'section'     => 'morrison_hotel_new_section_logo_favicon',
					'settings'    => 'morrison_hotel_opt_logo_retina',
					'priority'	  => 2
				)
			)
		);

		if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) {

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'favicon',
					array(
						'label'       => esc_html__( 'Upload Favicon', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_logo_favicon',
						'settings'    => 'morrison_hotel_opt_favicon',
						'priority'	  => 3
					)
				)
			);

		}

	/*--------------------------------------------------------------
	Header Settings
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_header',
		array(
			'title'      => esc_html__( 'Header Settings', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 51
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_opt_hide_top_header',
			array(
				'sanitize_callback' => 'morrison_hotel_sanitize_checkbox'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_site_info',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_header_layout',
			array(
				'default'           => 'default',
				'sanitize_callback' => 'morrison_hotel_sanitize_header_layout'
			)
		);

		/*---- controls ----*/

		$wp_customize->add_control(
			'hide_top_header',
			array(
				'label'       => esc_html__( 'Hide top header section (entirely)', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_header',
				'settings'    => 'morrison_hotel_opt_hide_top_header',
				'type'        => 'checkbox',
				'priority'	  => 1
			)
		);

		$wp_customize->add_control(
			'site_info',
			array(
				'label'       => esc_html__( 'Site info (leave empty to disable):', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_header',
				'settings'    => 'morrison_hotel_opt_site_info',
				'type'        => 'text',
				'priority'	  => 2
			)
		);

		$wp_customize->add_control(
			'header_layout',
			array(
				'label'       => esc_html__( 'Header layout', 'morrison-hotel' ),
				'description' => esc_html__( 'Select the layout of the header', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_header',
				'settings'    => 'morrison_hotel_opt_header_layout',
				'priority'    => 3,
				'type'        => 'select',
				'choices'     => array(
					'default'       => esc_html__( 'Center aligned', 'morrison-hotel' ),
					'left-aligned'  => esc_html__( 'Left aligned', 'morrison-hotel' ),
					'right-aligned' => esc_html__( 'Right aligned', 'morrison-hotel' ),
				)
			)
		);

	/*--------------------------------------------------------------
	Sidebar Settings
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_sidebar',
		array(
			'title'      => esc_html__( 'Sidebar Settings', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 52
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_opt_blog_layout',
			array(
				'default'           => 'left-sidebar',
				'sanitize_callback' => 'morrison_hotel_sanitize_layout'
			)
		);

		if ( morrison_hotel_is_hotelier_active() ) {

			$wp_customize->add_setting(
				'morrison_hotel_opt_booking_layout',
				array(
					'default'           => 'left-sidebar',
					'sanitize_callback' => 'morrison_hotel_sanitize_layout'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_rooms_layout',
				array(
					'default'           => 'no-sidebar',
					'sanitize_callback' => 'morrison_hotel_sanitize_layout'
				)
			);

		}

		/*---- controls ----*/

		$wp_customize->add_control(
			'blog_layout',
			array(
				'label'       => esc_html__( 'Blog sidebar', 'morrison-hotel' ),
				'description' => esc_html__( 'Select the layout of the blog page', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_sidebar',
				'settings'    => 'morrison_hotel_opt_blog_layout',
				'priority'    => 1,
				'type'        => 'radio',
				'choices'     => array(
					'right-sidebar' => esc_html__( 'Right sidebar', 'morrison-hotel' ),
					'left-sidebar'  => esc_html__( 'Left sidebar', 'morrison-hotel' ),
					'no-sidebar'    => esc_html__( 'No sidebar', 'morrison-hotel' )
				)
			)
		);

		if ( morrison_hotel_is_hotelier_active() ) {

			$wp_customize->add_control(
				'booking_layout',
				array(
					'label'       => esc_html__( 'Booking pages sidebar', 'morrison-hotel' ),
					'description' => esc_html__( 'Select the layout of the booking pages', 'morrison-hotel' ),
					'section'     => 'morrison_hotel_new_section_sidebar',
					'settings'    => 'morrison_hotel_opt_booking_layout',
					'priority'    => 2,
					'type'        => 'radio',
					'choices'     => array(
						'right-sidebar' => esc_html__( 'Right sidebar', 'morrison-hotel' ),
						'left-sidebar'  => esc_html__( 'Left sidebar', 'morrison-hotel' ),
						'no-sidebar'    => esc_html__( 'No sidebar', 'morrison-hotel' )
					)
				)
			);

			$wp_customize->add_control(
				'rooms_layout',
				array(
					'label'       => esc_html__( 'Room sidebar', 'morrison-hotel' ),
					'description' => esc_html__( 'Select the layout of the single room page', 'morrison-hotel' ),
					'section'     => 'morrison_hotel_new_section_sidebar',
					'settings'    => 'morrison_hotel_opt_rooms_layout',
					'priority'    => 3,
					'type'        => 'radio',
					'choices'     => array(
						'right-sidebar' => esc_html__( 'Right sidebar', 'morrison-hotel' ),
						'left-sidebar'  => esc_html__( 'Left sidebar', 'morrison-hotel' ),
						'no-sidebar'    => esc_html__( 'No sidebar', 'morrison-hotel' )
					)
				)
			);

		}

	/*--------------------------------------------------------------
	Fonts
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_fonts',
		array(
			'title'      => esc_html__( 'Fonts', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 53
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_primary_font',
			array(
				'default'           => 'Lora',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_headings_font',
			array(
				'default'           => 'Montserrat',
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		/*---- controls ----*/

		$google_fonts = array(
			'Abel' => 'Abel',
			'Arimo' => 'Arimo',
			'Arvo' => 'Arvo',
			'Asap' => 'Asap',
			'Bitter' => 'Bitter',
			'Bree Serif' => 'Bree Serif',
			'Cabin Condensed' => 'Cabin Condensed',
			'Cabin' => 'Cabin',
			'Chivo' => 'Chivo',
			'Cuprum' => 'Cuprum',
			'Dosis' => 'Dosis',
			'Droid Sans' => 'Droid Sans',
			'Droid Serif' => 'Droid Serif',
			'Exo' => 'Exo',
			'Francois+One' => 'Francois One',
			'Inconsolata' => 'Inconsolata',
			'Josefin Sans' => 'Josefin Sans',
			'Karla' => 'Karla',
			'Lato' => 'Lato',
			'Lora' => 'Lora',
			'Maven Pro' => 'Maven Pro',
			'Merriweather' => 'Merriweather',
			'Montserrat' => 'Montserrat',
			'Mr De Haviland' => 'Mr De Haviland',
			'Muli' => 'Muli',
			'Nunito' => 'Nunito',
			'Open Sans Condensed' => 'Open Sans Condensed',
			'Open Sans' => 'Open Sans',
			'Oswald' => 'Oswald',
			'Playfair Display' => 'Playfair Display',
			'PT Sans Narrow' => 'PT Sans Narrow',
			'PT Sans' => 'PT Sans',
			'PT Serif Caption' => 'PT Serif Caption',
			'PT Serif' => 'PT Serif',
			'Questrial' => 'Questrial',
			'Quicksand' => 'Quicksand',
			'Raleway' => 'Raleway',
			'Roboto' => 'Roboto',
			'Roboto Condensed' => 'Roboto Condensed',
			'Roboto Slab' => 'Roboto Slab',
			'Rokkitt' => 'Rokkitt',
			'Signika' => 'Signika',
			'Slabo' => 'Slabo',
			'Source Sans Pro' => 'Source Sans Pro',
			'Ubuntu Condensed' => 'Ubuntu Condensed',
			'Ubuntu' => 'Ubuntu',
			'Varela Round' => 'Varela Round',
			'Vollkorn' => 'Vollkorn'
		);

		$wp_customize->add_control(
			'primary_font',
			array(
				'label'       => esc_html__( 'Primary font', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_fonts',
				'settings'    => 'morrison_hotel_primary_font',
				'priority'	  => 1,
				'type'        => 'select',
				'choices'     => $google_fonts
			)
		);

		$wp_customize->add_control(
			'headings_font',
			array(
				'label'       => esc_html__( 'Headings font', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_fonts',
				'settings'    => 'morrison_hotel_headings_font',
				'priority'	  => 2,
				'type'        => 'select',
				'choices'     => $google_fonts
			)
		);

	/*--------------------------------------------------------------
	Colors
	--------------------------------------------------------------*/

	/*---- panels ----*/

	$wp_customize->add_panel( 'morrison_hotel_panel_colors', array(
		'priority'       => 54,
		'capability'     => 'edit_theme_options',
		'theme_supports' => '',
		'title'          => esc_html__( 'Colors', 'morrison-hotel' ),
		'description'    => '',
	) );

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_accent_colors',
			array(
			'title'      => esc_html__( 'Accent colors', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 1,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_primary_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'primary_color',
					array(
						'label'       => esc_html__( 'Primary color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_accent_colors',
						'settings'    => 'morrison_hotel_opt_primary_color',
						'priority'	  => 1
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_top_header_colors',
			array(
			'title'      => esc_html__( 'Top header', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 2,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_top_header_background_color',
				array(
					'default'           => '#323536',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_top_header_text_color',
				array(
					'default'           => '#999999',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_top_header_link_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_top_header_link_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'top_header_background_color',
					array(
						'label'       => esc_html__( 'Background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_top_header_colors',
						'settings'    => 'morrison_hotel_opt_top_header_background_color',
						'priority'	  => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'top_header_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_top_header_colors',
						'settings'    => 'morrison_hotel_opt_top_header_text_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'top_header_link_color',
					array(
						'label'       => esc_html__( 'Link color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_top_header_colors',
						'settings'    => 'morrison_hotel_opt_top_header_link_color',
						'priority'	  => 3
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'top_header_link_hover_color',
					array(
						'label'       => esc_html__( 'Link color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_top_header_colors',
						'settings'    => 'morrison_hotel_opt_top_header_link_hover_color',
						'priority'	  => 4
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_header_colors',
			array(
			'title'      => esc_html__( 'Header', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 3,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_header_background_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_header_site_title_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_header_site_title_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_background_color',
					array(
						'label'       => esc_html__( 'Background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_header_colors',
						'settings'    => 'morrison_hotel_opt_header_background_color',
						'priority'	  => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_site_title_color',
					array(
						'label'       => esc_html__( 'Site title color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_header_colors',
						'settings'    => 'morrison_hotel_opt_header_site_title_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'header_site_title_hover_color',
					array(
						'label'       => esc_html__( 'Site title color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_header_colors',
						'settings'    => 'morrison_hotel_opt_header_site_title_hover_color',
						'priority'	  => 3
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_mobile_menu_colors',
			array(
			'title'      => esc_html__( 'Off-canvas menu', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 4,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_mobile_menu_background_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_mobile_menu_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_mobile_menu_text_hover_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'mobile_menu_background_color',
					array(
						'label'       => esc_html__( 'Background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_mobile_menu_colors',
						'settings'    => 'morrison_hotel_opt_mobile_menu_background_color',
						'priority'	  => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'mobile_menu_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_mobile_menu_colors',
						'settings'    => 'morrison_hotel_opt_mobile_menu_text_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'mobile_menu_text_hover_color',
					array(
						'label'       => esc_html__( 'Text color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_mobile_menu_colors',
						'settings'    => 'morrison_hotel_opt_mobile_menu_text_hover_color',
						'priority'	  => 3
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_menu_colors',
			array(
			'title'      => esc_html__( 'Menu', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 5,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_text_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_submenu_background_color',
				array(
					'default'           => '#323536',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_submenu_text_color',
				array(
					'default'           => '#999999',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_submenu_text_hover_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_menu_submenu_background_hover_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'menu_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_text_color',
						'priority'	  => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'menu_text_hover_color',
					array(
						'label'       => esc_html__( 'Text color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_text_hover_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'submenu_background_color',
					array(
						'label'       => esc_html__( 'Submenu background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_submenu_background_color',
						'priority'	  => 3
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'submenu_text_color',
					array(
						'label'       => esc_html__( 'Submenu text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_submenu_text_color',
						'priority'	  => 4
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'submenu_text_hover_color',
					array(
						'label'       => esc_html__( 'Submenu text color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_submenu_text_hover_color',
						'priority'	  => 5
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'submenu_background_hover_color',
					array(
						'label'       => esc_html__( 'Submenu text background color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_menu_colors',
						'settings'    => 'morrison_hotel_opt_menu_submenu_background_hover_color',
						'priority'	  => 6
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_footer_colors',
			array(
			'title'      => esc_html__( 'Footer', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 6,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_background_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_widgets_background_color',
				array(
					'default'           => '#323536',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_text_color',
				array(
					'default'           => '#999999',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_link_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_link_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_headings_color',
				array(
					'default'           => '#999999',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_inputs_text_color',
				array(
					'default'           => '#999999',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_inputs_background_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_inputs_border_color',
				array(
					'default'           => '#383b3c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_footer_inputs_border_focus_color',
				array(
					'default'           => '#454849',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_background_color',
					array(
						'label'       => esc_html__( 'Background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_background_color',
						'priority'	  => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_widgets_background_color',
					array(
						'label'       => esc_html__( 'Background color (widgets area)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_widgets_background_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_text_color',
						'priority'	  => 3
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_link_color',
					array(
						'label'       => esc_html__( 'Link color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_link_color',
						'priority'	  => 4
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_link_hover_color',
					array(
						'label'       => esc_html__( 'Link color (hover)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_link_hover_color',
						'priority'	  => 5
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_headings_color',
					array(
						'label'       => esc_html__( 'Headings color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_headings_color',
						'priority'	  => 6
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_inputs_text_color',
					array(
						'label'       => esc_html__( 'Inputs text color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_inputs_text_color',
						'priority'	  => 7
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_inputs_background_color',
					array(
						'label'       => esc_html__( 'Inputs background color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_inputs_background_color',
						'priority'	  => 8
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_inputs_border_color',
					array(
						'label'       => esc_html__( 'Inputs border color', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_inputs_border_color',
						'priority'	  => 9
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'footer_inputs_border_focus_color',
					array(
						'label'       => esc_html__( 'Inputs border color (focus)', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_footer_colors',
						'settings'    => 'morrison_hotel_opt_footer_inputs_border_focus_color',
						'priority'	  => 10
					)
				)
			);

		/*---- sections ----*/

		$wp_customize->add_section( 'morrison_hotel_new_section_button_colors',
			array(
			'title'      => esc_html__( 'Buttons', 'morrison-hotel' ),
			'description'=> '',
			'priority'   => 7,
			'panel'  => 'morrison_hotel_panel_colors',
		) );

			/*---- settings ----*/

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_border_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_text_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_border_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_background_hover_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_text_hover_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_alt_background_color',
				array(
					'default'           => '#c19c78',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_alt_text_color',
				array(
					'default'           => '#ffffff',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_alt_background_hover_color',
				array(
					'default'           => '#ebebeb',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			$wp_customize->add_setting(
				'morrison_hotel_opt_button_alt_text_hover_color',
				array(
					'default'           => '#292b2c',
					'sanitize_callback' => 'sanitize_hex_color'
				)
			);

			/*---- controls ----*/

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_border_color',
					array(
						'label'       => esc_html__( 'Border color', 'morrison-hotel' ),
						'description' => esc_html__( 'Default button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_border_color',
						'priority'    => 1
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'description' => esc_html__( 'Default button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_text_color',
						'priority'	  => 2
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_border_hover_color',
					array(
						'label'       => esc_html__( 'Border color (hover)', 'morrison-hotel' ),
						'description' => esc_html__( 'Default button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_border_hover_color',
						'priority'	  => 3
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_background_hover_color',
					array(
						'label'       => esc_html__( 'Background color (hover)', 'morrison-hotel' ),
						'description' => esc_html__( 'Default button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_background_hover_color',
						'priority'	  => 4
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_text_hover_color',
					array(
						'label'       => esc_html__( 'Text color (hover)', 'morrison-hotel' ),
						'description' => esc_html__( 'Default button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_text_hover_color',
						'priority'	  => 5
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_alt_background_color',
					array(
						'label'       => esc_html__( 'Background color', 'morrison-hotel' ),
						'description' => esc_html__( 'Alt button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_alt_background_color',
						'priority'    => 6
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_alt_text_color',
					array(
						'label'       => esc_html__( 'Text color', 'morrison-hotel' ),
						'description' => esc_html__( 'Alt button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_alt_text_color',
						'priority'    => 7
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_alt_background_hover_color',
					array(
						'label'       => esc_html__( 'Background color (hover)', 'morrison-hotel' ),
						'description' => esc_html__( 'Alt button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_alt_background_hover_color',
						'priority'    => 8
					)
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'button_alt_text_hover_color',
					array(
						'label'       => esc_html__( 'Text color (hover)', 'morrison-hotel' ),
						'description' => esc_html__( 'Alt button', 'morrison-hotel' ),
						'section'     => 'morrison_hotel_new_section_button_colors',
						'settings'    => 'morrison_hotel_opt_button_alt_text_hover_color',
						'priority'    => 9
					)
				)
			);

	/*--------------------------------------------------------------
	Social Header
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_social_header',
		array(
		'title'      => esc_html__( 'Social Header', 'morrison-hotel' ),
		'description'=> '',
		'priority'   => 55
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_opt_show_social',
			array(
				'sanitize_callback' => 'morrison_hotel_sanitize_checkbox'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_check_social_target',
			array(
				'sanitize_callback' => 'morrison_hotel_sanitize_checkbox'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_social_header_label',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_facebook',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_twitter',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_dribbble',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_linkedin',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_flickr',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_tumblr',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_vimeo',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_youtube',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_instagram',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_google',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_foursquare',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_github',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_pinterest',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_stackoverflow',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_deviantart',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_behance',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_delicious',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_soundcloud',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_spotify',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_stumbleupon',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_reddit',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_vine',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_digg',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_vk',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_yelp',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_medium',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_slack',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_tripadvisor',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_setting(
			'morrison_hotel_opt_rss',
			array(
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		/*---- controls ----*/

		$wp_customize->add_control(
			'social_header',
			array(
				'label'       => esc_html__( 'Show social header', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_show_social',
				'type'        => 'checkbox',
				'priority'	  => 1
			)
		);

		$wp_customize->add_control(
			'social_header_label',
			array(
				'label'       => esc_html__( 'Social header label (optional):', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_social_header_label',
				'type'        => 'text',
				'priority'	  => 2
			)
		);

		$wp_customize->add_control(
			'social_header_target',
			array(
				'label'       => esc_html__( 'Open social links in a new window/tab', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_check_social_target',
				'type'        => 'checkbox',
				'priority'	  => 3
			)
		);

		$wp_customize->add_control(
			'facebook',
			array(
				'label'       => esc_html__( 'Facebook URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_facebook',
				'type'        => 'text',
				'priority'	  => 4
			)
		);

		$wp_customize->add_control(
			'twitter',
			array(
				'label'       => esc_html__( 'Twitter URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_twitter',
				'type'        => 'text',
				'priority'	  => 5
			)
		);

		$wp_customize->add_control(
			'dribbble',
			array(
				'label'       => esc_html__( 'Dribbble URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_dribbble',
				'type'        => 'text',
				'priority'	  => 6
			)
		);

		$wp_customize->add_control(
			'linkedin',
			array(
				'label'       => esc_html__( 'LinkedIn URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_linkedin',
				'type'        => 'text',
				'priority'	  => 7
			)
		);

		$wp_customize->add_control(
			'flickr',
			array(
				'label'       => esc_html__( 'Flickr URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_flickr',
				'type'        => 'text',
				'priority'	  => 8
			)
		);

		$wp_customize->add_control(
			'tumblr',
			array(
				'label'       => esc_html__( 'Tumblr URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_tumblr',
				'type'        => 'text',
				'priority'	  => 9
			)
		);

		$wp_customize->add_control(
			'vimeo',
			array(
				'label'       => esc_html__( 'Vimeo URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_vimeo',
				'type'        => 'text',
				'priority'	  => 10
			)
		);

		$wp_customize->add_control(
			'youtube',
			array(
				'label'       => esc_html__( 'Youtube URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_youtube',
				'type'        => 'text',
				'priority'	  => 11
			)
		);

		$wp_customize->add_control(
			'instagram',
			array(
				'label'       => esc_html__( 'Instagram URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_instagram',
				'type'        => 'text',
				'priority'	  => 12
			)
		);

		$wp_customize->add_control(
			'google',
			array(
				'label'       => esc_html__( 'Google Plus URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_google',
				'type'        => 'text',
				'priority'	  => 13
			)
		);

		$wp_customize->add_control(
			'foursquare',
			array(
				'label'       => esc_html__( 'Foursquare URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_foursquare',
				'type'        => 'text',
				'priority'	  => 14
			)
		);

		$wp_customize->add_control(
			'github',
			array(
				'label'       => esc_html__( 'GitHub URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_github',
				'type'        => 'text',
				'priority'	  => 15
			)
		);

		$wp_customize->add_control(
			'pinterest',
			array(
				'label'       => esc_html__( 'Pinterest URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_pinterest',
				'type'        => 'text',
				'priority'	  => 16
			)
		);

		$wp_customize->add_control(
			'stackoverflow',
			array(
				'label'       => esc_html__( 'Stack Overflow URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_stackoverflow',
				'type'        => 'text',
				'priority'	  => 17
			)
		);

		$wp_customize->add_control(
			'deviantart',
			array(
				'label'       => esc_html__( 'DeviantART URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_deviantart',
				'type'        => 'text',
				'priority'	  => 18
			)
		);

		$wp_customize->add_control(
			'behance',
			array(
				'label'       => esc_html__( 'Behance URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_behance',
				'type'        => 'text',
				'priority'	  => 19
			)
		);

		$wp_customize->add_control(
			'delicious',
			array(
				'label'       => esc_html__( 'Delicious URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_delicious',
				'type'        => 'text',
				'priority'	  => 20
			)
		);

		$wp_customize->add_control(
			'soundcloud',
			array(
				'label'       => esc_html__( 'SoundCloud URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_soundcloud',
				'type'        => 'text',
				'priority'	  => 21
			)
		);

		$wp_customize->add_control(
			'spotify',
			array(
				'label'       => esc_html__( 'Spotify URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_spotify',
				'type'        => 'text',
				'priority'	  => 22
			)
		);

		$wp_customize->add_control(
			'stumbleupon',
			array(
				'label'       => esc_html__( 'StumbleUpon URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_stumbleupon',
				'type'        => 'text',
				'priority'	  => 23
			)
		);

		$wp_customize->add_control(
			'reddit',
			array(
				'label'       => esc_html__( 'Reddit URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_reddit',
				'type'        => 'text',
				'priority'	  => 24
			)
		);

		$wp_customize->add_control(
			'vine',
			array(
				'label'       => esc_html__( 'Vine URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_vine',
				'type'        => 'text',
				'priority'	  => 25
			)
		);

		$wp_customize->add_control(
			'digg',
			array(
				'label'       => esc_html__( 'Digg URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_digg',
				'type'        => 'text',
				'priority'	  => 26
			)
		);

		$wp_customize->add_control(
			'vk',
			array(
				'label'       => esc_html__( 'VK URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_vk',
				'type'        => 'text',
				'priority'	  => 27
			)
		);

		$wp_customize->add_control(
			'yelp',
			array(
				'label'       => esc_html__( 'Yelp URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_yelp',
				'type'        => 'text',
				'priority'	  => 27
			)
		);

		$wp_customize->add_control(
			'medium',
			array(
				'label'       => esc_html__( 'Medium URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_medium',
				'type'        => 'text',
				'priority'	  => 27
			)
		);

		$wp_customize->add_control(
			'slack',
			array(
				'label'       => esc_html__( 'Slack URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_slack',
				'type'        => 'text',
				'priority'	  => 27
			)
		);

		$wp_customize->add_control(
			'tripadvisor',
			array(
				'label'       => esc_html__( 'Tripadvisor URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_tripadvisor',
				'type'        => 'text',
				'priority'	  => 27
			)
		);

		$wp_customize->add_control(
			'rss',
			array(
				'label'       => esc_html__( 'RSS URL:', 'morrison-hotel' ),
				'section'     => 'morrison_hotel_new_section_social_header',
				'settings'    => 'morrison_hotel_opt_rss',
				'type'        => 'text',
				'priority'	  => 28
			)
		);

	/*--------------------------------------------------------------
	Mailchimp
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_mailchimp',
		array(
		'title'      => esc_html__( 'Mailchimp', 'morrison-hotel' ),
		'description'=> '',
		'priority'   => 56
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_opt_mailchimp_api',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		/*---- controls ----*/

		$wp_customize->add_control(
			'mailchimp_api',
			array(
				'label'       => esc_html__( 'Mailchimp API key:', 'morrison-hotel' ),
				'description' => esc_html__( 'Enter your MailChimp API key here.', 'morrison-hotel' ) . ' <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key" target="_blank">Where can I find my api key?</a>',
				'section'     => 'morrison_hotel_new_section_mailchimp',
				'settings'    => 'morrison_hotel_opt_mailchimp_api',
				'type'        => 'textarea',
				'priority'	  => 1
			)
		);

	/*--------------------------------------------------------------
	Google Maps
	--------------------------------------------------------------*/

	$wp_customize->add_section( 'morrison_hotel_new_section_google_maps',
		array(
		'title'      => esc_html__( 'Google Maps', 'morrison-hotel' ),
		'description'=> '',
		'priority'   => 57
	) );

		/*---- settings ----*/

		$wp_customize->add_setting(
			'morrison_hotel_opt_google_maps_api',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		/*---- controls ----*/

		$wp_customize->add_control(
			'google_maps_api',
			array(
				'label'       => esc_html__( 'Google Maps API key:', 'morrison-hotel' ),
				'description' => esc_html__( 'Enter your Google Maps API key here.', 'morrison-hotel' ) . ' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Get an API key</a>',
				'section'     => 'morrison_hotel_new_section_google_maps',
				'settings'    => 'morrison_hotel_opt_google_maps_api',
				'type'        => 'textarea',
				'priority'	  => 1
			)
		);
}
add_action( 'customize_register', 'morrison_hotel_customize_register' );

/**
 * Sanitize layout radio
 */
function morrison_hotel_sanitize_layout( $input ) {
	$whitelist = array(
		'right-sidebar',
		'left-sidebar',
		'no-sidebar',
	);

	if ( in_array( $input, $whitelist ) ) {
		return $input;
	} else {
		return 'left-sidebar';
	}
}

/**
 * Sanitize header layout select
 */
function morrison_hotel_sanitize_header_layout( $input ) {
	$whitelist = array(
		'default',
		'left-aligned',
		'right-aligned'
	);

	if ( in_array( $input, $whitelist ) ) {
		return $input;
	} else {
		return 'default';
	}
}

/**
 * Sanitize checkboxes
 */
function morrison_hotel_sanitize_checkbox( $input ) {
	return ( 1 == $input ) ? 1 : '';
}
