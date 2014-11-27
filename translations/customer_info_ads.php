<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
			"cust_nfo_ads_advertisement" => "Annonser",
			"cust_nfo_ads_book_ad" => "Boka annons",
			"cust_nfo_ads_btn_file_upld" => "Välj fl",
	);

	$translation_array_en = array(
			"cust_nfo_ads_advertisement" => "Ads",
			"cust_nfo_ads_book_ad" => "Book your ad",
			"cust_nfo_ads_btn_file_upld" => "Upload file",
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>