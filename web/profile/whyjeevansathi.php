<?php
	header("Location: /");
	exit;
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include("connect.inc");
	include_once("payment_array.php");

	$lang=$_COOKIE["JS_LANG"];
	if($lang=="deleted")
		$lang="";
	
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
//$zonestr="18";
//include("../bmsjs/bms_display.php");
                                                                                                 
/************************************************End of Portion of Code*****************************************/
	$smarty->assign("CHECKSUM",$checksum);

	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

	if($lang)
	{
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
		$smarty->assign("RIGHTPANEL",$smarty->fetch($lang."_rightpanel.htm"));
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
	}
	else
	{
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("RIGHTPANEL",$smarty->fetch("rightpanel.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	}

        $smarty->assign("PAY_ERISHTA",$pay_erishta);
        $smarty->assign("PAY_ECLASSIFIED",$pay_eclassified);
        $smarty->assign("PAY_EVALUE",$pay_evalue);

	$smarty->display("whyjeevansathi.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
