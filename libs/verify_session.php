<?
// Verify Session
// Verify the current session variables by recaculating the key hash(es) via key ingredients
// This file should be required via require_once() as the FIRST line in any script that requires authentication.

// Session contains User,R,T,Session_Key,K - (User - User's db record, R - random, T - time, Session_Key - CheckSum salt, K - key)

// $_SESSION['User'] - an associative array of the user's db record Id,Email etc.. access via: $_SESSION['User']['Id'] ...
// $_SESSION['SESSION_RTPK_R'] = a random value 
// $_SESSION['SESSION_RTPK_T'] = current time in seconds
// $_SESSION['SESSION_KEY'] = a key of md5( <User's Password> + R + T . "secret string 1" )
// $_SESSION['SESSION_RTPK_K'] = a key of md5( R + T + User + Session_Key + "secret string 2" )

// A session is verified by first checking the existance of all variables.
// If present, a new key is calculated using the session variables.
// The new key is compared to the key present in the existing session, if valid, the session is updated and allowed to continue.

// Session Failure Handling - The default action is to redirect with an error message ($ErrMsg) to the "/login/?" url.
// You can over-ride this behaviour by defining a callback function or redirect url, and setting their values 
// *before* verify_session.php is included or required ...
// the callback will be called with two params, $ErrNo and $ErrMsg.
// the url will have ?$ErrNo&$ErrMsg as url params

// $VerifySessionCallBack = "foo";                   // will call foo( $ErrNo, $ErrMsg );
// $VerifySessionRedirectUrl = "/custom/location";   // will redirect to "/custom/location/?ErrNo&ErrMsg


// April 2012, Chris J. Veeneman


//
//
// this file verifies / updates the user session
//
//
// create a checksum from the variable data pointed to by SrcData (it may also be an array of variables)
// including some current session information, such that url's containing Id's
// can not be un-knowingly modified.
// 

//our constants / defines
require_once( $_SERVER['DOCUMENT_ROOT']."/libs/constants.php" );

