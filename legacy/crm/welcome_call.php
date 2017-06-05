<?php
include_once("connect.inc");
include_once("../profile/functions.inc");
if(authenticated($cid))
{
	if($submit)
	{
		$sql_w = "SELECT PROFILEID FROM incentive.WELCOME_CALLS WHERE PROFILEID='$profileid'";
		$res_w = mysql_query_decide($sql_w) or die("$sql_w".mysql_error_js());
		if(mysql_num_rows($res_w) == 0)
		{
			$sql_jp = "SELECT PROFILEID FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res_jp = mysql_query_decide($sql_jp) or die("$sql_jp".mysql_error_js());
			if(mysql_num_rows($res_jp) > 0)
				$smarty->assign("SHOW_LINKS",1);
			else
				$smarty->assign("NOT_FOUND",1);
		}
		else
			$smarty->assign("ALREADY_CALLED",1);
	}
	elseif($calling_done)
	{
		$percent = profile_percent($profileid);
		$sql_ins = "REPLACE INTO incentive.WELCOME_CALLS(PROFILEID, CALLED_BY, CALL_DATE, PROFILE_PERCENT_INIT) VALUES('$profileid','$name',NOW(),$percent)";
		mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
	}
	$smarty->assign("profileid",$profileid);
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->display("welcome_call.htm");
}
else
{
	$msg="Your session has been timed out  ";
	$msg .="<a href=\"index.php\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
