<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc");

	$lang=$_COOKIE["JS_LANG"];
	
	$db=connect_db();
	$data=authenticated($checksum);
	if($data)
		login_relogin_auth($data);
	/******************************CODE ADDED FOR BMS*************************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);
	/****************************************************************************************/

	$smarty->html_optimize=true;	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));

	if($lang)
	{
		$smarty->assign("HEAD",$smarty->fetch($lang."_headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooternew.htm"));
	}
	else
	{
		if($mbureau=="bureau1")
                        $smarty->assign("HEAD",$smarty->fetch("top_band.htm"));
                else
                {
			$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
			$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		}
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	}
		$smarty->display("differentiators_at_glance.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
