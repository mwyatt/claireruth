var ajax = '<div class="ajax"></div>';

var url = {
	base: '',
	query: false,

	initialise: function() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('&') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
		    hash = hashes[i].split('=');
		    vars.push(hash[0]);
		    vars[hash[0]] = hash[1];
		}
		url.query = vars;
	},

	getPart: function(part) {
		if (part in url.query) {
			return url.query[part];
		}
		return false;
	}
}

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


function formSubmit(e, button) {
	$(button).closest('form').submit();
	e.preventDefault();
}


$(document).ready(function() {
	url.base = $('body').data('url-base');
	$.ajaxSetup ({  
		cache: false
	});
	feedback.init();
	$('form').find('a.submit').on('click', function(e) {
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
	// $('body').mouseup(function(e) {
	// 	removeModals();
	// 	if ($(e.target).closest('.drop').length == 0) {
	// 		$('.drop').remove();
	// 	}
	// });	
	$('body').keyup(function(e) {
		if (e.keyCode == 27) {
			removeModals();
			$('.drop').remove();
		}
	});	
	function removeModals() {
		$('*').removeClass('active');
	}
	if ($('.content.gallery').length) {
		$('.file').magnificPopup({type:'image'});
	}
	var user = $('header.main').find('.user');
	user.find('a').on('click', clickUser);
	function clickUser() {
		user.addClass('active');
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

	// plugins
	$.getScript(url.base + 'js/jquery.lightbox.js?rev=1', function(data, textStatus, jqxhr) {
		$('.js-lightbox-media-browser').lightbox({
			inline: true
			, className: 'media-browser'
			, onComplete: $.fn.mediaBrowser
		});
	});
	$.getScript(url.base + 'js/admin/jquery.tags.js?rev=1', function(data, textStatus, jqxhr) {
		$('.management-tag').tags();
	});
	$.getScript(url.base + 'js/admin/jquery.mediabrowser.js?rev=1', function(data, textStatus, jqxhr) {
		$('.media-browser').mediaBrowser();
		if ($('.content.media.gallery').length) {
			$('.media-browser').mediaBrowser({
				defaultDirectory: 'gallery/'
			});
		}
	});
});