//
// HACK for missing file_info() extension.
// 
if( ! function_exists( "finfo_open" ) ) {
	function finfo_open( $arg ){
		return( true );
	}
}
if( ! function_exists( "finfo_file" ) ) {
	function finfo_file( $arg, $file ){
		$mimeType = `/usr/bin/file -ib $file`;
		$offset = strpos( $mimeType, ";" );
		if( FALSE === $offset )
			return( $mimeType );
		return( substr( $mimeType, 0, $offset - 1 ) );
	}
}
//
//
//
function CheckSum( $SrcData ){
	if( is_array( $SrcData ) ){
		$chk_data = "";
		while( list( $k, $v ) = each( $SrcData ) )
			$chk_data .= $v;
	}
	else
		$chk_data = $SrcData;
	return(	md5( $_SESSION[ "SESSION_KEY" ] . $chk_data ) );
}
//
// verify a checksum against data
// 
function VerifyCheckSum( $OldData, $OldCheckSum ){
	if( 0 == strcmp( $OldCheckSum, CheckSum( $OldData ) ) )
		return( true );
	return( false );
}
//
//
//
function IsAdmin(){
	return( "Admin" == $_SESSION['User']['UserType'] );
}
//
// destroy an open session
// 
function DestroySession(){

	//
	// one or more of the session variables are not registered.
	// unset and kill any old session variables that may still exist,
	//
	unset( $_SESSION[ "SESSION_RTPK_R" ] );
	unset( $_SESSION[ "SESSION_RTPK_T" ] );
	unset( $_SESSION[ "SESSION_RTPK_K" ] );
	unset( $_SESSION[ "SESSION_KEY" ] );
	unset( $_SESSION[ "User" ] );
	unset( $_SESSION[ "Business" ] );

	@session_destroy();
	return;
}
//
// Verify the session by cecking required variables (key ingredients)
// verifying the internal session key, then re-generate new keys
//
// returns 0 on succes or -1 .. -x on error
//
function VerifySession(){
	
	$SessionKeyCount = count( $_SESSION, COUNT_RECURSIVE );
	$SessionId = session_id();
	$SessionName = session_name();
	
	// ensure all session variables are set.
	if( 
		$SessionKeyCount < 5 ||
		! isset( $_SESSION[ "SESSION_RTPK_R" ] ) ||
		! isset( $_SESSION[ "SESSION_RTPK_T" ] ) ||
		! isset( $_SESSION[ "SESSION_RTPK_K" ] ) ||
		! isset( $_SESSION[ "SESSION_KEY" ] ) ||
		! isset( $_SESSION[ "User" ] ) ) {

		DestroySession(); // kill whatever may be there
		
		// session is not valid ... why ?
		
		// no session variables exist - not logged in
		if( 0 == $SessionKeyCount ){
			if( "/" === $_SERVER["REQUEST_URI"] )	
				return( -1 );
			else
				return( -2 );
		}
		// on or more session variables missing
		return( -3 ); // session was corrupt
	}
	// other checks can be added here (ip checks for content availability etc)
		
	// create a new key from session key ingredients
	$calc_key = md5( $_SESSION[ "SESSION_RTPK_R" ] . $_SESSION[ "SESSION_RTPK_T" ] . serialize( $_SESSION[ "User" ] ) . $_SESSION[ "SESSION_KEY" ] . "D^f<8@fh6(*SA@!><*JKfjdh5" );

	// verify new key against existing key
	if( 0 != strcmp( $calc_key, $_SESSION[ "SESSION_RTPK_K" ] ) ){
		DestroySession();
		return( -4 );
	}
	// session is good!
	// update key ingredients and generate a new key
	mt_srand( (double)microtime() * 1000000 );
	$r = mt_rand( 0, mt_getrandmax() ); 
	$t = time();
	// regenerate key
	$k = md5( $r . $t . serialize( $_SESSION[ "User" ] ) . $_SESSION[ "SESSION_KEY" ] . "D^f<8@fh6(*SA@!><*JKfjdh5" );
	
	$_SESSION[ "SESSION_RTPK_R" ] = $r;
	$_SESSION[ "SESSION_RTPK_T" ] = $t;
	$_SESSION[ "SESSION_RTPK_K" ] = $k;
	
	// all good!
	return( 0 );
}
//---------------------------------------------------------------------------------------------------------------------------------
// begin session verification
//---------------------------------------------------------------------------------------------------------------------------------
	@session_start();

	$sessionState = VerifySession();
	if( 0 != $sessionState ){
		switch( $sessionState ){
			// not logged in
			case -1 : {
				$ErrMsg = "Not Authenticated (".__LINE__.")";
				break;
			}
			// session expired via time (garbage collection) or logout
			case -2 : {
				$ErrMsg = "Session Retired (".__LINE__.")";
				break;
			}
			// session corrupted
			case - 3: {
				$ErrMsg = "Re-Authentication Required (".__LINE__.")";
				break;
			}
			// key verification failed (altered session vars?)
			case -4: {
				$ErrMsg = "Session Rejected (".__LINE__.")";
				break;
			}
			// anything else
			default : {
				$ErrMsg = "Session Error (".__LINE__.")";
				break;
			}
		} // switch
		
		//
		// what to do on a session verification error
		//
		
		// is there a callback param ? if so, do that
		if( function_exists( $VerifySessionCallBack ) ){
			$lang = DEFAULT_LANG;
			call_user_func( $VerifySessionCallBack, $sessionState, $ErrMsg );
			exit;
		}
		// is there a redirect url ? if so, go there :)
		else if( isset( $VerifySessionRedirectUrl ) ){
			header( "Location: $VerifySessionRedirectUrl?ErrNo=$sessionState&ErrMsg=".urlencode( $ErrMsg ) );
			exit;
		}
		//
		// otherwise, the default is to prompr re-login with a dialog box, then redirect 
		// this will shove a small script inline, which will get the dialog box (inc_dialogboxlogin.html)
		// the inc_dialogboxlogin.html contains handler for the login action etc.
		// if the document for which this script works with doesn't exist, the exception handler
		// will fallback to a vanila redirect.
		//
		echo "<script type=\"text/javascript\">
				try {
					$.get( \"/login/inc_dialogboxlogin.html?".time()."\", 
					function( data ){ 
						if( $('#dialogBoxReloginContainer').length ){ 
							$('#dialogBoxReloginContainer').html( data ); 
						} 
						else { 
							$('body').append( data ); 
						} 
					} ); 
				} catch(e) {
					window.location = '/login/index.php?ErrNo=$sessionState&ErrMsg=".urlencode( $ErrMsg )."';
				}
			</script>";
		exit;
		// old redirect method, not so ajax friendly. (want a login form in your drop down box ??? :
		//header( "Location: /login/?ErrNo=$sessionState&ErrMsg=".urlencode( $ErrMsg ) );
		//exit;
	}
	//---------------------------------------------------------------------------------------------
	// at this stage the session is verified in terms of validity
	// but no business rules have been applied in terms of ACL's etc.
	// this section is used for those rules.
	//---------------------------------------------------------------------------------------------
	
	//
	// rule 1) a priside admin must have IsPrisideAdmin set to 1 and must have
	//         an account id matching the defined system account PRISIDE_ACCOUNT_ID in constants.php
	//
	if(	"0" != $_SESSION['User']['IsPrisideAdmin'] && PRISIDE_ACCOUNT_ID != $_SESSION['User']['AccountId'] ){
		header( "Status: 400 Insufficient Privileges for priside administration. " . __LINE__ );
		exit;
	}
	//
	// rule 2) you must be a priside admin to be viewing anything in /admin/...
	//
	if( "0" == $_SESSION['User']['IsPrisideAdmin'] && stristr( $_SERVER["REQUEST_URI"], "/admin/" ) ){
		header( "Status: 400 Insufficient Privileges for priside administration. " . __LINE__ );
		exit;
	}
	// other rules / ACL checks here.
	
	//---------------------------------------------------------------------------------------------
	// at this stage the session is verified and ready for use
	// now we can add constants / settings / defaults / localization etc.
	//---------------------------------------------------------------------------------------------
	
	
	// set default lang / or update session based on url
	// "$lang" is used by the translations/xxx.php files
	// and must be defined here
	if( isset( $_SESSION[ "LANG" ] ) )
		$lang = $_SESSION[ "LANG" ];
	else
		$lang = DEFAULT_LANG;

	if( isset( $_GET['lang'] ) ){
		$lang = $_GET['lang'];
		$_SESSION[ "LANG" ] = $_GET['lang'];
	}
?>