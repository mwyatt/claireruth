/**
 * tag management, search, add, remove
 */
(function($) {
    $.fn.tags = function(options) {
		var core = this;
		var defaults = {
			timer: 0
		}
		var options = $.extend(defaults, options);
		if (! $(core).length) {
			return;
		}
		events();
		function events() {

			// hit enterkey to submit input as new tag
			$(core).find('#form-tag-search').off().on('keypress', function(e) {
				if (e.which == 13) {
					clearDrop();
			       	$('.tags').append('<div class="tag">' + $(this).val() + '</div>');
					addHiddenField($(this).val());
			       	$(this).val('');
					events();
				    return false;
			    }
			});
			$(core).find('#form-tag-search').on('keyup', function(e) {
				var query = $(this).val();
				clearTimeout(options.timer);
				if ($(this).val().length > 1) {
					options.timer = setTimeout(function() {
						poll(query);
					}, 300);
				}
			});
			$(core).find('.tags .tag').off().on('click', function() {
				removeTag(this);
			});
			$(core).find('.drop .tag').off().on('click', function() {
				addTag(this);
			});
		}
		function addHiddenField(name) {
			$(core).find('.tags').append('<input name="tag[]" type="hidden" value="' + name + '">');
		}
		function poll(query) {
			$.get(
				url.base + 'ajax/tag-management/',
				{
					query: query
				},
				function(result) { 
					clearDrop();
					if (! $('#form-tag-search').val()) {
						return;
					};
					if (result) {
						$(core).find('.area').append('<div class="drop">' + result + '</div>');
					}
					events();
				}
			);
		}
		function clearDrop() {
			$(core).find('.drop').remove();
		}
		function addTag(button) {
			$(button).appendTo($(core).find('.tags'));
			addHiddenField($(button).html());
			events();
			if (! $(core).find('.drop .tag').length) {
				clearDrop();
			};
			$('#form-tag-search').val('');
		}
		function removeTag(button) {
			$('input[type="hidden"][value="' + $(button).html() + '"]').remove();
			$(button).remove();
		}
    }
})(jQuery);
