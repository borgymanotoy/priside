<?php
	define( "MAX_LASTDOCUMENTS", 5, false );
	define( "MAX_LASTMESSAGES", 5, false );
	define( "MAX_MESSAGEBODY_SHOW_LEN", 46, false );
	define( "MAX_EVENTHOVER_SHOW_LEN", 50, false );

	//define x as date
	define( "NO_OF_DAYS_FROM_CURRENT_DAY", 5, false );

	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/project_overview.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/classifications.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notes.php" );

	$ErrMsg = "";
	$OkMsg = "";

	function populateProjectFileAttachmentsHTML( $ProjectId ){
		if( empty( $ProjectId ) ) array();

		$Sql  = " SELECT ";
		$Sql .= "     sr.Id as InquiryId, ";
		$Sql .= "     srf.File_Id, ";
		$Sql .= "     f.Name as FileName ";
		$Sql .= " FROM ServiceRequest sr ";
		$Sql .= " LEFT JOIN ServiceRequestFiles srf ON srf.ServiceRequest_Id = sr.Id ";
		$Sql .= " LEFT JOIN Files f ON f.Id = srf.File_Id ";
		$Sql .= " WHERE sr.Project_Id = $ProjectId ";
		$Sql .= " ORDER BY srf.File_Id ASC ";

		$result = mysql_query( $Sql );
		$ProjectInquiryAttachments = array();
		$result = mysql_query( $Sql );

		$InqId = 0;
		$Files = array();
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			if($InqId !=  $row["InquiryId"]){
				$InqId = $row["InquiryId"];
				$Files = array();
			}

			if( !empty($row["File_Id"]) && !empty($row["FileName"]) ) $Files[] = 	array( "FILE_ID" => $row["File_Id"], "FILE_NAME" => $row["FileName"] );

			$ProjectInquiryAttachments["$InqId"] = $Files;
		}
		mysql_free_result( $result );

		return $ProjectInquiryAttachments;
	}

