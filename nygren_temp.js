$(document).ready(function() {
   // Custom validation error messages for form fields.
//   var validationMessages =
//   {
//      companyBusinessNbr: {
//         personnummer: "Organization number must a 10 or 12 digit number," +
//            " with format 123456-7890 or 12345678-9012."
//      }
//   }

   //function setupFormValidation(form, rules, customMessages, createIcon) {


   // Show element's validation error when hovering error icon.
   $('.formErrorIcon').hover(
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

   // Add custom validation methods.
   //$.validator.addMethod("personnummer", validatePersonnummer, "Invalid number.");
   //$.validator.addMethod("postalcode", validatePostalCode, "Invalid postal code.");

   // Setup the validation for our form.
   $('#theForm').validate(
   {
      debug: false,

      errorClass:     "formerror",
      errorContainer: "#formInvalid", // Show this when there's an error
      wrapper:        "li",           // Wrap error labels in <li>
      highlight:       errorHighlightHandler,
      unhighlight:     errorUnhighlightHandler,
      
      errorPlacement: errorPlacementHandler,
      submitHandler:  submitFormHandler,
      
      //messages : validationMessages
   });

}); // $(document).ready()


function errorHighlightHandler(element, errorClass) {
	$(element).addClass(errorClass);

	// See if there's an error icon that needs to be shown.
	var $icon = getErrorIcon(element);
	if (null != $icon) {
		$icon.show();
	}
}


function errorUnhighlightHandler(element, errorClass) {
	$(element).removeClass(errorClass);

	// See if there's an error icon that needs to be hidden.
	var $icon = getErrorIcon(element);
	if (null != $icon) {
		$icon.hide();
	}
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

/**
 * Places form validation error messages.
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
		// 2. .errorListContainer > .errorList search.
	   $elementErrorList = element.siblings('.errorListContainer').children('.errorList');
	}

	// Only append error if we've found a unique element to add it to.	
	if ($elementErrorList.length == 1) {
		$elementErrorList.append(error);
	}
   
	// === Add to summary list of all errors.
	$('#formInvalid').children('.errorList').append(error.clone());
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
