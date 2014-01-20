/**
 * rules
 * all blocks of functionaltiy seperated into 'module**'
 */
$(document).ready(function() {
	config.setup();

	// form submission
	$('form').find('a.submit').on('mouseup', setSubmit);
	var modelMedia = new Model_Media();

	// general logic seperation
	if (config.documentBody.hasClass('admin-media') || config.content.hasClass('content-create-update')) {
		var modelMedia = new Model_Media();
		modelMedia.setEvent();
	};

	// lightbox
	$('.js-lightbox-media-browser').lightbox({
		inline: true
		, maxWidth: 800
		, className: 'media-browser'
		, onComplete: modelMedia.setEvent
	});

	// try adding all logic for manipulating objects here...
	if (config.content.hasClass('content-create-update')) {
		moduleContentCreateUpdate();
	};

	// header always following on scroll
	$('.js-header-main').scrollFollow();

	// watch for dismissers
	var dismiss = new Dismiss();
});


/**
 * content create update functionality
 */
function moduleContentCreateUpdate () {
	
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
