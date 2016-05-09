<?php
/**
*       Filename        :       outbound2.php
**/
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include("mainmenunew.php");
include("history.php"); 
include("viewprofilenew.php"); 
//include(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
        $name= getname($cid);
	$today=date("Y-m-d");
	$smarty->assign("subs_expiry",$subs_expiry);
	$privilage = explode("+",getprivilage($cid));
	if(in_array("SLHD",$privilage) || in_array("SLSUP",$privilage) || in_array("P",$privilage) || in_array("MG",$privilage) || in_array("TRNG",$privilage))
		$limit =0;
	else
	{
		$limitCount =getHistoryCount($profileid);
		if($limitCount>=5)
			$limit =$limitCount;
		else
			$limit =5;
	}
	
	if($submit)
	{
		$is_error=0;

		//code added by sriram to insert mode depending on inbound/outbound users.
		if(in_array("IUO",$privilage))
			$mode = "O";
		elseif(in_array("IUI",$privilage))
			$mode = "I";
		//code added by sriram to insert mode depending on inbound/outbound users.

		if(trim($ALTERNATE_NO) != "" && !is_numeric($ALTERNATE_NO))
		{
			$is_error++;
			$smarty->assign("check_alternate","Y");
		}
	
		if(($follow=="F" && $follow_date==0) || $is_error > 0)
                {
                        $values =gethistory($profileid,$limit);
                        $smarty->assign("ROW",$values);

                        if($follow=="F" && $follow_date==0)
                                $check_followtime = "Y";
                        $smarty->assign("check_followtime",$check_followtime);

			$followup_dd = get_followup_dd($profileid,$name);

			$smarty->assign("follow_time",$followup_dd[0]);
		        $smarty->assign("hour",$followup_dd[1]);
		        $smarty->assign("min",$followup_dd[2]);
                        $smarty->assign("follow",$follow);
                        $smarty->assign("ALTERNATE_NO",$ALTERNATE_NO);
                        $smarty->assign("profileid",$profileid);
			$smarty->assign("COMMENTS",$COMMENTS);
			if($WILL_PAY=='AA|X')
                                $will_pay = explode("|X",$WILL_PAY);
                        else
                                $will_pay = explode("|X|",$WILL_PAY);
			$willpay_val = populate_will_pay($will_pay[0]);
                	$smarty->assign("WILL_PAY",$willpay_val);
                                                                                                                            
                	$reasonopt="";
                	$reasonopt=willpay_populate_reason($will_pay[0],$REASON);
                	$smarty->assign("REASON",$reasonopt);


			$smarty->assign("USERNAME",$USERNAME);
			$smarty->assign("PHONE_MOB",$PHONE_MOB);
			$smarty->assign("PHONE_RES",$PHONE_RES);
			$checksum=md5($profileid)."i".$profileid;
			$smarty->assign("profileid",$profileid);
			$smarty->assign("CHECKSUM",$checksum);
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);

	                $pmsg=viewprofile($USERNAME,"internal");

			profileview($profileid,$checksum);
			$msg= $smarty->fetch("login1.htm");

        	        $smarty->assign("msg",$msg);
                	$smarty->assign("pmsg",$pmsg);

			$smarty->display("outbound2.htm");
			exit;
		}
		else
		{
			if($WILL_PAY=='AA|X')
                        {
                                $will_pay = explode("|X",$WILL_PAY);
                                $WILL_PAY = $will_pay[0];
                        }
                        else
                        {
                                $will_pay = explode("|X|",$WILL_PAY);
                                $WILL_PAY = $will_pay[0];
                        }     

			//Added By lavesh on 4 sep 2006
			$sql="UPDATE incentive.UNALLOTED_FAILED_PAYMENT SET ALLOCATED='Y' WHERE PROFILEID='$profileid'";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());
			//Ends Here

			if ($subs_expiry)
			{
				$relax=15;

				$sql="SELECT COUNT(*) AS CNT FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID = '$profileid' AND DATE_ADD( ALLOT_TIME, INTERVAL( $relax + RELAX_DAYS ) DAY ) >= NOW()";
                		$res = mysql_query_decide($sql) or die(mysql_error_js());
				$row=mysql_fetch_array($res);

				if($row['CNT']==0)
				{
					$sql = "REPLACE INTO incentive.MAIN_ADMIN_LOG SELECT * FROM incentive.MAIN_ADMIN WHERE PROFILEID = '$profileid'";
        	        		$res = mysql_query_decide($sql) or die(mysql_error_js());
                                                                                                                            
					$sql = "DELETE FROM incentive.MAIN_ADMIN WHERE PROFILEID = '$profileid'";
					mysql_query_decide($sql) or die(mysql_error_js());
				}

				$sql = "UPDATE incentive.SUBSCRIPTION_EXPIRY_PROFILES SET HANDLED='Y' , HANDLE_DT=IF(HANDLE_DT<>0,HANDLE_DT,NOW()) WHERE PROFILEID = '$profileid'";
				mysql_query_decide($sql) or die(mysql_error_js());
			}
			
			if($orders)
			{
				if($WILL_PAY)
				{
					$val="P";
				}
				else
				{
					$val="D";
				}
				$sql="UPDATE billing.ORDERS SET STATUS='$val' WHERE PROFILEID='$profileid' AND STATUS=''";
				mysql_query_decide($sql) or die("$sql".mysql_error_js());
			}
			$convincearr=array($chour,$cmin,"00");
			$convince=implode(":",$convincearr);

			$sql = "SELECT COUNT(*) as CNT FROM incentive.MAIN_ADMIN WHERE PROFILEID = '$profileid'";
			$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                        $row = mysql_fetch_array($res);
			if($follow!='F')
			{
				if ($row['CNT'] > 0)
				{
					$sql2 = "UPDATE incentive.MAIN_ADMIN SET STATUS='C',CLAIM_TIME=if(CONVINCE_TIME='0000-00-00 00:00:00',NOW(),if(CONVINCE_TIME<CURDATE(),CONVINCE_TIME,CLAIM_TIME)),CONVINCE_TIME=now(),WILL_PAY='$WILL_PAY',REASON='$REASON',COMMENTS='".addslashes($COMMENTS)."' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name'";
					mysql_query_decide($sql2) or die("2 $sql2".mysql_error_js());
				}
				else
				{
					$sql2 = "INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,WILL_PAY,REASON) VALUES('$profileid',NOW(),NOW(),'$name','C','$ALTERNATE_NO','$mode',NOW(),'".addslashes($COMMENTS)."','$PHONE_RES','$PHONE_MOB','$WILL_PAY','$REASON')";
					mysql_query_decide($sql2) or die("2 $sql2".mysql_error_js());

					// query added on 16.01.2006 by Shobha to change availability status in ADMIN POOL
					$sql_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL ='N' WHERE PROFILEID = '$profileid'";
					mysql_query_decide($sql_pool) or die("$sql_pool".mysql_error_js());


					// query added by Shobha on 11.04.2006 to mark that the profile has been handled
					$sql_allot_update = "UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED ='Y' WHERE PROFILEID = '$profileid'";
					mysql_query_decide($sql_allot_update) or die("$sql_allot_update".mysql_error_js());

					$sql_ins = "INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME, RELAX_DAYS) SELECT PROFILEID , ALLOTED_TO , ALLOT_TIME, '$relax' FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
					mysql_query_decide($sql_ins) or  die("$sql_ins".mysql_error_js());
				}
				// New implement for subscription expiry, marking the status as 'N' 
                                $sql = "UPDATE incentive.SUBSCRIPTION_EXPIRY_PROFILES SET HANDLED='N' WHERE PROFILEID = '$profileid'";
                                mysql_query_decide($sql) or die(mysql_error_js());

			}
			else
			{
				if(strstr($follow_hour,'am'))
				{
					if($follow_hour=="9 am")
                                        	$follow_hour = "09";
					else
						$follow_hour = substr($follow_hour,0,2);
				}
				else
				{
					if($follow_hour=="12 pm")
                                                $follow_hour = "12";
                                        else
                                                $follow_hour = substr($follow_hour,0,1)+12;
				}
				$follow_time = date("Y-m-d",JSstrToTime($follow_date))." ".$follow_hour.":".$follow_min.":"."00";
                                if ($row['CNT'] > 0)
				{
					$sql3 = "UPDATE incentive.MAIN_ADMIN SET STATUS='$follow',FOLLOWUP_TIME='$follow_time',ALTERNATE_NO='$ALTERNATE_NO',CLAIM_TIME=if(CONVINCE_TIME='0000-00-00 00:00:00',NOW(),if(CONVINCE_TIME<CURDATE(),CONVINCE_TIME,CLAIM_TIME)),CONVINCE_TIME=now(),WILL_PAY='$WILL_PAY',REASON='$REASON', COMMENTS='".addslashes($COMMENTS)."' WHERE PROFILEID='$profileid' AND ALLOTED_TO='$name'";
					mysql_query_decide($sql3) or die("3 $sql3".mysql_error_js());
				}
				else
				{
                                        $sql3 = "INSERT INTO incentive.MAIN_ADMIN (PROFILEID,ALLOT_TIME,CLAIM_TIME,ALLOTED_TO,STATUS,ALTERNATE_NO,FOLLOWUP_TIME,MODE,CONVINCE_TIME,COMMENTS,RES_NO,MOB_NO,WILL_PAY,REASON) VALUES('$profileid',NOW(),NOW(),'$name','$follow','$ALTERNATE_NO','$follow_time','$mode',NOW(),'".addslashes($COMMENTS)."','$PHONE_RES','$PHONE_MOB','$WILL_PAY','$REASON')";
					mysql_query_decide($sql3) or die("3 $sql3".mysql_error_js());

					// query added on 16.01.2006 by Shobha to change availability status in ADMIN POOL
					$sql_pool = "UPDATE incentive.MAIN_ADMIN_POOL SET ALLOTMENT_AVAIL ='N' WHERE PROFILEID = '$profileid'";
                                        mysql_query_decide($sql_pool) or die("$sql_pool".mysql_error_js());

					// query added by Shobha on 11.04.2006 to mark that the profile has been handled
					$sql_allot_update = "UPDATE incentive.PROFILE_ALLOCATION_TECH SET HANDLED ='Y' WHERE PROFILEID = '$profileid'";
                                        mysql_query_decide($sql_allot_update) or die("$sql_allot_update".mysql_error_js());

					$sql_ins = "INSERT INTO incentive.CRM_DAILY_ALLOT (PROFILEID,ALLOTED_TO,ALLOT_TIME, RELAX_DAYS) SELECT PROFILEID , ALLOTED_TO , ALLOT_TIME, '$relax' FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
					mysql_query_decide($sql_ins) or  die("$sql_ins".mysql_error_js());
				}
			}
                        $sql4 = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES ('$profileid','".addslashes($USERNAME)."','".addslashes($name)."','$mode','$WILL_PAY','$REASON','".addslashes($COMMENTS)."',now())";
			mysql_query_decide($sql4) or die("4 $sql4".mysql_error_js());

			//**Code added by sriram on 7th June 2007 to store alternate mumber separately.**//
			if($ALTERNATE_NO)
			{
				$name = addslashes(stripslashes($name));
				$sql_alt = "INSERT INTO incentive.PROFILE_ALTERNATE_NUMBER(PROFILEID,ALTERNATE_NUMBER,ENTRYBY,ENTRY_DT) VALUES('$profileid','$ALTERNATE_NO','$name',now())";
				mysql_query_decide($sql_alt) or die("$sql_alt".mysql_error_js());
			}
			//**End of - Code added by sriram on 7th June 2007 to store alternate mumber separately.**//

			$msg ="Entry for <font color=\"blue\">$USERNAME</font> is done<br>";

			 //Intimation for user 
                        $sql ="SELECT count(*) as cnt FROM incentive.MAIN_ADMIN WHERE STATUS!='P' AND WILL_PAY!='NI' AND ALLOTED_TO='$name' HAVING cnt>450";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());
                        $row = mysql_fetch_array($res);
                        if($row["cnt"])
                        {
                                $exceed = $row["cnt"]-450;
                                $msg .="You have reached your allocation limit and have <font color=\"blue\">$exceed</font> extra profiles<br>";
                        }
                        //end

			$msg .= "<a href=\"\" onclick= \"window.close()\">Close Window</a>";
													 
			$smarty->assign("name",$name);
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("incentive_msg.tpl");

			echo "<br>";
			echo "<script language='JavaScript'>
				 opener.location.reload(true);
			       </script>";
			die;
		}
	}
	else
	{
		if($unfos)
		{
			$sql_v="insert ignore into jsadmin.UNALLOTED_FREE_ONLINE_VIEWED (VIEWED) values ('$profileid')";
                        mysql_query_decide($sql_v) or die(mysql_error_js());
			unset($unfos);
		}
		if($orders)
		{
			$sql="SELECT ID,ORDERID,SERVICEMAIN,CURTYPE,SERVEFOR,PAYMODE,ENTRY_DT FROM billing.ORDERS WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$arr[$i]["orderid"]=$row['ORDERID']."-".$row['ID'];
					$arr[$i]["entry_dt"]=$row['ENTRY_DT'];
					$servefor=$row['SERVEFOR'];
					$service=$row['SERVICEMAIN'];
					$paymode=$row['PAYMODE'];
					$curtype = $row['CURTYPE'];
					$arr[$i]["paymode"]=get_paymode($paymode,$curtype);
					$arr[$i]["service"]=get_service($service);
					$i++;
				}while($row=mysql_fetch_array($res));
			}
			$smarty->assign("arr",$arr);
		}
        	$sql= "SELECT USERNAME,EMAIL,GENDER,PHONE_MOB,PHONE_RES,ACTIVATED,INCOMPLETE FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	        $result = mysql_query_decide($sql) or die("4".mysql_error_js());
	        $myrow=mysql_fetch_array($result);
		
		$NAME_OF_USER = get_name_of_user($profileid);

		$USERNAME=$myrow["USERNAME"];
		$EMAIL=$myrow['EMAIL'];
		$email=explode("@",$EMAIL);
		$EMAIL=$email[0]."@xxx.com";
		$CRM_GENDER=$myrow["GENDER"];
		$PHONE_MOB=$myrow["PHONE_MOB"];
		$PHONE_RES=$myrow["PHONE_RES"];	
		$ACTIVATED=$myrow["ACTIVATED"];
		$active_status_message=get_active_status($ACTIVATED,$INCOMPLETE);

		$sql="SELECT ALTERNATE_NO,COMMENTS,WILL_PAY,REASON FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$alt_no=$row['ALTERNATE_NO'];
		$comments=$row['COMMENTS'];
		$will_pay=$row['WILL_PAY'];
		$reason = $row['REASON'];

		$values =gethistory($profileid,$limit);	
		$sql="SELECT COUNT(*) AS cnt from billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		if($row['cnt']>0)
		{
			$smarty->assign("WAS_PAID","Y");
		}

                $smarty->assign("ROW",$values);

	        $smarty->assign("NAME_OF_USER",$NAME_OF_USER);
	        $smarty->assign("USERNAME",$USERNAME);
	        $smarty->assign("EMAIL",$EMAIL);
	        $smarty->assign("CRM_GENDER",$CRM_GENDER);
		$smarty->assign("PHONE_MOB",$PHONE_MOB);
		$smarty->assign("PHONE_RES",$PHONE_RES);
		$smarty->assign("ALTERNATE_NO",$alt_no);
		$smarty->assign("COMMENTS",$comments);

		//$smarty->assign("WILL_PAY",$will_pay);

		$willpay_val = populate_will_pay($will_pay);
		$smarty->assign("WILL_PAY",$willpay_val);

		$reasonopt="";
		$reasonopt=willpay_populate_reason($will_pay,$reason);
		$smarty->assign("REASON",$reasonopt);

		$smarty->assign("ACTIVE_STATUS_MESSAGE",$active_status_message);

                $checksum=md5($profileid)."i".$profileid;

		$followup_dd = get_followup_dd($profileid,$name);

		$smarty->assign("follow_time",$followup_dd[0]);
	        $smarty->assign("hour",$followup_dd[1]);
        	$smarty->assign("min",$followup_dd[2]);
                $smarty->assign("profileid",$profileid);
                $smarty->assign("CHECKSUM",$checksum);
		$smarty->assign("name",$name);
                $smarty->assign("cid",$cid);
                $smarty->assign("profileid",$profileid);

		$pmsg=viewprofile($USERNAME,"internal");
		profileview($profileid,$checksum);
		$msg=$smarty->fetch("login1.htm");
                $smarty->assign("msg",$msg);
                $smarty->assign("pmsg",$pmsg);

		//$smarty->assign("subs_expiry",$subs_expiry);
                $smarty->display("outbound2.htm");
	}

}
else//user timed out
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.php\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

