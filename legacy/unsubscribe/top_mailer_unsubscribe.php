<?php

/*********************************************************************************************
* FILE NAME   : top_mailer_unsubscribe.php
* DESCRIPTION : Unsubscribes the user by updating newjs.TOP_SAVE_MATCHALERT TABLE
* CREATION DATE        : 14 Feb, 2006
* CREATED BY        : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../profile/connect.inc");

if($Submit)
{
	$db = connect_db();

	$sql="UPDATE newjs.TOP_SAVE_MATCHALERT SET SUBSCRIBE='N' WHERE EMAIL='$mail'";
	mysql_query_decide($sql) or logerror("Error in UnSubscribing script. ".mysql_error_js());

	$smarty->assign("flag","1");
	$smarty->assign("mail",$mail);
	$smarty->display("unsub.htm");
}
else
{
	$smarty->assign("mail",$mail);
	$smarty->assign("form_action","top_mailer_unsubscribe.php");
	$smarty->display("unsub.htm");
}
?>
