var ajax = '<div class="ajax"></div>';


/**
 * lightbox
 */
(function($) {
    $.fn.lightbox = function(options) {
		var core = this;
		var defaults = {
			className: ''
			, inline: false
			, galleryClass: ''
			, onComplete: ''
			, resizeTimer: 0
			, scrollTimer: 0
			, timerDelay: 300
			, maxWidth: 0
		}
		var options = $.extend(defaults, options);

		// initialise common events
		events();


		/**
		 * opens the lightbox and determines if it is a gallery or
		 * not
		 */
		function open() {

			// build lightbox if required
			if (! $('.lightbox').length) {
				build();
			};

			// reset classnames
			$('.lightbox').attr('class', 'lightbox');

			if (options.maxWidth) {
				$('.lightbox').css('width', options.maxWidth + 'px');
			};

			if (options.className) {
				$('.lightbox').addClass(options.className);
			};

			// inline?
			if (options.inline) {
				updateContentInline($(this).prop('href'));
				$('.lightbox')
					.addClass('inline');
			};

			// is a gallery avaliable?
			if (options.galleryClass) {
				$('.lightbox')
					.removeClass('gallery')
					.addClass('gallery');
				$('.lightbox-gallery').html('');
				$('.' + options.galleryClass)
					.clone()
					.appendTo('.lightbox-gallery');
			};

			// update the lightbox content with first image
			if (! options.inline) {
				updateContentHero($(this).prop('href'));
			};

			// build lightbox and prevent href activating
			showLightbox();
			lightboxEvents();
			return false;
		}


		/**
		 * setup all items wishing to launch lightbox
		 */
		function events() {
			$.each($(core), function() {
				$(this)
					.off()
					.on('click', open);
			});
		}


		function lightboxEvents() {

			// remove
			$('.lightbox-blackout, .lightbox-anchor')
				.off()
				.on('click', hideLightbox);
			$('.lightbox-remove')
				.off()
				.on('click', hideLightbox);

			// click lightbox to remain
			$('.lightbox')
				.off()
				.on('click', function(event) {
					event.stopPropagation();
				});

			// setup gallery
			$('.lightbox-gallery')
				.children('.thumb')
				.off()
				.on('click', function(event) {
					event.preventDefault();
					updateContentHero($(this).prop('href'));
				});
		}


		/**
		 * process for booting up the lightbox
		 * @return {[type]} [description]
		 */
		function showLightbox() {

			// if the window resizes
			// eventResize();
			// $(window).resize(eventResize);

			// if the window scrolls
			eventScroll();
			// $(window).scroll(eventScroll);

		    // show it
			$('.lightbox-blackout, .lightbox-anchor').addClass('active');
		}


		/**
		 * shutdown lightbox
		 * @return {[type]} [description]
		 */
		function hideLightbox() {
			$(window).off('resize', eventResize);
			$(window).off('scroll', eventScroll);

			// hide it
			$('.lightbox-blackout, .lightbox-anchor').removeClass('active');
		}


		/**
		 * handles the document resize
		 */
		function eventResize() {
			clearTimeout(options.resizeTimer);
			options.resizeTimer = setTimeout(function() {
		    	$('.lightbox').height(document.documentElement.clientHeight - 200);
			}, options.delay);
		}

		
		/**
		 * handles the document scroll
		 */
		function eventScroll() {
			clearTimeout(options.scrollTimer);
			options.scrollTimer = setTimeout(function() {
		    	$('.lightbox').css('top', $(window).scrollTop() + 100);
			}, options.delay);
		}


		/**
		 * updates the main image in lightbox
		 * @param  {string} largeUrl 
		 */
		function updateContentHero(largeUrl) {
			$('.lightbox-content').html(
				'<img src="' + largeUrl + '">'
			);
		}


		/**
		 * updates content area with ajax result
		 * @param  {string} url 
		 */
		function updateContentInline(url) {
			$('.lightbox-content').html(ajax);
			$.get(
				url,
				function(result) {
					$('.ajax').remove();
					if (result) {
						$('.lightbox-content').html(result);
						if (options.onComplete) {
							options.onComplete.call($('.lightbox'));
						};
					}
				}
			);
		}


		/**
		 * builds the lightbox baseplate
		 */
		function build() {
			$('body').append(
				'<div class="lightbox-blackout"></div>'
				+ '<div class="lightbox-anchor">'
					+ '<div class="lightbox">'
						+ '<span class="lightbox-remove">&times;</span>'
						+ '<span class="lightbox-title"></span>'
						+ '<div class="lightbox-content">'
						+ '</div>'
						+ '<div class="lightbox-gallery">'
							+ '<div class="lightbox-gallery-item">'
								+ '<img src="" alt="">'
							+ '</div>'
							+ '<div class="lightbox-gallery-item">'
								+ '<img src="" alt="">'
							+ '</div>'
							+ '<div class="lightbox-gallery-item">'
								+ '<img src="" alt="">'
							+ '</div>'
						+ '</div>'
						+ '<div class="lightbox-control">'
							+ '<span class="lightbox-next">Next</span>'
							+ '<span class="lightbox-previous">Previous</span>'
						+ '</div>'
					+ '</div>'
				+ '</div>'
			);
		}
	}
})(jQuery);


