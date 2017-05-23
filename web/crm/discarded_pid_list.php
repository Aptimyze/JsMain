<?php
/***************************************************************************************************************
* FILE NAME     : add_homepage_user.php
* DESCRIPTION   : Adds a user to Home Page
* CREATION DATE : 11 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

include("connect.inc");

$smarty->assign("cid",$cid);
$today=date("Y-m-d");

if(authenticated($cid))
{
	if($submit)
	{
		if(trim($profileids))
		{
	                $pids = explode(",",$profileids);
			for($a=0;$a<count($pids);$a++)
			{
				if ($pids[$a]!= '')
					$pidarr[$a]=trim($pids[$a]);
			}
			print_r($pidarr);
			$pids=implode(",",$pidarr);
			$sql = "SELECT PROFILEID FROM incentive.PROFILEID_DELETED WHERE DATE ='$today'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());//logError(mysql_error_js(),$sql);
			if($row = mysql_fetch_array($res))
			{
				$profileidstr = $row['PROFILEID'];
			}

			if ($profileidstr)
			{
				$message="<font color=\"red\">A record for &nbsp;&nbsp;<b>".$today."</b>&nbsp;&nbsp;already exists</font>"."<br>".$profileidstr;
				$flag = 1;
				$smarty->assign("message",$message);
			}
			else
			{
				$sql="INSERT IGNORE INTO incentive.PROFILEID_DELETED VALUES('','$pids','$user',now())";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$flag =2;
			}
			$smarty->assign("flag",$flag);
			$smarty->display("discarded_pid_list.htm");
		}
		else
			$smarty->display("discarded_pid_list.htm");
	}
	else
	{
		$smarty->display("discarded_pid_list.htm");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
