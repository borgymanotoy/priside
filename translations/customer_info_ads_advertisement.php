<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/customer_info_ads.php" );

	$translation_array_sv = array(
			"cust_nfo_ads_advertising" => " Annonsering",
			"cust_nfo_ads_book_advertisement" => "Boka annons",
			"cust_nfo_ads_annonsnr" => "ANNONS NR",
			"cust_nfo_ads_investment" => "PLACERING",
			"cust_nfo_ads_start" => "START",
			"cust_nfo_ads_end" => "SLUT",
			"cust_nfo_ads_screenings" => "VISNINGAR",
			"cust_nfo_ads_unique_impr" => "UNIKA VISN",
			"cust_nfo_ads_click" => "KLICK",
			"cust_nfo_ads_status" => "STATUS",
			"cust_nfo_ads_link_url" => "Länkadress",
			"cust_nfo_ads_box_advertisement" => "Annons",

			"cust_nfo_ads_active" => "aktiv",
			"cust_nfo_ads_added" => "inlagd",
			"cust_nfo_ads_booked" => "bokad",
			"cust_nfo_ads_closed" => "avslutad",
			"cust_nfo_ads_select_status" => "Välj status",

			"cust_nfo_ads_search_display" => "Visar",
			"cust_nfo_ads_search_of" => "av",
			"cust_nfo_ads_search_matched_records" => "matchade register",

			"cust_nfo_ads_search_success_save" => "Annons uppdaterats.",

			"pcust_nfo_ads_account" => "Välj ett konto.",
			"pcust_nfo_ads_customer" => "Välj en kund.",
			"pcust_nfo_ads_fill_fields" => "Vänligen fyll i alla obligatoriska fält.",
			"pcust_nfo_ads_advertisement" => "Välj en annons."
	);

	$translation_array_en = array(
			"cust_nfo_ads_advertising" => " Advertising",
			"cust_nfo_ads_book_advertisement" => "Book your ad",
			"cust_nfo_ads_annonsnr" => "AD NO",
			"cust_nfo_ads_investment" => "INVESTMENT",
			"cust_nfo_ads_start" => "START",
			"cust_nfo_ads_end" => "END",
			"cust_nfo_ads_screenings" => "VIEWS",
			"cust_nfo_ads_unique_impr" => "UNIQUE VW",
			"cust_nfo_ads_click" => "CLICK",
			"cust_nfo_ads_status" => "STATUS",
			"cust_nfo_ads_link_url" => "Link URL",
			"cust_nfo_ads_box_advertisement" => "Advertisement",

			"cust_nfo_ads_active" => "active",
			"cust_nfo_ads_added" => "added",
			"cust_nfo_ads_booked" => "booked",
			"cust_nfo_ads_closed" => "closed",
			"cust_nfo_ads_select_status" => "Select status",

			"cust_nfo_ads_search_display" => "Displays",
			"cust_nfo_ads_search_of" => "of",
			"cust_nfo_ads_search_matched_records" => "matched records",

			"cust_nfo_ads_search_success_save" => "Advertisement successfully updated.",

			"pcust_nfo_ads_account" => "Please select an account.",
			"pcust_nfo_ads_customer" => "Please select a customer.",
			"pcust_nfo_ads_fill_fields" => "Please fill all the required fields.",
			"pcust_nfo_ads_advertisement" => "Please select an advertisement."
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>