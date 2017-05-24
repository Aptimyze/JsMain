<?php

/************************************************************************************************************************
*   FILENAME   :  managebackend.php
*   INCLUDE    :  connect.inc 
*   DESCRIPTION  : To manage the backend login viz display and modification of the privilages etc
***********************************************************************************************************************/

include ("connect.inc");

if (authenticated($cid))
{
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/showuser.php?name=$user&cid=$cid\"> Display User List</a>";
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/addnew_user.php?name=$user&cid=$cid\"> Add New User</a>";
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/showprivilege.php?name=$user&cid=$cid\"> Display Privilage List</a>";
	$linkarr[]="<a href=\"$SITE_URL/jsadmin/addnew_priv.php?name=$user&cid=$cid\"> Add New Privilage</a>";

	$smarty->assign("linkarr",$linkarr);
	$smarty->assign("cid",$cid);
	$smarty->display("managebackend.htm");
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

