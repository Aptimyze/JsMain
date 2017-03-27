<?php
header("Location: $SITE_URL/");
die;
include("connect.inc");
connect_db();

$data=authenticated($checksum);

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->display("astrol.htm");

?>
