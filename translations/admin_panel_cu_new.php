<?php

	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"cu_new_title" => "Ny kund",
						"cu_new_account" => "Kontotyp",

						"cu_new_organization" => "Organisationsform",
						"cu_new_co_org" => "Företag/organisation",

						"cu_new_industry_caption" => "Bransch",
						"cu_new_corporate_caption" => "Organisationsnummer",
						"cu_new_municipality_caption" => "Kommun",

						"cu_new_first_name" => "Kontaktperson, förnamn",
						"cu_new_last_name" => "Kontaktperson, efternamn",
						"cu_new_person_no" => "Personnummer",
						"cu_new_mail" => "E-post",
						"cu_new_telephone" => "Telefon",
						"cu_new_alt_telephone" => "Alternativ Telefon",
						"cu_new_address" => "Adress",
						"cu_new_zipcode" => "Postnr.",
						"cu_new_location" => "Ort",

						"cu_new_sales_account_manager" => "Säljare/kundansvarig",
						"cu_new_notes" => "Anteckningar",

						"cu_new_upld_audio_rec" => "Ladda upp ljudinspelning",
						"cu_new_upld_audio_contract" => "Ladda upp avtal",
						"cu_new_prnt_order_conf" => "Skriv ut orderbekräftelse", 
						
						"cu_new_required_field" => "är ett obligatoriskt fält",
						"cu_new_not_valid_email"=> "är inte en giltig e-postadress",
						"cu_new_no_service_selected"=> "välj iallafall en bransch",
						"cu_new_no_municipality_selected"=> "välj iallafall en kommun",
						"cu_new_no_organization_selected" => "välj organisationen",
						"cu_new_upload_audio" => "Ladda upp ljudinspelning",
						"cu_new_upload_contract" => "Ladda upp avtal",
						"cu_new_cancel_upload" => "Avbryt Ladda upp"
					);

	$translation_array_en = array(
						"cu_new_title" => "New customer",
						"cu_new_account" => "Account",

						"cu_new_organization" => "Organization",
						"cu_new_co_org" => "Company / organization",

						"cu_new_industry_caption" => "Industry",
						"cu_new_corporate_caption" => "Organization No.",
						"cu_new_municipality_caption" => "Municipality",

						"cu_new_first_name" => "Contact, first name",
						"cu_new_last_name" => "Contact, last name",
						"cu_new_person_no" => "Personal Code Number",
						"cu_new_mail" => "Email",
						"cu_new_telephone" => "Telephone",
						"cu_new_alt_telephone" => "Alternative Telephone",
						"cu_new_address" => "Address",
						"cu_new_zipcode" => "Zip Code.",
						"cu_new_location" => "Location",

						"cu_new_sales_account_manager" => "Sales / account manager",
						"cu_new_notes" => "Notes",

						"cu_new_upld_audio_rec" => "Upload audio recording",
						"cu_new_upld_audio_contract" => "Upload contract",
						"cu_new_prnt_order_conf" => "Print order confirmation",
						
						"cu_new_required_field" => "is a required field",
						"cu_new_not_valid_email"=> "is not a valid email address",
						"cu_new_no_service_selected"=> "please select atleast one industry",
						"cu_new_no_municipality_selected"=> "please select atleast one municipality",
						"cu_new_no_organization_selected" => "please select organization",
						"cu_new_upload_audio" => "Upload audio recording",
						"cu_new_upload_contract" => "Upload contract",
						"cu_new_cancel_upload" => "Cancel Upload"
					);

	if( ! is_array( $trans ) ) $trans = array();

	if( "en" == $lang ){
		$trans = array_merge( $trans, $translation_array_en );
	}
	else{
		$trans = array_merge( $trans, $translation_array_sv );
	}

?>