<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/public_verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_cyb.php" );

	// db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";

	$UploadPath = $_SESSION['User']['UploadPath'];

	//ajax request
	if( "ajaxSaveCYB" == $ajaxRequest ){
		idCheckSumSplit( $CybIdChecksum, $CybId, $CybChecksum );
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		idCheckSumSplit( $PricelistIdChecksum, $PricelistId, $PricelistChecksum );
		idCheckSumSplit( $AgreementIdChecksum, $AgreementId, $AgreementChecksum );

		//Verify Ids and Checksums
		if( ! empty( $CybId ) && ! empty( $CybChecksum ) && ! VerifyCheckSum( $CybId, $CybChecksum ) ){
			$ErrMsg = "Connect your business CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Image CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		if( ! empty( $PricelistId ) && ! empty( $PricelistChecksum ) && ! VerifyCheckSum( $PricelistId, $PricelistChecksum ) ){
			$ErrMsg = "Pricelist File CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		if( ! empty( $AgreementId ) && ! empty( $AgreementChecksum ) && ! VerifyCheckSum( $AgreementId, $AgreementChecksum ) ){
			$ErrMsg = "Agreement File CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $BannerId ) )  $BannerId = 0;
		if( empty( $PricelistId ) )  $PricelistId = 0;
		if( empty( $AgreementId ) )  $AgreementId = 0;
		else if( empty( $ApplicationContents ) ){
			$ErrMsg = $trans["pcyb__ErrMsg_ApplicationContents"]; //"ApplicationContents cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		// --------------------------------------------------------------

		db_connect();
		$ApplicationContents = mysql_real_escape_string( utf8_decode($ApplicationContents) );

		//If cyb id is empty, create new cyb record
		if( empty( $CybId ) ){
			$Sql = "INSERT INTO PresideConnectYourCompany (ApplicationContents, CompanyBannerFile_Id, PriceListFile_Id, AgreementFile_Id, DateCreated, LastModified) VALUES ('".$ApplicationContents."', $BannerId, $PricelistId, $AgreementId, now(), now())";
			$OkMsg = $trans["cyb_save_success_msg"];
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Saving Connect your business Info Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$CybId = mysql_insert_id();
		}
		//If cyb id is not empty, just update cyb record
		else{
			//update sql (only if consumer data already exists)
			$Sql  = " UPDATE PresideConnectYourCompany SET ";
			$Sql .= " 	ApplicationContents = '".$ApplicationContents."', ";
			$Sql .= " 	CompanyBannerFile_Id = $BannerId,";
			$Sql .= " 	PriceListFile_Id = $PricelistId,";
			$Sql .= " 	AgreementFile_Id = $AgreementId,";
			$Sql .= " 	LastModified = now()";
			$Sql .= "  WHERE Id = $CybId LIMIT 1";
			$OkMsg = $trans["cyb_update_success_msg"];
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Updating Connect your business Info Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
		}

		//If saving or updating is successfull, display success message in CYB page.
		$CybInfos = array( "CybId" =>  $CybId, "CybChecksum" => CheckSum(  $CybId ), "CybStatusMsg" => $OkMsg );

		//Done
		print( json_encode( $CybInfos ) );
		exit;
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
			$ErrMsg = $trans["pcyb__ErrMsg_filename"]; //"Image Filename cannot be blank.";
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
		mysql_query( "BEGIN" );

		//If banner id is blank, insert new image file
		if( empty( $BannerId )){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].",\"".mysql_real_escape_string( $filename )."\", \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
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
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		mysql_query( "COMMIT" );

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
	else if("setUploadedPriceList" == $ajaxRequest){

		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $PricelistIdChecksum, $PricelistId, $PricelistChecksum );
		if( ! empty( $PricelistId ) && ! empty( $PricelistChecksum ) && ! VerifyCheckSum( $PricelistId, $PricelistChecksum ) ){
			$ErrMsg = "Pricelist File CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		if( empty( $filename )){
			$ErrMsg = $trans["pcyb__ErrMsg_filename"]; //"Image Filename cannot be blank.";
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
		mysql_query( "BEGIN" );

		//If banner id is blank, insert new image file
		if( empty( $PricelistId )){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].",\"".mysql_real_escape_string( $filename )."\", \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Insert Statement Error : " . __LINE__ );
				exit;
			}
			$PricelistId = mysql_insert_id();
		}
		//If pricelist file id is not blank, just update the file record
		else{
			$Sql = "UPDATE Files SET Name=\"".mysql_real_escape_string( $filename )."\",Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $PricelistId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		mysql_query( "COMMIT" );

		//After inserting or updating the file record, overwrite the old file with "upload" file.
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$PricelistId";
		rename( $uploadFileName, $newFileName );

		//Return the file infos via JSON (File Id, File Checksum, File Name, and File Url)
		$fileUrl = "/libs/public_get_file.php?file=$PricelistId:" . PublicCheckSum( $PricelistId ) . "&disposition=attachment&" . time();
		$fileInfos = array( "PricelistId" =>  $PricelistId, "PricelistChecksum" => CheckSum(  $PricelistId ), "PricelistName" => $filename, "PricelistUrl" => $fileUrl );

		//Done
		print( json_encode( $fileInfos ) );
		exit;
	}
	else if("setUploadedAgreement" == $ajaxRequest){

		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $AgreementIdChecksum, $AgreementId, $AgreementChecksum );
		if( ! empty( $AgreementId ) && ! empty( $AgreementChecksum ) && ! VerifyCheckSum( $AgreementId, $AgreementChecksum ) ){
			$ErrMsg = "Agreement File CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		if( empty( $filename )){
			$ErrMsg = $trans["pcyb__ErrMsg_filename"]; //"Image Filename cannot be blank.";
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
		mysql_query( "BEGIN" );
		
		//If banner id is blank, insert new image file
		if( empty( $AgreementId )){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].",\"".mysql_real_escape_string( $filename )."\", \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Insert Statement Error : " . __LINE__ );
				exit;
			}
			$AgreementId = mysql_insert_id();
		}
		//If pricelist file id is not blank, just update the file record
		else{
			$Sql = "UPDATE Files SET Name=\"".mysql_real_escape_string( $filename )."\",Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $AgreementId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		mysql_query( "COMMIT" );

		//After inserting or updating the file record, overwrite the old file with "upload" file.
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$AgreementId";
		rename( $uploadFileName, $newFileName );

		//Return the file infos via JSON (File Id, File Checksum, File Name, and File Url)
		$fileUrl = "/libs/public_get_file.php?file=$AgreementId:" . PublicCheckSum( $AgreementId ) . "&disposition=attachment&" . time();
		$fileInfos = array( "AgreementId" =>  $AgreementId, "AgreementChecksum" => CheckSum(  $AgreementId ), "AgreementName" => $filename, "AgreementUrl" => $fileUrl );

		//Done
		print( json_encode( $fileInfos ) );
		exit;
	}
	//---------------------------------------------------------------------------------------------------------------
	// there is no ajax action ... so we let the page load

	$CYBImageUrl = "/img/transparent_logo.png";

	$CYBPriceListUrl = '';
	$CYBAgreementFileUrl = '';

	// connect to db
	db_connect();

	//select all columns for consumer (enum value 1) 
	$Sql  = " SELECT ";
	$Sql .= "   p.Id, p.ApplicationContents, ";
	$Sql .= "   p.CompanyBannerFile_Id, bf.Name as BannerFileName, ";
	$Sql .= "   p.PriceListFile_Id, plf.Name as PricelistName, ";
	$Sql .= "   p.AgreementFile_Id, af.Name as AgreementFileName ";
	$Sql .= " FROM PresideConnectYourCompany p ";
	$Sql .= " left join Files bf on bf.Id =  p.CompanyBannerFile_Id";
	$Sql .= " left join Files plf on plf.Id =  p.PriceListFile_Id ";
	$Sql .= " left join Files af on af.Id =  p.AgreementFile_Id ";
	$Sql .= " LIMIT 1 ";

	$result = mysql_query( $Sql );
	if( 0 != mysql_errno() ){
		$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
		include( "inc_admin_panel_pages_cyb.html" );
		return;
	}
	else if( 0 == mysql_num_rows( $result ) ){
		include( "inc_admin_panel_pages_cyb.html" );
		return;
	}
	$CYBDefaults = mysql_fetch_array( $result, MYSQL_ASSOC );
	mysql_free_result( $result );

	//Set values for fallthrough page loading (Banner Image, Pricelist, Agreement file, Checksums, etc.)
	$CYBDefaults['CybChecksum'] = CheckSum( $CYBDefaults['Id'] );

	if( ! empty( $CYBDefaults['CompanyBannerFile_Id'] ) ){
		$CYBDefaults['CompanyBannerFile_Checksum'] = CheckSum( $CYBDefaults['CompanyBannerFile_Id'] );
		$CYBImageUrl = '/libs/get_file.php?file=' . $CYBDefaults['CompanyBannerFile_Id'] . ':' . CheckSum( $CYBDefaults['CompanyBannerFile_Id'] ) . '&disposition=inline&' . time();
	}
	if( ! empty( $CYBDefaults['PriceListFile_Id'] ) ){
		$CYBDefaults['PriceListFile_Checksum'] = CheckSum( $CYBDefaults['PriceListFile_Id'] );
		$CYBPriceListUrl = '/libs/get_file.php?file=' . $CYBDefaults['PriceListFile_Id'] . ':' . CheckSum( $CYBDefaults['PriceListFile_Id'] ) . '&disposition=inline&' . time();
	}
	if( ! empty( $CYBDefaults['AgreementFile_Id'] ) ){
		$CYBDefaults['AgreementFile_Checksum'] = CheckSum( $CYBDefaults['AgreementFile_Id'] );
		$CYBAgreementFileUrl = '/libs/get_file.php?file=' . $CYBDefaults['AgreementFile_Id'] . ':' . CheckSum( $CYBDefaults['AgreementFile_Id'] ) . '&disposition=inline&' . time();
	}

	include( "inc_admin_panel_pages_cyb.html" );
	return;
?>