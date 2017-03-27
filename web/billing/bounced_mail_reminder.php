<?php
chdir(dirname(__FILE__));

include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("../jsadmin/time.php");
include("bounced_mail.php");

$db_slave = connect_slave();
//$sql="SELECT BILLID,PROFILEID,STATUS FROM billing.PAYMENT_DETAIL WHERE STATUS IN ('BOUNCE','CHARGE_BACK') AND BOUNCE_DT=DATE_SUB(CURDATE(),INTERVAL 5 DAY)";

$date = newtime(date("Y-m-d"),3,0,0);
$mail_date=substr($date,0,10);

$sql="SELECT BILLID,PROFILEID,STATUS FROM billing.BOUNCED_CHEQUE_HISTORY WHERE REMINDER_DT='$mail_date' AND DISPLAY ='Y'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$flag=0;
	$status='';
	$billid=$row['BILLID'];
	$profileid=$row['PROFILEID'];
	unset($cc);

	$sql="SELECT STATUS,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC LIMIT 1";
	$res1=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
	if($row1=mysql_fetch_array($res1))
	{
		if($row1['STATUS']=='BOUNCE' || $row1['STATUS']=='CHARGE_BACK')
		{
			$flag=1;
		}
	}

	// function called to get the template to be sent
	// first argument is the profileid
	// second argument "C" stands for bounced cheque and "O" stands for charge back

	if($flag)
	{
		$sql="SELECT EMAIL,USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
		$res1=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
		$row1=mysql_fetch_array($res1);
		$cemail=$row1['EMAIL'];
		$username=$row1['USERNAME'];

		if($row['STATUS']=='BOUNCE')
		{
			$sql="SELECT WALKIN FROM billing.PURCHASES WHERE BILLID='$billid'";
			$res2=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
			$row2=mysql_fetch_array($res2);
			$walkin=$row2['WALKIN'];

			if($walkin=='ONLINE' || $walkin=='OFFLINE' || $walkin=='ARAMEX')
			{
				//$cc="mahesh@jeevansathi.com,";
				//$cc="payments@jeevansathi.com,";
			}
			else
			{
				$sql="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$walkin'";
				$res2=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js());
				$row2=mysql_fetch_array($res2);
				$cc=$row2['EMAIL'].",";
			}
			/*added by Puneet Makkar to send mails to alloted persons*/
			$sql_allot="SELECT ALLOTED_TO FROM incentive.MAIN_ADMIN  WHERE PROFILEID='$profileid'";
			$res_allot=mysql_query_decide($sql_allot,$db_slave) or die(mysql_error_js());
			if($row_allot=mysql_fetch_array($res_allot))
			{
				$sql_e="SELECT EMAIL FROM jsadmin.PSWRDS WHERE USERNAME='$row_allot[ALLOTED_TO]'";
				$res_e=mysql_query_decide($sql_e,$db_slave) or die(mysql_error_js());
				if($row_e=mysql_fetch_array($res_e))
				{
					$cc.=$row_e['EMAIL'].",";
                        	}
                                                                                                                             
			}
			/*added by Puneet Makkar to send mails to alloted persons*/
			$status='C';
			$subject = "Cheque bounced alert of $username";
		}
		elseif($row['STATUS']=='CHARGE_BACK')
		{
			$status='O';
			$subject = "Charge back request of $username";
		}

		if($status)
		{

			bounced_mail($profileid,$status);

			$msg=$smarty->fetch("bounced_mail_reminder.htm");
			$from = "payments@jeevansathi.com";
			$cc.="payments@jeevansathi.com, ambarish@jeevansathi.com,nishant.sharma@naukri.com";
			send_email($cemail,$msg,$subject,$from,$cc);
		}
	}
}
?>
