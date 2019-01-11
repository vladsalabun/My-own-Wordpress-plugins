<?php
/**
 * Morrison Hotel Theme Customizer CSS.
 *
 * @package Morrison_Hotel
 */

/**
 * Output custom CSS to live site.
 */
function morrison_hotel_customizer_css() {
	?>

	<style type="text/css">

	/* Accent color */

	h1 a:hover,
	h1 a:focus,
	h1 a:active,
	h2 a:hover,
	h2 a:focus,
	h2 a:active,
	h3 a:hover,
	h3 a:focus,
	h3 a:active,
	h4 a:hover,
	h4 a:focus,
	h4 a:active,
	h5 a:hover,
	h5 a:focus,
	h5 a:active,
	h6 a:hover,
	h6 a:focus,
	h6 a:active,
	a,
	.entry-meta a:hover,
	.entry-footer .tags-links a:hover,
	.entry-footer .cat-links a:hover,
	.search .page-header .page-title span,
	#cancel-comment-reply-link,
	#respond .required,
	.morrison-hotel-service .service-icon,
	.morrison-hotel-toggle-header,
	#hotelier-datepicker .select-icon:before,
	.single-room .room-meta-wrapper a:hover,
	.single-room .room-rates .rate-conditions ul,
	form.room-list .more-about-room a:hover,
	form.room-list .show-gallery:hover,
	form.room-list .room-conditions ul,
	form.room-list .room-max-guests .max:after,
	.single-room .room-meta-wrapper a:hover,
	table.hotelier-table .view-price-breakdown:hover,
	.hotelier-listing .selected-nights:before,
	ul.reservation-details strong,
	.widget-hotelier-rooms-filter li:hover a,
	.widget-hotelier-rooms-filter li:hover a:before,
	.widget-hotelier-rooms-filter li.chosen a:before,
	.widget-hotelier-rooms-filter li.chosen a:hover:before,
	.widget-hotelier-booking .room-list-widget a:hover,
	table.hotelier-table td.room-name a:hover,
	.morrison-hotel-testimonials blockquote cite {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ) ); ?>;
	}
	.lol-mobile #menu-toggle:hover,
	.post-edit-link:hover,
	.morrison-hotel-newsletter-message,
	.flex-control-paging li a.flex-active,
	.flex-control-paging li a:hover,
	.hotelier-info,
	.hdp-wrapper .day.real-today.first-date-selected,
	.hdp-wrapper .day.real-today.last-date-selected,
	.hdp-wrapper .day.first-date-selected,
	.hdp-wrapper .day.last-date-selected,
	.hdp-wrapper .next:hover,
	.hdp-wrapper .prev:hover,
	.widget-hotelier-rooms-filter .filter-label,
	.widget-hotelier-booking .change-cart:hover,
	.hotelier-pagination ul .page-numbers.current,
	.hotelier-pagination ul .page-numbers:hover,
	.morrison-hotel-title:before,
	div.wpcf7-response-output {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ) ); ?>;
	}
	.post-edit-link,
	ul.rooms li.room .price .amount,
	.single-room .room-meta-wrapper h3,
	.single-room .room-details .room-price .amount,
	.single-room .room-rates .rate-price .amount,
	.single-room .room-meta-wrapper h3,
	form.room-list ul.rooms li.room.selected_room,
	ul.reservation-details .special-requests strong,
	.widget-hotelier-booking .amount,
	.morrison-hotel-menu h4,
	.sticky .entry-title {
		border-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ) ); ?>;
	}
	mark, ins {
		background-color: <?php echo esc_attr( morrison_hotel_hex2rgba( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ), 0.3 ) ); ?>;
	}
	.sticky .entry-title {
		background-color: <?php echo esc_attr( morrison_hotel_hex2rgba( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ), 0.1 ) ); ?>;
	}
	::selection {
		background-color: <?php echo esc_attr( morrison_hotel_hex2rgba( get_theme_mod( 'morrison_hotel_opt_primary_color', '#c19c78' ), 0.3 ) ); ?>;
	}

	/* Top header */

	#top-header {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_top_header_background_color', '#323536' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_top_header_text_color', '#999999' ) ); ?>;
	}

	#top-header a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_top_header_link_color', '#ffffff' ) ); ?>;
	}

	#top-header a:hover {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_top_header_link_hover_color', '#c19c78' ) ); ?>;
	}

	/* Masthead */

	#masthead {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_header_background_color', '#292b2c' ) ); ?>;
	}

	#masthead .site-title a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_header_site_title_color', '#ffffff' ) ); ?>;
	}

	#masthead .site-title a:hover {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_header_site_title_hover_color', '#c19c78' ) ); ?>;
	}

	/* Off-canvas Menu */

	.lol-mobile #primary-menu-container {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_mobile_menu_background_color', '#c19c78' ) ); ?>;

	}
	.lol-mobile #primary-menu-container a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_mobile_menu_text_color', '#ffffff' ) ); ?>;
	}

	.lol-mobile #primary-menu li:hover > a,
	.lol-mobile #primary-menu .current_page_item > a,
	.lol-mobile #primary-menu .current-menu-item > a,
	.lol-mobile #primary-menu .current_page_ancestor > a,
	.lol-mobile #primary-menu .current-menu-ancestor > a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_mobile_menu_text_hover_color', '#292b2c' ) ); ?>;
	}

	/* Menu */

	#primary-menu > li > a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_text_color', '#ffffff' ) ); ?>;
	}

	#primary-menu .current_page_item > a,
	#primary-menu .current-menu-item > a,
	#primary-menu .current_page_ancestor > a,
	#primary-menu .current-menu-ancestor > a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_text_hover_color', '#c19c78' ) ); ?>;
	}

	#primary-menu > li:hover > a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_text_hover_color', '#c19c78' ) ); ?>;
	}

	@media (min-width: 992px) {
		#primary-menu > li > a:before {
			background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_text_hover_color', '#c19c78' ) ); ?>;
		}
		#primary-menu ul {
			background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_submenu_background_color', '#323536' ) ); ?>;
		}

		#primary-menu ul li:hover {
			background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_submenu_background_hover_color', '#292b2c' ) ); ?>;
		}

		#primary-menu .sub-menu .current_page_item > a,
		#primary-menu .sub-menu .current-menu-item > a,
		#primary-menu .sub-menu .current_page_ancestor > a,
		#primary-menu .sub-menu .current-menu-ancestor > a {
			color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_submenu_text_hover_color', '#ffffff' ) ); ?>;
		}
	}

	#primary-menu ul a {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_submenu_text_color', '#999999' ) ); ?>;
	}

	#primary-menu ul li:hover > a, {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_menu_submenu_text_hover_color', '#ffffff' ) ); ?>;
	}

	/* Buttons */

	.more-link,
	.post-edit-link,
	.widget_tag_cloud a,
	#colophon .widget_tag_cloud a,
	ul.rooms li.room .view-room-details,
	form.room-list .only-x-left,
	.hdp-wrapper .apply-btn {
		border: 1px solid <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_border_color', '#c19c78' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_text_color', '#c19c78' ) ); ?>;
	}

	.more-link:hover,
	.post-edit-link:hover,
	.widget_tag_cloud a:hover,
	#colophon .widget_tag_cloud a:hover,
	ul.rooms li.room .view-room-details:hover,
	.hdp-wrapper .apply-btn:hover {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_background_hover_color', '#c19c78' ) ); ?>;
		border-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_border_hover_color', '#c19c78' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_text_hover_color', '#ffffff' ) ); ?>;
	}

	.button,
	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"],
	.posts-navigation a,
	.comment-navigation a:hover,
	.morrison-hotel-page-boxes .view-more-link {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_alt_background_color', '#c19c78' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_alt_text_color', '#ffffff' ) ); ?>;
	}

	.button:hover,
	button:hover,
	button:active,
	button:focus,
	input[type="button"]:hover,
	input[type="button"]:active,
	input[type="button"]:focus,
	input[type="reset"]:hover,
	input[type="reset"]:active,
	input[type="reset"]:focus,
	input[type="submit"]:hover,
	input[type="submit"]:active,
	input[type="submit"]:focus,
	.posts-navigation a:hover,
	.comment-navigation a,
	.morrison-hotel-page-boxes .view-more-link:hover {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_alt_background_hover_color', '#ebebeb' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_button_alt_text_hover_color', '#292b2c' ) ); ?>;
	}

	/* Colophon */

	#colophon {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_widgets_background_color', '#323536' ) ); ?>;
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_text_color', '#999999' ) ); ?>;
	}

	#colophon .footer-menu {
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_background_color', '#292b2c' ) ); ?>;
	}

	#colophon a,
	#colophon h1 a,
	#colophon h2 a,
	#colophon h3 a,
	#colophon h4 a,
	#colophon h5 a,
	#colophon h6 a,
	#colophon h1 a:visited,
	#colophon h2 a:visited,
	#colophon h3 a:visited,
	#colophon h4 a:visited,
	#colophon h5 a:visited,
	#colophon h6 a:visited,
	#colophon strong,
	#colophon .widget_rss cite {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_link_color', '#ffffff' ) ); ?>;
	}

	#colophon a:hover,
	#colophon h1 a:hover,
	#colophon h1 a:focus,
	#colophon h1 a:active,
	#colophon h2 a:hover,
	#colophon h2 a:focus,
	#colophon h2 a:active,
	#colophon h3 a:hover,
	#colophon h3 a:focus,
	#colophon h3 a:active,
	#colophon h4 a:hover,
	#colophon h4 a:focus,
	#colophon h4 a:active,
	#colophon h5 a:hover,
	#colophon h5 a:focus,
	#colophon h5 a:active,
	#colophon h6 a:hover,
	#colophon h6 a:focus,
	#colophon h6 a:active {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_link_hover_color', '#c19c78' ) ); ?>;
	}

	#colophon h1,
	#colophon h2,
	#colophon h3,
	#colophon h4,
	#colophon h5,
	#colophon h6,
	#colophon h3.widget-title {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_headings_color', '#999999' ) ); ?>;
	}

	#colophon input[type="text"],
	#colophon input[type="email"],
	#colophon input[type="url"],
	#colophon input[type="password"],
	#colophon input[type="search"],
	#colophon textarea {
		color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_inputs_text_color', '#999999' ) ); ?>;
		background-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_inputs_background_color', '#292b2c' ) ); ?>;
		border-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_inputs_border_color', '#383b3c' ) ); ?>;
	}

	#colophon input[type="text"]:focus,
	#colophon input[type="email"]:focus,
	#colophon input[type="url"]:focus,
	#colophon input[type="password"]:focus,
	#colophon input[type="search"]:focus,
	#colophon textarea:focus {
		border-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_inputs_border_focus_color', '#454849' ) ); ?>;
	}

	#colophon .widget_calendar table,
	#colophon .widget_calendar caption,
	#colophon .widget_calendar thead,
	#colophon .widget_calendar tfoot {
		border-color: <?php echo esc_attr( get_theme_mod( 'morrison_hotel_opt_footer_inputs_border_color', '#383b3c' ) ); ?>;
	}

	/* Fonts */

	<?php
	$primary_font  = get_theme_mod( 'morrison_hotel_primary_font', 'Lora' );
	$headings_font = get_theme_mod( 'morrison_hotel_headings_font', 'Montserrat' );
	?>

	body,
	button,
	input,
	select,
	textarea {
		font-family: "<?php echo esc_attr( $primary_font ); ?>", serif;
	}

	.button,
	button,
	input[type="submit"],
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	#primary-menu-container,
	#footer-menu,
	#footer-menu ul,
	.widget_recent_entries ul .post-date,
	.widget_rss .rss-date,
	.widget_rss cite,
	.widget_tag_cloud a,
	.widget_calendar caption,
	.entry-footer .tags-links a,
	.entry-footer .cat-links a,
	.entry-footer .comments-link a span,
	.more-link,
	.post-edit-link,
	.page-links a,
	.posts-navigation a,
	.morrison-hotel-toggle-header,
	.morrison-hotel-page-boxes .view-more-link,
	.reply a,
	.comment-navigation a {
		font-family: "<?php echo esc_attr( $headings_font ); ?>", serif;
	}

	</style>
	<?php
}
add_action( 'wp_head', 'morrison_hotel_customizer_css' );
