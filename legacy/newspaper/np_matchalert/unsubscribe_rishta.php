<?php

/*********************************************************************************************
* FILE NAME   : unsubscribe_rishta.php
* DESCRIPTION : Unsubscribes the user by updating MMM.RISHTA_EMAILS
* CREATION DATE        : 24 june, 2005
* CREATEDED BY        : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../profile/connect.inc");

if($Submit)
{
	function connect_db1()
	{
		$db=@mysql_connect("198.65.139.241","user","CLDLRTa9") or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes","","ShowErrTemplate");
		@mysql_select_db("MMM",$db);
		return $db;
	}
	$db = connect_db1();
	$today=date("Y-m-d");
	$sql="UPDATE MMM.RISHTA_EMAILS SET UNSUBSCRIBE='Y',UNSUB_TIME=now() WHERE EMAIL='$mail'";
//	$sql="INSERT INTO jsadmin.UNSUBSCRIBE(ID,EMAIL,SOURCE) VALUES ('','$mail','R')";
	mysql_query($sql) or logerror("Error in UnSubscribing script. ".mysql_error());

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
