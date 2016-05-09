<?php

/************************************************************************************************************************
*    FILENAME           : showcampaign.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : displays the list of all the campaigns
*    CREATED BY         : vibhor
***********************************************************************************************************************/


include ("connect.inc");

if (authenticated($cid))
{     
	$i=0;
	$sql  = "select * from incentive.CAMPAIGN" ;
	$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($result))
	{
		$camp[$i]["CAMPAIGN"]=$row['CAMPAIGN'];
		$camp[$i]["ACTIVE"]=$row['ACTIVE'];
	
		$i++;
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("camp",$camp);
	$smarty->display("showcampaign.htm");
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
