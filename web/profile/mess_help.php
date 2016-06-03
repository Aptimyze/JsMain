<?php
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

        /*************************************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);

        //$regionstr=8;
        //include("../bmsjs/bms_display.php");
        /************************************************End of Portion of Code*****************************************/
        //$db=connect_db();
	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	//$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	$smarty->display("mess_help.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
