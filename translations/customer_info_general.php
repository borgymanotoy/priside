<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/common.php" );

	$translation_array_sv = array(
						"cust_nfo_gen_accnt" => "Kontotyp",
						"cust_nfo_gen_sls_accnt_mgr" => "Säljare/kundansvarig",
						"cust_nfo_gen_nw_notes" => "Anteckningar",

						"cust_nfo_gen_org_nr" => "Org.nr",
						"cust_nfo_gen_cust" => "Kundnr",
						"cust_nfo_gen_org" => "Organisationsform",
						"cust_nfo_gen_ind" => "Bransch",
						"cust_nfo_gen_ans_inq" => "Besvarade förfrågningar/Annonser",
						"cust_nfo_gen_cmplt_assgn" => "Utförda uppdrag/Aktiva annonser",
						"cust_nfo_gen_stat" => "Status",
						"cust_nfo_gen_reg" => "Registrerad",
						"cust_nfo_gen_lst_sn" => "Senast inloggad",
						"cust_nfo_gen_invcd" => "Senast fakturerad",
						"cust_nfo_gen_paid" => "Betald",
						"cust_nfo_gen_crdtd" => "Krediterad",
						"cust_nfo_gen_nxt_bill" => "Nästa fakturering",

						"cust_nfo_gen_btn_delete" => "Radera",
						"cust_nfo_gen_btn_disable" => "Avaktivera",
						"cust_nfo_gen_btn_enable" => "Aktivera",
						
						"cust_nfo_gen_active" => "Aktiv",
						"cust_nfo_gen_inactive" => "Inaktiv",
						
						"cust_nfo_gen_business_update_success" => "Business Account Manager namn och notera har uppdaterats",
						"cust_nfo_gen_no_changes" => "Inga ändringar gjordes",
						"cust_nfo_gen_business_deleted_success" => "Konto har tagits bort",
						"cust_nfo_gen_business_enabled_success" => "Konto framgångsrikt aktiverat",
						"cust_nfo_gen_business_disabled_success" => "Konto framgångsrikt inaktiverat",
						"cust_nfo_gen_confirm_delete" => "Är du säker på att du vill ta bort",
						"cust_nfo_gen_confirm_enable" => "Är du säker på att du vill aktivera",
						"cust_nfo_gen_confirm_disable" => "Är du säker på att du vill inaktivera",
						"cust_nfo_gen_account_type_change_success" => "Kontotyp har uppdaterats"

	);

	$translation_array_en = array(
						"cust_nfo_gen_accnt" => "Account",
						"cust_nfo_gen_sls_accnt_mgr" => "Sales / account manager",
						"cust_nfo_gen_nw_notes" => "Notes",

						"cust_nfo_gen_org_nr" => "Org.nr",
						"cust_nfo_gen_cust" => "Customer",
						"cust_nfo_gen_org" => "organization",
						"cust_nfo_gen_ind" => "industry",
						"cust_nfo_gen_ans_inq" => "Answer inquiries / Ads",
						"cust_nfo_gen_cmplt_assgn" => "Completed assignments / Active Ads",
						"cust_nfo_gen_stat" => "Status",
						"cust_nfo_gen_reg" => "Registered",
						"cust_nfo_gen_lst_sn" => "Last seen",
						"cust_nfo_gen_invcd" => "Last invoiced",
						"cust_nfo_gen_paid" => "Paid",
						"cust_nfo_gen_crdtd" => "credited",
						"cust_nfo_gen_nxt_bill" => "Next billing",

						"cust_nfo_gen_btn_delete" => "Delete",
						"cust_nfo_gen_btn_disable" => "Disable",
						"cust_nfo_gen_btn_enable" => "Enable",
						
						"cust_nfo_gen_active" => "Active",
						"cust_nfo_gen_inactive" => "Inactive",

						"cust_nfo_gen_business_update_success" => "Business Account Manager name and note was successfully updated",
						"cust_nfo_gen_no_changes" => "No changes were made",
						"cust_nfo_gen_business_deleted_success" => "Account was successfully deleted",
						"cust_nfo_gen_business_enabled_success" => "Account was successfully enabled",
						"cust_nfo_gen_business_disabled_success" => "Account was successfully disabled",
						"cust_nfo_gen_confirm_delete" => "Are you sure you want to delete",
						"cust_nfo_gen_confirm_enable" => "Are you sure you want to enable",
						"cust_nfo_gen_confirm_disable" => "Are you sure you want to disable",
						"cust_nfo_gen_account_type_change_success" => "Account Type was successfully updated"
	);

if( ! is_array( $trans ) ) $trans = array();

if( "en" == $lang ){
	$trans = array_merge( $trans, $translation_array_en );
}
else{
	$trans = array_merge( $trans, $translation_array_sv );
}
?>