<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/message_center.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notes.php" );
	
	/*
		"ajaxRequest"      : method           (string) //the method we want to execute
		"filenameInServer" : filenameInServer (string) //the actual filename of the file in the server
 	*/
	//this is our save path for our uploads
	$UploadPath         = DATA_PATH."/accounts/{$_SESSION['User']['AccountId']}/logins/{$_SESSION['User']['Id']}";
	$MessageAttachment  = "$UploadPath/messageCenterAttachment"; 
	$SavePathAttachment = base64_encode($MessageAttachment).',CheckSum='.CheckSum(base64_encode($MessageAttachment)).',Session='.session_id();
	
	db_connect();
	
	if("renameAttachment" == $ajaxRequest){
		if(file_exists($MessageAttachment)){
			//we need to rename the file because there are multiple conversations
			rename($MessageAttachment,"$UploadPath/$filenameInServer");
		}
		exit(0);
	}
	else if("deleteAttachment" == $ajaxRequest){
		if(file_exists("$UploadPath/$filenameInServer")){
			unlink("$UploadPath/$filenameInServer");
		}
		exit(0);
	}
	else if("getContactInfo" == $ajaxRequest){
		if("null" !== $projectId){
			$mainTable = "Project P INNER JOIN User U ON P.User_Id = U.Id";
			$condition = "P.Id = $projectId";
		}
		else if("null" !== $inquiryId){
			$mainTable = "ServiceRequest SR INNER JOIN User U ON SR.User_Id = U.Id";
			$condition = "SR.Id = $inquiryId";
		}
		else if("null" !== $contactId){
			$mainTable = "User U";
			$condition = "U.Id = $contactId";
		}
		$sql    = "
					SELECT 
					   B.Name AS ConpanyName,
					   CONCAT_WS(' ',U.FirstName,U.LastName) AS ContactName,
					   U.Phone AS ContactMobile,
					   U.Email AS ContactEmail
					FROM
					  $mainTable
					  INNER JOIN Accounts A ON A.Id = U.Account_Id
					  INNER JOIN Business B ON B.Id = A.Business_Id 
					WHERE
					  $condition
				  ";
		$result = mysql_query($sql);
		$row    = mysql_fetch_assoc($result);
		mysql_free_result($result);
		header("Content-Type: application/json");
		die(json_encode(array_utf8_encode($row)));
	}
	else if("getProjectDetails" == $ajaxRequest){
		//we need this to access the global variable $trans
		global $trans;
		$sql = "
				SELECT
					P.Id AS ProjectViewProject,
					P.Name AS ProjectName,
					CONCAT_WS(' - ',DATE_FORMAT(P.StartDate,'%Y-%m-%d'),DATE_FORMAT(P.EndDate,'%Y-%m-%d')) AS ProjectDuration,
					CONCAT_WS('<br>','<b>{$trans["message_center_project_description"]}</b>',P.Description) AS ProjectDescription,
					CONCAT_WS('<br>','<b>{$trans["message_center_project_purpose"]}</b>',P.Purpose,'<br>') AS ProjectPurpose
				FROM
					Project P
				WHERE
					P.Id = $id
			   ";
		$result = mysql_query($sql);
		$row    = mysql_fetch_assoc($result);
		mysql_free_result($result);
		
		//hook it to the javascript function
		$projectIdCheckSum         = $row["ProjectViewProject"].":".CheckSum($row["ProjectViewProject"]);
		$row["ProjectViewProject"] = "<a style=\"text-decoration:none;\" href=\"javascript:void(0)\" onclick=\"messageCenterGoToProject('$projectIdCheckSum','{$row["ProjectName"]}')\">{$trans["message_center_project_view_project"]}</a>";
		
		header("Content-Type: application/json");
		die(json_encode(array_utf8_encode($row)));
	}
	else if("getInquiryDetails" == $ajaxRequest){
		//we need this to access the global variable $trans
		global $trans;
		$sql = "
				SELECT
				  CONCAT_WS(', ',BSC.Category,SLK.Name) AS InquiryCategoryLocation,
				  DATE_FORMAT(SR.DateCreated,'%Y-%m-%d') AS InquiryCreationDate,
				  CONCAT_WS('<br>','<b>{$trans["message_center_inquiry_mission_statement"]}</b>',SR.Description) AS InquiryMissionStatement,
				  GROUP_CONCAT(DISTINCT CONCAT_WS(':',F.Id,F.Name)) AS InquiryFiles,
				  SR.ServiceIsFor AS InquiryPerformedAt,
				  SR.Id AS InquiryRelatedNote,
				  SR.Status AS InquiryStatus,
				  P.Id AS ProjectId,
				  P.Name As ProjectName,
				  CASE WHEN P.Id IN (SELECT Project_Id FROM ProjectParticipantsGroup P WHERE User_Id = ".$_SESSION["User"]["Id"].") THEN 1 ELSE 0 END AS HasAccess
				FROM
				  ServiceRequest SR INNER JOIN BusinessServiceCategory BSC ON BSC.Id = SR.BusinessServiceCategory_Id
				  INNER JOIN Sweden_LanKommuner SLK ON SLK.Id = SR.Sweden_LanKommuner_Id
				  LEFT JOIN ServiceRequestFiles SRF ON SRF.ServiceRequest_Id = SR.Id
				  LEFT JOIN Files F ON F.Id = SRF.File_Id
				  LEFT JOIN Project P ON P.Id = SR.Project_Id
				WHERE
				  SR.Id = $id
				GROUP BY
				  SR.Id;
			   ";
		$result = mysql_query($sql);
		$row    = mysql_fetch_assoc($result);
		mysql_free_result($result);
		
		//put the attachments in an array
		$attachments = strlen($row["InquiryFiles"]) > 0 ? explode(",",$row["InquiryFiles"]) : null;
		$files       = array();
		
		if(count($attachments ) > 0){
			foreach($attachments as $file){
				$file_id  = substr($file,0,strpos($file,":"));
				$filename = substr($file,strlen($file_id) + 1);
				$url      = "/libs/get_file.php?file=".($file_id.":".CheckSum($file_id))."&disposition=inline";
				$files [] = array("url" => $url, "filename" => $filename);
			}
			//updated the InquiryFiles column
			if(count($files) > 0){
				$row["InquiryFiles"] = array_utf8_encode($files);
			}
		}
		else{
			$row["InquiryFiles"] = null;
		}
		
		//set ServiceIsFor to the correct value
		$row["InquiryPerformedAt"] = "<b>{$trans["message_center_inquiry_performed_at"]}</b> ".GetClassication($row["InquiryPerformedAt"])."<br><br>";
		
		//create a handler for the notes
		$row["InquiryRelatedNote"] = "<a style=\"text-decoration:none;color:#654985\" href=\"javascript:void(0)\" onclick=\"LoadNote('messageCenter_', 'noteDialogContainer',".NOTETYPE_MESSAGECENTERINQUIRY.",'".$row["InquiryRelatedNote"].":".CheckSum($row["InquiryRelatedNote"])."')\">{$trans["message_center_inquiry_make_a_note"]}</a>";
		
		//hook it to the javascript function
		if(!empty($row["ProjectId"])){
			if($row["HasAccess"] == 1){
				$projectIdCheckSum            = $row["ProjectId"].":".CheckSum($row["ProjectId"]);
				$row["InquiryRelatedProject"] = "<a style=\"text-decoration:none;\" href=\"javascript:void(0)\" onclick=\"messageCenterGoToProject('$projectIdCheckSum','{$row["ProjectName"]}')\">{$trans["message_center_project_view_project"]}</a>";
			}
			else{
				$row["InquiryRelatedProject"] = "<a style=\"text-decoration:none;\" href=\"javascript:void(0)\">{$trans["message_center_inquiry_related_project"]} {$row["ProjectName"]}</a>";
			}
		}
		else{
			$row["InquiryRelatedProject"] = "<a style=\"text-decoration:none;\" href=\"javascript:void(0)\">Inquiry is not associated to a project</a>";
		}
		header("Content-Type: application/json");
		die(json_encode(array_utf8_encode($row)));
	}
	
	function GetContacts(){
		//we need this to access the global variable $trans
		global $trans;
		$sql = "
				SELECT
				  U.Id,
				  CONCAT_WS(' ',U.FirstName,U.LastName) AS Name
				FROM
				  MessageContacts MS INNER JOIN User U ON MS.cUser_Id = U.Id
				WHERE
				  MS.oUser_Id = ".$_SESSION['User']['Id']."
				ORDER BY
				  U.LastName ASC
			   ";
		$result = mysql_query($sql);
		$list_html = "<li style=\"cursor:pointer;\" id=\"divContact-1\" value=\"All Contacts\" onclick=\"displaySelectedItem('div_sel_contact', 'spn_sel_contact', '".$trans["message_center_select_contact"]."');filterByContact(this)\">All Contacts</li>\n";
		while($row = mysql_fetch_assoc($result)){
			$list_html .= "<li style=\"cursor:pointer;\" id=\"divContact_".$row["Id"]."\" value=\"".$row["Name"]."\" onclick=\"displaySelectedItem('div_sel_contact', 'spn_sel_contact', '".$row["Name"]."');filterByContact(this)\">".$row["Name"]."</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
	}
	
	function GetProjects(){
		//we need this to access the global variable $trans
		global $trans;
		$sql = "
				SELECT DISTINCT
				  P.Id,
				  P.Name AS Description
				FROM
				  Project P INNER JOIN ProjectParticipantsGroup PPG ON P.Id = PPG.Project_Id
				WHERE 
					PPG.User_Id = ".$_SESSION['User']['Id']." 
				ORDER BY
				  Description ASC	
			   ";
		$result = mysql_query($sql);
		$list_html = "<li style=\"cursor:pointer;\" id=\"divProject-1\" value=\"All Conversations\" onclick=\"displaySelectedItem('div_project', 'spn_project', '".$trans["message_center_select_project"]."');filterByProject(this)\">All Projects</li>\n";
		while($row = mysql_fetch_assoc($result)){
			$list_html .= "<li style=\"cursor:pointer;\" id=\"divProject_".$row["Id"]."\" value=\"".$row["Description"]."\" onclick=\"displaySelectedItem('div_project', 'spn_project', '".$row["Description"]."');filterByProject(this)\">".$row["Description"]."</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
	}
	
	function GetInquiries(){
		//we need this to access the global variable $trans
		global $trans;
		// Table shortcuts:
		//   UC = User that Created the inquiry
		//   UR = User that Replied to the inquiry
		$sql = "
				SELECT
				 *
				FROM (
					 SELECT
					  SR.Project_Id,
					  SR.Id,
							  SR.Description
							FROM
							  ServiceRequest SR
							WHERE
					  SR.Id IN
								(SELECT SRR.ServiceRequest_Id FROM ServiceRequestReplies SRR, User UR
								 WHERE UR.Id = SRR.Replier_Id AND UR.Account_Id = ".$_SESSION['User']['AccountId'].")
							  OR
					  SR.User_Id IN
								(SELECT SR.User_Id
								 FROM ServiceRequest SR, User UC
								 WHERE SR.User_Id = UC.Id AND UC.Account_Id = ".$_SESSION['User']['AccountId'].")
					  ORDER BY SR.Description
					) AS SR
				";
		$result = mysql_query($sql);
		$list_html = "<li style=\"cursor:pointer;\" id=\"divInquiry-1\" value=\"All Conversations\" onclick=\"displaySelectedItem('div_inquiry', 'spn_inquiry', '".$trans["message_center_select_request"]."');filterByInquiry(this)\">All Requests</li>\n";
		while($row = mysql_fetch_assoc($result)){
			$description = str_replace("\r","",str_replace("\n","",htmlentities($row["Description"],ENT_QUOTES)));
			$list_html .= "<li style=\"cursor:pointer;\" id=\"divInquiry_".$row["Id"]."\" value=\"$description\" onclick=\"displaySelectedItem('div_inquiry', 'spn_inquiry', '$description');filterByInquiry(this)\">$description</li>\n";
		}
		mysql_free_result( $result );
		return $list_html;
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
	
	//will convert the IsServiceFor to the correct enum value
	function GetClassication($key){
		//get all the enum values of table Business and column Classification
		$sql       = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Business' AND COLUMN_NAME = 'Classification'";
		$result    = mysql_query($sql);
		$value     = "";
		while($row = mysql_fetch_assoc($result)){
			//parse the values
			preg_match('/enum\((.*)\)$/', $row["COLUMN_TYPE"], $matches);
			//store it in an array
			$enums = explode(',', $matches[1]);
			//use index so that even if the string has change, we will still have the correct value
			foreach($enums as $index => $enum){
				//display only valid enums (length > 0)
				$enum = ucwords(str_replace("'","",$enum));
				if($enum != ""){
					if($index == $key){
						$value = $enum;
						break;
					}
				}
			}
		}
		mysql_free_result($result);
		return $value;
	}
	
	include( "inc_message_center.html" ); 
	return;
?>