<?
/*
	Login module.
	Verify Email (username) and Password, Setup a session, update last login, redirect.
	
	Session contains User,R,T,Session_Key,K - (User - User's db record, R - random, T - time, Session_Key - CheckSum salt, K - key)
	
	$_SESSION['User'] - an associative array of the user's db record Id,Email etc.. access via: $_SESSION['User']['Id'] ...
	$_SESSION['SESSION_RTPK_R'] = a random value 
	$_SESSION['SESSION_RTPK_T'] = current time in seconds
	$_SESSION['SESSION_KEY'] = a key of md5( <User's Password> + R + T . "secret string 1" )
	$_SESSION['SESSION_RTPK_K'] = a key of md5( R + T + User + Session_Key + "secret string 2" )
	
	Once a session is established use libs/verify_session.php to verify.
	
	April 2012, Chris J. Veeneman
*/
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/constants.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/translations/login.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_common.php" );
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_login.php" );

$lang = DEFAULT_LANG;
if( isset( $_GET['lang'] ) )
	$lang = $_GET['lang'];
//
// login button handler
// only runs if login button pressed AND the user field is filled in AND the password field is filled in.
//
if( isset( $_POST['LoginButton'] ) && ! empty( $_POST['Email'] ) && ! empty( $_POST['Pwd'] ) ){
	
	db_connect();
	
	$authRet = Authenticate( $_POST['Email'], $_POST['Pwd'] );
	if( 0 == $authRet ){
		//redirect to admin page if the user is an administrator
		if( 0 != $_SESSION['User']['IsPrisideAdmin'] ){
			header( "Location: /admin/index.php" );
			exit;
		}
		header( "Location: /users_panel_main.php" );
		exit;
	}
	$ErrMsgs[] = $trans["login_invaliduser"];
}
else if( isset( $_POST['LoginButton'] ) && empty( $_POST['Email'] ) )
	$ErrMsgs[] = $trans["login_emailblank"];
else if( isset( $_POST['LoginButton'] ) && empty( $_POST['Pwd'] ) )
	$ErrMsgs[] = $trans["login_passblank"];

// $ErrMsg may contain an error msg from verify session.
// if so, add it to the error msgs array for display in the html doc...
if( ! empty( $ErrMsg ) )
	$ErrMsgs[] = $ErrMsg;
	
include( "inc_index.html" );
exit;
?>
