// base vars
var urlBase = $('body').data('url-base');
var urlBaseJs = urlBase + 'js/';
var urlBaseAjax = urlBase + 'admin/ajax/';


function example () {
	console.log('example construct');
	this.ok = 50;
	this.okTwo = 51;
}

example.prototype.exampleTwo = function() {
	this.okTwo = 100;
	console.log('exampleTwo construct');
}

example.prototype.exampleThree = function() {
	console.log('exampleThree construct');
	console.log(example.exampleTwo.okTwo);
}

var example = new example('construct info');
example.exampleTwo();
example.exampleThree();
console.log(example.okTwo);


/**
 * uploads the files which have been selected in the form
 */
function MediaUpload() {

	this.setupFormData = function() {

		// init formdata object
		this.theFormData = false;
		
		if (window.FormData) {
	  		this.theFormData = new FormData();
		}
	}


	this.sortFiles = function(files) {
		for (var i = 0; i < files.length; i++ ) {
			this.fileStore = files[i];
			if (this.theFormData) {
				this.theFormData.append("media[]", file);
			}
		}
	}


	this.upload = function() {
		this.sortFiles(this.files);
		$.ajax({
			url: urlBaseAjax + 'media/upload/'
			, type: 'POST'
			, data: this.theFormData
			, processData: false
			, contentType: false
			, timeout: 60000
			, success: function (result) {

				// reset the upload field
				$('.js-media-input-upload').remove();

				// add new upload field and result
				$('.js-media-upload-container')
					.append('<input id="js-media-input-upload" type="file" name="media" multiple />')
					.append(result);
		  		this.theFormData = new FormData();
				this.setEvents();
			}
			, error: function (jqXHR, textStatus, errorThrown) {
				// alert(jqXHR);
				alert(textStatus);
				// alert(errorThrown);
			}
		});
	}

	// init plugin for functions
	var plugin = this;
	this.setEvents();
}



/**
 * sets all events for common functions
 */
MediaUpload.prototype.setEvents = function() {
 	$('#js-media-input-upload').on("change", MediaUpload.upload);
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
 * @todo should import scripts only when the functionality is needed..
 */
$(document).ready(function() {
	var mediaUpload = new MediaUpload();




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
