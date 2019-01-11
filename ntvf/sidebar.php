<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Morrison_Hotel
 */

if ( morrison_hotel_is_hotelier_active() && is_room() && ( get_theme_mod( 'morrison_hotel_opt_rooms_layout', 'no-sidebar' ) == 'no-sidebar' ) ) {
	return;
}
?>

<?php if ( morrison_hotel_is_hotelier_active() && is_booking_page() && ( get_theme_mod( 'morrison_hotel_opt_booking_layout', 'left-sidebar' ) != 'no-sidebar' ) ) : ?>

	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'morrison-hotel-booking-sidebar' ); ?>
	</aside><!-- #secondary -->

<?php else : ?>

	<aside id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'morrison-hotel-sidebar' ); ?>
	</aside><!-- #secondary -->

<?php endif; ?>
