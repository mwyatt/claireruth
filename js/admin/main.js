var ajax = '<div class="ajax"></div>';

// shows the feedback message if required
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

function removeModals() {
	$('*').removeClass('active');
}

// submits a form if required
function formSubmit(e, button) {
	$(button).closest('form').submit();
	e.preventDefault();
}

function clickUser() {
	user.addClass('active');
}

$(document).ready(function() {
	var url = $('body').data('url-base');
	$.ajaxSetup ({  
		cache: false
	});
	feedback.init();
	$('form').find('.button.submit').on('click', function(e) {
		formSubmit(e, this);
	});

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
	$('body').keyup(function(e) {
		if (e.keyCode == 27) {
			removeModals();
			$('.drop').remove();
		}
	});	
	if ($('.content.gallery').length) {
		$('.file').magnificPopup({type:'image'});
	}
	var user = $('header.main').find('.user');
	user.find('a').on('click', clickUser);

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

	// getscripts
	$.when(
	    $.getScript(url + 'js/admin/jquery.mediabrowser.js')
	    , $.getScript(url + 'js/admin/jquery.tags.js')
	    , $.getScript(url + 'js/jquery.lightbox.js')
	    // , $.getScript(url + 'js/jquery.spin.js')
	    , $.Deferred(function(deferred){
			console.log('getscript has failed');
	        $(deferred.resolve);
	    })
	).done(function(){
		console.log('getscript was successful');

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
	});
});
