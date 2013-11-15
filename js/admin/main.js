// base vars
var urlBase = $('body').data('url-base');
var urlBaseJs = urlBase + 'js/';
var urlBaseAjax = urlBase + 'admin/ajax/';


/**
 * uploads the files which have been selected in the form
 */
function mediaManager() {
	this.formData = false;
	this.inputUpload = $('.js-media-input-upload')[0].outerHTML;


	/**
	 * sets all events for common functions
	 */
	this.setEvents = function() {
	 	$('.js-media-input-upload').on("change", this.upload);
		// $('.js-media-item')
		// 	.off('click')
		// 	.on('click', function() {
		// 		$(this).toggleClass('selected');
		// 		$('.js-media-browser').addClass('change-made');
		// 		$('.js-media-browser').find('.button.attach')
		// 			.off('click')
		// 			.on('click', function(event) {
		// 				attachSelections();

		// 				// need public functions
		// 				$('.lightbox-blackout, .lightbox-anchor').removeClass('is-active');
		// 			});
		// 	});
		// $('.content .js-media-item').removeClass('selected');
		// $('.content .js-media-item')
		// 	.off('click')
		// 	.on('click', function() {
		// 		this = $(this);
		// 		// remove hidden field and the media item
		// 		$('[name="media[]"][value="' + this.data('id') + '"]').remove();
		// 		this.remove();
		// 	});
	}


	this.setupFormData = function() {
		if (window.FormData) {
	  		this.formData = new FormData();
		}
	}


	/**
	 * appends files to the formdata object
	 * @param  {object} files 
	 */
	this.appendFiles = function(files) {
		var singleFile;
		for (var i = 0; i < files.length; i++ ) {
			singleFile = files[i];
			if (this.formData) {
				this.formData.append("media[]", singleFile);
			}
		}
	}


	/**
	 * uploads the files
	 */
	this.upload = function() {
		var progressBar = $('.js-media-progress');

		// append to formdata
		plugin.appendFiles(this.files);

		// perform ajax
		$.ajax({
			url: urlBaseAjax + 'media/upload/'
			, type: 'POST'
			, data: plugin.formData
			, processData: false
			, contentType: false
			, timeout: 60000
			, xhr: function() {
				var xhr = new window.XMLHttpRequest();

				// upload progress
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
				$('.js-media-input-upload').remove();

				// add new upload field and result
				$('.js-media-upload-container')
					.append(plugin.inputUpload)
					.append(result);
		  		plugin.setupFormData();
				plugin.setEvents();
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				// alert(jqXHR);
				alert(textStatus);
				// alert(errorThrown);
			}
		});
	}

	var plugin = this;
}

// init
var mediaManager = new mediaManager();


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
			, delay: 50
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
	var formData = false;

	// methods
	var module = function () {}


	/**
	 * setup form data
	 * @return {[type]} [description]
	 */
	module.prototype.resetFormData = function() {
		if (window.FormData) {
	  		formData = new FormData();
		}
	}


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

	// methods
	return module;
})();


/**
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {

	// cache
	var body = $('body');

	// admin/media
	if (body.hasClass('admin-media')) {
		var modelMedia = new Model_Media();
		$('.js-media-input-upload').on('change', function () {
			var backupUploadInput = $('.js-media-input-upload')[0].outerHTML;

			// append to formdata
	  		modelMedia.resetFormData();
			modelMedia.appendFiles(this.files);

			// perform ajax
			$.ajax({
				url: urlBaseAjax + 'media/upload/'
				, type: 'POST'
				, data: modelMedia.getFormData()
				, processData: false
				, contentType: false
				, timeout: 60000
				, xhr: function() {
					var xhr = new window.XMLHttpRequest();

					// upload progress
					xhr.upload.addEventListener('progress', function(event) {
						if (event.lengthComputable) {
							var percentComplete = event.loaded / event.total;
							;
							$('.js-media-progress').val(parseInt(percentComplete * 100));
						}
					}, false);
					return xhr;
				}
				, success: function (result) {

					// reset the upload field
					$('.js-media-input-upload').remove();

					// add new upload field and result
					$('.js-media-upload-container')
						.append(backupUploadInput)
						.append(result);
			  		modelMedia.resetFormData();
					// modelMedia.setEvents();
				}
				, error: function (jqXHR, textStatus, errorThrown) {
					// alert(jqXHR);
					alert(textStatus);
					// alert(errorThrown);
				}
			});
		});
	};

	// // getscripts
	// $.when(
	//     $.getScript(urlBaseJs + 'reusable.js')
	//     , $.getScript(urlBaseJs + 'jquery.lightbox.js')
	//     , $.getScript(urlBaseJs + 'admin/jquery.mediabrowser.js')
	//     , $.getScript(urlBaseJs + 'admin/jquery.tags.js')
	// ).done(function() {
		
	// 	*
	// 	 * application logic, use of object and functions
		 

	// 	// lightboxes
	// 	$('.js-lightbox-media-browser').lightbox({
	// 		inline: true
	// 		, maxWidth: 800
	// 		, className: 'media-browser'
	// 		, onComplete: $.fn.mediaBrowser
	// 	});	

	// 	// header always following on scroll
	// 	$('.js-header-main').scrollFollow();

	// 	// media-browser
	// 	$('.admin-media').mediaBrowser();

	// });
});
