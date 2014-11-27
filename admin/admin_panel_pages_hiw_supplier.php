<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/public_verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_hiw_supplier.php" );

	// db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	
	$ErrMsg = "";
	$OkMsg = "";

	$UploadPath = $_SESSION['User']['UploadPath'];

	//ajax request
	if( "ajaxSaveHIWSupplier" == $ajaxRequest ){
		//$HIWSupplierIdChecksum  = $HIWSupplierId and $HIWSupplierChecksum
		idCheckSumSplit( $HIWSupplierIdChecksum, $HIWSupplierId, $HIWSupplierChecksum );
		if( ! empty( $HIWSupplierId ) && ! empty( $HIWSupplierChecksum ) && ! VerifyCheckSum( $HIWSupplierId, $HIWSupplierChecksum ) ){
			$ErrMsg = "HIW Supplier CheckSum Error @ " . __LINE__ ;
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
		//$BrochureIdChecksum  = $BrochureId and $BrochureChecksum
		idCheckSumSplit( $BrochureIdChecksum, $BrochureId, $BrochureChecksum );
		if( ! empty( $BrochureId ) && ! empty( $BrochureChecksum ) && ! VerifyCheckSum( $BrochureId, $BrochureChecksum ) ){
			$ErrMsg = "Brochure File CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $BannerId ) ) $BannerId = 0;
		else if( empty( $BrochureId ) ) $BrochureId = 0;
		else if( empty( $SubHeading1 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeading1"]; //"SubHeading1 cannot be blank.";
		}
		else if( empty( $SubHeadingText1 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeadingText1"]; //"SubHeadingText1 cannot be blank.";
		}
		else if( empty( $SubHeading2 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeading2"]; //"SubHeading2 cannot be blank.";
		}
		else if( empty( $SubHeadingText2 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeadingText2"]; //"SubHeadingText2 cannot be blank.";
		}
		else if( empty( $SubHeading3 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeading3"]; //"SubHeading3 cannot be blank.";
		}
		else if( empty( $SubHeadingText3 ) ){
			$ErrMsg = $trans["phiw_supplier_ErrMsg_SupplierSubHeadingText3"]; //"SubHeadingText3 cannot be blank.";
		}

		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		// --------------------------------------------------------------

		db_connect();

		// escape the text to keep MySQL happy :)
		$SubHeading1 = mysql_real_escape_string( utf8_decode($SubHeading1) );
		$SubHeadingText1 = mysql_real_escape_string( utf8_decode($SubHeadingText1) );

		$SubHeading2 = mysql_real_escape_string( utf8_decode($SubHeading2) );
		$SubHeadingText2 = mysql_real_escape_string( utf8_decode($SubHeadingText2) );

		$SubHeading3 = mysql_real_escape_string( utf8_decode($SubHeading3) );
		$SubHeadingText3 = mysql_real_escape_string( utf8_decode($SubHeadingText3) );

		//--------------------------------------------------------------------------------------------
		// Insert or Update data
		//----------------------
		if( empty( $HIWSupplierId ) ){
			// No supplier data
			// create sql (If there are still no data for supplier then add one)
			$Sql = "INSERT INTO PresideHIW (SubHeading1, SubHeadingText1, SubHeading2, SubHeadingText2, SubHeading3, SubHeadingText3, PageType, BannerFile_Id, BrochureFile_Id, DateCreated, LastModified) VALUES ('".$SubHeading1."', '".$SubHeadingText1."', '".$SubHeading2."', '".$SubHeadingText2."', '".$SubHeading3."', '".$SubHeadingText3."', 2, $BannerId, $BrochureId, now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$HIWSupplierId = mysql_insert_id();
		} else {
			//update sql (only if supplier data already exists)
			$Sql = " UPDATE PresideHIW SET ";
			$Sql .= " 	SubHeading1 = '".$SubHeading1."', ";
			$Sql .= " 	SubHeadingText1 = '".$SubHeadingText1."', ";
			$Sql .= " 	SubHeading2 = '".$SubHeading2."', ";
			$Sql .= " 	SubHeadingText2 = '".$SubHeadingText2."', ";
			$Sql .= " 	SubHeading3 = '".$SubHeading3."', ";
			$Sql .= " 	SubHeadingText3 = '".$SubHeadingText3."', ";
			$Sql .= " 	BannerFile_Id = $BannerId, ";
			$Sql .= " 	BrochureFile_Id = $BrochureId, ";
			$Sql .= " 	LastModified = now() ";
			$Sql .= " WHERE PageType = 2 LIMIT 1"; //PageType (2) = Supplier

			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
		}

		//If saving or updating is successfull, display success message in HIW supplier page.
		$HIWSupplierInfos = array( "HIWSupplierId" =>  $HIWSupplierId, "HIWSupplierChecksum" => CheckSum(  $HIWSupplierId ), "HIWSupplierStatusMsg" => $trans["hiw_supplier_save_success_msg"] );

		//Done
		print( json_encode( $HIWSupplierInfos ) );
		exit;
		// done.
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
			$ErrMsg = $trans["phiw_supplier_ErrMsg_NoBanner"]; //"Image Filename cannot be blank.";
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
	else if("setUploadedBrochure" == $ajaxRequest){
		//$BrochureIdChecksum  = $BrochureId and $BrochureChecksum
		idCheckSumSplit( $BrochureIdChecksum, $BrochureId, $BrochureChecksum );
		if( ! empty( $BrochureId ) && ! empty( $BrochureChecksum ) && ! VerifyCheckSum( $BrochureId, $BrochureChecksum ) ){
			$ErrMsg = "Brochure File CheckSum Error @ " . __LINE__ ;
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

		//If banner id is blank, insert new image file
		if( empty( $BrochureId )){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].",\"".mysql_real_escape_string( $filename )."\", \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Insert Statement Error : " . __LINE__ );
				exit;
			}
			$BrochureId = mysql_insert_id();
		}
		//If pricelist file id is not blank, just update the file record
		else{
			$Sql = "UPDATE Files SET Name=\"".mysql_real_escape_string( $filename )."\",Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BrochureId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		//After inserting or updating the file record, overwrite the old file with "upload" file.
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$BrochureId";
		rename( $uploadFileName, $newFileName );

		//Return the file infos via JSON (File Id, File Checksum, File Name, and File Url)
		$fileUrl = "/libs/public_get_file.php?file=$BrochureId:" . PublicCheckSum( $BrochureId ) . "&disposition=inline&" . time();
		$fileInfos = array( "BrochureId" =>  $BrochureId, "BrochureChecksum" => CheckSum(  $BrochureId ), "BrochureName" => $filename, "BrochureUrl" => $fileUrl );

		//Done
		print( json_encode( $fileInfos ) );
		exit;
	}
	//---------------------------------------------------------------------------------------------------------------
	// there is no ajax action ... so we let the page load

	$HIWImageUrl =  "/img/transparent_logo.png";
	$HIWBrochureUrl = '';

	// connect to db
	db_connect();

	//select all columns for supplier (enum value 2)
	$Sql .= " SELECT  ";
	$Sql .= " 	hiw.Id as HIWSupplierId,  ";
	$Sql .= "     hiw.BannerFile_Id as HIWSupplierBannerFile_Id, ";
	$Sql .= "     bf.Name as HIWSupplierBannerFileName, ";
	$Sql .= "     hiw.SubHeading1 as HIWSupplierSH1,  ";
	$Sql .= "     hiw.SubHeadingText1 as HIWSupplierSHText1,  ";
	$Sql .= "     hiw.SubHeading2 as HIWSupplierSH2,  ";
	$Sql .= "     hiw.SubHeadingText2 as HIWSupplierSHText2,  ";
	$Sql .= "     hiw.SubHeading3 as HIWSupplierSH3, ";
	$Sql .= "     hiw.SubHeadingText3 as HIWSupplierSHText3,  ";
	$Sql .= "     hiw.BrochureFile_Id as HIWSupplierBrochureFile_Id, ";
	$Sql .= "     brf.Name as HIWSupplierBrochureName ";
	$Sql .= " FROM PresideHIW hiw ";
	$Sql .= " left join Files bf on bf.Id =  hiw.BannerFile_Id ";
	$Sql .= " left join Files brf on brf.Id =  hiw.BrochureFile_Id ";
	$Sql .= " WHERE hiw.PageType = 2  "; //PageType = 2 (Supplier)
	$Sql .= " LIMIT 1 ";

	$result = mysql_query( $Sql );
	if( 0 != mysql_errno() ){
		$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
		include( "inc_admin_panel_pages_hiw_supplier.html" );
		return;
	}
	else if( 0 == mysql_num_rows( $result ) ){
		include( "inc_admin_panel_pages_hiw_supplier.html" );
		return;
	}
	$HIWDefaults = mysql_fetch_array( $result, MYSQL_ASSOC );
	mysql_free_result( $result );

	if( ! empty( $HIWDefaults['HIWSupplierBannerFile_Id'] ) ){
		$HIWImageUrl = '/libs/get_file.php?file=' . $HIWDefaults['HIWSupplierBannerFile_Id'] . ':' . CheckSum( $HIWDefaults['HIWSupplierBannerFile_Id'] ) . '&disposition=inline&' . time();
	}
	if( ! empty( $HIWDefaults['HIWSupplierBrochureName'] ) ){
		$HIWBrochureUrl = '/libs/get_file.php?file=' . $HIWDefaults['HIWSupplierBrochureFile_Id'] . ':' . CheckSum( $HIWDefaults['HIWSupplierBrochureFile_Id'] ) . '&disposition=inline&' . time();
	}

	include( "inc_admin_panel_pages_hiw_supplier.html" );
	return;
?>