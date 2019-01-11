<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Morrison_Hotel
 */

if ( ! function_exists( 'morrison_hotel_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function morrison_hotel_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'morrison-hotel' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'morrison-hotel' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

if ( ! function_exists( 'morrison_hotel_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function morrison_hotel_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'morrison-hotel' ) );
		if ( $categories_list && morrison_hotel_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'morrison-hotel' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'morrison-hotel' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'morrison-hotel' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'morrison-hotel' ), wp_kses( __( '<span>1</span> Comment', 'morrison-hotel' ), array( 'span' => array() ) ), wp_kses( __( '<span>%</span> Comments', 'morrison-hotel' ), array( 'span' => array() ) ) );
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			/* translators: %s: Name of current post */
			esc_html__( 'Edit %s', 'morrison-hotel' ),
			the_title( '<span class="screen-reader-text">"', '"</span>', false )
		),
		'<span class="edit-link">',
		'</span>'
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function morrison_hotel_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'morrison_hotel_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'morrison_hotel_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so morrison_hotel_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so morrison_hotel_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in morrison_hotel_categorized_blog.
 */
function morrison_hotel_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'morrison_hotel_categories' );
}
add_action( 'edit_category', 'morrison_hotel_category_transient_flusher' );
add_action( 'save_post',     'morrison_hotel_category_transient_flusher' );

if ( ! function_exists( 'twentyfifteen_post_thumbnail' ) ) :
/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function morrison_hotel_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	$size = ( get_theme_mod( 'morrison_hotel_opt_blog_layout' ) == 'no-sidebar' ) ? 'morrison_hotel_1140x699' : 'morrison_hotel_815x500';

	if ( is_singular() ) :
	?>

	<div class="post-thumbnail">
		<?php the_post_thumbnail( $size ); ?>
	</div><!-- .post-thumbnail -->

	<?php else : ?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
		<?php
			the_post_thumbnail( $size, array( 'alt' => get_the_title() ) );
		?>
	</a>

	<?php endif; // End is_singular()
}
endif;

/**
 * Display social accounts header.
 */
