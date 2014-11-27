<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/classifications.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/servicerequesttime.php" );

$translation_array_sv = array(
					"new_request_new_req"=>"Ny förfrågan",
					"new_request_new_req_text"=>"Här kan du skapa en ny förfrågan.",
					"new_request_edit_req"=>"Redigera förfrågan",
					"new_request_edit_req_text"=>"Här kan du redigera din förfrågan.",
					"new_request_search_country"=>"Var | ort, kommun",
					"new_request_what_industry"=>"Vad | bransch, tjänst",
					"new_request_select"=>"välj",
					"new_request_mission"=>"Beskriv uppdraget.",
					"new_request_mission_text"=>"Beskriv uppdraget så tydligt som möjligt för fler och bättre svar.",
					"new_request_attach_file"=>"Bifoga fil",
					"new_request_attachments"=>"Bifogade filer",
					"new_request_whom"=>"Åt vem ska uppdraget utföras",
					"new_request_when"=>"När ska uppdraget utföras",
					"new_request_mediate"=>"Bli förmedlad",
					"new_request_invite_entre"=>"Bjud in företagare",
					"new_request_request"=>"Begär prisidé",
					"new_request_help"=>"hjälp",
					"new_request_created"=>"Ny förfrågan skapad! Du kan hitta den under \"Mina förfrågningar\".",
					"new_request_edited"=>"Ändringar sparade!",
               "new_request_category_info"=>"V&auml;lj vad (bransch, tj&auml;nst) du vill ska utf&ouml;ras.",
               "new_request_location_info"=>"V&auml;lj var (ort eller kommun) du vill att tj&auml;nsten ska utf&ouml;ras.",
               "new_request_mission_info"=>"Beskriv vad du vill ska utf&ouml;ras.",
               "new_request_whom_info"=>"Ange &aring;t vem projektet ska utf&ouml;ras.",
               "new_request_when_info"=>"Ange n&auml;r uppdraget ska utf&ouml;ras.",

					// File deletion text (fd = file dialog)
					"new_request_file_delete_hover" => "Ta bort fil",
					"new_request_fd_title" => "Ta bort fil?",
					"new_request_fd_descr" => "Obs! Filen kommer omedelbart att tas bort! Denna operation kan inte ångras! Är du säker på att du vill ta bort: ",
					"new_request_fd_btn_delete" => "Ta bort filen!",
					"new_request_fd_btn_cancel" => "Avbryt",
				);

  	            
				
$translation_array_en = array(
					"new_request_new_req"=>"New Request",
					"new_request_new_req_text"=>"Here you can create a new request.",
					"new_request_edit_req"=>"Edit Request",
					"new_request_edit_req_text"=>"Here you can edit your request.",
					"new_request_search_country"=>"Where | city, county",
					"new_request_what_industry"=>"What | industry, service",
					"new_request_select"=>"select",
					"new_request_mission"=>"Describe the mission",
					"new_request_mission_text"=>"Describe the mission as clearly as possible for more and better answers.",
					"new_request_attach_file"=>"Attach a file",
					"new_request_attachments"=>"Attachments",
					"new_request_whom"=>"For whom to carry out request",
					"new_request_when"=>"When shall the request be carried out",
					"new_request_mediate"=>"Be mediated",
					"new_request_invite_entre"=>"Invite companies",
					"new_request_request"=>"Request prisidé",
					"new_request_help"=>"help",
					"new_request_created"=>"New request created! You can find it under \"My requests\".",
					"new_request_edited"=>"Changes saved!",
               "new_request_category_info"=>"Choose what (business, service) you want to be performed.",
               "new_request_location_info"=>"Choose where (city or municipality) you want the request to be carried out.",
               "new_request_mission_info"=>"Describe what you want done.",
               "new_request_whom_info"=>"Specify for whom the work is to be done.",
               "new_request_when_info"=>"Specify when the work is to be done.",

					// File deletion text (fd = file dialog)
					"new_request_file_delete_hover" => "Delete file",
					"new_request_fd_title" => "Delete file?",
					"new_request_fd_descr" => "Obs! The file will be immediately deleted! This operation cannot be undone! Are you sure you want to delete: ",
					"new_request_fd_btn_delete" => "Delete the file!",
					"new_request_fd_btn_cancel" => "Cancel",
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
