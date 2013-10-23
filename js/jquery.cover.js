/**
 * takes control of a list of items and makes them scrollable fun
 * functions this can perform:
 * scroll between banners using speed option
 */
(function($){
	$.fn.cover = function(options) {
		if (! this.length) return;
		var core = this;
		var defaults = {
			speed: 6000
			, fadeSpeed: 200
		}
		var options = $.extend(defaults, options);
		var interval = {
			timer: 0
			, start: function() {
				clearTimeout(interval.timer);
				interval.timer = setInterval(next, options.speed);
			}
			, stop: function() {
				clearTimeout(interval.timer);
				interval.timer = setInterval(next, options.speed);
			}
		}
		var info = {
			total: $(core).find('a').length
			, current: 0
			, next: 0
		}
		if (info.total > 1) {
			start();
		};
		function start() {
			$.each($(core).find('a'), function(index) {
				if (index > 0) {
					$(this).addClass('hide');
				} else {
					$(this).removeClass('bring-out');
					$(this).addClass('bring-in');
				}
			});
			interval.start();
		}
		function next() {
			info.current += 1;
			if (info.current > info.total) {
				info.current = 0;
			};
			$.each($(core).find('a'), function(index) {
				if (info.current == index) {
					$(this).removeClass('bring-out').removeClass('hide').addClass('bring-in');
				} else {
					$(this).removeClass('bring-in').addClass('bring-out');
				}
			});
		}
	};
})(jQuery);
