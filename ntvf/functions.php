<?php
/**
 * Morrison Hotel functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Morrison_Hotel
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1140; /* pixels */
}

if ( ! function_exists( 'morrison_hotel_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function morrison_hotel_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Morrison Hotel, use a find and replace
	 * to change 'morrison-hotel' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'morrison-hotel', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Thumbnail sizes
	add_image_size( 'morrison_hotel_1140x699', 1140, 699, true );
	add_image_size( 'morrison_hotel_570x570', 570, 570, true );
	add_image_size( 'morrison_hotel_815x500', 815, 500, true );
	add_image_size( 'morrison_hotel_480x294', 480, 294, true );
	add_image_size( 'morrison_hotel_320x320', 320, 320, true );
	add_image_size( 'morrison_hotel_320x196', 320, 196, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary'    => esc_html__( 'Primary', 'morrison-hotel' ),
		'footer'     => esc_html__( 'Footer', 'morrison-hotel' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for exceprts on pages (exceprts are reuired by the 'Page-Boxes' shortcode).
	 */
	add_post_type_support( 'page', 'excerpt' );
}
endif; // morrison_hotel_setup
add_action( 'after_setup_theme', 'morrison_hotel_setup' );

/**
 * Register widget area.
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function morrison_hotel_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'morrison-hotel' ),
		'id'            => 'morrison-hotel-sidebar',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'morrison-hotel' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'morrison-hotel' ),
		'id'            => 'morrison-hotel-footer-1',
		'description'   => esc_html__( 'Add widgets here to appear in your footer (column 1).', 'morrison-hotel' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'morrison-hotel' ),
		'id'            => 'morrison-hotel-footer-2',
		'description'   => esc_html__( 'Add widgets here to appear in your footer (column 2).', 'morrison-hotel' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'morrison-hotel' ),
		'id'            => 'morrison-hotel-footer-3',
		'description'   => esc_html__( 'Add widgets here to appear in your footer (column 3).', 'morrison-hotel' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'morrison_hotel_widgets_init' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function morrison_hotel_content_width() {
	$GLOBALS[ 'content_width' ] = apply_filters( 'morrison_hotel_content_width', 640 );
}
add_action( 'after_setup_theme', 'morrison_hotel_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function morrison_hotel_scripts() {
	wp_enqueue_style( 'morrison-hotel-style', get_stylesheet_uri(), array(), '20160112' );

	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.5.0' );

	wp_enqueue_style( 'morrison-hotel-fonts', morrison_hotel_fonts_url(), array(), null );

	wp_register_script( 'morrison-hotel-shared-js', get_template_directory_uri() . '/js/shared.min.js', array(), '20160112', true );

	wp_enqueue_script( 'morrison-hotel-js', get_template_directory_uri() . '/js/morrison-hotel.min.js', array( 'jquery', 'morrison-hotel-shared-js' ), '20160112', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( morrison_hotel_is_hotelier_active() ) {
		wp_enqueue_style( 'morrison-hotel-hotelier', get_template_directory_uri() . '/css/hotelier.css', array(), '20160112' );
	}
}
add_action( 'wp_enqueue_scripts', 'morrison_hotel_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/customizer-css.php';

/**
 * Install required plugins.
 */
require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/inc/activate-morrison-hotel-plugins.php';

if ( morrison_hotel_is_hotelier_active() ) {
	require get_template_directory() . '/inc/hotelier.php';
}
