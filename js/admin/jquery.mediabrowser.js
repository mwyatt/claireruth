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
		 	$('.media-browser input[type="file"]').on("change", upload);
			$('.media-items .item')
				.off()
				.on('click', function() {
					$(this).toggleClass('selected');
					$('.media-browser').addClass('change-made');
					$('.media-browser .button.attach')
						.off()
						.on('click', function(event) {
							attachSelections();

							// dry violation
							$('.lightbox-blackout, .lightbox-anchor').removeClass('active');
						});
				});
			$('.row.media .item').removeClass('selected');
			$('.row.media .item')
				.off()
				.on('click', function() {
					$(this)
						.parent()
						.find('[value="' + $(this).data('id') + '"]')
						.remove();
					$(this).remove();
				});
		}


		/**
		 * adds a hidden field and attaches dom structure to create-update
		 */
		function attachSelections() {

			// cleanup past attachments
			$('.content .row.media input[type="hidden"]').remove();
			$('.content .row.media .item').remove();

			// add new ones
			$.each($(core).find('.selected'), function() {
				$('.content .row.media').append('<input name="media[]" type="hidden" value="' + $(this).data('id') + '">');
				$(this).appendTo('.content .row.media');
			});
			setEvent();
		}


		/**
		 * fancy bring in animation for items, not needed
		 */
		function bringIn() {
			$(thisCore).find('.hide').each(function(index) {
				$(this).delay(100 * index).fadeIn(300);
			});
		}


		/**
		 * removes the file from the specified path
		 * @param  {string} path 
		 */
		function removeFile(path) {
			if (confirm('Are you sure you want to remove this file? "' + path + '". This can\'t be undone.')) {
				$.getJSON(url.base + 'ajax/media-browser/remove-file?path=' + path, function(results) {
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
			$('.media-browser .tab-upload-content').append(ajax);
			for (var i = 0; i < this.files.length; i++ ) {
				file = this.files[i];
				if (uploadFormData) {
					uploadFormData.append("media[]", file);
				}
			}
			if (uploadFormData) {
				$.ajax({
					url: url.base + 'ajax/media-browser/upload/',
					type: 'POST',
					data: uploadFormData,
					processData: false,
					contentType: false,
					timeout: 60000,
					success: function (result) {

						// reset the upload field
						$('.media-browser input[type="file"], .ajax').remove();

						// add new upload field and result
						$('.media-browser .tab-upload-content')
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
