/**
 * interfaces with the tag table for the admin area
 * dependancy $
 */
var Model_Tag = function (options) {
	var defaults = {
		template: 'default'
	}
	this.options = $.extend(defaults, options);
	this.dropDown = $('.js-form-tag-drop');
	this.attachedTagContainer = $('.js-tag-attached');
	this.searchField = $('.js-tag-input-search');
	this.timer = 0;
	this.data = this;
	if (options.template == 'create-update') {
		
		// typing generally in tag field
		// passing this through as event data
		this.searchField
			.off('keyup.modelTag')
			.on('keyup.modelTag', this, function (event) {
				event.data.keyupSearchField(event, $(this));
			});

		// setup already attached tags to be removed
		this.refreshEventAttachedTags(this);
	};
	if (options.template == 'default') {

	};
};


/**
 * replenish the attached tags event
 */
Model_Tag.prototype.refreshEventAttachedTags = function(event) {
	event.data.attachedTagContainer.find('.js-tag')
		.off('click.modelTag')
		.on('click.modelTag', function (currentEvent) {
			currentEvent.preventDefault();
			event.data.aRemove(event, $(this));
		});
};


/**
 * attaches a tag when clicked in the dropdown
 * @param {object} button 
 */
Model_Tag.prototype.create = function(data) {
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
			result.appendTo(event.data.attachedTagContainer);
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
Model_Tag.prototype.search = function(event, query) {
	$.get(
		config.url.adminAjax + 'tag/search',
		{
			query: query
		},
		function(result) { 
			if (result) {
				event.data.dropDown
					.removeClass('hidden')
					.html(result);
				$(event.data.dropDown.selector).find('.js-tag').on('click.modelTag', function (thisEvent) {
					thisEvent.preventDefault();
					event.data.aAdd(event, $(this));
				});
			}
		}
	);
};


/**
 * removes a tag when clicked in the admin area
 * @param {object} button 
 */
Model_Tag.prototype.aRemove = function(event, tag) {
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
Model_Tag.prototype.aAdd = function(event, tag) {
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
			tag.appendTo(event.data.attachedTagContainer);
			event.data.refreshEventAttachedTags(event);
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		}
	});

	// no more tags in the dropdown
	if (! event.data.dropTags) {
		event.data.dropDown.html('');
	};

	// empty the searchfield
	event.data.searchField.val('');
};


/**
 * hitting keys when in the search field
 */
Model_Tag.prototype.keyupSearchField = function(event, field) {

	// clear timer always
    clearTimeout(event.data.timer);
	event.data.dropDown.html('');

	// enter key
	if (event.which == 13) {
		event.data.create({
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
	event.data.timer = setTimeout(function() {
		event.data.search(event, field.val());
	}, 300);
};
