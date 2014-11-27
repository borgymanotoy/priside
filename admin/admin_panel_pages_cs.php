<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/public_verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );

	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_cs.php" );

	// db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	
	$ErrMsg = "";
	$OkMsg = "";

	$UploadPath = $_SESSION['User']['UploadPath'];

	//ajax request
	if( "ajaxSaveCS" == $ajaxRequest ){
		//$CSIdChecksum  = $CSId and $CSChecksum
		idCheckSumSplit( $CSIdChecksum, $CSId, $CSChecksum );
		if( ! empty( $CSId ) && ! empty( $CSChecksum ) && ! VerifyCheckSum( $CSId, $CSChecksum ) ){
			$ErrMsg = "Customer Service Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Image CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $BannerId ) ) $BannerId = 0;
		else if( empty( $CSText ) ){
			$ErrMsg = $trans["pcs_ErrMsg_CSText"]; //"CSText cannot be blank.";
		}
		else if( empty( $CSPhone ) ){
			$ErrMsg = $trans["pcs_ErrMsg_CSPhone"]; //"CSPhone cannot be blank.";
		}
		else if( empty( $CSVisitingAddress ) ){
			$ErrMsg = $trans["pcs_ErrMsg_CSVisitingAddress"]; //"CSVisitingAddress cannot be blank.";
		}
		else if( empty( $CSEmail ) ){
			$ErrMsg = $trans["pcs_ErrMsg_CSEmail"]; //"CSEmail cannot be blank.";
		}
		else if( empty( $CSHoursOfOperation ) ){
			$ErrMsg = $trans["pcs_ErrMsg_CSHoursOfOperation"]; //"CSHoursOfOperation cannot be blank.";
		}

		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		// --------------------------------------------------------------

		db_connect();

		// escape the text to keep MySQL happy :)
		$CSText = mysql_real_escape_string( utf8_decode($CSText) );
		$CSPhone = mysql_real_escape_string( utf8_decode($CSPhone) );
		$CSVisitingAddress = mysql_real_escape_string( utf8_decode($CSVisitingAddress) );
		$CSEmail = mysql_real_escape_string( utf8_decode($CSEmail) );
		$CSHoursOfOperation = mysql_real_escape_string( utf8_decode($CSHoursOfOperation) );

		//--------------------------------------------------------------------------------------------
		// Insert or Update data
		//----------------------
		if( empty( $CSId ) ){
			// No consumer data
			// create sql (If there are still no data for consumer then add one)
			$Sql = "INSERT INTO PresidePage_CustomerService (CSText, CSPhone, CSVisitingAddress, CSEmail, CSHoursOfOperation, CSImageFile_Id, DateCreated, LastModified) VALUES ('".$CSText."', '".$CSPhone."', '".$CSVisitingAddress."', '".$CSEmail."', '".$CSHoursOfOperation."', $BannerId, now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$CSId = mysql_insert_id();
		} else {
			//update sql (only if consumer data already exists)
			$Sql  = " UPDATE PresidePage_CustomerService SET ";
			$Sql .= " 	CSText = '".$CSText."', ";
			$Sql .= " 	CSPhone = '".$CSPhone."', ";
			$Sql .= " 	CSVisitingAddress = '".$CSVisitingAddress."', ";
			$Sql .= " 	CSEmail = '".$CSEmail."', ";
			$Sql .= " 	CSHoursOfOperation = '".$CSHoursOfOperation."', ";
			$Sql .= " 	CSImageFile_Id = $BannerId, ";
			$Sql .= " 	LastModified = now()";
			$Sql .= " LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
		}

		//If saving or updating is successfull, display success message in HIW Consumer page.
		$CSInfos = array( "CSId" =>  $CSId, "CSChecksum" => CheckSum(  $CSId ), "CSStatusMsg" => $trans["cs_save_success_msg"] );

		print( json_encode( $CSInfos ) );
		exit;
		//Done
		//--------------------------------------------------------------------------------------------
	}
	else if("saveBannerImage" == $ajaxRequest){
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Image CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		if( empty( $filename )){
			$ErrMsg = $trans["pcs_ErrMsg_NoBanner"]; //"Image Filename cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// actual file on server
		$uploadFileName = $UploadPath . "/upload";

		// get mime type
		$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadFileName );

		// get file size
		$fileSize = filesize( $uploadFileName );

		db_connect();

		//If banner id is blank, insert new image file
		if( empty( $BannerId )){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].",\"".mysql_real_escape_string( $filename )."\", \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Insert Statement Error : " . __LINE__ );
				exit;
			}
			$BannerId = mysql_insert_id();
		}
		//If banner id is not blank, just update the file record
		else{
			$Sql = "UPDATE Files SET Name=\"".mysql_real_escape_string( $filename )."\",Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BannerId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		//After inserting or updating the file record, overwrite the old file with "upload" file.
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$BannerId";
		rename( $uploadFileName, $newFileName );

		//Return the file infos via JSON (File Id, File Checksum, File Name, and File Url)
		$fileUrl = "/libs/public_get_file.php?file=$BannerId:" . PublicCheckSum( $BannerId ) . "&disposition=inline&" . time();
		$fileInfos = array( "FileId" =>  $BannerId, "FileChecksum" => CheckSum(  $BannerId ), "FileName" => $filename, "FileUrl" => $fileUrl );

		//Done
		print( json_encode( $fileInfos ) );
		exit;
	}

	//---------------------------------------------------------------------------------------------------------------
	// there is no ajax action ... so we let the page load

	$CSImageUrl =  "/img/transparent_logo.png";

	db_connect();

	$Sql  = " SELECT ";
	$Sql .= "     cs.Id as CSId, ";
	$Sql .= "     cs.CSImageFile_Id, ";
	$Sql .= "     bf.Name as CSBannerFileName, ";
	$Sql .= "     cs.CSText, ";
	$Sql .= "     cs.CSPhone, ";
	$Sql .= "     cs.CSVisitingAddress, ";
	$Sql .= "     cs.CSEmail, ";
	$Sql .= "     cs.CSHoursOfOperation ";
	$Sql .= " FROM PresidePage_CustomerService  cs ";
	$Sql .= " left join Files bf on bf.Id = cs.CSImageFile_Id ";
	$Sql .= " LIMIT 1 ";

	$result = mysql_query( $Sql );
	if( 0 != mysql_errno() ){
		$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
		include( "inc_admin_panel_pages_cs.html" );
		return;
	}
	else if( 0 == mysql_num_rows( $result ) ){
		include( "inc_admin_panel_pages_cs.html" );
		return;
	}

	$CSDefaults = mysql_fetch_array( $result, MYSQL_ASSOC );
	mysql_free_result( $result );

	if( ! empty( $CSDefaults['CSImageFile_Id'] ) ){
		$CSImageUrl = '/libs/get_file.php?file=' . $CSDefaults['CSImageFile_Id'] . ':' . CheckSum( $CSDefaults['CSImageFile_Id'] ) . '&disposition=inline&' . time();
	}

	include( "inc_admin_panel_pages_cs.html" );
	return;
?>