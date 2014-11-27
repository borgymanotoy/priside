<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"pages_title" => "Rubrik",
						"subheading" => "Underrubrik",
						"button_brochure_consumer" => "Broschyr, konsument",
						"brochure" => "broschyr",

						"pnews_add" => "Läg till pressnyhet",
						"pnews_intro" => "Inledningstext pressidan.",
						"pnews_text_pressmedia" => "Presentationsmaterial Prisidé",
						"pnews_download_brochure" => "Ladda ner vår broschyr om du vill veta mer om Prisidé.",
						"pnews_start_bns" => "Prisidé startar Köp & sälj",
						"pnews_recruit_record" => "Prisidé rekryterar rekordmånga nya medarbetare i år",


					);

	$translation_array_en = array(
						"pages_title" => "Title",
						"subheading" => "Subheading",
						"button_brochure_consumer" => "Brochure, consumer",
						"brochure" => "brochure",



						"pnews_add" => "Add to the press news",
						"pnews_intro" => "Introduction Text press site.",
						"pnews_text_pressmedia" => "Presentation materials Prisidé",
						"pnews_download_brochure" => "Download our brochure to learn more about Prisidé.",
						"pnews_start_bns" => "Prisidé start the Buy & Sell",
						"pnews_recruit_record" => "Prisidé recruit a record number of new employees this year",

						);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else if( "sv" == $lang ){
	$trans = array_merge( $trans, $translation_array_sv );
}

?>