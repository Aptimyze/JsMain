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
/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data",$data["PROFILEID"]);
$smarty->assign("bms_topright",18);
$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom",19);
$smarty->assign("bms_left",24);
$smarty->assign("bms_new_win",32);
/************************************************End of Portion of Code*****************************************/
//added by lavesh for revamp.
include_once("../../profile/sphinx_search_function.php");//to be tested later
savesearch_onsubheader($data["PROFILEID"]);//to be tested later
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));//Added for revamp
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("head_tab",'my jeevansathi');
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));

	$smarty->display("community.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
