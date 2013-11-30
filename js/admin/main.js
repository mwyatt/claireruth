// init global variables
var url = {
	base: '',
	js: '',
	ajax: ''
};


function contentCreateUpdate () {
	
	// html5wysi
	var editor = new wysihtml5.Editor('form_html', {
		toolbar: 'toolbar'
		, parserRules: wysihtml5ParserRules
		, useLineBreaks: false
	});

	// tie in content meta
	var modelContentMeta = new Model_Content_Meta();

	// tag
	var modelTag = new Model_Tag({
		template: 'create-update'
	});
}

/**
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {

	// cache
	var content = $('.content');
	var body = $('body');

	// url helpers
	url.base = body.data('url-base');
	url.js = url.base + 'js/';
	url.ajax = url.base + 'admin/ajax/';

	// prevent ajax cache
	$.ajaxSetup ({  
		cache: false  
	});

	// form submission
	$('form').find('a.submit').on('mouseup', setSubmit);

	// general logic seperation
	if (body.hasClass('admin-media')) {
		var modelMedia = new Model_Media();
		modelMedia.setEvent();
	};

	// try adding all logic for manipulating objects here...
	if (content.hasClass('content-create-update')) {
		contentCreateUpdate();
	};

	var modelMedia = new Model_Media();

	// lightboxes
	$('.js-lightbox-media-browser').lightbox({
		inline: true
		, maxWidth: 800
		, className: 'media-browser'
		, onComplete: modelMedia.setEvent
	});

	// header always following on scroll
	$('.js-header-main').scrollFollow();
});
