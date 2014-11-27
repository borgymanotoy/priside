<?php

	$translation_array_sv = array(
		"my_company_prof_my_company_prof" => "Min f�retagsprofil",
		"my_company_prof_desc"=>"Detta �r din f�retagsprofil, dvs den information som tj�nstek�pare ser n�r du besvarar en f�rfr�gan",
		"my_company_prof_more_contacts"=>"Fler kontakter",
		
		"my_company_prof_company_presentation"=>"F�retagspresentation", 
		"my_company_prof_reviews"=>"Omd�men", 
		"my_company_prof_references"=>"Referenser",
		"my_company_prof_certification"=>"Kvalitetsst�mplar", 
		"my_company_prof_telephone"=>"Telefon",
		"my_company_prof_email"=>"E-mail",
		"my_company_prof_status"=>"Status",
		"my_company_prof_help"=>"hj�lp",
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