<?php

$translation_array_sv = array(
					"document_files_document_files"=>"Dokument och filer",
					"document_files_document_files_text1"=>"Här får du en översikt över dokument och filer kopplade till dina projekt.",
					"document_files_document_files_text2"=>"Du kan själv skapa nya mappar och flytta filer för att enklare hitta dem.",
					"document_files_last_uploaded_files"=>"Senast uppladdade dokument och filer",
					"document_files_upload"=>"Ladda upp fl",	
					"document_files_name"=>"Namn",
					"document_files_size"=>"Storlek",
					"document_files_created"=>"Kapad",
					"document_files_added"=>"Uppladdad av",
					"document_files_new_folder"=>"Ny mapp",
					"document_files_new_note"=>"Ny anteckning",
					"document_files_new_copy"=>"Kopiera",
					"document_files_new_move"=>"Flytta",
					"document_files_new_remove"=>"Ta bort",
					"document_files_new_rename"=>"Byt namn",
					"document_files_ButtonStateCancel"=>"Avbryt Ladda upp",
					"document_files_message_ok"=>"Ny filen till projektet!",
					"document_files_download_file"=>"Ladda ner",
					"document_files_file"=>"fil",
					"document_files_folder"=>"mapp",
					"document_files_wordNew"=>"ny",
					"document_files_wordName"=>"namn",
					"document_files_yousure"=>"är du säker på att du vill ta bort",
					"document_files_foldername"=>"Mappnamn",
					"document_files_move"=>"Flytta",
					"document_files_destination"=>"Välj destination",
					"document_files_new_folder_ok"=>"Ny mapp Skapad Ok",
					"document_files_rename_ok"=>"Byt namn Lyckad.",
					"document_files_deleted"=>"Filen bort.",
					"document_files_move_ok"=>"Flytta Framgångsrik.",
					"document_files_permission"=>"Åtkomst nekad.",
					"document_files_notes_filename"=>"Projekt Anteckningar.txt",
					"document_files_help"=>"hjälp",
					"document_files_select_project"=>"Välj projekt",


					"project_overview_notes"=>"Projekt Anteckningar",
					"project_overview_notesave"=>"Spara",
					"project_overview_notecancel"=>"Avbryt",
				);
				
$translation_array_en = array(
					"document_files_document_files"=>"Document and Files",
					"document_files_document_files_text1"=>"Here is an overview of documents and files related to your projects.",
					"document_files_document_files_text2"=>"You can create new folders and move files to more easily find them.",
					"document_files_last_uploaded_files"=>"Last uploaded documents and files",	
					"document_files_upload"=>"Upload fl",	
					"document_files_name"=>"Name",
					"document_files_size"=>"Size",
					"document_files_created"=>"Date",
					"document_files_added"=>"Added By",
					"document_files_new_folder"=>"New Folder",
					"document_files_new_note"=>"New note",
					"document_files_new_copy"=>"Copy",
					"document_files_new_move"=>"Move",
					"document_files_new_remove"=>"Delete",
					"document_files_new_rename"=>"Rename",
					"document_files_ButtonStateCancel"=>"Cancel Upload",
					"document_files_message_ok"=>"New File Added To Project!",
					"document_files_download_file"=>"Download",
					"document_files_file"=>"File",
					"document_files_folder"=>"Folder",
					"document_files_wordNew"=>"New",
					"document_files_wordName"=>"Name",
					"document_files_yousure"=>"Are you sure you want to delete",
					"document_files_foldername"=>"Folder Name",
					"document_files_move"=>"Move",
					"document_files_destination"=>"Select Destination",
					"document_files_new_folder_ok"=>"New Folder Created Successfully.",
					"document_files_rename_ok"=>"Rename Successful.",
					"document_files_deleted"=>"File Deleted.",
					"document_files_move_ok"=>"Move Successful.",
					"document_files_permission"=>"Permission Denied.",
					"document_files_notes_filename"=>"Project Notes.txt",
					"document_files_help"=>"help",
					"document_files_select_project"=>"Select Project",
					
					"project_overview_notes"=>"Project Notes",
					"project_overview_notesave"=>"Save",
					"project_overview_notecancel"=>"Cancel",
					
					
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