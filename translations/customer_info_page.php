<?php
	$translation_array_sv = array(
		"cu_nfo_gen" => "Allmänt",
		"cu_nfo_cu_nfo" => "Kunduppgifter",
		"cu_nfo_accnt" => "Konto",
		"cu_nfo_acts" => "Aktiviteter",
		"cu_nfo_ads" => "Annonsering",
	);

	$translation_array_en = array(
		"cu_nfo_gen" => "General",
		"cu_nfo_cu_nfo" => "Customer Information",
		"cu_nfo_accnt" => "Account",
		"cu_nfo_acts" => "Activities",
		"cu_nfo_ads" => "Advertising",
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>