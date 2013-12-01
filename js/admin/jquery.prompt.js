var Prompt = function (options) {
	var defaults = {
		template: 'default'
	}
	this.options = $.extend(defaults, options);
	this.dropDown = $('.js-tag-drop');
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


Prompt.prototype.refreshEventAttachedTags = function(event) {
};
