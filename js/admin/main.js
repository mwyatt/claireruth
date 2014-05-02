/**  
 * rules
 * all blocks of functionaltiy seperated into 'module**'
 */
$(document).ready(function() {
	config.setup();

	// form submission
	setSubmit();

	// try adding all logic for manipulating objects here...
	if (config.content.hasClass('content-create-update')) {
		var editor = new wysihtml5.Editor('form_html', {
			toolbar: 'toolbar'
			, parserRules: wysihtml5ParserRules
			, useLineBreaks: false
		});
		var modelMediaBrowser = new Model_Media_Browser();
		var modelTagBrowser = new Model_Tag_Browser();
		var modelContentSlug = new Model_Content_Slug();
	};

	// header always following on scroll
	$('.js-header-main').scrollFollow();

	// watch for dismissers
	var dismiss = new Dismiss();
});
