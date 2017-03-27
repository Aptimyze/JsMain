<?php

	echo "<META HTTP-EQUIV=\"refresh\" CONTENT=\"0;URL=http://www.jeevansathi.com/P/payment.php?checksum=$checksum\">";
	//echo "<center>If you are seeing this page then please go Back and refresh the page.</center>";
	die();

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
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
	
	$smarty->display("memberships.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
	
?>
