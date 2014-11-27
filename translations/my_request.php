<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/classifications.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/servicerequesttime.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/inquiry_status.php" );

$translation_array_sv = array(
					"my_request_my_req"=>"Mina f�rfr�gningar",
					"my_request_contractors"=>"Intresserade leverant�rer",
					"my_request_my_req_text"=>"H�r ser du status f�r dina f�rfr�gningar och vilka f�retag som �r intresserade.<br> <br> Du kan �ven bjuda in f�retagare som inte har svarat p� din f�rfr�gan.",
					"my_request_company"=>"f�retag",
					"my_request_place"=>"ort",
					"my_request_jugdement"=>"omd�me",
					"my_request_create_new_proj"=>"Skapa nytt projekt",
					"my_request_invite_entrepreneurs"=>"Bjud in f�retagare", // Header/title
					"my_request_invite_ent_text"=>"Prisid� f�rmedlar kontakt med kvalitets�krade f�retagare. Du kan ocks� bjuda in f�retagare till en prisf�rfr�gan genom att s�ka fram f�retaget i v�rt register alternativt ange dennes e-postadress. Om f�retagaren inte finns registrerad hos oss skickas ett inbjudningsmail till denne.",
					"my_request_invite_error_add_email"=>"Kan inte skicka inbjudan utan e-post-adress.",
 

					
					"my_request_answered_req"=>"Besvarade f�rfr�gningar.",
					"my_request_not_answered_ques"=>"Ej besvarade f�rfr�gningar.",
					"my_request_company_res"=>"Antal f�retag som redan har besvarat f�rfr�gan.",
					"my_request_find_entrepreneurs"=>"S�k f�retagare",
					"my_request_mail"=>"E-post",
					"my_request_invited_entre"=>"Inbjudna f�retagare",
					"my_request_invite_entrepreneur"=>"Bjud in f�retagare",
					"my_request_mission_statement"=>"Uppdragsbeskrivning",
					"my_request_perform_for"=>"Utf&ouml;res &aring;t",
					"my_request_mission_start"=>"Uppdraget ska p&aring;b&ouml;rjas",
					
					"my_request_project"=>"Projekt",
					"my_request_edit"=>"Redigera",
					"my_request_write_down"=>"Anteckna",
					"my_request_remove"=>"Ta bort",
					"my_request_publish"=>"Publicerad",

					"my_request_assign"=>"Tilldela uppdrag",
					"my_request_assigned"=>"Tilldelat!",
					"my_request_msg_center"=>"Meddelandecenter",
					"my_request_see"=>"Visa f�retagsprofil",
					"my_request_add_review"=>"L�gg omd�me	",
					"my_request_rating_count"=>" st",
					"my_request_reputation"=>"Omd�me i skalan",
					"my_request_new_msg"=>"Nytt meddelande finns",
					"my_request_new_msg"=>"Nytt meddelande finns",
					"my_request_new_help"=>"hj�lp",



					
					"my_request_date"=>"datum",
					"my_request_inquiry"=>"f�rfr�gan",
					"my_request_municipality"=>"kommun",
					"my_request_status"=>"status",
					"my_request_performed"=>"Utf�res �t",
					"my_request_when"=>"N�r",
					"my_request_description"=>"Beskrivning",
					"my_request_contact_info"=>"Kontaktuppgifter",
					"my_request_name"=>"Namn",
					"my_request_mail"=>"E-post",
					"my_request_telephone"=>"Telefon",
					
					"my_request_previous"=>"F�reg�ende",
					"my_request_next"=>"N�sta",
					"my_request_show"=>"Visar",
					"my_request_of"=>"av",
					"my_request_matches"=>"matchade f�rfr�gningar",
					"my_request_attached"=>"Bifogade filer",
					"my_request_no_replies"=>"Inga f�retag har svarat p� din f�rfr�gan �n.",

					"my_request_select_proj"=>"V&auml;lj projekt",
					"my_request_connect_proj"=>"Koppla f�rfr�gan till ett projekt",
					"my_request_connect_to_proj"=>"Koppla till projekt", // Shorter as displayd in request overview

					"pmy_request_entrepreneur_blank" => "Please select an entreprenuer.",

					// Note translations
					"my_request_notes_title" => "Anteckningar",
					"my_request_notes_btn_save" => "Spara anteckningar",
					"my_request_notes_btn_cancel" => "Avbryt",

					// Request deletion confirmation dialog
					"my_request_delete_dlg_title" => "Radera f�rfr�gan?",
					"my_request_delete_dlg_text_pre" => "�r du s�ker p� att du vill radera f�rfr�gan '",
					"my_request_delete_dlg_text_post" => "' permanent? Detta g�r inte att �ngra.",
					"my_request_delete_dlg_btn_delete" => "Radera f�rfr�gan",
					"my_request_delete_dlg_btn_cancel" => "Avbryt",
				);

  	            
				
