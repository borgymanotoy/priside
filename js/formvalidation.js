/**
 * FormAutoValidator add form validation and displays error when validation
 * fails. It is based on jQuery Validator plugin:
 *  http://docs.jquery.com/Plugins/Validation.
 *
 * Quick introduction
 * ==================
 * Being based on jQuery Validator any field within your form that adds
 * validation rules will be validated.
 * FormAutoValidator places the error messages in custom places (see further
 * description below). You do not need to define these places for your fields
 * if you do not wish to do so. To have the form validator automatically add
 * these target element/places, just add the class "autoValidate" to the fields
 * for which you want this behaviour.
 * NOTE: For radio buttons and check boxes it is recommended to add the
 * error targets manually as otherwise they will be placed next to one of the
 * items and not the others which might look strange in the user interface.
 *
 * To get the correct behavior you also need the styles defined in
 * formvalidation.css.
 *
 * Error class
 * -----------
 * The CSS class attached to invalid input fields is "formerror".
 *
 * Custom validation rules
 * -----------------------
 * In addition to the validation rules provided by jQuery Validator plugin,
 * FormAutoValidator also registers the following rules:
 *   * personnummer - for fields that should contain a Swedish person- or
 *                    organization number.
 *   * postalcode   - for fields that should contain Swedish postal codes
 *                    (aka postnummer in Sweden).
 *
 * The details
 * =====================
 * By default error messages are intended to be shown to the user by showing an
 * error icon next to the invalid field. Hovering this icon will then show
 * the error message for the field. All error messages are also added to a common
 * list for the form.
 *
 * The icon
 * --------------------
 * The icon need not actually be an icon. FormAutoValidator simply looks for an
 * element with a particular id or class and shows it when the corresponding
 * input element validation fails. The documentation will refer to this element
 * as the error icon for the field from here on.
 * 
 * When the icon needs to be shown FormAutoValidator will look for an element
 * with id <fieldname>_formErrorIcon, where <fieldname> is the name of the
 * input field that failed invalidation. If it can't find this id it will look
 * for an element with the class "formErrorIcon" that is a sibling of the input
 * field. If more than one element with class formErrorIcon is sibling to the
 * input field, then no icon will be shown.
 *
 * Hovering the icon will show an <ul> containing the errors for the
 * icon. The <ul> will be moved so that it's positioned close to the icon.
 * This list shall have the class <fieldname>_errorList.
 * Alternatively it can be identified by ".errorListContainer > .formErrorList",
 * but this is only used by AutoFormValidator if the id can't be found in the
 * DOM.
 * 
 * List of all errors
 * ------------------
 *  In addition to each field's validation error being added to a list related
 *  to the input field, all errors are also shown in a common error list.
 *  This list shall be a class named "" inside of an element with id
 *  "formInvalid", ie the CSS selector for it would be
 *  "#formInvalid > .formErrorList".
 *  TODO: Better naming scheme!
 */
