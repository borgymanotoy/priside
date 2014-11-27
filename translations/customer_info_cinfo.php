<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"cust_nfo_cinfo_co" => "Företag",
						"cust_nfo_cinfo_corp" => "Organisationsnummer",

						"cust_nfo_cinfo_fname" => "Kontaktperson, förnamn",
						"cust_nfo_cinfo_lname" => "Kontaktperson, efternamn",
						"cust_nfo_cinfo_civic" => "Personnummer",
						"cust_nfo_cinfo_mail" => "E-post",
						"cust_nfo_cinfo_tel" => "Telefon",
						"cust_nfo_cinfo_alt_tel" => "Alternativ Telefon",
						"cust_nfo_cinfo_addr" => "Adress",
						"cust_nfo_cinfo_zip" => "Postnr",
						"cust_nfo_cinfo_location" => "Ort",
						"cust_nfo_cinfo_update_success" => "Kundinformation har uppdaterats",
						"cust_nfo_cinfo_required_field" => "är ett obligatoriskt fält",
						"cust_nfo_cinfo_invalid_email" => "är inte en giltig e-postadress",
						"cust_nfo_organization" => "Organisationsform",
						"cust_nfo_industry_caption" => "Bransch",
						"cust_nfo_municipality_caption" => "Kommun",
						"cust_nfo_organization_selected" => "välj organisationen",
						"cust_nfo_no_service_selected"=> "välj iallafall en bransch",
						"cust_nfo_no_municipality_selected"=> "välj iallafall en kommun",
	);

	$translation_array_en = array(
						"cust_nfo_cinfo_co" => "Company",
						"cust_nfo_cinfo_corp" => "Organization No.",

						"cust_nfo_cinfo_fname" => "Contact, first name",
						"cust_nfo_cinfo_lname" => "Contact, last name",
						"cust_nfo_cinfo_civic" => "Personal Code Number",
						"cust_nfo_cinfo_mail" => "Email",
						"cust_nfo_cinfo_tel" => "Telephone",
						"cust_nfo_cinfo_alt_tel" => "Alternative Telephone",
						"cust_nfo_cinfo_addr" => "Address",
						"cust_nfo_cinfo_zip" => "Zip Code",
						"cust_nfo_cinfo_location" => "Location",
						"cust_nfo_cinfo_update_success" => "Customer information was successfully updated",
						"cust_nfo_cinfo_required_field" => "is a required field",
						"cust_nfo_cinfo_invalid_email" => "is not a valid email address",
						"cust_nfo_organization" => "Organization",
						"cust_nfo_industry_caption" => "Industry",
						"cust_nfo_municipality_caption" => "Municipality",
						"cust_nfo_organization_selected" => "please select the organization",
						"cust_nfo_no_service_selected"=> "please select atleast one industry",
						"cust_nfo_no_municipality_selected"=> "please select atleast one municipality"
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>