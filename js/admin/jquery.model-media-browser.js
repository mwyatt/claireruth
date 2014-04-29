/**
 * media browser
 */
var Model_Media_Browser = function (options) {
	var defaults = {
		// template: 'default'
	}
	this.options = $.extend(defaults, options);
	this.cache = {
		medium: '.js-medium',
		progressBar: '.js-media-progress',
		mediaBrowser: '.js-media-browser',
		mediaAttached: '.js-media-attached',
		mediaSearchInput: '.js-media-search-input',
		formUpload: '.js-form-upload',
		mediaInputUpload: '.js-media-input-upload',
		mediaBrowserDirectory: '.js-media-browser-directory'
	};
	this.formData = {};
	this.resetFormData(this);
	this.refreshHidden(this);
	this.refreshBrowser(this);
};


/**
 * reset form data with new object
 * if the browser is compatible
 */
Model_Media_Browser.prototype.resetFormData = function(data) {
	if (window.FormData) {
  		data.formData = new FormData();
	}
};


/**
 * should replace the file input where it stands!
 * @todo test
 */
Model_Media_Browser.prototype.resetFormFileInput = function(data) {
	var mediaInputUpload = $(data.cache.mediaInputUpload);
	mediaInputUpload.replaceWith(mediaInputUpload = mediaInputUpload.clone(true));
};


/**
 * appends files to the formdata object
 * @param  {object} files 
 */
Model_Media_Browser.prototype.appendFiles = function(data, files) {
	var singleFile;
	for (var i = 0; i < files.length; i++ ) {
		singleFile = files[i];
		if (data.formData) {
			data.formData.append("media[]", singleFile);
		}
	}
};


/**
 * builds the hidden field structure in the media browser to represent
 * attached files
 */
Model_Media_Browser.prototype.refreshHidden = function(data) {
	var attachedZone = $(data.cache.mediaAttached);
	var attachedMedia = attachedZone.find(data.cache.medium);
	var attachedMediaSingle;
	$('input[name="media_attached[]"]').remove();
	for (var i = attachedMedia.length - 1; i >= 0; i--) {
		attachedMediaSingle = $(attachedMedia[i]);
		attachedZone.append('<input name="media_attached[]" type="hidden" value="' + attachedMediaSingle.data('id') + '">');
	};
};


/**
 * refresh events
 */
Model_Media_Browser.prototype.events = function(data) {
	$(data.cache.mediaInputUpload)
		.off('change')
		.on('change', function() {
			data.resetFormData(data);
			data.appendFiles(data, this.files);
			data.upload(data);
		});
	$(data.cache.mediaBrowserDirectory).find(data.cache.medium)
		.off('click')
		.on('click', function() {
			$(this).clone().appendTo(data.cache.mediaAttached);
			data.refreshHidden(data);
			data.events(data);
		});
	$(data.cache.mediaAttached).find(data.cache.medium)
		.off('click')
		.on('click', function() {
			$(this).remove();
			data.refreshHidden(data);
			data.events(data);
		});
};


Model_Media_Browser.prototype.upload = function(data) {
	var progressBar = $(data.cache.progressBar);
	$.ajax({
		url: config.url.adminAjax + 'media/upload/'
		, type: 'post'
		, data: data.formData
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
			console.log(result);
			data.resetFormFileInput(data);
			progressBar.val(0);
	  		data.resetFormData(data);
			data.refreshBrowser(data);
		}
		, error: function (jqXHR, textStatus, errorThrown) {
			// alert(jqXHR);
			progressBar.val(0);
			alert(textStatus);
			// alert(errorThrown);
		}
	});
};


Model_Media_Browser.prototype.refreshBrowser = function(data) {
	var mediaBrowserDirectory = $(data.cache.mediaBrowserDirectory);
	mediaBrowserDirectory.html(config.spinner);
	$.ajax({
		url: config.url.adminAjax + 'media/read/',
		data: {},
		dataType: 'html',
		success: function(result) {
			if (result) {
				mediaBrowserDirectory.html(result);
			} else {
				mediaBrowserDirectory.html('<p class="p1">Nothing Uploaded Yet.</p>');
			}
			data.events(data);
		}
	});
};
