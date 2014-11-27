<?php
//-----------------------------------------------------------------------------
//
// lib_common.php
// misc common functions etc.
// Chris J. Veeneman June 2012
//
//-----------------------------------------------------------------------------

if( ! defined( "__lib_common__" ) ){
	define( "__lib_common__", 1 );
}
else // already included / connected.
	return;

//
//
//
function DumpMsgs( $Msgs,
							$HtmlPreMsg = "<li><font color=\"red\"><strong>",
							$HtmlPostMsg = "</strong></font></li>",
							$MsgSeparater = "<BR>\n"
							){
	if( ! is_array( $Msgs ) || empty( $Msgs ) )
		return;
	reset( $Msgs );
	foreach( $Msgs as $Msg ){
		if( empty( $Msg ) )continue;
		echo $HtmlPreMsg . $Msg . $HtmlPostMsg . $MsgSeparater;
	}
	echo "<BR><BR>\n";
}
//
//
//
?>
