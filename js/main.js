$(document).ready(function() {  
	$('.js-lightbox-gallery').lightbox({
		galleryClass: 'group-1',
		title: $('h1.main').html()
	});	
	var topButton = new Button_To_Top({
		button: '.to-top'
	});
});
