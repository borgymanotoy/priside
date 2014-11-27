<?php
	$translation_array_sv = array(
						"cu_customers_users" => "Kunder/användare",
						"cu_new_customer" => "Ny kund",
					);

	$translation_array_en = array(
						"cu_customers_users" => "Customers / users",
						"cu_new_customer" => "New customer",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>