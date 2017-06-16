<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include(realpath("../../profile/connect.inc"));	

       $lang=$_COOKIE['JS_LANG'];

//	$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/profile/templates";
//	$smarty->compile_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/profile/templates_c";

	$db=connect_db();
	$data=authenticated($checksum);
	if($data)
		login_relogin_auth($data);	
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
	$smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
	$smarty->assign("coming_from_index","1");
	$smarty->assign("coming_from_community","1");
	$smarty->assign("head_tab",'home');
	if($lang)
	{
		$smarty->assign("HEAD",$smarty->fetch($lang."_head.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch($lang."_leftpanel.htm"));
		$smarty->assign("FOOT",$smarty->fetch($lang."_foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch($lang."_subfooter.htm"));
	}
	else
	{
		$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
		$smarty->assign("LEFTPANEL",$smarty->fetch("leftpanelnew.htm"));
		$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
		$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
	}

	$smarty->display("indian-religions.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
