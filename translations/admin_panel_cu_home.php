<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
					"cu_home_title" => "Kunder / anv�ndare",
					"cu_home_search_text" => "S�k kunder/anv�ndare (t.ex. namn, org.nr., ort, bransch, kundnummer)",

					"cu_home_suppliers" => "Leverant�rer",
					"cu_home_consumers" => "Konsumenter",
					"cu_home_advertisers" => "Annons�rer",
					"cu_home_company" => "F�retag",
					"cu_home_builder_contractor" => "Byggherre/entrepren�r",
					"cu_home_tenant" => "Bostadsr�ttsf�rening",
					"cu_home_non_profit" => "Ideell f�rening",
					"cu_home_municipality_country_authority" => "Kommun/landsting/myndighet",

					"cu_search_display" => "Visar",
					"cu_search_of" => "av",
					"cu_search_matched_customers_users" => "matchade kunder/anv�ndare",

					"cu_header_custno" => "KUNDNR.",
					"cu_header_customer" => "KUND",
					"cu_header_regno" => "ORG.NR.",
					"cu_header_place" => "ORT",
					"cu_header_trade" => "BRANSCH",
					"cu_header_type" => "TYP",
					"cu_header_login" => "SENAST INLOGGAD",
					"cu_header_status" => "STATUS",
					"cu_header_priside_admin" => "Priside Admin",
					
					"cu_home_active" => "Aktiv",
					"cu_home_inactive" => "Inaktiv",
					"cu_home_today" => "Idag",

					"cu_home_industry_construction" => "Bygg",
					"cu_home_industry_cleaning" => "St�d",
					"cu_button_new_customer" => "Ny kund",
					
					"cu_home_account_type" => "Kontotyp",
					"cu_home_business_classification" => "Business Klassificering",
					"cu_home_no_records_found" => "inga poster hittades",
					// php strings and errormessages
					"pcu_home_ErrorMsg" => "param kan inte vara tomt."
					
					);
	$translation_array_en = array(
					"cu_home_title" => "Customers / Users",
					"cu_home_search_text" => "Search customers/users (e.g. name, organization number, location, category, customer number)",

					"cu_home_suppliers" => "Suppliers",
					"cu_home_consumers" => "Consumers",
					"cu_home_advertisers" => "Advertisers",
					"cu_home_company" => "Company",
					"cu_home_builder_contractor" => "Builder / Contractor",
					"cu_home_tenant" => "Tenant",
					"cu_home_non_profit" => "Non-profit organization",
					"cu_home_municipality_country_authority" => "Municipality / county / authority",

					"cu_search_display" => "Displays",
					"cu_search_of" => "of",
					"cu_search_matched_customers_users" => "matched customers / users",

					"cu_header_custno" => "CUSTOMER NO.",
					"cu_header_customer" => "CUSTOMER",
					"cu_header_regno" => "REG.NO.",
					"cu_header_place" => "LOCATION",
					"cu_header_trade" => "CATEGORY",
					"cu_header_type" => "TYPE",
					"cu_header_login" => "LAST LOGIN",
					"cu_header_status" => "STATUS",
					"cu_header_priside_admin" => "Priside Admin",

					"cu_home_active" => "Active",
					"cu_home_inactive" => "Inactive",
					"cu_home_today" => "Today",

					"cu_home_industry_construction" => "Construction",
					"cu_home_industry_cleaning" => "Cleaning",
					"cu_button_new_customer" => "New customer",
					
					"cu_home_account_type" => "Account Type",
					"cu_home_business_classification" => "Business Classification",
					"cu_home_no_records_found" => "No records found",
					// php strings and errormessages
					"pcu_home_ErrorMsg" => "param cannot be blank.",
			);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>
