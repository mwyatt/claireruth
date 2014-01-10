/**
 * interfaces with content_meta table
 * @todo  needs to be converted to module
 * @return {object} 
 */
Model_Content_Meta = (function () {
	var module = function () {};

	module.prototype.create = function(data) {
		$.get(
			config.url.ajax + 'content/meta/create'
			, {
				content_id: data.content_id
				, name: data.name
				, value: data.value
			}
			, function(result) { 
				data.callback.call();
			}
		);
	}

	// methods
	return module;
})();
