<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common_user_panel.php" );

$translation_array_sv = array(
	"usr_panel_inquries"=>"Förfrågningar",
	"usr_panel_my_proc" =>"Mina projekt",
	"usr_panel_msg_center"=>"Meddelandecenter",
	"usr_panel_my_account"=>"Mitt konto",
	"dropdown_select"=>"välj",

);

$translation_array_en = array(
	"usr_panel_inquries"=>"Inquiries",
	"usr_panel_my_proc" =>"My Projects",
	"usr_panel_msg_center"=>"Message Center",
	"usr_panel_my_account"=>" My Account",
	"dropdown_select"=>"select",
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