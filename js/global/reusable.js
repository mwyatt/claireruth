/**
 * generic keyup
 */
$(document).keyup(function(event) {

	// escape
	if (event.keyCode == 27) {
		$('.is-active').removeClass('is-active');
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
