<?php

/*********************************************************************************************
* FILE NAME   : unsubscribe_rishta.php
* DESCRIPTION : Unsubscribes the user by updating MMM.RISHTA_EMAILS
* CREATION DATE        : 24 june, 2005
* CREATEDED BY        : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../profile/connect.inc");
if(!$active_db)
	$active_db = "";
if(!$previous_db)
	$previous_db = "";
if(!$db_211)
        $db_211="";
include_once(JsConstants::$docRoot."/profile/mysql_multiple_connections.php");

if($Submit)
{
	function connect_db1()
	{
		$db=@db_set_active("737","10.208.64.70","user","CLDLRTa9") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
		@mysql_select_db_js("MMM",$db);
		return $db;
	}
	$db = connect_db1();
	$today=date("Y-m-d");
	$sql="UPDATE MMM.RISHTA_EMAILS SET UNSUBSCRIBE='Y',UNSUB_TIME=now() WHERE EMAIL='$mail'";
	mysql_query_decide($sql) or logerror("Error in UnSubscribing script. ".mysql_error_js());

	$smarty->assign("flag","1");
	$smarty->assign("mail",$mail);
	$smarty->display("rishta_unsub.htm");
}
else
{
	$smarty->assign("mail",$mail);
	$smarty->display("rishta_unsub.htm");
}
?>
