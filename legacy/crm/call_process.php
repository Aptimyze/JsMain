<?php
/***************************************************************************************************************************
Filename    : call_process.php
Description : Display the his/her detailed data to the sales executive and handle the complete work process.
Created By  : Vibhor Garg
Created On  : 19 May 2008
****************************************************************************************************************************/
include_once("connect.inc");
include_once("viewprofilenew.php"); 
include_once("mainmenunew.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
        $name= getname($cid);
	$today=date("Y-m-d");
	if($donotcall)
	{
		$sql = "REPLACE INTO incentive.DO_NOT_CALL (PROFILEID,ENTRY_DT,USER,REMOVED) VALUES('$profileid',NOW(),'$name','N')";
                mysql_query_decide($sql) or die("$sql".mysql_error_js());

                $smarty->display("do_not_call.htm");
	}
else
{
	if($submit)
	{
		$will_pay = explode("|X|",$WILL_PAY);
		$WILL_PAY = $will_pay[0];
		if(($follow_time==0)&&($WILL_PAY!='N'))
                {
			if($flag == 'P')
		        {
				$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=1,ALTERNATE_NO='$ALTERNATE_NO',HANDLED_COMMENTS='$COMMENTS',COMFORT='$COMFORT' WHERE PROFILEID='$profileid'";
			}
			if($flag == 'C')
                	{
                        	if($CALL_TYPE=='S|X|S1$TextArea')
				{
					$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=2,ALTERNATE_NO='$ALTERNATE_NO',FEEDBACK_COMMENTS='$COMMENTS',CALL_TYPE='$CALL_TYPE',SUBTYPE='$SUBTYPE_TEXT' WHERE PROFILEID='$profileid'";
				}
				else
				{
					$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=2,ALTERNATE_NO='$ALTERNATE_NO',FEEDBACK_COMMENTS='$COMMENTS',CALL_TYPE='$CALL_TYPE',SUBTYPE='$SUBTYPE' WHERE PROFILEID='$profileid'";
				}
                        }
                	if($flag == 'E')
                	{
                        	$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=3,ALTERNATE_NO='$ALTERNATE_NO',WILL_PAY_AGAIN='$WILL_PAY',REASON='$REASON' WHERE PROFILEID='$profileid'";
                        }
                	if(($flag == 'F')||($flag == 'N'))
                	{
                        	$sql="SELECT CALL_STATUS FROM incentive.SERVICE_ADMIN WHERE PROFILEID='$profileid'";
                		$res=mysql_query_decide($sql) or die(mysql_error_js());
                		$row=mysql_fetch_array($res);
				$call_status=$row['CALL_STATUS'];
				if($call_status==0)
				{
					$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=1,ALTERNATE_NO='$ALTERNATE_NO',HANDLED_COMMENTS='$COMMENTS',COMFORT='$COMFORT',ON_TIME='Y' WHERE PROFILEID='$profileid'";
				}
				if($call_status==1) 
	                        {
					if($CALL_TYPE=='S|X|S1$TextArea')
	                                {
        	                                $sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=2,ALTERNATE_NO='$ALTERNATE_NO',FEEDBACK_COMMENTS='$COMMENTS',CALL_TYPE='$CALL_TYPE',SUBTYPE='$SUBTYPE_TEXT',ON_TIME='Y' WHERE PROFILEID='$profileid'";
                	                }
                        	        else
                                	{
                                        	$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=2,ALTERNATE_NO='$ALTERNATE_NO',FEEDBACK_COMMENTS='$COMMENTS',CALL_TYPE='$CALL_TYPE',SUBTYPE='$SUBTYPE',ON_TIME='Y' WHERE PROFILEID='$profileid'";
                                	}	
                        	}
                        	if($call_status==2) 
                        	{
					$sql="UPDATE  incentive.SERVICE_ADMIN SET CALL_STATUS=3,ALTERNATE_NO='$ALTERNATE_NO',WILL_PAY_AGAIN='$WILL_PAY',REASON='$REASON',ON_TIME='Y' WHERE PROFILEID='$profileid'";
                       	 	}
			}
		}
                else
                {
                	$sql="UPDATE incentive.SERVICE_ADMIN SET FOLLOWUP_DT='$follow_time',ALTERNATE_NO='$ALTERNATE_NO',WILL_PAY_AGAIN='$WILL_PAY' WHERE PROFILEID='$profileid'";
                }
                $res=mysql_query_decide($sql) or die(mysql_error_js());
                
		$sql4 = "INSERT INTO incentive.HISTORY (PROFILEID,USERNAME,ENTRYBY,COMMENT,ENTRY_DT) VALUES ('$profileid','$USERNAME','$name','$COMMENTS',now())";
                mysql_query_decide($sql4) or die("$sql4".mysql_error_js());
		
		if($ALTERNATE_NO && ($ALTERNATE_NO != $UNEDITED_ALTERNATE_NO))
                {
 	               $name = addslashes(stripslashes($name));
                       $sql_alt = "INSERT INTO incentive.PROFILE_ALTERNATE_NUMBER(PROFILEID,ALTERNATE_NUMBER,ENTRYBY,ENTRY_DT) VALUES('$profileid','$ALTERNATE_NO','$name',now())";
                       mysql_query_decide($sql_alt) or die("$sql_alt".mysql_error_js());
                }
		
		$msg ="Entry for <font color=\"blue\">$USERNAME</font> is done<br>";
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
	else
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

		$sql="SELECT ALTERNATE_NO,WILL_PAY_AGAIN,REASON,CALL_TYPE,SUBTYPE,CALL_STATUS FROM incentive.SERVICE_ADMIN WHERE PROFILEID='$profileid'";
		
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$alt_no=$row['ALTERNATE_NO'];
		$will_pay=$row['WILL_PAY_AGAIN'];
		$reason=$row['REASON'];
		$call_type=$row['CALL_TYPE'];
                $subtype=$row['SUBTYPE'];
		$call_status=$row['CALL_STATUS'];
		
		$sql="SELECT ENTRYBY,COMMENT,ENTRY_DT FROM incentive.HISTORY WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
                $res=mysql_query_decide($sql) or die(mysql_error_js());
		$i=1;
		while($myrow=mysql_fetch_array($res))
		{
			$values[] = array("SNO"=>$i,
					  "NAME"=>$myrow["ENTRYBY"],
					  "DATE"=>$myrow["ENTRY_DT"],
					  "COMMENTS"=>str_replace("\n","<br>",$myrow["COMMENT"])
					 );
			$i++;

		}

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

		$willpay_val = populate_will_pay($will_pay);
		$smarty->assign("WILL_PAY",$willpay_val);
		
		$calltype_val = populate_call_type($call_type);
                $smarty->assign("CALL_TYPE",$calltype_val);

		$subtypeopt="";
                $subtypeopt=populate_call_subtype($call_type,$subtype);
                $smarty->assign("SUBTYPE",$subtypeopt);
		
		$reasonopt="";
                $reasonopt=willpay_populate_reason($will_pay,$reason);
                $smarty->assign("REASON",$reasonopt);

		$smarty->assign("ACTIVE_STATUS_MESSAGE",$active_status_message);

                $checksum=md5($profileid)."i".$profileid;
		$smarty->assign("follow_time","0000:00:00 00:00");
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
		if(($flag=='F')||($flag=='N'))
		{
			if($call_status==0)
				$flag='P';
			elseif($call_status==1)
				$flag='C';
			else
				$flag='E';
		}
                $smarty->assign("flag",$flag);
		$smarty->display("call_process.htm");
	}
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
?>
