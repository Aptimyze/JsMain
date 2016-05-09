<?php

	include('connect.inc');
	if (authenticated($cid))
	{
		if ($ncr!='Y')
		{
			$sql = "UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED ='Y' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}

		$user = getuser($cid);

		$sql = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL ='N' WHERE PROFILEID='$profileid'";
                mysql_query_decide($sql) or die("$sql".mysql_error_js());

		$sql = "REPLACE INTO incentive.DO_NOT_CALL (PROFILEID,ENTRY_DT,USER,REMOVED) VALUES('$profileid',NOW(),'$user','N')";
                mysql_query_decide($sql) or die("$sql".mysql_error_js());

		$smarty->display("do_not_call.htm");
	}
	else //user timed out
	{
		$msg="Your session has been timed out  ";
		$msg .="<a href=\"index.php\">";
		$msg .="Login again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
?>
