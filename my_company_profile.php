<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/public_verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/my_company_profile.php" );
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";

// -------------------------------------------------------------
// Ajax Request
// -------------------------------------------------------------

//ajaxRequest=getAllContacts
	if( "getAllContacts" == $ajaxRequest ){

		
		db_connect();
		
		$Sql = "SELECT Id,CONCAT_WS( FirstName, ' ', LastName ) AS Name, Title,IsPrimary FROM BusinessContacts WHERE Business_Id = {$_SESSION['User']['Business_Id']} LIMIT 50";

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}
		if( 0 == mysql_num_rows( $result ) ){
			// no contacts to display.
			print "";
			exit;
		}
		$HTML = "";
		while( $row = mysql_fetch_row( $result ) ){
			// create <li onClick="foo(x,y)"> Name (Title)</li>
			$chksum = CheckSum( $row[0] );
			$HTML .= "<li onclick=\"getContact({$row[0]},'$chksum');\">{$row[1]} ({$row[2]})";
			if( 1 == $row[4] )
				$HTML .= "*";
			$HTML .= "</li>\n";
		}
		mysql_free_result( $result );
		print $HTML;
		exit;
	}
//ajaxRequest=getContact&Id=3&CheckSum=5cf2793cb14b8dab1736cfa9824dd6e1
	else if( "getContact" == $ajaxRequest ){

		// verify checksum
		if( ! VerifyCheckSum( $Id, $CheckSum ) ){
			$ErrMsg = "CheckSum Failed @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}

		db_connect();

		$Sql = "SELECT Title,FirstName,LastName,Email,Phone,AltPhone,Address,Postal,City FROM BusinessContacts WHERE Id = $Id LIMIT 1";
		
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}
		if( 0 == mysql_num_rows( $result ) ){
			$ErrMsg = "Error, no data found @ " . __LINE__;
			header( "Status: 400 " . $ErrMsg );
			exit;
		}
		$Contact = mysql_fetch_array( $result );
		mysql_free_result( $result );
		
		ob_start();
		include( "my_company_profileSnippet.html" );
		$my_company_profileSnippet_html = ob_get_contents();
		ob_end_clean();

		print $my_company_profileSnippet_html;

		exit;
		
	} 
//ajaxRequest=loadCertification
	else if ("loadCertification" == $ajaxRequest) {

		db_connect();

		$Sql = "SELECT 
					C.Name AS Certification_Name
					,C.Logo_File_Id AS File_Id
				FROM BusinessCertifications BC
				INNER JOIN Certifications C
				ON C.Id = BC.Certifications_Id
				WHERE BC.Business_Id = {$_SESSION['Business']['Id']}
				AND BC.Status = 'Approved'";
		$result = mysql_query( $Sql );

		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}
		
		if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( "inc_nodata_snippet.html" );
			$inc_my_company_references_snippet_html = ob_get_contents();
			ob_end_clean();
			print $inc_my_company_references_snippet_html;
			exit;
		}
		
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			$row['LogoUrl'] = '/libs/public_get_file.php?file=' . $row['File_Id'] . ':' . PublicCheckSum( $row['File_Id'] ) . '&disposition=inline';
			$images_html .= "<img src=\"{$row['LogoUrl']}\" alt=\"{$row['Certification_Name']}\" />";
		}

		mysql_free_result( $result );
		
		print $images_html;
		exit;
	} 
