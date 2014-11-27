<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/classifications.php" );

$translation_array_sv = array(
					"trash_trash"=>"Papperskorg",
					"trash_search_country"=>"Var | ort, kommun",
					"trash_what_industry"=>"Vad | bransch, tjänst",
					"trash_select"=>"välj",


					"trash_trash_text"=>"Här ser du de förfrågningar du placerat i papperskorgen. Om du besvarar en förfrågan här placeras den automatiskt under Besvarade..",
					"trash_answered_req"=>"Besvarade förfrågningar.",
					"trash_not_answered_ques"=>"Ej besvarade förfrågningar.",
					"trash_company_res"=>"Antal företag som redan har besvarat förfrågan.",
					
					"trash_date"=>"datum",
					"trash_inquiry"=>"förfrågan",
					"trash_municipality"=>"kommun",
					"trash_status"=>"status",
					"trash_performed"=>"Utföres åt",
					"trash_when"=>"När",
					"trash_description"=>"Beskrivning",
					"trash_contact_info"=>"Kontaktuppgifter",
					"trash_name"=>"Namn",
					"trash_mail"=>"E-post",
					"trash_telephone"=>"Telefon",
					"trash_invite_entrepreneur"=>"Bjud in företagare",
					"trash_previous"=>"Föregående",
					"trash_next"=>"Nästa",
					"trash_show"=>"Visar",
					"trash_of"=>"av",
					"trash_matches"=>"matchade förfrågningar",
					"trash_attached"=>"Bifogade filer",
					"trash_help"=>"hjälp",

					// Following translations are also defined by translation/inquiries.php
					// (but with other key values of course) and should be merged so we
					// have only one set of these!
					// Note that many of the above translations are also defined in
					// that file.
					"trash_have_replied"=>"Du har besvarat denna förfrågan.",
					"trash_list_showing"=>"Visar ",
					"trash_list_of"=>" av ",
					"trash_list_matches"=>" matchande förfrågningar.",
					"trash_search"=>"Sök",
					"trash_contact_after_reply"=>"Köparens kontaktuppgifter visas här när du har besvarat förfrågan.",
					"trash_reply"=>"Besvara",
					"trash_have_replied"=>"Du har besvarat denna förfrågan.",
					"trash_have_not_replied"=>"Frågeställaren väntar på ditt svar.",
					"trash_no_matches"=>"Inga förfrågningar matchade din sökning."
				);

  	            
				
$translation_array_en = array(
					"trash_trash"=>"Trash",
					"trash_search_country"=>"Where | city, county",
					"trash_what_industry"=>"Vad | bransch, tjänst",
					"trash_select"=>"select",

					"trash_trash_text"=>"Here are the questions you placed in the trash. If you respond to a request to load the automatically during Answered.",
					"trash_answered_req"=>"Answered requests.",
					"trash_not_answered_ques"=>"Not answered questions..",
					"trash_company_res"=>"Number of companies that already have responded to the request.",

					"trash_date"=>"date",
					"trash_inquiry"=>"inquiry",
					"trash_municipality"=>"municipality",
					"trash_status"=>"status",
					"trash_performed"=>"Performed for",
					"trash_when"=>"When",
					"trash_description"=>"Description",
					"trash_contact_info"=>"Contact Information",
					"trash_name"=>"Name",
					"trash_mail"=>"Mail",
					"trash_telephone"=>"telephone",
					"trash_invite_entrepreneur"=>"Invite entrepreneurs",
					"trash_previous"=>"Previous",
					"trash_next"=>"Next",
					"trash_show"=>"Showing",
					"trash_of"=>"of",
					"trash_matches"=>"matches requests",
					"trash_attached"=>"Attached files",
					"trash_help"=>"help",
					// Following translations are also defined by translation/inquiries.php
					// (but with other key values of course) and should be merged so we
					// have only one set of these!
					// Note that many of the above translations are also defined in
					// that file.
					"trash_have_replied"=>"You have answered to this request.",
					"trash_list_showing"=>"Showing ",
					"trash_list_of"=>" of ",
					"trash_list_matches"=>" matching inquiries.",
					"trash_search"=>"Search",
					"trash_contact_after_reply"=>"The buyer's contact information is shown here when you have answered the request.",
					"trash_reply"=>"Reply",
					"trash_have_replied"=>"You have answered to this request.",
					"trash_have_not_replied"=>"The inquirer is awaiting your reply.",
					"trash_no_matches"=>"No inquiries matched your search."
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
