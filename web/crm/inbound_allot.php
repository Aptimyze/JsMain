<?php
/***************************************************************************************************************************
Filename        :       inbound_allot.php
Included        :       connect.inc
Description     :       used for inbound manual allotment.
***************************************************************************************************************************/

include("connect.inc");
include("mainmenunew.php"); 
include("viewprofilenew.php"); 
include("functions_inbound.php"); 
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include(JsConstants::$docRoot."/commonFiles/flag.php");

if(authenticated($cid))
{
	$active_status_message=get_active_status($ACTIVATED,$INCOMPLETE);
	$smarty->assign("ACTIVE_STATUS_MESSAGE",$active_status_message);

	$call_source_arr = populate_call_source();
	$smarty->assign("call_source_arr",$call_source_arr);

	$query_type_arr = populate_query_type();
	$smarty->assign("query_type_arr",$query_type_arr);

        $name= getname($cid);
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);

	if($submit)
	{
		$is_error = 0;

		if(trim($call_source) == "")
		{
			$is_error++;
			$smarty->assign("CHECK_CALL_SOURCE","Y");
		}
		if(trim($query_type) == "")
		{
			$is_error++;
			$smarty->assign("CHECK_QUERY_TYPE","Y");
		}
		if(trim($comments) == "")
		{
			$is_error++;
			$smarty->assign("CHECK_COMMENTS","Y");
		}
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
		if(trim($WILL_PAY) == "")
                {
                        $is_error++;
                        $smarty->assign("WILL_PAY","y");
                }

		// Paid check added
                $sql = "SELECT SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
                $row = mysql_fetch_array($res);
                $subscription = $row["SUBSCRIPTION"];
		if((strstr($subscription,"F")!="") || (strstr($subscription,"D")!="")){
			$is_error++;
			$smarty->assign("PAID_PROFILE","Y");
		}
		// Paid check ends
				
		if($is_error > 0)
		{
	                $willpay_val = populate_will_pay('');
	                $smarty->assign("WILL_PAY",$willpay_val);
	                $reasonopt=willpay_populate_reason('','');
	                $smarty->assign("REASON",$reasonopt);

			$smarty->assign("WILL_PAY",$willpay_val);
			$smarty->assign("username",$username);
			$smarty->assign("email",$email);
			$smarty->assign("phone_mob",$phone_mob);
			$smarty->assign("phone_res",$phone_res);
			$smarty->assign("activated",$activated);
			$smarty->assign("ALTERNATE_NO",$ALTERNATE_NO);
			$smarty->assign("call_source",$call_source);
			$smarty->assign("query_type",$query_type);
			$smarty->assign("comments",$comments);

			$smarty->display("inbound_allot.htm");
		}
		else
		{
			$alloted_to = $name;
			$now = date('Y-m-d G:i:s');
			$mode = "I";
			$username = addslashes(stripslashes($username));
			$comments = addslashes(stripslashes($comments));

			$sql="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_assoc($res))
			{
				if($alloted_to==$row['ALLOTED_TO'])
				{
					$sql3 = "UPDATE incentive.MAIN_ADMIN SET STATUS='C',CONVINCE_TIME=now(),WILL_PAY='$WILL_PAY',REASON='$REASON',COMMENTS='".addslashes($COMMENTS)."' WHERE PROFILEID='$profileid'";
					mysql_query_decide($sql3) or die("3 $sql3".mysql_error_js());
				}
			}
			else
			{
				$sql_ins = "INSERT INTO incentive.INBOUND_ALLOT (PROFILEID,USERNAME,CALL_SOURCE,QUERY_TYPE,COMMENTS,ALLOTED_TO,ALLOT_TIME) VALUES('$profileid','$username','$call_source','$query_type','$comments','$alloted_to','$now')";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());

				$sql_ins = "REPLACE INTO incentive.MAIN_ADMIN(PROFILEID,ALLOT_TIME,ALLOTED_TO,MODE,COMMENTS,STATUS,WILL_PAY,REASON) VALUES('$profileid','$now','$alloted_to','$mode','$comments','C','$WILL_PAY','$REASON')";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());

				$sql_ins = "INSERT INTO incentive.CRM_DAILY_ALLOT(PROFILEID,ALLOT_TIME,ALLOTED_TO) SELECT PROFILEID,ALLOT_TIME,ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
			}

			$sql_ins = "INSERT INTO incentive.HISTORY(PROFILEID,USERNAME,ENTRYBY,MODE,DISPOSITION,VALIDATION,COMMENT,ENTRY_DT) VALUES('$profileid','$username','$alloted_to','$mode','$WILL_PAY','$REASON','$comments','$now')";
			mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());

			//Intimation for user 
                        $exceed = 0;
                        $sql ="SELECT count(*) as cnt FROM incentive.MAIN_ADMIN WHERE STATUS!='P' AND WILL_PAY!='NI' AND ALLOTED_TO='$name' HAVING cnt>450";
                        $res= mysql_query_decide($sql) or die(mysql_error_js());
                        $row = mysql_fetch_array($res);
                        if($row["cnt"])
                                $exceed = $row["cnt"]-450;
                        $smarty->assign("exceed",$exceed);
                        //end

			$smarty->assign("submitted","1");
			$smarty->display("inbound_allot.htm");
		}
	}
	else
	{
		if(!can_allot($profileid))
		{
			$sqlo="SELECT EXECUTIVE FROM newjs.OFFLINE_REGISTRATION WHERE PROFILEID='$profileid'";
		        $reso=mysql_query($sqlo) or logError($sqlo);
		        $rowo=mysql_fetch_array($reso);
		        $smarty->assign("ofl_se",$rowo["EXECUTIVE"]);
                        $smarty->assign("CANNOT_ALLOT",1);
		}
		else
		{
        	$sql = "SELECT USERNAME,EMAIL,GENDER,PHONE_MOB,PHONE_RES,ACTIVATED,INCOMPLETE FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	        $res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
	        $row = mysql_fetch_array($res);

		$username = $row["USERNAME"];
		$EMAIL = $row['EMAIL'];
		$email = explode("@",$EMAIL);
		$email = $email[0]."@xxx.com";
		$crm_gender = $row["GENDER"];
		$phone_mob = $row["PHONE_MOB"];
		$phone_res = $row["PHONE_RES"];	
		$activated = $row["ACTIVATED"];

		$smarty->assign("username",$username);
		$smarty->assign("email",$email);
		$smarty->assign("phone_mob",$phone_mob);
		$smarty->assign("phone_res",$phone_res);
		$smarty->assign("activated",$activated);

		$sql="SELECT COUNT(*) AS CNT FROM billing.PURCHASES WHERE PROFILEID='$profileid' AND STATUS='DONE'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		if($row['CNT']>0)
		{
			$smarty->assign("WAS_PAID","Y");
		}
                $smarty->assign("profileid",$profileid);

		$pmsg=viewprofile($username,"internal");
                $checksum=md5($profileid)."i".$profileid;
		profileview($profileid,$checksum);
		$msg=$smarty->fetch("login1.htm");
                $smarty->assign("msg",$msg);
                $smarty->assign("pmsg",$pmsg);
		}

		$willpay_val = populate_will_pay('');
                $smarty->assign("WILL_PAY",$willpay_val);
                $reasonopt="";
                $reasonopt=willpay_populate_reason('','');
                $smarty->assign("REASON",$reasonopt);

                $smarty->display("inbound_allot.htm");
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
?>
