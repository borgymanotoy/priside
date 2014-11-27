<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
		"cust_nfo_actv_ans_inquiries" => "Besvarade förfrågningar",
		"cust_nfo_actv_comp_inquiries" => "Utförda uppdrag",
		"cust_nfo_actv_inquiries" => "Förfrågningar",

		"actv_inquiry" => "FÖRFRÅGNING",
		"actv_place" => "ORT",
		"actv_date" => "DATUM",
		"actv_status" => "STATUS",

		"actv_data_hdr_sample" => "Badrum/våtrum, Landskrona",
		"actv_added" => "Inkom",
		"actv_client" => "Uppdragsgivare",
		"actv_type" => "Utföres åt",
		"actv_when" => "När",
		"actv_description" => "Beskrivning",
		"actv_attached_file" => "Bifogad fl",
		
		"actv_sample_desc" => "Renovering av 2 badrum från 1970. Fuktskada vid golvbrunn. Behöver prisförslag på vad det skulle kosta för badrum på ca 8 och 5 kvm. Kakel och klinkers köpes själv men material för att lägga golvet ska ingå. Gärna förslag både med och utan golvvärme och inbyggda spottar i taket. Litet badrum har snedtak. Maila gärna för att få mer information.",
		"actv_sample_data" => "Badrum/våtrum",

		"actv_reject_note" => "Meddelande till kunden",

		"actv_no_records_found" => "Inga poster hittades",
	);

	$translation_array_en = array(
		"cust_nfo_actv_ans_inquiries" => "Answered inquiries",
		"cust_nfo_actv_comp_inquiries" => "Completed inquiries",
		"cust_nfo_actv_inquiries" => "Inquiries",

		"actv_inquiry" => "INQUIRY",
		"actv_place" => "PLACE",
		"actv_date" => "DATE",
		"actv_status" => "STATUS",

		"actv_data_hdr_sample" => "Bathroom / wet room, Landskrona",
		"actv_added" => "Added",
		"actv_client" => "Client",
		"actv_type" => "Performed at",
		"actv_when" => "When",
		"actv_description" => "Description",
		"actv_attached_file" => "Attached file",

		"actv_sample_desc" => "Renovation of two bathrooms of 1970. Moisture Damage at the floor drain. Need quote on what it would cost the bathroom at about 8 and 5 square meters. Tiles purchase itself, but materials for installing the floor must be included. Gladly proposals both with and without floor heating and built-in spotlights in the ceiling. Small bathroom has a sloping ceiling. Please email for more information.",
		"actv_sample_data" => "Bathroom / wetroom",
		
		"actv_reject_note" => "Notice to customer",

		"actv_no_records_found" => "No records found",
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>