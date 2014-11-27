<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"cyb_title" => "Anslut ditt förretag",

						"cyb_apply_for_provider_account" => "Ansök om leverantörskonto",
						"cyb_button_upload_price_list" => "Ladda upp prislista",
						"cyb_button_upload_agreement" => "Ladda upp avtal",

						"cyb_save_success_msg" => "Anslut ditt företag : Information framgångsrikt sparas.",
						"cyb_update_success_msg" => "Anslut ditt företag : Information framgångsrikt uppdateras.",
						"cyb_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						// php errors / etc.
						"pcyb__ErrMsg_ApplicationContents" => "Application Contents cannot be blank.",
						"pcyb__ErrMsg_filename" => "Filename cannot be blank.",
					);

	$translation_array_en = array(
						"cyb_title" => "Connect your company",
						"cyb_tv_radio" => "Television and radio",

						"cyb_apply_for_provider_account" => "Apply for provider account",
						"cyb_button_upload_price_list" => "Upload price list",
						"cyb_button_upload_agreement" => "Upload agreement",
						"cyb_button_save" => "Save",

						"cyb_save_success_msg" => "Connect your company : Information are successfully saved.",
						"cyb_update_success_msg" => "Connect your company : Information are successfully updated.",
						"cyb_error_entry_validation" => "Please fill-up the entries properly.",

						// php errors / etc.
						"pcyb__ErrMsg_ApplicationContents" => "Application Contents cannot be blank.",
						"pcyb__ErrMsg_filename" => "Filename cannot be blank.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>