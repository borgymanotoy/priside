<?php
//
//
//
if( ! defined( "__db_common__" ) ){
	define( "__db_common__", 1 );
}
else // already included / connected.
	return;

// idCheckSumSplit - split an id / checksum pair in the form of "id:checksum" into $Id and $CheckSum
// params:
// $idCheckSum - input string "id:checksum"
// $Id - returned id portion of $idCheckSum
// $CheckSum - returned checksum portion of $idCheckSum
function idCheckSumSplit( $idCheckSum, &$Id, &$CheckSum ){
	$offset = strpos( $idCheckSum, ":" );
	$Id = substr( $idCheckSum, 0, $offset );
	$CheckSum = substr( $idCheckSum, $offset + 1, strlen( $idCheckSum ) );
}

function db_connect(){

	if( defined( "__connected__" ) )
		return( true );

	$db_conn = mysql_connect( "localhost", "priside", "priside" );
	if( ! $db_conn )
		return( false );
	if( ! mysql_select_db( "priside" ) )
		return( false );

	define( "__connected__", 1 );
	register_shutdown_function ( 'shutdown' );
	return( true );
}

function shutdown(){
	mysql_close();
}

?>
