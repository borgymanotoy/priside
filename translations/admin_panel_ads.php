<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
					"ads_title" => "Annonser",
					"ads_default_search_text" => "Sök annons (t.ex. kund, annonsnummer eller startdatum)",

					"ads_active_ads" => "Aktiva annonser",
					"ads_pickled" => "Inlagda",
					"ads_booked" => "Bokade",
					"ads_completed" => "Avslutade",

					"ads_annonsnr" => "ANNONS NR",
					"ads_customer" => "KUND",
					"ads_investment" => "PLACERING",
					"ads_start" => "START",
					"ads_end" => "SLUT",
					"ads_screenings" => "VISNINGAR",
					"ads_unique_impr" => "UNIKA VISN",
					"ads_click" => "KLICK",
					"ads_status" => "STATUS",

					"ads_active" => "aktiv",
					"ads_added" => "inlagd",
					"ads_closed" => "bokad",
					"ads_booked" => "avslutad",

					"ads_search_display" => "Visar",
					"ads_search_of" => "av",
					"ads_search_matched_records" => "matchade register",

					"pads_no_selected_Status" => "Välj en annons status.",
					"pads_no_selected_customer" => "Välj en kund.",
					);

	$translation_array_en = array(
					"ads_title" => "Ads",
					"ads_default_search_text" => "Search advertisement (eg customer, ad number or start date)",

					"ads_active_ads" => "Active",
					"ads_pickled" => "Added",
					"ads_booked" => "Booked",
					"ads_completed" => "Closed",

					"ads_annonsnr" => "AD NO",
					"ads_customer" => "CUSTOMER",
					"ads_investment" => "INVESTMENT",
					"ads_start" => "START",
					"ads_end" => "END",
					"ads_screenings" => "VIEWS",
					"ads_unique_impr" => "UNIQUE VIEWS",
					"ads_click" => "CLICK",
					"ads_status" => "STATUS",

					"ads_active" => "active",
					"ads_added" => "added",
					"ads_closed" => "closed",
					"ads_booked_small" => "booked",

					"ads_search_display" => "Displays",
					"ads_search_of" => "of",
					"ads_search_matched_records" => "matched records",

					"pads_no_selected_Status" => "Please select an advertisement status.",
					"pads_no_selected_customer" => "Please select a customer.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>
