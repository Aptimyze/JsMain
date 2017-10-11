<?php
	header("Location: $SITE_URL/profile/faqs.php?checksum=$checksum",true,301);
	die;
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");
	
	$db=connect_db();
	$data=authenticated($checksum);
	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->display("faqs.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
