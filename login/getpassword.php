<?
require_once( "../libs/db_common.php" );
require_once( "../libs/lib_common.php" );
	//
	//
	//

	$OkMsgs = array();

	if( isset( $GetPasswordButton ) && ! empty( $Email ) ){

		db_connect();
		
		// lookup email provided, reset the associated account's password to a random password,
		// email that new password to the specified email address
		
		$Sql = "SELECT Id,Email FROM User WHERE LCASE( Email ) = \"".strtolower( $Email )."\" LIMIT 1";
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsgs[] =  "Error with Users for SELECT[Code: ".mysql_errno()."@".__LINE__."] Module: ".__FILE__.mysql_error();
			include( "inc_getpassword.html" );
			exit;
		}
		$found = mysql_num_rows( $result );
		$record = mysql_fetch_row( $result );
		mysql_free_result( $result );

		if( 0 === found || 0 != strcasecmp( $record[1], $Email ) ){
			$ErrMsgs[] = "Sorry, we have no record of that email address / login.";
			include( "inc_getpassword.html" );
			exit;
		}
		
		// reset password with a random value, then update and email...
		mt_srand( (double)microtime() * 1000000 );
		$r = mt_rand( 0, mt_getrandmax() ); 
		$t = time();
		$md5string = md5( $Email . $r . $t );
		$newpwd = "";
		for( $k = 0; $k < 32; $k +=4 ){
			$newpwd .= $md5string[$k];
		}
		
		$Sql = "UPDATE User SET Password = MD5( \"$newpwd\" ) WHERE Id = {$record[0]} AND LCASE( Email ) = \"{$record[1]}\" LIMIT 1";
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsgs[] =  "Error with Users for UPDATE[Code: ".mysql_errno()."@".__LINE__."] Module: ".__FILE__.mysql_error();
			include( "inc_getpassword.html" );
			exit;
		}
		
		$EmailBody = "Your password has been reset.  The new password is: \r\n$newpwd\r\n";
		
		$ret = mail( $record[1], "Your Password Has Been Reset.", $EmailBody, "From: \"Priside Website\"<noreply@priside.se>" );
		if( ! $ret ){
			$ErrMsgs[] = "There was an error with sendmail. Code: ".__LINE__."\r\n";
			include( "inc_getpassword.html" );
			exit;
		}
		
		$OkMsgs[] = "You're new password has been emailed to: \"{$record[1]}\"";
		include( "index.php" );
		exit;
	}
	else {
		$Email = "";
	}
	include( "inc_getpassword.html" );
	exit;
?>
