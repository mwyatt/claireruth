var ajax = '<div class="ajax"></div>';

/**
 * constructs a media browser area based on the options provided
 * can customise the root directory
 * requires a php ajax construct to upload and remove files
 * options
 * 		directory: string,
 * @param  {object} $ 
 */
(function($){
	$.fn.mediaBrowser = function(options) {
		// @todo find a way to remove
		if (! this.length) {
			return;
		};
		var thisCore = this;
		var html = {
			base: '<div class="control clearfix">'
				+ '<p class="bread">'
					+ '<span class="tree"></span>'
				+ '</p>'
				+ '<div class="upload">'
				    + '<label for="form_images">Upload Media</label>'
				    + '<input id="form_images" type="file" name="images" multiple />'
				    + '<div id="response"></div>'
				    + '<ul id="image-list"></ul>'
				+ '</div>'
			+ '</div>'
			+ '<div class="directory clearfix" ></div>'
			, newFolder: '<div class="new">'
					+ '<div class="inner clearfix">'
						+ '<label for="form_create_folder">Create Folder</label>'
						+ '<input id="form_create_folder" type="text">'
						+ '<a class="submit">Create</a>'
					+ '</div>'
				+ '</div>'
		}
		var bread = [];
		var defaults = {
			defaultDirectory: ''
			, thumbWidth: '250'
			, thumbHeight: '200'
		}
		var options = $.extend(defaults, options);
		$(thisCore).html(html.base);
		getDirectory('');
		var uploadFormData = false;
		if (window.FormData) {
	  		uploadFormData = new FormData();
	  		// document.getElementById("btn").style.display = "none";
		}
		function getBread() {
			var path = options.defaultDirectory + '';
			for (var i = 0; i < bread.length; i++) {
				path += bread[i];
			}
			return path;
		}
		function assignHandler() {
			$(thisCore).find('.directory').find('.folder').off().on('click', function() {
				getDirectory($(this).data('path'));
			});
			$(thisCore).find('.directory').find('.file').find('.remove').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				removeFile($(this).parent().data('path'));
			});
			$(thisCore).find('.directory').find('.folder').find('.remove').on('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				removeFolder($(this).parent().data('path'));
			});
			$(thisCore).find('.back').off().on('click', getPreviousDirectory);
			$(thisCore).find('.directory').find('.submit').off().on('click', function() {
				createFolder($(thisCore).find('.directory').find('input#form_create_folder').val());
			});
			$(thisCore).find('.directory').find('input').on('keyup', function(e) {
				if (e.keyCode == 13) {
					createFolder($(thisCore).find('.directory').find('input#form_create_folder').val());
					e.preventDefault();
					return false;
				}
			});
			$('#form_images').on('change', upload);
		}
		function getDirectory(folder) {
			$(thisCore).find('.directory').html(ajax);
			if (folder) {
				bread.push(folder);
			};
			$.getJSON(url.base + 'ajax/media-browser/get-directory?path=' + getBread(), function(results) {
				$(thisCore).find('.ajax').remove();
				if (results) {
					$(thisCore).find('.back').remove();
					$(thisCore).find('.directory').html('');
					if (bread.length) {
						$(thisCore).find('.bread').prepend('<a class="back">Back</a>');
					};
					$(thisCore).find('.bread').find('.tree').html(getBread());
					if ('folder' in results) {
						$.each(results.folder, function() {
							$(thisCore).find('.directory').append('<a class="folder" data-path="' + this.basename + '/" title="/' + this.basename + '/"><div class="img"><img src="' + url.base + 'media/folder.png" alt="' + this.basename + '"></div><span title="Remove folder" class="remove">&times;</span><p>' + this.basename + '</p></a>');
						});
					};
					$(thisCore).find('.directory').append(html.newFolder);
					if ('file' in results) {
						$.each(results.file, function() {
							this.extension = this.extension.toLowerCase();
							if (this.extension == 'png' || this.extension == 'jpg' || this.extension == 'gif') {
								$(thisCore).find('.directory').append('<a target=_blank" href="' + url.base + this.guid + '" class="clearfix file hide" data-path="' + getBread() + this.basename + '" title="' + this.basename + '"><div class="img"><img src="' + url.base + 'timthumb/?src=' + url.base + this.guid + '&w=' + options.thumbWidth + '&h=' + options.thumbHeight + '" alt="' + this.basename + '"></div><span title="Remove file" class="remove">&times;</span><p>' + this.basename + '</p></a>');
							}
							if (this.extension == 'pdf') {
								$(thisCore).find('.directory').append('<a class="clearfix file hide" data-path="' + this.path + '" title="' + this.basename + '"><div class="img"><img src="img/icon-pdf.png" alt="' + this.basename + '"></div><span title="Remove file" class="remove">&times;</span><p>' + this.basename + '</p></a>');
							}
						});
					};
					assignHandler();
					bringIn();
				}
			});
		}
		function bringIn() {
			$(thisCore).find('.hide').each(function(index) {
				$(this).delay(100 * index).fadeIn(300);
			});
		}
		function getPreviousDirectory() {
			if (bread.length) {
				bread.splice(-1, 1);
			};
		    getDirectory('');
		}
		function createFolder(folderName) {
			if (! /\S/.test(folderName)) {
				return;
			}
			folderName = folderName.replace(/\s/g, '-').toLowerCase();
			$.getJSON(url.base + 'ajax/media-browser/create-folder?path=' + getBread() + folderName + '/', function(results) {
				if (results) {
					getDirectory('');
				};
			});
		}
		function removeFolder(path) {
			if (confirm('Are you sure you want to remove this folder? "' + path + '". Note: If the folder contains files or folders it will not be removed.')) {
				$.getJSON(url.base + 'ajax/media-browser/remove-folder?path=' + getBread() + path, function(results) {
					if (results) {
						getDirectory('');
					};
				});
			}
		}
		function removeFile(path) {
			if (confirm('Are you sure you want to remove this file? "' + path + '". This can\'t be undone.')) {
				$.getJSON(url.base + 'ajax/media-browser/remove-file?path=' + path, function(results) {
					if (results) {
						getDirectory('');
					};
				});
			}
		}
		function upload() {
	 		document.getElementById("response").innerHTML = "Uploading..."
	 		var i = 0;
	 		var len = this.files.length;
	 		var img;
	 		var reader;
	 		var file;
			for ( ; i < len; i++ ) {
				file = this.files[i];
				// if (window.FileReader) {
				// 	reader = new FileReader();
				// 	reader.readAsDataURL(file);
				// }
				if (uploadFormData) {
					uploadFormData.append("images[]", file);
				}
			}
			if (uploadFormData) {
				$.ajax({
					url: url.base + 'ajax/media-browser/upload?path=' + getBread(),
					type: 'POST',
					data: uploadFormData,
					processData: false,
					contentType: false,
					timeout: 60000,
					success: function (res) {
						document.getElementById("response").innerHTML = res; 
						$('#form_images').remove();
						$('.upload').find('label').after('<input id="form_images" type="file" name="images" multiple />');
						uploadFormData = new FormData();
						$('#form_images').on('change', upload);
						getDirectory('');
					},
					error: function (jqXHR, textStatus, errorThrown) {
						// console.log(jqXHR);
						console.log(textStatus);
						// console.log(errorThrown);
					}
				});
			}
		}
	};
})(jQuery);

