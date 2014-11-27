<?php
	$translation_array_sv = array(
						"pages_title" => "Rubrik",
						"pages_description" => "Beskrivning",
						"pages_sub_home" => "Startsida",  
						"pages_sub_connect_your_business" => "Anslut ditt f�retag",
						"pages_sub_how_it_works" => "S� fungerar Prisid�",
						"pages_sub_custservice" => "Kundtj�nst",
						"pages_sub_FAQs" => "Vanliga fr�gor",
						"pages_sub_careers" => "Jobba hos oss",
						"pages_sub_media_news" => "Pressnyheter",

						"consumer_small" => "regelbunden",
						"supplier_small" => "premium",

						"box_tv_radio" => "Topbild",
						"box_figure" => "Bild",

						"pages_button_add" => "L�gg till",
						"pages_button_save" => "Spara",
						"pages_button_remove" => "Ta bort",
						"pages_button_cancel" => "avbryta",
						"pages_button_choose_picture" => "V�lj bild",

						"pages_lang_previous" => "F�reg�ende",
						"pages_lang_next" => "N�sta",

						"pages_help_html_tags" => "HTML-taggar",
						"pages_help_msg1" => "Genom att omge en text med en HTML-tagg �ndrar du formateringen p� just den texten. H�r ser du n�gra exempel:",
						"pages_help_bold" => "Fetstil",
						"pages_help_sample_bold" => "fet text",
						"pages_help_italic" => "Kursiv",
						"pages_help_sample_italic" => "kursiv text",
						"pages_help_bullets" => "Punktlista",
						"pages_help_bullets_msg_start" => "anger att listan b�rjar h�r",
						"pages_help_bullets_msg_end" => "anger att listan slutar h�r",
						"pages_help_bullets_item" => "Punkt",
						"pages_help_linking_a_text" => "L�nka en text",
						"pages_help_text_to_link" => "Text som ska l�nkas",
						
						"page_help" => "HJ�LP",
					);

	$translation_array_en = array(
						"pages_title" => "Title",
						"pages_description" => "Description",
						"pages_sub_home" => "Home", 
						"pages_sub_connect_your_business" => "Connect your business",
						"pages_sub_how_it_works" => "How it works Prisid�",
						"pages_sub_custservice" => "Customer service",
						"pages_sub_FAQs" => "FAQs",
						"pages_sub_careers" => "Careers",
						"pages_sub_media_news" => "Media news",

						"consumer_small" => "regular",
						"supplier_small" => "premium",

						"box_tv_radio" => "Top Image",
						"box_figure" => "Figure",

						"pages_button_add" => "Add",
						"pages_button_save" => "Save",
						"pages_button_remove" => "Remove",
						"pages_button_cancel" => "Cancel",
						"pages_button_choose_picture" => "Choose a picture",

						"pages_lang_previous" => "Previous",
						"pages_lang_next" => "Next",

						"pages_help_html_tags" => "HTML tags",
						"pages_help_msg1" => "By surrounding a text with an HTML tag, change the formatting on the right the text. Here are some examples:",
						"pages_help_bold" => "Bold",
						"pages_help_sample_bold" => "bold text",
						"pages_help_italic" => "Italic",
						"pages_help_sample_italic" => "italic",
						"pages_help_bullets" => "Bullets",
						"pages_help_bullets_msg_start" => "indicates that the list starts here",
						"pages_help_bullets_msg_end" => "indicates that the list ends here",
						"pages_help_bullets_item" => "Item",
						"pages_help_linking_a_text" => "Linking a text",
						"pages_help_text_to_link" => "Text to link",

						"page_help" => "HELP",
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