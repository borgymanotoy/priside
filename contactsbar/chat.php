<?php
	/*
		Chat functions called by ajax
		Chris J. Veeneman
		Apr 2012
	*/
	// Authenticate session
	require_once( "../libs/verify_session.php" );
	
	// required libs for this module
	require_once( "../libs/db_common.php" );
	require_once( "chatlib.php" );

	// ChatActions

	// ------------------------------------------
	// SetUserOnline() - chat.php?ChatAction=SetUserOnline&User_Id=x&CheckSum=y
	// ------------------------------------------
	if( "SetUserOnline" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 SetUserOnline():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = SetUserOnline( (int)$User_Id );
		if( true === $ret )
			exit( "1" );
		header("Status: 400 SetUserOnline():$ret");
		exit(0);
	}
	// ------------------------------------------
	// SetUserOffline() - chat.php?ChatAction=SetUserOffline&User_Id=x&CheckSum=y
	// ------------------------------------------
	else if( "SetUserOffline" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 SetUserOffline():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = SetUserOffline( (int)$User_Id );
		if( true === $ret )
			exit( "1" );
		header("Status: 400 SetUserOffline():$ret");
		exit(0);
	}
	// ------------------------------------------
	// GetOnlineUsers() - chat.php?ChatAction=GetOnlineUsers
	// ------------------------------------------
	else if( "GetOnlineUsers" == $ChatAction ){
		db_connect();
		$ret = GetOnlineUsers();
		if( is_array( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetOnlineUsers():$ret");
		exit(0);
	}
	// ------------------------------------------
	// CreateConversationId() - chat.php?ChatAction=GetConversationIdsFromUserId&User_Id=x&CheckSum=y&User_Id_Array=array
	// ------------------------------------------
	else if( "CreateConversationId" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 CreateConversationId():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = CreateConversationId( (int)$User_Id, $User_Id_Array );
		if( is_int( $ret ) )
			exit( json_encode( array( "Conversation_Id" => $ret, "User_Id_Array" => $User_Id_Array ) ) );
		header("Status: 400 CreateConversationId():$ret");
		exit(0);
	}
	// ------------------------------------------
	// GetConversationIdsFromUserId() - chat.php?ChatAction=GetConversationIdsFromUserId&User_Id=x&CheckSum=y
	// ------------------------------------------
	else if( "GetConversationIdsFromUserId" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 GetConversationIdsFromUserId():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = GetConversationIdsFromUserId( (int)$User_Id );
		if( is_array( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetConversationIdsFromUserId():$ret");
		exit(0);
	}
	// ------------------------------------------
	// GetUsersInConversation() - chat.php?ChatAction=GetUserIdsInConversation&Conversation_Id=x
	// ------------------------------------------
	else if( "GetUsersInConversation" == $ChatAction ){
		db_connect();
		$ret = GetUsersInConversation( (int)$Conversation_Id );
		if( is_array( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetUsersInConversation():$ret");
		exit(0);
	}	
	// ------------------------------------------
	// GetUnreadMessages() - chat.php?ChatAction=GetUnreadMessages&User_Id=x&CheckSum=y
	// ------------------------------------------
	else if( "GetUnreadMessages" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 GetUnreadMessages():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = GetUnreadMessages( (int)$User_Id );
		if( is_array( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetUnreadMessages():$ret");
		exit(0);
	}
	// ------------------------------------------
	// GetMessages() - chat.php?ChatAction=GetMessages&User_Id=x&CheckSum=y&LastMessageId=message id
	// ------------------------------------------
	else if( "GetMessages" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 GetMessages():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = GetMessages( GetConversationIdsFromUserId( (int)$User_Id ), (int)$LastMessageId );
		if( is_array( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetMessagess():$ret");
		exit(0);
	}
	// ------------------------------------------
	// GetLastReadMessageId() - chat.php?ChatAction=GetLastReadMessageId&User_Id=x&CheckSum=y&LastMessageId=message id
	// ------------------------------------------
	else if( "GetLastReadMessageId" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 GetLastReadMessageId():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = GetLastReadMessageId( (int)$User_Id );
		if( is_int( $ret ) )
			exit( json_encode( $ret ) );
		header("Status: 400 GetLastReadMessageId():$ret");
		exit(0);
	}
	// ------------------------------------------
	// SendMessage() - chat.php?ChatAction=SendMessage&User_Id=x&CheckSum=y&Message=msg
	// ------------------------------------------
	else if( "SendMessage" == $ChatAction ){
		if( ! VerifyCheckSum( $User_Id, $CheckSum ) ){
			header("Status: 400 SendMessage():VerifyCheckSum() Failed.");
			exit(0);
		}
		db_connect();
		$ret = SendMessage( (int)$User_Id, (int)$Conversation_Id, $Message ); 
		if( true === $ret )
			exit( "1" );
		header("Status: 400 SendMessage():$ret");
		exit(0);
	}
	// ------------------------------------------
	// UpdateLastRead() - chat.php?ChatAction=UpdateLastRead&User_Id=x&CheckSum=y&LastReadArray=array
	// ------------------------------------------
	else if( "UpdateLastRead" == $ChatAction ){
		db_connect();
		$ret = UpdateLastRead( $LastReadArray );
		if( true === $ret )
			exit( "1" );
		header("Status: 400 UpdateLastRead():$ret");
		exit(0);
	}
	// ------------------------------------------
	// invalid action
	// ------------------------------------------
	header( "Status: 400 Invalid ChatAction." );
	exit;
?>
