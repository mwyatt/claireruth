/**
 * interfaces with tag table
 * @return {object} 
 */
Model_Tag = (function () {
	var module = function () {};
	var dropDown = $('.js-tag-drop');


	/**
	 * sets up core selections 
	 */
	module.prototype.setElement = function() {
		this.searchField = $('.js-tag-input-search');
		this.dropDown = $('.js-tag-drop');
		this.dropTags = this.dropDown.find('.js-tag');
		this.attachedTagContainer = $('.js-tag-attached');
		this.attachedTags = this.attachedTagContainer.find('.js-tag');
		this.timer = 0;
	}
	

	/**
	 * perform ajax search and return result
	 * @param  {string} query
	 */
	module.prototype.search = function(query) {
		$.get(
			url.ajax + 'tag/search',
			{
				query: query
			},
			function(result) { 
				if (result) {
					dropDown.html(result);
				}
			}
		);
	}
	

	/**
	 * attaches a tag when clicked in the dropdown
	 * @param {object} button 
	 */
	module.prototype.create = function(data) {
		$.get(
			url.ajax + 'tag/create'
			, {
				title: data.title
				, description: data.description
			}
			, function(result) { 
				data.callback.call();
			}
		);
	}


	/**
	 * removes a tag when clicked in the admin area
	 * @param {object} button 
	 */
	module.prototype.clickRemove = function() {
		var button = $(this);
		$.ajax({
			url: url.ajax + 'content/meta/delete'
			, type: 'get'
			, data: {
				content_id: $('.content').data('id')
				, name: 'tag'
				, value: $(this).data('id')
			}
			, success: function (result) {
				button.remove();
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	}

	// methods
	module.prototype.setElement();
	return module;
})();