//-------------------------------------------------------------------------------------------
// load the selected project
//
	if( "LoadProjectDetails" == $ajaxRequest ){

		idCheckSumSplit( $projectId, $pid, $checksum );
		if( ! VerifyCheckSum( $pid, $checksum ) ) {
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}

		if( empty( $pid ) ){
			include( "inc_project_overviewSnippet.html" );
			exit;
		}

		db_connect();

		// ----------------------------------------------------------------------
		// load project data...
		$Sql = "SELECT Id, Name,CreateDate,Description,Purpose,StartDate,EndDate FROM Project WHERE Id = $pid LIMIT 1";
		$result = mysql_query( $Sql );
		$Project = mysql_fetch_array( $result, MYSQL_ASSOC );
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load current events
		$Sql  = " SELECT a.User_Id, a.Private, a.Message, a.Date, a.StartTime, a.EndTime ";
		$Sql .= " FROM ( ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime  ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $pid and Private = 0 ";
		$Sql .= " 	UNION ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $pid and Private <> 0 and User_id = " . $_SESSION["User"]["Id"];
		$Sql .= " ) a ";
		$Sql .= " WHERE a.Date between now() and date_add( now(), INTERVAL ".NO_OF_DAYS_FROM_CURRENT_DAY." DAY ) ";
		$Sql .= " ORDER BY a.Date DESC, a.StartTime ASC ";

		$CurrentEvents = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$CurrentEvents[] = $row;
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load last messages
		$Sql = "SELECT M.Date AS MessageDate, M.Subject AS Subject,";
		$Sql.= "IF( LENGTH( M.Message ) > ".MAX_MESSAGEBODY_SHOW_LEN.", CONCAT_WS( '', SUBSTR( M.Message, 1, ".MAX_MESSAGEBODY_SHOW_LEN." ), '...' ), M.Message ) AS Message,";
		$Sql.= "CONCAT_WS( ' ',U.FirstName, U.LastName ) AS FromName,IFNULL( B.Name, '' ) AS BusinessName ";
		$Sql.= "FROM Message M LEFT JOIN User U ON ( U.Id = M.User_Id ) LEFT JOIN Accounts A ON ( A.Id = U.Account_Id ) LEFT JOIN Business B ON ( B.Id = A.Business_Id ) ";
		$Sql.= "WHERE M.Project_Id = $pid ORDER BY M.Date DESC LIMIT ".MAX_LASTMESSAGES;
		$LastMessages = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$LastMessages[] = $row;
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load the last two docs/files uploaded. (not dirs)
		$Sql = "SELECT F.Id AS Id, F.Name AS FileName, F.Size AS FileSize, F.CreationDate AS FileCreationDate, ";
		$Sql.= "CONCAT_WS( ' ',U.FirstName, U.LastName ) AS FileCreatedBy ";
		$Sql.= "FROM ProjectFiles PF, Files F LEFT JOIN User U ON ( U.Id = F.User_Id ) WHERE ( F.Id = PF.File_Id ) AND ";
		$Sql.= "( PF.Project_Id = $pid ) AND F.Type != 'DIR' ORDER BY F.CreationDate DESC LIMIT " . MAX_LASTDOCUMENTS;
		$LastUploadedFiles = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			$row['FileSize'] = BytesToHuman( $row['FileSize'] );
			$row['DownloadUrl'] = "/libs/get_file.php?file=" . $row['Id'] . ":" . CheckSum( $row['Id'] ) . "&disposition=attachment";
			$LastUploadedFiles[] = $row;
		}
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load project participants
		$Sql = "SELECT PPG.Id AS Id, CONCAT_WS( ' ', U.FirstName, U.LastName ) AS Name, IFNULL( B.Name, '' ) AS BusinessName, U.Position AS Position, DATE_FORMAT(U.LastLogin, '%Y-%m-%e') AS LastLogin ";
		$Sql.= "FROM ProjectParticipantsGroup PPG, User U LEFT JOIN Accounts A ON ( A.Id = U.Account_Id ) LEFT JOIN Business B ON ( B.Id = A.Business_Id ) ";
		$Sql.= "WHERE PPG.User_Id = U.Id AND PPG.Project_Id = $pid";
		$ProjectParticipants = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$ProjectParticipants[] = $row;
		mysql_free_result( $result );
		// ----------------------------------------------------------------------
		// load inquiries related to this project
		$Sql  = " select sr.id as ServiceRequestId, p.Name as Project_Name, concat(bsc.Category, ', ', sl.name) as Title, sr.Description as Mission_Statement, sr.ServiceIsfor, sr.ServiceNeededWhen, DATE_FORMAT(sr.DateCreated, '%Y-%m-%e') as RequestDate ";
		$Sql .= " from Project p inner join ServiceRequest sr on sr.Project_Id = p.Id inner join Sweden_LanKommuner sl on sr.Sweden_LanKommuner_Id = sl.Id inner join BusinessServiceCategory bsc on bsc.Id = sr.BusinessServiceCategory_Id ";
		$Sql .= " where p.Id = $pid ";
		$ProjectInquiries = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$ProjectInquiries[] = $row;
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load service requests file attachments for the selected project
		$ProjectFileAttachments = populateProjectFileAttachmentsHTML( $pid );
		// ----------------------------------------------------------------------
		include( "inc_project_overviewSnippet.html" ); 
		exit;
	}
//-------------------------------------------------------------------------------------------
// Create new project
//
	else if( "CreateNewProject" == $ajaxRequest ){
		$PageTitle = $trans['proj_overview_create_new_proj'];
		$Project = array();
		$ProjectParticipants = array();
		$ProjectInquiries = array();
		include( "inc_project_overviewEditSnippet.html" );
		exit;
	}
