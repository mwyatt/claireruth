/**
 * lightbox
 *
 * responsive
 * ajax
 * next / prev
 * gallery
 */
(function($) {
    $.fn.lightbox = function(options) {
		var core = this;
		var defaults = {
			className: ''
			, inline: false
			, galleryClass: ''
			, maxWidth: 0
			, onComplete: ''
			, title: ''
		}
		var options = $.extend(defaults, options);
		var timer = 0;
		var timerDelay = 200;

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

			// reset height
			$('.lightbox, .lightbox-content').css('height', '');

			// reset classnames
			$('.lightbox').attr('class', 'lightbox');

			// maxwidth has been set
			if (options.maxWidth) {
				$('.lightbox').css('width', options.maxWidth + 'px');
			};

			// always overwrite the title
			if (options.title) {
				$('.lightbox-title').html(options.title);
				$('.lightbox-title').removeClass('hide');
			} else {
				$('.lightbox-title').addClass('hide');
			}

			// custom class added
			if (options.className) {
				$('.lightbox').addClass(options.className);
			};

			// inline?
			if (options.inline) {
				updateContentInline($(this).prop('href'));
				$('.lightbox')
					.addClass('inline');
			};

			// gallery is happening
			if (options.galleryClass) {
				$('.lightbox')
					.addClass('gallery');
				$('.lightbox-gallery').html('');
				$('.' + options.galleryClass)
					.clone()
					.appendTo('.lightbox-gallery');
				var mainImageUrl = $(this).prop('href');
				$('.lightbox-gallery').children().addClass('lightbox-gallery-item');
				$.each($('.lightbox-gallery-item'), function(index) {
					if ($(this).prop('href') == mainImageUrl) {
						$(this).addClass('active');
					};
				});
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
					.off('click', open)
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
				.off('click')
				.on('click', function(event) {
					event.preventDefault();
					clickGalleryItem(this);
				});
			$('.lightbox-next')
				.off()
				.on('click', function() {
					galleryMove('next');
				});
			$('.lightbox-previous')
				.off()
				.on('click', function() {
					galleryMove('previous');
				});
			$('.lightbox-title-link')
				.off()
				.on('click', function(event) {
					event.preventDefault();
					hideLightbox();
				});
			$('.lightbox-title')
				.off()
				.on('click', function(event) {
					event.preventDefault();
					hideLightbox();
				});
		}


		function clickGalleryItem(item) {
			updateContentHero($(item).prop('href'));
			$('.lightbox-gallery-item').removeClass('is-active');
			$(item).addClass('is-active');
			scrollSingle();
		}



		function galleryMove(action) {
			$.each($('.lightbox-gallery-item'), function(index) {
				if ($(this).hasClass('is-active')) {
					if (action == 'next') {
						nextindex = index + 1;
						opposingindex = 0;
					} else {
						nextindex = index - 1;
						opposingindex = $('.lightbox-gallery-item').length - 1;
					};
					$('.lightbox-gallery-item').removeClass('is-active');
					if ($('.lightbox-gallery-item').eq(nextindex).length) {
						$('.lightbox-gallery-item').eq(nextindex).addClass('is-active');
						updateContentHero($('.lightbox-gallery-item').eq(nextindex).prop('href'));
					} else {
						$('.lightbox-gallery-item')
							.eq(opposingindex)
							.addClass('is-active');
						updateContentHero($('.lightbox-gallery-item').eq(0).prop('href'));
					};
					return false;
				};
			});
		}

		/**
		 * process for booting up the lightbox
		 * @return {[type]} [description]
		 */
		function showLightbox() {

			// if the window resizes
			// only if a width is not set
			if (! options.maxWidth) {
				resizeSingle();
				$(window).resize(resizing);
			};

			// // if the window scrolls
			scrollSingle();
			// $(window).scroll(scrolling);

		    // show it
			$('.lightbox-blackout, .lightbox-anchor').addClass('is-active');
		}


		/**
		 * shutdown lightbox, and remove event handlers
		 */
		function hideLightbox() {
			$(window).off('resize', resizing);
			$(window).off('scroll', scrolling);

			// hide it
			$('.lightbox-blackout, .lightbox-anchor').removeClass('is-active');
		}


		/**
		 * handles the document resize
		 */
		function resizing() {
			clearTimeout(timer);
			timer = setTimeout(resizeSingle, timerDelay);
		}

		function scrolling() {
			clearTimeout(timer);
			timer = setTimeout(scrollSingle, timerDelay);
		}

		function resizeSingle() {

			// the lightbox
			$('.lightbox')
				.css({
					width: document.documentElement.clientWidth - 100
					, height: document.documentElement.clientHeight - 100
				});

			// content box
			$('.lightbox-content').height($('.lightbox').height() - 28);

			// gallery height
			$('.lightbox-gallery').height($('.lightbox-content').height() - 28);

			$('.lightbox-content img').load(positionAndScaleLightboxImage);
			positionAndScaleLightboxImage();
		}


		/**
		 * handles the document scroll
		 */
		function scrollSingle() {
			$('.lightbox').css({
				top: $(window).scrollTop() + 50
			});
		}


		/**
		 * main image position
		 */
		function positionAndScaleLightboxImage() {
			var originalImage = new Image();
			originalImage.src = $('.lightbox-content img').attr('src');
			originalImage.onload = function() {
				var overflow = originalImage.height - $('.lightbox-content').height();
				if (overflow > 0) {
					$('.lightbox-content img').css('height', (originalImage.height - overflow - 40));
				};
				$('.lightbox-content img').css({
					left: (($('.lightbox-content').width() / 2) - ($('.lightbox-content img').width() / 2))
					, top: (($('.lightbox').height() / 2) - ($('.lightbox-content img').height() / 2))
				});			
			}

		}


		/**
		 * updates the main image in lightbox
		 * @param  {string} largeUrl 
		 */
		function updateContentHero(largeUrl) {
			$('.lightbox-content').html(
				'<img src="' + largeUrl + '">'
			);
			$('.lightbox-content img').load(positionAndScaleLightboxImage);
			positionAndScaleLightboxImage();
		}


		/**
		 * updates content area with ajax result
		 * @param  {string} url 
		 */
		function updateContentInline(url) {
			$('.lightbox-content').addClass('ajax');
			$.get(
				url,
				function(result) {
					$('.ajax').removeClass('ajax');
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
						+ '<span class="lightbox-remove" title="Close">&times;</span>'
						+ '<p class="lightbox-title"><a href="#" class="lightbox-title-link"></a></p>'
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
