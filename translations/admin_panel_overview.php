<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"ov_adminpanel" => "Administrationspanel",
						"ov_overview_small" => "översikt",

						"dataheader_ov_inquiries" => "Förfrågningar",
						"dataheader_ov_custusers" => "Kunder/användare",
						"dataheader_ov_ads" => "Annonser",
						
						"dataheader_ov_no_of_waiting_approval" => "Antal förfrågningar som väntar på godkännande",
						"dataheader_ov_goto_list" => "Gå till listan",
						"dataheader_ov_no_of_active_requests" => "Antal aktiva förfrågningar",
						"dataheader_ov_no_of_accepted_tasks_to_date" => "Antal accepterade uppdrag hittills",
						"dataheader_ov_most_service_requested" => "Mest efterfrågande tjänster",
						
						"services_bathroom_wetroom" => "Badrum/våtrum",
						"services_final_cleaning" => "Flyttstädning",
						"services_accounting" => "Redovisning",
						"services_catering" => "Catering",
						"services_construction" => "Bygg",
						
						"cu_no_of_users" => "Antal användare",
						"cu_no_of_suppliers" => "Leverantörer",
						"cu_no_of_consumers" => "Konsumenter",
						"cu_no_of_advertisers" => "Annonsörer",
						
						"ads_no_of_active_ads" => "Antal aktiva annonser",
						"ads_no_of_free_ad_site" => "Antal lediga annonsplatser",

						"ov_help_title" => "Översikt",
						"overview_help" => "Här ser du aktuell status för priside.se med statistik över kunder, förfrågningar och annonser m.m.",
					);
	$translation_array_en = array(
						"ov_adminpanel" => "Administration Panel",
						"ov_overview_small" => "overview",

						"dataheader_ov_inquiries" => "Inquiries",
						"dataheader_ov_custusers" => "Customers / users",
						"dataheader_ov_ads" => "Ads",

						"dataheader_ov_no_of_waiting_approval" => "Number of requests awaiting for approval",
						"dataheader_ov_goto_list" => "Go to the list",
						"dataheader_ov_no_of_active_requests" => "Number of active requests",
						"dataheader_ov_no_of_accepted_tasks_to_date" => "Number of accepted tasks to date",
						"dataheader_ov_most_service_requested" => "Most service requested",

						"services_bathroom_wetroom" => "Bathroom / wetroom",
						"services_final_cleaning" => "Final cleaning",
						"services_accounting" => "Accounting",
						"services_catering" => "Catering",
						"services_construction" => "Construction",

						"cu_no_of_users" => "Number of users",
						"cu_no_of_suppliers" => "Suppliers",
						"cu_no_of_consumers" => "Consumers",
						"cu_no_of_advertisers" => "Advertisers",

						"ads_no_of_active_ads" => "Number of active ads",
						"ads_no_of_free_ad_site" => "Number of free ad spots",
						
						"ov_help_title" => "Overview",
						"overview_help" => "Here you see the current status of priside.se with statistics customers, inquiries and advertisements, etc.",
					);

	if( ! is_array( $trans ) ) $trans = array();

	if( "en" == $lang ){
		$trans = array_merge( $trans, $translation_array_en );
	}
	else {
		$trans = array_merge( $trans, $translation_array_sv );
	}
?>