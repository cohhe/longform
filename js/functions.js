/**
 * Theme functions file
 *
 * Contains handlers for navigation, accessibility, header sizing
 * footer widgets and Featured Content slider
 *
 */

 var longform = {};

( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window );

	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			$('.scroll-to-top').fadeIn();
		} else {
			$('.scroll-to-top').fadeOut();
		}

		if ( jQuery('.scroll-nav__item').hasClass('in-view') ) {
			jQuery('body').addClass('timeline-in-view');
		} else {
			jQuery('body').removeClass('timeline-in-view');
		}
	});

	$('.scroll-to-top').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});

	jQuery(".header-search .search-button").on('click', function(e) {
		e.preventDefault();
		if ( jQuery(".header-search form input").val() != '' ) {
			jQuery('.header-search form').submit();
		} else {
			jQuery('.header-search').toggleClass('header-search-open');

			setTimeout(function(){
				jQuery(".header-search form.header-search-form input").focus();
			}, 500);
		}
	});

	jQuery(".header-main .menu-toggle a").on('click', function(e) {
		e.preventDefault();
		jQuery('.header-main').toggleClass('header-menu-active');
	});

	jQuery('body').click(function() {
		if ( jQuery('#primary-navigation').hasClass('in') ) {
			jQuery('.site-navigation').collapse("hide");
		};
	});

	// Shrink header on scroll down
	if($('.site-header').length > 0) {
		var y = $(window).scrollTop();
		var padding_element = $('.slider');
		var site_title = jQuery('.site-title a').text();

		if ( padding_element.length === 0 && jQuery('.intro-effect-bg-img-container').length === 0 ) {
			padding_element = $('#main');
		} else if ( jQuery('.intro-effect-bg-img-container').length !== 0 ) {
			padding_element = $('.intro-effect-bg-img-container');
		}

		if($(window).width() > 979) {
			masthead_height = $('.site-header').height();
			masthead_top    = $('.site-header').offset().top+$('.site-header').height()+50; 

			if( y > masthead_top ) { 
				$('.site-header').addClass('fixed'); 
				padding_element.css('margin-top', (masthead_height)+'px'); 
			}

			if( y > 150 ) {

				// On single post page show article title in header menu bar
				// if ( jQuery('body').hasClass('single-post') ) {
				// 	jQuery('.single-post .site-title a').text(jQuery('.entry-title').text());
				// }

				$('.site-header, .progress-bar').addClass('shrink');
				$('body').addClass('sticky_header_active');
			} else {

				// Switch back to site title
				// if ( jQuery('body').hasClass('single-post') ) {
				// 	jQuery('.single-post .site-title a').text(site_title);
				// }

				$('.site-header, .progress-bar').removeClass('shrink');
				$('body').removeClass('sticky_header_active');
			}
			
			// Shrink menu on scroll
			var didScroll = false;
			$(window).scroll(function() {
				didScroll = true;
			});

			setInterval(function() {
				if ( didScroll ) {
					didScroll = false;
					y         = $(window).scrollTop();

					if( y > masthead_top ) { 
						$('.site-header').addClass('fixed'); 
						padding_element.css('margin-top', (masthead_height)+'px'); 
					} else { 
						$('.site-header').removeClass('fixed'); 
						padding_element.css('margin-top', ''); 
					}

					if( y > 500 ) {

						// On single post page show article title in header menu bar
						// if ( jQuery('body').hasClass('single-post') ) {
						// 	jQuery('.single-post .site-title a').text(jQuery('.entry-title').text());
						// }

						jQuery('.site-header, .progress-bar').addClass('shrink');
						jQuery('body').addClass('sticky_header_active');
					} else {

						// Switch back to site title
						if ( jQuery('body').hasClass('single-post') && !jQuery('.site-title a img').length ) {
							jQuery('.single-post .site-title a').text(site_title);
						}

						jQuery('.site-header, .progress-bar').removeClass('shrink');
						jQuery('body').removeClass('sticky_header_active'); 
					}
				}
			}, 50);
		} else {

			// Switch back to site title
			// if ( jQuery('body').hasClass('single-post') ) {
			// 	jQuery('.single-post .site-title a').text(site_title);
			// }

			jQuery('.site-header, .progress-bar').removeClass('shrink');
			jQuery('.site-header').removeClass('fixed');
		}

	} else {
		jQuery('#page').addClass('static-header'); 
	}

	// Enable menu toggle for small screens.
	( function() {
		var nav = $( '#primary-navigation' ), button, menu;
		if ( ! nav ) {
			return;
		}

		button = nav.find( '.menu-toggle' );
		if ( ! button ) {
			return;
		}

		// Hide button if menu is missing or empty.
		menu = nav.find( '.nav-menu' );
		if ( ! menu || ! menu.children().length ) {
			button.hide();
			return;
		}

		$( '.menu-toggle' ).on( 'click.longform', function() {
			nav.toggleClass( 'toggled-on' );
		} );
	} )();

	/*
	 * Makes "skip to content" link work correctly in IE9 and Chrome for better
	 * accessibility.
	 *
	 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
	 */
	_window.on( 'hashchange.longform', function() {
		var element = document.getElementById( location.hash.substring( 1 ) );

		if ( element ) {
			if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) {
				element.tabIndex = -1;
			}

			element.focus();

			// Repositions the window on jump-to-anchor to account for header height.
			window.scrollBy( 0, -80 );
		}
	} );

	$( function() {

		/*
		 * Fixed header for large screen.
		 * If the header becomes more than 48px tall, unfix the header.
		 *
		 * The callback on the scroll event is only added if there is a header
		 * image and we are not on mobile.
		 */
		if ( _window.width() > 781 ) {
			var mastheadHeight = $( '#masthead' ).height(),
				toolbarOffset, mastheadOffset;

			if ( mastheadHeight > 48 ) {
				body.removeClass( 'masthead-fixed' );
			}

			if ( body.is( '.header-image' ) ) {
				toolbarOffset  = body.is( '.admin-bar' ) ? $( '#wpadminbar' ).height() : 0;
				mastheadOffset = $( '#masthead' ).offset().top - toolbarOffset;

				_window.on( 'scroll.longform', function() {
					if ( ( window.scrollY > mastheadOffset ) && ( mastheadHeight < 49 ) ) {
						body.addClass( 'masthead-fixed' );
					} else {
						body.removeClass( 'masthead-fixed' );
					}
				} );
			}
		}

		// Focus styles for menus.
		$( '.primary-navigation, .secondary-navigation' ).find( 'a' ).on( 'focus.longform blur.longform', function() {
			$( this ).parents().toggleClass( 'focus' );
		} );
	} );
} )( jQuery );

