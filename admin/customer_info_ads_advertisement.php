<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/customer_info_ads_advertisement.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	db_connect();

	function generateCustomerAdsTableHTML( $customerId, $records, $page, $offset, $trans ){
		if( empty( $customerId ) ) return "";
		//paging variables
		$records  = !empty( $records ) ? $records : 5;
		$page     = !empty( $page )    ? $page    : 1;
		$offset   = !empty( $offset )  ? $offset  : 0;

		$Sql_count = " SELECT COUNT(a.Id) as rowCount FROM Ad a INNER JOIN Accounts acc on acc.Id = a.Account_Id WHERE acc.PrimaryUser_Id = $customerId ";

		$result      = mysql_query($Sql_count);
		$count       = mysql_fetch_row($result);
		$count       = $count[0];
		mysql_free_result($result);
		$total_pages = ceil((int)$count / $records);

		$Sql  = " SELECT ";
		$Sql .= "     a.Id AS Ad_Id, ";
		$Sql .= "     acc.Id as Account_Id, ";
		$Sql .= "     a.ImageFile_Id, ";
		$Sql .= "     f.Name AS Image_name, ";
		$Sql .= "     a.Link AS URL, ";
		$Sql .= "     GROUP_CONCAT(DISTINCT bsc.Category  ORDER BY bsc.Category SEPARATOR ', ') AS Industry, ";
		$Sql .= "     GROUP_CONCAT(DISTINCT sl.Name  ORDER BY sl.Name SEPARATOR ', ') AS Location, ";
		$Sql .= "     a.Amount, ";
		$Sql .= "     DATE_FORMAT(MIN(s.StartDate), '%m/%d/%Y') AS StartDate, ";
		$Sql .= "     DATE_FORMAT(MAX(s.EndDate), '%m/%d/%Y') AS EndDate, ";
		$Sql .= "     COUNT(DISTINCT acl.BusinessServiceCategory_Id) AS CategoryCount, ";
		$Sql .= "     COUNT(DISTINCT acl.Sweden_LanKommuner_Id) AS LocationCount, ";
		$Sql .= "     a.Status ";
		//$Sql .= "     , IFNULL(ast.Views, 0) as Views ";
		//$Sql .= "     , IFNULL(ast.Clicks, 0) as Clicks ";
		//$Sql .= "     , IFNULL(ast.UniqueViews, 0) as UniqueViews ";
		$Sql .= " FROM Ad a ";
		$Sql .= " INNER JOIN Accounts acc on acc.Id = a.Account_Id ";
		$Sql .= " LEFT JOIN AdSpot s ON s.Ad_Id = a.Id ";
		$Sql .= " LEFT JOIN AdCategoryLocation acl ON acl.AdSpot_Id = s.Id ";
		$Sql .= " LEFT JOIN BusinessServiceCategory bsc ON bsc.Id = acl.BusinessServiceCategory_Id ";
		$Sql .= " LEFT JOIN Sweden_LanKommuner sl ON sl.Id = acl.Sweden_LanKommuner_Id ";
		$Sql .= " LEFT JOIN Files f ON f.Id = a.ImageFile_Id ";
		//$Sql .= " LEFT JOIN AdStats ast ON ast.Ad_Id = a.Id ";
		$Sql .= " WHERE acc.PrimaryUser_Id = $customerId ";
		$Sql .= " GROUP BY a.Id ";
		$Sql .= " ORDER BY a.Id ";
		$Sql .= " LIMIT $offset, $records ";

		$CustomerAdsListHTML = "";

		$AdIds = array();
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Error @ " . __LINE__ . " : " . mysql_error();
			return "<span class=\"txt12Bold\" style=\"color: red;\">No data available due to error.</span>";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( $_SERVER['DOCUMENT_ROOT']."/inc_nodata_snippet.html" );
			$CustomerAdsListHTML = ob_get_contents();
			ob_end_clean();
			return $CustomerAdsListHTML;
		}

		//Populate Data Here
		$CustomerAdvertisements = array();
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$CustomerAdvertisements[] = $row;
			$AdIds[] = $row["Ad_Id"];
		}
		mysql_free_result( $result );

		//Create array that will contain the stats of the selected ads in $AdIds
		$AdStats = array();
		if(count( $AdIds ) > 0 ){
			$Sql  = " SELECT Ad_Id, SUM(Views) AS Views, SUM(Clicks) AS Clicks , SUM(UniqueViews) AS UniqueViews ";
			$Sql .= " FROM AdStats WHERE Ad_Id IN ( ".implode( ", ", $AdIds ).") ";
			$Sql .= " GROUP BY Ad_Id ORDER BY Ad_Id ";

			$result = mysql_query( $Sql );
			if( mysql_errno() == 0 && mysql_num_rows( $result ) > 0){
				while($row = mysql_fetch_array( $result )){
					$AdStats[$row["Ad_Id"]] = $row;
				}
			}
		}

		ob_start();

		$statusColor = "#412467"; //booked by default
		$rowNum = 0;
		$OddEven = "even";
		reset( $CustomerAdvertisements );

		$Stats = array();
		foreach( $CustomerAdvertisements as $ad ) {
			$rowNum+=1;
			$Stats = $AdStats[$ad["Ad_Id"]];

			$ad["Views"] = !empty($Stats["Views"]) ? $Stats["Views"] : 0;
			$ad["Clicks"] = !empty($Stats["Clicks"]) ? $Stats["Clicks"] : 0;
			$ad["UniqueViews"] = !empty($Stats["UniqueViews"]) ? $Stats["UniqueViews"] : 0;

			if( "even" == $OddEven ){
				$OddEven = "odd";
			}
			else{
				$OddEven = "even";
			}

			switch( $ad["Status"] ){
				case "added" :
					$statusColor = "#fff101";
					break;
				case "active" :
					$statusColor = "#40ae49";
					break;
				case "booked" :
					$statusColor = "#412467";
					break;
				case "closed" :
					$statusColor = "#d1232a";
					break;
			}

			include( "inc_customer_info_ads_advertisement_rowSnippet.html" );
		}

		$CustomerAdsListHTML = ob_get_contents();
		ob_end_clean();

		return $CustomerAdsListHTML;
	}

