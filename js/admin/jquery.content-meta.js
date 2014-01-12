/**
 */
var Content_Meta = function (options) {
	var defaults = {
		content_id: config.content.data('id'),
		name: 'tag',
	}
	this.options = $.extend(defaults, options);
};


/**
 * performs an ajax call to the content meta model
 * @param  {string}   action   create | delete
 * @param  {array}   ids      
 * @param  {Function} callback what do do once complete
 * @return {function}            callback
 */
Content_Meta.prototype.modify = function(event, action, ids, callback) {
	$.ajax({
		url: config.url.adminAjax + 'content/meta/' + action,
		type: 'get',
		data: {
			content_id: this.options.content_id,
			name: this.options.name,
			values: ids,
		},
		success: function() {
			callback.call(event);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			alert('error while performing Content_Meta.prototype.ajaxmethod');
		},
	});
};
