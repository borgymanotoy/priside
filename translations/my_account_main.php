<?php


$languages = is_null($_GET['lang']) ? "sv" : $_GET['lang'];	

$translation_array_sv = array(
					"my_account_inquries"=>"Förfrågningar",
					"my_account_my_proc" =>"Mina projekt",
					"my_account_msg_center"=>"Meddelandecenter",
					"my_account_my_account"=>"Mitt konto",
					"my_account_my_fore"=>"Min företagsprofl",
					
					"my_account_my_account_details"=>"Mina kontouppgifter",
					"my_account_cancel_account"=>"Avsluta mitt konto/säg upp avtal  ",
				);

$translation_array_en = array(
					"my_account_inquries"=>"Inquiries",
					"my_account_my_proc" =>"My Projects",
					"my_account_msg_center"=>"Message Center",
					"my_account_my_account"=>" My Account",
					"my_account_my_fore"=>"My företagsprofl",
					
					"my_account_my_account_details"=>"My account details",
					"my_account_cancel_account"=>"Cancel My Account / looking up contracts",
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