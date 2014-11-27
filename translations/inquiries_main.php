<?php


$translation_array_sv = array(
	"inquires_main_inquries"=>"Förfrågningar",
	"inquires_main_answered" =>"Besvarade",
	"inquires_main_trash"=>"Papperskorg",
	"inquires_main_mina"=>"Mina förfrågningar",
	"inquires_main_ny_fore"=>"Ny förfrågan",

);

$translation_array_en = array(
	"inquires_main_inquries"=>"Inquiries",
	"inquires_main_answered" =>"Answered",
	"inquires_main_trash"=>"Trash",
	"inquires_main_mina"=>"My Requests",
	"inquires_main_ny_fore"=>"New request",
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