var url = {
	base: '',
	query: false,

	initialise: function() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('&') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
		    hash = hashes[i].split('=');
		    vars.push(hash[0]);
		    vars[hash[0]] = hash[1];
		}
		url.query = vars;
	},

	getPart: function(part) {
		if (part in url.query) {
			return url.query[part];
		}
		return false;
	}
}

var feedback = {
	container: false,
	speed: 'fast',

	init: function() {
		feedback.container = $('.feedback');
		$(feedback.container).on('click', feedback._click);
	},

	_click: function() {
		$(this).fadeOut(feedback.speed);
		// setTimeout(showFeedback, 1000);
		// function showFeedback() {
		// 	feedback.fadeIn(animationSpeed);
		// 	setTimeout(hideFeedback, 10000);
		// }
		// function hideFeedback() {
		// 	// feedback.fadeOut(animationSpeed);
		// }
	}
}

var exclude = {
	container: $('.exclude'),

	init: function() {
		$(exclude.container).on('click', exclude.isChecked);
	},

	isChecked: function() {
		if ($(this).find('input').prop('checked')) {
			$(this).closest('.row.score').addClass('excluded');
		} else {
			$(this).closest('.row.score').removeClass('excluded');
		}
	}
};

var select = {
	container: false,
	division: false,
	team: false,
	player: false,
	side: false,

	init: function() {
		select.container = $('.content');
		select.division = $(select.container).find('select[name="division_id"]');
		select.team = $(select.container).find('select[name^="team"]');
		select.player = $(select.container).find('select[name^="player"]');
		$(select.division).on('change', select.loadTeam);
		$(select.container).find('.play-up').on('click', select.clickPlayUp);
		$(select.container).find('.score').find('input').on('click', select.clickInputScore);
		$(select.container).find('.score').find('input').on('keyup', select.changeScore);
		$('.play-up').on('mouseup', select.playUp);
	},

	loadTeam: function() {
		select._reset('player');
		$(select.team).html('');

		$.getJSON(url.base + '/ajax/team/?division_id=' + $(select.division).val(), function(results) {
			if (results) {
				$(select.team).append('<option value="0"></option>');
				$.each(results, function(index, result) {
					$(select.team).append('<option value="' + result.id + '">' + result.name + '</option>');
				});
				$(select.team).on('change', select.loadPlayer);
				$(select.team).prop("disabled", false);
			}
		});		
	},

	playUp: function() {
		var playerSelect;
		var playUpButton = $(this);
		$(playUpButton).off();
		if ($(this).hasClass('left')) {
			playerSelect = $(this).parent().find('select[name^="player[left]"]');
		} else {
			playerSelect = $(this).parent().find('select[name^="player[right]"]');
		}
		$.getJSON(url.base + '/ajax/player/', function(results) {
			if (results) {
				$(playerSelect).html('');
				$.each(results, function(index, result) {
					$(playerSelect).append('<option value="' + result.id + '">' + result.name + '</option>');
				});
				$(playerSelect).on('change', select.changePlayer);
				$(playUpButton).fadeOut('fast');
			}
		});
	},

	updatePlayerLabel: function(side, index, name) {
		$('label[for$="' + side + '"].player-' + index).html(name);
	},

	clickInputScore: function() {
		$(this).select();
	},

	arrangePlayerSelect: function() {
		for (var index = 0; index < 3; index ++) { 
			playerIndex = index + 1;
			playerOptions = $('select[name="player[' + select.side + '][' + playerIndex + ']"]').find('option');
			playerOptions.each(function(optionIndex) {
				if ((optionIndex) == (index + 1)) {
					$(this).prop('selected', 'selected');
					select.updatePlayerLabel(select.side, playerIndex, $(this).html());
				}
			});
		}
	},

	_reset: function(key) {
		if (key == 'player') {
			$(select.container).find('select[name^="player"]').html('');
		}
		if (key == 'score') {
			$(select.container).find('select[name^="player"]').html('');
		}
	},

	updateFixtureScore: function() {
		var score, leftTotal = 0, rightTotal = 0;
		$(select.container).find('.row.score').find('input[name$="[left]"]').each(function() {
	 		score = parseInt($(this).val());
	 		if (isNaN(score))
	 			score = 0;
			leftTotal = leftTotal + score;
		});
		$(select.container).find('.row.score').find('input[name$="[right]"]').each(function() {
	 		score = parseInt($(this).val());
	 		if (isNaN(score))
	 			score = 0;
			rightTotal = rightTotal + score;
		});
		$(select.container).find('.row.total').find('.left').html(leftTotal);
		$(select.container).find('.row.total').find('.right').html(rightTotal);
	},

	changeScore: function(e) {
		// exclude tab, shift, backspace key

		if ((e.keyCode == 9) || (e.keyCode == 16)|| (e.keyCode == 8))
			return false;

		// continue...

		var
			currentValue
			, parts
			, index
			, oppositeScore
			;

			currentValue = parseInt($(this).val());
			if (currentValue == NaN)
				currentValue = 0;

		parts = $(this).prop('id').split('_');

		if (2 in parts) {

			if ($(this).val() >= 3)
				oppositeScore = 0;
			else
				oppositeScore = 3;

			if (parts[2] == 'left')
				$('#encounter_' + parts[1] + '_right').val(oppositeScore);
			else
				$('#encounter_' + parts[1] + '_left').val(oppositeScore);

		}

		if (!currentValue)
			$(this).val(0);

		// if ((currentValue == 0) || (currentValue)) 
		// 	$(this).val(currentValue + 1);

		// if (currentValue == 2)
		// 	$(this).val(2);

		if (currentValue > 3)
			$(this).val(3);

		// update the totals
		
		select.updateFixtureScore();
	},

	loadPlayer: function() {
		if ($(this).attr('name') == 'team[left]') {
			select.side = 'left';
		} else {
			select.side = 'right';
		}
		select.player = $(select.container).find('select[name^="player[' + select.side + ']"]');
		$(select.player).html('');
		$.getJSON(url.base + '/ajax/player/?team_id=' + $(this).val(), function(results) {
			if (results) {
				$(select.player).append('<option value="0">Absent Player</option>');
				$.each(results, function(index, result) {
					$(select.player).append('<option value="' + result.id + '">' + result.full_name + '</option>');
				});
				select.arrangePlayerSelect();
				$(select.player).on('change', function() {
					select.updatePlayerLabel($(this).data('side'), $(this).data('position'), $(this).find('option:selected').html());
				});
				$(select.player).prop("disabled", false);
			}
		});	
	}
}

