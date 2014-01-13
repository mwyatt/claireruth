/**
 * scours for 'js-dismiss' and attaches an event
 * which calls ajax, wipes the session variable and hides the element
 */
var Dismiss = function (options) {
	var defaults = {};
	this.options = $.extend(defaults, options);
	this.data = this;
	this.timer = 0;
	this.dismissElement = $('.js-dismiss');
	timerData = this;

	// dont act if nothing there
	if (! this.dismissElement.length) {
		return;
	};

	// timed remove 30 sec
	this.timer = setTimeout(function(event) {
		timerData.wipeSession(event, timerData.dismissElement);
	}, 1000*30);

	// event
	this.dismissElement.on('click', this, function(event) {
		event.preventDefault();
		event.data.wipeSession(event, $(this))
	});

	// hover to remain
	this.dismissElement.on('hover', function(event) {
		clearTimeout(this.timer);
	});
};


/**
 * will wipe the session key
 * @param  {object} event       
 * @param  {$} thisElement 
 */
Dismiss.prototype.wipeSession = function(event, thisElement) {
	$.ajax({
		url: config.url.ajax + 'dismiss/',
		success: function () {
			thisElement.remove();
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert('error while dismissing');
		},
	});
}
