<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_careers.php" );

	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";

	function getCareerImageUrl( $SelectedCareerId ){
		//Set the image to default transparent image if there are no career image or the parameter is empty.
		$SelectedCareerId =  empty( $SelectedCareerId ) || 0 >= $SelectedCareerId ? 1 : $SelectedCareerId;
		$ImageUrl = "/libs/get_file.php?file=".$SelectedCareerId.":" . CheckSum( $SelectedCareerId ) . "&disposition=inline&" . time();
		return $ImageUrl;
	}

	function getCareerListHtml(){
		db_connect();
		$Sql = "SELECT CareerId, CareerTitle FROM PresideCareers ORDER BY CareerId ASC LIMIT 200";
		$careers_html = "";
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">".$ErrMsg."</span>\n";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">No career(s) available.</span>\n";
		}
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$careers_html .= "<a href=\"javascript:void(0);\" onclick=\"displayCareerInfos(".$row["CareerId"].",'".CheckSum( $row["CareerId"] )."');\" class=\"arrow-link\" style=\"margin-right: 10px;\"><span class=\"pre-padding-10\">".$row["CareerTitle"]."</span></a><br/>\n";
		}
		mysql_free_result( $result );
		return $careers_html;
	}

	//Set the default image to transparent to fix th size of the image so that the upload button's flash object will not be rellocated
	//since there are issues with how location of the flash object for file upload.
	$CareerImageUrl = "/img/transparent_logo.png";

	//This is the default upload path that will contain the uploaded file that will be named "upload" by default
	$UploadPath = $_SESSION['User']['UploadPath'];

	// ajax request
	// data : { "ajaxRequest" : "ajaxSaveUpdateCareers", "CareerId" : CareerId, "CareerCheckSum" : CareerCheckSum, "CareerTitle" : CareerTitle, "CareerDescription" : CareerDescription, },
	if( "getPreview" == $ajaxRequest ){

		if( empty( $filename )){
			$ErrMsg = $trans["pcareers_ErrMsg_filename"]; //"Image Filename cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// actual file on server
		$filepath = base64_encode( $UploadPath . "/upload" );
		$CareerImageUrl = "/libs/get_file.php?filepath=$filepath:".CheckSum( $filepath )."&disposition=inline&" . time();

		//Create JSON Object to be used in the page, should contain image file name and the URL.
		$CareerImageInfos = array( "CareerImageFileName" => $filename, "CareerImageUrl" => $CareerImageUrl );

		//Return back JSON Data and then Exit
		print( json_encode( $CareerImageInfos ) );
		exit;
	}
	else if ( "ajaxSaveUpdateCareers" == $ajaxRequest ) {
		//$CareerIdCheckSum  = $CareerId and $CareerCheckSum
		idCheckSumSplit( $CareerIdCheckSum, $CareerId, $CareerCheckSum );
		if( ! empty( $CareerId ) && ! empty( $CareerCheckSum ) && ! VerifyCheckSum( $CareerId, $CareerCheckSum ) ){
			$ErrMsg = "Career Info CheckSum Error @ " . __LINE__ ;
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
		else if( empty( $BannerFileName ) ){
			$ErrMsg = $trans["pcareers_ErrMsg_filename"]; //"Banner Image cannot be blank.";
		}
		else if( empty( $CareerTitle ) ){
			$ErrMsg = $trans["pcareers_ErrMsg_CareerTitle"]; //"CareerTitle cannot be blank.";
		}
		else if( empty( $CareerDescription ) ){
			$ErrMsg = $trans["pcareers_ErrMsg_CareerDescription"]; //"CareerDescription cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		// --------------------------------------------------------------

		// actual file on server
		$uploadFileName = $UploadPath . "/upload";

		//If CareerId is not empty (Existing Career : Update) AND "upload" file does not exist AND There is an existing FileId
		//Set the old path to the Actual File (for updating Career INFOS only and NOT CHANGING the IMAGE).
		if( ! empty( $CareerId ) && ! file_exists( $uploadFileName ) && 0 < $BannerId ){
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
			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].", 'career_item_image', \"".$mimeType."\", ".$fileSize.", NOW())";
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
			$Sql = "UPDATE Files SET Name='career_item_image',Type=\"$mimeType\",Size=$fileSize,CreationDate=NOW() WHERE Id = $BannerId LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Query Error : " . __LINE__ );
				exit;
			}
		}

		$CareerTitle = mysql_real_escape_string( utf8_decode($CareerTitle) );
		$CareerDescription = mysql_real_escape_string( utf8_decode($CareerDescription) );

		if( empty( $CareerId ) || empty( $CareerCheckSum )){
			$Sql = "INSERT INTO PresideCareers (CareerTitle, CareerDescription, CareerImageFile_Id, StartDate, EndDate, DateCreated, LastModified) VALUES ('".$CareerTitle."', '".$CareerDescription."', $BannerId, date_add(now(), INTERVAL -100 YEAR), date_add(now(), INTERVAL 100 YEAR), now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$OkMsg =  $trans["careers_save_success_msg"];
		}
		else{
			$Sql  = " UPDATE PresideCareers SET ";
			$Sql .= " 	CareerTitle = '".$CareerTitle."', ";
			$Sql .= " 	CareerDescription = '".$CareerDescription."', ";
			$Sql .= " 	CareerImageFile_Id = $BannerId, ";
			$Sql .= " 	StartDate = date_add(now(), INTERVAL -100 YEAR), ";
			$Sql .= " 	EndDate = date_add(now(), INTERVAL 100 YEAR), ";
			$Sql .= " 	LastModified = now()";
			$Sql .= " WHERE CareerId = ".$CareerId." LIMIT 1";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$OkMsg =  $trans["careers_update_success_msg"];
		}
		mysql_query( "COMMIT" );

		//After inserting or updating the file record, overwrite the old file with "upload" file.
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$BannerId";
		rename( $uploadFileName, $newFileName );

		print( $OkMsg );
		exit;
		// done.
		//--------------------------------------------------------------------------------------------
	}
	else if ( "ajaxRemoveCareers" == $ajaxRequest ){

		//$CareerIdCheckSum  = $CareerId and $CareerCheckSum
		idCheckSumSplit( $CareerIdCheckSum, $CareerId, $CareerCheckSum );
		if( ! empty( $CareerId ) && ! empty( $CareerCheckSum ) && ! VerifyCheckSum( $CareerId, $CareerCheckSum ) ){
			$ErrMsg = "Career Info CheckSum Error @ " . __LINE__ ;
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

		if( empty( $CareerId ) ){
			$ErrMsg = $trans["pcareers_ErrMsg_CareerId"]; //"CareerId cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		$CareerId = mysql_real_escape_string( utf8_decode($CareerId) );

		$Sql = "DELETE FROM PresideCareers WHERE CareerId = ".$CareerId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$Sql = "DELETE FROM Files WHERE Id = ".$BannerId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "File Removal SQL Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// if ok, verify file exists if( file_exists( $path ) )
		$removeFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $BannerId;
		if( file_exists( $removeFileName ) ){
			unlink( $removeFileName );
		}

		// form deleted ok.
		$OkMsg = $trans["careers_remove_success_msg"];
		print( $OkMsg );
		exit;
	}
	else if ( "reloadCareersList" == $ajaxRequest ){
		$CareerListHtml = getCareerListHtml();
		print( $CareerListHtml );
		exit;
	}
	else if ( "displayCareerInfos" == $ajaxRequest ){
		//$CareerIdChecksum  = $CareerId and $CareerChecksum
		idCheckSumSplit( $CareerIdChecksum, $CareerId, $CareerChecksum );
		if( ! empty( $CareerId ) && ! empty( $CareerChecksum ) && ! VerifyCheckSum( $CareerId, $CareerChecksum ) ){
			$ErrMsg = "Career Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//If Career Id is empty, return to page with an error message.
		if( empty( $CareerId ) ){
			$ErrMsg = "Career Info Error @ " . __LINE__ . "Career Id Cannot be Blank.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Query PrisideCareers and get Career Infos
		$Sql  = " Select ";
		$Sql .= " 	c.CareerId, ";
		$Sql .= " 	c.CareerTitle, ";
		$Sql .= " 	c.CareerDescription, ";
		$Sql .= " 	c.CareerImageFile_Id as CareerImageId, ";
		$Sql .= " 	f.Name as CareerImageFileName ";
		$Sql .= " From PresideCareers c ";
		$Sql .= " Left Join Files f On f.Id = c.CareerImageFile_Id ";
		$Sql .= " Where ";
		$Sql .= " 	c.CareerId = $CareerId AND";
		$Sql .= " 	now() between c.StartDate and c.EndDate ";
		$Sql .= " LIMIT 1 ";

		db_connect();

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Careers Query Error @ " . __LINE__ . " : " . mysql_error();
			include( "inc_admin_panel_pages_careers.html" );
			return;
		}
		else if( 0 == mysql_num_rows( $result ) ){
			$ErrMsg = "Career Info not found.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );

		//Generate Career Image Url based from the Career Image Id
		$CareerImageUrl = getCareerImageUrl( $row["CareerImageId"] );

		//Create JSON Object based from the query results
		//The JSON Object should have the following fields (CareerId, CareerChecksum, CareerTitle, CareerDescription, CareerImageId, CareerImageChecksum, CareerImageFileName, CareerImageUrl)
		$CareerInfos = array(
							"CareerId" =>  $row["CareerId"],
							"CareerChecksum" => CheckSum( $row["CareerId"] ),
							"CareerTitle" => $row["CareerTitle"],
							"CareerDescription" => $row["CareerDescription"],
							"CareerImageId" => $row["CareerImageId"],
							"CareerImageChecksum" => CheckSum( $row["CareerImageId"] ),
							"CareerImageFileName" => $row["CareerImageFileName"],
							"CareerImageUrl" => $CareerImageUrl
						);
		//MySQL Resource not used now, lets throw them to trash! Oyeah!

		print( json_encode( $CareerInfos ) );
		exit;
	}
	//---------------------------------------------------------------------------------------------------------------
	// there is no ajax action ... so we let the page load

	$CareerListHtml = getCareerListHtml();

	include( "inc_admin_panel_pages_careers.html" );
	return;
?>