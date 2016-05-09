<?php
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it

	include("connect.inc"); 
	//$db=connect_db();
	//$data=authenticated($checksum);
print_r($_SERVER);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));

        if(strstr($_SERVER["HTTP_USER_AGENT"],"Windows"))
                $smarty->assign("VIDEO_SUPPORT","YES");
        else
                $smarty->assign("VIDEO_SUPPORT","NO");
	
	$smarty->display("video_ad.htm");
	
	// flush the buffer
	if($zipIt)
		ob_end_flush();
?>
