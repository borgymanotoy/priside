<?php


$translation_array_sv = array(
	"inquires_main_inquries"=>"F�rfr�gningar",
	"inquires_main_answered" =>"Besvarade",
	"inquires_main_trash"=>"Papperskorg",
	"inquires_main_mina"=>"Mina f�rfr�gningar",
	"inquires_main_ny_fore"=>"Ny f�rfr�gan",

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

