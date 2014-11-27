<? 
	// required of *ALL* php scripts - this authenticates the current
	// user / session, and if not valid or not logged in, redirects to login
	// require_once( $_SERVER['DOCUMENT_ROOT']."/translations/index.php" );

	// ***************************************************************
	// NOTE: lib_ads.php is included in this code since we need to
	//       retain the session if there is a session and lib_ads.php
	//       do the magic for this.
	//
	// require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_ads.php" );
	//
	// ***************************************************************


	require_once( "libs/db_common.php" );
	require_once( "libs/lib_common.php" );

	include( "inc_index.html" );
?>
