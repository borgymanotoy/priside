<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
					"inq_title" => "Förfrågningar",
					"inq_filter_search" => "Filtrera sökning",

					"inq_hdr_nr" => "NR.",
					"inq_hdr_cust" => "KUND",
					"inq_hdr_plc" => "ORT",
					"inq_hdr_srv" => "TJÄNST",
					"inq_hdr_vrfd" => "VERIFIERAD",
					"inq_hdr_aprd" => "GODKÄND",
					"inq_hdr_date" => "DATUM",
					"inq_hdr_stat" => "STATUS",
					"inq_hdr_certifications" => "BESTÄLLD KVALITETSSTÄMPEL",

					"inq_rej_req" => "Ej godkända förfrågningar",
					"inq_not_vrfd_email" => "Ej veriferade mailadresser",
					"inq_cncld_req" => "Avbrutna förfrågningar",
					
					"inq_fltr_services" => "Tjänster",
					"inq_fltr_countries_municipalities" => "Län/kommuner",
					"inq_filter" => "Filtrera",

					"inq_tab_services" => "Tjänster",
					"inq_tab_certs" => "Kvalitetsstämplar",

					"inq_industry_construction" => "Bygg",
					
					"inq_cert_sample" => "Certifierad elektriker",
					
					"inq_rejected_request"=> "Förfrågningar som avslagits",
					"inq_unverified" => "Okontrollerade e-postadresser",
					"inq_cancelled" => "Inställda begäranden",
					
					"inq_company" => "Företag",
					"inq_builder_contractor" => "Byggherre/entreprenör",
					"inq_tenant" => "Bostadsrättsförening",
					"inq_non_profit" => "Ideell förening",
					"inq_municipality_country_authority" => "Kommun/landsting/myndighet"
	);


	$translation_array_en = array(
					"inq_title" => "Inquiries",
					"inq_filter_search" => "Filter search",

					"inq_hdr_nr" => "NR.",
					"inq_hdr_cust" => "CUSTOMER",
					"inq_hdr_plc" => "PLACE",
					"inq_hdr_srv" => "SERVICE",
					"inq_hdr_vrfd" => "VERIFIED",
					"inq_hdr_aprd" => "APPROVED",
					"inq_hdr_date" => "DATE",
					"inq_hdr_stat" => "STATUS",

					"inq_hdr_certifications" => "ORDERED CERTIFICATIONS",

					"inq_rej_req" => "Rejected requests",
					"inq_not_vrfd_email" => "Not veriferade email addresses",
					"inq_cncld_req" => "Cancelled requests",

					"inq_fltr_services" => "Services",
					"Counties / municipalities" => "Counties / municipalities",
					"inq_filter" => "Filter",

					"inq_tab_services" => "Services",
					"inq_tab_certs" => "Certifications",

					"inq_industry_construction" => "Construction",
					"inq_cert_sample" => "Approved certificates.",
					
					"inq_rejected_request"=> "Rejected requests",
					"inq_unverified" => "Unverified email addresses",
					"inq_cancelled" => "Cancelled requests",
					
					"inq_company" => "Company",
					"inq_builder_contractor" => "Builder / Contractor",
					"inq_tenant" => "Tenant",
					"inq_non_profit" => "Non-profit organization",
					"inq_municipality_country_authority" => "Municipality / county / authority"
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>
