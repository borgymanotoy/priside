<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"pnews_add" => "L�g till pressnyhet",
						"pnews_intro" => "Inledningstext pressidan.",
						"pnews_text_pressmedia" => "Presentationsmaterial Prisid�",
						"pnews_download_brochure" => "Ladda ner v�r broschyr om du vill veta mer om Prisid�.",
						"pnews_start_bns" => "Prisid� startar K�p & s�lj",
						"pnews_recruit_record" => "Prisid� rekryterar rekordm�nga nya medarbetare i �r",

						"pnews_brochure" => " broschyr",
						"pnews_btn_brochure" => " Broschyr",

						"pnews_save_success_msg" => "Pressnyheter : Information framg�ngsrikt sparas.",
						"pnews_update_success_msg" => "Pressnyheter : Information framg�ngsrikt uppdateras.",

						"pnews_item_save_success_msg" => "Pressnyheter : Item information framg�ngsrikt sparas.",
						"pnews_item_update_success_msg" => "Pressnyheter : Item information framg�ngsrikt uppdateras.",
						"pnews_item_remove_success_msg" => "Pressnyheter : Item information framg�ngsrikt avl�gsnades.",

						"pnews_error_entry_validation" => "V�nligen fyll upp posterna p� r�tt s�tt.",

						"pnews_confirm_delete_title" => "Ta bort markerat objekt Press Nyheter",
						"pnews_confirm_delete" => "�r du s�ker p� att du vill radera objektet pressen nyheterna?",
						"pnews_confirm_changes" => "Spara �ndringarna i pressen nyheter innan navigera att trycka nyheter sida.",

						// php errors / etc.
						"p_news_ErrMsg_BannerImageNotSet" => "Please select a banner.",
						"p_news_ErrMsg_BrochureNotSet" => "Please select a brochure.",
						"p_news_ErrMsg_PressIntroduction" => "Press Introduction cannot be blank.",
						"p_news_ErrMsg_PressSubHeading" => "Press Sub Heading cannot be blank.",
						"p_news_ErrMsg_PressSubHeadingContent" => "Press Sub Heading Content cannot be blank.",

						"p_news_ErrMsg_PressNewsItemId" => "Press News Item Id cannot be blank.",
						"p_news_ErrMsg_PressNewsItemTitle" => "Press News Item Title cannot be blank.",
						"p_news_ErrMsg_PressNewsItemDescription" => "Press News Item Description cannot be blank.",
					);

	$translation_array_en = array(
						"pnews_add" => "Add to the press news",
						"pnews_intro" => "Introduction Text press site.",
						"pnews_text_pressmedia" => "Presentation materials Prisid�",
						"pnews_download_brochure" => "Download our brochure to learn more about Prisid�.",
						"pnews_start_bns" => "Prisid� start the Buy & Sell",
						"pnews_recruit_record" => "Prisid� recruit a record number of new employees this year",

						"pnews_brochure" => " brochure",
						"pnews_btn_brochure" => " Brochure",

						"pnews_save_success_msg" => "Media News : Information successfully saved.",
						"pnews_update_success_msg" => "Media News : Information successfully updated.",

						"pnews_item_save_success_msg" => "Media News : Item information successfully saved.",
						"pnews_item_update_success_msg" => "Media News : Item information successfully updated.",
						"pnews_item_remove_success_msg" => "Media News : Item information successfully removed.",

						"pnews_error_entry_validation" => "Please fill-up the entries properly.",

						"pnews_confirm_delete_title" => "Remove selected press news item",
						"pnews_confirm_delete" => "Are you sure you want to delete the press news item?",
						"pnews_confirm_changes" => "Please save the changes in press news before navigating to press news items page.",

						// php errors / etc.
						"p_news_ErrMsg_BannerImageNotSet" => "Please select a banner.",
						"p_news_ErrMsg_BrochureNotSet" => "Please select a brochure.",
						"p_news_ErrMsg_PressIntroduction" => "Press Introduction cannot be blank.",
						"p_news_ErrMsg_PressSubHeading" => "Press Sub Heading cannot be blank.",
						"p_news_ErrMsg_PressSubHeadingContent" => "Press Sub Heading Content cannot be blank.",

						"p_news_ErrMsg_PressNewsItemId" => "Press News Item Id cannot be blank.",
						"p_news_ErrMsg_PressNewsItemTitle" => "Press News Item Title cannot be blank.",
						"p_news_ErrMsg_PressNewsItemDescription" => "Press News Item Description cannot be blank.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>