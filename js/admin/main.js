// base vars   
var urlBase = document.body.data('url-base');
var urlBaseJs = urlBase + 'js/';
var urlBaseAjax = urlBase + 'admin/ajax/';


function contentCreateUpdate () {
	
	// html5wysi
	var editor = new wysihtml5.Editor('form_html', {
		toolbar: 'toolbar'
		, parserRules: wysihtml5ParserRules
		, useLineBreaks: false
	});

	// tie in content meta
	var modelContentMeta = new Model_Content_Meta();

	// tag
	var modelTag = new Model_Tag();

	// enter key
	modelTag.searchField.live('keypress', function(event) {
		var field = $(this);
		if (event.which == 13) {
    		$.ajax({
    			url: urlBaseAjax + 'tag/create'
    			, type: 'get'
    			, data: {
    				title: field.val()
    				, description: ''
    			}
    			, success: function (result) {
    				
    			}
    			, error: function (jqXHR, textStatus, errorThrown) {
    				alert(textStatus);
    			}
    		});
			return false;
	    }
	});

	// hitting keys when in the search field
	modelTag.searchField.live('keyup', function(event) {
		var field = $(this);

		// clear timer always
	    clearTimeout(modelTag.timer);
		modelTag.dropDown.html('');

	    // handle search terms if long enough
	    if (field.val().length > 1) {
	    	modelTag.timer = setTimeout(function() {
	    		modelTag.search(field.val());
	    	}, 300);
	    }
	});

	// clicking a existing tag
	modelTag.attachedTags.live('click', modelTag.clickRemove);

	// clicking a tag in the dropdown
	modelTag.dropTags.live('click', function() {
		var button = $(this);

		// create content association
		$.ajax({
			url: urlBaseAjax + 'content/meta/create'
			, type: 'get'
			, data: {
				content_id: $('.content').data('id')
				, name: 'tag'
				, value: $(this).data('id')
			}
			, success: function (result) {
				
				// move the button to the attached area
				button.appendTo(modelTag.attachedTagContainer);
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			}
		});

		// no more tags in the dropdown
		if (! modelTag.dropTags) {
			modelTag.dropDown.html('');
		};

		// empty the searchfield
		modelTag.searchField.val('');
	});
}

/**
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {

	// cache
	var content = $('.content');
	var body = document.body;

	// prevent ajax cache
	$.ajaxSetup ({  
		cache: false  
	});

	// form submission
	$('form').find('a.submit').on('mouseup', formSubmitDisable);

	// general logic seperation
	if (body.hasClass('admin-media')) {
		var modelMedia = new Model_Media();
		modelMedia.setEvent();
	};

	// try adding all logic for manipulating objects here...
	if (content.hasClass('content-create-update')) {
		contentCreateUpdate();
	};

	var modelMedia = new Model_Media();

	// lightboxes
	$('.js-lightbox-media-browser').lightbox({
		inline: true
		, maxWidth: 800
		, className: 'media-browser'
		, onComplete: modelMedia.setEvent
	});

	// header always following on scroll
	$('.js-header-main').scrollFollow();
});
