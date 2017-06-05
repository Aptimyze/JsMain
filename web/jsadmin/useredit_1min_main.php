<?php
/*********************************************************************************************
* FILE NAME             : useredit_1min_main.php
* DESCRIPTION           : script for calling the template for using the frames in useredit page
* CREATION DATE         : 13 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
if(authenticated($cid))
{
        $smarty->assign("cid",$cid);
        $smarty->assign("pid",$pid);
        $smarty->assign("user",$user);
        $smarty->display("user_edit_1min_main.htm");
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>

