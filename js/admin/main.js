/**  
 * rules
 * all blocks of functionaltiy seperated into 'module**'
 */
$(document).ready(function() {
	config.setup();

	// form submission
	$('form').find('a.submit').on('mouseup', setSubmit);

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
	var modelMediaBrowser = new Model_Media_Browser();

	// tag
	var modelTag = new Model_Tag({
		template: 'create-update'
	});
};
