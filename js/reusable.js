/**
 * finds a class and removes it
 */
function removeClass (className) {
	$('.' + className).removeClass(className);
}

// generic keyup
$(document).keyup(function(event) {
	
	// backspace
	// if (event.keyCode == 8) {
	// 	search.poll;
	// }

	// escape
	if (event.keyCode == 27) {
		removeClass('is-active');
	} 
});


/**
 * handles generic form submission
 * @todo possible to integrate the ajax script to validate?
 * @return {bool} 
 */
function formSubmitDisable () {
	if ($(this).hasClass('disabled')) {
		return false;
	}
	$(this).addClass('disabled');
	$(this).closest('form').submit();
	return false;
}

// global variables
var ajax = '<div class="ajax"></div>';

// prevent ajax cache
$.ajaxSetup ({  
	cache: false  
});

// form submission
$('form').find('a.submit').on('mouseup', formSubmitDisable);
