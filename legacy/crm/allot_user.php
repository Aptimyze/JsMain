<?php
/***************************************************************************************************************************
Filename    : allot_user.php
Description : Allot the profiles to the service executive so that it start the process of calls.
Created By  : Vibhor Garg
Created On  : 06 May 2008
****************************************************************************************************************************/
$path = $_SERVER['DOCUMENT_ROOT'];
include_once("connect.inc");
include_once($path."/mis/user_hierarchy.php");

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	$entryby=getname($cid);
	$smarty->assign("name",$entryby);

	if($CMDSubmit)
	{
		$error=0;

		if(trim($username)=='')
		{
			$error++;
			$smarty->assign("NO_USERNAME","Y");
		}
		else
		{
			$sql="SELECT ACTIVATED,PROFILEID,PHONE_RES, PHONE_MOB, EMAIL, CITY_RES, COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME='".addslashes(stripslashes($username))."'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				if($row['ACTIVATED']=="D")
				{
					$error++;
					$smarty->assign("DELETED","Y");
				}
				else
				{
					$profileid=$row['PROFILEID'];
					$ph_res=$row['PHONE_RES'];
					$ph_mob=$row['PHONE_MOB'];
					$email=$row['EMAIL'];

					$sql="SELECT REALLOTED_TO FROM incentive.SERVICE_ADMIN WHERE PROFILEID='$profileid'";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					if($row1=mysql_fetch_array($res1))
					{
						$error++;
						$smarty->assign("REALLOTED_TO",$row1['REALLOTED_TO']);
					}
				}
			}
			else
			{
				$error++;
				$smarty->assign("WRONG_USERNAME","Y");
			}
		}
		if($error)
		{
			user_hierarchy_array($entryby);
			$smarty->assign("ERROR","Y");
			$smarty->display("allot_user.htm");
		}
		else
		{
			if(trim($comments))
			{
				$sql="INSERT INTO incentive.SERVICE_DAILY_ALLOT (PROFILEID,REALLOTED_TO,REALLOT_TIME,REALLOTED_BY) VALUES ('$profileid','$allot_to',now(),'$entryby')";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$sql="SELECT ALLOTED_TO,ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{
					$preallot_to=$row1['ALLOTED_TO'];
					$preallot_time=$row1['ALLOT_TIME'];
				}
				
				$allot_time=date("Y-m-d H:i:s");	
				$handled_date=date("Y-m-d");
				$feedback_date=date("Y-m-d",time()+(30*86400));

				//$sql = "SELECT EXPIRY_DT FROM billing.SERVICE_STATUS,billing.PURCHASES WHERE SERVICE_STATUS.BILLID=PURCHASES.BILLID AND SERVICE_STATUS.PROFILEID = '$profileid' AND STATUS = 'DONE' AND ACTIVATED = 'Y' ORDER BY ID DESC LIMIT 1";
				$sql = "SELECT MAX(EXPIRY_DT) AS EXPIRY_DT FROM billing.SERVICE_STATUS WHERE PROFILEID = '$profileid' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%'";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row1=mysql_fetch_array($res1);
				$exp_date1=$row1['EXPIRY_DT'];
				$exp_date=explode("-",$exp_date1);
				$reconvince_time=@mktime(0,0,0,$exp_date[1],($exp_date[2]-10),$exp_date[0]);
 				$reconvince_date=date("Y-m-d",$reconvince_time);

                                $sql="INSERT INTO incentive.SERVICE_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,REALLOT_TIME,REALLOTED_TO,HANDLED_DT,FEEDBACK_DT,RECONVINCE_DT,ALLOT_COMMENTS,CALL_STATUS) VALUES('$profileid','$preallot_time','$preallot_to','$allot_time','$allot_to','$handled_date','$feedback_date','$reconvince_date','$comments',0)";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
				$id = mysql_insert_id_js();	
				$sql="INSERT INTO incentive.SERVICE_ADMIN_LOG (ID,PROFILEID,ALLOT_TIME,ALLOTED_TO,REALLOT_TIME,REALLOTED_TO,HANDLED_DT,FEEDBACK_DT,RECONVINCE_DT,ALLOT_COMMENTS,CALL_STATUS) VALUES('$id','$profileid','$preallot_time','$preallot_to','$allot_time','$allot_to','$handled_date','$feedback_date','$reconvince_date','$comments',0)";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
				$sql="INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,COMMENT,ENTRY_DT) VALUES('$profileid','$username','$entryby','$comments','$allot_time')";
                                mysql_query_decide($sql) or die("$sql".mysql_error_js());

				$smarty->assign("ERROR","Y");
                                $smarty->assign("arr",$arr);
                                $smarty->assign("username",$username);
                                $smarty->assign("allot_to",$allot_to);
                                $smarty->assign("allot_time",$allot_time);
				$smarty->assign("ALLOT","Y");
                        	$smarty->display("allot_user.htm");

			}
			else
			{
				user_hierarchy_array($entryby);
				$smarty->assign("NO_COMMENTS","Y");
				$smarty->assign("ERROR","Y");
				$smarty->assign("username",$username);
				$smarty->assign("allot_to",$allot_to);
				$smarty->assign("allot_time",$allot_time);
				$smarty->display("allot_user.htm");
			}
		}
	}
	else
	{
		user_hierarchy_array($entryby);
		$smarty->display("allot_user.htm");
	}
}
else
{
	$msg="Your session has been timed out  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

function user_hierarchy_array($entryby)
{
	global $smarty;
	$username_str = user_hierarchy($entryby);
	$arr = explode("','",ltrim(rtrim($username_str,"'"),"'"));
	$smarty->assign("arr",$arr);
}
?>
