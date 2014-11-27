<?php


$translation_array_sv = array(
					"reg_pge_three_title"=>"Bekr�ftelse",
					"reg_pge_three_acknowledgement_text"=>"V�nligen bekr�fta din e-postadress (namn.efternamn@domain.com) genom att klicka p� 
bekr�ftelsel�nken i det mail vi skickat till dig.",
					"reg_pge_three_thanks_message" => "Tack f�r att du anv�nder Prisid�!",
				);

$translation_array_en = array(
					"reg_pge_three_title"=>"Acknowledgement",
					"reg_pge_three_acknowledgement_text"=>"Please verify your email address (firstname.lastname @ domain.com) by clicking on
confirmation link in the email we sent you.",
					"reg_pge_three_thanks_message" => "Thank you for using Prisid�!",
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