<?php
/**
 * Hotelier specific functions.
 *
 * @package Morrison_Hotel
 */

/**
 * Disable default Hotelier CSS style
 */
if ( ! function_exists( 'morrison_hotel_disable_hotelier_style' ) ) :
	function morrison_hotel_disable_hotelier_style() {
		return false;
	}
endif;
add_filter( 'hotelier_enqueue_styles', 'morrison_hotel_disable_hotelier_style' );

/**
 * Extra div in room details.
 */
if ( ! function_exists( 'morrison_hotel_before_room_meta' ) ) :
	function morrison_hotel_before_room_meta() {
		echo '<div class="room-meta-wrapper">';
	}
endif;
add_action( 'hotelier_single_room_details', 'morrison_hotel_before_room_meta', 21 );

if ( ! function_exists( 'morrison_hotel_after_room_meta' ) ) :
	function morrison_hotel_after_room_meta() {
		echo '</div>';
	}
endif;
add_action( 'hotelier_single_room_details', 'morrison_hotel_after_room_meta', 51 );

/**
 * Hide room description from the room shortcodes/archive.
 */
remove_action( 'hotelier_archive_item_room', 'hotelier_template_archive_room_description', 20 );

/**
 * Show datepicker in page carousel.
 */
if ( ! function_exists( 'morrison_hotel_show_carousel_datepicker' ) ) :
	function morrison_hotel_show_carousel_datepicker() {
		echo do_shortcode( '[hotelier_datepicker]' );
	}
endif;
add_action( 'morrison_hotel_carousel_datepicker', 'morrison_hotel_show_carousel_datepicker', 10 );

/**
 * Register booking sidebar.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'morrison_hotel_booking_sidebar' ) ) :
	function morrison_hotel_booking_sidebar() {
		register_sidebar( array(
			'name'          => esc_html__( 'Booking Sidebar', 'morrison-hotel' ),
			'id'            => 'morrison-hotel-booking-sidebar',
			'description'   => esc_html__( 'This sidebar is visible only in the booking pages.', 'morrison-hotel' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
endif;
add_action( 'widgets_init', 'morrison_hotel_booking_sidebar' );
