<?php

 
$translation_array_sv = array(
	"common_usr_pnel_my_company_profile"=>"Min företagsprofl",
	"common_usr_pnel_more_contacts"=>"Fler kontakter",
	
);

$translation_array_en = array(

	"common_usr_pnel_my_company_profile"=>"My Company Profile",
	"common_usr_pnel_more_contacts"=>"More Contacts",
);


if( ! is_array( $trans ) )
	$trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}

 return;
 ?>