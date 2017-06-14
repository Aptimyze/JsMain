<?php
include_once('connect.inc');

$db = connect_db();
if(authenticated($cid))
{
	$sql = "UPDATE infovision.MAILER SET SENT='Y' WHERE USERNAME='$username'";
	mysql_query_decide($sql) or die("$sql".mysql_error_js());

	$smarty->display("infovision_send_mail.htm");
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->assign("user",$user);
        $smarty->display("jsadmin_msg.tpl");
}
?>
