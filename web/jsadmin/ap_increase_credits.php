<?php
include("connect.inc");

$db=connect_db();

if(authenticated($cid))
{
	$user=getname($cid);
	if($increaseCredits)
	{
		if($addCredits)
		{
			$sql="UPDATE billing.SERVICE_STATUS SET TOTAL_COUNT=TOTAL_COUNT+$addCredits WHERE PROFILEID='$profileid' AND SERVEFOR='I'";
			mysql_query_decide($sql) or die("Error while updating counts   ".$sql."  ".mysql_error_js());
			$searchProfile=1;
			$userName=$searchedUserName;
		}
	}
	if($searchProfile)
	{
		$sql="SELECT PROFILEID,SUBSCRIPTION FROM newjs.JPROFILE WHERE USERNAME='$userName'";
		$res=mysql_query_decide($sql) or die("Error while fetching profileid   ".$sql."   ".mysql_error_js());
		if(mysql_num_rows($res))
		{
			$row=mysql_fetch_assoc($res);
			if($row["SUBSCRIPTION"])
			{
				$profileid=$row["PROFILEID"];
				$sub=explode(",",$row["SUBSCRIPTION"]);
				if(in_array("I",$sub))
				{
					$sql="SELECT TOTAL_COUNT,USED_COUNT FROM billing.SERVICE_STATUS WHERE PROFILEID='$profileid' AND SERVEFOR='I'";
					$res=mysql_query_decide($sql) or die("Error while fetching call count   ".$sql."  ".mysql_error_js());
					if(mysql_num_rows($res))
					{
						$row=mysql_fetch_assoc($res);
						$smarty->assign("totalCount",$row["TOTAL_COUNT"]);	
						$smarty->assign("usedCount",$row["USED_COUNT"]);
						for($i=1;$i<=100;$i++)
							$credits[]=$i;
						$smarty->assign("credits",$credits);
					}
					else
						$smarty->assign("errorMessage","Some billing error has occurred");
				}
				else
					$smarty->assign("errorMessage","User is not valid Intro Call customer");
				$smarty->assign("profileid",$profileid);
			}
			else
				$smarty->assign("errorMessage","User is not valid Intro Call customer");
		}
		else
			$smarty->assign("errorMessage","Invalid username");
		$smarty->assign("userName",$userName);
	}
	$smarty->assign("cid",$cid);
	$smarty->assign("user",$user);
	$smarty->display("ap_increase_credits.htm");
}
else
{
	$msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
