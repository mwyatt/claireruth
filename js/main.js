/** 
 * app
 */
function app () {
	$('.js-lightbox-gallery').lightbox({
		galleryClass: 'group-1'
		, title: $('h1.main').html()
	});	
}


/**
 * ready!
 */
$(document).ready(function() {

	// getscripts
	$.when(
	    $.getScript(url.base + 'js/reusable.js')
	    , $.getScript(url.base + 'js/jquery.lightbox.js')
	    // $.Deferred(function( deferred ){
	    //     $( deferred.resolve );
	    // })
	).done(app);
});
 