<?php
include("connect.inc");

if(authenticated($cid))
{
	if($flag == "edit")
	{
		$sql = "Select * from tieups.PSWRDS where RESID = $resid";	
                $result = mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow = mysql_fetch_array($result);

		$username = $myrow["USERNAME"];
		$password = $myrow["PASSWORD"];
		
		$smarty->assign("USERNAME",$username);
		$smarty->assign("PASSWORD",$password);
		$smarty->assign("RESID",$resid);
		$smarty->assign("CID",$cid);
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->display("tieups_modify_passwd.htm");
		
	}
	elseif ($flag == "delete")
	{
		$sql = "Delete from tieups.PSWRDS where RESID = $resid";
		$result = mysql_query_decide($sql,$db) or die(mysql_error_js());
		$display = "Username successfully deleted.";
	
		$smarty->assign("CID",$cid);
		$smarty->assign("DISPLAY",$display);	
		$smarty->assign("HEAD",$smarty->fetch("head.htm"));
		$smarty->display("tieups_result.htm");
	}
	else
	{
		echo "Error !!! Not possible to reach here.";
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