//this will encode an array to utf8
 function array_utf8_encode(&$array){ 
  if(is_string($array)) { 
   return utf8_encode($array); 
  } 
  
  if (!is_array($array)){
   return $dat;
  } 
  
  $ret = array(); 
  foreach($array as $i => $d){ 
   if(!is_array($d)){
    $ret[$i] = utf8_encode($d); 
   }
   else{
    $ret[$i] = $d; 
   }
  }
  return $ret; 
 }

	//this is our save path for our uploads advertisement image
	//since the "admin" acts in behalf of the customer, lets use the CURRENT_USER's details
	$UploadPath    = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}/logins/{$_SESSION['User']['Id']}";
	$ImageFilename = "$UploadPath/advertisementImage";
	$SavePathImage = base64_encode($ImageFilename).',CheckSum='.CheckSum(base64_encode($ImageFilename)).',Session='.session_id();

	if("getImagePreview" == $ajaxRequest){
		if(!VerifyCheckSum($customerId,$checksum)){
			$ErrMsg = "CheckSum Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		$ImageFilename = base64_encode($ImageFilename);
		$fileUrl = "/libs/get_file.php?filepath={$ImageFilename}:".CheckSum($ImageFilename)."&disposition=inline&" . time();
		header( "Content-Type: application/json" );
		die(json_encode(array("fileUrl" => $fileUrl)));
	}
	else if( "loadCustomerAdvertisements" == $ajaxRequest ){
		// sanity checks on variables
		if(!VerifyCheckSum($records.$page,$checksum)){
			$ErrMsg = "CheckSum Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		if( empty($customerId) ){
			$ErrMsg = $trans["pcust_nfo_ads_customer"];
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		//paging variables
		$records     = isset($records) ? $records : 5;
		$page        = isset($page)    ? $page    : 1;
		$offset      = ($page - 1) * $records;

		$CustomerAdsListHTML = generateCustomerAdsTableHTML( $customerId, $records, $page, $offset, $trans );
		print( $CustomerAdsListHTML );
		exit;
	}
	else if( "loadCustomerAdInfos" == $ajaxRequest ){
		//$AdIdChecksum  = $AdId and $AdChecksum
		idCheckSumSplit( $AdIdChecksum, $AdId, $CheckSum );
		if( ! VerifyCheckSum( $AdId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		/*
		//$AccountIdChecksum  = $AccountId and $AccountChecksum
		idCheckSumSplit( $AccountIdChecksum, $AccountId, $CheckSum );
		if( ! VerifyCheckSum( $AccountId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}*/

		$Sql  = " SELECT ";
		$Sql .= "     a.Id as Ad_Id, ";
		$Sql .= "     acc.Id as Account_Id, ";
		$Sql .= "     a.ImageFile_Id, ";
		$Sql .= "     f.Name as FileName, ";
		$Sql .= "     a.Link, ";
		$Sql .= "     GROUP_CONCAT(DISTINCT bsc.Category  ORDER BY bsc.Category SEPARATOR ', ') AS Industry, ";
		$Sql .= "     GROUP_CONCAT(DISTINCT sl.Name  ORDER BY sl.Name SEPARATOR ', ') AS Location, ";
		$Sql .= "     a.Status ";
		$Sql .= " FROM Ad a ";
		$Sql .= " INNER JOIN Accounts acc on acc.Id = a.Account_Id ";
		$Sql .= " LEFT JOIN AdSpot s ON s.Ad_Id = a.Id ";
		$Sql .= " LEFT JOIN AdCategoryLocation acl ON acl.AdSpot_Id = s.Id ";
		$Sql .= " LEFT JOIN BusinessServiceCategory bsc ON bsc.Id = acl.BusinessServiceCategory_Id ";
		$Sql .= " LEFT JOIN Sweden_LanKommuner sl ON sl.Id = acl.Sweden_LanKommuner_Id ";
		$Sql .= " LEFT JOIN Files f ON f.Id = a.ImageFile_Id ";
		$Sql .= " WHERE a.Id = $AdId ";

		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		else if( 0 == mysql_num_rows( $result ) ){
			$ErrMsg = "Customer Advertisement Info not found.";
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );

		if( ! empty($row["Ad_Id"]) )
			$row["Checksum"] = CheckSum( $row["Ad_Id"] );

		if( ! empty($row["Account_Id"]) )
			$row["Account_IdChecksum"] = CheckSum( $row["Account_Id"] );

		if( ! empty( $row["ImageFile_Id"] ) )
			$row["AdImageUrl"] = "/libs/get_file.php?file=".$row["ImageFile_Id"].":" . CheckSum( $row["ImageFile_Id"] ) . "&disposition=inline&" . time();
		else
			$row["AdImageUrl"] = "/img/transparent_logo.png";

		header("Content-Type: application/json");
		print( json_encode( array_utf8_encode($row) ) );
		exit;
	}
	else if( "ajaxUpdateAdvertisementInfos" == $ajaxRequest ){
		idCheckSumSplit( $AdIdChecksum, $AdId, $CheckSum );
		if( ! VerifyCheckSum( $AdId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		idCheckSumSplit( $accountIdChecksum, $accountId, $CheckSum );
		if( ! VerifyCheckSum( $accountId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		idCheckSumSplit( $customerIdChecksum, $customerId, $CheckSum );
		if( ! VerifyCheckSum( $customerId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		//Sanity Check for Advertisement Id and Status
		//If Link is null then set its value to ""
		if( empty( $Status ) || $Status == "default" )
			$ErrMsg = $trans["pcust_nfo_ads_fill_fields"];
		else if( empty( $Link ) )
			$Link = "";
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		mysql_query( "BEGIN" );

		if( file_exists($ImageFilename) ){
			// get mime type
			$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $ImageFilename );

			// get file size
			$fileSize = filesize( $ImageFilename );

			$ext = '';
			if(!empty($ImageName)){
				$offset = strrpos($ImageName, ".");
				if( FALSE !== $offset) $ext = substr($ImageName, $offset);
			}

			if( empty($ImageFileId) ){
				//Save the File and get the file id to be used in updating the Advertisement Info
				$Sql = "INSERT INTO Files(Parent_Id, User_Id, Name, Type, Size, CreationDate) VALUES(0, $customerId, 'advertisementImage".$ext."', '".$mimeType."', ".$fileSize.", now())";
				mysql_query( $Sql );
				if( 0 != mysql_errno() ){
					mysql_query( "ROLLBACK" );
					header( "Status: 400 Insert Statement Error : " . __LINE__ . " SQL: " . $Sql );
					exit;
				}
				$ImageFileId = mysql_insert_id();
			}
			else{
				$Sql = "UPDATE Files SET Name='advertisementImage".$ext."',Type=\"$mimeType\",Size=$fileSize,CreationDate=now() WHERE Id = $ImageFileId LIMIT 1";
				mysql_query( $Sql );
				if( 0 != mysql_errno() ){
					mysql_query( "ROLLBACK" );
					header( "Status: 400 Query Error : " . __LINE__ );
					exit;
				}
			}

			//move the uploaded files to the appropriate directory
			$Destination = DATA_PATH."/accounts/$accountId";
			if(0 != $ImageFileId){
				if( file_exists($ImageFilename) ){
					rename($ImageFilename, "$Destination/$ImageFileId");
				}
			}
		}

		//Save changes to the selected advertisement
		$Sql = " UPDATE Ad SET Status = '".$Status."', Link = '".$Link."', ImageFile_Id = $ImageFileId, LastModified = now() WHERE Id = $AdId LIMIT 1 ";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			mysql_query( "ROLLBACK" );
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		mysql_query( "COMMIT" );

		//return a success message after saving
		$OkMsg = $trans["cust_nfo_ads_search_success_save"];

		print( $OkMsg );
		exit;
	}
	else if( "crossLoadCustomerAdvertisements" == $ajaxRequest ){
		idCheckSumSplit( $accountIdChecksum, $accountId, $CheckSum );
		if( ! VerifyCheckSum( $accountId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		idCheckSumSplit( $customerIdChecksum, $customerId, $CheckSum );
		if( ! VerifyCheckSum( $customerId, $CheckSum ) ){
			header("Status: 400 CheckSum Failed @ " . __LINE__ );
			exit;
		}

		if( empty($customerId) ){
			$ErrMsg = $trans["pcust_nfo_ads_customer"];
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		//paging variables
		$records     = isset($records) ? $records : 5;
		$page        = isset($page)    ? $page    : 1;
		$offset      = ($page - 1) * $records;

		$FromAdsPage = "YES";
		$CustomerAdsListHTML = generateCustomerAdsTableHTML( $customerId, $records, $page, $offset, $trans );
		ob_start();
		include( "inc_customer_info_ads_advertisement.html" );
		$CustomerAdsPageHTML = ob_get_contents();
		ob_end_clean();

		print( $CustomerAdsPageHTML );
		exit;
	}

	//-----------------------------------------------------------------
	//Start: Fall-through
	//-----------------------------------------------------------------
	$CustomerAdsListHTML = generateCustomerAdsTableHTML( $customerId, 5, 1, 0, $trans );
	//-----------------------------------------------------------------

	include( "inc_customer_info_ads_advertisement.html" );
	return;

?>