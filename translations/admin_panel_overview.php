<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"ov_adminpanel" => "Administrationspanel",
						"ov_overview_small" => "�versikt",

						"dataheader_ov_inquiries" => "F�rfr�gningar",
						"dataheader_ov_custusers" => "Kunder/anv�ndare",
						"dataheader_ov_ads" => "Annonser",
						
						"dataheader_ov_no_of_waiting_approval" => "Antal f�rfr�gningar som v�ntar p� godk�nnande",
						"dataheader_ov_goto_list" => "G� till listan",
						"dataheader_ov_no_of_active_requests" => "Antal aktiva f�rfr�gningar",
						"dataheader_ov_no_of_accepted_tasks_to_date" => "Antal accepterade uppdrag hittills",
						"dataheader_ov_most_service_requested" => "Mest efterfr�gande tj�nster",
						
						"services_bathroom_wetroom" => "Badrum/v�trum",
						"services_final_cleaning" => "Flyttst�dning",
						"services_accounting" => "Redovisning",
						"services_catering" => "Catering",
						"services_construction" => "Bygg",
						
						"cu_no_of_users" => "Antal anv�ndare",
						"cu_no_of_suppliers" => "Leverant�rer",
						"cu_no_of_consumers" => "Konsumenter",
						"cu_no_of_advertisers" => "Annons�rer",
						
						"ads_no_of_active_ads" => "Antal aktiva annonser",
						"ads_no_of_free_ad_site" => "Antal lediga annonsplatser",

						"ov_help_title" => "�versikt",
						"overview_help" => "H�r ser du aktuell status f�r priside.se med statistik �ver kunder, f�rfr�gningar och annonser m.m.",
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