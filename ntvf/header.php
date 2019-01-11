<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Morrison_Hotel
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
	<?php if ( get_theme_mod( 'morrison_hotel_opt_favicon' ) ) : ?>
	<link rel="shortcut icon" href="<?php echo esc_url( get_theme_mod( 'morrison_hotel_opt_favicon' ) ); ?>">
	<?php endif; ?>
<?php endif; ?>

<script>document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/g, '') + ' js ';</script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'morrison-hotel' ); ?></a>

	<div id="top-header">
		<div class="wrap">

			<?php if ( $morrison_hotel_site_info = get_theme_mod( 'morrison_hotel_opt_site_info' ) ) : ?>
				<p class="site-info"><?php echo esc_html( $morrison_hotel_site_info ); ?></p>
			<?php endif; ?>

			<?php
			/* Show social icons if activated */
			if ( get_theme_mod( 'morrison_hotel_opt_show_social' ) ) :
				morrison_hotel_social_header();
			endif; ?>
		</div><!-- .wrap -->
	</div><!-- #top-header -->

	<?php
	$morrison_hotel_header_layout = get_theme_mod( 'morrison_hotel_opt_header_layout', 'default' );
	?>

	<header id="masthead" class="site-header <?php echo esc_attr( $morrison_hotel_header_layout ); ?>">
		<div class="wrap">
			<div class="site-branding">
				<?php
				/* Show custom logo if available */
				if ( $morrison_hotel_logo_desktop = get_theme_mod( 'morrison_hotel_opt_logo' ) ) :

					$morrison_hotel_logo_data = get_theme_mod( 'morrison_hotel_opt_logo_data' ); ?>

					<?php if ( ( is_home() ) || ( is_front_page() ) ) : ?>
					<h1 class="site-title semantic"><?php bloginfo( 'name' ); ?></h1>
					<?php else : ?>
					<h3 class="site-title semantic"><?php bloginfo( 'name' ); ?></h3>
					<?php endif; ?>

					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-logo">
						<img id="desktop-logo" src="<?php echo esc_url( $morrison_hotel_logo_desktop ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $morrison_hotel_logo_data[ 'width' ] ); ?>" height="<?php echo esc_attr( $morrison_hotel_logo_data[ 'height' ] ); ?>">

						<?php
						/* Check if the retina version is available */
						if ( $morrison_hotel_logo_retina = get_theme_mod('morrison_hotel_opt_logo_retina') ):
							$morrison_hotel_logo_data = get_theme_mod( 'morrison_hotel_opt_logo_retina_data' ); ?>
							<img id="retina-logo" src="<?php echo esc_url( $morrison_hotel_logo_retina ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $morrison_hotel_logo_data[ 'width' ] ); ?>" height="<?php echo esc_attr( $morrison_hotel_logo_data[ 'height' ] ); ?>">
						<?php endif; ?>
					</a>

				<?php
				/* No custom logo available */
				else: ?>

					<?php
					if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<h3 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h3>
					<?php
					endif;

				endif; ?>
			</div><!-- .site-branding -->

			<nav id="site-navigation" class="main-navigation">
				<button id="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'morrison-hotel' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container_id' => 'primary-menu-container' ) ); ?>
			</nav><!-- #site-navigation -->
		</div><!-- .wrap -->
	</header><!-- #masthead -->

	<?php do_action( 'morrison_hotel_after_masthead' ); ?>

	<div id="content" class="site-content wrap">

