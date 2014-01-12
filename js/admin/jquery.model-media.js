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
		var contentMeta = new Content_Meta({
			name: 'media'
		});

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
	                        var selectedMedia = lightbox.find('.js-media-item.is-selected');

							// cleanup past attachments
							contentRow.find('input[type="hidden"]').remove();

							// remove current items
							var ids = [];
							for (var i = contentItem.length - 1; i >= 0; i--) {
								ids[i] = $(contentItem[i]).data('id');
							};
							contentMeta.modify(false, 'delete', ids, function() {
								contentItem.remove();
							});

							// add new ones
							$.each(selectedMedia, function() {
								contentRow.append('<input name="media[]" type="hidden" value="' + $(this).data('id') + '">');
								$(this).appendTo(contentRow);
							});

							// setup events again (for attached items)
							module.prototype.setEvent();

	                        // need public functions for this
	                        $('.lightbox-blackout, .lightbox-anchor').removeClass('is-active');

	                        // set meta
	                        var ids = [];
	                        for (var i = selectedMedia.length - 1; i >= 0; i--) {
	                        	ids[i] = $(selectedMedia[i]).data('id');
	                        };
	                        contentMeta.modify(false, 'create', ids, function() {});
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
                var ids = [$(this).data('id')];
                contentMeta.modify(false, 'delete', ids, function() {});
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
			config.url.ajax + 'media/read/'
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
			url: config.url.ajax + 'media/upload/'
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