//-------------------------------------------------------------------------------------------
// edit the selected project
//
	else if( "EditProjectDetails" == $ajaxRequest ){
		idCheckSumSplit( $projectId, $pid, $checksum );
		if( ! VerifyCheckSum( $pid, $checksum ) ) {
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}

		$PageTitle = "Edit Project Information";
		db_connect();

		// ----------------------------------------------------------------------
		// load project data...
		$Sql = "SELECT Id, Name, CreateDate, Description, Purpose, DATE_FORMAT(StartDate, '%Y-%m-%e') as StartDate, DATE_FORMAT(EndDate, '%Y-%m-%e') as EndDate FROM Project WHERE Id = $pid LIMIT 1";
		$result = mysql_query( $Sql );
		$Project = mysql_fetch_array( $result, MYSQL_ASSOC );
		if( ! empty( $Project['Name'] ) ) $PageTitle = $Project['Name'];
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load project participants
		$Sql = "SELECT PPG.Id AS Id, CONCAT_WS( ' ', U.FirstName, U.LastName ) AS Name, IFNULL( B.Name, '' ) AS BusinessName, U.Position AS Position, PPG.Permissions, DATE_FORMAT(U.LastLogin, '%Y-%m-%e') AS LastLogin ";
		$Sql.= "FROM ProjectParticipantsGroup PPG, User U LEFT JOIN Accounts A ON ( A.Id = U.Account_Id ) LEFT JOIN Business B ON ( B.Id = A.Business_Id ) ";
		$Sql.= "WHERE PPG.User_Id = U.Id AND PPG.Project_Id = $pid";
		$ProjectParticipants = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$ProjectParticipants[] = $row;
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load inquiries related to this project
		$Sql  = " select sr.id as ServiceRequestId, p.Name as Project_Name, concat(bsc.Category, ', ', sl.name) as Title, sr.Description as Mission_Statement, sr.ServiceIsfor, sr.ServiceNeededWhen, DATE_FORMAT(sr.DateCreated, '%Y-%m-%e') as RequestDate ";
		$Sql .= " from Project p inner join ServiceRequest sr on sr.Project_Id = p.Id inner join Sweden_LanKommuner sl on sr.Sweden_LanKommuner_Id = sl.Id inner join BusinessServiceCategory bsc on bsc.Id = sr.BusinessServiceCategory_Id ";
		$Sql .= " where p.Id = $pid ";
		$ProjectInquiries = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$ProjectInquiries[] = $row;
		mysql_free_result( $result );

		// ----------------------------------------------------------------------
		// load service requests file attachments for the selected project
		$ProjectFileAttachments = populateProjectFileAttachmentsHTML( $pid );

		include( "inc_project_overviewEditSnippet.html" ); 
		exit;
	}
