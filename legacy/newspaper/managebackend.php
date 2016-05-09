<?php

/************************************************************************************************************************
*   FILENAME   :  managebackend.php
*   INCLUDE    :  connect.inc 
*   DESCRIPTION  : To manage the backend login viz display, modification and addition of new accounts.
***********************************************************************************************************************/

include ("connect.inc");
dbsql2_connect();

if (authenticated($cid))
{
	$linkarr[]="<a href=\"$SITE_URL/showuser.php?name=$user&cid=$cid\"> Display User List</a>";
	$linkarr[]="<a href=\"$SITE_URL/addnew_user.php?name=$user&cid=$cid\"> Add New User</a>";

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("cid",$cid);
	$smarty->display("managebackend.htm");
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>

