<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_home.php" );

	$UploadPath = $_SESSION['User']['UploadPath'];

	function getHomeGalleryImageUrl( $SelectedGalleryId ){
		$SelectedGalleryId =  empty( $SelectedGalleryId ) || 0 >= $SelectedGalleryId ? 1 : $SelectedGalleryId;
		$ImageUrl = "/libs/get_file.php?file=".$SelectedGalleryId.":" . CheckSum( $SelectedGalleryId ) . "&disposition=inline&" . time();
		return $ImageUrl;
	}

	function generateHomeGalleryTableHTML( $records, $page, $offset, $trans ){
		//paging variables
		$records  = !empty( $records ) ? $records : 5;
		$page     = !empty( $page )    ? $page    : 1;
		$offset   = !empty( $offset )  ? $offset  : 0;

		db_connect();

		$Sql_count  = " SELECT count( g.DisplayId ) as rowCount ";
		$Sql_count .= " FROM PrisideHomeCategoriesGallery g ";
		$Sql_count .= " INNER JOIN BusinessServiceCategory sc ON sc.Id = g.ServiceCategoryId ";

		$result      = mysql_query($Sql_count);
		$count       = mysql_fetch_row($result);
		$count       = $count[0];
		mysql_free_result($result);
		$total_pages = ceil((int)$count / $records);

		$Sql  = " SELECT ";
		$Sql .= " 	g.DisplayId, ";
		$Sql .= " 	g.DisplayTitle, ";
		$Sql .= " 	g.DisplayImageFile_Id, ";
		$Sql .= " 	g.ServiceCategoryId, ";
		$Sql .= " 	sc.Category as CategoryName, ";
		$Sql .= " 	g.DateCreated, ";
		$Sql .= " 	g.LastModifiedDate ";
		$Sql .= " FROM PrisideHomeCategoriesGallery g ";
		$Sql .= " INNER JOIN BusinessServiceCategory sc ON sc.Id = g.ServiceCategoryId ";
		$Sql .= " ORDER BY  displayId ";
		$Sql .= " LIMIT $offset, $records ";

		$HomeGallerTableHTML = "";

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Error @ " . __LINE__ . " : " . mysql_error();
			return "<span class=\"txt12Bold\" style=\"color: red;\">No data available due to error.</span>";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( $_SERVER['DOCUMENT_ROOT']."/inc_nodata_snippet.html" );
			$HomeGallerTableHTML = ob_get_contents();
			ob_end_clean();
			return $HomeGallerTableHTML;
		}

		//Populate Data Here
		$CategoriesGallery = array();
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$CategoriesGallery[] = $row;
		}
		mysql_free_result( $result );

		ob_start();

		$rowNum = 0;
		$OddEven = "even";
		reset( $CategoriesGallery );
		foreach( $CategoriesGallery as $row ) {
			$rowNum+=1;
			if( "even" == $OddEven ){
				$OddEven = "odd";
			}
			else{
				$OddEven = "even";
			}
			include( "inc_admin_panel_pages_home_data_snippet.html" );
		}

		$HomeGallerTableHTML = ob_get_contents();
		ob_end_clean();

		return $HomeGallerTableHTML;
	}

	$ErrMsg = "";
	$OkMsg = "";

	// called via onComplete of fileuploader
	// here a file was uploaded, but we only want to show a preview
	// (or more specifically, provide a path for the preview)
	if( "getPreview" == $ajaxRequest ){

		if( empty( $filename )){
			$ErrMsg = $trans["ppages_ErrMsg_filename"]; //"Image Filename cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// actual file on server
		$filepath = base64_encode( DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/logins/" . $_SESSION['User']['Id'] . "/upload" );
		$fileUrl = "/libs/get_file.php?filepath=$filepath:".CheckSum( $filepath )."&disposition=inline&" . time();

		$homeImageInfos = array( "fileUrl" => $fileUrl, "Name" => utf8_decode( $filename ) );

		print( json_encode( $homeImageInfos ) );
		exit;
	}
	else if( "ajaxAddGalleryItem" == $ajaxRequest ){
		/*
			"ajaxRequest" 			: "ajaxAddGalleryItem",
			"BannerIdChecksum" 		: $("#GalleryItemBannerFile_Id").val()+":"+$("#GalleryItemBannerFile_Checksum").val(),
			"BannerFileName" 		: $("#GalleryItemBannerFile_Name").val(),
			"DisplayTitle" 			: $("#GalleryItemTitle").val(),
			"CategoryIdCheckSum" 	: $("#CategoryId").val()+":"+$("#CategoryChecksum").val()
		*/

		$uploadFileName = $UploadPath . "/upload";

		//Since image is really needed by this page, if the request does not have any upload, display an error
		if( ! file_exists( $uploadFileName ) ){
			$ErrMsg = "Uploaded Image not found on disk @ " . __LINE__ ;
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

		/* Validation:
		   - Check if banner id is empty, if empty set it to 0
		   - Display error msg if the following fields are empty ($BannerFileName, $DisplayTitle, $CategoryId)
		*/
		if( empty( $BannerId ) ) $BannerId = 0;
		else if( empty( $BannerFileName ) ){
			$ErrMsg = $trans["ppages_ErrMsg_filename"];
		}
		else if( empty( $DisplayTitle ) ){
			$ErrMsg = $trans["ppages_ErrMsg_title"];
		}
		else if( empty( $CategoryId ) ){
			$ErrMsg = $trans["ppages_ErrMsg_category"];
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		// Create the image file by saving the image infos to the Files table and set the banner if to the generated file id

		// get mime type
		$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadFileName );

		// get file size
		$fileSize = filesize( $uploadFileName );

		db_connect();

		mysql_query( "BEGIN" );

		$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES(0,".$_SESSION['User']['Id'].", 'home_gallery_image', \"".$mimeType."\", ".$fileSize.", NOW())";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			mysql_query( "ROLLBACK" );
			header( "Status: 400 Insert Statement Error : " . __LINE__ );
			exit;
		}
		$BannerId = mysql_insert_id();

		// Insert the gallery item infos to the PrisideHomeCategoriesGallery table
		$Sql = "INSERT INTO PrisideHomeCategoriesGallery (DisplayTitle, DisplayImageFile_Id, ServiceCategoryId, DateCreated, LastModifiedDate) VALUES ('".$DisplayTitle."', $BannerId, $CategoryId, now(), now())";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			mysql_query( "ROLLBACK" );
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		mysql_query( "COMMIT" );

		// Rename the uploaded file to the value of banner id
		$newFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/" . $_SESSION['UserId'] . "/$BannerId";
		rename( $uploadFileName, $newFileName );

		// Return add successful msg back to UI via ajax success function
		$OkMsg = $trans["pages_save_success_msg"];
		print( $OkMsg );

		// Done - exit
		exit;
	}
	else if( "displaySelectedGalleryItem" == $ajaxRequest ){
		//$DisplayIdChecksum  = $DisplayId and $DisplayChecksum
		idCheckSumSplit( $DisplayIdChecksum, $DisplayId, $DisplayChecksum );
		if( ! empty( $DisplayId ) && ! empty( $DisplayChecksum ) && ! VerifyCheckSum( $DisplayId, $DisplayChecksum ) ){
			$ErrMsg = "Gallery Image CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Query gallery for the specific gallery item
		//Prepare json object if found and return back to the page via ajax
		$Sql  = " SELECT ";
		$Sql .= " 	g.DisplayTitle, ";
		$Sql .= " 	g.DisplayImageFile_Id, ";
		$Sql .= " 	g.ServiceCategoryId, ";
		$Sql .= " 	sc.Category as CategoryName ";
		$Sql .= " FROM PrisideHomeCategoriesGallery g ";
		$Sql .= " INNER JOIN BusinessServiceCategory sc ON sc.Id = g.ServiceCategoryId ";
		$Sql .= " WHERE g.DisplayId = $DisplayId ";

		db_connect();

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		else if( 0 == mysql_num_rows( $result ) ){
			$ErrMsg = "Gallery Item Info not found.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );

		$GalleryImageUrl = "/img/transparent_logo.png";
		if( ! empty( $row["DisplayImageFile_Id"] ) ){
			$GalleryImageUrl = getHomeGalleryImageUrl( $row["DisplayImageFile_Id"] );
		}

		$GalleryItemInfos = array(
							"DisplayTitle" =>  $row["DisplayTitle"],
							"GalleryImageUrl" => $GalleryImageUrl,
							"ServiceCategoryId" => $row["ServiceCategoryId"],
							"CategoryName" => $row["CategoryName"]
						);

		print( json_encode( $GalleryItemInfos ) );
		exit;

	}
	else if( "ajaxRemoveGalleryItems" == $ajaxRequest ){
		//validate checksum
		//$DisplayIdChecksum  = $DisplayId and $DisplayCheckSum
		idCheckSumSplit( $DisplayIdChecksum, $DisplayId, $DisplayCheckSum );
		if( ! empty( $DisplayId ) && ! empty( $DisplayCheckSum ) && ! VerifyCheckSum( $DisplayId, $DisplayCheckSum ) ){
			$ErrMsg = "Gallery Item Info CheckSum Error @ " . __LINE__ ;
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

		db_connect();

		//delete record from PrisideHomeCategoriesGallery, and Files
		$Sql = "DELETE FROM PrisideHomeCategoriesGallery WHERE DisplayId = ".$DisplayId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Delete Statement Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql ;
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

		// Return add successful msg back to UI via ajax success function
		$OkMsg = $trans["pages_remove_success_msg"];
		print( $OkMsg );

		// Done - exit
		exit;
	}
	else if( "ajaxReloadGalleryItems" == $ajaxRequest ){
		//paging variables
		$records     = 5;
		$page        = 1;
		$offset      = 0;

		$reloadedHTML = generateHomeGalleryTableHTML( $records, $page, $offset, $trans );
		print( $reloadedHTML );
		exit;
	}
	else if( "loadServiceRequestsAvailable" == $ajaxRequest ){
		// sanity checks on variables
		if(!VerifyCheckSum($records.$page,$checksum)){
			$ErrMsg = "CheckSum Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		//paging variables
		$records     = isset($records) ? $records : 5;
		$page        = isset($page)    ? $page    : 1;
		$offset      = ($page - 1) * $records;

		$reloadedHTML = generateHomeGalleryTableHTML( $records, $page, $offset, $trans );
		print( $reloadedHTML );
		exit;
	}

	//-----------------------------------------------------------------
	//Start: Fall-through
	//-----------------------------------------------------------------
	//$HomeGallerTableHTML = "<span class=\"txt12Bold\" style=\"color: red;\">No data available.</span>";
	$HomeGallerTableHTML = generateHomeGalleryTableHTML( 5, 1, 0, $trans );
	//-----------------------------------------------------------------

	include( "inc_admin_panel_pages_home.html" );
	return;
?>