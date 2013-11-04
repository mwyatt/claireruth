/**
 * uploads the files which have been selected in the form
 */
function mediaUpload() {
	var theFormData = false;
	if (window.FormData) {
  		theFormData = new FormData();
	}
	var file;
	// $('.js-media-browser .tab-upload-content').append(ajax);
	for (var i = 0; i < this.files.length; i++ ) {
		file = this.files[i];
		if (theFormData) {
			theFormData.append("media[]", file);
		}
	}
	if (theFormData) {
		$.ajax({
			url: urlBaseAjax + 'media/upload/',
			type: 'POST',
			data: theFormData,
			processData: false,
			contentType: false,
			timeout: 60000,
			success: function (result) {

				// reset the upload field
				$('.js-media-input-upload').remove();

				// add new upload field and result
				$('.js-media-upload-container')
					.append('<input id="js-media-input-upload" type="file" name="media" multiple />')
					.append(result);
		  		theFormData = new FormData();
				setEvent();
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// alert(jqXHR);
				alert(textStatus);
				// alert(errorThrown);
			}
		});
	}



	/**
	 * sets all events for common functions
	 */
	function setEvent() {
	 	$('#js-media-input-upload').on("change", upload);
		$('.js-media-item')
			.off('click')
			.on('click', function() {
				$(this).toggleClass('selected');
				$('.js-media-browser').addClass('change-made');
				$('.js-media-browser .button.attach')
					.off('click')
					.on('click', function(event) {
						attachSelections();

						// need public functions
						$('.lightbox-blackout, .lightbox-anchor').removeClass('is-active');
					});
			});
		$('.content .js-media-item').removeClass('selected');
		$('.content .js-media-item')
			.off('click')
			.on('click', function() {

				// remove hidden field and the media item
				$('[name="media[]"][value="' + $(this).data('id') + '"]').remove();
				$(this).remove();
			});
	}

	
}


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
		console.log('value');
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
 * application logic, use of object and functions
 */

// lightboxes
$('.js-lightbox-media-browser').lightbox({
	inline: true
	, maxWidth: 800
	, className: 'media-browser'
	, onComplete: $.fn.mediaBrowser
});	

// header always following on scroll
$('.js-header-main').scrollFollow();

// media-browser
$('.admin-media').mediaBrowser();