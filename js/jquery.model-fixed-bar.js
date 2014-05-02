var Model_Fixed_Bar = function (options) {
	var defaults = {
		target: '.js-fixed-bar'
	}
	this.options = $.extend(defaults, options);
	this.cache = {
		// fieldSlug: '.js-input-slug',
		// fieldTitle: '.js-input-title'
	};
	this.timer;
	this.events(this);
};


Model_Fixed_Bar.prototype.events = function(data) {
	$(window).on('scroll.fixed-bar', function(event) {
		clearTimeout(data.timer);
		if (! $(window).scrollTop()) {
			return $(data.options.target).removeClass('is-scrolling');
		};
	    data.timer = setTimeout(function() {
	    	$(data.options.target).addClass('is-scrolling');
	    }, 0);
	});
};
