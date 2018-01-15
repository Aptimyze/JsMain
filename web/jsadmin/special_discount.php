<?php
include("connect.inc");
                                                                                                                             
$db = connect_db();
                                                                                                                             
$data = authenticated($cid);
if($data)
{
	if($submit)
       	{
		$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$phrase'";
		$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		if($row = mysql_fetch_array($result))
		{
			$pid = $row['PROFILEID'];
			$sql_score = "SELECT * FROM mailer.DISCOUNT_MAILER WHERE PROFILE_ID='$pid'";
			$res_score =mysql_query_decide($sql_score) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			if(mysql_num_rows($res_score) > 0)
			{
				$smarty->assign("FOUND",1);
				$row=mysql_fetch_array($res_score);
				$smarty->assign("DATE",$row[VALID_TILL]);
			}
			else
				$smarty->assign("FOUND",0);
			$smarty->assign("username",$phrase);
	  		$smarty->assign("RESULT_FOUND",1);
		}
		else
		{
		  	$smarty->assign("NO_RESULT_FOUND",1);
		}
	}
	$smarty->assign("cid",$cid);
	$smarty->display("special_discount.htm");
	

}


?>
