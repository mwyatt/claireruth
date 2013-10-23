/**
 * finds a class and removes it
 */
function selectClassAndRemove (className) {
	$('.' + className).removeClass(className);
}


/**
 * generic keyup while on the document
 * @param  {object} event 
 */
function handleKeyup (event) {

	// backspace
	if (e.keyCode == 8) {
		search.poll;
	}

	// escape
	if (e.keyCode == 27) {
		selectClassAndRemove('is-active');
	} 
}


/**
 * handles generic form submission
 * @todo possible to integrate the ajax script to validate?
 * @return {bool} 
 */
function formSubmitDisable() {
	if ($(this).hasClass('disabled')) {
		return false;
	}
	$(this).addClass('disabled');
	$(this).closest('form').submit();
	return false;
}

// global variables
var ajax = '<div class="ajax"></div>';
var urlBase = $('body').data('url-base'),

// generic keyup
$(document).keyup(handleKeyup);

// prevent ajax cache
$.ajaxSetup ({  
	cache: false  
});

// form submission
$('form').find('a.submit').on('mouseup', formSubmitDisable);
