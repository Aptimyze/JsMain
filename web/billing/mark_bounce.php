<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("comfunc_sums.php");
include("bounced_mail.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Membership.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$data=authenticated($cid);
$flag=0;

if(isset($data))
{
	$bounce_reason_arr = populate_bounce_reason();
	$smarty->assign("bounce_reason_arr",$bounce_reason_arr);
	if($CMDSubmit)
	{
		$membershipObj = new Membership;
		$user=getname($cid);

		$sql="SELECT MEMBERSHIP,PROFILEID,USERNAME,WALKIN,DUEAMOUNT,EMAIL FROM billing.PURCHASES WHERE BILLID='$billid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$profileid=$row['PROFILEID'];
		$walkin=$row['WALKIN'];
		$username=$row['USERNAME'];
		$dueamt=$row['DUEAMOUNT'];
		$cemail=$row['EMAIL'];
		$cmembership=$row['MEMBERSHIP'];

		if($cmdcheck=='stop')
                {
			$status='STOPPED';
			$membershipObj->change_status($billid,$status);
			$membershipObj->stop_service($billid,$profileid);
		}

		/*code to change the due amount of last billid*/
		$dueamt+=$amt;
		$sql="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt' WHERE BILLID='$billid'";
		mysql_query_decide($sql) or die(mysql_error_js());
		/*end of code*/

		/*to send mails to alloted persons*/	
		$sql_allot="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid'";
		$res_allot=mysql_query_decide($sql_allot) or die(mysql_error_js());
		if($row_allot=mysql_fetch_array($res_allot))
		{
			$sql_e="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$row_allot[ALLOTED_TO]'";
                	$res_e=mysql_query_decide($sql_e) or die(mysql_error_js());
                	if($row_e=mysql_fetch_array($res_e))
                	{
                        	$aemail=$row_e['EMAIL'];
                	}
	
		}
		/*to send mails to alloted persons*/	
		
		$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$walkin'";
		$res_e=mysql_query_decide($sql) or die(mysql_error_js());
		if($row_e=mysql_fetch_array($res_e))
			$wemail=$row_e['EMAIL'];
		if(!$wemail)
			$wemail="mahesh@jeevansathi.com";

		$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
		$res_e=mysql_query_decide($sql) or die(mysql_error_js());
		if($row_e=mysql_fetch_array($res_e))
			$eemail=$row_e['EMAIL'];

		$sql="SELECT CD_NUM,CD_DT,BANK,CD_CITY,TYPE FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$cd_num=$row['CD_NUM'];
			$cd_dt=$row['CD_DT'];
			$cd_city=$row['CD_CITY'];
			$bank=$row['BANK'];
            $type = $row['TYPE'];
		}

		// function called to get the template to be sent
		// first argument is the profileid
		// second argument "C" stands for bounced cheque and "O" stands for charge back

		if($bounce_reason)
		{
			if($bounce_reason=="PS")
			{
				$stern="S";
				$reason="Payment Stopped by User";
				$smarty->assign("BOUNCE_REASON","Payment Stopped by User");
			}
			elseif($bounce_reason=="IF")
			{
				$stern="S";
				$reason="Insufficient Funds";
				$smarty->assign("BOUNCE_REASON","Insufficient Funds");
			}
			elseif($bounce_reason=="AC")
			{
				$stern="S";
				$reason="Account Closed";
				$smarty->assign("BOUNCE_REASON","Account Closed");
			}
			elseif($bounce_reason=="ENC")
			{
				$reason=addslashes(stripslashes("Effects not cleared, present again"));
				$smarty->assign("BOUNCE_REASON","Effects not cleared, present again");
			}
			elseif($bounce_reason=="NAF")
			{
				$reason=addslashes(stripslashes("Not arrange for"));
				$smarty->assign("BOUNCE_REASON","Not arrange for");
			}
			elseif($bounce_reason=="RTD")
			{
				$reason=addslashes(stripslashes("Refer to Drawer"));
				$smarty->assign("BOUNCE_REASON","Refer to Drawer");
			}
			elseif($bounce_reason=="DSI")
			{
				$reason=addslashes(stripslashes("Drawers Signature incomplete / differs / required"));
				$smarty->assign("BOUNCE_REASON","Drawers Signature incomplete / differs / required");
			}
			elseif($bounce_reason=="AFS")
			{
				$reason=addslashes(stripslashes("Alteration requires full signature"));
				$smarty->assign("BOUNCE_REASON","Alteration requires full signature");
			}
			elseif($bounce_reason=="PDC")
			{
				$reason=addslashes(stripslashes("Post dated cheque"));
				$smarty->assign("BOUNCE_REASON","Post dated cheque");
			}
			elseif($bounce_reason=="OD")
			{
				$reason=addslashes(stripslashes("Out of date"));
				$smarty->assign("BOUNCE_REASON","Out of date");
			}
			elseif($bounce_reason=="AWD")
			{
				$reason=addslashes(stripslashes("Amount in words and figures differ"));
				$smarty->assign("BOUNCE_REASON","Amount in words and figures differ");
			}
			elseif($bounce_reason=="EA")
			{
				$reason=addslashes(stripslashes("Exceeds arrangement"));
				$smarty->assign("BOUNCE_REASON","Exceeds arrangement");
			}
			elseif($bounce_reason=="NDU")
			{
				$reason=addslashes(stripslashes("Not drawn on us"));
				$smarty->assign("BOUNCE_REASON","Not drawn on us");
			}
			elseif($bounce_reason=="PNR")
			{
				$reason=addslashes(stripslashes("Payee’s name required / differs / mismatch"));
				$smarty->assign("BOUNCE_REASON","Payee’s name required / differs / mismatch");
			}
			$smarty->assign("STERN",$stern);
		}
		else
			$smarty->assign("BOUNCE_REASON",$reason);

		$sql="UPDATE billing.PAYMENT_DETAIL SET STATUS='BOUNCE', REASON = '".addslashes(stripslashes($reason))."', BOUNCE_DT = now(),MAIL_TYPE='$stern' WHERE RECEIPTID='$receiptid'";
	        mysql_query_decide($sql) or die(mysql_error_js());

		$sql ="INSERT INTO billing.BOUNCED_CHEQUE_HISTORY ( ID , RECEIPTID , PROFILEID , BILLID ,BOUNCE_DT  , REMINDER_DT , ENTRYBY , ENTRY_DT , DISPLAY ) VALUES ('', '$receiptid', '$profileid', '$billid', NOW(),DATE_ADD( CURDATE() , INTERVAL 2 DAY ), '$user', NOW(), 'Y')";
		mysql_query_decide($sql) or die(mysql_error_js());

        //**START - Entry for negative transactions
        $memHandlerObject = new MembershipHandler();
        $memHandlerObject->handleNegativeTransaction(array('RECEIPTIDS'=>array($receiptid)));
        unset($memHandlerObject);
        //**END - Entry for negative transactions
        
		bounced_mail($profileid,"C");

		$msg=$smarty->fetch("bounced_mail.htm");
		$subject = "Cheque bounced of $username";
		$from = "payments@jeevansathi.com";
		$cc = $wemail.",".$eemail.",".$aemail;
		$cc.= ", payments@jeevansathi.com,nishant.sharma@naukri.com";
		$bcc = "aman.sharma@jeevansathi.com";
		send_email($cemail,$msg,$subject,$from,$cc);
		if($cmembership=='Y')
		{
			$mem_arr=$membershipObj->lastMainExpiryDate($profileid);
			if($mem_arr=='L')
				$cmembership='N';
			if(is_array($mem_arr))
				if($mem_arr[EXPIRY_DT]>date('Y-m-d'))
					$cmembership='N';
		}
		if($cmdcheck=="continue"||$cmembership!='Y')
		{
			$flag=1;

			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);
			$smarty->assign("flag",$flag);

			$smarty->display("mark_bounce.htm");	

		}
		elseif($cmdcheck=="stop")
		{
			$flag=1;

			$sql_act = "SELECT ACTIVATED,PREACTIVATED FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
			$res_act = mysql_query_decide($sql_act) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql_act));
			$row_act = mysql_fetch_array($res_act);
			
			// delete the contacts of this person
                        if($row_act['ACTIVATED']!='D' && !$offline_billing)
                        {
                                $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
                                $cmd = JsConstants::$php5path." -q ".$path;
                                passthru($cmd);
                        }

			/*$sql="UPDATE newjs.JPROFILE SET PREACTIVATED=IF(ACTIVATED<>'D',ACTIVATED,PREACTIVATED), ACTIVATED='D', ACTIVATE_ON=now(),activatedKey=0 where PROFILEID='$profileid'";
                        mysql_query_decide($sql) or die(mysql_error_js());*/
                        if($row_act['ACTIVATED']!='D')
                                $preActivated =$row_act['ACTIVATED'];
                        else
                                $preActivated =$row_act['PREACTIVATED'];

                        $jprofileObj    =JProfileUpdateLib::getInstance();
			$dateNew        =date("Y-m-d");
                        $updateStr      ="PREACTIVATED='$preActivated', ACTIVATED='D', ACTIVATE_ON='$dateNew',activatedKey=0";
                        $paramArr       =$jprofileObj->convertUpdateStrToArray($updateStr);
                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');
			
			$sql="INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME) values('$profileid','$username','Cheque Bounce','$reason','$user',now())";
			mysql_query_decide($sql) or die(mysql_error_js());

			if($offline_billing)
				stop_offline_service($profileid);
			
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);
			$smarty->assign("flag",$flag);
			$smarty->assign("offline_billing",$offline_billing);

			$smarty->display("mark_bounce.htm");	
		}
	}
	else
	{
		$sql="SELECT AMOUNT,CD_NUM,CD_DT,CD_CITY,BANK,REASON FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$amt=$row['AMOUNT'];
			$cd_num=$row['CD_NUM'];
			$cd_dt=$row['CD_DT'];
			$cd_city=$row['CD_CITY'];
			$bank=$row['BANK'];
			$reason=$row['REASON'];
		}

		$smarty->assign("amt",$amt);
		$smarty->assign("flag",$flag);
		$smarty->assign("cd_num",$cd_num);
		$smarty->assign("cd_dt",$cd_dt);
		$smarty->assign("cd_city",$cd_city);
		$smarty->assign("bank",$bank);
		$smarty->assign("reason",$reason);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("offline_billing",$offline_billing);

		$smarty->display("mark_bounce.htm");
	}
}
else
{
        $smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->display("jsconnectError.tpl");
}
?>
