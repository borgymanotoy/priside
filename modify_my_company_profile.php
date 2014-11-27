<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/public_verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/modify_my_company_profile.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	
	$ErrMsg = "";
	$OkMsg = "";
	
	// SETS THE CONTACT BUTTON
	$ContactButton = "";
	
	$UploadPath = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/logins/" . $_SESSION['User']['Id'];
	
	
	function GetComboboxItems($div_name, $span_name){

		$Sql = "SELECT Id,Title,CONCAT_WS( FirstName,' ',LastName) AS Name,IsPrimary FROM BusinessContacts WHERE Business_Id = {$_SESSION['Business']['Id']} LIMIT 200";
		$result = mysql_query( $Sql );
		
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			
			$list_html .= "<li id=\"org_".$row['Id']."\" value=\"".$row['Id']."\" ";
			$list_html .= "onclick=\"displaySelectedItem('".$div_name."', '".$span_name."', '".$row['Name']."');ajaxLoadSelectedContact( {$row['Id']},'".CheckSum( $row['Id'] )."' );\">".$row['Name'];
			if( 0 != $row['IsPrimary'] )
				$list_html .= "*";
			$list_html .="</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
	}
	
	
	// -------------------------------------------------------------
	// Ajax Request
	// -------------------------------------------------------------
	
	// ajaxSaveBusinessEntries
	// params : ajaxRequest=ajaxSaveBusinessEntries&Address=55+Tiongko+ave.&City=Davao+City&Postal=9000&BillingAddress=City+High+Ave.&BillingCity=Davao+City&BillingPostal=8000&Video=www.youtube.com&LinkedIn=www.linkedIn.com&Facebook=www.facebook.com&Twitter=www.twitter.com&Presentation=%3Cstrong%3E%3Cp%3ECamperskie+corporation%3C%2Fstrong%3E+provides+insight+into+underwriting%2C+marketing+and+purchasing+commercial+insurance.%3C%2Fp%3E%3Cp%3ECampers's+web-based+workstation+incorporates+real-time+analytics+and+research+on+over+15+million+entities+around+the+globe%2C+subsidiaries%2C+and+thousands+of+industries.%3C%2Fp%3E%3Cp%3ECamper+currently+serves+over+500+leading+commercial+insurers%2C+insurance+brokers%2C+risk+management+departments+of+major+corporations%2C+law+firms+and+other+related+organizations.%3C%2Fp%3E%3Cp%3EProprietary+offerings+of+the+Camper+service+include+Program%2FPolicy+Large+Loss+Data+Benchmarking%2C+Custom+Templates+for+Underwriting+Work-ups%2C+Company+and+Industry+Research%2C+Loss+%26+Risk+Analysis%2C+Management+Portfolio+Analysis+and+Policy+Wording+Comparison.+Headquartered+in+Davao+City%2C+Camper+was+established+in+2012+by+a+team+of+seasoned+insurance+and+technology+professionals.+Today%2C+Camper+is+backed+and+funded+by+some+of+the+commercial+insurance+industry's+most+recognized+and+respected+executives.%3C%2Fp%3E
	if( "ajaxSaveBusinessEntries" == $ajaxRequest ){
		
		// sanity checks for the params.
		if( empty( $Address ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_address"];
		}
		else if( empty( $City ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_location"]; 
		}
		else if( empty( $Postal ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_postal"]; 
		}

		if( ! empty( $ErrMsg ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		db_connect();
		
		
		// first check to see if a logo has already been uploaded.
		// if a logo is uploaded, it's Files.Id will be stored in the Bussiness.LogoFile_Id
		//
	
		$Sql = "SELECT IFNULL( LogoFile_Id, 0 ) FROM Business WHERE Id = {$_SESSION['Business']['Id']} LIMIT 1";
		$result = mysql_query( $Sql );
		$row = mysql_fetch_row( $result );
		$existingLogoId = $row[0];
		mysql_free_result( $result );

		
		$uploadedFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/logins/" . $_SESSION['User']['Id'] . "/upload";
		$mimeType = "";
		$fileSize = 0;

		if( file_exists( $uploadedFileName ) ){
			// get mime type
			$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadedFileName );

			// get file size
			$fileSize = filesize( $uploadedFileName );
		}
		
		// our Business record does NOT have a LogoFile_Id
		// therefor no Files.Id - in this case, we create the new File record
		if( "0" == $existingLogoId ){
			
			$Sql = "INSERT INTO Files(Parent_Id, User_Id, Name, Type, Size, CreationDate) VALUES(0, '".$_SESSION['User']['Id']."', '".mysql_real_escape_string( $file_name )."', '".$mimeType."', ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Query Error : " . __LINE__ . " SQL: " . $Sql );
				exit;
			}
			// the auto_increment value of the File.Id		
			$fileId = mysql_insert_id();
		}
		// there is already a LogoFile_Id
		// we will use this Id to update the Files table
		else {
		
			$fileId = $existingLogoId;

			$Sql = "UPDATE Files SET Name='".mysql_real_escape_string( $file_name )."', Type='".$mimeType."', Size=".$fileSize.", CreationDate = NOW() WHERE Id = ".$fileId." LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}
		// rename upload to real file name.
		$finalFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $fileId;
		if( file_exists( $uploadedFileName ) ){
			rename( $uploadedFileName, $finalFileName );
		}

		// Update Business table
		
		$Sql = " UPDATE Business SET ";
		$Sql .= " 	Address = '".mysql_real_escape_string(utf8_decode($Address))."', ";
		$Sql .= " 	City = '".mysql_real_escape_string(utf8_decode($City))."', ";
		$Sql .= " 	Postal = '".mysql_real_escape_string(utf8_decode($Postal))."', ";
		$Sql .= " 	BillingAddress = '".mysql_real_escape_string(utf8_decode($BillingAddress))."', ";
		$Sql .= " 	BillingPostal = '".mysql_real_escape_string(utf8_decode($BillingPostal))."', ";
		$Sql .= " 	BillingCity = '".mysql_real_escape_string(utf8_decode($BillingCity))."', ";
		$Sql .= " 	Video = '".mysql_real_escape_string(utf8_decode($Video))."', ";
		$Sql .= " 	LinkedIn = '".mysql_real_escape_string(utf8_decode($LinkedIn))."', ";
		$Sql .= " 	Facebook = '".mysql_real_escape_string(utf8_decode($Facebook))."', ";
		$Sql .= " 	Twitter = '".mysql_real_escape_string(utf8_decode($Twitter))."', ";
		$Sql .= " 	Presentation = '".mysql_real_escape_string(utf8_decode($Presentation))."', ";
		$Sql .= " 	HomePage = '".mysql_real_escape_string(utf8_decode($HomePage))."', ";
		$Sql .= " 	Email = '".mysql_real_escape_string(utf8_decode($Email))."', ";
		$Sql .= " 	LogoFile_id = '".$fileId."'";
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
			if( "0" !== $_SESSION['Business']['LogoFile_Id'] ){
				$Business['LogoUrl'] = "/libs/public_get_file.php?file=" . $_SESSION['Business']['LogoFile_Id'].":".PublicCheckSum( $_SESSION['Business']['LogoFile_Id'] ) . '&disposition=inline&' . time();
			}
			mysql_free_result( $result );
			//session is updated.
		}
			
		$OkMsg = $trans["hiw_modify_my_company_prof_save_success_msg"];
		print( $OkMsg );
		exit;
	} 
	// ajaxAddContactEntries
	// params : ajaxRequest=ajaxAddContactEntries&FirstName=Glynn&LastName=Barrogsment&Phone=7987654&Email=gmail%40g.com&Postal=8000&PersonNumber=65464645&City=davao+city&Title=manager&AltPhone=65464654&Address=address+davao&IsPrimary=0&LoadedContactId=&LoadedCheckSum=
	else if( "ajaxAddContactEntries" == $ajaxRequest ){
		
		if( ! empty( $LoadedContactId ) || ! empty( $LoadedCheckSum ) ){
			if( ! VerifyCheckSum( $LoadedContactId, $LoadedCheckSum ) ){
				header( "Status: 400 CheckSum Failed @: " . __LINE__ );
				exit;
			}
		}
		if( empty( $FirstName ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_FirstName"];
		}
		else if( empty( $LastName ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_LastName"]; 
		}
		else if( empty( $Phone ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_Phone"]; 
		} 
		else if( empty( $Email ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_Email"]; 
		}
		else if( empty( $PersonNumber ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_PersonNumber"]; 
		}
		else if( empty( $Postal ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_Postal"]; 
		}
		else if( empty( $City ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_City"]; 
		}
		else if( empty( $Address ) ){
			$ErrMsg = $trans["phiw_modify_my_company_prof_ErrMsg_Address"]; 
		}
		
		
		if( ! empty( $ErrMsg ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		$FirstName = utf8_decode($FirstName);
		$LastName = utf8_decode($LastName);
		$Phone = utf8_decode($Phone);
		$Email = utf8_decode($Email);
		$PersonNumber = utf8_decode($PersonNumber);
		$Postal = utf8_decode($Postal);
		$City = utf8_decode($City);
		$Title = utf8_decode($Title);
		$AltPhone = utf8_decode($AltPhone);
		$Address = utf8_decode($Address);
		
		db_connect();
		
		$FirstName = mysql_real_escape_string($FirstName);
		$LastName = mysql_real_escape_string($LastName);
		$Phone = mysql_real_escape_string($Phone);
		$Email = mysql_real_escape_string($Email);
		$PersonNumber = mysql_real_escape_string($PersonNumber);
		$Postal = mysql_real_escape_string($Postal);
		$City = mysql_real_escape_string($City);
		$Title = mysql_real_escape_string($Title);
		$AltPhone = mysql_real_escape_string($AltPhone);
		$Address = mysql_real_escape_string($Address);
			
		// if we are setting a new primary contact,
		// we must clear the old (if existing) primary
		if( "1" == $IsPrimary ){
			$Sql = "UPDATE BusinessContacts SET IsPrimary = 0 WHERE IsPrimary = 1 AND Business_Id = {$_SESSION['User']['Business_Id']} LIMIT 1";
			mysql_query( $Sql );
		}
		
		
		if( empty( $LoadedContactId ) || empty( $LoadedCheckSum ) ) {
			$Sql = "INSERT INTO  BusinessContacts (IsPrimary, FirstName, LastName, Phone, Email, PersonNumber, Postal, City, Title, AltPhone, Address, Business_Id) VALUES ($IsPrimary, '".$FirstName."', '".$LastName."', '".$Phone."', '".$Email."', '".$PersonNumber."', '".$Postal."', '".$City."', '".$Title."', '".$AltPhone."', '".$Address."', '{$_SESSION['User']['Business_Id']}')";
		} 
		else {
			$Sql = " UPDATE BusinessContacts SET IsPrimary = $IsPrimary, FirstName = '".$FirstName."', Phone = '".$Phone."', Email = '".$Email."', PersonNumber = '".$PersonNumber."', Postal = '".$Postal."', AltPhone = '".$AltPhone."', Address = '".$Address."', Business_Id = '{$_SESSION['User']['Business_Id']}', City = '".$City."', Title = '".$Title."' WHERE Id = {$LoadedContactId} LIMIT 1"; 
		}
		
		
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		
		$OkMsg = $trans["hiw_modify_my_company_prof_save_success_msg"];
		
		$data = array( "OkMsg"=>$OkMsg
					  ,"UpdateContactButton"=>$trans["modify_my_company_prof_add_contact"]
		);
		
		header( "Content-Type: application/json" );
		print json_encode( $data );
		exit;
		
	}
	//  ajaxReloadContactsList
	//	params : ajaxRequest=ajaxReloadContactsList
	//  Notes: 
	//  reload combo box
	else if( "ajaxReloadContactsList" == $ajaxRequest ){
			db_connect();
			$contactsListHTML = GetComboboxItems("div_contact", "spn_contact" );
			print $contactsListHTML;
			exit;
	}
	// ajaxLoadSelectedContact
	// params: ajaxRequest=ajaxLoadSelectedContact&Contact_Id=3&CheckSum=c22470f7c9bccc01ffbecbfc649b7f94
	// Notes:
	// Output Type : JSON
	else if( "ajaxLoadSelectedContact" == $ajaxRequest ){
		
		if( ! VerifyCheckSum( $Contact_Id, $CheckSum ) ){
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}
		
		db_connect();
		
		// create query
		$Sql =  "SELECT FirstName ";
		$Sql .= "       ,LastName ";
		$Sql .= "       ,Title ";
		$Sql .= "       ,Phone ";
		$Sql .= "       ,Email ";
		$Sql .= "       ,AltPhone ";
		$Sql .= "       ,PersonNumber ";
		$Sql .= "       ,Address ";
		$Sql .= "       ,Postal ";
		$Sql .= "       ,City ";
		$Sql .= "       ,Id ";
		$Sql .= "       ,IsPrimary ";
		$Sql .= "  FROM BusinessContacts ";
		$Sql .= " WHERE Id = {$Contact_Id} LIMIT 1";
		
		$result = mysql_query( $Sql );
		
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @: " . __LINE__ . " : " . mysql_error();
			header( "Status: 400 " . $ErrMsg );
			exit;
		}
		
		if( 0 == mysql_num_rows( $result ) ){
			// no contacts to display.
			print "No Contacts Found";
			exit;
		}
		
		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		
		// set ContactButton
		
		$data = Array( "FirstName"=>$row["FirstName"]
					  ,"LastName"=>$row["LastName"]
					  ,"Title"=>$row["Title"]
					  ,"Phone"=>$row["Phone"]
					  ,"Email"=>$row["Email"]
					  ,"AltPhone"=>$row["AltPhone"]
					  ,"PersonNumber"=>$row["PersonNumber"]
					  ,"Address"=>$row["Address"]
					  ,"Postal"=>$row["Postal"]
					  ,"City"=>$row["City"]
					  ,"IsPrimary"=>$row["IsPrimary"]
					  ,"ContactId"=>$Contact_Id //update hidden field
					  ,"CheckSum"=>$CheckSum	//update hidden field
					  ,"UpdateContactButton"=>$trans["modify_my_company_prof_save_contact"]
		);
		
		mysql_free_result( $result );
		
		header( "Content-Type: application/json" );
		print json_encode( $data );
		exit;
		
	}
	// This section is called During file Upload
	//ajaxRequest=getPreview&filename=Tulips.jpg
	else if( "getPreview" == $ajaxRequest ){
		// actual file on server
		$filepath = base64_encode( DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/logins/" . $_SESSION['User']['Id'] . "/upload" );
		$fileUrl = "/libs/get_file.php?filepath={$filepath}:".CheckSum( $filepath )."&disposition=inline&" . time();

		header( "Content-Type: application/json" );
		print( json_encode( array( "fileUrl" => $fileUrl ) ) );
		exit;
	}
	
	
	
	
	// -------------------------------------------------------------
	// First Load
	// This section is called during the first load of the document.
	// Business table is already loaded in the session variable
	// -------------------------------------------------------------
	
	
	$Business['Address'] = $_SESSION['Business']['Address'];
	$Business['Postal'] = $_SESSION['Business']['Postal'];
	$Business['City'] = $_SESSION['Business']['City'];
	$Business['BillingAddress'] = $_SESSION['Business']['BillingAddress'];
	$Business['BillingPostal'] = $_SESSION['Business']['BillingPostal'];
	$Business['BillingCity'] = $_SESSION['Business']['BillingCity'];
	$Business['HomePage'] = $_SESSION['Business']['HomePage'];
	$Business['Email'] = $_SESSION['Business']['Email'];
	$Business['Facebook'] = $_SESSION['Business']['Facebook'];
	$Business['Twitter'] = $_SESSION['Business']['Twitter'];
	$Business['LinkedIn'] = $_SESSION['Business']['LinkedIn'];
	$Business['Video'] = $_SESSION['Business']['Video'];
	$Business['Presentation'] = $_SESSION['Business']['Presentation'];
	if( isset( $_SESSION['Business']['LogoFile_Id'] ) ){
		$Business['LogoUrl'] = "/libs/public_get_file.php?file=" . $_SESSION['Business']['LogoFile_Id'].":".PublicCheckSum( $_SESSION['Business']['LogoFile_Id'] ) . '&disposition=inline&' . time();
	}
	//db_connect();
	$contactsListHTML = '';//GetComboboxItems("div_contact", "spn_contact" );
	
	include( "inc_modify_my_company_profile.html" ); 
	return;
?>

