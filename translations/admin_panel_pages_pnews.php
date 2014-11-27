<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"pnews_add" => "Läg till pressnyhet",
						"pnews_intro" => "Inledningstext pressidan.",
						"pnews_text_pressmedia" => "Presentationsmaterial Prisidé",
						"pnews_download_brochure" => "Ladda ner vår broschyr om du vill veta mer om Prisidé.",
						"pnews_start_bns" => "Prisidé startar Köp & sälj",
						"pnews_recruit_record" => "Prisidé rekryterar rekordmånga nya medarbetare i år",

						"pnews_brochure" => " broschyr",
						"pnews_btn_brochure" => " Broschyr",

						"pnews_save_success_msg" => "Pressnyheter : Information framgångsrikt sparas.",
						"pnews_update_success_msg" => "Pressnyheter : Information framgångsrikt uppdateras.",

						"pnews_item_save_success_msg" => "Pressnyheter : Item information framgångsrikt sparas.",
						"pnews_item_update_success_msg" => "Pressnyheter : Item information framgångsrikt uppdateras.",
						"pnews_item_remove_success_msg" => "Pressnyheter : Item information framgångsrikt avlägsnades.",

						"pnews_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						"pnews_confirm_delete_title" => "Ta bort markerat objekt Press Nyheter",
						"pnews_confirm_delete" => "Är du säker på att du vill radera objektet pressen nyheterna?",
						"pnews_confirm_changes" => "Spara ändringarna i pressen nyheter innan navigera att trycka nyheter sida.",

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
						"pnews_text_pressmedia" => "Presentation materials Prisidé",
						"pnews_download_brochure" => "Download our brochure to learn more about Prisidé.",
						"pnews_start_bns" => "Prisidé start the Buy & Sell",
						"pnews_recruit_record" => "Prisidé recruit a record number of new employees this year",

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