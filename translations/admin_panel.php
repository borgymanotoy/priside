<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"tab_overview" => "�versikt",
						"tab_pages" => "Sidor",
						"tab_cust_users" => "Kunder/Anv�ndare",
						"tab_inquiries" => "F�rfr�gningar",
						"tab_certifications" => "Kvalitetsst�mplar",
						"tab_mail" => "Mail",
						"tab_ads" => "Annonser",
					);
	$translation_array_en = array(
						"tab_overview" => "Overview",
						"tab_pages" => "Pages",
						"tab_cust_users" => "Customers / Users",
						"tab_inquiries" => "Inquiries",
						"tab_certifications" => "Certifications",
						"tab_mail" => "Mail",
						"tab_ads" => "Ads",

						);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>
