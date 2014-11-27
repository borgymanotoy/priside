<?php
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/cancel_account.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notification.php" );

if ("cancelAccount" == $ajaxRequest) {

	$notificationVariables = array("SERVERNAME" => $_SERVER['SERVER_NAME']
		,"SenderName" => "{$_SESSION['User']['FirstName']} {$_SESSION['User']['LastName']}"
		,"SenderEmail" => "{$_SESSION['User']['Email']}"
		,"SenderCustomerNumber" => "{$_SESSION['User']['Id']}");
	
	SendNotification(NOTIFYCUSTOMERCLOSEACCOUNT, $notificationVariables, SYSTEM_FROM_ID, $ErrMsg);
	
	exit;
}

include( "inc_cancel_account.html" ); 
return;
?>