<?php
	include_once("connect.inc");
	connect_db();
	$data=authenticated();
	$smarty->assign("PROFILE_CHECKSUM",$profilechecksum);
	if($data['PROFILEID'])
		include_once("viewprofile.php");
	else
	{	
		/*************Portion of Code added for display of Banners*****************************/
		$smarty->assign("NO_BOTTOM_ADSENSE","1");
		$smarty->assign("data",$data["PROFILEID"]);
		$smarty->assign("bms_topright",11);
		$smarty->assign("bms_middle",12);
		$smarty->assign("bms_bottom",13);
		$smarty->assign("bms_new_win",38);
		/***********************End of Portion of Code*****************************************/


		$smarty->assign("CAME_FROM_TIMEDOUT","1");
		$smarty->assign("METHOD", "GET");
		$smarty->assign("REQUESTEDURL","/profile/viewprofile.php?checksum=$checksum&profilechecksum=$profilechecksum&MYMOBILE=$MYMOBILE&EditWhatNew=$EditWhatNew");
		$smarty->assign("RELOGIN","Y");
		$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
		$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
		$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
		//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
		//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
		$smarty->assign("head_tab",'my jeevansathi');
		$smarty->assign("login_mes",'Please login to continue');
		$smarty->display("logout_1.htm");
	}
?>

