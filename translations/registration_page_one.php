<?php
$translation_array_sv = array(
					
					"reg_pge_one_office_relocation" => "Kontorsflytt?",
					"reg_pge_one_need_help" => "Vad behöver du hjälp med?",
					"reg_pge_one_office_text" => "Låt proffs från våra kvalitetssäkrade företag ta hand om jobbet!",
					"reg_pge_one_what" => "Vad",
					"reg_pge_one_industry" => "bransch",
					"reg_pge_one_service" => "tjänst",
					"reg_pge_one_select" => "välj",
					"reg_pge_one_city" => "ort",
					"reg_pge_one_county" => "kommun",
					"reg_pge_one_zip_code" => "postnummer",
					"reg_pge_one_arrow_down" => "?",
					"reg_pge_one_proceed" => "Gå vidare",
					"hiw_reg_pge_one_error_Industry"=> "Du måste ange bransch",
					"hiw_reg_pge_one_error_Location"=> "Du måste ange plats (ort/kommun)",
					"hiw_reg_pge_one_error_required_fields"=> "Ange obligatoriska fält",
				);
$translation_array_en = array(
					"reg_pge_one_office_relocation" => "Office Relocation?",
					"reg_pge_one_need_help" => "What do you need help with?",
					"reg_pge_one_office_text" => "Let the pros from our quality assured businesses take care of the job!",
					"reg_pge_one_what" => "What",
					"reg_pge_one_industry" => "industry",
					"reg_pge_one_service" => "service",
					"reg_pge_one_select" => "select",
					"reg_pge_one_city" => "city",
					"reg_pge_one_county" => "county",
					"reg_pge_one_zip_code" => "zip code",
					"reg_pge_one_arrow_down" => "?",
					"reg_pge_one_proceed" => "Proceed",
					"hiw_reg_pge_one_error_Industry"=> "Industry field is required",
					"hiw_reg_pge_one_error_Location"=> "Location field is required",
					"hiw_reg_pge_one_error_required_fields"=> "Enter required fields",
				);


				
				
if( ! is_array( $trans ) )
	$trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}

?>
