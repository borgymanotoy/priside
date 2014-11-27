<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"cs_title" => "Kundtj�nst",
						"cs_text_label" => "Text",
						"cs_text_sample" => "Vi p� kundtj�nst finns alltid h�r f�r dig f�r att hj�lpa dig med fr�gor och funderingar!\nTveka inte att kontakta oss!",
						"cs_text_telephone" => "Telefon",
						"cs_text_visit" => "Bes�k",
						"cs_text_mail" => "E-post",
						"cs_text_hours_of_operation" => "�ppettider",

						"cs_save_success_msg" => "Kundtj�nst : Information framg�ngsrikt sparas.",
						"cs_update_success_msg" => "Kundtj�nst : Information framg�ngsrikt uppdateras.",
						"cs_error_entry_validation" => "V�nligen fyll upp posterna p� r�tt s�tt.",

						// php errors / etc.

						"pcs_ErrMsg_NoBanner" => "Please select banner image.",
						"pcs_ErrMsg_CSText" => "Customer Service Text cannot be blank.",
						"pcs_ErrMsg_CSPhone" => "Customer Service Phone cannot be blank.",
						"pcs_ErrMsg_CSVisitingAddress" => "Customer Service Visiting Address cannot be blank.",
						"pcs_ErrMsg_CSEmail" => "Customer Service Email cannot be blank.",
						"pcs_ErrMsg_CSHoursOfOperation" => "Customer Service Hours Of Operation cannot be blank.",
					);

	$translation_array_en = array(
						"cs_title" => "Customer Service",
						"cs_text_label" => "Text",
						"cs_text_sample" => "We at the customer service is always here for you to help you with questions and concerns!\nDo not hesitate to contact us!",
						"cs_text_telephone" => "Telephone",
						"cs_text_visit" => "Visit",
						"cs_text_mail" => "Mail",
						"cs_text_hours_of_operation" => "Hours of operation",

						"cs_save_success_msg" => "Customer Service : Information are successfully saved.",
						"cs_update_success_msg" => "Customer Service : Information are successfully updated.",
						"cs_error_entry_validation" => "Please fill-up the entries properly.",

						// php errors / etc.

						"pcs_ErrMsg_NoBanner" => "Please select banner image.",
						"pcs_ErrMsg_CSText" => "Customer Service Text cannot be blank.",
						"pcs_ErrMsg_CSPhone" => "Customer Service Phone cannot be blank.",
						"pcs_ErrMsg_CSVisitingAddress" => "Customer Service Visiting Address cannot be blank.",
						"pcs_ErrMsg_CSEmail" => "Customer Service Email cannot be blank.",
						"pcs_ErrMsg_CSHoursOfOperation" => "Customer Service Hours Of Operation cannot be blank.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>