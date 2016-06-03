<?php

include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	$flag=0;
	$name= getname($cid);
        $privilage = explode("+",getprivilage($cid));
        if(in_array("SLHD",$privilage) || in_array("SLSUP",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage) || in_array("TRNG",$privilage))
                $limit =0;
        else
                $limit =5;

	if($GetHistory)
	{
		$flag=1;
		$sql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($myrow=mysql_fetch_array($result))
		{
	                $profileid=$myrow['PROFILEID'];

			if($limit){	
				$limitCount =getHistoryCount($profileid);
				if($limitCount>=5)
					$limit =$limitCount;
			}
			$user_values=gethistory($USERNAME,$limit);
			$smarty->assign("ROW",$user_values);
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PROFILEID",$profileid);
		}
                else
		{
                        $smarty->assign("wrong_username","Y");
		}
		$sql="SELECT COUNT(*) as cnt FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		if($row['cnt']>0)
		{
			$smarty->assign("SHOW_FOLLOW","Y");
		}
		else
		{
			$smarty->assign("SHOW_FOLLOW","N");
		}
		//$privilage=getprivilage($cid);
		//$priv=explode("+",$privilage);
		if(in_array("IA",$privilage))
		{
			$smarty->assign("ADMIN","Y");
			$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND MODE='O'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$smarty->assign("FOUND_IN","A");
				$orig_alloted_to=$row['ALLOTED_TO'];
				$smarty->assign("orig_alloted_to",$orig_alloted_to);
			}
			else
			{
				$sql="SELECT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND MODE='O'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$smarty->assign("FOUND_IN","C");
					$claimed_by=$row['ENTRYBY'];
					$smarty->assign("claimed_by",$claimed_by);
				}
				else
				{
					$smarty->assign("FOUND_IN","N");
				}
			}
		}
		$smarty->assign("flag",$flag);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->display("userhistory.htm");
	}
	else
	{
//		echo "How did u get here playa' ? Close the window. ";
//		exit;
		$smarty->assign("flag",$flag);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("userhistory.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("crm_msg.tpl");
}
?>
