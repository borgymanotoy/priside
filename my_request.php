<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/my_request.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notes.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notification.php" );

	define('MAX_REPLIES_PER_INQUIRY', 4, false);

	$ErrMsg = "";
	$OkMsg  = "";


	if("loadRequest" == $ajaxRequest) {
		//========================================================================
		//=
		//= LOAD REQUEST
		//=   Loads and generates the html for a request.
		//=
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "loadRequest"             (string)
		//=   requestId            : (integer)
		//=   checksum             : Checksum of requestId and user id. (string)
		//========================================================================


		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if(!VerifyCheckSum(array($_SESSION['User']['Id']),$checksum)){
			$inputVariablesOK = false;
			$ErrMsg .= "CheckSum Error @ ".__LINE__;
		}

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		$sql = "
				SELECT
					U.Id AS Creator_Id,
					SR.Id AS Request_Id,
					SL.name AS Place,
					BSC.Category AS Service,
					SR.DateCreated AS Date,
					SR.Status AS Status,
					SR.Description AS Description,
					SR.ServiceIsFor AS Classification,
					SR.ServiceNeededWhen AS ServiceNeededWhen,
					SR.Project_Id AS Project_Id,
					Project.Name AS Project_Name,
					(SELECT COUNT(SRR.Id) FROM ServiceRequestReplies AS SRR WHERE SR.Id = SRR.ServiceRequest_Id) AS ReplyCount
				FROM
					User U INNER JOIN ServiceRequest SR ON U.Id = SR.User_Id
					INNER JOIN BusinessServiceCategory BSC ON SR.BusinessServiceCategory_Id = BSC.Id
					INNER JOIN Sweden_LanKommuner SL ON SR.Sweden_LanKommuner_Id = SL.Id
					LEFT OUTER JOIN Project ON SR.Project_Id = Project.Id
				WHERE
					U.Account_Id = ".$_SESSION['User']['AccountId']."
					AND SR.Id = ".$requestId;
		$result = mysql_query($sql);

		if(0 != mysql_errno()){
			$ErrMsg = "Query Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg ); // TODO: Fix error handling.
			exit(0);
		}

		ob_start(); // Output buffer to prevent immediate output.
		$rowNum = 0;
		$projects = array();
		$projectId = 0;
		while($request = mysql_fetch_assoc($result)){
			$request['Files'] = getInquiryFiles($request['Request_Id']);
			$rowNum+=1;
			// Write general request info (description, date etc
			$request['Service'] = utf8_encode($request['Service']);
			$request['Place'] = utf8_encode($request['Place']);
			include("inc_my_request_requestSnippet.html");
			$projectId = $request['Project_Id'];
		}
		mysql_free_result($result);
		$result_data_html = ob_get_contents(); // Get output buffer
		ob_end_clean();
		$returnData = json_encode(array(
			'ProjectId'=> $projectId,
			'html'=>$result_data_html
		));

		if(0 != $rowNum){
			print $returnData;
		}

		exit(0);
	} // End: loadRequest
	else if("loadReplies" == $ajaxRequest) {
		//========================================================================
		//=
		//= LOAD REPLIES
		//=   Loads and outputs html for the replies to a request.
		//=
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "loadReplies"             (string)
		//=   requestId            : (integer)
		//=   checksum             : Checksum of user id. (string)
		//========================================================================
		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if(!VerifyCheckSum(array($_SESSION['User']['Id']),$checksum)){
			$ErrMsg = "CheckSum Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		$sql_count = "
					SELECT
						COUNT(SRR.Id)
					FROM
						ServiceRequestReplies SRR
					WHERE
						SRR.ServiceRequest_Id = ".$requestId;
		$result    = mysql_query($sql_count);
		$count     = mysql_fetch_row($result);
		mysql_free_result($result);

		if ($count[0] == 0) {
			//return;
		}

		$sql = "
			SELECT
				Acc.Id AS Account_Id,
				Bus.Name AS Business_Name,
				Bus.City AS Business_City,
				CAST(AVG(1. * BR.Rating) AS DECIMAL(12,2)) AS Business_AvgRating,
				COUNT(BR.Id) AS Business_Ratings,
				SR.Id AS Request_Id,
				SR.Assigned_Account_Id AS Request_AssignedTo,
				SR.Status AS Request_Status
			FROM
				ServiceRequestReplies SRR,
				User U,
				Accounts Acc,
				ServiceRequest SR,
				Business Bus
				LEFT OUTER JOIN BusinessRatings BR ON BR.Business_Id = Bus.Id
			WHERE
				Bus.Id = Acc.Business_Id
				AND Acc.Id = U.Account_Id
				AND U.Id = SRR.Replier_Id
				AND SR.Id = ".$requestId."
				AND SRR.ServiceRequest_Id = ".$requestId."
			GROUP BY U.Account_Id";
		$result = mysql_query($sql);

		if(0 != mysql_errno()){
			$ErrMsg = "Query Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg ); // TODO: Fix error handling.
			exit(0);
		}

		ob_start(); // Output buffer to prevent immediate output.
		$rowNum = 0;
		$projects = array();
		while($row = mysql_fetch_assoc($result)){
			$rowNum+=1;
			outputReply($row);
		}
		mysql_free_result($result);
		$result_data_html = ob_get_contents(); // Get output buffer
		ob_end_clean();

		if(0 == $rowNum) {
			$result_data_html = "<div style=\"text-align:center;padding-top:5px;color:#E66313;font-weight:bold;\">{$trans["my_request_no_replies"]}</div>";
		}
		$result_data_html = utf8_encode($result_data_html);

		$returnArray = array(
			"replyCount" => $count[0],
			"html" => $result_data_html);

		header('Content-Type: application/json');
		print json_encode($returnArray);

		exit(0);
	} // End loadReplies
	else if("connectToProject" == $ajaxRequest) {
		//========================================================================
		//=
		//= CONNECT TO PROJECT
		//=   Connects a service request to a project.
		//=
		//=   If request was already connected to a project that connection will
		//=   be removed.
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "loadReplies"             (string)
		//=   requestId            : (integer)
		//=   projectId            : (integer)
		//=   checksum             : Checksum of user id. (string)
		//========================================================================
		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if (!isset($projectId) || !is_numeric($projectId) || $projectId< 1) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if(!VerifyCheckSum(array($_SESSION['User']['Id']),$checksum)){
			$inputVariablesOK = false;
			$ErrMsg .= "CheckSum Error @ ".__LINE__."<br />";
		}

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		// TODO: Verify that user is allowed to move the request to the project.
		//       Hm...multiple companies can't assign the request to their own projects...
		// When doing the update:
		//   * Make sure user is allowed to change project for request.
		//   * Make sure user has access to the project.
		$sql = "
			UPDATE
				ServiceRequest SR,
				User U,
				ProjectParticipantsGroup PPG
			SET
				SR.Project_Id = ".$projectId."
			WHERE
				SR.Id = ".$requestId."
				-- Make sure user has access to project
				AND PPG.User_Id = ".$_SESSION['User']['Id']."
				AND PPG.Project_Id = ".$projectId."
				AND PPG.Permissions IN ('extended', 'admin')";
		$result = mysql_query($sql);

		if(!$result) {
			$ErrMsg = "Query Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		if (0 == mysql_affected_rows()) {
			$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		exit(0);
	} // End connectToProject
	else if("disconnectFromProject" == $ajaxRequest) {
		//========================================================================
		//=
		//= DISCONNECT FROM PROJECT
		//=   Disconnects a service request from its project.
		//=
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "disconnectFromProject"             (string)
		//=   requestId            : (integer)
		//=   checksum             : Checksum of user id. (string)
		//========================================================================
		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if(!VerifyCheckSum(array($_SESSION['User']['Id']),$checksum)){
			$inputVariablesOK = false;
			$ErrMsg .= "CheckSum Error @ ".__LINE__."<br />";
		}

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		// TODO: Verify that user is allowed to move the request to the project.
		//       Hm...multiple companies can't assign the request to their own projects...
		// When doing the update:
		//   * Make sure user is allowed to change project for request.
		//   * Make sure user has access to the project.
		$sql = "
			UPDATE
				ServiceRequest SR,
				ProjectParticipantsGroup PPG
			SET
				SR.Project_Id = 0
			WHERE
				SR.Id = ".$requestId."
				-- Make sure user has required access to project
				AND PPG.User_Id = ".$_SESSION['User']['Id']."
				AND PPG.Project_Id = SR.Project_Id
				AND PPG.Permissions IN ('extended', 'admin')";
		$result = mysql_query($sql);

		if(!$result) {
			$ErrMsg = "Query Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		if (0 == mysql_affected_rows()) {
			$ErrMsg = "Database Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		exit(0);
	} // End disconnectFromProject
	else if("assignRequest" == $ajaxRequest) {
		//========================================================================
		//=
		//= ASSIGN REQUEST
		//=   Assigns a request to an accounts.
		//=
		//=   Account assigned to must have replied to the request, the current
		//=   user must be the owner of the request and the request must be in
		//=   state 'published'.
		//=
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "assignRequest"             (string)
		//=   requestId            : Database id of the request to assign.(integer)
		//=   accountId            : Account to assign the request to. (integer)
		//========================================================================
		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if (!isset($accountId) || !is_numeric($accountId) || $accountId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		// When doing the update:
		//   * Make sure user is owner of request.
		//   * Make sure request is in state 'published'.
		//   * Make sure account to assign to has replied to the request.

		// Table shortcuts:
		//   UR = User that replied to the request
		$sql = "
			UPDATE
				ServiceRequest SR,
				ServiceRequestReplies SRR,
				User UR
			SET
				SR.Assigned_Account_Id = ".$accountId.",
				SR.Status = 'assigned'
			WHERE
				SR.User_Id = ".$_SESSION['User']['Id']." AND
				SR.Status = 'published' AND
				SRR.ServiceRequest_Id = SR.Id AND
				SRR.Replier_Id = UR.Id AND
				UR.Account_Id = ".$accountId;

		$result = mysql_query($sql);

		if(!$result) {
			$ErrMsg = "Query Error @ ".__LINE__." ". mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		if (0 == mysql_affected_rows()) {
			$ErrMsg = "Assign Failed @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		exit(0);
	} // End assignRequest
	else if("deleteRequest" == $ajaxRequest) {
		//========================================================================
		//=
		//= DELETE REQUEST
		//=   Deletes a request.
		//=
		//=   User performing the delete must either be the creator of the request
		//=   or an account admin.
		//=
		//=   Files attached to the request will also be deleted (both from db
		//=   and file system).
		//=
		//=   Replies, conversations and their respective files will not be
		//=   deleted. This is according to spec.
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "deleteRequest"             (string)
		//=   requestId            : Database id of the request to delete.(integer)
		//========================================================================

		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}
		$requestId = (int) $requestId;

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();
		$result = mysql_query("BEGIN");

		// Steps for doing the delete:
		//   1. Make sure user is either creator of request or an admin of the
		//      same account as the user who created the request.
		//      Request's state doesn't matter.
		//   2. Get files attached to the request.
		//   3. Delete files from the db.
		//   4. Delete the request.
		//   5. Delete the request files from the filesystem.
		//   6. Commit the db changes.
		//   7. Done!
		//
		// If any step goes wrong, don't forget to rollback the database!
		//
		// TODO: Possible things to delete:
		//   * Replies to the request (and their files)
		//   * Conversations ONLY attached to the request (and not a project etc)
		//     and the files attached to those conversations.

		$filesToDelete = array(); // Array of file ids of the files we need to
		                          // delete when everything has been deleted in
		                          // the database.


		// === 1. Make sure user is either creator of request or admin...

		// If the following select returns nothing then we're not allowed to
		// delete the request.
		// Table shortcuts:
		//   SR = Service Request we want to delete.
		//   UC = User that Created the service request.
		//   UD = User that wants to Delete the service request.
		$sql = "
			SELECT SR.Id
			FROM
				ServiceRequest SR,
				User UC,
				User UD
			WHERE
				SR.Id = ".$requestId." AND
				UC.Id = SR.User_Id AND
				UD.Id = ".$_SESSION['User']['Id']." AND
				(UC.Id = ".$_SESSION['User']['Id']." OR
				(UC.Account_Id = UD.Account_ID AND
				 UD.Type = 'Admin'))";

		$result = mysql_query($sql);
		verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());

		verifyOrRollback(mysql_fetch_row($result), "Permission error @ ".__LINE__." ".mysql_error());
		mysql_free_result($result);

		// If we've reached here, we're allowed to delete.



		// === 2. Get files attached to the request.
		$sql = "
			SELECT
				SRF.File_Id AS File_Id
			FROM
				ServiceRequestFiles SRF
			WHERE
				SRF.ServiceRequest_Id = ".$requestId;

		$result = mysql_query($sql);
		verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());

		while ($row = mysql_fetch_assoc($result)) {
			$filesToDelete[] = $row['File_Id'];
		}


		// === 3. Delete files from the db.
		if (0 < count($filesToDelete)) {
			$fileIdsAsString = implode(",", $filesToDelete);
			$sql = "DELETE FROM ServiceRequestFiles WHERE File_Id IN (".$fileIdsAsString.")";
			$result = mysql_query($sql);
			verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());

			$sql = "DELETE FROM Files WHERE Id IN (".$fileIdsAsString.")";
			$result = mysql_query($sql);
			verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());

			$sql = "DELETE FROM FilesStats WHERE Id IN (".$fileIdsAsString.")";
			$result = mysql_query($sql);
			verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());
		}


		// === 4. Delete the request.
		$sql = "DELETE FROM ServiceRequest WHERE Id = ".$requestId;
		$result = mysql_query($sql);
		verifyOrRollback($result, "Query error @ ".__LINE__." ".mysql_error());

		verifyOrRollback(
			0 != mysql_affected_rows(),
			"Delete failed @ ".__LINE__." ".mysql_error());


		// === 5. Delete the request files from the filesystem.
		if (0 < count($filesToDelete)) {
			// Since only users with the same account as request creator can delete
			// the request we can use the session to get the account id under which
			// the files are stored.
			$dir = DATA_PATH.'/accounts/'.$_SESSION['User']['AccountId'].'/';
			foreach ($filesToDelete as &$fileId) {
				unlink($dir.$fileId);
			}
		}

		// === 6. Commit the db changes.
		$result = mysql_query("COMMIT");
		verifyOrRollback($result, "Commit error @ ".__LINE__." ".mysql_error());

		// === 7. Done!

		exit(0);

	} // End deleteRequest
	else if("inviteBusiness" == $ajaxRequest) {
		//========================================================================
		//=
		//= INVITE BUSINESS
		//=   Sends a notification to a business about a request.
		//=
		//=   The notification is an invitation from the request creator to a
		//=   business/supplier to the request. Whether it is sent as an email
		//=   and/or internal site message depends on the suppliers preferences.
		//=
		//=   Business not registered on the site can also be sent invites, in
		//=   which case an email will be sent to them (since internal messages
		//=   can't be sent to a non-registered user).
		//=
		//=   User performing the invite must either be the creator of the request
		//=   or an account admin.
		//=
		//= TODO: Safety check variable contents (decode etc)!
		//= TODO: If we get a PHP error caller seems to get a success response!
		//= ----------------------------------------------------------------------
		//= Parameters:
		//=   ajaxRequest          : "inviteBusiness"             (string)
		//=   requestId            : Database id of the request to delete.(integer)
		//=   businessName         : Name of the business to send to. Required!
		//=   businessEmail        : E-mail to send to. Required.
		//=   businessId           : Id of business user to send to. If business
		//=                          isn't registered on priside this parameter
		//=                          shall be left unset.
		//=                          Currently this parameter is ignored, but it
		//=                          is recommended to set it anyway to support
		//=                          future improvements in invitation sending
		//=                          such as sending the invitation as a message.
		//=   businessIdChecksum   : Server generated checksum.
		//========================================================================

		// Check user input variables
		$inputVariablesOK = true;
		$ErrMsg = "";
		$businessIdOK = false;

		if (!isset($requestId) || !is_numeric($requestId) || $requestId < 0) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}
		$requestId = (int) $requestId;

		if (isset($businessId) && is_numeric($businessId) && $businessId > 0) {
			$businessIdOK = true;
		}
		$businessId = (int) $businessId;
		if(!VerifyCheckSum($businessId ,$businessIdChecksum)){
			$inputVariablesOK = false;
			$ErrMsg .= "CheckSum Error @ ".__LINE__;
		}
		// For now we always send the invitation to the companies e-mail address
		// and don't make use of the notification libs abilities to automatically
		// send it as an e-mail and/or message depending on user preference.
		// The reason for doing this is that we don't know which user to send to.
		// How to select that user needs to be discussed first.
		// So we pretend we never got a businessId and thus will always just send
		// to the e-mail address instead.
		$businessIdOK = false;

		// If business id isn't set, then email needs to be.
		if (!$businessIdOK && (!isset($businessEmail) || 0 == strlen($businessEmail)) ) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}
		// Make sure we only send to one e-mail address.
		if (1 < substr_count($businessEmail, '@')) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}

		// $businessName could be used by spammers to send whatever text they want
		$businessNameLength = strlen($businessName);
		if (!isset($businessName) || $businessNameLength <= 0 || $businessNameLength > 255) {
			$inputVariablesOK = false;
			$ErrMsg .= "Error @ ".__LINE__."<br />";
		}
		$businessName = strip_tags($businessName);

		if(!$inputVariablesOK) {
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();
		// TODO: Verify that user is allowed to invite companies for this request!
		// We don't know which users are allowed to though...
		// But we can at least make sure that the inviting user belongs to the
		// same account as the request creator.
		// While we're reading the ServiceRequest for data, let's fetch category
		// and location from it as well.
		$sql = "
			SELECT
				BSC.Category AS Category,
				SLK.Name AS Sweden_LanKommuner
			FROM
				BusinessServiceCategory BSC,
				Sweden_LanKommuner SLK,
				Accounts A,
				ServiceRequest SR,
				User U
			WHERE
				SR.Id = ".$requestId." AND
				U.Id = SR.User_Id AND
				U.Account_Id = A.Id AND
				A.Id = ".$_SESSION['User']['AccountId']." AND
				BSC.Id = SR.BusinessServiceCategory_Id AND
				SLK.Id = SR.Sweden_LanKommuner_Id";
		$result = mysql_query($sql);
		if ( !$result ) {
			$ErrMsg = "Error @ ".__LINE__.mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}
		$row = '';
		if ( !$result || !($row = mysql_fetch_assoc($result)) ) {
			$ErrMsg = "Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		$category = $row['Category'];
		$location = $row['Sweden_LanKommuner'];

		// Setup template variables/labels.
		$templateVariables = array(
			"SERVERNAME" => $_SERVER['SERVER_NAME'],
			"Name" => $businessName, // Name for greeting receiver
			"Link" => $requestId,    // Link to the request. TODO: Generate this link.
			"Category" => $category,
			"Location" => $location
		);

		if ($businessIdOK) {
			// TODO: This code isn't implemented yet. See comment earlier in this
			// function.
		//	$sql = "SELECT ...";
		//	$receiverUserId = 138; // TODO: Find user to send to! 138 is Fredrik N
		//	$result = SendNotification(SUPPLIERINVITEDREQUEST, $templateVariables, $receiverUserId, $ErrMsg);
		//	if (!$result) {
		//		header("Status: 400 Sending notification failed. " . $ErrMsg );
		//		exit(0);
		//	}
		} else {
			$result = SendNotificationNonMember(SUPPLIERINVITEDREQUESTNOACCOUNT, $templateVariables, $businessEmail, $ErrMsg);
			if (!$result) {
				header("Status: 400 Sending notification failed. " . $ErrMsg );
				exit(0);
			}
		}

		exit(0);

	} // End inviteBusiness
	else if("ajaxGetEntrepreneurMail" == $ajaxRequest){
		if( empty( $EntrepreneurId ) ){
			$ErrMsg = $trans["pmy_request_entrepreneur_blank"];
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		// TODO: Should we use Business email or primary contact email?
		//       A business might not have a primary contact, though.
		$Sql = " SELECT Id, Email FROM Business WHERE Id = $EntrepreneurId ";

		db_connect();
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}
		else if( 0 == mysql_num_rows( $result ) ){
			$EntrepreneurInfos = array( "Id" => "N/A", "Checksum" => "N/A" );
			header( "Content-type: text/json" );
			print( json_encode( $EntrepreneurInfos ) );
		}

		$row = mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );

		$EntrepreneurInfos = array( "Id" => $row["Id"], "Checksum" => CheckSum( $row["Id"] ), "Email" => $row["Email"] );

		header( "Content-type: text/json" );
		print( json_encode( $EntrepreneurInfos ) );
		exit;
	} // End ajaxGetEntrepreneurMail

	// TODO: Move this function. It's a view function not a model/controller function.
	/**
	 * Outputs (prints HTML code) a row with information about a company that has
	 * replied to an inquiry.
	 */
	function outputReply($reply) {
		global $trans;
?>
						<div style="margin:1em 0 1em 0;">
							<div class="requestsHeader" style="background:#d9d3e0;">
								<div class="serviceheadercontainer_top_left">
									   <div class="serviceheadercontainer_top_right">
											  <table style="color:black;">
												<thead>
													<tr>
														<td style="width:265px;"><?=$reply['Business_Name']?></td>
														<td style="width:120px;"><?=$reply['Business_City']?></td>

														<td style="width:125px;">
														<?php
														// Write ratings by first writing the X icons representing the
														// achieved rating and then the 5-X icons that represent a non-achieved
														// rating.
														$avgRating = (int) round($reply['Business_AvgRating'], 0);
														for ($i=0; $i<$avgRating; $i++) {
															print '<img src="img/reputation.png" />';
														}
														// Non-achieved rating
														for ($i=$avgRating; $i<5; $i++) {
															print'<img src="img/reputation_off.png" />';
														}
														?>
														</td>
														<td>
														(<?=$reply['Business_Ratings']?> <?=$trans['my_request_rating_count']?>)
														</td>
													</tr>
												</thead>
											  </table>
										</div>
								</div>
							</div>
							<div class="servicecontainer-box-top">
								<div class="servicecontainer-box-top-br">
									<div class="servicecontainer-box-top-bl">
										<table>
											<tbody>
												<tr>
													<?php if (null == $reply['Request_AssignedTo'] && 0 == strcmp('published', $reply['Request_Status'])) : ?>
													<td class="bottom-left-rounded myreq_business_link clickable" onclick="assignRequest(<?=$reply['Request_Id']?>, <?=$reply['Account_Id']?>);">
														<?=$trans["my_request_assign"];?>
													</td>
													<?php elseif ($reply['Request_AssignedTo'] == $reply['Account_Id']): ?>
													<td>
														<?=$trans["my_request_assigned"];?>
													</td>
													<?php else : ?>
													<td>
														&nbsp;
													</td>
													<?php endif; ?>
													<td class="myreq_business_link clickable">
														<?=$trans["my_request_msg_center"];?>
													</td>
													<td class="myreq_business_link clickable">
														<?=$trans["my_request_see"];?>
													</td>
													<td class="myreq_business_link clickable">
														<?=$trans["my_request_add_review"];?>
													</td>
													<td class="bottom-right-rounded myreq_business_link clickable" onclick="LoadNote('myReqNote_', 'noteDialogContainer', <?=NOTETYPE_OTHERACCOUNT;?>, '<?=$reply['Account_Id'].":".CheckSum($reply['Account_Id']);?>' );">
														<?=$trans["my_request_write_down"];?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
<?php
	}


	/**
	 * Get an array of files attached to an inquiry.
	 *
	 * @param inquiryId  Id of the inquiry whos files we want.
	 *
	 * @return An array with each entry representing one file.
	 *         Each entry is an associative array of the form:
	 *         "name" => <name of file>,
	 *         "idAndChecksum" => <string of the form "<fileId>:<CheckSum(fileId)>",
	 *         e.g. "123:72347ca6234ca".
	 */
	function getInquiryFiles($inquiryId) {
		$sql = "
			SELECT
				F.Id AS Id,
				F.Name AS Name
			FROM ServiceRequestFiles SRF, Files F
			WHERE
				SRF.ServiceRequest_Id = ".$inquiryId." AND
				SRF.File_Id = F.Id";
		$result = mysql_query($sql);
		if (!$result) {
			return;
		}

		$files = array();
		while ($row = mysql_fetch_assoc($result)) {
			$files[] = array('name' => $row['Name'], 'idAndChecksum' => $row['Id'].':'.CheckSum($row['Id']));
		}

		mysql_free_result($result);
		return $files;
	}

	/**
	 * Verifies that $result isn't false.
	 *
	 * This is a utility function to keep the code a bit cleaner/shorter.
	 *
	 * If $result is false this function will do the following:
	 *   1. Perform a mysql_query("ROLLBACK").
	 *   2. Send a header with supplied error message.
	 *   3. exit(0).
	 * If $result isn't false this function does nothing.
	 *
	 * @param $message  Message to attach to the sent header if $result is false.
	 *                  Before this message "Status: 400" will be written.
	 */
	function verifyOrRollback($result, $message) {
		if (!$result) {
			mysql_query("ROLLBACK");
			header("Status: 400 " . $message );
			exit(0);
		}
	}

	//START: Population of organization combobox items
	//$sql : source query of the combobox list
	//$id_column : the column name where that will be used as ID of every list item
	//$name_column : the column name where the text of each list item will be based from.
	function GetComboboxItems($div_name, $span_name, $sql, $id_column, $name_column){
		//$sql = "select id, category from priside.BusinessServiceCategory where parent_id = 0 order by category";
		$result = mysql_query($sql);
		$list_html = "";
		while($row = mysql_fetch_assoc($result)){
			$list_html .= "<li id=\"org_".$row[$id_column]."\" value=\"".$row[$id_column]."\" onclick=\"displaySelectedItem('".$div_name."', '".$span_name."', '".$row[$name_column]."');\">".$row[$name_column]."</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
	}
	//END

	db_connect();

	include( "inc_my_request.html" ); 
	return;
?>
