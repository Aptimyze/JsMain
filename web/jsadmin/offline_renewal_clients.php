<?php
/*********************************************************************************************************************
Filename    : offine_renewal_clients.php
Description : Module for clients to follow up for renewals [3209]
Created On  : 14 July 2008
Author      : Sadaf Alam
**********************************************************************************************************************/

include("connect.inc");

$db=connect_db();

if(authenticated($cid))
{
	$name=getname($cid);
	if($renewal_call)
	{
		$sql="UPDATE jsadmin.OFFLINE_BILLING SET RENEWAL_CALL='Y' WHERE PROFILEID=$profileid ORDER BY BILLID DESC LIMIT 1";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
	}
	$sql="SELECT OFFLINE_BILLING.PROFILEID, DATEDIFF(EXPIRY_DT,CURDATE()) AS DAYS_REMAINING, ENTRY_DATE FROM jsadmin.OFFLINE_BILLING JOIN billing.SERVICE_STATUS ON OFFLINE_BILLING.BILLID = SERVICE_STATUS.BILLID JOIN OFFLINE_ASSIGNED ON OFFLINE_BILLING.PROFILEID = OFFLINE_ASSIGNED.PROFILEID WHERE DATEDIFF(EXPIRY_DT,CURDATE())<=10 AND ACTIVE = 'Y' AND RENEWAL_CALL='' AND OPERATOR='$name'";

	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$i=1;
	while($row=mysql_fetch_assoc($res))
	{
		unset($sno);
		unset($username);
		unset($password);
		unset($pool_count);
		unset($sl_count);
		unset($acc_count);
		unset($start_date);
		unset($days_remaining);
		unset($remove);

		$sno=$i;
		$start_date=$row["ENTRY_DATE"];
		$days_remaining=$row["DAYS_REMAINING"];
		
		$sqldata="SELECT USERNAME,PASSWORD FROM newjs.JPROFILE WHERE PROFILEID='$row[PROFILEID]'";
		$resdata=mysql_query_decide($sqldata) or die("$sqldata".mysql_error_js());
		$rowdata=mysql_fetch_assoc($resdata);
		mysql_free_result($resdata);
		$username="<a href=# onClick=\"window.open('$SITE_URL/profile/profileselect_oc.php?profileid=$row[PROFILEID]','','width=760,height=570,resizable=1,scrollbars=1'); return false;\">".$rowdata["USERNAME"]."</a>";
		$password=$rowdata["PASSWORD"];
		unset($rowdata);

		$sqldata="SELECT COUNT(*) AS CNT,STATUS,CATEGORY FROM jsadmin.OFFLINE_MATCHES WHERE PROFILEID='$row[PROFILEID]' GROUP BY STATUS,CATEGORY";        
		$resdata=mysql_query_decide($sqldata) or logError("$sqldata".mysql_error_js());
		while($rowdata=mysql_fetch_assoc($resdata))
		{
			if(($rowdata["STATUS"]=="N" || $rowdata["STATUS"]=="NNOW" || $rowdata["STATUS"]=="NACC") && $rowdata["CATEGORY"]!=0)
			{
				$pool_count+=$rowdata["CNT"];
			}
			if($rowdata["STATUS"]=="ACC")	
				$acc_count+=$rowdata["CNT"];
			if($rowdata["STATUS"]=="SL")
				$sl_count+=$rowdata["CNT"];
		}
		mysql_free_result($resdata);
		unset($rowdata);
		$remove="<a href=\"$SITE_URL/jsadmin/offline_renewal_clients.php?profileid=$row[PROFILEID]&renewal_call=Y&cid=$cid&name=$name\">Remove</a>";
		$tableData[]=array($sno,$username,$password,$pool_count,$sl_count,$acc_count,$start_date,$days_remaining,$remove);
		$i++;
	}
	mysql_free_result($res);
	unset($row);
	$smarty->assign("tableData",$tableData);
	$smarty->assign("name",$name);
	$smarty->assign("cid",$cid);
	$smarty->display("offline_renewal_clients.htm");
}
else
{
	$msg.="Your session has been timed out.";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
