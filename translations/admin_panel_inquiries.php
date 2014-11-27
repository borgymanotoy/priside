<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
					"inq_title" => "F�rfr�gningar",
					"inq_filter_search" => "Filtrera s�kning",

					"inq_hdr_nr" => "NR.",
					"inq_hdr_cust" => "KUND",
					"inq_hdr_plc" => "ORT",
					"inq_hdr_srv" => "TJ�NST",
					"inq_hdr_vrfd" => "VERIFIERAD",
					"inq_hdr_aprd" => "GODK�ND",
					"inq_hdr_date" => "DATUM",
					"inq_hdr_stat" => "STATUS",
					"inq_hdr_certifications" => "BEST�LLD KVALITETSST�MPEL",

					"inq_rej_req" => "Ej godk�nda f�rfr�gningar",
					"inq_not_vrfd_email" => "Ej veriferade mailadresser",
					"inq_cncld_req" => "Avbrutna f�rfr�gningar",
					
					"inq_fltr_services" => "Tj�nster",
					"inq_fltr_countries_municipalities" => "L�n/kommuner",
					"inq_filter" => "Filtrera",

					"inq_tab_services" => "Tj�nster",
					"inq_tab_certs" => "Kvalitetsst�mplar",

					"inq_industry_construction" => "Bygg",
					
					"inq_cert_sample" => "Certifierad elektriker",
					
					"inq_rejected_request"=> "F�rfr�gningar som avslagits",
					"inq_unverified" => "Okontrollerade e-postadresser",
					"inq_cancelled" => "Inst�llda beg�randen",
					
					"inq_company" => "F�retag",
					"inq_builder_contractor" => "Byggherre/entrepren�r",
					"inq_tenant" => "Bostadsr�ttsf�rening",
					"inq_non_profit" => "Ideell f�rening",
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