//-------------------------------------------------------------------------------------------
// ajax driver for calendar navigation (load current months events)
//
	else if( "getCalendarEvents" == $ajaxRequest ) {

		idCheckSumSplit( $projectId, $pid, $checksum );
		if( ! VerifyCheckSum( $pid, $checksum ) ) {
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}
		db_connect();

		if( ! is_numeric( $year ) || ! is_numeric( $month ) )
			return( array() );
			
		db_connect();
		$Sql  = " SELECT UNIX_TIMESTAMP( a.Date ),IF( LENGTH( a.Message ) > ".MAX_EVENTHOVER_SHOW_LEN.", CONCAT_WS( '', SUBSTR( a.Message, 1, ".MAX_EVENTHOVER_SHOW_LEN." ), '...' ), a.Message ) ";
		$Sql .= " FROM ( ";
		$Sql .= " 	SELECT Project_Id, Message, Date FROM CalendarEvent  ";
		$Sql .= " 	WHERE Project_Id = $pid and Private = 0 ";
		$Sql .= " 	UNION ";
		$Sql .= " 	SELECT Project_Id, Message, Date FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $pid and Private <> 0 and User_id = " . $_SESSION["User"]["Id"];
		$Sql .= " ) a ";
		$Sql .= " WHERE a.Project_Id = $pid AND a.Date BETWEEN '$year-$month-00' AND '$year-$month-31' ";

		$CalendarEvents = array();
		$result = mysql_query( $Sql );
		while($row = mysql_fetch_row( $result ) )
			$CalendarEvents[$row[0]] = array( "Message"=>$row[1] );
		mysql_free_result( $result );
		header( "Content-type: text/json" );
		print( json_encode( $CalendarEvents ) );
		exit;
	}
	//
	// load events for selected day
	//
	else if( "getCalendarEventDay" == $ajaxRequest ){
		idCheckSumSplit( $projectId, $pid, $checksum );
		if( ! VerifyCheckSum( $pid, $checksum ) ) {
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}

		db_connect();

		//current (all public and self owned private for this date)
		$Sql  = " SELECT a.User_Id, a.Private, a.Message, a.Date, a.StartTime, a.EndTime ";
		$Sql .= " FROM ( ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime  ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $pid and Private = 0 ";
		$Sql .= " 	UNION ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $pid and Private <> 0 and User_id = " . $_SESSION["User"]["Id"];
		$Sql .= " ) a ";
		$Sql .= " WHERE a.Date = '$date' ";
		$Sql .= " ORDER BY a.StartTime ASC ";

		$CurrentEvents = array();
		$result = mysql_query( $Sql );
		while( $Event = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			echo "<strong>";
			if( 0 != $Event['Private'] )
					echo $trans["proj_overview_private"];
				else
					echo $trans["proj_overview_public"];
			echo "</strong><br />{$Event['StartTime']} - {$Event['EndTime']}<br />{$Event['Message']}<br />";
		}
		mysql_free_result( $result );
		exit;
	}
	else if( "ajaxSaveProjectInfoChanges" == $ajaxRequest ){

		/*
			"ajaxRequest" 		: "ajaxSaveProjectInfoChanges",
			"ProjectIdChecksum"	: $("#ProjectId").val() + ":" + $("#ProjectChecksum").val(),
			"Name" 				: $("#txt_project_name").val(),
			"Description" 		: $("#txt_project_description").val(),
			"Purpose"	 		: $("#txt_project_purpose").val(),
			"StartDate"	 		: $("#txt_project_start_date").val(),
			"EndDate"	 		: $("#txt_project_end_date").val()
		*/

		//$ProjectIdChecksum  = $ProjectId and $ProjectChecksum
		idCheckSumSplit( $ProjectIdChecksum, $ProjectId, $ProjectChecksum );
		if( ! empty( $ProjectId ) && ! empty( $ProjectChecksum ) && ! VerifyCheckSum( $ProjectId, $ProjectChecksum ) ){
			$ErrMsg = "Project Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		if( empty( $Name ) ){
			$ErrMsg = $trans["pcreate_edit_project_name_blank"];
		}
		else if( empty( $Description ) ){
			$ErrMsg = $trans["pcreate_edit_project_description_blank"];
		}
		else if( empty( $Purpose ) ){
			$ErrMsg = $trans["pcreate_edit_project_purpose_blank"];
		}
		else if( empty( $StartDate ) || empty( $EndDate ) ){
			$ErrMsg = $trans["pcreate_edit_project_date_inavlid"];
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		db_connect();

		$Name = mysql_real_escape_string( utf8_decode( $Name ) );
		$Description = mysql_real_escape_string( utf8_decode( $Description ) );
		$Purpose = mysql_real_escape_string( utf8_decode( $Purpose ) );

		if( empty( $ProjectId ) ){
			$Sql = "INSERT INTO Project(Account_Id, User_Id, Name, Description, Purpose, StartDate, EndDate, CreateDate, LastModified) VALUES (".$_SESSION["User"]["AccountId"].",".$_SESSION['User']['Id'].",'".$Name."', '".$Description."', '".$Purpose."', STR_TO_DATE('".$StartDate."','%Y-%m-%e'), STR_TO_DATE('".$EndDate."','%Y-%m-%e'), now(), now())";
			//Display success msg here
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Insert statement Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$ProjectId = mysql_insert_id();

			//It should also add automatically the creator to the project participant group
			$Sql = "INSERT INTO ProjectParticipantsGroup( Project_Id, User_Id, Permissions, DateCreated, LastModified) VALUES (".$ProjectId.", ".$_SESSION["User"]["Id"].", 'admin', now(), now())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Insert statement Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
				header("Status: 400 " . $ErrMsg );
				exit;
			}
			$OkMsg = utf8_decode($trans["create_edit_proj_save_success_msg"]);
		}
		else{
			//Update statement here
			$Sql = " UPDATE Project SET ";
			$Sql .= " 	Name = '".$Name."', ";
			$Sql .= " 	Description = '".$Description."', ";
			$Sql .= " 	Purpose = '".$Purpose."', ";
			$Sql .= " 	StartDate = STR_TO_DATE('".$StartDate."','%Y-%m-%e'), ";
			$Sql .= " 	EndDate = STR_TO_DATE('".$EndDate."','%Y-%m-%e'), ";
			$Sql .= " 	LastModified = now() ";
			$Sql .= " WHERE Id = $ProjectId LIMIT 1";
			//Display success msg here
			$OkMsg = utf8_decode($trans["create_edit_proj_update_success_msg"]);
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				$ErrMsg = "Update statement Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
				header("Status: 400 " . $ErrMsg );
				exit;
			}
		}

		$ProjectInfos = array( "ProjectId" =>  $ProjectId, "ProjectChecksum" => CheckSum(  $ProjectId ), "ProjectStatusMsg" => "(Success) ".$OkMsg );

		//Done
		print( json_encode( $ProjectInfos ) );
		exit;
	}
	else if( "ajaxSaveCalendarEvent" == $ajaxRequest ){

		/*
			"ajaxRequest" 		: "ajaxSaveCalendarEvent",
			"ProjectIdChecksum"	: $("#hProjectId").val() + ":" + $("#hProjectChecksum").val(),
			"IsPrivate"			: getEventType(),
			"Date" 				: $("#event_date").val(),
			"StartTime" 		: $("#event_start_time").val(),
			"EndTime"	 		: $("#event_end_time").val(),
			"Message"	 		: $("#event_msg").val()
		*/

		//$ProjectIdChecksum  = $ProjectId and $ProjectChecksum
		idCheckSumSplit( $ProjectIdChecksum, $ProjectId, $ProjectChecksum );
		if( ! empty( $ProjectId ) && ! empty( $ProjectChecksum ) && ! VerifyCheckSum( $ProjectId, $ProjectChecksum ) ){
			$ErrMsg = "Project Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Sanity Test (Date, StartTime, EndTime, Message should not be blank)
		if( empty( $ProjectId ) ){
			$ErrMsg = $trans["pcreate_edit_project_id_blank"];
		}
		else if( empty( $IsPrivate ) ){
			$ErrMsg = $trans["pcreate_edit_event_type_blank"];
		}
		else if( empty( $Date ) ){
			$ErrMsg = $trans["pcreate_edit_event_date_blank"];
		}
		else if( empty( $StartTime ) || empty( $EndTime ) ){
			$ErrMsg = $trans["pcreate_edit_event_time_blank"];
		}
		else if( empty( $Msg ) ){
			$ErrMsg = $trans["pcreate_edit_event_message_blank"];
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		db_connect();

		$Date = mysql_real_escape_string( utf8_decode( $Date ) );
		$StartTime = mysql_real_escape_string( utf8_decode( $StartTime ) );
		$EndTime = mysql_real_escape_string( utf8_decode( $EndTime ) );
		$Msg = mysql_real_escape_string( utf8_decode( $Msg ) );

		//Insert the record
		$Sql = "INSERT INTO CalendarEvent( User_Id, Project_Id, Private, Message, Date, StartTime, EndTime, DateCreated, LastModified ) VALUES (".$_SESSION["User"]["Id"].", $ProjectId, $IsPrivate, '".$Msg."', STR_TO_DATE('".$Date."','%Y-%m-%e'), STR_TO_DATE('".$StartTime."','%H:%i'), STR_TO_DATE('".$EndTime."','%H:%i'), now(), now())";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Insert statement Error @ " . __LINE__ . " : " . mysql_error() . " SQL: " . $Sql;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Set success message
		$OkMsg = $trans["create_edit_event_save_success_msg"];

		//Done and Exit
		print( $OkMsg );
		exit;
	}
	else if( "ajaxReloadCalendarEvents" == $ajaxRequest ){
		//$ProjectIdChecksum  = $ProjectId and $ProjectChecksum
		idCheckSumSplit( $ProjectIdChecksum, $ProjectId, $ProjectChecksum );
		if( ! empty( $ProjectId ) && ! empty( $ProjectChecksum ) && ! VerifyCheckSum( $ProjectId, $ProjectChecksum ) ){
			$ErrMsg = "Project Info CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		//Sanity Test (Date, StartTime, EndTime, Message should not be blank)
		if( empty( $ProjectId ) ){
			$ErrMsg = $trans["pcreate_edit_project_id_blank"];
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}

		db_connect();

		// ----------------------------------------------------------------------
		// load current events
		$Sql  = " SELECT a.User_Id, a.Private, a.Message, a.Date, a.StartTime, a.EndTime ";
		$Sql .= " FROM ( ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime  ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $ProjectId and Private = 0 ";
		$Sql .= " 	UNION ";
		$Sql .= " 	SELECT User_Id, Private, Message, Date, DATE_FORMAT( StartTime, '%k:%i') AS StartTime, DATE_FORMAT( EndTime, '%k:%i') AS EndTime ";
		$Sql .= " 	FROM CalendarEvent ";
		$Sql .= " 	WHERE Project_Id = $ProjectId and Private <> 0 and User_id = " . $_SESSION["User"]["Id"];
		$Sql .= " ) a ";
		$Sql .= " WHERE a.Date between now() and date_add( now(), INTERVAL ".NO_OF_DAYS_FROM_CURRENT_DAY." DAY ) ";
		$Sql .= " ORDER BY a.Date DESC, a.StartTime ASC ";

		$CurrentEvents = array();
		$result = mysql_query( $Sql );
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
			$CurrentEvents[] = $row;
		mysql_free_result( $result );

		ob_start();
		include( "inc_project_overviewCalendarEventsSnippet.html" );
		$CalendarEventsHTML = ob_get_contents();
		ob_end_clean();

		print( $CalendarEventsHTML );
		exit;
	}

// -----------------------------------------------------------------------------------------------------------------------
// functions
// -----------------------------------------------------------------------------------------------------------------------
// BytesToHuman - return human readable string for input of $bytes
// given 1024 will return 1 KB, 1024000 will return 1 MB ...
// params:
// bytes - number of bytes
function BytesToHuman( $bytes ){
	$kb = 1024;
	$mb = $kb * 1024;
	$gb = $mb * 1024;

	if( $bytes >= 0 && $bytes < $kb )
		return( $bytes . " B" );
	else if( $bytes >= $kb && $bytes < $mb )
		return( ceil( $bytes / $kb ) . " KB" );
	else if( $bytes >= $mb && $bytes < $gb )
		return( ceil( $bytes / $mb ) . " MB" );
	else if( $bytes >= $gb )
		return( ceil( $bytes / $gb ) . " GB" );
	return( $bytes );
}
//-------------------------------------------------------------------------------------------
//
// GetComboboxProjectItems - returns a list of Project names for the combo box control.
// Params: 
// div_name - the div name that holds the combo box control
// span_name - the span that displays the current selection
// returns a string in the format of:
// <li id="org_1" onclick="displaySelectedItem('div_doc_file_location', 'spn_doc_file_location', 'ProjectName');LoadProjectFiles( Id, 'CheckSum' );" value="Id">Project Name</li>
//
function GetComboboxProjectItems( $div_name, $span_name ){
	$Sql = "SELECT P.Id AS Id, P.Name AS Name FROM Project P, ProjectParticipantsGroup PPG WHERE P.Id = PPG.Project_Id AND PPG.User_Id = {$_SESSION['User']['Id']} AND ";
	$Sql.= "P.Account_Id = {$_SESSION['User']['AccountId']} AND CURDATE() <= EndDate LIMIT 100";
	$result = mysql_query( $Sql );
	$list_html = "";
	while($row = mysql_fetch_row($result)){
		$list_html .= "<li id=\"org_".$row[0]."\" value=\"".$row[0]."\" onclick=\"displaySelectedItem('".$div_name."', '".$span_name."', '".$row[1]."');LoadProjectDetails( '$row[0]:".CheckSum( $row[0] )."' );\">".$row[1]."</li>\n";
	}
	mysql_free_result( $result );
	return $list_html;
}
//-------------------------------------------------------------------------------------------

	db_connect();
	include( "inc_project_overview.html" );
	return;
?>