function get_service($service)
{
	$sql="SELECT NAME FROM billing.SERVICES WHERE SERVICEID='$service'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_array($res);
	$msg=$row['NAME'];
        return $msg;
}

function get_paymode($paymode,$curtype)
{
        //if(strlen($paymode)>8)
	if(stristr("cheque",$paymode))
        {
		$type="Cheque";
        }
        elseif (stristr("card",$paymode))
        {
		$type="Card";
        }

	//$curr=substr($paymode,0,2);

	//if($curr=="rs")
	if ($curtype == 'RS')
	{
                $msg="Tried by $type in Rupees";
	}
	elseif ($curtype == 'DOL')
	{
                $msg="Tried by $type in USD";
	}

        return $msg;
}
function get_followup_dd($profileid,$name)
{
	//FollowUp Dropdown
	$followup_dd[0]="<option value=\"" . 0 . "\">" . "Select" . "</option>\n";
	$sql ="select m.STATUS, c.ALLOT_TIME, c.RELAX_DAYS from incentive.CRM_DAILY_ALLOT c,incentive.MAIN_ADMIN m WHERE c.PROFILEID=m.PROFILEID AND m.PROFILEID='$profileid' AND m.ALLOTED_TO='$name' AND m.ALLOT_TIME=c.ALLOT_TIME";
	$res =mysql_query_decide($sql) or  die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res)){
		$allotTime =$row['ALLOT_TIME'];
		$relaxDays =$row['RELAX_DAYS'];
		$status    =$row['STATUS'];
		if($status=='R')
			$duration ='25';
		elseif($status=='P' || $status=='S'){
			$sql1 ="SELECT SERVICEID FROM billing.SERVICE_STATUS WHERE ACTIVE = 'Y' AND SERVEFOR LIKE '%F%' AND PROFILEID='$profileid' ORDER BY EXPIRY_DT DESC LIMIT 1";
			$res1 =mysql_query_decide($sql1) or  die("$sql1".mysql_error_js());
			if($row1=mysql_fetch_array($res1))
			{
				$serviceObj = new Services;
				$duration =$serviceObj->getDuration($row1["SERVICEID"],$period = 'D')+15;
			}
		}
		else
			$duration ='15';
		$totDays = $duration+$relaxDays;
	}
	else{
		$totDays = 15;
		$allotTime = date("Y-m-d");
	}
	$start_dt = date("d M Y",time());
	$totDays = $totDays-((time()-JSstrToTime($allotTime))/86400);
	$followup_dd[0].="<option value=\"" . $start_dt . "\">" . $start_dt . "</option>\n";
	for($x=0;$x<$totDays;$x++)
	{
		$follow_dts = date("d M Y",(time()+(($x+1)*86400)));
		$followup_dd[0].="<option value=\"" . $follow_dts . "\">" . $follow_dts . "</option>\n";
	}
	for($x=0,$hour=8;$x<=12;$x++)
	{
		if($hour>11)
			$hour=1;
		else
			$hour++;
		if($x<3)
			$suffix = "am";
		else
			$suffix = "pm";
		$followup_dd[1].="<option value=\"" . $hour." ".$suffix . "\">" . $hour." ".$suffix . "</option>\n";
	}
	for($x=0;$x<60;$x=$x+15)
	{
		if(!$x)
			$followup_dd[2].="<option value=\"" . $x . "\">" . $x.$x . "</option>\n";
		else
			$followup_dd[2].="<option value=\"" . $x . "\">" . $x . "</option>\n";
	}

	return $followup_dd;
}
?>
