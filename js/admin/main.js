// base vars
var urlBase = $('body').data('url-base');
var urlBaseJs = urlBase + 'js/';
var urlBaseAjax = urlBase + 'admin/ajax/';


/**
 * hovering which adds a class
 * start simple!
 * js-hover-addclass
 * 		js-hover-addclass-trigger
 * 		js-hover-addclass-drop
 */
(function ($) {
	var hoverClass = 'is-open';
	var container = $('.js-hover-addclass');
	var trigger = $(container).find('.js-hover-addclass-trigger');
	var drop = $(container).find('.js-hover-addclass-drop');

	// module not required
	if (! container.length || trigger.length || drop.length) {
		return;
	}
	setEvents();

	// standard function for setting events
	function setEvents () {
		$(trigger)
			.off('hover', open)
			.on('hover', open);
	}

	function open () {
		removeClass(hoverClass);
		$(drop).addClass('.' + hoverClass);
	}
}(jQuery));


/**
 * adds a class to an element when it goes past a certain point on the screen
 */
(function($){
	$.fn.scrollFollow = function(options) {
		if (! this.length) return;
		var defaults = {
			timer: 0
			, delay: 0
			, followAfter: 0
		}
		var options = $.extend(defaults, options);
		var element = $(this);

		// init
		timeUpdateClass();

		// start checking for the scrolling
		$(window).scroll(timeUpdateClass);


		function timeUpdateClass () {
			clearTimeout(options.timer);
			options.timer = setTimeout(updateClass, options.delay);
		}


		/**
		 * updates the class periodically
		 */
		function updateClass () {
			if ($(window).scrollTop() > Math.floor(options.followAfter)) {
				$(element).addClass('is-fixed-top');
			} else {
				$(element).removeClass('is-fixed-top');
			}
		}
	}
})(jQuery);


/**
 * module for all actions involved with media things
 * this could be instanciated for each place where media manipulation
 * is needed...
 * @return {object} instance of self
 */
Model_Media = (function () {
	var module = function () {}
		, formData = false
		, inputUploadClass = '.js-media-input-upload'
		, inputUpload = $(inputUploadClass);


	/**
	 * sets all required events, must be a better way to do this?
	 */
	module.prototype.setEvent = function() {
		var lightbox = $('.lightbox')
			, content = $('.content')
			, contentRow = content.find('.row.media')
			, contentItem = contentRow.find('.js-media-item')
			, lightboxItem = lightbox.find('.js-media-item');

		// add a file to the browse input, upload the files
		// no need for off as this will be removed on success / failure
		$('.js-media-input-upload').on('change', this.upload);

		// clicking a media item in the lightbox
		lightboxItem
	        .off('click')
	        .on('click', function(event) {
	        	event.preventDefault();

	        	// flag this as selected
	            $(this).toggleClass('is-selected');

	            // if any selected items
	            if (lightbox.find('.js-media-item.is-selected').length) {
		            lightbox.addClass('change-made');

		            // click the attach all button
		            lightbox.find('.js-button-attach')
	                    .off('click')
	                    .on('click', function(event) {
	                        
							// cleanup past attachments
							contentRow.find('input[type="hidden"]').remove();
							contentItem.remove();

							// add new ones
							$.each(lightbox.find('.js-media-item.is-selected'), function() {
								contentRow.append('<input name="media[]" type="hidden" value="' + $(this).data('id') + '">');
								$(this).appendTo(contentRow);
							});

							// setup events again (for attached items)
							module.prototype.setEvent();

	                        // need public functions for this
	                        $('.lightbox-blackout, .lightbox-anchor').removeClass('is-active');
	                    });
	            } else {

	            	// if items had been selected then all removed
		            lightbox.removeClass('change-made');
	            };
	        });
		contentItem
	        .off('click')
	        .on('click', function(event) {
	        	event.preventDefault();
	        	
                // remove hidden field and the media item
                $('[name="media[]"][value="' + $(this).data('id') + '"]').remove();
                $(this).remove();
	        });
	}


	/**
	 * reset form data with new object
	 */
	module.prototype.resetFormData = function() {
		if (window.FormData) {
	  		formData = new FormData();
		}
	}


	/**
	 * simply return formdata
	 * @return {object} 
	 */
	module.prototype.getFormData = function() {
		return formData;
	}


	/**
	 * appends files to the formdata object
	 * @param  {object} files 
	 */
	module.prototype.appendFiles = function(files) {
		var singleFile;
		for (var i = 0; i < files.length; i++ ) {
			singleFile = files[i];
			if (formData) {
				formData.append("media[]", singleFile);
			}
		}
	}


	/**
	 * read in all media items and store in refreshpane
	 */
	module.prototype.readAll = function() {
		var refreshPane = $('.js-media-refresh');
		refreshPane.addClass('ajax');
		$.get(
			urlBaseAjax + 'media/read/'
			// , {}
			, function(result) { 
				if (result) {
					refreshPane
						.removeClass('ajax')
						.html(result);
				}
			}
		);
	}

	/**
	 * ajax upload procedure with progress bar support
	 */
	module.prototype.upload = function() {
		var backupUploadInput = inputUpload[0].outerHTML
			, progressBar = $('.js-media-progress');

		// append to formdata
  		module.prototype.resetFormData();
		module.prototype.appendFiles(this.files);

		// perform ajax
		$.ajax({
			url: urlBaseAjax + 'media/upload/'
			, type: 'POST'
			, data: module.prototype.getFormData()
			, processData: false
			, contentType: false
			, timeout: 60000

			// progress bar
			, xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener('progress', function(event) {
					if (event.lengthComputable) {
						var percentComplete = event.loaded / event.total;
						;
						progressBar.val(parseInt(percentComplete * 100));
					}
				}, false);
				return xhr;
			}
			, success: function (result) {

				// reset the upload field
				$(inputUploadClass).remove();

				// add new upload field and result
				progressBar
					.before(backupUploadInput)
					.after(result)
					.val(0);
		  		module.prototype.resetFormData();
				module.prototype.setEvent();
				module.prototype.readAll();
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				// alert(jqXHR);
				progressBar.val(0);
				alert(textStatus);
				// alert(errorThrown);
			}
		});
	}

	// methods
	return module;
})();


/**
 * generic keyup
 */
$(document).keyup(function(event) {

	// escape
	if (event.keyCode == 27) {
		$('.is-active').removeClass('is-active');
	} 
});


/**
 * handles generic form submission
 * @todo possible to integrate the ajax script to validate?
 * @return {bool} 
 */
function formSubmitDisable () {
	if ($(this).hasClass('disabled')) {
		return false;
	}
	$(this).addClass('disabled');
	$(this).closest('form').submit();
	return false;
}


/**
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {

	// cache
	var content = $('.content');
	var body = $('body');

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
	if (content.hasClass('post')) {
		var editor = new wysihtml5.Editor('form_html', {
			toolbar: 'toolbar'
			, parserRules: wysihtml5ParserRules
			, useLineBreaks: false
		});
	};

	// // getscripts
	$.when(
	    $.getScript(urlBaseJs + 'jquery.lightbox.js')
	    // , $.getScript(urlBaseJs + 'admin/jquery.tags.js')
	).done(function() {
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

	// 	// media-browser
	// 	$('.admin-media').mediaBrowser();

	});
});
