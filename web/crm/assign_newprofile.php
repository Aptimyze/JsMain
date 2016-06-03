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
	$allotTimeAvail =false;
        if(in_array("SLHD",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage))
		$allotTimeAvail =true;
	$smarty->assign("allotTimeAvail",$allotTimeAvail);

	if($CMDSubmit)
	{
		// New Disposition added
		$will_pay = explode("|X|",$WILL_PAY);
		$will_pay_val =$will_pay[0];	

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
			elseif(!can_allot($profileid))
			{
				$error++;
                                $smarty->assign("CANNOT_ALLOT",1);
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
					$error++;
					$smarty->assign("ALLOTED_TO",$row1['ALLOTED_TO']);
				}
				// commented by Shiv to remove region condition
				/*else
				{
					$sql="SELECT LEFT(PRIORITY,4) AS CITY FROM incentive.BRANCH_CITY WHERE VALUE='$row[CITY_RES]'";
					$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row1=mysql_fetch_array($res1);

					$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$allot_to'";
					$res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row2=mysql_fetch_array($res2);
					$center=strtoupper($row2['CENTER']);

					$sql="SELECT LEFT(PRIORITY,4) AS CITY FROM incentive.BRANCH_CITY WHERE UPPER(LABEL)='$center'";
					$res2=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					$row2=mysql_fetch_array($res2);

					if($row1['CITY']!=$row2['CITY'])
					{
						$error++;
						$smarty->assign("OUT_OF_REGION","Y");
					}

					// changed by shiv on 30th may to allow NRI profiles to be alloted to NOIDA
					if($row["COUNTRY_RES"]!=51)
					{
						$error=0;
						$smarty->assign("OUT_OF_REGION","");
					}
				}*/
			}
			/*else
			{
				$sql="SELECT ENTRYBY FROM incentive.CLAIM WHERE PROFILEID='$profileid'";
				$res1=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{
					$error++;
					$smarty->assign("CLAIMED_BY",$row1['ENTRYBY']);
				}
			}*/
		}
		else
		{
			$error++;
			$smarty->assign("WRONG_USERNAME","Y");
		}

		if($error)
		{
			/*$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%' AND ACTIVE='Y' AND CENTER='NOIDA'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$arr[]=$row['USERNAME'];
			}*/

			$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$entryby' AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			$emp_id=$row['EMP_ID'];

			$emp_id_str="'$emp_id'";
			$emp_id_str1="";
			
			$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID='$emp_id' AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
			while($row=mysql_fetch_array($res))
			{
				if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
				{
					$emp_id_str1 .= "'$row[EMP_ID]',";
				}
			}

			$emp_id_str = $emp_id_str1 . $emp_id_str;
			$val=0;
			while(1)
			{
				$emp_id_str1=substr($emp_id_str1, 1, strlen($emp_id_str1) - 3);
				$emp_id_str2="";
				
				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID IN ('$emp_id_str1') AND COMPANY='JS'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

				if(mysql_num_rows($res) == 0)
				{
					break;
				}  
				else
				{
					while($row=mysql_fetch_array($res))
					{
						if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
						$emp_id_str2 .= "'$row[EMP_ID]',";
					}
					if(!$emp_id_str2)
						break;
					$emp_id_str1=$emp_id_str2;
					$emp_id_str = $emp_id_str2 . $emp_id_str;
					$val++;
					if($val == 15)
					{
						echo $val;
						die();
					}
				}
			}

			$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str) AND COMPANY='JS' AND (PRIVILAGE LIKE '%IUO%' OR PRIVILAGE LIKE '%IUI%') AND ACTIVE='Y'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			while($row=mysql_fetch_array($res))
			{
				$arr[]=$row['USERNAME'];
			}

                        // New Disposition added
                        $willpay_val = populate_will_pay('MA');
                        $smarty->assign("WILL_PAY",$willpay_val);

			$smarty->assign("arr",$arr);
			$smarty->assign("ERROR","Y");
			$smarty->assign("call_source",$call_source);
			$smarty->display("assign_newprofile.htm");
		}
		else
		{
			$sql="SELECT PRIVILAGE FROM jsadmin.PSWRDS WHERE USERNAME='$allot_to' AND ACTIVE='Y'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row=mysql_fetch_array($res);
			if(strpos($row["PRIVILAGE"],"IUO"))
				$mode='O';
			else
				$mode='I';

			if(trim($comments))
			{
				if($allot_time)
				{
					//$today=date("Y-m-d");
					//if(substr($allot_time,0,10)!=$today)
					//{
						$sql="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID, ALLOT_TIME, ALLOTED_TO) VALUES ('$profileid','$allot_time','$allot_to')";
						mysql_query_decide($sql) or die("$sql".mysql_error_js());
					//}

					$sql="INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,RES_NO,MOB_NO,EMAIL,STATUS,WILL_PAY,REASON) VALUES('$profileid','$allot_time','$allot_to','$mode','".addslashes($ph_res)."','".addslashes($ph_mob)."','$email','C','$will_pay_val','$call_source')";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}
				else
				{
					$sql="INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,RES_NO,MOB_NO,EMAIL,STATUS,WILL_PAY,REASON) VALUES('$profileid',now(),'$allot_to','$mode','".addslashes($ph_res)."','".addslashes($ph_mob)."','$email','C','$will_pay_val','$call_source')";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());

					$sql="INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOT_TIME,ALLOTED_TO) SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
					mysql_query_decide($sql) or die("$sql".mysql_error_js());
				}

				$sql="INSERT INTO incentive.MANUAL_ALLOT (PROFILEID, ALLOT_TIME, ALLOTED_TO, ALLOTED_BY, COMMENTS, CALL_SOURCE) VALUES ('$profileid',now(),'$allot_to','$entryby','".addslashes(stripslashes($comments))."','$call_source')";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
				
				$sqlh = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES ('$profileid','".addslashes(stripslashes($username))."','$entryby','$mode','$will_pay_val','$call_source','".addslashes(stripslashes($comments))."',now())";
	                        mysql_query_decide($sqlh) or die("$sqlh".mysql_error_js());

				$sql="UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL='N' WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());

				if(!$allot_time)
					$allot_time=date("Y-m-d H:i:s");

				$to="vivek@jeevansathi.com";
				$subject="Manual allotment of user $username";
				$message="Username : $username\nAlloted to : $allot_to\nAlloted by : $entryby\nAllot time : $allot_time\nTime : ".date("Y-m-d H:i:s")."\nComments : $comments"
;
				send_email($to,$message,$subject);

				$msg="Record Inserted.<br>";
				$msg .="<a href=\"assign_newprofile.php?cid=$cid\">";
				$msg .="Continue </a>";
				$smarty->assign("MSG",$msg);
				$smarty->display("jsadmin_msg.tpl");
			}
			else
			{
				/*$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%' AND ACTIVE='Y' AND CENTER='NOIDA'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$arr[]=$row['USERNAME'];
				}*/

				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$entryby' AND COMPANY='JS'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
				$row=mysql_fetch_array($res);
				$emp_id=$row['EMP_ID'];

				$emp_id_str="'$emp_id'";
				$emp_id_str1="";
				
				$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID='$emp_id' AND COMPANY='JS'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
					
				while($row=mysql_fetch_array($res))
				{
					if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
					{
						$emp_id_str1 .= "'$row[EMP_ID]',";
					}
				}

				$emp_id_str = $emp_id_str1 . $emp_id_str;
				$val=0;
				while(1)
				{
					$emp_id_str1=substr($emp_id_str1, 1, strlen($emp_id_str1) - 3);
					$emp_id_str2="";
					
					$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID IN ('$emp_id_str1') AND COMPANY='JS'";
					$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

					if(mysql_num_rows($res) == 0)
					{
						break;
					}  
					else
					{
						while($row=mysql_fetch_array($res))
						{
							if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
							$emp_id_str2 .= "'$row[EMP_ID]',";
						}
						if(!$emp_id_str2)
							break;
						$emp_id_str1=$emp_id_str2;
						$emp_id_str = $emp_id_str2 . $emp_id_str;
						$val++;
						if($val == 15)
						{
							echo $val;
							die();
						}
					}
				}

				$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str) AND COMPANY='JS' AND (PRIVILAGE LIKE '%IUO%' OR PRIVILAGE LIKE '%IUI%') AND ACTIVE='Y'";
				$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

				while($row=mysql_fetch_array($res))
				{
					$arr[]=$row['USERNAME'];
				}

				$smarty->assign("NO_COMMENTS","Y");
				$smarty->assign("ERROR","Y");
				$smarty->assign("arr",$arr);
				$smarty->assign("username",$username);
				$smarty->assign("allot_to",$allot_to);
				$smarty->assign("allot_time",$allot_time);
				$smarty->display("assign_newprofile.htm");
			}
		}
	}
	else
	{
		/*$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%IUO%' AND ACTIVE='Y' AND CENTER='NOIDA'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$arr[]=$row['USERNAME'];
		}*/

		$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE USERNAME='$entryby' AND COMPANY='JS'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		$emp_id=$row['EMP_ID'];

		$emp_id_str="'$emp_id'";
		$emp_id_str1="";
		
		$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID='$emp_id' AND COMPANY='JS'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			
		while($row=mysql_fetch_array($res))
		{
			if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
			{
				$emp_id_str1 .= "'$row[EMP_ID]',";
			}
		}

		$emp_id_str = $emp_id_str1 . $emp_id_str;
		$val=0;
		while(1)
		{
			$emp_id_str1=substr($emp_id_str1, 1, strlen($emp_id_str1) - 3);
			$emp_id_str2="";
			
			$sql="SELECT EMP_ID FROM jsadmin.PSWRDS WHERE HEAD_ID IN ('$emp_id_str1') AND COMPANY='JS'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());

			if(mysql_num_rows($res) == 0)
			{
				break;
			}  
			else
			{
				while($row=mysql_fetch_array($res))
				{
					if(strstr($emp_id_str , "'$row[EMP_ID]'") == "")
					$emp_id_str2 .= "'$row[EMP_ID]',";
				}
				if(!$emp_id_str2)
					break;
				$emp_id_str1=$emp_id_str2;
				$emp_id_str = $emp_id_str2 . $emp_id_str;
				$val++;
				if($val == 15)
				{
					echo $val;
					die();
				}
			}
		}

		$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE EMP_ID IN ($emp_id_str) AND COMPANY='JS' AND (PRIVILAGE LIKE '%IUO%' OR PRIVILAGE LIKE '%IUI%') AND ACTIVE='Y'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$arr[]=$row['USERNAME'];
		}
		$smarty->assign("arr",$arr);

		// New Disposition added
                $willpay_val = populate_will_pay('MA');
                $smarty->assign("WILL_PAY",$willpay_val);

		$smarty->display("assign_newprofile.htm");
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
