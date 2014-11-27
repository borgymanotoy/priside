<? 
	// required of *ALL* php scripts - this authenticates the current
	// user / session, and if not valid or not logged in, redirects to login
	//require_once( "libs/verify_session.php" );
	
	//	
	require_once( "libs/db_common.php" );
	require_once( "libs/lib_common.php" );
	

	//echo "You are now logged in...";
	//include( "/inc_index.html" );
	//include( "/registration_page_one.php" );

	include( "/inc_main_index.html" );

	exit;
?>