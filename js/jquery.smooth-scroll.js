var Smooth_Scroll = function (options) {
	var defaults = {
		target: '.js-smooth-scroll',

		// offsets the scroll down if there is a
		// fixed header for example
		topOffset: 50,
		scrollSpeed: 500,
		activeClass: 'has-smooth-scroll',
		direct: ''
	};
	this.options = $.extend(defaults, options);
	this.cache = {
		target: $(this.options.target)
	};
	var firstEvent = {};
	firstEvent.data = this;

	// clicking a targeted item
	firstEvent.data.cache.target.off('click.smoothScroll').on('click.smoothScroll', firstEvent.data, function(event) {
		event.preventDefault();
		firstEvent.data.scrollTo(firstEvent, this.hash);
	});

	// immediatly scroll to
	if (firstEvent.data.options.direct) {
		firstEvent.data.scrollTo(firstEvent, firstEvent.data.options.direct);
	};
};


/**
 * takes a clicked button and smooth scrolls to its hash
 * target is the jquery selector string
 * @param  {object} event
 * @param  {string} target
 */
Smooth_Scroll.prototype.scrollTo = function(event, target) {
	var timer = 0;
	var destination = 0;
	if ($(target).offset().top > $(document).height() - $(window).height()) {
		destination = $(document).height() - $(window).height();
	} else {
		destination = $(target).offset().top;
		destination = destination - event.data.options.topOffset;
	}
	$('html, body').animate({scrollTop:destination}, event.data.options.scrollSpeed, 'swing');

	// add class to target, remove it after timeout
	$('.' + event.data.options.activeClass).removeClass(event.data.options.activeClass);
	clearTimeout(timer);
	target = $(target);
	target.addClass(event.data.options.activeClass);
	timer = setTimeout(function() {
		target.removeClass(event.data.options.activeClass);
	}, 1000);
};
