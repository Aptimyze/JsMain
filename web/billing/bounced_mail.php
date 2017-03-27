<?php
include("../crm/mainmenunew.php");

function bounced_mail($profileid,$bounced_from)
{
	global $smarty;
	global $db_slave;

	if($bounced_from=='O')
	{
		$smarty->assign("CHARGE_BACK","Y");
	}
	elseif($bounced_from=='C')
	{
		$smarty->assign("MARK_BOUNCE","Y");
	}

	profileview($profileid,$checksum);

	$sql="SELECT ENTRY_DT,IPADD FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
	$res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		list($edt,$etime)=explode(" ",$row['ENTRY_DT']);
		list($yy,$mm,$dd)=explode("-",$edt);
		list($hr,$min,$sec)=explode(":",$etime);
		$entry_dt=gmdate("Y-m-d H:i:s",mktime($hr,$min,$sec,$mm,$dd,$yy));
		list($edt,$etime)=explode(" ",$entry_dt);
		list($yy,$mm,$dd)=explode("-",$edt);
		$entry_dt=my_format_date($dd,$mm,$yy);
		$entry_dt.=" ".$etime; 
		$smarty->assign("ENTRY_DT",$entry_dt);
		$smarty->assign("REGISTER_IP",$row['IPADD']);
	}

	$sql="SELECT SERVICEID,ENTRY_DT FROM billing.PURCHASES WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
	$res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		list($pdt,$ptime)=explode(" ",$row['ENTRY_DT']);
		list($yy,$mm,$dd)=explode("-",$pdt);
		$paid_date=my_format_date($dd,$mm,$yy);
		$smarty->assign("PAYMENT_ENTRY_DT",$paid_date);

		if($row['SERVICEID']=='S1' || $row['SERVICEID']=='S4')
			$n="3";
		elseif($row['SERVICEID']=='S2' || $row['SERVICEID']=='S5')
			$n="6";
		elseif($row['SERVICEID']=='S3' || $row['SERVICEID']=='S6')
			$n="12";
		else
		{
			$sql = "Select c.DURATION as DURATION from billing.SERVICES a, billing.PACK_COMPONENTS b, billing.COMPONENTS c where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID ='$row[SERVICEID]'";	
			$result_duration = mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
			$myrow_duration = mysql_fetch_array($result_duration);
			$n = $myrow_duration["DURATION"];
		}
		$smarty->assign("N",$n);
	}

	$sql="SELECT ID,ORDERID FROM billing.ORDERS WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC";
	$res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		$orderid=$row['ORDERID']."-".$row['ID'];
		$smarty->assign("ORDERID",$orderid);
	}

/*
	$sql="SELECT TIME FROM newjs.CONTACTS WHERE SENDER='$profileid' ORDER BY TIME DESC";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		list($cdt,$ctime)=explode(" ",$row['TIME']);
		list($yy,$mm,$dd)=explode("-",$cdt);
		$smarty->assign("LAST_CONTACT_DT",my_format_date($dd,$mm,$yy));
	}
*/
	$contactResult = getResultSet("TIME",$profileid,"","","","","","","","TIME DESC");
	list($cdt,$ctime) = explode(" ",$contactResult[0]["TIME"]);
	list($yy,$mm,$dd) = explode("-",$cdt);
	$smarty->assign("LAST_CONTACT_DT",my_format_date($dd,$mm,$yy));

	$sql="SELECT BOUNCE_DT,REASON,MAIL_TYPE,MODE FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profileid' AND STATUS IN ('BOUNCE','CHARGE_BACK')";
	$res=mysql_query_decide($sql,$db_slave) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		list($bdt,$btime)=explode(" ",$row['BOUNCE_DT']);
		list($yy,$mm,$dd)=explode("-",$bdt);
		$smarty->assign("STERN",$row['MAIL_TYPE']);
		$smarty->assign("BOUNCE_DT",my_format_date($dd,$mm,$yy));
		$smarty->assign("BOUNCE_REASON_REMINDER",nl2br($row['REASON']));
		if($row['MODE']!='ONLINE')
			$smarty->assign("ORDERID",'');
	}
	
//	$smarty->assign("BOUNCE_REASON",$reason);

//	$msg=$smarty->fetch("bounced_mail.htm");
//	return $msg;
}
?>
