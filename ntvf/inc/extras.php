<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * @package Morrison_Hotel
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function morrison_hotel_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_home() || is_archive() || is_search() ) {
		$layout = esc_attr( get_theme_mod( 'morrison_hotel_opt_blog_layout', 'left-sidebar' ) );

		$classes[] = $layout;
	}

	if ( is_single() ) {
		if ( morrison_hotel_is_hotelier_active() && is_room() ) {
			$layout = esc_attr( get_theme_mod( 'morrison_hotel_opt_rooms_layout', 'no-sidebar' ) );

		} else {
			$layout = esc_attr( get_theme_mod( 'morrison_hotel_opt_blog_layout', 'left-sidebar' ) );
		}

		$classes[] = $layout;
	}

	if ( is_page() ) {
		$layout = 'no-sidebar';

		if ( morrison_hotel_is_hotelier_active() && ( is_booking() || is_listing() ) ) {
			$layout = esc_attr( get_theme_mod( 'morrison_hotel_opt_booking_layout', 'left-sidebar' ) );
		}

		$classes[] = $layout;
	}

	return $classes;
}
add_filter( 'body_class', 'morrison_hotel_body_classes' );

/**
 * Change the font-size of the tag cloud widget.
 */
if ( ! function_exists( 'morrison_hotel_tag_cloud' ) ) :
function morrison_hotel_tag_cloud( $args ) {
	$args[ 'largest' ] = 11;
	$args[ 'smallest' ] = 11;
	$args[ 'unit' ] = 'px';
	return $args;
}
endif;
add_filter( 'widget_tag_cloud_args', 'morrison_hotel_tag_cloud' );

/**
 * Check if Hotelier is active
 */
if ( ! function_exists( 'morrison_hotel_is_hotelier_active' ) ) :
	function morrison_hotel_is_hotelier_active() {
		if ( in_array( 'hotelier/hotelier.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		    return true;
		}
	}
endif;

/**
 * Hide the page title.
 */
if ( ! function_exists( 'morrison_hotel_hide_page_title' ) ) :
function morrison_hotel_hide_page_title() {
	// Hide the title only in regular pages or in the main blog page
	if ( ! is_home() && ! is_page() ) {
		return true;
	}

	if ( is_home() ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

	} else {

		global $post;

		$page_id = $post->ID;
	}


	if ( get_post_meta( $page_id, 'mh_hide_page_title', true ) ) {
		return false;
	}

	return true;
}
endif;
add_filter( 'morrison_hotel_show_page_title', 'morrison_hotel_hide_page_title' );

/**
 * Register Google Web Fonts
 */
if ( ! function_exists( 'morrison_hotel_fonts_url' ) ) :
	function morrison_hotel_fonts_url() {
		$fonts_url              = '';
		$primary_font_selected  = get_theme_mod( 'morrison_hotel_primary_font', 'Lora' );
		$headings_font_selected = get_theme_mod( 'morrison_hotel_headings_font', 'Montserrat' );

		/* Translators: If there are characters in your language that are not
		* supported by the primary font (default Lora), translate this to 'off'.
		* Do not translate into your own language.
		*/
		$primary_font = esc_html_x( 'on', 'Primary font: on or off', 'morrison-hotel' );

		/* Translators: If there are characters in your language that are not
		* supported by the headings font (default Montserrat), translate this to 'off'.
		* Do not translate into your own language.
		*/
		$headings_font = esc_html_x( 'on', 'Headings font: on or off', 'morrison-hotel' );

		if ( 'off' !== $primary_font || 'off' !== $headings_font ) {
			$font_families = array();

			if ( 'off' !== $primary_font ) {
				$font_families[] = $primary_font_selected . ':400,700,400italic,700italic';
			}

			if ( 'off' !== $headings_font ) {
				$font_families[] = $headings_font_selected . ':400';
			}

			$query_args = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);

			$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
		}

		return esc_url_raw( $fonts_url );
	}
endif;

/**
 * Convert hex color to rgba
 */
function morrison_hotel_hex2rgba( $color, $opacity ) {

	//Sanitize $color if "#" is provided
	if ( $color[ 0 ] == '#' ) {
		$color = substr( $color, 1 );
	}

	//Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array( $color[ 0 ] . $color[ 1 ], $color[ 2 ] . $color[ 3 ], $color[ 4 ] . $color[ 5 ] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[ 0 ] . $color[ 0 ], $color[ 1 ] . $color[ 1 ], $color[ 2 ] . $color[ 2 ] );
	}

	//Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );

	if ( abs( $opacity ) > 1 ) {
		$opacity = 1.0;
	}

	$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
	return $output;
}
