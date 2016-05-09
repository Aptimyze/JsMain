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
$data=authenticated($cid);

if(isset($data))
{
	if($submit)
	{
		if(trim($users))
		{
	                $users1 = explode(",",$users);
			for($a=0;$a<count($users1);$a++)
			{
				$users1[$a]=trim($users1[$a]);
			}
			$users=implode("','",$users1);

			$sql="INSERT IGNORE INTO newjs.HOMEPAGE_PROFILES SELECT PROFILEID,GENDER,'Y' FROM newjs.JPROFILE WHERE USERNAME IN ('".$users."')";
			$res=mysql_query_decide($sql) or logError(mysql_error_js(),$sql);
			$cnt = mysql_affected_rows_js();

			if($cnt==0)
			{
	   			$msg="The Username already exists on the HomePage<br>  ";
			}
			else
			{
	   			$msg="$cnt Username has been added successfully<br>  ";
			}
                        $msg .="<a href=\"mainpage.php?cid=$cid\">";
                        $msg .="Go To MainPage </a>";
                        $smarty->assign("MSG",$msg);
                        $smarty->display("jsadmin_msg.tpl");

		}
	}
	else
	{
		$smarty->display("add_homepage_user.htm");
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
