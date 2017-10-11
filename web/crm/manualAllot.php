<?php
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("functions_inbound.php");
if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	$entryby=getname($cid);
	$smarty->assign("name",$entryby);
	$call_source_arr = populate_call_source();
        $smarty->assign("call_source_arr",$call_source_arr);

        $privilage = explode("+",getprivilage($cid));

	if($CMDSubmit)
	{
		$error=0;
		if(trim($call_source) == "")
                {
                        $error++;
                        $smarty->assign("CHECK_CALL_SOURCE","Y");
                }
		if(trim($username)=='')
		{
			$error++;
			$smarty->assign("NO_USERNAME","Y");
		}
		$sql="SELECT ACTIVATED,PROFILEID,PHONE_RES, PHONE_MOB, EMAIL, CITY_RES, COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME='".addslashes(stripslashes($username))."'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$profileid=$row['PROFILEID'];
			if($row['ACTIVATED']=="D")
			{
				$error++;
				$smarty->assign("DELETED","Y");
			}
			else
			{
				$ph_res=$row['PHONE_RES'];
				$ph_mob=$row['PHONE_MOB'];
				$email=$row['EMAIL'];

				$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{	
					if($row1['ALLOTED_TO']==$entryby)
					{	
                                        	$excl_rest_dt=date('Y-m-d',time()-7*86400);
                                        	$sql_history="SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                                        	$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error($db));
                                        	if($row_history = mysql_fetch_array($res_history))
                                        	{
                                                	if($row_history["ENTRY_DT"]>=$excl_rest_dt)
							{
                                                        	$sql="UPDATE incentive.CRM_DAILY_ALLOT set RELAX_DAYS=15 WHERE PROFILEID='$profileid' AND ALLOTED_TO='$entryby' ORDER BY ID DESC LIMIT 1";
                                                                mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                                                $msg="Allocation Extension given for 15 days.<br>";
								$msg .="<a href=\"manualAllot.php?cid=$cid\">";
                                                                $msg .="Continue </a>";
                                                                $smarty->assign("MSG",$msg);
                                                                $smarty->display("jsadmin_msg.tpl");
								die();
							}
							else
							{
								$error++;
                                                                $smarty->assign("CANT_ALLOT","Y");
							}
                                        	}
					}
					else
					{	
						$error++;
						$smarty->assign("CANT_ALLOT","Y");
					}
				}
				/* same city Check*/
				$sql="SELECT NAME AS CITY FROM incentive.LOCATION WHERE VALUE='".$row['CITY_RES']."'";
                                $res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row1=mysql_fetch_assoc($res1);
                                $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$entryby'";
                                $res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
                                $row2=mysql_fetch_assoc($res2);
                                $center=strtoupper($row2['CENTER']);
                                if($row1['CITY']!=$center)
                                {	
					$sql_alloted="SELECT ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' ORDER BY ALLOT_TIME DESC LIMIT 1";
					$res_alloted=mysql_query_decide($sql_alloted) or die("$sql_alloted".mysql_error_js());
					if($row_alloted=mysql_fetch_array($res_alloted))
                                	{
                                        	if($row_alloted['ALLOTED_TO']==$entryby)
                                        	{
                                                	$excl_rest_dt=date('Y-m-d',time()-7*86400);
                                                	$sql_history="SELECT ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ID DESC LIMIT 1";
                                                	$res_history = mysql_query($sql_history,$db) or die("$sql_history".mysql_error($db));
                                                	if($row_history = mysql_fetch_array($res_history))
                                                	{
                                                        	if($row_history["ENTRY_DT"]< $excl_rest_dt)
                                                        	{
                                                                	$error++;
                                                                	$smarty->assign("CANT_ALLOT","Y");
                                                        	}
							}
						}
					}
				}
			}
		}
		else
		{
			$error++;
			$smarty->assign("WRONG_USERNAME","Y");
		}

		if($error)
		{
			$smarty->assign("ERROR","Y");
			$smarty->assign("call_source",$call_source);
			$smarty->display("manualAllot.htm");
		}
		else
		{
			if(in_array("IUO",$privilage))
				$mode='O';
			else
				$mode='I';
			if(trim($comments))
			{
				$sql_main="INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,RES_NO,MOB_NO,EMAIL,STATUS,WILL_PAY,REASON) VALUES('$profileid',now(),'$entryby','$mode','".addslashes($ph_res)."','".addslashes($ph_mob)."','$email','C','$will_pay_val','$call_source')";
				mysql_query_decide($sql_main) or die("$sql_main".mysql_error_js());

				$sql_daily="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOT_TIME,ALLOTED_TO) SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
				mysql_query_decide($sql_daily) or die("$sql_daily".mysql_error_js());
				

				$sql_manual="INSERT INTO incentive.MANUAL_ALLOT (PROFILEID, ALLOT_TIME, ALLOTED_TO, ALLOTED_BY, COMMENTS, CALL_SOURCE) VALUES ('$profileid',now(),'$entryby','$entryby','".addslashes(stripslashes($comments))."','$call_source')";
				mysql_query_decide($sql_manual) or die("$sql_manual".mysql_error_js());
				
				$sqlh = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES ('$profileid','".addslashes(stripslashes($username))."','$entryby','$mode','$will_pay_val','$call_source','".addslashes(stripslashes($comments))."',now())";
	                        mysql_query_decide($sqlh) or die("$sqlh".mysql_error_js());

				$msg="Manual Allocation Done Successfully.<br>";
				$msg .="<a href=\"manualAllot.php?cid=$cid\">";
				$msg .="Continue </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
			}
			else
			{
				$smarty->assign("NO_COMMENTS","Y");
				$smarty->assign("ERROR","Y");
				$smarty->assign("username",$username);
				$smarty->assign("allot_to",$allot_to);
				$smarty->assign("allot_time",$allot_time);
				$smarty->display("manualAllot.htm");
			}
		}
	}
	else
	{
		$smarty->display("manualAllot.htm");
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
?>
