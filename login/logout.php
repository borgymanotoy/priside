<?
	//require_once( "../libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/constants.php" );
	$lang = DEFAULT_LANG;
	if( isset( $_GET['lang'] ) )
		$lang = $_GET['lang'];
	session_start();
	
	unset( $_SESSION[ "SESSION_RTPK_R" ] );
	unset( $_SESSION[ "SESSION_RTPK_T" ] );
	unset( $_SESSION[ "SESSION_RTPK_K" ] );
	unset( $_SESSION[ "SESSION_KEY" ] );
	unset( $_SESSION[ "User" ] );
	unset( $_SESSION[ "Business" ] );

	@session_destroy();

	//DestroySession();
	include( "inc_logout.html" );
	exit;
?>
