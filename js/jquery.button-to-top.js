/**
 * watches the scrollwindow and displays a to top button when moving down
 * over a threshold
 * dependancy $
 */
var Button_To_Top = function (options) {
	var defaults = {
		threshold: 100,
		button: '.null',
		classLabel: 'is-active',
		delay: 200
	};
	this.options = $.extend(defaults, options);
	this.timer;
	this.events(this);
};


Button_To_Top.prototype.events = function(data) {
	var button = $(data.options.button);
	$(window).on('scroll.button-to-top', function(event) {
		clearTimeout(data.timer);
		data.timer = setTimeout(function(event) {
			documentPosition = $(document).scrollTop();
			if (documentPosition > data.options.threshold) {
				button.addClass(data.options.classLabel);
			} else {
				button.removeClass(data.options.classLabel);
			}
		}, data.options.delay);
	});
};
