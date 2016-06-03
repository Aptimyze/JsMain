<?php
include("connect.inc");
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	$smarty->display("faq_admin_main.htm");
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("faq_continue.htm");
}
?>
