/**
 * update content slug
 */
var Model_Content_Slug = function (options) {
	var defaults = {}
	this.options = $.extend(defaults, options);
	this.cache = {
		fieldSlug: '.js-input-slug',
		fieldTitle: '.js-input-title'
	};
	this.timer;
	this.events(this);
};


Model_Content_Slug.prototype.getSlug = function(data, field) {
	$.ajax({
		url: config.url.adminAjax + 'content/slug',
		data: {
			title: field.val()
		},
		dataType: 'html',
		success: function(result) {
			$(data.cache.fieldSlug).val(result)
		}
	});
};


Model_Content_Slug.prototype.events = function(data) {
	$(data.cache.fieldTitle)
		.off()
		.on('keyup', function(event) {
			clearTimeout(data.timer);
			var field = $(this);
		    if (! field.val().length) {
		    	return;
		    }
		    data.timer = setTimeout(function() {
		    	data.getSlug(data, field);
		    }, 500);
		});
};
