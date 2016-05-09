<?php
include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
//connect_misdb();
mysql_select_db_js("newjs");
if($Reply)
{
	$sql="update newjs.FEEDBACK set STATUS='R', STATUS_DT=now() where ID=$id";
	mysql_query_decide($sql);
	send_email($emailto,$message,"feedback from jeevansathi","feedback@jeevansathi.com");
	header("Location: feedback_check.php");

}
elseif($Discard)
{
	$sql="update newjs.FEEDBACK set STATUS='D', STATUS_DT=now() where ID=$id";
	mysql_query_decide($sql);
	header("Location: feedback_check.php");
}
else
{
	$smarty->assign("ID",$id);
	$smarty->assign("EMAILTO",$email);
	$smarty->display("replyfeedback.tpl");
}
?>
