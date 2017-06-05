<?php
include('connect.inc');

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

$db = connect_db();
if(authenticated($cid))
{
	$sql = "SELECT CONTENT FROM infovision.MAILER WHERE USERNAME='$username' AND SENT='N'";
	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row = mysql_fetch_array($res);

	$smarty->assign('username',$username);
	$smarty->assign('cid',$cid);
	$smarty->assign("msg",$row['CONTENT']);
	$smarty->display("infovision_print_version.htm");

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