if ( ! function_exists( 'morrison_hotel_social_header' ) ) :
	function morrison_hotel_social_header() {
		$target = ( get_theme_mod( 'morrison_hotel_opt_check_social_target' ) ? 'target="_blank"' : '' );
		?>

		<div class="site-follow">

			<?php if ( $morrison_hotel_social_header_label = get_theme_mod( 'morrison_hotel_opt_social_header_label' ) ) : ?>
			<span class="site-follow-label"><?php echo esc_html( $morrison_hotel_social_header_label ); ?></span>
			<?php endif; ?>

			<ul>
				<?php if ( $morrison_hotel_opt_facebook = get_theme_mod( 'morrison_hotel_opt_facebook' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_facebook ); ?>" <?php echo esc_attr( $target ); ?> title="Facebook"><i class="fa fa-facebook"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_twitter = get_theme_mod( 'morrison_hotel_opt_twitter' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_twitter ); ?>" <?php echo esc_attr( $target ); ?> title="Twitter"><i class="fa fa-twitter"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_dribbble = get_theme_mod( 'morrison_hotel_opt_dribbble' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_dribbble ); ?>" <?php echo esc_attr( $target ); ?> title="Dribbble"><i class="fa fa-dribbble"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_linkedin = get_theme_mod( 'morrison_hotel_opt_linkedin' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_linkedin ); ?>" <?php echo esc_attr( $target ); ?> title="Linkedin"><i class="fa fa-linkedin"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_flickr = get_theme_mod( 'morrison_hotel_opt_flickr' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_flickr ); ?>" <?php echo esc_attr( $target ); ?> title="Flickr"><i class="fa fa-flickr"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_tumblr = get_theme_mod( 'morrison_hotel_opt_tumblr' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_tumblr ); ?>" <?php echo esc_attr( $target ); ?> title="Tumblr"><i class="fa fa-tumblr"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_vimeo = get_theme_mod( 'morrison_hotel_opt_vimeo' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_vimeo ); ?>" <?php echo esc_attr( $target ); ?> title="Vimeo"><i class="fa fa-vimeo-square"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_youtube = get_theme_mod( 'morrison_hotel_opt_youtube' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_youtube ); ?>" <?php echo esc_attr( $target ); ?> title="Youtube"><i class="fa fa-youtube"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_instagram = get_theme_mod( 'morrison_hotel_opt_instagram' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_instagram ); ?>" <?php echo esc_attr( $target ); ?> title="Instagram"><i class="fa fa-instagram"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_google = get_theme_mod( 'morrison_hotel_opt_google' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_google ); ?>" <?php echo esc_attr( $target ); ?> title="Google Plus"><i class="fa fa-google-plus"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_foursquare = get_theme_mod( 'morrison_hotel_opt_foursquare' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_foursquare ); ?>" <?php echo esc_attr( $target ); ?> title="Foursquare"><i class="fa fa-foursquare"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_github = get_theme_mod( 'morrison_hotel_opt_github' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_github ); ?>" <?php echo esc_attr( $target ); ?> title="GitHub"><i class="fa fa-github"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_pinterest = get_theme_mod( 'morrison_hotel_opt_pinterest' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_pinterest ); ?>" <?php echo esc_attr( $target ); ?> title="Pinterest"><i class="fa fa-pinterest"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_stackoverflow = get_theme_mod( 'morrison_hotel_opt_stackoverflow' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_stackoverflow ); ?>" <?php echo esc_attr( $target ); ?> title="Stack Overflow"><i class="fa fa-stack-overflow"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_deviantart = get_theme_mod( 'morrison_hotel_opt_deviantart' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_deviantart ); ?>" <?php echo esc_attr( $target ); ?> title="DeviantART"><i class="fa fa-deviantart"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_behance = get_theme_mod( 'morrison_hotel_opt_behance' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_behance ); ?>" <?php echo esc_attr( $target ); ?> title="Behance"><i class="fa fa-behance"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_delicious = get_theme_mod( 'morrison_hotel_opt_delicious' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_delicious ); ?>" <?php echo esc_attr( $target ); ?> title="Delicious"><i class="fa fa-delicious"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_soundcloud = get_theme_mod( 'morrison_hotel_opt_soundcloud' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_soundcloud ); ?>" <?php echo esc_attr( $target ); ?> title="SoundCloud"><i class="fa fa-soundcloud"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_spotify = get_theme_mod( 'morrison_hotel_opt_spotify' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_spotify ); ?>" <?php echo esc_attr( $target ); ?> title="Spotify"><i class="fa fa-spotify"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_stumbleupon = get_theme_mod( 'morrison_hotel_opt_stumbleupon' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_stumbleupon ); ?>" <?php echo esc_attr( $target ); ?> title="StumbleUpon"><i class="fa fa-stumbleupon"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_reddit = get_theme_mod( 'morrison_hotel_opt_reddit' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_reddit ); ?>" <?php echo esc_attr( $target ); ?> title="Reddit"><i class="fa fa-reddit"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_vine = get_theme_mod( 'morrison_hotel_opt_vine' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_vine ); ?>" <?php echo esc_attr( $target ); ?> title="Vine"><i class="fa fa-vine"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_digg = get_theme_mod( 'morrison_hotel_opt_digg' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_digg ); ?>" <?php echo esc_attr( $target ); ?> title="Digg"><i class="fa fa-digg"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_vk = get_theme_mod( 'morrison_hotel_opt_vk' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_vk ); ?>" <?php echo esc_attr( $target ); ?> title="VK"><i class="fa fa-vk"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_yelp = get_theme_mod( 'morrison_hotel_opt_yelp' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_yelp ); ?>" <?php echo esc_attr( $target ); ?> title="Yelp"><i class="fa fa-yelp"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_medium = get_theme_mod( 'morrison_hotel_opt_medium' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_medium ); ?>" <?php echo esc_attr( $target ); ?> title="Medium"><i class="fa fa-medium"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_slack = get_theme_mod( 'morrison_hotel_opt_slack' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_slack ); ?>" <?php echo esc_attr( $target ); ?> title="Slack"><i class="fa fa-slack"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_tripadvisor = get_theme_mod( 'morrison_hotel_opt_tripadvisor' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_tripadvisor ); ?>" <?php echo esc_attr( $target ); ?> title="Tripadvisor"><i class="fa fa-tripadvisor"></i></a></li>
				<?php endif; ?>
				<?php if ( $morrison_hotel_opt_rss = get_theme_mod( 'morrison_hotel_opt_rss' ) ) : ?>
					<li><a href="<?php echo esc_url( $morrison_hotel_opt_rss ); ?>" <?php echo esc_attr( $target ); ?> title="RSS"><i class="fa fa-rss"></i></a></li>
				<?php endif; ?>
			</ul>

		</div>

	<?php
	}
endif;

/**
 * Filter the archive title.
 */
if ( ! function_exists( 'morrison_hotel_archive_title' ) ) :
	function morrison_hotel_archive_title() {
		if ( is_category() ) {
			$title = esc_html__( 'Category', 'morrison-hotel' ) . ' / ' . '<span>' . single_cat_title( '', false ) . '</span>';
		} elseif ( is_tag() ) {
			$title = esc_html__( 'Tag', 'morrison-hotel' ) . ' / ' . '<span>' . single_tag_title( '', false ) . '</span>';
		} elseif ( is_author() ) {
			$title = esc_html__( 'Author', 'morrison-hotel' ) . ' / ' . '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = esc_html__( 'Year', 'morrison-hotel' ) . ' / ' . '<span>' . get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'morrison-hotel' ) ) . '</span>';
		} elseif ( is_month() ) {
			$title = esc_html__( 'Month', 'morrison-hotel' ) . ' / ' . '<span>' . get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'morrison-hotel' ) ) . '</span>';
		} elseif ( is_day() ) {
			$title = esc_html__( 'Day', 'morrison-hotel' ) . ' / ' . '<span>' . get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'morrison-hotel' ) ) . '</span>';
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Asides', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Galleries', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Images', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Videos', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Quotes', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Links', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Statuses', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Audio', 'post format archive title', 'morrison-hotel' ) . '</span>';
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = esc_html__( 'Post Format', 'morrison-hotel' ) . ' / ' . '<span>' . esc_html_x( 'Chats', 'post format archive title', 'morrison-hotel' ) . '</span>';
			}
		} elseif ( is_post_type_archive() ) {
			$title = esc_html__( 'Archives', 'morrison-hotel' ) . ' / ' . '<span>' . post_type_archive_title( '', false ) . '</span>';
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			$title = $tax->labels->singular_name . ' / ' . '<span>' . single_term_title( '', false ) . '</span>';
		} else {
			$title = esc_html__( 'Archives', 'morrison-hotel' );
		}

		return $title;
	}
