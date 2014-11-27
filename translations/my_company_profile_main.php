<?php

	$translation_array_sv = array(
		"my_company_prof_main_my_company_prof" => "Min företagsprofil",
		"my_company_prof_main_modify_profile" => "Ändra företagsprofil",
		"my_company_prof_main_my_review" => "Mina omdömen",
		"my_company_prof_main_ref" => "Referenser",
		"my_company_prof_main_quality_stamps" => "Kvalitetsstämplar",
	);

	$translation_array_en = array(
		"my_company_prof_main_my_company_prof" => "My Company Profile",
		"my_company_prof_main_modify_profile" => "Modify company profile",
		"my_company_prof_main_my_review" => "My Reviews",
		"my_company_prof_main_ref" => "References",
		"my_company_prof_main_quality_stamps" => "Certification",
);


	if( ! is_array( $trans ) ) $trans = array();

	if( "en" == $lang ){
		$trans = array_merge( $trans, $translation_array_en );
	}
	else {
		$trans = array_merge( $trans, $translation_array_sv );
	}
?>