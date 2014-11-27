<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );

	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/my_account.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";
	
	$SavePath = base64_encode( $_SESSION['User']['UploadPath'] ).',CheckSum='.CheckSum( base64_encode( $_SESSION['User']['UploadPath'] ) ).',Session='.session_id();


	function GetComboboxItems($div_name, $span_name){
		
		$Sql = "SELECT Id , CONCAT_WS( ' ',FirstName,LastName) AS Name FROM User WHERE Account_Id = {$_SESSION['User'][AccountId]} LIMIT 50";
		$result = mysql_query( $Sql );

		while($row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			
			$list_html .= "<li id=\"org_".$row['Id']."\" value=\"".$row['Id']."\" ";
			$list_html .= "onclick=\"displaySelectedItem('".$div_name."', '".$span_name."', '".$row['Name']."');ajaxLoadSelectedUser( {$row['Id']},'".CheckSum( $row['Id'] )."' );\">".$row['Name'];
			//if( 0 != $row['IsPrimary'] )
				//$list_html .= "*";
			$list_html .="</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
	}

		
	// -------------------------------------------------------------
	// Ajax Request
	// -------------------------------------------------------------


	if( "ajaxReloadUserList" == $ajaxRequest ){
			db_connect();
			$userListHTML = GetComboboxItems("div_contact", "spn_contact" );
			print $userListHTML;
			exit;
	}
	else if ( "ajaxLoadSelectedUser" == $ajaxRequest) {

		if( ! VerifyCheckSum( $UserId, $CheckSum ) ){
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}

		db_connect();

		// create query
		$Sql =  "SELECT FirstName ";
		$Sql .= "       ,LastName ";
		$Sql .= "       ,Position ";
		$Sql .= "       ,Phone ";
		$Sql .= "       ,Email ";
		$Sql .= "       ,AltPhone ";
		$Sql .= "       ,Type ";
		$Sql .= "       ,Active ";
		$Sql .= "       ,Password ";
		$Sql .= "       ,Id,ProfilePhotoFile_Id ";
		$Sql .= "  FROM User ";
		$Sql .= " WHERE Id = {$UserId} LIMIT 1";

		$result = mysql_query( $Sql );
		
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}

		if( 0 == mysql_num_rows( $result ) ){
			// no contacts to display.
			print "No Users Found";
			exit;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		if( empty( $row['ProfilePhotoFile_Id'] ) )
			$PhotoUrl = '/img/person-pic.png';
		else
			$PhotoUrl = '/libs/get_file.php?file=' . $row['ProfilePhotoFile_Id'] . ':' . CheckSum( $row['ProfilePhotoFile_Id'] ) . '&disposition=inline&' . time();

		$data = Array( "FirstName"=>$row["FirstName"]
					  ,"LastName"=>$row["LastName"]
					  ,"Title"=>$row["Position"]
					  ,"Phone"=>$row["Phone"]
					  ,"Email"=>$row["Email"]
					  ,"AltPhone"=>$row["AltPhone"]
					  ,"Type"=>$row["Type"]
					  ,"Active"=>$row["Active"]
					  ,"Password"=>$row["Password"]
					  ,"Password"=>$row["Password"]
					  ,"UserId"=>$UserId //update hidden field
					  ,"PhotoUrl"=>$PhotoUrl
					  ,"CheckSum"=>$CheckSum	//update hidden field
					  /*
					  ,"UpdateContactButton"=>$trans["modify_my_company_prof_save_contact"]
					  */
		);
		mysql_free_result( $result );
		
		// load user preferences
		$Sql = "SELECT Notification_Id AS I,Notify AS N,DeliveryMethod AS D FROM NotificationPreferences WHERE User_Id = " . $UserId;
		$result = mysql_query( $Sql );
		$NotificationPreferences = array();
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			$row['I'] = (int)$row['I']; //index
			$row['N'] = (int)$row['N']; //notify (yes/no)
			$row['D'] = (int)$row['D']; //delivery method
			$NotificationPreferences[] = $row;
		}
		mysql_free_result( $result );
		
		// add to the data array
		$data['NotificationPreferences'] = $NotificationPreferences;

		header( "Content-Type: application/json" );
		print json_encode( $data );
		exit;

	}
	else if ("ajaxCreateUser" == $ajaxRequest) {

		// only admin's can create users...
		if( ! IsAdmin() ){
			header( "Status: 400 Insufficient Privileges. " . __LINE__ );
			exit;
		}
	
		db_connect();
		
		$Sql = "SELECT
				CASE WHEN COUNT(DISTINCT U.Id) >= A.MaxUsers THEN 0 ELSE 1 END AS CAN_ADD
				FROM Accounts A INNER JOIN User U ON A.Id = U.Account_Id
				WHERE A.Id = {$_SESSION['User']['AccountId']} LIMIT 1";

		$result = mysql_query($Sql);
		$row= mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );
		
		// checks to see if user create
		if(0 == $row["CAN_ADD"]){
			header("Status: 400  Exceeded Maximum number of User @ " . __LINE__ );
			exit;
		}		
		
		// other sanity checks
		if( empty( $FirstName ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_FirstName"];
		}
		else if( empty( $LastName ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_LastName"]; 
		}
		else if( empty( $Phone ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Phone"]; 
		} 
		else if( empty( $Email ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Email"]; 
		} 
		else if( empty( $Password ) || empty( $ConfirmPassword ) ){
			$ErrMsg = "Password fields cannot be blank.";
		}
		else if( $Password !== $ConfirmPassword ){
			$ErrMsg = "Password does not match";
		}
		// bail
		if( ! empty( $ErrMsg ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// good to go!
		

		$FirstName = mysql_real_escape_string( utf8_decode( $FirstName ) );
		$LastName = mysql_real_escape_string( utf8_decode( $LastName ) );
		$Title = mysql_real_escape_string( utf8_decode( $Title ) );
		$Email = mysql_real_escape_string( utf8_decode( $Email ) );
		$Phone = mysql_real_escape_string( utf8_decode( $Phone ) );
		$AltPhone = mysql_real_escape_string( utf8_decode( $AltPhone ) );
		$Password = mysql_real_escape_string( utf8_decode( $Password ) );
		$Type = mysql_real_escape_string( utf8_decode( $Type ) );

		// handle profile pic (if it was uploaded)
		if( "true" == $ProfilePicUploaded ){
			$uploadedFileName = $_SESSION['User']['UploadPath'] . "/upload";
			$mimeType = "";
			$fileSize = 0;

			if( file_exists( $uploadedFileName ) ){
				// get mime type
				$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadedFileName );

				// get file size
				$fileSize = filesize( $uploadedFileName );
			}
			//
			$Sql = "INSERT INTO Files (Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES (0,{$_SESSION['User']['Id']},'profilePhoto','$mimeType',$fileSize,NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header("Status: 400 Query Error " . __LINE__ );
				exit;
			}
			$ProfilePhotoFile_Id = mysql_insert_id();
			
			// rename upload to real file name.
			$finalFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $ProfilePhotoFile_Id;
			if( file_exists( $uploadedFileName ) ){
				rename( $uploadedFileName, $finalFileName );
			}
		}
		
		

		$Sql = "INSERT INTO User (FirstName,LastName,Position,Email";
		if( isset( $ProfilePhotoFile_Id ) )
			$Sql.= ",ProfilePhotoFile_Id";
		$Sql .= ",Phone,AltPhone,Password,Account_Id,CreateDate,Type,Active) VALUES( ";
		$Sql .= "'$FirstName','$LastName','$Title','$Email'";
		if( isset( $ProfilePhotoFile_Id ) )
			$Sql.= ",$ProfilePhotoFile_Id";
		$Sql.= ",'$Phone','$AltPhone',";
		$Sql .= "md5('$Password'),'{$_SESSION['User']['AccountId']}',NOW(),'$Type',1)";

		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			header("Status: 400 Query Error. " . __LINE__ );
			exit;
		}
		$newUserId = mysql_insert_id();

		// create a directory for this login ( mode 0760 drwxrw---- )
		mkdir( DATA_PATH . '/accounts/' . $_SESSION['User']['AccountId'] . '/logins/' . $newUserId, 0760 );
		
		// ----------------------------------------------------------------------------------------------------------------------------------
		// Update Notification Preferences
		//
		$numPrefs = 0;
		$Sql = "INSERT INTO NotificationPreferences (User_Id,Notification_Id,Notify,DeliveryMethod) VALUES ";
		foreach( $NotificationPreferences as $Notify ){
			// only save preference if a delivery method was specified.
			// if so, set notify to 1 anyways, because it *may* not have been checked
			// by the user.
			if( 0 != $Notify['D'] ) {
				$Notify['N'] = 1;
				$Sql .= '(' . $newUserId . ',' . $Notify['I'] . ',' . $Notify['N'] . ',' . $Notify['D'] . '),';
				$numPrefs ++;
			}
		}
		$Sql = rtrim( $Sql, "," );
		if( 0 < $numPrefs ){
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header("Status: 400 Query Error. " . __LINE__ . $Sql );
				exit;
			}	
		}
		// ----------------------------------------------------------------------------------------------------------------------------------

		$OkMsg = "New User Created.";
		header( "Content-Type: application/json" );
		print json_encode( array( "OkMsg" => $OkMsg ) );
		exit;
		

	} 
	else if ("ajaxUpdateUser" == $ajaxRequest) {

		//
		// if a LoadedUserId is present (not empty) - we are updating an existing user
		//
		if( ! VerifyCheckSum( $LoadedUserId, $LoadedUserIdCheckSum ) ){
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}

		// if the current logged in user is NOT the user id we are editing, you MUST be an admin.
		if( $_SESSION['User']['Id'] != $LoadedUserId && ! IsAdmin() ){
			header( "Status: 400 Insufficient Privileges. " . __LINE__ );
			exit;
		}
		// other sanity checks
		if( empty( $FirstName ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_FirstName"];
		}
		else if( empty( $LastName ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_LastName"]; 
		}
		else if( empty( $Phone ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Phone"]; 
		} 
		else if( empty( $Email ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Email"]; 
		} 
		else if( $Password !== $ConfirmPassword ){
				$ErrMsg = "Password does not match";
		}
		// bail
		if( ! empty( $ErrMsg ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		// good to go!
		db_connect();
		
		

	
		// handle profile pic (if it was uploaded)
		if( "true" == $ProfilePicUploaded ){
		
			$uploadedFileName = $_SESSION['User']['UploadPath'] . "/upload";
			$mimeType = "";
			$fileSize = 0;

			$Sql = "SELECT IFNULL( ProfilePhotoFile_Id, 0 ) FROM User WHERE Id = $LoadedUserId LIMIT 1";
			$result = mysql_query( $Sql );
			$row = mysql_fetch_row( $result );
			$ProfilePhotoFile_Id = $row[0];
			mysql_free_result( $result );

			if( file_exists( $uploadedFileName ) ){
				$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadedFileName );
				$fileSize = filesize( $uploadedFileName );
			}

			// no ProfilePhotoFile_Id yet, so we create it.
			if( "0" == $ProfilePhotoFile_Id ){
				$Sql = "INSERT INTO Files (Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES (0,{$_SESSION['User']['Id']},'profilePhoto','$mimeType',$fileSize,NOW())";
				mysql_query( $Sql );
				$ProfilePhotoFile_Id = mysql_insert_id();
				
			}
			else{
				$Sql = "UPDATE Files SET Type='$mimeType',Size=$fileSize,CreationDate=NOW() WHERE Id = $ProfilePhotoFile_Id AND User_Id = $LoadedUserId LIMIT 1";
				mysql_query( $Sql );
			}
			
			if( 0 != mysql_errno() ){
				header("Status: 400 Query Error $Sql " . __LINE__ );
				exit;
			}
			
			// rename upload to real file name.
			$finalFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $ProfilePhotoFile_Id;
			if( file_exists( $uploadedFileName ) ){
				rename( $uploadedFileName, $finalFileName );
			}
		}
			
		$FirstName = mysql_real_escape_string( utf8_decode( $FirstName ) );
		$LastName = mysql_real_escape_string( utf8_decode( $LastName ) );
		$Title = mysql_real_escape_string( utf8_decode( $Title ) );
		$Email = mysql_real_escape_string( utf8_decode( $Email ) );
		$Phone = mysql_real_escape_string( utf8_decode( $Phone ) );
		$AltPhone = mysql_real_escape_string( utf8_decode( $AltPhone ) );
		$Password = mysql_real_escape_string( utf8_decode( $Password ) );
		$Type = mysql_real_escape_string( utf8_decode( $Type ) );
		$Notification = mysql_real_escape_string( utf8_decode( $Notification ) );
		// update the loaded user record

		$Sql = "UPDATE User SET FirstName = '{$FirstName}',LastName = '{$LastName}',Position = '{$Title}',";
		$Sql.= "Email = '{$Email}',Phone = '{$Phone}',AltPhone = '{$AltPhone}', Type= '{$Type}' ";
		// update password if not empty
		if( ! empty( $Password ) )
			$Sql .= ",Password = md5('{$Password}') ";
		if( isset( $ProfilePhotoFile_Id ) )
			$Sql.= ",ProfilePhotoFile_Id=$ProfilePhotoFile_Id ";
		
		$Sql .= "WHERE Id = {$LoadedUserId} LIMIT 1";
		$OkMsg = "User Updated.";

		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			header("Status: 400 Query Error : " . __LINE__ );
			exit;
		}

		// ----------------------------------------------------------------------------------------------------------------------------------
		// Update Notification Preferences
		//
		// delete existing prefs
		mysql_query( "DELETE FROM NotificationPreferences WHERE User_Id = $LoadedUserId" );
		if( 0 != mysql_errno() ){
			header("Status: 400 Query Error. " . __LINE__ );
			exit;
		}

		$numPrefs = 0;
		$Sql = "INSERT INTO NotificationPreferences (User_Id,Notification_Id,Notify,DeliveryMethod) VALUES ";
		foreach( $NotificationPreferences as $Notify ){
			// only save preference if a delivery method was specified.
			// if so, set notify to 1 anyways, because it *may* not have been checked
			// by the user.
			if( 0 != $Notify['D'] ) {
				$Notify['N'] = 1;
				$Sql .= '(' . $LoadedUserId . ',' . $Notify['I'] . ',' . $Notify['N'] . ',' . $Notify['D'] . '),';
				$numPrefs ++;
			}
		}
		$Sql = rtrim( $Sql, "," );
		if( 0 < $numPrefs ){
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header("Status: 400 Query Error. " . __LINE__ . $Sql );
				exit;
			}	
		}
		// ----------------------------------------------------------------------------------------------------------------------------------
		
		
		
		header( "Content-Type: application/json" );
		print json_encode( array( "OkMsg" => $OkMsg ) );
		exit;
	}
	else if ("ajaxSaveBusinessEntries" == $ajaxRequest) {
			
		if( empty( $Name ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Company_Name"];
		}
		else if( empty( $Phone ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Company_Phone"]; 
		}
		else if( empty( $Address ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Address"]; 
		} 
		else if( empty( $Postal ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_Postal"]; 
		}
		else if( empty( $City ) ){
			$ErrMsg = $trans["phiw_my_account_ErrMsg_City"]; 
		}
		
		if( ! empty( $ErrMsg ) ) { 
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		db_connect();
		
		$Sql = " UPDATE Business SET ";
		$Sql .= " 	Name = '".mysql_real_escape_string(utf8_decode($Name))."', ";
		$Sql .= " 	Address = '".mysql_real_escape_string(utf8_decode($Address))."', ";
		$Sql .= " 	Postal = '".mysql_real_escape_string(utf8_decode($Postal))."', ";
		$Sql .= " 	City = '".mysql_real_escape_string(utf8_decode($City))."', ";
		$Sql .= " 	BillingAddress = '".mysql_real_escape_string(utf8_decode($BillingAddress))."', ";
		$Sql .= " 	BillingPostal = '".mysql_real_escape_string(utf8_decode($BillingPostal))."', ";
		$Sql .= " 	BillingCity = '".mysql_real_escape_string(utf8_decode($BillingCity))."', ";
		$Sql .= " 	Email = '".mysql_real_escape_string(utf8_decode($Email))."', ";
		$Sql .= " 	HomePage = '".mysql_real_escape_string(utf8_decode($HomePage))."' ";
		$Sql .= " WHERE Id = {$_SESSION['User']['Business_Id']} LIMIT 1";
		
		mysql_query( $Sql );
		
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		// update $_SESSION['Business'] to reflect new changes (if any)
		$result = mysql_query( "SELECT * FROM Business WHERE Id = {$_SESSION['User']['Business_Id']} LIMIT 1" );
		if( 0 == mysql_errno() ){
			unset( $_SESSION['Business'] );
			$_SESSION['Business'] = mysql_fetch_array( $result, MYSQL_ASSOC );
			mysql_free_result( $result );
			//session is updated.
		}
			
		$OkMsg = $trans["hiw_my_account_save_success_msg"];
		print( $OkMsg );
			
		exit;
	
	}
	else if( "getPreview" == $ajaxRequest ){
	
		// actual file on server
		$filepath = base64_encode( $_SESSION['User']['UploadPath'] . '/upload' );
		$fileUrl = "/libs/get_file.php?filepath=$filepath:".CheckSum( $filepath )."&disposition=inline&" . time();

		header( "Content-Type: application/json" );
		print( json_encode( array( "fileUrl" => $fileUrl ) ) );
		exit;
	}
	
	
	// -------------------------------------------------------------
	// First Load
	// This section is called during the first load of the document.
	// 
	// -------------------------------------------------------------
	
	
	$Business['Name'] = $_SESSION['Business']['Name'];
	$Business['Phone'] = $_SESSION['Business']['Phone'];
	$Business['Address'] = $_SESSION['Business']['Address'];
	$Business['Email'] = $_SESSION['Business']['Email'];
	$Business['Postal'] = $_SESSION['Business']['Postal'];
	$Business['City'] = $_SESSION['Business']['City'];
	$Business['HomePage'] = $_SESSION['Business']['HomePage'];
	$Business['BillingAddress'] = $_SESSION['Business']['BillingAddress'];
	$Business['BillingPostal'] = $_SESSION['Business']['BillingPostal'];
	$Business['BillingCity'] = $_SESSION['Business']['BillingCity'];
	
	$userListHTML = "";
	
	
	db_connect();
	
	$Sql  = "SELECT Id, Name, DATE(CreationDate) AS CreationDate, Type FROM Files F ";
	$Sql .= " WHERE F.Id IN ( ";
	$Sql .= "	SELECT A.FileId FROM ( ";
	$Sql .= "		SELECT ContractFile_Id as FileId FROM Accounts Where Id = {$_SESSION['User']['AccountId']} ";
	$Sql .= "		UNION ";
	$Sql .= "		SELECT RecordingFile_Id as FileId FROM Accounts Where Id = {$_SESSION['User']['AccountId']} ";
	$Sql .= "	) A )";
	
	$result = mysql_query($Sql);
	
	if( 0 != mysql_errno() ){
		header("Status: 400 Query Error " . __LINE__ );
		exit;
	}
	
	$contracts = array();
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$row['fileUrl'] = '/libs/get_file.php?file=' . $row['Id'] . ':' . CheckSum( $row['Id'] ) . '&disposition=inline';
		$contracts[] = $row;
	}	
	
	//SELECT F.*
	//FROM Accounts A INNER JOIN Files F ON (A.ContractFile_Id = F.Id OR A.RecordingFile_Id = F.Id)
	//WHERE A.Id = 3

		
	mysql_free_result( $result );
	
	include( "inc_my_account.html" ); 
	return;
?>
