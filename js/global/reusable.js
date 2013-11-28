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
 * sets the submit classes found within the forms to handle
 * prevents double submission with keyboard and button press
 * form submission
 */
function setSubmit() {

	// generic form submission
	$('form')
		.off('submit')
		.on('submit', function(event) {
			var form = $(this);

			// dont submit if already submitting
			if (form.hasClass('is-submitting')) {
				return false;
			}
			form.addClass('is-submitting');
		});

	// a.submit buttons trigger form submission
	$('form')
		.find('.submit')
		.off('click')
		.on('click',  function(event) {
			event.preventDefault();
			var button = $(this);

			// dont submit if already disabled
			if (button.hasClass('disabled')) {
				return false;
			}
			button.addClass('disabled');
			button.closest('form').submit();
		});
}


/**
 * Array.prototype.[method name] allows you to define/overwrite an objects method
 * needle is the item you are searching for
 * this is a special variable that refers to "this" instance of an Array.
 * returns true if needle is in the array, and false otherwise
 */
Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] == needle) return true;
   }
   return false;
}
