/**
 * media browser
 */
var Model_Media_Browser = function (options) {
	var defaults = {
		// template: 'default'
	}
	this.options = $.extend(defaults, options);
	this.cache = {
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
	this.events(this);
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
 * refresh events
 */
Model_Media_Browser.prototype.events = function(data) {
	$(data.cache.mediaInputUpload).on('change', function() {
		data.upload(data, this.files);
		console.log('value');
	});
};


Model_Media_Browser.prototype.upload = function(data, files) {
	var progressBar = $(data.cache.progressBar);

	// formData
	data.resetFormData(data);
	data.appendFiles(data, files);

	// perform ajax
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
	// $(data.cache.mediaBrowserDirectory).html(config.spinner);
	// $.ajax({
	// 	url: config.url.ajax + 'media/read/'
	// 	data: {},
	// 	dataType: 'html',
	// 	success: function(result) {
	// 		if (result) {
	// 			$(data.cache.mediaBrowserDirectory).html(result);
	// 		} else {
	// 			$(data.cache.mediaBrowserDirectory).html('<p class="p1">Nothing in this folder yet.</p>');
	// 		}
	// 		mediaBrowser.getTree();
	// 	}
	// });
};
