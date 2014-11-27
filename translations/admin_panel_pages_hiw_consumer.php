<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"hiw_consumer_title" => "Så fungerar Prisidé",
						"hiw_consumer_subheading" => "Underrubrik",
						"hiw_consumer_text" => "Text",
						"hiw_consumer_brochure" => " broschyr",
						"hiw_consumer_button_brochure" => "Broschyr, konsument",

						"hiw_consumer_save_success_msg" => "Så priside fungerar : Konsument information framgångsrikt sparas.",
						"hiw_consumer_update_success_msg" => "Så priside fungerar : Konsument information framgångsrikt uppdateras.",
						"hiw_consumer_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",

						// php errors / etc.
						"phiw_consumer_ErrMsg_NoBanner" => "Please select banner image.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading1" => "Consumer Sub-Heading 1 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading2" => "Consumer Sub-Heading 2 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading3" => "Consumer Sub-Heading 3 cannot be blank.",

						"phiw_consumer_ErrMsg_ConsumerSubHeadingText1" => "Consumer Sub-Heading Text 1 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeadingText2" => "Consumer Sub-Heading Text 2 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeadingText3" => "Consumer Sub-Heading Text 3 cannot be blank.",
					);

	$translation_array_en = array(
						"hiw_consumer_title" => "How Prisidé works",
						"hiw_consumer_subheading" => "Subheading",
						"hiw_consumer_text" => "Text",
						"hiw_consumer_brochure" => "brochure",
						"hiw_consumer_button_brochure" => "Brochure, Consumer",

						"hiw_consumer_save_success_msg" => "How priside works : Consumer information are successfully saved.",
						"hiw_consumer_update_success_msg" => "Så priside fungerar : Konsumentinformation framgångsrikt updated.",
						"hiw_consumer_error_entry_validation" => "Please fill-up the entries properly.",

						// php errors / etc.
						"phiw_consumer_ErrMsg_NoBanner" => "Please select banner image.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading1" => "Consumer Sub-Heading 1 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading2" => "Consumer Sub-Heading 2 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeading3" => "Consumer Sub-Heading 3 cannot be blank.",

						"phiw_consumer_ErrMsg_ConsumerSubHeadingText1" => "Consumer Sub-Heading Text 1 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeadingText2" => "Consumer Sub-Heading Text 2 cannot be blank.",
						"phiw_consumer_ErrMsg_ConsumerSubHeadingText3" => "Consumer Sub-Heading Text 3 cannot be blank.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>