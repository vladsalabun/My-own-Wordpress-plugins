(function($, sr){
	'use strict';
	/*jshint unused: vars */

	// smartresize function from Paul Irish
	// http://www.paulirish.com/2009/throttled-smartresize-jquery-event-handler/
	// debouncing function from John Hann
	// http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
	var debounce = function(func, threshold, execAsap) {
		var timeout;

		return function debounced () {
			var obj = this;
			var args = arguments;

			function delayed () {
				if (!execAsap) {
					func.apply(obj, args);
				}
				timeout = null;
			}

			if (timeout) {
				clearTimeout(timeout);
			} else if (execAsap) {
				func.apply(obj, args);
			}

			timeout = setTimeout(delayed, threshold || 100);
	   };
	};
	// smartresize
	jQuery.fn[sr] = function(fn) { return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery, 'smartresize');

jQuery(function($) {

	'use strict';
	/* global jQuery, hoverintent */

	var _w = window;
	var _doc = document;
	var _html = _doc.documentElement;
	var _body = _doc.body;

	var Morrison_Hotel = {
		init: function() {
			this.documentClasses();
			this.navigation();
			this.toggles();
			this.gallery();
			this.testimonials();
			this.carousel();
		},

		viewport: function() {
			// Function that calculates the width of the screen.
			var e = _w;
			var a = 'inner';

			if (!('innerWidth' in _w)) {
				a = 'client';
				e = _html || _body;
			}

			return {
				width: e[a + 'Width'],
				height: e[a + 'Height']
			};
		},

		documentClasses: function() {
			// Add the 'lol-mobile' class when the screens is less than 992.
			// Otherwise, add the 'lol-desktop' class.
			if (this.viewport().width < 992) {
				_html.className = _html.className.replace(' lol-desktop', '');

				if (-1 === _html.className.indexOf('lol-mobile')) {
					_html.className += ' lol-mobile';
				}
			} else {
				_html.className = _html.className.replace(' lol-mobile', '');
				if (-1 === _html.className.indexOf('lol-desktop')) {
					_html.className += ' lol-desktop';
				}
			}
		},

		retina: function(dektop, retina) {
			if ($(document.getElementById(retina)).length > 0) {
				var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
				var desktopLogo = $( document.getElementById( dektop ) );
				var retinaLogo = $( document.getElementById( retina ) );
				var logoWidth = desktopLogo.width();
				var logoHeight = desktopLogo.height();

				if (pixelRatio > 1) {
					retinaLogo.attr({height: logoHeight, width: logoWidth});
					retinaLogo.css('display', 'inline-block');
					desktopLogo.hide();
				}
			}
		},

		navigation: function() {
			// Off canvas navigation
			var container, button, menu, subMenus, obfuscator, closeButton, divMenu;

			container = _doc.getElementById('site-navigation');
			if (!container) {
				return;
			}

			button = _doc.getElementById('menu-toggle');
			if ('undefined' === typeof button) {
				return;
			}

			menu = _doc.getElementById('primary-menu');

			// Hide menu toggle button if menu is empty and return early.
			if ('undefined' === typeof menu) {
				button.style.display = 'none';
				return;
			}

			divMenu = _doc.getElementById('primary-menu-container');

			// Create overlay.
			obfuscator = _doc.createElement('div');
			obfuscator.id = 'mobile-nav-obfuscator';

			// Create another button to close the mobile menu from the sidebar.
			closeButton = _doc.createElement('button');
			closeButton.id = 'close-mobile-menu';
			closeButton.textContent = button.textContent;

			menu.setAttribute('aria-expanded', 'false');
			if (-1 === menu.className.indexOf('nav-menu')) {
				menu.className += ' nav-menu';
			}

			button.onclick = function() {
				if (-1 !== _html.className.indexOf('mobile-nav-open')) {
					close_menu();
				} else {
					open_menu();
				}
			};

			obfuscator.addEventListener('click', close_menu_listener);
			closeButton.addEventListener('click', close_menu_listener);

			// The listener attached to 'obfuscator' and 'closeButton' (closes the menu).
			function close_menu_listener() {
				if (-1 !== _html.className.indexOf('mobile-nav-open')) {
					close_menu();
				}
			}

			// Open the off canvas menu.
			function open_menu() {
				_html.className += ' mobile-nav-open';
				button.setAttribute('aria-expanded', 'true');
				menu.setAttribute('aria-expanded', 'true');
				_html.appendChild(obfuscator);
				divMenu.appendChild(closeButton);
				closeButton.setAttribute('aria-expanded', 'true');
			}

			// Close the off canvas menu.
			function close_menu() {
				_html.className = _html.className.replace(' mobile-nav-open', '');
				button.setAttribute('aria-expanded', 'false');
				menu.setAttribute('aria-expanded', 'false');
				obfuscator.remove();
				closeButton.remove();
			}

			// Get all submenus.
			subMenus = menu.getElementsByTagName('ul');

			// Set menu items with submenus to aria-haspopup="true".
			// Create a toggle button to expand/collapse the submenus.
			// Hoverintent on submenus to add a delay on mouseover.
			for (var i = 0, len = subMenus.length; i < len; i++) {

				// toggle button
				var expandMenu = _doc.createElement('span');

				expandMenu.className += 'expand-submenu';
				expandMenu.addEventListener('click', expand_submenu);

				if (-1 !== _html.className.indexOf('lol-mobile')) {
					subMenus[i].parentNode.appendChild(expandMenu);
				}

				// hoverintent
				hoverintent(subMenus[i].parentNode,
				function() {
					this.className += ' hover';
				}, function() {
					// this.className = 'off';
					this.className = this.className.replace(' hover', '');
				}).options({timeout: 200});

				// aria
				subMenus[i].parentNode.setAttribute('aria-haspopup', 'true');
			}

			// The listener attached to each submenu button (expand/collapse the submenu).
			function expand_submenu(e) {
				var _self = e.currentTarget;

				if (-1 !== _self.parentNode.className.indexOf('open')) {
					_self.parentNode.className = _self.parentNode.className.replace(' open', '');
				} else {
					_self.parentNode.className += ' open';
				}
			}

			// Close menu on resize and create the submenu toggles.
			$(window).smartresize(function() {
				close_menu();
			});
		},

		toggles: function() {
			var toggle = $('.morrison-hotel-toggle');
			var toggle_contents = toggle.find('.morrison-hotel-toggle-content');

			toggle_contents.not('[data-toggle="open"]').css('display', 'none');

			toggle.on('click', '.morrison-hotel-toggle-header', function() {
				var this_toggle = $(this);
				var toggle_content = $(this).next('.morrison-hotel-toggle-content');

				toggle_content.slideToggle();
				this_toggle.toggleClass('open');
			});

		},

		gallery: function() {
			if (jQuery().flexslider) {
				$('.morrison-hotel-gallery').flexslider({
					slideshowSpeed: $('.morrison-hotel-gallery').data('speed'),
					animation: 'slide',
					easing: 'swing',
					smoothHeight: true,
					animationSpeed: 600,
					directionNav: false
				});
			}
		},

		testimonials: function() {
			if (jQuery().flexslider) {
				$('.morrison-hotel-testimonials').flexslider({
					slideshowSpeed: $('.morrison-hotel-testimonials').data('speed'),
					animation: 'slide',
					easing: 'swing',
					smoothHeight: true,
					animationSpeed: 600,
					directionNav: false
				});
			}
		},

		carousel: function() {
			if (jQuery().owlCarousel) {
				$('.morrison-hotel-carousel').owlCarousel({
					center: true,
					loop: true,
					autoplay: true,
					nav: false,
					responsive: {
						0: {
							items: 1,
						},
						768: {
							items: 2,
						}
					}
				});
			}
		},
	};

	Morrison_Hotel.init();

	$(window).smartresize(function() {
		Morrison_Hotel.documentClasses();
	});

	$(window).load(function() {
		Morrison_Hotel.retina('desktop-logo', 'retina-logo');
	});
});
