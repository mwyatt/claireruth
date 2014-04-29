/**
 * interfaces with the tag table for the admin area
 * dependancy $
 */
var Model_Tag_Browser = function (options) {
	var defaults = {}
	this.options = $.extend(defaults, options);
	this.cache = {
		tagBrowser: '.js-browser-tag',
		tagInputSearch: '.js-browser-tag-input-search',
		tag: '.js-tag',
		// dropPosition: '.js-drop-position',
		drop: '.js-drop',
		// dropInner: '.js-drop-inner',
		tagAttached: '.js-browser-tag-attached'
	};
	this.events(this);
};


Model_Tag_Browser.prototype.events = function(data) {
	$(data.cache.tagAttached).find(data.cache.tag)
		.off('click')
		.on('click', function(event) {
			event.preventDefault();
			data.aRemove(event, $(this));
		});
	$(data.cache.tagInputSearch)
		.off('keyup')
		.on('keyup', this, function() {
			data.keyupSearchField(data, $(this));
		});
};


/**
 * calls to see if tag exists, if not create and attach
 * otherwise attach
 * @param {object} button 
 */
Model_Tag_Browser.prototype.createPossibly = function(data) {
	$.ajax({
		url: config.url.adminAjax + 'tag/create'
		, data: {
			title: data.title
			, description: data.description
		}
		, type: 'get'
		, success: function (result) {
			if (! result) {
				return;
			};
			result.appendTo(data.attachedTagContainer);
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		}
	});
};


/**
 * perform ajax search and return result
 * @param  {string} query
 */
Model_Tag_Browser.prototype.search = function(data, query) {
	$.ajax({
		url: config.url.adminAjax + 'tag/search'
		, data: {
			query: query
		}
		, type: 'get'
		, success: function (result) {
			if (result) {
				data.dropDown
					.removeClass('hidden')
					.html(result);
				$(data.dropDown.selector).find('.js-tag').on('click', function (thisEvent) {
					thisEvent.preventDefault();
					data.aAdd(event, $(this));
				});
			}
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		}
	});
};


/**
 * removes a tag when clicked in the admin area
 * @param {object} button 
 */
Model_Tag_Browser.prototype.aRemove = function(data, tag) {
	var contentMeta = new Content_Meta({
		name: 'tag'
	});
	contentMeta.modify(event, 'delete', [tag.data('id')], function() {
		tag.remove();
	});
};


/**
 * clicking a dropdown tag to attach
 * depentant on meta
 */
Model_Tag_Browser.prototype.aAdd = function(event, tag) {
	var tags = [tag.data('id')];

	// create content association
	$.ajax({
		url: config.url.adminAjax + 'content/meta/create'
		, type: 'get'
		, data: {
			content_id: config.content.data('id')
			, name: 'tag'
			, values: tags
		}
		, success: function (result) {
			
			// move the button to the attached area
			tag.appendTo(data.attachedTagContainer);
			data.refreshEventAttachedTags(data);
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		}
	});

	// no more tags in the dropdown
	if (! data.dropTags) {
		data.dropDown.html('');
	};

	// empty the searchfield
	data.searchField.val('');
};


/**
 * hitting keys when in the search field
 */
Model_Tag_Browser.prototype.keyupSearchField = function(event, field) {
	data.dropDown.html('');

	// enter key
	if (event.which == 13) {
		data.create({
			title: field.val()
			, description: ''
		});
		event.preventDefault();
    }

    // handle search terms if long enough
    if (field.val().length < 2) {
    	return;
    }

    // timeout for search
	data.timer = setTimeout(function() {
		data.search(event, field.val());
	}, 300);
};
