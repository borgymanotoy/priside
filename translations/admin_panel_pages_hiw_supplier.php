<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/pages_common.php" );

	$translation_array_sv = array(
						"hiw_supplier_title" => "Så fungerar Prisidé",
						"hiw_supplier_subheading" => "Underrubrik",
						"hiw_supplier_text" => "Text",
						"hiw_supplier_button_brochure" => "Broschyr, leverantör",
						"hiw_supplier_brochure" => "broschyr",

						"hiw_supplier_save_success_msg" => "Så priside fungerar : Leverantör informationen sparats.",
						"hiw_supplier_update_success_msg" => "Så priside fungerar : Leverantör informationen uppdateras.",
						"hiw_supplier_error_entry_validation" => "Vänligen fyll upp posterna på rätt sätt.",
						// php errors / etc.

						"phiw_supplier_ErrMsg_NoBanner" => "Please select banner image.",
						"phiw_supplier_ErrMsg_SupplierSubHeading1" => "Supplier Sub-Heading 1 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeading2" => "Supplier Sub-Heading 2 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeading3" => "Supplier Sub-Heading 3 cannot be blank.",

						"phiw_supplier_ErrMsg_SupplierSubHeadingText1" => "Supplier Sub-Heading Text 1 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeadingText2" => "Supplier Sub-Heading Text 2 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeadingText3" => "Supplier Sub-Heading Text 3 cannot be blank.",
					);

	$translation_array_en = array(
						"hiw_supplier_title" => "How Prisidé works",
						"hiw_supplier_subheading" => "Subheading",
						"hiw_supplier_text" => "Text",
						"hiw_supplier_button_brochure" => "Brochure, supplier",
						"hiw_supplier_brochure" => "brochure",

						"hiw_supplier_save_success_msg" => "How priside works : Supplier information are successfully saved.",
						"hiw_supplier_update_success_msg" => "How priside works : Supplier information are successfully updated.",
						"hiw_supplier_error_entry_validation" => "Please fill-up the entries properly.",

						// php errors / etc.
						"phiw_supplier_ErrMsg_NoBanner" => "Please select banner image.",
						"phiw_supplier_ErrMsg_SupplierSubHeading1" => "Supplier Sub-Heading 1 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeading2" => "Supplier Sub-Heading 2 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeading3" => "Supplier Sub-Heading 3 cannot be blank.",

						"phiw_supplier_ErrMsg_SupplierSubHeadingText1" => "Supplier Sub-Heading Text 1 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeadingText2" => "Supplier Sub-Heading Text 2 cannot be blank.",
						"phiw_supplier_ErrMsg_SupplierSubHeadingText3" => "Supplier Sub-Heading Text 3 cannot be blank.",
					);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>