endif;
add_filter( 'get_the_archive_title', 'morrison_hotel_archive_title' );

if ( ! function_exists( 'morrison_hotel_page_cover' ) ) :
/**
 * Displays the page cover if enabled.
 */
function morrison_hotel_page_cover() {
	// Show the cover only in regular pages or in the main blog page
	if ( ! is_home() && ! is_page() ) {
		return;
	}

	if ( is_home() ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

	} else {
		global $post;

		$page_id = $post->ID;
	}

	// Check if the page has the carousel enabled with some images attached
	if ( ! ( get_post_meta( $page_id, 'mh_show_page_carousel', true ) ) || ! ( get_post_meta( $page_id, 'mh_page_carousel_images', true ) ) ) {
		return;
	}

	?>

	<div id="page-carousel">
		<?php

		$images = explode( ',', get_post_meta( $page_id, 'mh_page_carousel_images', true ) );
		$images = array_map( 'absint', $images );
		?>

		<?php if ( $images ) :
			$show_booking_form = get_post_meta( $page_id, 'mh_show_booking_form', true ) ? true : false;
			?>

			<div class="owl-carousel morrison-hotel-carousel <?php echo $show_booking_form ? 'has-booking-form' : ''; ?>">

				<?php foreach ( $images as $image ) :

					$attachment   = get_post( intval( $image ) );
					$alt          = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true );
					$img_obj      = wp_get_attachment_image_src( $image, 'morrison_hotel_815x500' );
					$image_srcset = function_exists( 'wp_get_attachment_image_srcset' ) ? wp_get_attachment_image_srcset( $image, 'morrison_hotel_815x500' ) : false;
					$image_sizes  = function_exists( 'wp_get_attachment_image_sizes' ) ? wp_get_attachment_image_sizes( $image, 'morrison_hotel_815x500' ) : false;
					?>

					<div>
						<img src="<?php echo esc_url( $img_obj[ 0 ] ); ?>" width="<?php echo esc_attr( $img_obj[ 1 ] ); ?>" height="<?php echo esc_attr( $img_obj[ 2 ] ); ?>" <?php echo $image_srcset ? 'srcset="' . esc_attr( $image_srcset ) . '"' : ''; ?> <?php echo $image_sizes ? 'sizes="' . esc_attr( $image_sizes ) . '"' : ''; ?> alt="<?php echo esc_attr( $alt ); ?>">
					</div>

				<?php endforeach; ?>

			</div>

		<?php endif; ?>

	</div>

	<?php
}
endif;
add_action( 'morrison_hotel_after_masthead', 'morrison_hotel_page_cover', 10 );

if ( ! function_exists( 'morrison_hotel_page_datepicker' ) ) :
/**
 * Displays the page datepicker if enabled.
 */
function morrison_hotel_page_datepicker() {
	// Show the cover only in regular pages or in the main blog page
	if ( ! is_home() && ! is_page() ) {
		return;
	}

	if ( is_home() ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

	} else {
		global $post;

		$page_id = $post->ID;
	}

	// Check if the page has the datepicker enabled
	$show_booking_form = get_post_meta( $page_id, 'mh_show_booking_form', true ) ? true : false;

	if ( ! $show_booking_form ) {
		return;
	}

	$has_carousel = false;

	// Check if the page has the carousel enabled with some images attached
	if ( get_post_meta( $page_id, 'mh_show_page_carousel', true ) && get_post_meta( $page_id, 'mh_page_carousel_images', true ) ) {
		$has_carousel = true;
	}

	?>

	<div id="page-datepicker" class="<?php echo $has_carousel ? 'has-carousel' : 'false'; ?>">

		<?php
			/**
			 * morrison_hotel_carousel_datepicker hook.
			 *
			 * @hooked morrison_hotel_show_carousel_datepicker - 10
			 */
			do_action( 'morrison_hotel_carousel_datepicker' );
		?>

	</div>

	<?php
}
endif;
add_action( 'morrison_hotel_after_masthead', 'morrison_hotel_page_datepicker', 15 );
