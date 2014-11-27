<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"pages_home" => "Startsida",
						"pages_industry_service" => "Bransch/tjänst",
						"pages_help" => "Välj vilka exempel kategorier som ska visas på startsidan.",
						"pages_sample_categories" => "Exempelkategorier",
						"pages_table_header_picture" => "BILD",
						"pages_table_header_title" => "RUBRIK",
						"pages_table_header_industry_service" => "BRANSCH/TJÄNST",

						"categories_wedding_party" => "Bröllopsfest",
						
						"pages_item_edit" => "Redigera",
						"pages_item_delete" => "Ta bort",

						"pages_user_search_display" => "Visar",
						"pages_user_search_of" => "av",
						"pages_user_search_matched_records" => "matchade register",

						"pages_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						"pages_save_success_msg" => "Startsida : Information framgångsrikt sparas.",
						"pages_remove_success_msg" => "Startsida : Information framgångsrikt avlägsnades.",

						"pages_confirm_delete_title" => "Avlägsna galleriobjektet",
						"pages_confirm_delete" => "Är du säker på att du vill ta bort galleriet objekt?",

						"ppages_ErrMsg_filename" => "Please select an image.",
						"ppages_ErrMsg_category" => "Please select a category.",
						"ppages_ErrMsg_title" => "Gallery item title cannot be blank.",
					);

	$translation_array_en = array(
						"pages_home" => "Home",
						"pages_industry_service" => "Industry/service",
						"pages_help" => "Select which example categories are to be displayed on the homepage.",
						"pages_sample_categories" => "Example categories",
						"pages_table_header_picture" => "PICTURE",
						"pages_table_header_title" => "TITLE",
						"pages_table_header_industry_service" => "INDUSTRY/SERVICE",
						
						"categories_wedding_party" => "Wedding party",
						
						"pages_item_edit" => "Edit",
						"pages_item_delete" => "Delete",

						"pages_user_search_display" => "Displays",
						"pages_user_search_of" => "of",
						"pages_user_search_matched_records" => "matched records",


						"pages_error_entry_validation" => "Please fill-up the entries properly.",

						"pages_save_success_msg" => "Home : Information successfully saved.",
						"pages_remove_success_msg" => "Home : Information successfully removed.",

						"pages_confirm_delete_title" => "Remove Gallery Item",
						"pages_confirm_delete" => "Are you sure you want to delete the gallery item?",

						"ppages_ErrMsg_filename" => "Please select an image.",
						"ppages_ErrMsg_category" => "Please select a category.",
						"ppages_ErrMsg_title" => "Gallery item title cannot be blank.",
					);

if( ! is_array( $trans ) )
	$trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>