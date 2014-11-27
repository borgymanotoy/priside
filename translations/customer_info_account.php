<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
			"cust_nfo_acnt_account" => "Kontoinformation",
			"cust_nfo_acnt_certifications" => "Kvalitetsstämplar",

	);

	$translation_array_en = array(
			"cust_nfo_acnt_account" => "Account",
			"cust_nfo_acnt_certifications" => "Certifications",
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>