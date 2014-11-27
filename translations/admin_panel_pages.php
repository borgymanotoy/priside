<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"pages_title" => "Rubrik",
						"subheading" => "Underrubrik",
						"button_brochure_consumer" => "Broschyr, konsument",
						"brochure" => "broschyr",

						"pnews_add" => "L�g till pressnyhet",
						"pnews_intro" => "Inledningstext pressidan.",
						"pnews_text_pressmedia" => "Presentationsmaterial Prisid�",
						"pnews_download_brochure" => "Ladda ner v�r broschyr om du vill veta mer om Prisid�.",
						"pnews_start_bns" => "Prisid� startar K�p & s�lj",
						"pnews_recruit_record" => "Prisid� rekryterar rekordm�nga nya medarbetare i �r",


					);

	$translation_array_en = array(
						"pages_title" => "Title",
						"subheading" => "Subheading",
						"button_brochure_consumer" => "Brochure, consumer",
						"brochure" => "brochure",



						"pnews_add" => "Add to the press news",
						"pnews_intro" => "Introduction Text press site.",
						"pnews_text_pressmedia" => "Presentation materials Prisid�",
						"pnews_download_brochure" => "Download our brochure to learn more about Prisid�.",
						"pnews_start_bns" => "Prisid� start the Buy & Sell",
						"pnews_recruit_record" => "Prisid� recruit a record number of new employees this year",

						);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else if( "sv" == $lang ){
	$trans = array_merge( $trans, $translation_array_sv );
}

?>