//ajaxRequest=loadReferences
	else if ("loadReferences" == $ajaxRequest ) {

		db_connect();

		$Sql = "SELECT DISTINCT A.Id , CONCAT(B.FirstName, ' ', B.LastName) as Name
			,DATE(BR.CreationDate) AS CreationDate 
			,BR.Comments AS Comments 
			,BR.Status AS Status 
			,B.Phone AS Phone 
			,B.Email AS Email 
			,BR.Status AS Status 
			,BR.Author_User_Id AS User_Id
			,BR.Business_Id AS Business_Id
		FROM Accounts A
		INNER JOIN User U ON U.Account_Id = A.Id
		INNER JOIN BusinessReferences BR ON BR.Author_User_Id = A.Id
		INNER JOIN Business B ON B.Id = BR.Business_Id
		WHERE A.Id = {$_SESSION['User']['AccountId']} AND BR.Status = 'Approved'";

		$result = mysql_query( $Sql );

		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}

		if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( "inc_nodata_snippet.html" );
			$inc_my_company_references_snippet_html = ob_get_contents();
			ob_end_clean();
			print $inc_my_company_references_snippet_html;
			exit;
		}

		$index = 1;
		ob_start();

		while($references = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$cssClass = ($index%2 == 0) ? "even" : "odd";
			include( "inc_my_company_references_snippet.html" );
			$index++;
		}

		mysql_free_result( $result );
		$inc_my_company_references_snippet_html = ob_get_contents();
		ob_end_clean();

		print $inc_my_company_references_snippet_html;
		exit;

	} 
//ajaxRequest=loadReviews
	else if ("loadReviews" == $ajaxRequest) {
		db_connect();

		$Sql  = "SELECT CONCAT(FirstName, ' ', LastName) as Name ";
		$Sql .=	"	,Comments ";
		$Sql .=	"	,DATE(CommentDate) as CommentDate ";
		$Sql .=	"	,ReplyComments ";
		$Sql .=	"	,ReplyDate ";
		$Sql .=	"	,Rating ";
		$Sql .=	"	,User_Id ";
		$Sql .=	"FROM BusinessRatings ";
		$Sql .=	"INNER JOIN User ";
		$Sql .=	"ON BusinessRatings.User_Id = User.Id ";
		$Sql .=	"WHERE BusinessRatings.Business_Id = {$_SESSION['Business']['Id']} LIMIT 1000";

		$result = mysql_query( $Sql );
		
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: __LINE__ : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}

		if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( "inc_nodata_snippet.html" );
			$inc_my_company_my_reviews_snippet_html = ob_get_contents();
			ob_end_clean();
			print $inc_my_company_my_reviews_snippet_html;
			exit;
		}

		$index = 1;	
		ob_start();

		while($review = mysql_fetch_array( $result, MYSQL_ASSOC )){

			// add stripe effect 
			$cssClass = ($index%2 == 0) ? "even" : "odd";
			$isReply = (empty( $review["ReplyComments"] )) ? true : false;

			// set reputation image
			for($i=0; $i<$review["Rating"]; $i++) {
				$ratings .= "<img src=\"img/reputation.png\" />";
			}
			
			include( "inc_my_company_my_reviews_snippet.html" );
			$ratings = "";
			$index++;

		}
		mysql_free_result( $result );	

		$inc_my_company_my_reviews_snippet_html = ob_get_contents();
		ob_end_clean();

		print $inc_my_company_my_reviews_snippet_html;

		exit;
	}

// -------------------------------------------------------------
// First Load
// This section is called during the first load of the document.
//
// -------------------------------------------------------------

	$Contact['OrgNumber'] = $_SESSION['Business']['OrgNumber'];
	$Contact['Title'] = $_SESSION['Business']['Title'];
	$Contact['FirstName'] = $_SESSION['Business']['FirstName'];
	$Contact['LastName'] = $_SESSION['Business']['LastName'];
	$Contact['Email'] = $_SESSION['Business']['Email'];
	$Contact['Phone'] = $_SESSION['Business']['Phone'];
	$Contact['AltPhone'] = $_SESSION['Business']['AltPhone'];
	$Contact['Address'] = $_SESSION['Business']['Address'];
	$Contact['Postal'] = $_SESSION['Business']['Postal'];
	$Contact['City'] = $_SESSION['Business']['City'];
	
	
	if( isset( $_SESSION['Business']['LogoFile_Id'] ) ){
		$Business['LogoUrl'] = "/libs/public_get_file.php?file=" . $_SESSION['Business']['LogoFile_Id'].":".PublicCheckSum( $_SESSION['Business']['LogoFile_Id'] ) . '&disposition=inline&' . time();
	}
	
	ob_start();
	include( "my_company_profileSnippet.html" );
	$my_company_profileSnippet_html = ob_get_contents();
	ob_end_clean();


	include( "inc_my_company_profile.html" ); 
	return;
?>

