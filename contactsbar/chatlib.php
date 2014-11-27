<?php
/*
	Chat Lib
	Implementations of functions called in chat.php
	
	SetUserOnline( $User_Id )								Set the user specified by User_Id online.
	SetUserOffline( $User_Id )								Set the user specified by User_Id offline.
	GetOnlineUsers( &$OnlineUsersResult )					Get a list of online users.						
	CreateConverstaionId( $FromUser_Id, $User_Id_Array )	Create a new conversation from the specified array of user ids
	GetUserIdsInConversation( $Conversation_Id )			Get a list of user ids from the specified conversation id
	GetConversationIdsFromUserId( $FromUser_Id )			Get a list of conversation ids from the specified user id
	GetUnreadMessages( $ConversationIdArray, $Since )		Get a list of unread messages for a user.
	GetMessages( $ConversationIdArray, $Since )				Get a list of messages from the specified conversation id list.
	SendMessage( $FromUser_Id, $Conversation_Id, $Message )	Send a message to a conversation 
	UpdateLastRead( $LastReadArray )						Updates the last read message id for all users in all their active converstions
	April 2012, Chris J. Veeneman
*/

define( "MAX_MSG_LEN", 512 ); // the maximum length of a single message (will truncate)

//
// set a user to online by creating an entry in the ChatOnlineUsers table
// if the user is already online, the LastActive field will be updated to NOW()
// Params:
// User_Id - the user id of who to set online
// returns: true on success, or an errormsg string on error
//
function SetUserOnline( $User_Id ){
	
	if( empty( $User_Id ) || ! is_int( $User_Id ) )
		return( "Param error" );
	$Sql = "UPDATE User SET IsOnlineChat = 1, LastActiveChat = NOW() WHERE Id = $User_Id LIMIT 1";
	mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	// user is now online
	return( true );
	
}
//
// set a user to offline by removing the record from the ChatOnlineUsers table
// Params:
// User_Id - the user id of who to set offline
// returns: true on success, or an errormsg string on error
//
function SetUserOffline( $User_Id ){

	if( empty( $User_Id ) || ! is_int( $User_Id ) )
		return( "Param error: User_Id - wrong type or empty." );
	$Sql = "UPDATE User SET IsOnlineChat = 0, LastActiveChat = NOW() WHERE Id = $User_Id LIMIT 1";
	mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	// user is now offline
	return( true );
}
//
// returns an associative array of users who are online
// returns an array of online users on success, or an errormsg on error
//
function GetOnlineUsers(){
	
	$OnlineUsers = array();
	$Sql = "
		SELECT
			U.Id AS Id, CONCAT( U.FirstName, ' ', U.LastName ) AS Name,
			Bus.Name As BusinessName
		FROM User U, Accounts Acc, Business Bus
		WHERE
			U.IsOnlineChat != 0 AND
			U.Account_Id = Acc.Id AND
			Acc.Business_Id = Bus.Id";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
		$OnlineUsers[] = $row;
	mysql_free_result( $result );
	return( $OnlineUsers );
}
//
// given an array of user ids (chat participants), get the associated Conversation_Id
// params: 
// FromUser_Id - the user creating the conversation
// User_Id_Array - an array of user ids: array( 1,2,3 ... )
// returns:
// Conversation_Id (int) on success, or an errormsg on error
//
function CreateConversationId( $FromUser_Id, $User_Id_Array ){

	// given an array of user ids -
	// 1) check if this exact group of ids is already forming a conversation.
	// 2) if so, return the conversation id - finished.
	// 3) otherwise, create a new conversation id record
	// 4) insert each user id into the participants table
	// 5) return the new conversation id.

	if( ! is_int( $FromUser_Id ) || ! is_array( $User_Id_Array ) || 0 == count( $User_Id_Array ) )
		return( "Param error: FromUser_Id / User_Id_Array - wrong type or empty." );

	// sort the User_Id_Array in numeric order 
	sort( $User_Id_Array, SORT_NUMERIC );
	$IdList = implode( $User_Id_Array, ',' );
	
	// search for idlist
	$Sql = "SELECT Id FROM ChatConversations WHERE UserId_List = '$IdList' LIMIT 1";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
		
	if( 0 < mysql_num_rows( $result ) ){
		$row = mysql_fetch_row( $result );
		mysql_free_result( $result );
		return( (int)$row[0] );
	}
	mysql_query( "BEGIN" );
	
	// conversation doesn't yet exist, create a new one and return it's id.
	$Sql = "INSERT INTO ChatConversations (Id,CreatedByUser_Id,CreatedDate,UserId_List) VALUES (NULL,$FromUser_Id,NOW(),'$IdList')";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno ){
		mysql_query( "ROLLBACK" );
		return( "mysql error: $errno" );
	}

	$Conversation_Id = mysql_insert_id();

	// create a multi-insert record
	$Values = '';
	foreach( $User_Id_Array as $Id )
		$Values .= "(NULL,$Conversation_Id,$Id),";
	$Values = rtrim( $Values, "," );
	
	$Sql = "INSERT INTO ChatConversationParticipants (Id,Conversation_Id,User_Id) VALUES $Values";
	mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno ){
		mysql_query( "ROLLBACK" );
		return( "mysql error: $errno" );
	}
	mysql_query( "COMMIT" );
	return( (int)$Conversation_Id );
}
// 
// return an array of user ids that are participating in a conversation
// params:
// Conversation_Id - the conversation id 
// returns:
// an array of user ids - array( 1,2,...) on success
// errormsg on error
//
function GetUsersInConversation( $Conversation_Id ){

	if( ! is_int( $Conversation_Id ) )
		return( "Param error: Conversation_Id - wrong type or empty." );

	// get the user ids within a conversation
	$Sql = "SELECT UserId_List FROM ChatConversations WHERE Id = $Conversation_Id LIMIT 1";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	$row = mysql_fetch_row( $result );
	mysql_free_result( $result );

	// get the usernames of the ids..
	$Sql = "SELECT Id,CONCAT( FirstName, ' ', LastName ) AS Name FROM User WHERE Id IN ( {$row[0]} )";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	
	$Users = array();
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
		// convert Id to int.
		$row[0] = (int)$row[0];
		$Users[] = $row;
	}
	mysql_free_result( $result );

	/*
	// final array returned should be:
	array(
		"Conversation_Id" => (int)$Conversation_Id, 
		"User_Array" => array(
							array( "Id" = > 1, Name => "Chris Veeneman" ),
							array( "Id" = > 2, Name => "Fredrik Nygren" )
						)
		)
	*/
	return( array( "Conversation_Id" => (int)$Conversation_Id, "User_Array" => $Users ) );
}
//
// return an array of conversation ids that a user id is participating in
// params:
// FromUser_Id - the user id 
// returns:
// an array of conversation ids - array( 1,2,...) on success
// errormsg on error
//
function GetConversationIdsFromUserId( $FromUser_Id ){

	if( ! is_int( $FromUser_Id ) )
		return( "Param error: FromUser_Id - wrong type or empty." );

	$Sql = "SELECT Conversation_Id FROM ChatConversationParticipants WHERE User_Id = $FromUser_Id";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	$Conversation_Ids = array();
	while( $row = mysql_fetch_row( $result ) )
		$Conversation_Ids[] = $row[0];
	mysql_free_result( $result );
	return( $Conversation_Ids );
}
//
// send a message to a user
// Params:
// FromUser_Id - who the message is from
// Conversation_Id - the conversation id that the message is posted to
// Message - the message text (MAX_MESSAGE_LEN bytes max)
// returns: true on success, errormsg otherwise.
//
function SendMessage( $FromUser_Id, $Conversation_Id, $Message ){

	// sanity checks.
	if( ! is_int( $FromUser_Id ) || ! is_int( $Conversation_Id ) || 0 == strlen( $Message ) )
		return( "Param error: FromUser_Id / Conversation_Id / Message - wrong type or empty." );
		
	// ensure the FromUser_Id is actually in the Conversation_Id
	if( ! SelfInConversationId( $FromUser_Id, $Conversation_Id ) )
		return( "Error: ! SelfInConversationId()" );

	// if message is too long (>MAX_MSG_LEN), truncate
	if( MAX_MSG_LEN < strlen( $Message ) )
		$Message = substr( $Message, 0, MAX_MSG_LEN );
		
	$Message = mysql_real_escape_string( $Message );
	$Sql = "INSERT INTO ChatMessages (Id,FromUser_Id,Conversation_Id,Message,DateSent) VALUES(NULL,$FromUser_Id,$Conversation_Id,'$Message',NOW())";
	mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
		
	// message sent
	return( true );
}
//
// Get all unread messages to (for) a specified user
// Params:
// User_Id - user id of who to get messages for
// returns: array of messages on success, or an errormsg on error
//
function GetUnreadMessages( $User_Id ){

	if( ! is_int( $User_Id ) )
		return( "Param error: User_Id - wrong type or empty." );
		
	$Messages = array();
	$Sql = "SELECT 
				m.Conversation_Id AS Conversation_Id, m.Id AS MessageId, 
				m.FromUser_Id as FromUser_Id, CONCAT( u.FirstName, ' ', u.LastName ) AS FromUser_Name,
				m.DateSent as DateSent,
				m.Message
			FROM ChatMessages m,User u,ChatConversationParticipants p
			WHERE 
				(m.FromUser_Id = u.Id) AND
				(m.Id > p.LastReadMessage_Id) AND
				(m.Conversation_Id = p.Conversation_Id) AND
				(p.User_Id = $User_Id)
			ORDER BY m.Id ASC";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) )
		$Messages[] = $row;
	mysql_free_result( $result );
	return( $Messages );
}
//
// return the last read message id for a given user
// Params:
// User_Id - user id of who to get messages for
// returns: the last unre
//
function GetLastReadMessageId( $User_Id ){

	if( ! is_int( $User_Id ) )
		return( "Param error: User_Id - wrong type or empty." );
	$Sql = "SELECT MAX( LastReadMessage_Id ) FROM ChatConversationParticipants WHERE User_Id = $User_Id LIMIT 1";
	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	$row = mysql_fetch_row( $result );
	mysql_free_result( $result );
	return( (int)$row[0] );
}
//
// Get all messages to a specified user that are greater than LastMessageId
// Params:
// ConversationIdArray - an array of conversation ids that you want the messages for
// this can be obtained from: GetConversationIdsFromUserId()
// LastMessageId - a message id 
// returns: array of messages on success, or an errormsg on error
//
function GetMessages( $ConversationIdArray, $LastMessageId ){

	if( ! is_array( $ConversationIdArray ) || ! is_int( $LastMessageId ) )
		return( "Param error: ConversationIdArray / LastMessageId - wrong type or empty." );
	$Messages = array();
	if( 0 == count( $ConversationIdArray ) ){
		// this user is not in any conversations, so there are no messages to retreive.
		return( $Messages );
	}
	$ConvIdListStr = implode( ",", $ConversationIdArray );
	$Sql = 
			"SELECT 
				m.Conversation_Id AS Conversation_Id, m.Id AS MessageId, 
				m.FromUser_Id as FromUser_Id, CONCAT( u.FirstName, ' ', u.LastName ) AS FromUser_Name,
				m.DateSent as DateSent,
				m.Message
			FROM ChatMessages m,User u
			WHERE 
				(m.FromUser_Id = u.Id) AND
				m.Conversation_Id IN ($ConvIdListStr) AND
				m.Id > $LastMessageId
			ORDER BY m.Id ASC";

	$result = mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != $errno )
		return( "mysql error: $errno" );
	while( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ){
		// MessageId, needs to be an int
		$row["MessageId"] = (int)$row["MessageId"];
		$Messages[] = $row;
	}
	mysql_free_result( $result );
	return( $Messages );
}
//
// update all LastReadMessage_Id for all User_Id in all their active conversations
//
// given an array of array( 0 => array(Conversation_Id,User_Id,Message_Id),
//							1 => array(Conversation_Id,User_Id,Message_Id),
//							...
//						) 
// update the ChatConversationParticipants tables
// setting the LastReadMessage_Id to the Message_Id for each Conversation_Id,User_Id 
//
function UpdateLastRead( $LastReadArray ){

	if( ! is_array( $LastReadArray ) || 0 == count( $LastReadArray ) )
		return( "Param error: LastReadArray - wrong type or empty." );
	/*
	// compose an sql statement like this:
	UPDATE ChatConversationParticipants SET
	LastReadMessage_Id = CASE
		WHEN ( Conversation_Id = 42 AND User_Id = 1 ) THEN 1
		WHEN ( Conversation_Id = 42 AND User_Id = 2 ) THEN 2
		WHEN ( Conversation_Id = 43 AND User_Id = 1 ) THEN 1
		WHEN ( Conversation_Id = 43 AND User_Id = 2 ) THEN 2
	ELSE LastReadMessage_Id END
	WHERE
	Conversation_Id IN (42,43,44)
	*/	
	$InList = "";
	$Sql = "UPDATE ChatConversationParticipants SET LastReadMessage_Id = CASE ";
	foreach( $LastReadArray as $row ){
		$InList .= "{$row[0]},";
		$Sql .= "WHEN (Conversation_Id = {$row[0]} AND User_Id = {$row[1]} ) THEN {$row[2]} ";
	}
	$InList = rtrim( $InList, "," );
	$Sql .= "ELSE LastReadMessage_Id END WHERE Conversation_Id IN ($InList)";
	mysql_query( $Sql );
	$errno = mysql_errno();
	if( 0 != mysql_errno() )
		return( "mysql error: $errno" );
	return( true );
}
//
// this is a sanity check called before SendMessage to ensure
// that the Self_Id (caller user_id) is actually in the Conversation_Id
// that the message is being sent to...
//
function SelfInConversationId( $Self_Id, $Conversation_Id ){
	$result = mysql_query( "SELECT Id FROM ChatConversationParticipants WHERE Conversation_Id = $Conversation_Id AND User_Id = $Self_Id LIMIT 1" );
	if( 0 == mysql_num_rows( $result ) )
		return( false );
	return( true );
}
?>
