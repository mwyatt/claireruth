/**
 * media browser
 */
var Model_Media_v2 = function (options) {
	var defaults = {
		// template: 'default'
	}
	this.options = $.extend(defaults, options);
};


Model_Media_v2.prototype.refreshBrowser = function(event) {
	$('.js-media-browser-directory').html(config.spinner);
	$.ajax({
		url: config.url.ajax + 'media/read/'
		data: {},
		dataType: 'html',
		success: function(result) {
			if (result) {
				$(mediaBrowser.directory).html(result);
			} else {
				$(mediaBrowser.directory).html('<p class="p1">Nothing in this folder yet.</p>');
			}
			mediaBrowser.getTree();
		}
	});
};
