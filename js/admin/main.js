
/**
 * ready!
 */
$(document).ready(function() {
	var urlBase = $('body').data('url-base');

	// getscripts
	$.when(
	    $.getScript(urlBase + 'js/reusable.js')
	    , $.getScript(urlBase + 'js/jquery.lightbox.js')
	    , $.getScript(urlBase + 'js/admin/jquery.mediabrowser.js')
	    , $.getScript(urlBase + 'js/admin/jquery.tags.js')
	    , $.getScript(urlBase + 'js/admin/app.js')
	).done(function() {
		console.log('js loaded');
	});
});
