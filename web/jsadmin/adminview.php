<?php
include("connect.inc");
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->display("admin_view.tpl");
}
else
{
	$msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
