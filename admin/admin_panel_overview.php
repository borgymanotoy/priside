<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_overview.php" );
	
	//db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );
	db_connect();
	
	//get all pending service requests stats
	$sql    = "
				SELECT 
					COUNT(SR.Id) AS TotalPendingServiceRequests 
				FROM 
					ServiceRequest SR INNER JOIN User U ON SR.User_Id = U.Id
				WHERE 
					U.Account_Id <> 1 AND
					SR.Status = 1
				LIMIT 1
			  ";
	$result = mysql_query($sql);
	$totalPendingServiceRequests = mysql_fetch_assoc($result);
	$totalPendingServiceRequests = $totalPendingServiceRequests["TotalPendingServiceRequests"];
	mysql_free_result($result);
	
	//get all pending certification requests stats
	$sql    = "
				SELECT
				  COUNT(BC.Id) AS TotalPendingCertifications
				FROM
				  Accounts A INNER JOIN BusinessCertifications BC ON A.Business_Id = BC.Business_Id
				WHERE
				  A.Id <> 1 AND
				  BC.Status = 1
				LIMIT 1
			  ";
	$result = mysql_query($sql);
	$totalPendingCertifications = mysql_fetch_assoc($result);
	$totalPendingCertifications = $totalPendingCertifications["TotalPendingCertifications"];
	mysql_free_result($result);
	
	//get all active request
	$sql    = "
				SELECT 
					COUNT(SR.Id) AS TotalActiveServiceRequests 
				FROM 
					ServiceRequest SR INNER JOIN User U ON SR.User_Id = U.Id
				WHERE 
					U.Account_Id <> 1 AND
					SR.Status <> 4
				LIMIT 1
			  ";
	$result = mysql_query($sql);
	$totalActiveServiceRequests = mysql_fetch_assoc($result);
	$totalActiveServiceRequests = $totalActiveServiceRequests["TotalActiveServiceRequests"];
	mysql_free_result($result);
	
	//get top 5% of most requested service
	$sql = "
				SELECT
				  BSC.Category,
				  COUNT(SR.Id) AS TotalRequests
				FROM
				  BusinessServiceCategory BSC LEFT JOIN ServiceRequest SR ON BSC.Id = SR.BusinessServiceCategory_Id
				GROUP BY
				  BSC.Id
				HAVING 
				  COUNT(SR.Id) > 0
				ORDER BY
				  COUNT(SR.Id) DESC,
				  SR.DateCreated DESC
				LIMIT 5
		   ";
	$result         = mysql_query($sql);
	$overAllRequest = 0;
	$topServices    = array();
	while($row = mysql_fetch_assoc($result)){
		$topServices[]  = $row;
		$overAllRequest+= $row["TotalRequests"];
	}
	mysql_free_result($result);
	
	
	//get customer / user stats
	//CAST A.Type so that we get the numeric value of the enum
	$sql = "
			SELECT
			  CAST(A.Type AS UNSIGNED) AS Type,
				COUNT(A.Type) AS TypeCount
			FROM
			  Accounts A INNER JOIN User U ON A.PrimaryUser_Id = U.ID
			WHERE
			  A.Type <> 4
			GROUP BY
			  A.Type
	       ";
	$result     = mysql_query($sql);
	$totalPremium     = 0;
	$totalAdvertiser  = 0;
	$totalRegular     = 0;
	$totalUsers       = 0;
	while($row = mysql_fetch_assoc($result)){
		if($row["Type"] == 1){//regular
			$totalRegular = $row["TypeCount"];
		}
		else if ($row["Type"] == 2){//Advertiser
			$totalAdvertiser = $row["TypeCount"];
		}
		else if($row["Type"] == 3){//Premium
			$totalPremium = $row["TypeCount"];
		}
		$totalUser+=$row["TypeCount"];
	}
	mysql_free_result($result);
	include( "inc_admin_panel_overview.html" );
	return;
?>