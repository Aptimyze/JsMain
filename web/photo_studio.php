<?php
	include("profile/connect.inc");

	$data=authenticated();

	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));

	$smarty->display("js_photo_studio_index.htm");
?>
