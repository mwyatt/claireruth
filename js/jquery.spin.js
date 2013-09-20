/**
 * adds a spinny to the selected element
 */
(function($) {
    $.fn.spin = function(options) {
		var core = this;
		var defaults = {}
		var options = $.extend(defaults, options);
		$(core).html('<div class="ajax"></div>');
})(jQuery);
