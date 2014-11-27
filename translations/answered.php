<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/classifications.php" );

$translation_array_sv = array(
					"answered_help"=>"hjälp",
					"answered_answered"=>"Besvarade",
					"answered_search_country"=>"Var | ort, kommun",
					"answered_what_industry"=>"Vad | bransch, tjänst",
					"answered_select"=>"välj",
				
					"answered_search"=>"Sök",

					"answered_answered_text"=>"Här ser du de förfrågningar du redan besvarat.",
					"answered_answered_req"=>"Besvarade förfrågningar.",
					"answered_not_answered_ques"=>"Ej besvarade förfrågningar.",
					"answered_company_res"=>"Antal företag som redan har besvarat förfrågan.",
					
					"answered_date"=>"datum",
					"answered_inquiry"=>"förfrågan",
					"answered_municipality"=>"kommun",
					"answered_status"=>"status",
					"answered_performed"=>"Utföres åt",
					"answered_when"=>"När",
					"answered_description"=>"Beskrivning",
					"answered_contact_info"=>"Kontaktuppgifter",
					"answered_name"=>"Namn",
					"answered_mail"=>"E-post",
					"answered_telephone"=>"Telefon",
					"answered_invite_entrepreneur"=>"Bjud in företagare",
					"answered_previous"=>"Föregående",
					"answered_next"=>"Nästa",
					"answered_show"=>"Visar",
					"answered_of"=>"av",
					"answered_matches"=>"matchade förfrågningar",
					"answered_attached"=>"Bifogade filer",

					// Following translations are also defined by translation/inquiries.php
					// (but with other key values of course) and should be merged so we
					// have only one set of these!
					// Note that many of the above translations are also defined in
					// that file.
					"answered_have_replied"=>"Du har besvarat denna förfrågan.",
					"answered_list_showing"=>"Visar ",
					"answered_list_of"=>" av ",
					"answered_list_matches"=>" matchande förfrågningar.",
					"answered_no_matches"=>"Inga förfrågningar matchade din sökning."
				);

  	            
				
$translation_array_en = array(
					"answered_help"=>"help",
					"answered_answered"=>"Answered",
					"answered_search_country"=>"Where | city, county",
					"answered_what_industry"=>"What | industry, service",
					"answered_select"=>"select",

					"answered_search"=>"Search",

					"answered_answered_text"=>"Here are the questions you already answered.",
					"answered_answered_req"=>"Answered requests.",
					"answered_not_answered_ques"=>"Not answered questions..",
					"answered_company_res"=>"Number of companies that already have responded to the request.",

					"answered_date"=>"date",
					"answered_inquiry"=>"inquiry",
					"answered_municipality"=>"municipality",
					"answered_status"=>"status",
					"answered_performed"=>"Performed for",
					"answered_when"=>"When",
					"answered_description"=>"Description",
					"answered_contact_info"=>"Contact Information",
					"answered_name"=>"Name",
					"answered_mail"=>"Mail",
					"answered_telephone"=>"telephone",
					"answered_invite_entrepreneur"=>"Invite entrepreneurs",
					"answered_previous"=>"Previous",
					"answered_next"=>"Next",
					"answered_show"=>"Showing",
					"answered_of"=>"of",
					"answered_matches"=>"matches requests",
					"answered_attached"=>"Attached files",

					// Following translations are also defined by translation/inquiries.php
					// (but with other key values of course) and should be merged so we
					// have only one set of these!
					// Note that many of the above translations are also defined in
					// that file.
					"answered_have_replied"=>"You have answered to this request.",
					"answered_list_showing"=>"Showing ",
					"answered_list_of"=>" of ",
					"answered_list_matches"=>" matching inquiries.",
					"answered_no_matches"=>"No inquiries matched your search."
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
