<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include_once("../profile/connect.inc"); 
	$db=connect_db();
	$data=authenticated($checksum);
	if($data)
		login_relogin_auth($data);
	
	/* earlier code 
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
	$smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
	$smarty->assign("SUBHEADER",$smarty->fetch("../success/subheader.htm"));
	$smarty->assign("SUBHEADER1",$smarty->fetch("../success/subheader1.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("../success/topleft.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("../success/leftpanel.htm"));
	*/	
	

	$smarty->assign("REVAMP_LEFT_PANEL",$smarty->fetch("leftpanel_settings.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("head_tab",'my jeevansathi');
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
	rightpanel($data);
	$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
	$smarty->assign("REVAMP_RIGHT_PANEL",$smarty->fetch("revamp_rightpanel.htm"));
	$smarty->assign("PROFILECHECKSUM",$profilechecksum);
	$smarty->assign("CHECKSUM",$checksum);








	//new changed by puneet
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	//$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));	
        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));


	//new change ends by puneet
	
	
	if($year)
	{
		switch($year)
		{
			case "2000" :	$smarty->display("../success/success_stories_2000.htm");
							break;
			case "2001" :	$smarty->display("../success/success_stories_2001.htm");
							break;
			case "2002" :	$smarty->display("../success/success_stories_2002.htm");
							break;
			case "2003" :	$smarty->display("../success/success_stories_2003.htm");
							break;
			case "2004" :	$smarty->display("../success/success_stories_2004.htm");
							break;
		}	
	}	
	else
	{
		if($tpl)
			$smarty->display("../success/$tpl");
		elseif($send_story)
			$smarty->display("../success/upload_success_stories.htm");
		elseif($send_story1)
			$smarty->display("../success/success_stories_1.htm");
		else
			$smarty->display("../success/page_2006.htm");
			//$smarty->display("../success/page_2005_1.htm");
			//$smarty->display("../success/success_stories.htm");
	}

	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