var FormAutoValidator = function() {


	//===========================================================================
	//= Private fields                                                          = 
	//=   - Access without using "this.".                                       = 
	//=========================================================================== 

	var that = this;  // Used to get access to "this" inside of "private" functions.
	var $form;




	//===========================================================================
	//= Private methods                                                         = 
	//=   - Call them without using "this.".                                    = 
	//=   - Use variable "that" instead of "this" inside private methods.       = 
	//=                                                                         = 
	//=========================================================================== 


	/**
	 * Adds the tags necessary to display error information for an input field.
	 *
	 * If the element already has an error icon, no tags will be added.
	 */
	function addElementErrorTags(element) {
		var name = element.attr('name');
		var type = element.attr('type');
		if (type.match(/submit/i)) {
			return; // Don't add tags to the submit button.
		}


		// Check if there are error elements already.
		var icon = getErrorIcon(element);
		if (icon != null) {
			// Icon already exists, just add hover handler to it.
			 addErrorIconHoverListener(icon);
			return; 
		}

		// No error icon, so add error targets/tags.
		// BUT only if the element has the class "autoValidate".
		if (!element.hasClass('autoValidate')) {
			return;
		}	
		var iconId = name + '_formErrorIcon';
	   var errorListId = name + '_errorList';
		var html = '<img id="' + iconId + '" class="formErrorIcon" alt="Error"' +
		  	' src="/img/form_error_icon.png">\n' +
         '<div class="errorListContainer"><ul id="' + errorListId + '"' +
			' class="formErrorList"></ul></div>';
	   element.after(html);

		icon = getErrorIcon(element);
		addErrorIconHoverListener(icon);
	}

	/**
	 * Attach listener for mouse hover to an error icon.
	 *
	 * Listener will show the error list when mouse is hovering over the error
	 * icon.
	 *
	 * @param icon  jQuery object of the error icon DOM element.
	 */
	function addErrorIconHoverListener($icon) {
		// Show element's validation error when hovering error icon.
		$icon.hover(
			function(event) {
				$errorList = $(this).siblings('.errorListContainer');
				$errorList.css('left', event.pageX - $('html').scrollLeft());
				$errorList.css('top', event.pageY - $('html').scrollTop() + 20);
				$errorList.show();
			},
			function() {
				$(this).siblings('.errorListContainer').hide();
			}
		);
	}


	/**
	 * Returns the element's error icon.
	 *
	 * An icon can be identified in two ways:
	 * 1. It has an id like this: element.name + '_formErrorIcon'.
	 * 2. It is a sibling to the element and has a class of .formErrorIcon.
	 *
	 * The first is safer since it doesn't risk conflicts as long as
	 * the name is unique on the page (it might not be if there are two forms
	 * using the same element name for a field).
	 *
	 * @returns  The icon element as a jQuery object or null if no icon was found
	 *           or if we found several icons.
	 */
	function getErrorIcon(element) {
		var $icon;
	  
		// 1. Id search.
		var idName = '#' + $(element).attr('name') + '_formErrorIcon';
		$icon = $(idName);
		if ($icon.length != 1) {
			// 2. .formErrorIcon class search.
			$icon = $(element).siblings('.formErrorIcon');
		}

		if ($icon.length != 1) {
			// Oops, there are none or more than one icon at the same level as the
			// input element. We don't know which one to use...
			return null;
		}

		return $icon;
	}



	//===========================================================================
	//= Private methods                                                         = 
	//=   -  jQuery Validation plugin callbacks.                                = 
	//=                                                                         = 
	//=========================================================================== 

	/**
	 *
	 * jQuery Validation plugin callback.
	 */
	function errorHighlightHandler(element, errorClass) {
		$(element).addClass(errorClass);

		// See if there's an error icon that needs to be shown.
		var $icon = getErrorIcon(element);
		if (null != $icon) {
			$icon.show();
		}
	}

	/**
	 *
	 * jQuery Validation plugin callback.
	 */
	function errorUnhighlightHandler(element, errorClass) {
		$(element).removeClass(errorClass);

		// See if there's an error icon that needs to be hidden.
		var $icon = getErrorIcon(element);
		if (null != $icon) {
			$icon.hide();
		}
	}



	/**
	 * Places form validation error messages.
	 *
	 * jQuery Validation plugin callback.
	 *
	 * @param error   A label element containing the error message.
	 * @param element The DOM element that failed validation.
	 */
	function errorPlacementHandler(error, element) {
		// Add it to popup and also the summary list of all errors.

		// === Add to popup.
		var $elementErrorList;
		// 1. Id search.
		var idName = '#' + $(element).attr('name') + '_errorList';
		$elementErrorList = $(idName);
		if ($elementErrorList.length != 1) {
			// 2. .errorListContainer > .formErrorList search.
			$elementErrorList = element.siblings('.errorListContainer').children('.formErrorList');
		}

		// Only append error if we've found a unique element to add it to.	
		if ($elementErrorList.length == 1) {
			$elementErrorList.append(error);
		}

		// === Add to summary list of all errors.
		$('#formInvalid').children('.formErrorList').append(error.clone());
	}


	//===========================================================================
	//= Public members                                                          =
	//=   - Access internally using "this.method()"                             = 
	//===========================================================================


	/**
	 *
	 * TODO: Allow custom submit handler or target url!
	 *
	 * @param formId   CSS id of the form to validate.
	 * @param options  map of options to use.
	 *        Available options are:
	 *        errors : {
	 *          	elementName : errorString, ...
	 *        }
	 *        
	 *        errors allows adding errors to the forms before validation has
	 *        started. This can be used when the server rejects the form because
	 *        of server-side validation failure. Error messages from the server
	 *        can then be added to the elements.
	 *
	 *        messages : {
	 *        	elementName : { rule : message }
	 *        }
	 *
	 *        messages allows the caller to replace validation messages for
	 *        elements. It has the same format as jQuery Validation plugin.
	 *        The short hand "messages : { elementName : message }" is also
	 *        possible.
	 *        Messages will not override the messages supplied in the errors
	 *        property.
	 *                 
	 */
	this.setupValidator = function(formId, options) {
		$form = $('#' + formId);

		// Add error icon elements etc for input fields requestion so.
		var $formInputs = $form.find('*').filter(':input');
		$formInputs.each(function(i, input) {
			addElementErrorTags($(input));
		});

		// Messages specified by this class can be put here.
		var validationMessages = {}; 

		// Add/merge caller supplied messages.
		for (property in options) {
			if ('messages' == property) {
				var userMessages = options[property];
				for (var attrname in userMessages) {
					validationMessages[attrname] = userMessages[attrname];
				}
			}
		}

		// Add custom validation methods.
		$.validator.addMethod("personnummer", validatePersonnummer, "Invalid number.");
		$.validator.addMethod("postalcode", validatePostalCode, "Invalid postal code.");

		// Setup the validation for our form.
		var validator = $form.validate(
		{
			debug: false,

			errorClass:     "formerror",
			errorContainer: "#formInvalid", // Show this when there's an error
			wrapper:        "li",           // Wrap error labels in <li>
			highlight:       errorHighlightHandler,
			unhighlight:     errorUnhighlightHandler,

			errorPlacement: errorPlacementHandler,
			submitHandler:  submitFormHandler,

			messages : validationMessages
		});


		// TODO: Error icon isn't always shown...
		$('document').ready(function() {
			for (property in options) {
				if ('errors' == property) {
					validator.showErrors(options[property]);
				}
			}
		});
	}



/**
 * To be called when a form has been validated and should be submitted.
 *
 * Submits the form.
 *
 * @param form  Form DOM element.
 */
function submitFormHandler(form) {
		var values = $(form).serializeArray();
		$.ajax( {
			type: 'POST',
			url:  'admin_panel_cu_new.php',
			data: values,
			success: function(data) { $('#reload').replaceWith( data ); }
		});
}

}; // End FormAutoValidator





