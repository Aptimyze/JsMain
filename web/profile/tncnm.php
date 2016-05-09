<?php
include "connect.inc";
connect_db();
$data=authenticated($checksum);
if($data)
	login_relogin_auth($data);
/*****bms code*****/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/*****bms code ends*****/
$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));

if($small)
	$smarty->display("terms_and_conditions_new_media_small.htm");
else
	$smarty->display("terms_and_conditions_new_media.htm");


?>