/*------------------------------------------------------------
 * FUNCTION: Scroll Page Back to Top
 * Used for ajax navigation scroll position reset
 *------------------------------------------------------------*/

function scrollPageToTop(){
	// Height hack for mobile/tablet
	jQuery('body').css('height', 'auto');
	jQuery("html, body").animate({ scrollTop: 0 }, "slow");

	// if( longform.device != 'desktop' ){
		// jQuery('body').scrollTop(0);
	// }else{
	// 	jQuery('.content-wrapper').scrollTop(0);
	// }

	jQuery('body').css('height', '');
}

(function() {

	if( jQuery('.header_chapter_wrapper').length != 0 ) {

		// Chapter open overlay functionality
		var triggerBttn = document.getElementById( 'trigger-chapters-overlay' ),
			overlay = document.querySelector( 'div.header_chapter_wrapper' ),
			closeBttn = overlay.querySelector( 'button.overlay-close' );

		function toggleOverlay() {
			if( jQuery('div.header_chapter_wrapper').hasClass( 'open' ) ) {
				jQuery('div.header_chapter_wrapper').removeClass( 'open' );
				jQuery('div.header_chapter_wrapper').addClass( 'o-close' );
			} else {
				jQuery('div.header_chapter_wrapper').removeClass( 'o-close' );
				jQuery('div.header_chapter_wrapper').addClass( 'open' );
			}
		}

		triggerBttn.addEventListener( 'click', toggleOverlay );
		closeBttn.addEventListener( 'click', toggleOverlay );
		
		jQuery('a.scroll-nav__link').live('click', function() {
			toggleOverlay();
		});
		// END - Chapter open overlay functionality

	}

	if ( jQuery('.header_highlight_wrapper').length != 0 ) {

		// Highlights open overlay functionality
		var triggerHBttn = document.getElementById( 'trigger-highlight-overlay' ),
			HOverlay = document.querySelector( 'div.header_highlight_wrapper' ),
			closeHBttn = HOverlay.querySelector( 'button.overlay-close' );

		function toggleHOverlay() {
			if( jQuery('div.header_highlight_wrapper').hasClass( 'open' ) ) {
				jQuery('div.header_highlight_wrapper').removeClass( 'open' );
				jQuery('div.header_highlight_wrapper').addClass( 'o-close' );
			} else {
				jQuery('div.header_highlight_wrapper').removeClass( 'o-close' );
				jQuery('div.header_highlight_wrapper').addClass( 'open' );
			}
		}

		triggerHBttn.addEventListener( 'click', toggleHOverlay );
		closeHBttn.addEventListener( 'click', toggleHOverlay );
		
		jQuery('a.scroll-nav__link').live('click', function() {
			toggleHOverlay();
		});
		// END - Highlughts open overlay functionality

	};

	// Story reading progress
	jQuery.fn.scrollProgress = function() {

		// progress element
		var prElement = document.createElement('progress');
		document.body.appendChild(prElement);

		// element state info
		var docOffset = jQuery(this).offset().top,
			elmHeight = jQuery(this).height(),
			winHeight = jQuery(window).height();

		jQuery(prElement).addClass('progress-bar');

		// initial value of progress element
		jQuery(prElement).attr('min', 0);
		jQuery(prElement).attr('max', 1);
		jQuery(prElement).attr('value', 0);

		// listen for scroll changes
		jQuery(window).on('scroll', function() {

			// docScroll     = relative window position to top of page
			// windowOffset  = relative position of element to top of window
			// viewedPortion = how much of the element has been seen / is visible
			var docScroll = jQuery(window).scrollTop(),
				windowOffset = docOffset - docScroll,
				viewedPortion = winHeight + docScroll - docOffset;

			// do max / min for proper percentages
			if(viewedPortion < 0) viewedPortion = 0;
			if(viewedPortion > elmHeight) viewedPortion = elmHeight;

			// calculate viewed percentage
			var viewedPercentage = viewedPortion / elmHeight;

			// set percent in progress element
			jQuery(prElement).attr('value', viewedPercentage);
			jQuery('.story-progress-bar').html((viewedPercentage * 100).toFixed(0) + '%');

		});

		// track window changes to make sure that values are consistent
		var self = this;
		jQuery(window).on('resize', function() {

			// update tracking values
			docOffset = jQuery(self).offset().top;
			elmHeight = jQuery(self).height();
			winHeight = jQuery(window).height();

			// trigger a scroll event to fix any potential issues
			jQuery(window).trigger('scroll');

		});

		// trigger scroll to render
		jQuery(window).trigger('scroll');

	};

	if ( jQuery(".single-post .site-content article .entry-content").length ) {
		jQuery(".single-post .site-content article .entry-content").scrollProgress();
	}
	if ( jQuery(".aesop-story-front .site-main article .entry-content").length ) {
		jQuery(".aesop-story-front .site-main article .entry-content").scrollProgress();
	}
	// END - Story reading progress

	// detect if IE : from http://stackoverflow.com/a/16657946		
	var ie = (function(){
		var undef,rv = -1; // Return value assumes failure.
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf('MSIE ');
		var trident = ua.indexOf('Trident/');

		if (msie > 0) {
			// IE 10 or older => return version number
			rv = parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		} else if (trident > 0) {
			// IE 11 (or newer) => return version number
			var rvNum = ua.indexOf('rv:');
			rv = parseInt(ua.substring(rvNum + 3, ua.indexOf('.', rvNum)), 10);
		}

		return ((rv > -1) ? rv : undef);
	}());


	// disable/enable scroll (mousewheel and keys) from http://stackoverflow.com/a/4770179					
	// left: 37, up: 38, right: 39, down: 40,
	// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
	var keys = [37, 38, 39, 40], wheelIter = 0;

	function preventDefault(e) {
		e = e || window.event;
		if (e.preventDefault)
		e.preventDefault();
		e.returnValue = false;  
	}

	function keydown(e) {
		for (var i = keys.length; i--;) {
			if (e.keyCode === keys[i]) {
				preventDefault(e);
				return;
			}
		}
	}

	function touchmove(e) {
		preventDefault(e);
	}

	function wheel(e) {
		// for IE 
		//if( ie ) {
			//preventDefault(e);
		//}
	}

	function disable_scroll() {
		window.onmousewheel = document.onmousewheel = wheel;
		document.onkeydown = keydown;
		document.body.ontouchmove = touchmove;
	}

	function enable_scroll() {
		window.onmousewheel = document.onmousewheel = document.onkeydown = document.body.ontouchmove = null;  
	}

	var docElem = window.document.documentElement,
		scrollVal,
		isRevealed, 
		noscroll, 
		isAnimating;

	function scrollY() {
		return window.pageYOffset || docElem.scrollTop;
	}

	function scrollPage() {
		scrollVal = scrollY();
		
		if( noscroll && !ie ) {
			if( scrollVal < 0 ) return false;
			// keep it that way
			window.scrollTo( 0, 0 );
		}

		if( jQuery('body').hasClass( 'notrans' ) ) {
			jQuery('body').removeClass( 'notrans' );
			return false;
		}

		if( isAnimating ) {
			return false;
		}
		
		if( scrollVal <= 0 && isRevealed ) {
			toggle(0);
		}
		else if( scrollVal > 0 && !isRevealed ){
			toggle(1);
		}
	}

	function toggle( reveal ) {
		isAnimating = true;
		
		if( reveal ) {
			jQuery('body').addClass( 'modify' );
		}
		else {
			noscroll = true;
			disable_scroll();
			jQuery('body').removeClass( 'modify' );
		}

		// simulating the end of the transition:
		setTimeout( function() {
			isRevealed = !isRevealed;
			isAnimating = false;
			if( reveal ) {
				noscroll = false;
				enable_scroll();
			}
		}, 600 );
	}

	if( !/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

		// refreshing the page...
		var pageScroll = scrollY();
		noscroll = pageScroll === 0;

		disable_scroll();

		if( pageScroll ) {
			isRevealed = true;
			jQuery('body').addClass( 'notrans' );
			jQuery('body').addClass( 'modify' );
		}

		if ( jQuery('body').hasClass('single-post') || jQuery('body').hasClass('aesop-story-front') ) {
			window.addEventListener( 'scroll', scrollPage );
		}
	} else if ( jQuery('body').hasClass('single-post') && /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		jQuery('body').addClass( 'notrans' );
		jQuery('body').addClass( 'modify' );
	}
	
})();
