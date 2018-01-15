<?php
	//include("../manoj/doc.php"); 
	
	//to zip the file before sending it
	$zipIt = 0;
	if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
		$zipIt = 1;
	if($zipIt)
		ob_start("ob_gzhandler");
	//end of it
	
	include("connect.inc");

	if(strstr($_SERVER['HTTP_USER_AGENT'],'MSIE 5.5'))
		$smarty->assign("class","hand");
	else
		$smarty->assign("class","pointer");
	if($summon)
	{
		$smarty->assign("summon",'1');
	}
	if($grievance)
	{
		$smarty->assign("grievance",'1');
	}


	$smarty->assign("googleApiKey",$googleApiKey);
	$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
	$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
	$smarty->display("summon.htm");
	
		// flush the buffer
		if($zipIt)
					ob_end_flush();
?>
