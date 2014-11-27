<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
$translation_array_sv = array(
	"message_center_title"=>"Meddelandecenter",
	"message_center_desciption"=>"I meddelandecentret kan du kommuniceringa säkert med dina kontakter på Prisidé. Här har du alla dina kontakter, meddelanden och filer samlade på ett och samma ställe. Du slipper strul med försvunna e-postadresser och mail.",
	"message_center_contact_info"=>"Kontaktuppgifter",
	"message_center_view_company_prof"=>"Visa företagsprofil",
	"message_center_contact_history"=>" Kontakthistorik",
	"message_center_more_contacts"=>"Fler kontakter",
	"message_center_my_files"=>"Filer",
	"message_center_my_desc"=>"Här samlas alla filer du skickat och tagit emot i samband dessa konversationer. Du kan också ladda upp nya filer här direkt.",
	"message_center_invite_contacts"=>"Bjud in kontakt",
	"message_center_remove_contacts"=>"Ta bort kontakt",
	"message_center_save_desc"=>"Spara alla konversationer, kontaktuppgifter och filer tillhörande denna kontakt.",
	"message_center_table_header"=>"Kontorsflytt, Landskrona",
	"message_center_new_conversation"=>"Ny konversation Alla",
	"message_center_tags"=>"Taggar",
	"message_center_office_relocation"=>"Kontorsflytt",
	"message_center_office_mission_statement"=>"Uppdragsbeskrivning",
	"message_center_select_recipient"=>"Välj mottagare",
	"message_center_send_attach_file"=>"Bifoga fil",
	"message_center_select_tag_msg"=>"Tagga meddelandet",
	"message_center_search_in_text"=>"Sök i konversationer (t.ex. text, kontaktnamn, tagg, datum)",
	"message_center_conversation_text"=>"Hej! Din offert verkar bra så vi kör på det.",
	"message_center_today_at"=>"Idag kl",
	"message_center_reciever"=>"Mottagare",
	"message_center_read_more"=>"Läs mer",
	"message_center_kon"=>"Kontorsflytt",
	"message_center_comment"=>"Kommentera",
	"message_center_attach_file"=>"Bifoga fil",
	"message_center_select_contact"=>"Välj kontakt",
	"message_center_select_request"=>"Välj förfrågan",
	"message_center_select_project"=>"Välj projekt",
	"message_center_attachments"=>"Bifogade filer",
	"message_center_project_description" => "Projektbeskrivning:",
	"message_center_project_purpose" => "Syfte:",
	"message_center_inquiry_mission_statement" => "Uppdragsbeskrivning:",
	"message_center_inquiry_performed_at" => "Utföres åt:",
	"message_center_inquiry_attached_files" => "Bifogade filer:",
	"message_center_inquiry_related_project" => "Projekt:",
	"message_center_inquiry_make_a_note" => "Anteckna",
	"message_center_project_view_project" => "Visa projektsidan",
	"project_overview_notes" => "Anteckna",
	"project_overview_notesave" => "Ok",
	"project_overview_notecancel" => "Avbryt",
	"message_center_help"=>"hjälp",
	"message_center_specify_message" => "Ange meddelande",
	"message_center_specify_recipient" => "Välj minst 1 mottagare"
);

$translation_array_en = array(
	"message_center_title"=>"Message Center",
	"message_center_desciption"=>"The message center allows you to communicated to call safely with your Prisidé contacts. Here you have all your contacts, messages and files gathered in a place. No more hassles of missing e-mail addresses and e-mail.",
	"message_center_contact_info"=>"Contact Information",
	"message_center_view_company_prof"=>"View company profile",
	"message_center_contact_history"=>"Contact History",
	"message_center_more_contacts"=>"More contacts",
	"message_center_my_files"=>"Files",
	"message_center_my_desc"=>"Here are all the files you've sent and received in these conversations are collected . You can also upload new files here.",
	"message_center_invite_contacts"=>"Invite contacts",
	"message_center_remove_contacts"=>"Remove contact",
	"message_center_save_desc"=>"Save all conversations, contact information and files related to this contact.",
	"message_center_table_header"=>"Office Relocation, Landskrona",
	"message_center_new_conversation"=>"New conversation",
	"message_center_office_relocation"=>"Office Relocation",
	"message_center_tags"=>"Tags",
	"message_center_office_mission_statement"=>"Mission Statement",
	"message_center_select_recipient"=>"Select the recipient",
	"message_center_send_attach_file"=>"Attach file",
	"message_center_select_tag_msg"=>"Tag the message",
	"message_center_search_in_text"=>"Search in conversations (eg, text, contact name, tag, date)",
	"message_center_conversation_text"=>"Hello! Your quote seems fine!",
	"message_center_today_at"=>"Today at",
	"message_center_reciever"=>"Recipient",
	"message_center_read_more"=>"Read more",
	"message_center_kon"=>"Office relocation.",
	"message_center_comment"=>"Comment",
	"message_center_attach_file"=>"Attach a file",
	"message_center_select_contact"=>"Select contact",
	"message_center_select_request"=>"Select request",
	"message_center_select_project"=>"Choose a project",
	"message_center_attachments"=>"Attachment",
	"message_center_project_description" => "Project Description:",
	"message_center_project_purpose" => "Purpose:",
	"message_center_inquiry_mission_statement" => "Mission Statement:",
	"message_center_inquiry_performed_at" => "Performed at:",
	"message_center_inquiry_attached_files" => "Attachments:",
	"message_center_inquiry_related_project" => "Project:",
	"message_center_inquiry_make_a_note" => "Make a note",
	"message_center_project_view_project" => "View project page",
	"project_overview_notes" => "Make a note",
	"project_overview_notesave" => "Ok",
	"project_overview_notecancel" => "Cancel",
	"message_center_help"=>"help",
	"message_center_specify_message" => "Please specify message",
	"message_center_specify_recipient" => "Please select atleast 1 recipient"
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