/**
 * Utility functions for form validation.
 * 
 * Requires jQuery and jQuery form validation plugin.
 * Tested using:
 *   jQuery 1.7.2.
 *   Validation plugin 1.9.0
 *
 * http://docs.jquery.com/Plugins/Validation
 */

/** Validation method for jQuery validator plugin for Swedish person- and
 * organisationsnummer.
 *
 * Organisationsnummer and personnummer are numbers identifying organizations
 * and persons in Sweden.
 *
 * Verifies that it is a 10 or 12 digit number with an optional dash before
 * the last four digits.
 *
 * Currently it doesn't validate that the control digit is correct other than it
 * being a digit.
 *
 * jQuery validation plugin: http://docs.jquery.com/Plugins/Validation
 */
function validatePersonnummer(value, element) {
	// this.optional(element) will return true if the element is empty.
	// It's used to skip validation of empty, non-required elements.
	// Yes, this is an unfortunate naming on jQuery.validation's part.
	if (this.optional(element)) {
		return true;
	}

	if (undefined == value || null == value) {
		return false;
	}

	// = Check length.
	// Format is either 123456-7890 or 12345678-9012, so length must be 10-13
	//  (we accept omitting the dash).
	var lenValue = value.length;
	if (lenValue < 10 || lenValue > 13) {
		return false;
	}

	var firstPart; // Part of value that is first part of number.

	// = Check dash
	var dashPos = value.indexOf('-');
	if (-1 == dashPos) {
		// No dash. Length is required to be 10 or 12.
		if (lenValue != 10 && lenValue != 12) {
			return false;
		}
		// Without dash. First part of value is all but last 4 characters.
		firstPart = value.substring(0, lenValue - 4);
	} else {
		// We have a dash. It is required to be the 5th character from the end.
		if (dashPos != lenValue - 5) {
			return false;
		}
		// ... and length must be 11 or 13.
		if (lenValue !=11 && lenValue != 13) {
			return false;
		}
		// With dash, first part is all but last 5 characters.
		firstPart = value.substring(0, lenValue - 5);
	}

	// = Check first part of number.
	var number = +firstPart;
	if (isNaN(number)) {
		return false;
	}

	// = Check last part of number.
	var lastPart = value.substring(lenValue-4);
	number = +lastPart;
	if (isNaN(number)) {
		return false;
	}

	// TODO: See if the control number (last digit) is correct.

	// All checks passed.
	return true;
}

/**
 * jQuery validation plugin function for validating a Swedish postal code
 * (postkod).
 *
 * Verifies that the value is five digits with an optional space before the last
 * two digits.
 *
 * jQuery validation plugin: http://docs.jquery.com/Plugins/Validation
 **/
function validatePostalCode(value, element) {
	// this.optional(element) will return true if the element is empty.
	// It's used to skip validation of empty, non-required elements.
	// Yes, this is an unfortunate naming on jQuery.validation's part.
	if (this.optional(element)) {
		return true;
	}

	if (undefined == value || null == value) {
		return false;
	}

	var lenValue = value.length;

	// Format is either "12345" or "123 45". 
	if (lenValue != 5 && lenValue != 6) {
		return false;
	}

	// If length is 5, then there can be no space.
	if (lenValue == 5 && value.indexOf(' ') != -1) {
		return false;
	}

	// If length is 6, then 4th character must be a space.
	if (lenValue == 6 && value[3] != ' ') {
		return false;
	}

	// Check first three digits.
	var part = value.substring(0,3);
	var number = +part;
	if (isNaN(number)) {
		return false;
	}

	// Check last two digits.
	part = value.substring(lenValue-2);
	number = +part;
	if (isNaN(number)) {
		return false;
	}

	return true;
}
