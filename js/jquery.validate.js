/**
 * general validation which latches onto a form
 * this then takes control of the submit function
 * and checks all *required* fields before submission
 *
 * all required fields are passed into 'field' object
 * then these can be looped over
 *
 * mustPass means that the custom function must be passed also in order to
 * validate
 *
 * examplemoasmsd.field['form-sku'].mustPass('ALPH-AB-LU623MA')
 * 
 * default rule, must be filled!
 */
(function($){
	$.fn.validate = function(options) {
		if (! this.length) return;
		var core = this;
		var defaults = {
			field: false
		}
		var options = $.extend(defaults, options);
		if (! options.field) {
			return;
		};

		// initial validation check
		poll();

		// apply any custom validation triggers
		// a custom function which will trigger the poll
		$.each(options.field, function(fieldName, rule) {
			if (rule.hasOwnProperty('pollTrigger')) {
				$(rule.pollTrigger.selector).on(rule.pollTrigger.eventName, poll);
			};
		});

		// events
		$(core).find('input').on('change', poll);
		$(core).find('textarea').on('change', poll);
		$(core).find('select').on('change click', poll);

		// button click form submission
		$(core).find('.submit').on('click', function(e) {
			e.preventDefault();
			$(core).submit();
		});

		// general form submission (enter key)
		$(core).submit(function() {
			return submitAndValidate();
		});


		/**
		 * whips through all passed field names, these refer to the ids of the
		 * actual form field
		 */
		function poll() {
			$.each(options.field, function(fieldName, rule) {
				var label = $(core).find('[for="' + fieldName + '"]');
				var input = $(core).find('#' + fieldName);
				var row = $(label).closest('.row');

				// setup the row and label to display validity flags
				$(row)
					.removeClass('validate-is-valid')
					.removeClass('validate-is-invalid')
					.addClass('validate-is-invalid');
				if (! $(row).find('.validate-flag-required').length) {
					$(label).append('<span class="validate-flag-required">(Required)</span>');
				};

				// if its invisible then its valid!
				if (! $(row).is(':visible')) {
					validateRow(row);
				};

				// a custom function which the field must pass
				if (rule.hasOwnProperty('mustPass')) {
					if (! rule.mustPass($(input).val())) {
						return true;
					};
					validateRow(row);
				};

				// an assigned partner which means the partner or this
				// one must be filled
				if (rule.hasOwnProperty('partner')) {
					if (! $('#' + rule.partner).val() && ! $(input).val()) {
						return true;
					};
				};

				// is the field empty and no partner?
				if (! $(input).val() && ! rule.hasOwnProperty('partner')) {
					return true;
				};

				// valid!
				validateRow(row);
			});
		}


		function validateRow(row) {
			$(row)
				.removeClass('validate-is-invalid')
				.addClass('validate-is-valid');
		}

		/**
		 * looking for all 'is-invalid' rows
		 * @return {bool} on the validity of the form
		 */
		function submitAndValidate() {
			poll();
			var valid = true;
			$.each($(core).find('.validate-is-invalid'), function() {
				$('html, body').animate({scrollTop: $(this).offset().top - 100}, 200);
				$(core).find('.disabled').removeClass('disabled');
				return valid = false;
			});
			return valid;
		}
	};
})(jQuery);
