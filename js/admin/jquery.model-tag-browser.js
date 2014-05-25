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
		dropPosition: '.js-drop-position',
		drop: '.js-drop',
		// dropInner: '.js-drop-inner',
		tagAttached: '.js-browser-tag-attached'
	};
	this.timer;
	this.refreshHidden(this);
	this.events(this);
};


Model_Tag_Browser.prototype.events = function(data) {
	$(data.cache.tagAttached).find(data.cache.tag)
		.off('click')
		.on('click', function(event) {
			event.preventDefault();
			$(this).remove();			
			data.refreshHidden(data);
		});
	$(data.cache.drop).find(data.cache.tag)
		.off('click')
		.on('click', function (event) {
			event.preventDefault();
			$(this).clone().appendTo(data.cache.tagAttached);
			$(data.cache.dropPosition).remove();
			data.refreshHidden(data);
			data.events(data);
		});
	$(data.cache.tagInputSearch)
		.off('keypress')
		.on('keypress', function(event) {
			clearTimeout(data.timer);
			$(data.cache.dropPosition).remove();
			var code = event.keyCode || event.which; 
			var field = $(this);

			// enter key
			if (code == 13) {
				data.createPossibly(data, field);
				event.preventDefault();
				return false;
		    }

		    // handle search terms if long enough
		    if (field.val().length < 2) {
		    	return;
		    }
		    data.timer = setTimeout(function() {
		    	data.search(data, field.val());
		    }, 500);
		});
};


/**
 * calls to see if tag exists, if not create and attach
 * otherwise attach
 * @param {object} button 
 */
Model_Tag_Browser.prototype.createPossibly = function(data, field) {
	$.ajax({
		url: config.url.adminAjax + 'tag/create'
		, data: {
			title: field.val()
			, description: ''
		}
		, type: 'get'
		, success: function (result) {
			$(data.cache.tagInputSearch).val('');
			$(data.cache.tagAttached).append(result);

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
		url: config.url.adminAjax + 'tag/searching'
		, data: {
			query: query
		}
		, type: 'get'
		, success: function (result) {
			if (result) {
				$(data.cache.tagBrowser).append(result);
			}
			data.events(data);
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			alert(textStatus);
		}
	});
};


/**
 * builds the hidden field structure in the media browser to represent
 * attached files
 */
Model_Tag_Browser.prototype.refreshHidden = function(data) {
	var attachedZone = $(data.cache.tagAttached);
	var attachedItem = attachedZone.find(data.cache.tag);
	var attachedItemSingle;
	$('input[name="tag_attached[]"]').remove();
	for (var i = attachedItem.length - 1; i >= 0; i--) {
		attachedItemSingle = $(attachedItem[i]);
		attachedZone.append('<input name="tag_attached[]" type="hidden" value="' + attachedItemSingle.data('id') + '">');
	};
};
