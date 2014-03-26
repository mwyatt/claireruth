$(document).ready(function() {  
	// $('.js-lightbox-gallery').lightbox({
	// 	galleryClass: 'group-1',
	// 	title: $('h1.main').html()
	// });	
	var topButton = new Button_To_Top({
		button: '.to-top'
	});

	$('.js-header-button-mobile-menu').on('click', function(event) {
		event.preventDefault();
		$(this).closest('.js-container-header').toggleClass('is-menu-open');
	});
});
