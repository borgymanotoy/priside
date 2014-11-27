<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_pnews.php" );

	// db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";

	$UploadPath = $_SESSION['User']['UploadPath'];

	function getNewsImageUrl( $SelectedCareerId ){
		//Set the image to default transparent image if there are no career image or the parameter is empty.
		$SelectedCareerId =  empty( $SelectedCareerId ) || 0 >= $SelectedCareerId ? 1 : $SelectedCareerId;
		$ImageUrl = "/libs/get_file.php?file=".$SelectedCareerId.":" . CheckSum( $SelectedCareerId ) . "&disposition=inline&" . time();
		return $ImageUrl;
	}

	function getPressNewsItemListHtml(){
		db_connect();
		$Sql = "SELECT PressNewsItemId, PressNewsItemTitle FROM PresideNews ORDER BY PressNewsItemId ASC LIMIT 200";
		$pnews_items_html = "";
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">".$ErrMsg."</span>\n";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">No media new(s) available.</span>\n";
		}
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$pnews_items_html .= "<a class=\"arrow-link\" href=\"javascript:void(0);\" onclick=\"displayNewsItemInfos(".$row["PressNewsItemId"].", '".CheckSum( $row["PressNewsItemId"] )."');\" style=\"margin-right: 10px;\"><span class=\"pre-margin-10\">".$row["PressNewsItemTitle"]."</span></a><br/>\n";
		}
		mysql_free_result( $result );
		return $pnews_items_html;
	}


	//ajax request
	//$.ajax({ type : "POST", url : url, data : { "ajaxRequest" : "ajaxSavePressNewsInfos", "PressIntroduction" : $PressIntroduction, "PressSubHeading" : $PressSubHeading, "PressSubHeadingContent" : $PressSubHeadingContent, "PressImagePath" : $PressImagePath, "PressBrochureUrl" : $PressBrochureUrl });
	if( "ajaxSavePressNewsInfos" == $ajaxRequest ){
		//$PressNewsIdChecksum  = $PressNewsId and $PressNewsChecksum
		idCheckSumSplit( $PressNewsIdChecksum, $PressNewsId, $PressNewsChecksum );
		if( ! empty( $PressNewsId ) && ! empty( $PressNewsChecksum ) && ! VerifyCheckSum( $PressNewsId, $PressNewsChecksum ) ){
			$ErrMsg = "News Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		//$BrochureIdChecksum  = $BrochureId and $BrochureChecksum
		idCheckSumSplit( $BrochureIdChecksum, $BrochureId, $BrochureChecksum );
		if( ! empty( $BrochureId ) && ! empty( $BrochureChecksum ) && ! VerifyCheckSum( $BrochureId, $BrochureChecksum ) ){
			$ErrMsg = "Brochure Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $PressIntroduction ) ) $ErrMsg = $trans["p_news_ErrMsg_PressIntroduction"];
		else if( empty( $PressSubHeading ) ) $ErrMsg = $trans["p_news_ErrMsg_PressSubHeading"];
		else if( empty( $PressSubHeadingContent ) ) $ErrMsg = $trans["p_news_ErrMsg_PressSubHeadingContent"];
		else if( empty( $BannerId ) ) $BannerId = 0;
		else if( empty( $BrochureId ) ) $BrochureId = 0;

		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}
		// --------------------------------------------------------------

		db_connect();

		$PressIntroduction = mysql_real_escape_string( utf8_decode($PressIntroduction) );
		$PressSubHeading = mysql_real_escape_string( utf8_decode($PressSubHeading) );
		$PressSubHeadingContent = mysql_real_escape_string( utf8_decode($PressSubHeadingContent) );
		$PressImagePath = mysql_real_escape_string( utf8_decode($PressImagePath) );
		$PressBrochureUrl = mysql_real_escape_string( utf8_decode($PressBrochureUrl) );

		//--------------------------------------------------------------------------------------------
		// Insert or Update data
		//----------------------
		if( empty( $PressNewsId ) ){
			$Sql = "INSERT INTO PresidePressKit (PressIntroduction, PressSubHeading, PressSubHeadingContent, PressImageFile_Id, PressBrochureFile_Id, DateCreated, LastModified) VALUES ('".$PressIntroduction."', '".$PressSubHeading."', '".$PressSubHeadingContent."', $BannerId, $BrochureId, now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Insert Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$PressNewsId = mysql_insert_id();
		} else {
			//update sql (only if consumer data already exists)
			$Sql  = " UPDATE PresidePressKit SET ";
			$Sql .= " 	PressIntroduction = '".$PressIntroduction."', ";
			$Sql .= " 	PressSubHeading = '".$PressSubHeading."', ";
			$Sql .= " 	PressSubHeadingContent = '".$PressSubHeadingContent."', ";
			$Sql .= " 	PressImageFile_Id = $BannerId, ";
			$Sql .= " 	PressBrochureFile_Id = $BrochureId, ";
			$Sql .= " 	LastModified = now()";
			$Sql .= " WHERE PressId = $PressNewsId ";
			$Sql .= " LIMIT 1 ";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Update Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
		}

		//Create JSON Object that will return ( $PressNewsId, PressNewsChecksum, $BannerId, BannerChecksum, $BrochureId, BrochureChecksum, PressNewsStatusMsg)
		$PressNewsInfos = array(
							"PressNewsId" 				=> $PressNewsId,
							"PressNewsChecksum" 		=> CheckSum( $PressNewsId ),
							"PressNewsBannerId" 		=> $BannerId,
							"PressNewsBannerChecksum" 	=> CheckSum( $BannerId ),
							"PressNewsBrochureId" 		=> $BrochureId,
							"PressNewsBrochureChecksum" => CheckSum( $BrochureId ),
							"PressNewsImageImageUrl" 	=> getNewsImageUrl( $BannerId ),
							"PressNewsStatusMsg" 		=> utf8_decode( $trans["pnews_save_success_msg"] )
						);
		print( json_encode( $PressNewsInfos ) );
		exit;
		//--------------------------------------------------------------------------------------------
	}
	else if ( "ajaxSaveUpdatePressNewsItemInfos" == $ajaxRequest ) {
		//$PressNewsItemIdCheckSum  = $PressNewsItemId and $PressNewsItemChecksum
		idCheckSumSplit( $PressNewsItemIdCheckSum, $PressNewsItemId, $PressNewsItemChecksum );
		if( ! empty( $PressNewsItemId ) && ! empty( $PressNewsItemChecksum ) && ! VerifyCheckSum( $PressNewsItemId, $PressNewsItemChecksum ) ){
			$ErrMsg = "News Item Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $BannerId ) ) $BannerId = 0;
		else if( empty( $PressNewsItemTitle ) ){
			$ErrMsg = $trans["p_news_ErrMsg_PressNewsItemTitle"]; //"PressNewsItemTitle cannot be blank.";
		}
		else if( empty( $BannerFileName ) ){
			$ErrMsg = $trans["p_news_ErrMsg_BannerImageNotSet"]; //"BannerFileName cannot be blank.";
		}
		else if( empty( $PressNewsItemDescription ) ){
			$ErrMsg = $trans["pcareers_ErrMsg_CareerDescription"]; //"PressNewsItemDescription cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}
		// --------------------------------------------------------------

		// actual file on server
		$uploadFileName = $UploadPath . "/upload";

		if( ! empty( $PressNewsItemId ) && ! file_exists( $uploadFileName ) && 0 < $BannerId ){
			$uploadFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $BannerId;
		}

		// get mime type
		$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadFileName );

		// get file size
		$fileSize = filesize( $uploadFileName );

		db_connect();

		mysql_query( "BEGIN" );

		//Since BannerId is needed, we need to generate BannerId by saving the uploaded banner image and use the generated BannerId after the insertion of record
		//If banner id is blank, insert new image file
		if( 0 == $BannerId ){
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].", 'press_news_item_image', \"".$mimeType."\", ".$fileSize.", NOW())";
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
			$Sql = "UPDATE Files SET Name='press_news_item_image', Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BannerId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		$PressNewsImageUrl = mysql_real_escape_string( utf8_decode($PressNewsImageUrl) );
		$PressNewsItemTitle = mysql_real_escape_string( utf8_decode($PressNewsItemTitle) );
		$PressNewsItemDescription = mysql_real_escape_string( utf8_decode($PressNewsItemDescription) );

		if( empty( $PressNewsItemId ) || empty( $PressNewsItemChecksum )){
			$Sql = "INSERT INTO PresideNews (PressNewsItemTitle, PressNewsItemDescription, PressNewsImageFile_Id, StartDate, EndDate, DateCreated, LastModified) VALUES ('".$PressNewsItemTitle."', '".$PressNewsItemDescription."', $BannerId, date_add(now(), INTERVAL -100 YEAR), date_add(now(), INTERVAL 100 YEAR), now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$PressNewsItemId = mysql_insert_id();
			$OkMsg = $trans["pnews_item_save_success_msg"];
		}
		else{
			$Sql  = " UPDATE PresideNews SET ";
			$Sql .= " 	PressNewsItemTitle = '".$PressNewsItemTitle."', ";
			$Sql .= " 	PressNewsItemDescription = '".$PressNewsItemDescription."', ";
			$Sql .= " 	PressNewsImageFile_Id = $BannerId, ";
			$Sql .= " 	StartDate = date_add(now(), INTERVAL -100 YEAR), ";
			$Sql .= " 	EndDate = date_add(now(), INTERVAL 100 YEAR), ";
			$Sql .= " 	LastModified = now()";
			$Sql .= " WHERE PressNewsItemId = ".$PressNewsItemId." LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$OkMsg = $trans["pnews_item_update_success_msg"];
		}

		mysql_query( "COMMIT" );

		if( file_exists( $uploadFileName ) ){
			//After inserting or updating the file record, overwrite the old file with "upload" file.
			$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$BannerId";
			rename( $uploadFileName, $newFileName );
		}

		print( $OkMsg );
		exit;
		//--------------------------------------------------------------------------------------------
	}
	else if ( "ajaxRemovePressNewsItem" == $ajaxRequest ){
		//$PressNewsItemIdCheckSum  = $PressNewsItemId and $PressNewsItemChecksum
		idCheckSumSplit( $PressNewsItemIdCheckSum, $PressNewsItemId, $PressNewsItemChecksum );
		if( ! empty( $PressNewsItemId ) && ! empty( $PressNewsItemChecksum ) && ! VerifyCheckSum( $PressNewsItemId, $PressNewsItemChecksum ) ){
			$ErrMsg = "News Item Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		if( empty( $PressNewsItemId ) ){
			$ErrMsg = $trans["p_news_ErrMsg_PressNewsItemId"]; //"PressNewsItemId cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		//$BannerIdChecksum  = $BannerId and $BannerChecksum
		idCheckSumSplit( $BannerIdChecksum, $BannerId, $BannerChecksum );
		if( ! empty( $BannerId ) && ! empty( $BannerChecksum ) && ! VerifyCheckSum( $BannerId, $BannerChecksum ) ){
			$ErrMsg = "Banner Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		db_connect();

		$PressNewsItemId = mysql_real_escape_string( utf8_decode($PressNewsItemId) );

		$Sql = "DELETE FROM PresideNews WHERE PressNewsItemId = ".$PressNewsItemId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$Sql = "DELETE FROM Files WHERE Id = ".$BannerId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "File Deletion Error in DB @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// if ok, verify file exists if( file_exists( $path ) )
		$removeFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $BannerId;
		if( file_exists( $removeFileName ) ){
			unlink( $removeFileName );
		}

		// form deleted ok.
		$OkMsg = $trans["pnews_item_remove_success_msg"];
		print( $OkMsg );
		exit;
	}
	else if ( "ajaxReloadPressNewsItemList" == $ajaxRequest ){
		$PressNewsListHtml = getPressNewsItemListHtml();
		print( $PressNewsListHtml );
		exit;
	}
	else if( "getPreview" == $ajaxRequest ){

		if( empty( $filename )){
			$ErrMsg = $trans["p_news_ErrMsg_BannerImageNotSet"]; //"Image Filename cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// actual file on server
		$filepath = base64_encode( $UploadPath . "/upload" );
		$PressNewsImageImageUrl = "/libs/get_file.php?filepath=$filepath:".CheckSum( $filepath )."&disposition=inline&" . time();

		//Create JSON Object to be used in the page, should contain image file name and the URL.
		$PressNewsImageInfos = array( "PressNewsItemImageFileName" => utf8_decode( $filename ), "PressNewsItemImageImageUrl" => $PressNewsImageImageUrl );

		//Return back JSON Data and then Exit
		print( json_encode( $PressNewsImageInfos ) );
		exit;
	}
	else if ( "displayNewsItemInfos" == $ajaxRequest ){
		//$NewsItemIdChecksum  = $NewsItemId and $NewsItemChecksum
		idCheckSumSplit( $NewsItemIdChecksum, $NewsItemId, $NewsItemChecksum );
		if( ! empty( $NewsItemId ) && ! empty( $NewsItemChecksum ) && ! VerifyCheckSum( $NewsItemId, $NewsItemChecksum ) ){
			$ErrMsg = "News Item Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//If Career Id is empty, return to page with an error message.
		if( empty( $NewsItemId ) ){
			$ErrMsg = "News Item Info Error @ " . __LINE__ . "News Item Id Cannot be Blank.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Query PrisideCareers and get Career Infos
		$Sql  = " SELECT ";
		$Sql .= " 	pn.PressNewsItemId, ";
		$Sql .= " 	pn.PressNewsItemTitle, ";
		$Sql .= " 	pn.PressNewsItemDescription, ";
		$Sql .= " 	pn.PressNewsImageFile_Id, ";
		$Sql .= " 	f.Name as PressNewsItemImageFileName, ";
		$Sql .= " 	pn.StartDate, ";
		$Sql .= " 	pn.EndDate, ";
		$Sql .= " 	pn.DateCreated, ";
		$Sql .= " 	pn.LastModified ";
		$Sql .= " FROM PresideNews pn ";
		$Sql .= " Left Join Files f On f.Id = pn.PressNewsImageFile_Id ";
		$Sql .= " WHERE  ";
		$Sql .= " 	pn.PressNewsItemId = $NewsItemId ";
		$Sql .= " LIMIT 1 ";

		db_connect();

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "News Item Info Query Error @ " . __LINE__ . " : " . mysql_error();
			include( "inc_admin_panel_pages_careers.html" );
			return;
		}
		else if( 0 == mysql_num_rows( $result ) ){
			$ErrMsg = "News Item Info not found.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );

		//Generate News Item Image Url based from the News Item Image Id
		$PressNewsItemImageUrl = getNewsImageUrl( $row["PressNewsImageFile_Id"] );

		//Create JSON Object based from the query results
		//The JSON Object should have the following fields (PressNewsItemId, PressNewsItemChecksum, PressNewsItemTitle, PressNewsItemDescription, PressNewsImageFile_Id, PressNewsItemImageChecksum, PressNewsItemImageFileName, PressNewsItemImageUrl)
		$PressNewsItemInfos = array(
							"PressNewsItemId" =>  $row["PressNewsItemId"],
							"PressNewsItemChecksum" => CheckSum( $row["PressNewsItemId"] ),
							"PressNewsItemTitle" => utf8_decode( $row["PressNewsItemTitle"] ),
							"PressNewsItemDescription" => utf8_decode( $row["PressNewsItemDescription"] ),
							"PressNewsImageFile_Id" => $row["PressNewsImageFile_Id"],
							"PressNewsItemImageChecksum" => CheckSum( $row["PressNewsImageFile_Id"] ),
							"PressNewsItemImageFileName" => $row["PressNewsItemImageFileName"],
							"PressNewsItemImageUrl" => $PressNewsItemImageUrl
						);
		//MySQL Resource not used now, lets throw them to trash! Oyeah!
		mysql_free_result( $result );
		print( json_encode( $PressNewsItemInfos ) );
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
			$ErrMsg = $trans["p_news_ErrMsg_BannerImageNotSet"]; //"Image Filename cannot be blank.";
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
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].", 'press_news_image', \"".$mimeType."\", ".$fileSize.", NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Insert Statement Error : " . __LINE__ );
				exit;
			}
			$BannerId = mysql_insert_id();
		}
		//If banner id is not blank, just update the file record
		else{
			$Sql = "UPDATE Files SET Name='press_news_image',Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BannerId LIMIT 1";
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
		$fileUrl = "/libs/get_file.php?file=$BannerId:" . CheckSum( $BannerId ) . "&disposition=inline&" . time();
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
		$ErrMsg = $trans["p_news_ErrMsg_BrochureNotSet"]; //"Image Filename cannot be blank.";
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
		$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].", 'press_news_brochure', \"".$mimeType."\", ".$fileSize.", NOW())";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			header( "Status: 400 Insert Statement Error : " . __LINE__ );
			exit;
		}
		$BrochureId = mysql_insert_id();
	}
	//If pricelist file id is not blank, just update the file record
	else{
		$Sql = "UPDATE Files SET Name='press_news_brochure', Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BrochureId LIMIT 1";
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
	$fileUrl = "/libs/get_file.php?file=$BrochureId:" . CheckSum( $BrochureId ) . "&disposition=attachment&" . time();
	$BrochureInfos = array( "BrochureId" =>  $BrochureId, "BrochureChecksum" => CheckSum(  $BrochureId ), "BrochureName" => $filename, "BrochureUrl" => $fileUrl );

	//Done
	print( json_encode( $BrochureInfos ) );
	exit;
}

	//START: Fall-through codes-------------------------------

	$PressNewsImageUrl = "/img/transparent_logo.png";
	$PressNewsBrochureUrl = "";
	$PressNewsListHtml = getPressNewsItemListHtml();

	//Load Press Kit Infos
	$Sql  = " SELECT ";
	$Sql .= " 	ppk.PressId, ";
	$Sql .= " 	ppk.PressImageFile_Id, ";
	$Sql .= " 	f1.Name as PressImageFileName, ";
	$Sql .= " 	ppk.PressIntroduction, ";
	$Sql .= " 	ppk.PressSubHeading, ";
	$Sql .= " 	ppk.PressSubHeadingContent, ";
	$Sql .= " 	ppk.PressBrochureFile_Id, ";
	$Sql .= " 	f2.Name as BrochureFileName,";
	$Sql .= " 	ppk.DateCreated, ";
	$Sql .= " 	ppk.LastModified ";
	$Sql .= " FROM PresidePressKit ppk ";
	$Sql .= " Left Join Files f1 On f1.Id = ppk.PressImageFile_Id ";
	$Sql .= " Left Join Files f2 On f2.Id = ppk.PressBrochureFile_Id ";
	$Sql .= " LIMIT 1 ";

	db_connect();

	$result = mysql_query( $Sql );
	if( 0 != mysql_errno() ){
		$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
		include( "inc_admin_panel_pages_pnews.html" );
		return;
	}
	else if( 0 == mysql_num_rows( $result ) ){
		include( "inc_admin_panel_pages_pnews.html" );
		return;
	}

	$PressNewsDefaults = mysql_fetch_array( $result, MYSQL_ASSOC );
	mysql_free_result( $result );

	if( ! empty( $PressNewsDefaults['PressImageFile_Id'] ) ){
		$PressNewsImageUrl = '/libs/get_file.php?file=' . $PressNewsDefaults['PressImageFile_Id'] . ':' . CheckSum( $PressNewsDefaults['PressImageFile_Id'] ) . '&disposition=inline&' . time();
	}
	if( ! empty( $PressNewsDefaults['PressBrochureFile_Id'] ) ){
		$PressNewsBrochureUrl = '/libs/get_file.php?file=' . $PressNewsDefaults['PressBrochureFile_Id'] . ':' . CheckSum( $PressNewsDefaults['PressBrochureFile_Id'] ) . '&disposition=attachment&' . time();
	}

	include( "inc_admin_panel_pages_pnews.html" );
	//END: Fall-through codes-------------------------------

	return;
?>