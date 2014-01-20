/**
 * watches the scrollwindow and displays a to top button when moving down
 * over a threshold
 * dependancy $
 */
var Button_To_Top = function (options) {
	var defaults = {
		threshold: 300,
		button: '.null',
		classLabel: 'is-active',
		delay: 200
	};
	this.options = $.extend(defaults, options);
	var timer = 0;
	var thisPlugin = event.data = this;
	$(window).scroll(function(event) {
		clearTimeout(timer);
		timer = setTimeout(function(event) {
			thisPlugin.poll(event);
		}, thisPlugin.options.delay);
	});
};


Button_To_Top.prototype.poll = function(event) {
	documentPosition = $(document).scrollTop();
	if (documentPosition > event.data.options.threshold) {
		$(event.data.options.button).fadeIn().addClass('.' + event.data.options.classLabel);
	} else {
		$(event.data.options.button).fadeOut().removeClass('.' + event.data.options.classLabel);
	}
};
