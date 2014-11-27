<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_ads.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	db_connect();

	function generateAdvertisementListHTML( $searchKey, $statusKeys, $records, $page, $offset, $trans ){
		//paging variables
		$records  = !empty( $records ) ? $records : 10;
		$page     = !empty( $page )    ? $page    : 1;
		$offset   = !empty( $offset )  ? $offset  : 0;

		$Sql_count  = " SELECT  COUNT(distinct a.Id) as rowCount ";
		$Sql_count .= " FROM Ad a ";
		$Sql_count .= " INNER JOIN Accounts acc on acc.Id = a.Account_Id ";
		$Sql_count .= " INNER JOIN Business b on b.Id = acc.Business_Id ";
		$Sql_count .= " LEFT JOIN AdSpot s ON s.Ad_Id = a.Id ";
		$Sql_count .= " LEFT JOIN AdCategoryLocation acl ON acl.AdSpot_Id = s.Id ";
		$Sql_count .= " WHERE concat_ws(' ', s.Ad_Id, lower(b.Name), DATE_FORMAT(s.StartDate, '%M %d, %Y'), '-', DATE_FORMAT(s.EndDate, '%M %d, %Y'))  like '%".strtolower( $searchKey )."%' ";
		if( !empty($statusKeys) )  $Sql_count .= " AND CAST(a.Status AS UNSIGNED) in (".$statusKeys.") ";

		$result      = mysql_query($Sql_count);
		$count       = mysql_fetch_row($result);
		$count       = $count[0];
		mysql_free_result($result);
		$total_pages = ceil((int)$count / $records);

		$Sql  = " SELECT ";
		$Sql .= "     a.Id AS Ad_Id, ";
		$Sql .= "     acc.Id as Account_Id, ";
		$Sql .= "     acc.PrimaryUser_Id AS CompanyUserId, ";
		$Sql .= "     b.Name as CompanyName, ";
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
		$Sql .= " INNER JOIN Business b on b.Id = acc.Business_Id ";
		$Sql .= " LEFT JOIN AdSpot s ON s.Ad_Id = a.Id ";
		$Sql .= " LEFT JOIN AdCategoryLocation acl ON acl.AdSpot_Id = s.Id ";
		$Sql .= " LEFT JOIN BusinessServiceCategory bsc ON bsc.Id = acl.BusinessServiceCategory_Id ";
		$Sql .= " LEFT JOIN Sweden_LanKommuner sl ON sl.Id = acl.Sweden_LanKommuner_Id ";
		//$Sql .= " LEFT JOIN AdStats ast ON ast.Ad_Id = a.Id ";
		$Sql .= " WHERE concat_ws(' ', s.Ad_Id, lower(b.Name), DATE_FORMAT(s.StartDate, '%M %d, %Y'), '-', DATE_FORMAT(s.EndDate, '%M %d, %Y'))  like '%".strtolower( $searchKey )."%' ";

		if( !empty($statusKeys) )  $Sql .= " AND CAST(a.Status AS UNSIGNED) in (".$statusKeys.") ";

		$Sql .= " GROUP BY a.Id ";
		$Sql .= " ORDER BY a.Id ";
		$Sql .= " LIMIT $offset, $records ";

		$AdvertisementListHTML = "";

		$AdIds = array();
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Error @ " . __LINE__ . " : " . mysql_error();
			return "<span class=\"txt12Bold\" style=\"color: red;\">No data available due to error.</span>";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			ob_start();
			include( $_SERVER['DOCUMENT_ROOT']."/inc_nodata_snippet.html" );
			$AdvertisementListHTML = ob_get_contents();
			ob_end_clean();
			return $AdvertisementListHTML;
		}

		//Populate Data Here
		$Advertisements = array();
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$Advertisements[] = $row;
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
		reset( $Advertisements );

		$Stats = array();
		foreach( $Advertisements as $ad ) {
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
				case "active" :
					$statusColor = "#40ae49";
					break;
				case "added" :
					$statusColor = "#fff101";
					break;
				case "booked" :
					$statusColor = "#412467";
					break;
				case "closed" :
					$statusColor = "#d1232a";
					break;
			}

			include( "inc_admin_panel_ads_rowSnippet.html" );
		}

		$AdvertisementListHTML = ob_get_contents();
		ob_end_clean();

		return $AdvertisementListHTML;
	}

	if( "ajaxReloadAdvertisementLists" == $ajaxRequest ){
		// sanity checks on variables
		if(!VerifyCheckSum($records.$page,$checksum)){
			$ErrMsg = "CheckSum Error @ ".__LINE__;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		//paging variables
		$records     = isset($records) ? $records : 10;
		$page        = isset($page)    ? $page    : 1;
		$offset      = ($page - 1) * $records;

		$AdvertisementListHTML = generateAdvertisementListHTML( $searchKey, $statusKeys, $records, $page, $offset, $trans );
		print( $AdvertisementListHTML );
		exit;
	}

	//-----------------------------------------------------------------
	//Start: Fall-through
	//-----------------------------------------------------------------
	$AdvertisementListHTML = generateAdvertisementListHTML( '', '', 10, 1, 0, $trans );
	//-----------------------------------------------------------------

	include( "inc_admin_panel_ads.html" );
	return;
?>
