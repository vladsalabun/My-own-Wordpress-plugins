<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Morrison_Hotel
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="wrap">
			<div class="widget-area">

				<div class="footer-widget">
					<?php dynamic_sidebar( 'morrison-hotel-footer-1' ); ?>
				</div><!-- .footer-widget -->

				<div class="footer-widget">
					<?php dynamic_sidebar( 'morrison-hotel-footer-2' ); ?>
				</div><!-- .footer-widget -->

				<div class="footer-widget">
					<?php dynamic_sidebar( 'morrison-hotel-footer-3' ); ?>
				</div><!-- .footer-widget -->

			</div><!-- .widget-area -->
		</div><!-- .wrap -->

		<div class="footer-menu">
			<div class="wrap">
				<?php wp_nav_menu( array( 'theme_location' => 'footer', 'menu_id' => 'footer-menu', 'depth' => -1 ) ); ?>
			</div><!-- .wrap -->
		</div><!-- .footer-menu -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