$translation_array_en = array(
					"my_request_my_req"=>"My Requests",
					"my_request_contractors"=>"Contractors",
					"my_request_my_req_text"=>"Here you see the status of your requests and which companies who are interested. <br><br> You can also invite companies that haven\'t yet responded to your request.",
					"my_request_company"=>"company",
					"my_request_place"=>"place",
					"my_request_jugdement"=>"judgement",
					"my_request_create_new_proj"=>"Create new project",
					"my_request_invite_entrepreneurs"=>"Invite entrepreneurs", // Header/title
					"my_request_invite_ent_text"=>"Prisid� mediates contact with quality-assured businesses. You can also invite entrepreneurs to a quote by searching the company in our directory or enter their email address. If this company is not registered with us send we will send an invitation mail.",
					"my_request_invite_error_add_email"=>"Can not send invitation without an e-mail address.",

					"my_request_answered_req"=>"Answered requests.",
					"my_request_not_answered_ques"=>"Not answered questions..",
					"my_request_company_res"=>"Number of companies that already have responded to the request.",
					"my_request_find_entrepreneurs"=>"Find entrepreneur",
					"my_request_mail"=>"Mail",
					"my_request_invited_entre"=>"Invited entrepreneurs",
					"my_request_invite_entrepreneur"=>"Invite entrepreneur",
					"my_request_mission_statement"=>"Description",
					"my_request_perform_for"=>"Carried out for",
					"my_request_mission_start"=>"The mission will begin",
					"my_request_project"=>"Project",
					"my_request_edit"=>"Edit",
					"my_request_write_down"=>"Make note",
					"my_request_remove"=>"Remove",
					"my_request_publish"=>"Published",
					"my_request_assign"=>"Assign request",
					"my_request_assigned"=>"Assigned!",
					"my_request_msg_center"=>"Message Center",
					"my_request_see"=>"View company profile",
					"my_request_add_review"=>"Add review",
					"my_request_rating_count"=>" reviews",
					
					
					"my_request_new_msg"=>"New message received",
					"my_request_reputation"=>"Reputation in a scale of",
					"my_request_new_help"=>"help",

					"my_request_date"=>"date",
					"my_request_inquiry"=>"inquiry",
					"my_request_municipality"=>"municipality",
					"my_request_status"=>"status",
					"my_request_performed"=>"Performed for",
					"my_request_when"=>"When",
					"my_request_description"=>"Description",
					"my_request_contact_info"=>"Contact Information",
					"my_request_name"=>"Name",
					"my_request_mail"=>"Mail",
					"my_request_telephone"=>"telephone",
					"my_request_previous"=>"Previous",
					"my_request_next"=>"Next",
					"my_request_show"=>"Showing",
					"my_request_of"=>"of",
					"my_request_matches"=>"matches requests",
					"my_request_attached"=>"Attached files",
					"my_request_no_replies"=>"No companies have replied to your request yet.",

					"my_request_select_proj"=>"Select project",
					"my_request_connect_proj"=>"Connect request to a project",
					"my_request_connect_to_proj"=>"Connect to project", // Shorter as displayd in request overview

					"pmy_request_entrepreneur_blank" => "V�lj en entreprenuer.",

					// Note translations
					"my_request_notes_title" => "Notes",
					"my_request_notes_btn_save" => "Save notes",
					"my_request_notes_btn_cancel" => "Cancel",

					// Request deletion confirmation dialog
					"my_request_delete_dlg_title" => "Delete the request?",
					"my_request_delete_dlg_text_pre" => "Are you sure you want to delete the request '",
					"my_request_delete_dlg_text_post" => "' permanently? This can not be undone.",
					"my_request_delete_dlg_btn_delete" => "Delete request",
					"my_request_delete_dlg_btn_cancel" => "Cancel",
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
