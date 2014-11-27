<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/new_request.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_files.php" );

	$ErrMsg = "";
	$OkMsg  = "";

	//this is our upload path
	//since we will be uploading multiple files, we will dynamically set the flashvars
	$UploadPath         = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}/logins/{$_SESSION['User']['Id']}";
	$AttachmentFileName = "$UploadPath/requestAttachment";
	$SavePath           = base64_encode($AttachmentFileName).',CheckSum='.CheckSum(base64_encode($AttachmentFileName)).',Session='.session_id();

	if("renameUploadedFile" == $ajaxRequest){
		//@$fileId
		//since we are uploading multiple files, we need to rename the uploaded files
		if(file_exists($AttachmentFileName)){
			rename($AttachmentFileName,"$UploadPath/inquiryAttachment_$fileId");
		}
		exit(0);
	}
	else if("deleteUploadedFile"  == $ajaxRequest){
		//@$filename
		if(file_exists("$UploadPath/$filename")){
			unlink("$UploadPath/$filename");
		}
		exit(0);
	}
	else if("deleteSavedFile"  == $ajaxRequest){
		//========================================================================
		//=
		//= DELETE SAVED FILE
		//=   Deletes a file that was previously uploaded/saved to a request.
		//=
		//=   This differs from deleteUploadedFile in that it handles files that
		//=   were uploaded to requests that have been saved already.
		//=   deletedUploadedFile only handles files that were uploaded to a
		//=   request that is not yet saved, i.e. those files don't have entries
		//=   in the database etc.
		//=
		//= TODO: If we get a PHP error caller gets a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "deleteSavedFile"             (string)
		//=   fileId               : (integer)
		//========================================================================

		if (!isset($fileId) || !is_numeric($fileId)) {
			header("Status: 400 " . "Invalid file! @ ".__LINE__);
			exit(0);
		}
		$fileId = (int) $fileId;

		db_connect();

		// Make sure user is owner of this file.
		$sql = "
			SELECT Id
			FROM Files
			WHERE
				Id = ".$fileId." AND
				User_Id = ".$_SESSION['User']['Id']." AND
				Type <> 'DIR'";
		$result = mysql_query($sql);
		if (!$result) {
			header("Status: 400 " . "Database error! @ ".__LINE__);
			exit(0);
		}

		$row = mysql_fetch_assoc($result);
		if (!$row) {
			header("Status: 400 " . "Operation not allowed or file not found! @ ".__LINE__);
			exit(0);
		}

		// Delete file from database.
		$result = mysql_query("BEGIN");
		if (!$result) {
			header("Status: 400 " . "Database error! @ ".__LINE__);
			exit(0);
		}

		$sql = "DELETE FROM ServiceRequestFiles WHERE File_Id = ".$fileId;
		$result = mysql_query($sql);
		if (!$result) {
			mysql_query("ROLLBACK");
			header("Status: 400 " . "Database error! @ ".__LINE__);
			exit(0);
		}

		$sql = "DELETE FROM Files WHERE Id = ".$fileId;
		$result = mysql_query($sql);
		if (!$result) {
			mysql_query("ROLLBACK");
			header("Status: 400 " . "Database error! @ ".__LINE__);
			exit(0);
		}

		$result = mysql_query("COMMIT");
		if (!$result) {
			mysql_query("ROLLBACK");
			header("Status: 400 " . "Database error! @ ".__LINE__);
			exit(0);
		}
		// File deleted from database.

		// Delete file from file system.
		$dir = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}";
		$fullPath = $dir."/".$fileId;

		if(file_exists($fullPath)){
			unlink($fullPath);
		}
		// file deleted from file system.

		exit(0);
	}
	else if("createNewRequest" == $ajaxRequest){
		//========================================================================
		//=
		//= CREATE NEW REQUEST
		//=   Stores a new service request into the database.
		//=
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "createNewRequest"             (string)
		//=   categoryId           : (integer)
		//=   locationId           : Id for an existing entry in the Sweden_LanKommuner
		//=                          database table. (integer)
		//=   whomId
		//=   whenId
		//=   description          : Description of the service request. (string)
		//=   uploadedFiles        : filenames of the uploaded files. (format : FilenameInServer:RealFileName ex. inquiryAttachment1:mycondo.jpg)
		//========================================================================


		// Check user input variables
      $inputVariablesOK = true;
      $ErrMsg = "";
		$description = makeInputSafer($description);
		if(0 == strlen($description)){
         $inputVariablesOK = false;
			$ErrMsg .= $trans["new_request_mission_info"]."<br />";
		}

      if (!isset($categoryId) || !is_numeric($categoryId) || $categoryId < 0) {
         $inputVariablesOK = false;
         $ErrMsg .= $trans["new_request_category_info"]."<br />";
      }

		if	(!isset($locationId) || !is_numeric($locationId) || $locationId < 0) {
		 $inputVariablesOK = false;
		 $ErrMsg .= $trans["new_request_location_info"]."<br />";
		}

      if (!isset($whomId) || !is_numeric($whomId) || $whomId < 0) {
         $inputVariablesOK = false;
         $ErrMsg .= $trans["new_request_whom_info"]."<br />";
      }

      if (!isset($whenId)) {
         $inputVariablesOK = false;
         $ErrMsg .= $trans["new_request_when_info"]."<br />";
		}
		$whenId = makeInputSafer($whenId);

      if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
      }

		db_connect();
		mysql_query("BEGIN");

		$sql = "
			INSERT INTO ServiceRequest
				(User_Id, DateCreated, BusinessServiceCategory_Id, Sweden_LanKommuner_Id, Description,
				ServiceIsFor, ServiceNeededWhen, Status)
			VALUES
				(".$_SESSION['User']['Id'].", NOW(), ".$categoryId.",".$locationId.",'".$description."',
				".$whomId.", '".$whenId."', 'pending')";
		$result = mysql_query($sql);
		$serviceRequestId = mysql_insert_id();

		if(0 != mysql_errno()){
			mysql_query("ROLLBACK");
			$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		//insert the files to the Files table
		//@uploadedFiles
		if(!empty($uploadedFiles)){
			$fileIds = array();
			$startId = 0;
			$countFileIds = 0;
			$uploadedFiles = explode(",",$uploadedFiles);
			$sql = "INSERT INTO Files (Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES ";
			for($i = 0; $i < count($uploadedFiles); $i++){
				$filename = $uploadedFiles[$i];
				$filename = substr($filename,0,strpos($filename,":"));
				$file     = "$UploadPath/$filename";
				if(file_exists($file)){
					$filename      = utf8_decode(mysql_real_escape_string($uploadedFiles[$i]));
					$filename      = substr($filename,strpos($filename,":") + 1);
					// get mime type
					$mimeType      = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file);
					// get file size
					$fileSize      = filesize($file);
					$sql          .= "(0,{$_SESSION['User']['Id']},'$filename','$mimeType',$fileSize,NOW()),";
					$countFileIds++;
				}
			}
			$sql     = rtrim($sql,",");
			$result  = mysql_query($sql);
			$startId = mysql_insert_id();

			if(0 != mysql_errno()){
				mysql_query("ROLLBACK");
				$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit(0);
			}

			//insert the files to ServiceRequestFiles table
			$sql = "INSERT INTO ServiceRequestFiles (ServiceRequest_Id,File_Id) VALUES";
			for($f = $startId; $f < $countFileIds + $startId; $f++){
				$sql       .="($serviceRequestId,$f),";
				$fileIds[] = $f;
			}
			$sql     = rtrim($sql,",");
			$result  = mysql_query($sql);
			if(0 != mysql_errno()){
				mysql_query("ROLLBACK");
				$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
				header("Status: 400 " . $ErrMsg );
				exit(0);
			}

			//move all the uploaded files to the approriate directory
			$Destination = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}";
			for($i = 0; $i < count($uploadedFiles); $i++){
				$filename = $uploadedFiles[$i];
				$filename = substr($filename,0,strpos($filename,":"));
				$file     = "$UploadPath/$filename";
				if(file_exists($file)){
					rename($file,"$Destination/{$fileIds[$i]}");
				}
			}
		}
		mysql_query("COMMIT");
		PurgeFiles("inquiryAttachment_");
		exit(0);
	} // End: createNewRequest
	else if("editRequest" == $ajaxRequest) {
		//========================================================================
		//=
		//= EDIT REQUEST
		//=   Edits an existing request.
		//=
		//=   Requires the logged in user to be the original creator of the
		//=   request.
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "editRequest"             (string)
		//=   requestId            : Id of request to edit.
		//=   categoryId           : (integer)
		//=   locationId           : Id for an existing entry in the Sweden_LanKommuner
		//=                          database table. (integer)
		//=   whomId
		//=   whenId
		//=   description          : Description of the service request. (string)
		//=   uploadedFiles        : filenames of the uploaded files. (format : FilenameInServer:RealFileName ex. inquiryAttachment1:mycondo.jpg)
		//========================================================================


		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";
		db_connect(); // makeInputSafer() requires this.

		$description = makeInputSafer($description);
		if(0 == strlen($description)){
			$inputVariablesOK = false;
			$ErrMsg .= $trans["new_request_mission_info"]."<br />";
		}

		if (!isset($requestId) || !is_numeric($requestId) || $requestId< 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "<br />";
		}
		if (!isset($categoryId) || !is_numeric($categoryId) || $categoryId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= $trans["new_request_category_info"]."<br />";
		}

		if	(!isset($locationId) || !is_numeric($locationId) || $locationId < 0) {
		 $inputVariablesOK = false;
		 $ErrMsg .= $trans["new_request_location_info"]."<br />";
		}

		if (!isset($whomId) || !is_numeric($whomId) || $whomId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= $trans["new_request_whom_info"]."<br />";
		}

		if (!isset($whenId)) {
			$inputVariablesOK = false;
			$ErrMsg .= $trans["new_request_when_info"]."<br />";
		}
		$whenId = makeInputSafer($whenId);

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		mysql_query("BEGIN");

		// Before doing the edit:
		//   * Make sure user is either creator of request or an admin of the
		//     same account as the user who created the request.
		//   * Request's state can't be 'assigned' or 'completed'.

		// If the following select returns nothing then we're not allowed to
		// delete the request.
		// Table shortcuts:
		//   SR = Service Request we want to edit.
		//   UC = User that Created the service request.
		//   UD = User that wants to edit the service request.
		$sql = "
			SELECT SR.Id
			FROM
				ServiceRequest SR,
				User UC,
				User UD
			WHERE
				SR.Id = ".$requestId." AND
				SR.Status NOT IN ('assigned', 'completed') AND
				UC.Id = SR.User_Id AND
				UD.Id = ".$_SESSION['User']['Id']." AND
				(UC.Id = ".$_SESSION['User']['Id']." OR
				(UC.Account_Id = UD.Account_ID AND
				 UD.Type = 'Admin'))";

		$result = mysql_query($sql);
		if (!$result) {
			mysql_query("ROLLBACK");
			$ErrMsg = "Query Error @ ".__LINE__." ".mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		if (!mysql_fetch_row($result)) {
			mysql_query("ROLLBACK");
			$ErrMsg = "Permission Error @ ".__LINE__." ".mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		mysql_free_result($result);


		// If we've reached here, we've got the required permissions.
		$sql = "
			UPDATE ServiceRequest
			SET
				BusinessServiceCategory_Id = ".$categoryId.",
				Sweden_LanKommuner_Id = ".$locationId.",
				Description = '".$description."',
				ServiceIsFor = ".$whomId.",
				ServiceNeededWhen = '".$whenId."',
				LastModified = NOW()
			WHERE
				Id = ".$requestId;
		$result = mysql_query($sql);

		if(0 != mysql_errno()){
			mysql_query("ROLLBACK");
			$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// update the database tables related to files
		try {
			handleUploadedRequestFiles($uploadedFiles, $UploadPath, $requestId);
		}
		catch (Exception $e) {
			mysql_query("ROLLBACK");
			$ErrMsg = $e->getMessage();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		mysql_query("COMMIT");
		PurgeFiles("inquiryAttachment_");
		exit(0);
	} // End: editRequest



	/**
	 * Updates the database with info of files uploaded to a request.
	 *
	 * This function performs multiple database inserts so it is advised to setup
	 * a transaction before calling this function.
	 *
	 * @param uploadedFiles  A comma-separated list of strings representing
	 *                       uploaded files.
	 *                       The format for each file is
	 *                       <fileNameUsedOnServer>:<originalFileName>.
	 *                       filenameUsedOnServer is the name under which the
	 *                       file has currently been saved in the uploadPath.
	 *                       originalFileName is the "real" file name, e.g.
	 *                       my-house.jpg.
	 *                       May be empty.
	 * @param uploadPath     The destination directory where the uploaded files
	 *                       are placed.
	 *                       This function will move them from this directory
	 *                       to their correct location.
	 * @param requestId      Id of the request for which these files were uploaded.
	 *                       No checks will be made that the user is allowed to
	 *                       attach files to this request.
	 * @return   Nothing. If something goes wrong, an Exception will be thrown.
	 *           An empty uploadPath parameter is not consider an error and will
	 *           not cause an exception to be thrown. If it is empty or not set
	 *           this function will silently return.
	 */
	function handleUploadedRequestFiles($uploadedFiles, $uploadPath, $requestId) {
		if(!isset($uploadedFiles) || empty($uploadedFiles)) {
			return;
		}

		$fileIds = array();
		$startId = 0;
		$countFileIds = 0;
		$uploadedFiles = explode(",",$uploadedFiles);
		$sql = "INSERT INTO Files (Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES ";
		$nUploadedFiles = count($uploadedFiles);
		for($i = 0; $i < $nUploadedFiles; $i++) {
			$filename = $uploadedFiles[$i];
			$filename = substr($filename,0,strpos($filename,":"));
			$file     = "$uploadPath/$filename";
			if(file_exists($file)) {
				$filename      = utf8_decode(mysql_real_escape_string($uploadedFiles[$i]));
				$filename      = substr($filename,strpos($filename,":") + 1);
				// get mime type
				$mimeType      = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file);
				// get file size
				$fileSize      = filesize($file);
				$sql          .= "(0,{$_SESSION['User']['Id']},'$filename','$mimeType',$fileSize,NOW()),";
				$countFileIds++;
			}
		}
		$sql     = rtrim($sql,",");
		$result  = mysql_query($sql);
		$startId = mysql_insert_id();

		if(0 != mysql_errno()) {
			throw new Exception("Database Error @ ".__LINE__." ". mysql_error());
		}

		// insert the files to ServiceRequestFiles table
		$sql = "INSERT INTO ServiceRequestFiles (ServiceRequest_Id,File_Id) VALUES";
		for($f = $startId; $f < $countFileIds + $startId; $f++){
			$sql       .="($requestId, $f),";
			$fileIds[] = $f;
		}
		$sql     = rtrim($sql,",");
		$result  = mysql_query($sql);
		if(0 != mysql_errno()) {
			throw new Exception("Database Error @ ".__LINE__." ". mysql_error());
		}

		// move all the uploaded files to the appropriate directory
		$Destination = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}";
		for($i = 0; $i < count($uploadedFiles); $i++){
			$filename = $uploadedFiles[$i];
			$filename = substr($filename,0,strpos($filename,":"));
			$file     = "$uploadPath/$filename";
			if(file_exists($file)) {
				rename($file,"$Destination/{$fileIds[$i]}");
			}
		}
	} // End of handleUploadedRequestFiles()


	/**
	 * Makes a string input variable safer.
	 *
	 * Strips html tags, converts characters to html entities.
	 * Assumes encoding is UTF-8.
	 *
	 * Requires a database connection to have been established.
	 *
	 * @return The adjusted string. May be an empty string!
	 */
	function makeInputSafer($input) {
		$input = htmlentities($input, ENT_QUOTES, 'UTF-8');
		$input = strip_tags($input);
		//$input = mysql_real_escape_string($input); // TODO: Use this pathetic mysql/php code
		return $input;
	}



	db_connect();

	// === START: Edit request/inquiry handling.
	// If variable $editInquiryId is set, then user is trying to edit an
	// inquiry instead of creating a new one. Make sure user is allowed to do so.
	if (isset($editInquiryId)) {
		$editInquiryId = (int) $editInquiryId;
		$accessAllowed = false;

		// Table C = Creator
		$sql = "
			SELECT
				SR.Id,
				SR.Sweden_LanKommuner_Id,
				SR.BusinessServiceCategory_Id,
				SR.ServiceIsFor,
				SR.ServiceNeededWhen,
				SR.Description
			FROM ServiceRequest SR, User C
			WHERE SR.Id = ".$editInquiryId." AND
				SR.User_Id = C.Id AND
				C.Account_Id = ".$_SESSION['User']['AccountId'];
		$result = mysql_query($sql);
		if ($result && $oldInquiryValues = mysql_fetch_assoc($result)) {
			if ($oldInquiryValues  && $editInquiryId == $oldInquiryValues ['Id']) {
				$accessAllowed = true;
			}
		}

		if ($accessAllowed) {
			// Get the files attached to the inquiry.
			$sql = "
				SELECT
					F.Id AS File_Id,
					F.Name AS File_Name
				FROM Files F, ServiceRequestFiles SRF
				WHERE
					SRF.ServiceRequest_Id = ".$editInquiryId." AND
					F.Id = SRF.File_Id";
			$result = mysql_query($sql);
			$files = array();
			while ($file = mysql_fetch_assoc($result)) {
				$files[] = array(
					'File_Id' => $file['File_Id'],
					'File_Name' => $file['File_Name'],
					'File_IdAndChecksum' => $file['File_Id'].':'.CheckSum($file['File_Id']));
			}
			$oldInquiryValues['Files'] = $files;
		}
		if (!$accessAllowed) {
			unset($editInquiryId);
		}
	}
	// === END: Edit request/inquiry handling.

	include( "inc_new_request.html" );
	return;
?>
