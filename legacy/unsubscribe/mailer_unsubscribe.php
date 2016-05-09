<?php

/*********************************************************************************************
* FILE NAME   : mailer_unsubscribe.php
* DESCRIPTION : Unsubscribes the user by updating jsadmin.AFFILIATE_DATA TABLE
* CREATION DATE        : 19 May, 2005
* CREATEDED BY        : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../profile/connect.inc");

if($Submit)
{
	$db = connect_db();

	$today=date("Y-m-d");
	$sql="UPDATE jsadmin.AFFILIATE_MAIN SET UNSUBSCRIBE='Y',UNSUB_TIME=now() WHERE EMAIL='$mail'";
	mysql_query_decide($sql) or logerror("Error in UnSubscribing script. ".mysql_error_js());

	$smarty->assign("flag","1");
	$smarty->assign("mail",$mail);
	$smarty->display("unsub.htm");
}
else
{
	$smarty->assign("mail",$mail);
	$smarty->display("unsub.htm");
}
?>
