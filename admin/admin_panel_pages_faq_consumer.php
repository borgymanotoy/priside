<?php
	require_once( $_SERVER['DOCUMENT_ROOT']."/libs/verify_session.php" );
	require_once( $_SERVER['DOCUMENT_ROOT']."/translations/admin_panel_pages_faq_consumer.php" );

	// db
	include_once( $_SERVER['DOCUMENT_ROOT']."/libs/db_common.php" );

	$ErrMsg = "";
	$OkMsg = "";

	function getConsumerFAQListHtml($answer_text){
		db_connect();
		$Sql = "SELECT ConsumerId, ConsumerQuestion, ConsumerAnswer FROM PresideFAQ_Consumer ORDER BY ConsumerId ASC LIMIT 200";
		$faqs_html = "";
		$result = mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">".$ErrMsg."</span>\n";
		}
		else if( 0 == mysql_num_rows( $result ) ){
			return "\n<span class=\"txt14Bold\" style=\"color: red;\">No consumer FAQs available.</span>\n";
		}
		while($row = mysql_fetch_array( $result, MYSQL_ASSOC )){
			$faqs_html .= "<div class=\"clearfix container-small\">\n";
			$faqs_html .= "    <a href=\"javascript: void(0);\" onclick=\"loadFaqConsumerInfo(".$row["ConsumerId"].",'".CheckSum( $row["ConsumerId"] )."');\" class=\"arrow-link\">&nbsp;&nbsp;<span id=\"span_faq_consumer_question_".$row["ConsumerId"]."\" class=\"txt14Purple\">".$row["ConsumerQuestion"]."</span>?</a><br/>\n";
			$faqs_html .= "    <br/>";
			$faqs_html .= "    <span class=\"txt12Bold\" style=\"margin-top: 10px;\">".$answer_text.":</span>&nbsp;<span id=\"span_faq_consumer_answer_".$row["ConsumerId"]."\" class=\"txt12\">".$row["ConsumerAnswer"]."</span>\n";
			$faqs_html .= "</div>\n";
		}
		mysql_free_result( $result );
		return $faqs_html;
	}

	// ajax request
	// data : { "ajaxRequest" : "ajaxSaveFaqConsumer", "ConsumerId" : ConsumerId,"ConsumerCheckSum" : ConsumerCheckSum, "ConsumerQuestion" : ConsumerQuestion, "ConsumerAnswer" : ConsumerAnswer, },
	if ( "ajaxSaveUpdateFaqConsumer" == $ajaxRequest ) {

		if( ! empty( $ConsumerId ) && ! empty( $ConsumerCheckSum ) && ! VerifyCheckSum( $ConsumerId, $ConsumerCheckSum ) ){
			$ErrMsg = "CheckSum Error @ " . __LINE__ ;
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		// --------------------------------------------------------------
		// sanity checks for the params.
		if( empty( $ConsumerQuestion ) ){
			$ErrMsg = $trans["pfaq_consumer_ErrMsg_ConsumerQuestion"]; //"ConsumerQuestion cannot be blank.";
		}
		else if( empty( $ConsumerAnswer ) ){
			$ErrMsg = $trans["pfaq_consumer_ErrMsg_ConsumerAnswer"]; //"ConsumerAnswer cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . utf8_decode( $ErrMsg ) );
			exit(0);
		}
		// --------------------------------------------------------------

		db_connect();

		$ConsumerQuestion = mysql_real_escape_string( utf8_decode($ConsumerQuestion) );
		$ConsumerAnswer = mysql_real_escape_string( utf8_decode($ConsumerAnswer) );

		if( empty( $ConsumerId ) || empty( $ConsumerCheckSum )){
			$Sql = "INSERT INTO PresideFAQ_Consumer (ConsumerQuestion, ConsumerAnswer, DateCreated, LastModified) VALUES ('".$ConsumerQuestion."', '".$ConsumerAnswer."', now(), now())";
			$OkMsg = $trans["faq_consumer_save_success_msg"];
		}
		else{
			$Sql  = " UPDATE PresideFAQ_Consumer SET ";
			$Sql .= " 	ConsumerQuestion = '".$ConsumerQuestion."', ";
			$Sql .= " 	ConsumerAnswer = '".$ConsumerAnswer."', ";
			$Sql .= " 	LastModified = now()";
			$Sql .= " WHERE ConsumerId = ".$ConsumerId." LIMIT 1";
			$OkMsg = $trans["faq_consumer_update_success_msg"];
		}

		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// form saved ok.
		print( $OkMsg );
		exit;
		// done.
		//--------------------------------------------------------------------------------------------
	}
	else if ( "ajaxRemoveFaqConsumer" == $ajaxRequest ){
		if( empty( $ConsumerId ) ){
			$ErrMsg = $trans["pfaq_consumer_ErrMsg_ConsumerId"]; //"ConsumerId cannot be blank.";
		}
		if( ! empty( $ErrMsgs ) ) { // we got an error, send it to ajax handler via http header.
			header("Status: 400 " . $ErrMsg );
			exit(0);
		}

		db_connect();

		$ConsumerId = mysql_real_escape_string( utf8_decode($ConsumerId) );

		$Sql = "DELETE FROM PresideFAQ_Consumer WHERE ConsumerId = ".$ConsumerId;
		mysql_query( $Sql );
		if( 0 != mysql_errno() ){
			$ErrMsg = "Query Error @ " . __LINE__ . " : " . mysql_error();
			header("Status: 400 " . $ErrMsg );
			exit;
		}

		// form deleted ok.
		$OkMsg = $trans["faq_consumer_remove_success_msg"];
		print( $OkMsg );
		exit;
	}
	else if ( "reload_consumer_faqs" == $ajaxRequest ){
		$ConsumerFAQsHtml = getConsumerFAQListHtml( $trans["faq_answer"] );
		print( $ConsumerFAQsHtml );
		exit;
	}

	//---------------------------------------------------------------------------------------------------------------
	// there is no ajax action ... so we let the page load

	$ConsumerFAQsHtml = getConsumerFAQListHtml( $trans["faq_answer"] );

	include( "inc_admin_panel_pages_faq_consumer.html" );
	return;
?>