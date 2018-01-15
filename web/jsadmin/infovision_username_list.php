<?php
include_once('connect.inc');

$db = connect_db();
if(authenticated($cid))
{
	$name= getname($cid);

	$sql = "SELECT USERNAME FROM infovision.MAILER WHERE SENT='N'";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while ($row = mysql_fetch_array($res))
	{
		$usernamearr[] = $row['USERNAME'];
	}

	$smarty->assign("name",$name);
	$smarty->assign('cid',$cid);
	$smarty->assign('usernamearr',$usernamearr);
	$smarty->display('infovision_username_list.htm');
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
