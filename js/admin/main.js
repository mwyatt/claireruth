/**
 * app
 */
function app () {

	var feedback = {
		container: false,
		speed: 'fast',

		init: function() {
			feedback.container = $('.feedback');
			$(feedback.container).on('click', feedback._click);
		},

		_click: function() {
			$(this).fadeOut(feedback.speed);
			// setTimeout(showFeedback, 1000);
			// function showFeedback() {
			// 	feedback.fadeIn(animationSpeed);
			// 	setTimeout(hideFeedback, 10000);
			// }
			// function hideFeedback() {
			// 	// feedback.fadeOut(animationSpeed);
			// }
		}
	}	
	feedback.init();
	function clickUser() {
		user.addClass('active');
	}
	user.find('a').on('click', clickUser);
	if (
		$('.content.create').length
		|| $('.content.update').length
	) {
		var editor = new wysihtml5.Editor("form_html", {
		  toolbar:        "toolbar",
		  parserRules:    wysihtml5ParserRules,
		  useLineBreaks:  false
		});
	}
	var websiteTitle = $('header.main').find('.title').find('a');
	websiteTitleText = $('header.main').find('.title').find('a').html();
	websiteTitle.hover(function over() {
		var text = $(this).html();
		text = 'Open ' + text + ' Homepage';
		$(this).html(text);
	},
	function out() {
		$(this).html(websiteTitleText);
	});

	// tags
	$('.management-tag').tags();

	// media browser
	$('.media-browser').mediaBrowser();
	if ($('.content.media.gallery').length) {
		$('.media-browser').mediaBrowser({
			defaultDirectory: 'gallery/'
		});
	}

	// lightboxes
	$('.js-lightbox-media-browser').lightbox({
		inline: true
		, className: 'media-browser'
		, onComplete: $.fn.mediaBrowser
	});	
}


/**
 * ready!
 */
$(document).ready(function() {

	// getscripts
	$.when(
	    $.getScript(url.base + 'js/reusable.js')
	    , $.getScript(url + 'js/jquery.lightbox.js')
	    , $.getScript(url + 'js/admin/jquery.mediabrowser.js')
	    , $.getScript(url + 'js/admin/jquery.tags.js')
	).done(app);
});