/**
 * tag management, search, add, remove
 */
(function($) {
    $.fn.tags = function(options) {
		var core = this;
		var defaults = {
			timer: 0
		}
		var options = $.extend(defaults, options);
		if (! $(core).length) {
			return;
		}
		events();
		function events() {

			// hit enterkey to submit input as new tag
			$(core).find('#form-tag-search').off().on('keypress', function(e) {
				if (e.which == 13) {
					clearDrop();
			       	$('.tags').append('<div class="tag">' + $(this).val() + '</div>');
					addHiddenField($(this).val());
			       	$(this).val('');
					events();
				    return false;
			    }
			});
			$(core).find('#form-tag-search').on('keyup', function(e) {
				var query = $(this).val();
				clearTimeout(options.timer);
				if ($(this).val().length > 1) {
					options.timer = setTimeout(function() {
						poll(query);
					}, 300);
				}
			});
			$(core).find('.tags .tag').off().on('click', function() {
				removeTag(this);
			});
			$(core).find('.drop .tag').off().on('click', function() {
				addTag(this);
			});
		}
		function addHiddenField(name) {
			$(core).find('.tags').append('<input name="tag[]" type="hidden" value="' + name + '">');
		}
		function poll(query) {
			$.get(
				url.base + 'ajax/tag-management/',
				{
					query: query
				},
				function(result) { 
					clearDrop();
					if (! $('#form-tag-search').val()) {
						return;
					};
					if (result) {
						$(core).find('.area').append('<div class="drop">' + result + '</div>');
					}
					events();
				}
			);
		}
		function clearDrop() {
			$(core).find('.drop').remove();
		}
		function addTag(button) {
			$(button).appendTo($(core).find('.tags'));
			addHiddenField($(button).html());
			events();
			if (! $(core).find('.drop .tag').length) {
				clearDrop();
			};
			$('#form-tag-search').val('');
		}
		function removeTag(button) {
			$('input[type="hidden"][value="' + $(button).html() + '"]').remove();
			$(button).remove();
		}
    }
})(jQuery);


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
	$('.js-lightbox-media-browser').lightbox({
		inline: true
		, className: 'media-browser'
		, onComplete: $.fn.mediaBrowser
	});
	$('.media-browser').mediaBrowser();
	exclude.init();
	select.init();
	feedback.init();
	$('.management-tag').tags();
	$('form').find('a.submit').on('click', function(e) {
		formSubmit(e, this);
	});
	if ($('.content.media.gallery').length) {
		$('.media-browser').mediaBrowser({
			defaultDirectory: 'gallery/'
		});
	}
	if (
		$('.content.create').length
		|| $('.content.update').length
	) {
		var editor = new wysihtml5.Editor("form_html", {
		  toolbar:        "toolbar",
		  parserRules:    wysihtml5ParserRules,
		  useLineBreaks:  false
		});
	}
	// $('body').mouseup(function(e) {
	// 	removeModals();
	// 	if ($(e.target).closest('.drop').length == 0) {
	// 		$('.drop').remove();
	// 	}
	// });	
	$('body').keyup(function(e) {
		if (e.keyCode == 27) {
			removeModals();
			$('.drop').remove();
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