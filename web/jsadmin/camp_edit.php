<?php

/*************************************************************************************************************************
*    FILENAME        : camp_edit.php 
*    DESCRIPTION     : Activate/De-activate the campain 
**************************************************************************************************************************/  
include ("connect.inc");
$empty=1;
  
if (authenticated($cid))
{
	if($act)
	{
		$sql="update incentive.CAMPAIGN set ACTIVE='$act' where CAMPAIGN='$CAMPAIGN'";
		mysql_query_decide($sql) or die(mysql_error_js());

		$msg= " Record Updated<br>  ";
		$msg .="<a href=\"showcampaign.php?cid=$cid\">";
		$msg .="Continue </a>";                                
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
}
else 
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