function formSubmit(e, button) {
	$(button).closest('form').submit();
	e.preventDefault();
}

// document ready

$(document).ready(function() {
	url.base = $('body').data('url-base');
	// less.watch();
	$.ajaxSetup ({  
		cache: false
	});
	exclude.init();
	select.init();
	feedback.init();
	$('.button.season-start').on('click', function() {
		// e.preventDefault();
		if (confirm('Once you \'start\' the season the fixtures will be generated and you will be unable to move teams to other divisions.')) {
			return true;
		}
		return false;
	});
	$('form').find('a.submit').on('click', function(e) {
		formSubmit(e, this);
	});
	if ($('.content.media.index').length) {
		$('.browser').mediaBrowser();
	}
	if ($('.content.media.gallery').length) {
		$('.browser').mediaBrowser({
			defaultDirectory: 'gallery/'
		});
	}
	if (
		$('.content.page.create').length
		|| $('.content.page.update').length
		|| $('.content.press.create').length
		|| $('.content.press.update').length
	) {
		var editor = new wysihtml5.Editor("form_html", {
		  toolbar:        "toolbar",
		  parserRules:    wysihtml5ParserRules,
		  useLineBreaks:  false
		});
	}
	$(document).mouseup(removeModals);	
	$(document).keyup(function(e) {
		if (e.keyCode == 27) {
			removeModals();
		}
	});	
	function removeModals() {
		$('*').removeClass('active');
	}
	if ($('.content.gallery').length) {
		$('.file').magnificPopup({type:'image'});
	}
	var user = $('header.main').find('.user');
	user.find('a').on('click', clickUser);
	function clickUser() {
		user.addClass('active');
	}
	var websiteTitle = $('header.main').find('.title').find('a');
	websiteTitleText = $('header.main').find('.title').find('a').html();
	websiteTitle.hover(function over() {
		var text = $(this).html();
		text = 'Open ' + text + ' Homepage';
		$(this).html(text);
	},
	function out() {
		$(this).html(websiteTitleText);
	});
}); // document ready