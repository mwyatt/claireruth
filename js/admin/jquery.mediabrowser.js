/**
 * constructs a media browser area based on the options provided
 */
(function($){
	$.fn.mediaBrowser = function(options) {
		var core = this;
		var defaults = {}
		var options = $.extend(defaults, options);
		setEvent();


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


		/**
		 * adds a hidden field and attaches dom structure to create-update
		 */
		function attachSelections() {

			// cleanup past attachments
			$('.content .row.media input[type="hidden"]').remove();
			$('.content .js-media-item').remove();

			// add new ones
			$.each($(core).find('.selected'), function() {
				$('.content .row.media').append('<input name="media[]" type="hidden" value="' + $(this).data('id') + '">');
				$(this).appendTo('.content .row.media');
			});
			setEvent();
		}


		/**
		 * removes the file from the specified path
		 * @param  {string} path 
		 */
		function removeFile(path) {
			if (confirm('Are you sure you want to remove this file? "' + path + '". This can\'t be undone.')) {
				$.getJSON($('body').data('url-base') + 'ajax/media-browser/remove-file?path=' + path, function(results) {
					if (results) {
						getDirectory('');
					};
				});
			}
		}


		/**
		 * uploads the files which have been selected in the form
		 */
		function upload() {
			var uploadFormData = false;
			if (window.FormData) {
		  		uploadFormData = new FormData();
			}
			var file;
			$('.js-media-browser .tab-upload-content').append(ajax);
			for (var i = 0; i < this.files.length; i++ ) {
				file = this.files[i];
				if (uploadFormData) {
					uploadFormData.append("media[]", file);
				}
			}
			if (uploadFormData) {
				$.ajax({
					url: config.url.base + 'admin/ajax/media/upload/',
					type: 'POST',
					data: uploadFormData,
					processData: false,
					contentType: false,
					timeout: 60000,
					success: function (result) {

						// reset the upload field
						$('.js-media-browser input[type="file"], .ajax').remove();

						// add new upload field and result
						$('.js-media-browser .tab-upload-content')
							.append('<input id="form_images" type="file" name="media[]" multiple />')
							.append(result);
				  		uploadFormData = new FormData();
						setEvent();
					},
					error: function (jqXHR, textStatus, errorThrown) {
						// alert(jqXHR);
						alert(textStatus);
						// alert(errorThrown);
					}
				});
			}
		}
	};
})(jQuery);
