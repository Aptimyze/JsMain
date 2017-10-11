<?php

/************************************************************************************************************************
*   FILENAME     : manageOnlineDialing.php  
*   DESCRIPTION  : To manage the online dialing viz display and modification of the campaigns etc
***********************************************************************************************************************/

include ("connect.inc");

if (authenticated($cid))
{
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/showcampaign.php?name=$user&cid=$cid\"> Display Campaign List</a>";
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/addnew_camp.php?name=$user&cid=$cid\"> Add New Campaign</a>";

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("cid",$cid);
	$smarty->display("manageOnlineDialing.htm");
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

