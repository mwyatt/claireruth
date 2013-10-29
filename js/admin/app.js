/**
 * hovering which adds a class
 * start simple!
 * js-hover-addclass
 * 		js-hover-addclass-trigger
 * 		js-hover-addclass-drop
 */
(function ($) {
	var hoverClass = 'is-open';
	var container = $('.js-hover-addclass');
	var trigger = $(container).find('.js-hover-addclass-trigger');
	var drop = $(container).find('.js-hover-addclass-drop');

	// module not required
	if (! container.length || trigger.length || drop.length) {
		return;
	}
	setEvents();

	// standard function for setting events
	function setEvents () {
		console.log('value');
		$(trigger)
			.off('hover', open)
			.on('hover', open);
	}

	function open () {
		removeClass(hoverClass);
		$(drop).addClass('.' + hoverClass);
	}
}(jQuery));


/**
 * adds a class to an element when it goes past a certain point on the screen
 */
(function($){
	$.fn.scrollFollow = function(options) {
		if (! this.length) return;
		var defaults = {
			timer: 0
			, delay: 50
			, followAfter: 0
		}
		var options = $.extend(defaults, options);
		var element = $(this);

		// start checking for the scrolling
		$(window).scroll(function() {
			clearTimeout(options.timer);
			options.timer = setTimeout(updateClass, options.delay);
		});


		/**
		 * updates the class periodically
		 */
		function updateClass () {
			if ($(window).scrollTop() > Math.floor(options.followAfter)) {
				$(element).addClass('is-fixed-top');
			} else {
				$(element).removeClass('is-fixed-top');
			}
		}
	}
})(jQuery);



// var feedback = {
// 	container: false,
// 	speed: 'fast',

// 	init: function() {
// 		feedback.container = $('.feedback');
// 		$(feedback.container).on('click', feedback._click);
// 	},

// 	_click: function() {
// 		$(this).fadeOut(feedback.speed);
// 		// setTimeout(showFeedback, 1000);
// 		// function showFeedback() {
// 		// 	feedback.fadeIn(animationSpeed);
// 		// 	setTimeout(hideFeedback, 10000);
// 		// }
// 		// function hideFeedback() {
// 		// 	// feedback.fadeOut(animationSpeed);
// 		// }
// 	}
// }	
// feedback.init();
// function clickUser() {
// 	user.addClass('active');
// }
// user.find('a').on('click', clickUser);
// if (
// 	$('.content.create').length
// 	|| $('.content.update').length
// ) {
// 	var editor = new wysihtml5.Editor("form_html", {
// 	  toolbar:        "toolbar",
// 	  parserRules:    wysihtml5ParserRules,
// 	  useLineBreaks:  false
// 	});
// }
// var websiteTitle = $('header.main').find('.title').find('a');
// websiteTitleText = $('header.main').find('.title').find('a').html();
// websiteTitle.hover(function over() {
// 	var text = $(this).html();
// 	text = 'Open ' + text + ' Homepage';
// 	$(this).html(text);
// },
// function out() {
// 	$(this).html(websiteTitleText);
// });

// // tags
// $('.management-tag').tags();

// // media browser
// $('.media-browser').mediaBrowser();
// if ($('.content.media.gallery').length) {
// 	$('.media-browser').mediaBrowser({
// 		defaultDirectory: 'gallery/'
// 	});
// }

/**
 * application logic, use of object and functions
 */

// lightboxes
$('.js-lightbox-media-browser').lightbox({
	inline: true
	, className: 'media-browser'
	, onComplete: $.fn.mediaBrowser
});	

// header always following on scroll
$('.js-header-main').scrollFollow();
