<?php

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("bounced_mail.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

$data=authenticated($cid);
$flag=0;

if(isset($data))
{
	$user=getname($cid);
	if($CMDSubmit)
	{
		$smarty->assign("flag",2);
		if(trim($username)=='')
		{
			$error++;
			$smarty->assign("NO_USER","Y");
		}
		if($error)
		{
			$smarty->assign("flag",0);
			$smarty->assign("CID",$cid);
			$smarty->display("offline_charge_back.htm");
		}
		else
		{
			$sql="SELECT PROFILEID,ORDER_NO,ENTRY_DT,AMOUNT FROM billing.oct_nov_record WHERE USERNAME='".addslashes($username)."'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$smarty->assign("profileid",$row['PROFILEID']);
				$smarty->assign("orderid",$row['ORDER_NO']);
				$smarty->assign("paid_date",$row['ENTRY_DT']);
				$smarty->assign("amt",$row['AMOUNT']);
				$smarty->assign("username",$username);
				$smarty->assign("CID",$cid);

				$smarty->display("offline_charge_back.htm");
			}
			else
			{
				$smarty->assign("NO_RECORD","Y");
				$smarty->assign("flag",0);
				$smarty->assign("CID",$cid);
				$smarty->display("offline_charge_back.htm");
			}
		}
	}
	elseif($CMDGo)
	{
		$smarty->assign("flag",1);

		$error=0;
		if(trim($reason)=='')
		{
			$error++;
			$smarty->assign("NO_REASON","Y");
		}

		if($error)
		{
			$smarty->assign("flag",2);
			$smarty->assign("orderid",$orderid);
			$smarty->assign("username",$username);
			$smarty->assign("reason",$reason);
			$smarty->assign("profileid",$profileid);
			$smarty->assign("paid_date",$paid_date);
			$smarty->assign("amt",$amt);
			$smarty->assign("service",$service);
			$smarty->assign("CID",$cid);
			$smarty->display("offline_charge_back.htm");
		}
		else
		{
			$sql="SELECT EMAIL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
			$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$cemail=$row['EMAIL'];
				$cc="payments@jeevansathi.com";

				// function called to get the template to be sent
				// first argument is the profileid
				// second argument "C" stands for bounced cheque and "O" stands for charge back

				bounced_mail($profileid,"O");
				list($yy,$mm,$dd)=explode("-",$paid_date);
				$paid_date=my_format_date($dd,$mm,$yy);
				$smarty->assign("PAYMENT_ENTRY_DT",$paid_date);

				if($service=='S1' || $service=='S4')
					$n="3";
				elseif($service=='S2' || $service=='S5')
					$n="6";
				elseif($service=='S3' || $service=='S6')
					$n="12";
				$smarty->assign("N",$n);

				$smarty->assign("BOUNCE_REASON",nl2br($reason));
				$smarty->assign("ORDERID",$orderid);

				$msg=$smarty->fetch("bounced_mail.htm");
				$subject = "Charge back request of $username";
				$from = "info@jeevansathi.com";
	//			$bcc = "alok@jeevansathi.com";
				send_email($cemail,$msg,$subject,$from,$cc);

				/*$sql="UPDATE newjs.JPROFILE SET PREACTIVATED=ACTIVATED,ACTIVATED='D',SUBSCRIPTION='',ACTIVATE_ON=now(),activatedKey=0 WHERE PROFILEID='$profileid'";
				mysql_query_decide($sql) or die(mysql_error_js());*/
				$jprofileObj    =JProfileUpdateLib::getInstance();
				$dateNew        =date("Y-m-d");
	                        $updateStr      ="PREACTIVATED=ACTIVATED,ACTIVATED='D',SUBSCRIPTION='',ACTIVATE_ON='$dateNew',activatedKey=0";
	                        $paramArr       =$jprofileObj->convertUpdateStrToArray($updateStr);
	                        $jprofileObj->editJPROFILE($paramArr,$profileid,'PROFILEID');

				$smarty->assign("CID",$cid);
				$smarty->display("offline_charge_back.htm");
			}
			else
			{
				$smarty->assign("orderid",$orderid);
				$smarty->assign("username",$username);
				$smarty->assign("reason",$reason);
				$smarty->assign("flag",0);
				$smarty->assign("CID",$cid);
				$smarty->assign("NO_RECORD","Y");
				$smarty->display("offline_charge_back.htm");
			}
		}
	}
	else
	{
		$smarty->assign("receiptid",$receiptid);
		$smarty->assign("USER",$user);
		$smarty->assign("CID",$cid);
		$smarty->assign("phrase",$phrase);
		$smarty->assign("criteria",$criteria);
		$smarty->assign("billid",$billid);

		$smarty->display("offline_charge_back.htm");
	}
}
else
{
        $smarty->assign("HEAD",$smarty->fetch("../head.htm"));
        $smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
        $smarty->display("jsconnectError.tpl");
}
?>
