/**
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {
	var urlBase = $('body').data('url-base');
	var urlBaseJs = urlBase + 'js/';

	// getscripts
	$.when(
	    $.getScript(urlBaseJs + 'reusable.js')
	    , $.getScript(urlBaseJs + 'jquery.lightbox.js')
	    , $.getScript(urlBaseJs + 'admin/jquery.mediabrowser.js')
	    , $.getScript(urlBaseJs + 'admin/jquery.tags.js')
	    , $.getScript(urlBaseJs + 'admin/app.js')
	).done(function() {
		console.log('all js imported');
	});
});
