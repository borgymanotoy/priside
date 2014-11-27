<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"careers_driver_account_manager_wanted" => "Driven Account Manager sökes",
						"careers_experienced_sales_manager_wanted" => "Erfaren säljledare sökes",
						"careers_title_service" => "Rubrik/tjänst",

						"careers_save_success_msg" => "Jobba hos oss : Information framgångsrikt sparas.",
						"careers_update_success_msg" => "Jobba hos oss : Information framgångsrikt uppdateras.",
						"careers_remove_success_msg" => "Jobba hos oss : Information framgångsrikt avlägsnades.",
						"careers_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						"careers_confirm_delete_title" => "Ta bort markerade karriär",
						"careers_confirm_delete" => "Är du säker på att du vill ta bort den valda karriär?",

						// php errors / etc.
						"pcareers_ErrMsg_filename" => "Banner image cannot be blank.",
						"pcareers_ErrMsg_CareerId" => "Career Id cannot be blank.",
						"pcareers_ErrMsg_CareerTitle" => "Career Title cannot be blank.",
						"pcareers_ErrMsg_CareerDescription" => "Career Description cannot be blank.",
					);

	$translation_array_en = array(
						"careers_driver_account_manager_wanted" => "Driven Account Manager wanted",
						"careers_experienced_sales_manager_wanted" => "Experienced Sales Manager wanted",
						"careers_title_service" => "Title / Service",

						"careers_save_success_msg" => "Careers : Information successfully saved.",
						"careers_update_success_msg" => "Careers : Information successfully updated.",
						"careers_remove_success_msg" => "Careers : Information successfully removed.",
						"careers_error_entry_validation" => "Please fill-up the entries properly.",

						"careers_confirm_delete_title" => "Remove selected career",
						"careers_confirm_delete" => "Are you sure you want to delete the selected career?",

						// php errors / etc.
						"pcareers_ErrMsg_filename" => "Banner image cannot be blank.",
						"pcareers_ErrMsg_CareerId" => "Career Id cannot be blank.",
						"pcareers_ErrMsg_CareerTitle" => "Career Title cannot be blank.",
						"pcareers_ErrMsg_CareerDescription" => "Career Description cannot be blank.",
						);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>