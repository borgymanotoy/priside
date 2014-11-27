<?php
	$translation_array_sv = array(
						"consumer" => "Regelbunden",
						"supplier" => "Premium",
						"advertiser" => "Annons�r",
						"priside" => "Priside",
						
						"pending" => "Avvaktan",
						"approved" => "Godk�nd",
						"denied" => "Nekas",
						
						"controls_select" => "v�lj",
						"button_add" => "L�gg till",
						"button_save" => "Spara",
						"button_remove" => "Ta bort",
						"button_cancel" => "Avbryt",
						"button_send"=>"Skicka",
						"button_choose_picture" => "V�lj bild",
						"button_state_cancel"=>"Avbryt Ladda upp",
						"button_close" => "St�nga",

						"text" => "Text",

						"lang_previous" => "F�reg�ende",
						"lang_next" => "N�sta",

						"search_as" => "Vad",
						"search_was" => "Var",
						"search_select" => "&nbsp;&nbsp;v�lj&nbsp;&nbsp;",

						"search_display" => "Visar",
						"search_of" => "av",
						"search_matched_customers_users" => "matchade kunder/anv�ndare",

						"field_industry" => "Branscher",
						"field_localities" => "Kommuner",

						"col_industry_hdr" => "Bransch",
						"col_location_hdr" => "Kommun",

						"help" => "HJ�LP",
					);

	$translation_array_en = array(
						"consumer" => "Regular",
						"supplier" => "Premium",
						"advertiser" => "Advertiser",
						"priside" => "Priside",

						"pending" => "Pending",
						"approved" => "Approved",
						"denied" => "Denied",
						
						"controls_select" => "select",
						"button_add" => "Add",
						"button_save" => "Save",
						"button_remove" => "Remove",
						"button_cancel" => "Cancel",
						"button_send"=>"Send",
						"button_choose_picture" => "Choose a picture",
						"button_state_cancel"=>"Cancel Upload",
						"button_close" => "Close",

						"text" => "Text",

						"lang_previous" => "Previous",
						"lang_next" => "Next",

						"search_as" => "What",
						"search_was" => "Where",
						"search_select" => "select",

						"search_display" => "Displays",
						"search_of" => "of",
						"search_matched_customers_users" => "matched customers / users",

						"field_industry" => "Industry",
						"field_localities" => "Localities",

						"col_industry_hdr" => "Industry",
						"col_location_hdr" => "Municipality",

						"help" => "HELP",
					);

	if( ! is_array( $trans ) ) $trans = array();

	if( "en" == $lang ){
		$trans = array_merge( $trans, $translation_array_en );
	}
	else{
		$trans = array_merge( $trans, $translation_array_sv );
	}
?>