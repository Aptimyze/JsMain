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
	if($data)
		login_relogin_auth($data);
	if(($data["BUREAU"]==1 && ($_COOKIE['JSMBLOGIN'] || $mbureau=="bureau")) || $mb_io==1)
        {
                $fromprofilepage=1;
                mysql_select_db_js('marriage_bureau');
                include('../marriage_bureau/connectmb.inc');
                $mbdata=authenticatedmb($mbchecksum);
                if(!$mbdata)timeoutmb();
                $smarty->assign("source",$mbdata["SOURCE"]);
                $smarty->assign("mbchecksum",$mbdata["CHECKSUM"]);
                mysql_select_db_js('newjs');
                //$data=login_every_user($profileid);
		$mbureau="bureau1";
        }
	$lang=$_COOKIE["JS_LANG"];

	/*****************Portion of Code added for display of Banners*******************************/
	$smarty->assign("data",$data["PROFILEID"]);
	$smarty->assign("bms_topright",18);
	$smarty->assign("bms_right",28);
	$smarty->assign("bms_bottom",19);
	$smarty->assign("bms_left",24);
	$smarty->assign("bms_new_win",32);

	/***********************End of Portion of Code*****************************************/

	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("coming_from_index","1");
	$smarty->assign("coming_from_community","1");
	$smarty->assign("head_tab",'home');

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
	$smarty->display("us_matrimonial.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
