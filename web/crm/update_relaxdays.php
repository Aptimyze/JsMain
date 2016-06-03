<?php

include("connect.inc");

if(authenticated($cid))
{
	$flag=0;
	$name= getname($cid);
	if($Submit)
	{
		$iserror = 0;

		if (trim($USERNAME) == "")
                {
                        $iserror++;
                        $smarty->assign('check_pid',1);
                }
		if ((trim($DAYS)=="") || (trim($DAYS) == '0') || !is_numeric($DAYS))
		{
			$iserror++;
                        $smarty->assign('check_days',1);
		}
		if (trim($DAYS) > 15)
		{
			$iserror++;
                        $smarty->assign('days_exceed',1);
		}
		if ($iserror > 0)
		{
			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("DAYS",$DAYS);
			$smarty->assign("flag",$flag);
                	$smarty->assign("cid",$cid);
                	$smarty->assign("name",$name);
                	$smarty->display("update_relaxdays.htm");
		}
		else
		{
			$flag=1;
			$valid_relax = 0;
			$USERNAME = stripslashes(addslashes($USERNAME));
			//$sql = "SELECT  c.PROFILEID ,  c.RELAX_DAYS FROM incentive.CRM_DAILY_ALLOT c , newjs.JPROFILE j WHERE j.PROFILEID=c.PROFILEID AND j.USERNAME='$USERNAME'";
			$sql = "SELECT  PROFILEID FROM newjs.JPROFILE WHERE USERNAME='$USERNAME'";
                	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                	if($myrow=mysql_fetch_array($result))
			{
	               		$profileid=$myrow['PROFILEID'];

				$sql="SELECT ID, RELAX_DAYS FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
				$res1 = mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row1 = mysql_fetch_array($res1);
				$$relaxdays = $row1['RELAX_DAYS'];
				$id=$row1['ID'];

				$sql1 = "SELECT COUNT(*) AS CNT FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$res1 = mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
				$row1 = mysql_fetch_array($res1);
				if ($row1['CNT'] > 0)
				{
					//$relaxdays = $myrow['RELAX_DAYS'];
					$relax_durtn = $relaxdays + $DAYS;

					if ($relax_days == 0)
					{
						if ($relax_durtn > 15)
						{	
							$smarty->assign("limitexceed","Y1");
						}
						else
							$valid_relax = 1;
					}
					else
					{
						if ($relax_durtn > 30)
							$smarty->assign("limitexceed","Y2");
						else
						{
							$valid_relax = 1;
						}
					//	$sql_update = "UPDATE incentive.CRM_DAILY_ALLOT SET RELAX_DAYS = '$relax_durtn' WHERE PROFILEID='$profileid'";
					//	mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());
					/*$msg= " Record Update<br>  ";
					$msg .="<a href=\"outbound.php?cid=$cid\">";
					$msg .="Continue </a>";
					$smarty->assign("MSG",$msg);
					$smarty->display("crm_msg.tpl");*/
					}
					if ( $valid_relax == 1)
					{
						$sql = "SELECT ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
						$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
						$row = mysql_fetch_array($res);

						$sql_update = "UPDATE incentive.CRM_DAILY_ALLOT SET RELAX_DAYS = '$relax_durtn' WHERE PROFILEID='$profileid' AND ALLOT_TIME='$row[ALLOT_TIME]'";
						mysql_query_decide($sql_update) or die("$sql_update".mysql_error_js());
					}
				}
				else
					$smarty->assign("record_deleted","Y");
			}
			else
			{
				$smarty->assign("norecord","Y");
			}
			
			$smarty->assign("flag",$flag);
			$smarty->assign("cid",$cid);
			$smarty->assign("name",$name);
			$smarty->display("update_relaxdays.htm");
		}
	}
	else
	{
		$smarty->assign("flag",$flag);
		$smarty->assign("name",$name);
		$smarty->assign("cid",$cid);
		$smarty->display("update_relaxdays.htm");
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
