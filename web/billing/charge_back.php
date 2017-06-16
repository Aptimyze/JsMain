<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("bounced_mail.php");
include_once("comfunc_sums.php");
include_once($_SERVER["DOCUMENT_ROOT"]."/classes/Membership.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$data=authenticated($cid);
$flag=0;

if(isset($data))
{
	if($CMDSubmit)
	{
		$membershipObj = new Membership;
		$user=getname($cid);

		if(trim($reason)=='')
		{
			$smarty->assign("NO_REASON","Y");
		}
		else
		{
			$sql="SELECT PROFILEID,USERNAME,DUEAMOUNT,EMAIL FROM billing.PURCHASES WHERE BILLID='$billid'";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$profileid=$row['PROFILEID'];
			$username=$row['USERNAME'];
			$dueamt=$row['DUEAMOUNT'];
			$cemail=$row['EMAIL'];

			//function called to store user's stats.
			charge_back_stats_log($profileid,$receiptid);

			$sql="UPDATE billing.PAYMENT_DETAIL SET STATUS='CHARGE_BACK', REASON = '".addslashes(stripslashes($reason))."', BOUNCE_DT = now() WHERE RECEIPTID='$receiptid'";
			mysql_query_decide($sql) or die(mysql_error_js());
            
            //**START - Entry for negative transactions
            $memHandlerObject = new MembershipHandler();
            $memHandlerObject->handleNegativeTransaction(array('RECEIPTIDS'=>array($receiptid)),'CHARGE_BACK');
            unset($memHandlerObject);
            //**END - Entry for negative transactions            

			//$dueamt+=$amt;
			$status='STOPPED';
                        $membershipObj->change_status($billid,$status);
                        $membershipObj->stop_service($billid,$profileid);
			
			/*code to change the due amount of last billid*/			

			$sql_due_sel="SELECT BILLID,DUEAMOUNT FROM billing.PURCHASES  WHERE PROFILEID='$profileid' order by BILLID desc LIMIT 1";
			$res_due_sel=mysql_query_decide($sql_due_sel) or die(mysql_error_js());
                        $row_due_sel=mysql_fetch_array($res_due_sel);
			$billid_set=$row_due_sel['BILLID'];
			$dueamt_set=$row_due_sel['DUEAMOUNT'];
			$dueamt_set+=$amt;
			$sql="UPDATE billing.PURCHASES SET DUEAMOUNT='$dueamt_set' WHERE BILLID='$billid_set'";
			mysql_query_decide($sql) or die(mysql_error_js());

			/*end of code*/

			$sql1 ="INSERT INTO billing.BOUNCED_CHEQUE_HISTORY ( ID , RECEIPTID , PROFILEID , BILLID , STATUS,BOUNCE_DT , REMINDER_DT , ENTRYBY , ENTRY_DT , DISPLAY) VALUES ('', '$receiptid', '$profileid', '$billid', 'CHARGE_BACK', NOW(),DATE_ADD( CURDATE( ) , INTERVAL 2 DAY ), '$user', NOW(), 'Y')";
                        mysql_query_decide($sql1) or die("$sql1".mysql_error_js());

			//$cc="payments@jeevansathi.com,rohan.mathur@jeevansathi.com,JSSalesLeads@Infoedge.com,nishant.sharma@naukri.com,services@jeevansathi.com,shyam@naukri.com,jitesh.bhugra@naukri.com";
            $cc="services@jeevansathi.com,JsSalesLeads@jeevansathi.com,payments@jeevansathi.com";

			// function called to get the template to be sent
			// first argument is the profileid
			// second argument "C" stands for bounced cheque and "O" stands for charge back

			bounced_mail($profileid,"O");
			$smarty->assign("BOUNCE_REASON",nl2br($reason));

			$msg=$smarty->fetch("bounced_mail.htm");
			$subject = "Charge back request of $username";
			$from = "payments@jeevansathi.com";
			//$bcc = "alok@jeevansathi.com";
			send_email($cemail,$msg,$subject,$from,$cc);

			$flag=1;

			//added by sriram to prevent the query being run several times on page reload.
                        $sql_act = "SELECT ACTIVATED,PREACTIVATED FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
                        $res_act = mysql_query_decide($sql_act) or die($sql_act);
                        $row_act = mysql_fetch_array($res_act);
                        // delete the contacts of this person
                        if($row_act['ACTIVATED']!='D' && !$offline_billing)
                        {
                                $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
                                $cmd = "/usr/bin/php -q ".$path;
                                passthru($cmd);
                        }
                        //end of - added by sriram to prevent the query being run several times on page reload.

			/*$sql="UPDATE newjs.JPROFILE SET PREACTIVATED=IF(ACTIVATED<>'D',ACTIVATED,PREACTIVATED), ACTIVATED='D',activatedKey=0, SUBSCRIPTION='', ACTIVATE_ON=now() where PROFILEID='$profileid'";
			mysql_query_decide($sql) or die(mysql_error_js());*/
			if($row_act['ACTIVATED']!='D')
				$preActivated =$row_act['ACTIVATED'];
			else
				$preActivated =$row_act['PREACTIVATED'];

                        $jprofileObj    =JProfileUpdateLib::getInstance();
			$dateNew	=date("Y-m-d");
                        $updateStr      ="PREACTIVATED='$preActivated', ACTIVATED='D',activatedKey=0, SUBSCRIPTION='', ACTIVATE_ON='$dateNew'";
                        $paramArr       =$jprofileObj->convertUpdateStrToArray($updateStr);
                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');


			$sql="INSERT into jsadmin.DELETED_PROFILES(PROFILEID,USERNAME,REASON,COMMENTS,USER,TIME) values('$profileid','$username','Charge Back','$reason','$user',now())";
                        mysql_query_decide($sql) or die(mysql_error_js());

			if($offline_billing)
				stop_offline_service($profileid);
		}

		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("offline_billing",$offline_billing);
		$smarty->assign("flag",$flag);

		$smarty->display("charge_back.htm");
	}
	elseif($CMDORDERID)
	{
		list($order,$id)=explode("-",$orderid);
		$sql="SELECT USERNAME,ENTRY_DT,PROFILEID FROM billing.ORDERS WHERE ID='$id' AND ORDERID='$order'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$username=$row['USERNAME'];
			$cd_dt=$row['ENTRY_DT'];
			$profileid=$row['PROFILEID'];
		}
		else
		{
			$smarty->assign("INVALID_ORDER","Y");
			$smarty->assign("receiptid",$receiptid);
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);

			$smarty->display("charge_back.htm");
			exit;
		}

		$sql="SELECT AMOUNT,REASON,TYPE FROM billing.PAYMENT_DETAIL WHERE RECEIPTID='$receiptid'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$amt=$row['AMOUNT'];
			$reason=$row['REASON'];
            $type = $row['TYPE'];
		}

		$smarty->assign("username",$username);
		$smarty->assign("amt",$amt);
		$smarty->assign("flag",$flag);
		$smarty->assign("cd_num",$orderid);
		$smarty->assign("cd_dt",$cd_dt);
		$smarty->assign("reason",$reason);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("offline_billing",$offline_billing);
		$smarty->assign("flag","2");
        $smarty->assign("type",$type);

		$smarty->display("charge_back.htm");
	}
	elseif($CMDIVR)
	{
		$sql = "SELECT p.USERNAME,pd.ENTRY_DT, pd.PROFILEID, pd.AMOUNT, pd.REASON,pd.TYPE FROM billing.PURCHASES p, billing.PAYMENT_DETAIL pd WHERE pd.RECEIPTID='$receiptid' AND p.BILLID=pd.BILLID AND pd.TRANS_NUM = '$ivr_number'";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			$username=$row['USERNAME'];
			$cd_dt=$row['ENTRY_DT'];
			$profileid=$row['PROFILEID'];
			$amt=$row['AMOUNT'];
			$reason=$row['REASON'];
            $type = $row['TYPE'];
		}
		else
		{
			$smarty->assign("INVALID_IVR","Y");
			$smarty->assign("receiptid",$receiptid);
			$smarty->assign("USER",$user);
			$smarty->assign("CID",$cid);
			$smarty->assign("phrase",$phrase);
			$smarty->assign("criteria",$criteria);
			$smarty->assign("billid",$billid);
            $smarty->assign("type",$type);

			$smarty->display("charge_back.htm");
			exit;
		}

		$smarty->assign("username",$username);
		$smarty->assign("amt",$amt);
		$smarty->assign("flag",$flag);
		$smarty->assign("cd_num",$ivr_number);
		$smarty->assign("cd_dt",$cd_dt);
		$smarty->assign("reason",$reason);
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("offline_billing",$offline_billing);
		$smarty->assign("flag","2");
		$smarty->assign("ivr",1);

		$smarty->display("charge_back.htm");
	}
	else
	{
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);
		$smarty->assign("offline_billing",$offline_billing);

		$smarty->display("charge_back.htm");
	}
}
else
{
        $smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->display("jsconnectError.tpl");
}
?>
