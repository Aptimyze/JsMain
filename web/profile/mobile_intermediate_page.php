<?php
/**********************************************************************************************
  FILENAME    : mobile_intermediate_page.php
  DESCRIPTION : intermediate page to display mobile no.
  INCLUDE     : connect.inc
  CREATED BY  : Lavesh Rawat
  CREATED ON  : 6 feb 2007
**********************************************************************************************/

include_once("connect.inc");
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

	$smarty->assign("PHONE_MOB",$PHONE_MOB);
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->display("mobile_intermediate_page.htm");
}
else
	timedOut();
?>

