<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/documents_files.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/lib_notes.php" );

	$ErrMsg = "";
	$OkMsg = "";
	
	// ----------------------------------------------------------------------------
	// "ajaxRequest" : "LoadProjectFiles", - loads main content
	// "pid" : ProjectId,
	// "checksum" : CheckSum
	// inc_documents_filesSnippet.html - wich sets up the file tree navigator
	// ----------------------------------------------------------------------------
	if( "LoadProjectFiles" == $ajaxRequest ){

		if( ! VerifyCheckSum( $pid, $checksum ) ) {
			header( "Status: 400 CheckSum Failed @: " . __LINE__ );
			exit;
		}
		// set the upload save path variable for the flash uploader (base64 path,checksum of base64 path)
		$SavePath = base64_encode( $_SESSION['User']['UploadPath'] ) . ",CheckSum=". CheckSum( base64_encode( $_SESSION['User']['UploadPath'] ) );

		db_connect();
		
		// load the last two docs/files uploaded. (not dirs)
		$Sql = "SELECT F.Id AS Id, F.Name AS FileName, F.Size AS FileSize, F.CreationDate AS FileCreationDate, ";
		$Sql.= "CONCAT_WS( ' ',U.FirstName, U.LastName ) AS FileCreatedBy ";
		$Sql.= "FROM ProjectFiles PF, Files F LEFT JOIN User U ON ( U.Id = F.User_Id ) WHERE ( F.Id = PF.File_Id ) AND ";
		$Sql.= "( PF.Project_Id = $pid ) AND F.Type != 'DIR' ORDER BY F.CreationDate DESC LIMIT 2";

		$LastUploadedFiles = array();
		$result = mysql_query( $Sql );
		
		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
			$row['FileSize'] = BytesToHuman( $row['FileSize'] );
			$row['DownloadUrl'] = "/libs/get_file.php?file=" . $row['Id'] . ":" . CheckSum( $row['Id'] ) . "&disposition=attachment";
			$LastUploadedFiles[] = $row;
		}
		mysql_free_result( $result );


		include( "inc_documents_filesSnippet.html" ); 
		exit();
	}
	// ----------------------------------------------------------------------------
	// 'ajaxRequest' : 'FileTreeNavigation', -  called from jqueryfiletree plugin
	// 'pid'         : '$pid:CheckSum',
	// 'pfid'        : G_currentFileParentId,
	// 'filename'    : filename
	// ----------------------------------------------------------------------------
	else if( "FileTreeNavigation" == $ajaxRequest ){
	
		// pid is a tupple in the form: "pid:checksum" (project_id / checksum)
		// pfid is a tupple in the form: "pfid:checksum" (parent file_id / checksum)
	
		idCheckSumSplit( $pid, $projectId, $projectIdCheckSum );
		idCheckSumSplit( $pfid, $parentFileId, $parentFileIdCheckSum );
		
		if( ! VerifyCheckSum( $projectId, $projectIdCheckSum ) || ! VerifyCheckSum( $parentFileId, $parentFileIdCheckSum ) ){
			header( "Status: 400 CheckSum(s) Failed @: " . __LINE__ );
			exit;
		}

		db_connect();

		$directories = array();
		$files = array();
		$html = '';

		$Sql = "SELECT F.Id AS Id, F.Parent_Id AS Parent_Id, F.Name AS FileName, F.Type AS FileType, F.Size AS FileSize, F.CreationDate AS FileCreationDate, ";
		$Sql.= "CONCAT_WS( ' ',U.FirstName, U.LastName ) AS FileCreatedBy ";
		$Sql.= "FROM ProjectFiles PF, Files F LEFT JOIN User U ON ( U.Id = F.User_Id ) WHERE ( F.Id = PF.File_Id ) AND ( PF.Project_Id = $projectId ) ";
		$Sql.= "AND ( F.Parent_Id = $parentFileId ) ORDER BY F.Name ASC";

		$result = mysql_query( $Sql );

		while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){

			// split results such that directories ( "DIR" == Type ) go into their own array
			if( "DIR" == $row["FileType"] )
				$directories[] = $row;
			else
				$files[] = $row;
		}
		mysql_free_result( $result );
		
		if( 0 >= count($files) + count( $directories ) )
			exit( "" ); // no output

		foreach( $directories as $entry ) {
		
			// tag root branches to allow inline branch updates
			if( 0 == $entry['Parent_Id'] )
				$html .= '<ul branchUpdateKey="'.$entry['Id'].':'.CheckSum($entry['Id']).'" class="jqueryFileTree" style="display: none;">';
			else
				$html .= '<ul class="jqueryFileTree" style="display: none;">';

			$attrib_ids = 'fileId="'.$entry['Id'].':'.CheckSum($entry['Id']).'" fileParentId="'.$entry['Parent_Id'].':'.CheckSum($entry['Parent_Id']).'"';
			$attribs_file = 'fileType="'.$entry['FileType'].'" fileName="'.$entry['FileName'].'" fileSize="'.BytesToHuman($entry['FileSize']).'" fileCreationDate="'.$entry['FileCreationDate'].'" fileCreatedBy="'.$entry['FileCreatedBy'].'"';
			
			$html .=  '<li class="directory collapsed">';
			$html .=  "<a href=\"#\" $attrib_ids $attribs_file>".$entry['FileName'];
			$html .=  '</a></li>';

			$html .=  "</ul>";	
		}
		foreach( $files as $entry ) {
		
			// tag root branches to allow inline branch updates
			if( 0 == $entry['Parent_Id'] )
				$html .= '<ul branchUpdateKey="'.$entry['Id'].':'.CheckSum($entry['Id']).'" class="jqueryFileTree" style="display: none;">';
			else
				$html .= '<ul class="jqueryFileTree" style="display: none;">';

			$ext = preg_replace('/^.*\./', '', $entry['FileName']);
			$attrib_ids = 'fileId="'.$entry['Id'].':'.CheckSum($entry['Id']).'" fileParentId="'.$entry['Parent_Id'].':'.CheckSum($entry['Parent_Id']).'"';
			$attribs_file = 'fileType="'.$entry['FileType'].'" fileName="'.$entry['FileName'].'" fileSize="'.BytesToHuman($entry['FileSize']).'" fileCreationDate="'.$entry['FileCreationDate'].'" fileCreatedBy="'.$entry['FileCreatedBy'].'"';

			$html .=  '<li class="entry ext_'.$ext.'">';
			$html .=  "<a href=\"#\" $attrib_ids $attribs_file>".$entry['FileName'];
			$html .=  '</a></li>';
			
			$html .=  "</ul>";	
		}
		print $html;	
		exit;
	}
	// ----------------------------------------------------------------------------
	// ajaxRequest - fileUploaded - called after successfull upload of a file
	// data passed: 'ajaxRequest','fileUploaded','pid','pfid','filename'
	// ----------------------------------------------------------------------------
	else if( "fileUploaded" == $ajaxRequest ){

		// pid is a tupple in the form: "pid:checksum" (project_id / checksum)
		// pfid is a tupple in the form: "pfid:checksum" (parent file_id / checksum)
		idCheckSumSplit( $pid, $projectId, $projectIdCheckSum );
		idCheckSumSplit( $pfid, $parentFileId, $parentFileIdCheckSum );
		
		if( ! VerifyCheckSum( $projectId, $projectIdCheckSum ) || ! VerifyCheckSum( $parentFileId, $parentFileIdCheckSum ) ){
			header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
			exit;
		}
		
		// the physical file 
		$uploadedFile = $_SESSION['User']['UploadPath'] . "/upload";
		if( ! file_exists( $uploadedFile ) ){
			header( "Status: 400 File Not Found " . __LINE__ );
			exit;
		}
		// get mime type
		$mimeType = finfo_file( finfo_open( FILEINFO_MIME_TYPE  ), $uploadedFile );
		
		// get file size
		$fileSize = filesize( $uploadedFile );
	
		db_connect();
		
		mysql_query( "BEGIN" );

		$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES($parentFileId,{$_SESSION['User']['Id']},'".mysql_real_escape_string(strtolower( $filename ))."','$mimeType',$fileSize,NOW())";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			mysql_query( "ROLLBACK" );
			header( "Status: 400 Upload Transaction Failed " . __LINE__ . " / " . mysql_errno() . ":" . mysql_error() );
			exit;
		}
		// get the id of the new record (it will be our new filename)
		$File_Id = mysql_insert_id();
		$finalFileName = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/$File_Id";

		// link this uploaded file to the current project
		$Sql = "INSERT INTO ProjectFiles( Project_Id, File_Id ) VALUES( $projectId, $File_Id )";
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			mysql_query( "ROLLBACK" );
			header( "Status: 400 Upload Transaction Failed " . __LINE__ . " / " . mysql_errno() . ":" . mysql_error() );
			exit;
		}
			
		// rename the file to it's new name
		if( ! rename( $uploadedFile, $finalFileName ) ){
			mysql_query( "ROLLBACK" );
			header( "Status: 400 Upload Transaction Failed $uploadedFile / $finalFileName" . __LINE__ );
			exit;
		}
		
		// all ok, commit the transaction
		mysql_query( "COMMIT" );

		// update branch or tree
		header( "Status: 200 " . $trans["document_files_message_ok"] );
		header( "Content-Type: application/json" );
		
		if( 0 == $parentFileId ){
			// update tree, file was uploaded to the root
			exit( json_encode( array( "updateType" => "tree" ) ) );
		}
		// refresh only this branch that the rename took place in.
		$json_data = RefreshTreeBranch( $projectId, $File_Id );
		print $json_data;
		exit;
	}
	// 'ajaxRequest': 'FileTreeOperations',
	// 'pid'        : $pid:CheckSum( $pid ),     - current project id:checksum
	// 'operation'  : fileTreeOp,                - requested operation (CREATEFOLDER | RENAMEFILE | DELETEFILE)
	// 'cfid'       : fileId,                    - current file id:checksum
	// 'cfidparent' : fileParentId,              - current parent id:checksum
	// 'userInput'  : userInput                  - user input value
	else if( "FileTreeOperations" == $ajaxRequest ){

		idCheckSumSplit( $pid, $projectId, $projectIdCheckSum );
		if( ! VerifyCheckSum( $projectId, $projectIdCheckSum ) ){
			header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
			exit;
		}
		
		// --------------------------------------------------------
		// CREATEFOLDER
		// --------------------------------------------------------
		if( "CREATEFOLDER" == $operation ){
		
			idCheckSumSplit( $cfidparent, $parentFileId, $parentFileIdCheckSum );
			if( ! VerifyCheckSum( $parentFileId, $parentFileIdCheckSum ) ){
				header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
				exit;
			}

			db_connect();
			
			mysql_query( "BEGIN" );

			$Sql = "INSERT INTO Files(Parent_Id,User_Id,Name,Type,Size,CreationDate) VALUES($parentFileId,{$_SESSION['User']['Id']},'".mysql_real_escape_string(strtolower( $userInput ))."','DIR',0,NOW())";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Folder Creation Failed " . __LINE__ );
				exit;
			}
			// get the id of the new record (it will be our new filename)
			$File_Id = mysql_insert_id();

			// link this uploaded file to the current project
			$Sql = "INSERT INTO ProjectFiles( Project_Id, File_Id ) VALUES( $projectId, $File_Id )";
			mysql_query( $Sql );
			if( 0 != mysql_errno() ){
				mysql_query( "ROLLBACK" );
				header( "Status: 400 Folder Creation Failed " . __LINE__ );
				exit;
			}
			// all ok, commit the transaction
			mysql_query( "COMMIT" );

			header( "Status: 200 " . $trans["document_files_new_folder_ok"] );
			if( 0 == $parentFileId ){
				header( "Content-Type: application/json" );
				exit( json_encode( array( "updateType" => "tree" ) ) );
			}
			// otherwise, we refresh only the branch that the new folder took place in.
			else {
				$json_data = RefreshTreeBranch( $projectId, $parentFileId );
				header( "Content-Type: application/json" );
				print $json_data;
			}
			exit;
		}
		// --------------------------------------------------------
		// RENAMEFILE
		// --------------------------------------------------------
		else if( "RENAMEFILE" == $operation ){
			//
			// are we renaming a file or a directory ?
			// if cfid is set it's a file - verify cfid (fileId / fileIdCheckSum) and use fileId as the current file id to rename,
			// otherwise, it's a directory - verify cfidparent (parentFileId / parentFileIdCheckSum) and use parentFileId as the current file id to rename
			//
			if( ! empty( $cfid ) ){
				idCheckSumSplit( $cfid, $fileId, $fileIdCheckSum );
				if( ! VerifyCheckSum( $fileId, $fileIdCheckSum ) ){
					header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
					exit;
				}
			}
			else {
				idCheckSumSplit( $cfidparent, $parentFileId, $parentFileIdCheckSum );
				if( ! VerifyCheckSum( $parentFileId, $parentFileIdCheckSum ) ){
					header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
					exit;
				}
				$fileId = $parentFileId;
			}

			db_connect();

			// simple permission check
			if( ! MyFile( $fileId ) ){
				header( "Status: 400 " . $trans["document_files_permission"] . " " . __LINE__ );
				exit;
			}
			
			// do the rename
			mysql_query( "UPDATE Files SET Name = '" . mysql_real_escape_string( $userInput ) . "' WHERE Id = $fileId LIMIT 1" );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Folder Rename Failed " . __LINE__ );
				exit;
			}
			// refresh only this branch that the rename took place in.
			$json_data = RefreshTreeBranch( $projectId, $fileId );
			header( "Status: 200 " . $trans["document_files_rename_ok"] );
			header( "Content-Type: application/json" );
			print $json_data;
			exit;
		}
		// --------------------------------------------------------
		// DELETEFILE
		// --------------------------------------------------------
		else if( "DELETEFILE" == $operation ){

			idCheckSumSplit( $cfid, $fileId, $fileIdCheckSum );
			idCheckSumSplit( $cfidparent, $fileIdParent, $fileIdParentCheckSum );

			if( ! VerifyCheckSum( $fileId, $fileIdCheckSum ) || ! VerifyCheckSum( $fileIdParent, $fileIdParentCheckSum ) ){
				header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
				exit;
			}

			db_connect();

			// simple permission check
			if( ! MyFile( $fileId ) ){
				header( "Status: 400 " . $trans["document_files_permission"] . " " . __LINE__ );
				exit;
			}
			
			// directory delete
			if( 'DIR' == $fileType ){
				$Sql = "SELECT COUNT(Id) FROM Files WHERE Parent_Id = $fileId LIMIT 1";
				$result = mysql_query( $Sql );
				$row = mysql_fetch_row( $result );
				mysql_free_result( $result );
				if( 0 < $row[0] ){
					header( "Status: 400 Folder Not Empty " . __LINE__ );
					exit;
				}
				// 
				// due to some bad logic elsewhere, the parent_id of a selected directory 
				// is itself... therefore, we can't use fileIdParent in the folder refresh code
				// so, we need to manually look up the parent id here.
				//
				$Sql = "SELECT Parent_Id FROM Files WHERE Id = $fileId LIMIT 1";
				$result = mysql_query( $Sql );
				$row = mysql_fetch_row( $result );
				mysql_free_result( $result );
				// set fileIdParent to the correct value here.
				$fileIdParent = $row[0];

				mysql_query( "DELETE FROM Files WHERE Id = $fileId LIMIT 1" );
			}
			// regular file delete
			else{
				mysql_query( "BEGIN" );
				$Sql = "DELETE FROM Files WHERE Id = $fileId LIMIT 1";
				mysql_query( $Sql );
				$Sql = "DELETE FROM ProjectFiles WHERE File_Id = $fileId LIMIT 1";
				mysql_query( $Sql );
				$path = DATA_PATH . "/accounts/" . $_SESSION['User']['AccountId'] . "/$fileId";
				if( ! file_exists( $path ) ){
					mysql_query( "ROLLBACK" );
					header( "Status: 400 File Not Found : " . __LINE__ );
					exit;
				}
				unlink( $path );
				mysql_query( "COMMIT" );
			}

			// update branch or tree
			header( "Status: 200 " . $trans["document_files_deleted"] );
			header( "Content-Type: application/json" );
			
			if( 0 == $fileIdParent ){
				// update tree, file was uploaded to the root
				exit( json_encode( array( "updateType" => "tree" ) ) );
			}
			// refresh only this branch that the rename took place in.
			$json_data = RefreshTreeBranch( $projectId, $fileIdParent );
			print $json_data;
			exit;

		}
		// --------------------------------------------------------
		// MOVEFILE
		// --------------------------------------------------------
		else if( "MOVEFILE" == $operation ){

			idCheckSumSplit( $cfid, $fileId, $fileIdCheckSum ); // file to be moved
			idCheckSumSplit( $cfidparent, $parentFileId, $parentFileIdCheckSum ); // target parent to be moved to
			idCheckSumSplit( $userInput, $targetId, $targetIdCheckSum ); // target parent to be moved to

			if( ! VerifyCheckSum( $fileId, $fileIdCheckSum ) || ! VerifyCheckSum( $parentFileId, $parentFileIdCheckSum ) || ! VerifyCheckSum( $targetId, $targetIdCheckSum ) ){
				header( "Status: 400 CheckSum(s) Failed " . __LINE__ );
				exit;
			}

			db_connect();
			
			// simple permission check
			if( ! MyFile( $fileId ) ){
				header( "Status: 400 " . $trans["document_files_permission"] . " " . __LINE__ );
				exit;
			}
			
			// move file/directory
			mysql_query( "UPDATE Files SET Parent_Id = $targetId WHERE Id = $fileId LIMIT 1" );
			if( 0 != mysql_errno() ){
				header( "Status: 400 Move Failed " . __LINE__ );
				exit;
			}
			
			//
			// this is a tad annoying, updating the whole tree for a move.
			// todo this "correctly", I need to add support for "multi-branch" updates,
			// such that the branch the file was from and the new branch the file is moved to are both updated
			// otherwise it looks like a copy...
			// for now I will take the easy way.
			//
			header( "Status: 200 " . $trans["document_files_move_ok"] );
			header( "Content-Type: application/json" );
			exit( json_encode( array( "updateType" => "tree" ) ) );
		}
		
		// invalid / unlisted FileTreeOperations
		header( "Status: 400 Invalid Action : " . __LINE__ );
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
	$Sql.= "P.Account_Id = {$_SESSION['User']['AccountId']} AND NOW() <= EndDate LIMIT 100";
	$result = mysql_query( $Sql );
	$list_html = "";
	while($row = mysql_fetch_row($result)){
		$list_html .= "<li id=\"org_".$row[0]."\" value=\"".$row[0]."\" onclick=\"displaySelectedItem('".$div_name."', '".$span_name."', '".$row[1]."');LoadProjectFiles( $row[0], '".CheckSum( $row[0] )."' );\">".$row[1]."</li>\n";
	}
	mysql_free_result( $result );
	return $list_html;

}
//
// Rebuild the branch of the tree that contains the $targetId
//
function RefreshTreeBranch( $projectId, $targetId ){

	$Sql = "SELECT
				F.Id AS Id, F.Parent_Id AS Parent_Id, 
				F.Name AS FileName, F.Type AS FileType, F.Size AS FileSize, 
				F.CreationDate AS FileCreationDate, CONCAT_WS( ' ',U.FirstName, U.LastName ) AS FileCreatedBy
			FROM ProjectFiles PF, Files F LEFT JOIN User U ON ( U.Id = F.User_Id )
			WHERE
				( F.Id = PF.File_Id ) AND ( PF.Project_Id = $projectId )";
				
	$allEntries = array();
	$parentId = 0;
	$rootAncestorId = 0;
	
	$result = mysql_query( $Sql );
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
		// get first ancestor of targetId
		if( $targetId == $row['Id'] )
			$parentId = $row['Parent_Id'];
		$allEntries[$row['Id']] = $row;
	}
	mysql_free_result( $result );

	// trace parentId back to 0 (root) to get the rootAncestorId
	while( 0 != $parentId ){
		//
		// NOTICE: rootAncestorId assigned *before* parentId, such that the loops ends, leaving rootAncestorId 
		// at the first non root id.
		$rootAncestorId = $allEntries[$parentId]['Id'];
		$parentId = $allEntries[$parentId]['Parent_Id'];
	}
	// it could be zero, such that it is a single root node.
	if( 0 == $rootAncestorId )
		$rootAncestorId = $targetId;
		
	$newBranchHtml = '';
	rGetChildren( $rootAncestorId, $allEntries, $newBranchHtml );
	$newBranchHtml .= '</ul>';
	$branchUpdateKey = $rootAncestorId.':'.CheckSum( $rootAncestorId );

	return( json_encode( array( "updateType" => "branch", "branchUpdateKey" => $branchUpdateKey, "html" => $newBranchHtml ) ) );
}
//
// recursive function to get all children of a parent
// called by RefreshTreeBranch()
// markup into html
//
function rGetChildren( $parentId, &$allEntries, &$newBranchHtml ){
	
	// add node
	$entry = $allEntries[$parentId];

	// tag root branches with a "branchUpdateKey" to allow for a single branch update from javascript with a $('#container').replaceWith() call
	if( 0 == $entry['Parent_Id'] )
		$newBranchHtml .= '<ul branchUpdateKey="'.$entry['Id'].':'.CheckSum($entry['Id']).'" class="jqueryFileTree" style="">';
	else
		$newBranchHtml .= '<ul class="jqueryFileTree" style="">';
	
	$attrib_ids = 'fileId="'.$entry['Id'].':'.CheckSum($entry['Id']).'" fileParentId="'.$entry['Parent_Id'].':'.CheckSum($entry['Parent_Id']).'"';
	$attribs_file = 'fileType="'.$entry['FileType'].'" fileName="'.$entry['FileName'].'" fileSize="'.BytesToHuman($entry['FileSize']).'" fileCreationDate="'.$entry['FileCreationDate'].'" fileCreatedBy="'.$entry['FileCreatedBy'].'"';
	if( 'DIR' == $entry['FileType'] )
		$newBranchHtml .=  '<li class="directory expanded">';
	else{
		$ext = preg_replace('/^.*\./', '', $entry['FileName']);
		$newBranchHtml .=  '<li class="entry ext_'.$ext.'">';
	}
	$newBranchHtml .=  "<a href=\"#\" $attrib_ids $attribs_file>".$entry['FileName'].'</a>';

	// look for children
	reset( $allEntries );
	foreach( $allEntries as $entry ){
		if( $parentId == $entry['Parent_Id'] ){
			// recurse
			rGetChildren( $entry['Id'], $allEntries, $newBranchHtml );
			$newBranchHtml .= "</ul>";
		}
	}
	$newBranchHtml .= "</li>";
}

//
// IsMyFile( $fileId ) - check if the file pointed to by fileId is *my* file / directory
// we won't allow move / rename / deletions of files not owned by self (current user - $_SESSION['User']['Id'])
//
function MyFile( $fileId ){
	//
	// this query will return 1|0 - bool if the file in question is == current user.
	//
	$Sql = "SELECT (User_Id = {$_SESSION['User']['Id']}) FROM Files WHERE Id = $fileId LIMIT 1";
	$result = mysql_query( $Sql );
	$row = mysql_fetch_row( $result );
	$nr = mysql_num_rows( $result );
	mysql_free_result( $result );
	if( "0" == $row[0] || 0 == $nr )
		return( false );
	return( true );
}
//
//
// -----------------------------------------------------------------------------------------------------------------------
	db_connect();
	include( "inc_documents_files.html" ); 
	exit();
	// eof
?>