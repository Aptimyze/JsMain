<?php
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");
$db=connect_db();

//Bms code
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
//Ends here.

$data=authenticated($checksum);
if($data)
{
	$pid=$data["PROFILEID"];
	login_relogin_auth($data);

	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("data",$data["PROFILEID"]);
	if(getSubscriptionStatus($data["PROFILEID"]))
		$smarty->assign("RENEW",1);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->display("membership_intermediate_page.htm");
}
else
	timedOut();
?>

