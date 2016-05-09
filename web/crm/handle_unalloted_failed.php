<?php

include("connect.inc");
include("history.php");

if(authenticated($cid))
{
	$name= getname($cid);
	$city=get_centre($cid);
	
	$sql1="SELECT PROFILEID FROM incentive.UNALLOTED_FAILED_PAYMENT WHERE ALLOCATED ='N' AND CITY='$city'";
	$result1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
        while($myrow1=mysql_fetch_array($result1))
	{
		$skip=1;
		$profileid=$myrow1['PROFILEID'];

		$sql="UPDATE incentive.UNALLOTED_FAILED_PAYMENT SET ALLOCATED='L',LOCK_TIME=now() WHERE PROFILEID='$profileid'";
        	mysql_query_decide($sql) or die("$sql1".mysql_error_js());

		$sql = "SELECT SUBSCRIPTION,USERNAME,PHONE_MOB,PHONE_RES FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                if($myrow=mysql_fetch_array($result))
                {
			$USERNAME=$myrow['USERNAME'];
			$phone=$myrow['PHONE_RES'];
			$mobile=$myrow['PHONE_MOB'];

                        if($myrow['SUBSCRIPTION'])
				$skip=0;
                        else
			{
				$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
                                $res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                if($row=mysql_fetch_array($res))
					$skip=0;
                                else
                                {
					$sql="SELECT COUNT(*) as cnt FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE' AND ENTRY_DT>=DATE_SUB(CURDATE(),INTERVAL 37 DAY)";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row=mysql_fetch_array($res);
					if($row['cnt']>0)
						$skip=0;
                                }
                        }
                        $smarty->assign("PROFILEID",$profileid);
		}

		if($skip)
			break;
		else
		{ 
			$sql="UPDATE incentive.UNALLOTED_FAILED_PAYMENT SET ALLOCATED='Y' WHERE PROFILEID='$profileid'";
                        mysql_query_decide($sql) or die("$sql".mysql_error_js());
		}

	}

	if(!$skip)
	{
		$smarty->assign("No_Records",'Y');
		$smarty->assign("cid",$cid);
	}
	else
	{
		$smarty->assign("USERNAME",$USERNAME);
		$smarty->assign("cid",$cid);
		$smarty->assign("name",$name);
		$smarty->assign("mobile",$mobile);
		$smarty->assign("phone",$phone);
		$smarty->assign("city",$city);
	}
	$smarty->display("handle_unalloted_failed.htm");
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
