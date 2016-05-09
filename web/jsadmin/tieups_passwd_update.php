<?php

include("connect.inc");

if(authenticated($cid))
{
	if($passwd == "")
	{
		$display = "Password field empty. Updation failed.";
		$smarty->assign("CID",$cid);
		$smarty->assign("DISPLAY",$display);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->display("tieups_result.htm");
	}	
	else
	{
		$sql = "Update tieups.PSWRDS set PASSWORD = '$passwd' where RESID=$resid ";
	        $result = mysql_query_decide($sql,$db) or die(mysql_error_js());

	        $display = "Updation of password successfully done.";
	
		$smarty->assign("CID",$cid);
	        $smarty->assign("DISPLAY",$display);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
        	$smarty->display("tieups_result.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

