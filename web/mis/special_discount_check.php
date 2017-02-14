<?
/***********************************************************************
Created by sriram to search for users who can avail 40% special discount
***********************************************************************/
include("connect.inc");
                                                                                                                             
$db = connect_misdb();
                                                                                                                             
$data = authenticated($cid);
                                                                                                                             
if($data)
{
	if($submit)
	{
		$sql = "SELECT USERNAME,PROFILEID FROM newjs.JPROFILE WHERE";
		if($criteria=="email")
		{
			$sql .= " EMAIL='$phrase'";
		}
		else
		{
			$sql .= " USERNAME='$phrase'";
		}
		$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
		if($row = mysql_fetch_array($res))
		{
			$pid = $row['PROFILEID'];
			$username = $row['USERNAME'];

			$sql_score = "SELECT * FROM billing.SCORE_MAILER WHERE PROFILEID='$pid'";
			$res_score = mysql_query_decide($sql_score) or die($res_score.mysql_query_decide());
			if(mysql_num_rows($res_score) > 0)
				$smarty->assign("FOUND",1);
			else
				$smarty->assign("FOUND",0);

			$smarty->assign("username",$username);
			$smarty->assign("RESULT_FOUND",1);
		}
		else
		{
			$smarty->assign("NO_RESULT_FOUND",1);
		}
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("special_discount_check.htm");
	}
	else
	{
		$smarty->assign("user",$user);
		$smarty->assign("cid",$cid);
		$smarty->display("special_discount_check.htm");
	}
}
else
{
        $smarty->assign("user",$user);
        $smarty->display("jsconnectError.tpl");
}

?>
