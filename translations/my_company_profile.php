<?php

	$translation_array_sv = array(
		"my_company_prof_my_company_prof" => "Min företagsprofil",
		"my_company_prof_desc"=>"Detta är din företagsprofil, dvs den information som tjänsteköpare ser när du besvarar en förfrågan",
		"my_company_prof_more_contacts"=>"Fler kontakter",
		
		"my_company_prof_company_presentation"=>"Företagspresentation", 
		"my_company_prof_reviews"=>"Omdömen", 
		"my_company_prof_references"=>"Referenser",
		"my_company_prof_certification"=>"Kvalitetsstämplar", 
		"my_company_prof_telephone"=>"Telefon",
		"my_company_prof_email"=>"E-mail",
		"my_company_prof_status"=>"Status",
		"my_company_prof_help"=>"hjälp",
	);

	$translation_array_en = array(
		"my_company_prof_my_company_prof" => "My Company Profile",
		"my_company_prof_desc" => "This is your corporate identity, ie the information services for buyers see when answering a query",
		"my_company_prof_more_contacts"=>"More Contacts",
		"my_company_prof_company_presentation"=>"Company Presentation", 
		"my_company_prof_reviews"=>"Reviews", 
		"my_company_prof_references"=>"References",
		"my_company_prof_certification"=>"Certification", 
		
		"my_company_prof_telephone"=>"Telephone",
		"my_company_prof_email"=>"Mail",
		"my_company_prof_status"=>"Status",
		"my_company_prof_help"=>"help",



);


	if( ! is_array( $trans ) ) $trans = array();

	if( "en" == $lang ){
		$trans = array_merge( $trans, $translation_array_en );
	}
	else {
		$trans = array_merge( $trans, $translation_array_sv );
	}
?>