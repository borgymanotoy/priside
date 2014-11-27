<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/customer_info_page.php" );
	
	//db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	
	$ErrMsg = "";
	$OkMsg  = "";
		
	include( "inc_customer_info_page.html" );
	return;
?>