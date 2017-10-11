<?php
/**********************************************************************************************************
Filename    : send_johareez_voucher.php
Description : to send johareez voucher to 36k unique users before 19th oct 2007 [2521]
Created By  : Sadaf Alam
Created On  : 11 dec 2007
***********************************************************************************************************/
ini_set('max_execution_time','0');
include(JsConstants::$docRoot."/profile/connect.inc");
include(JsConstants::$docRoot."/crm/func_sky.php");

$db=connect_db();

$i=1;

$sql="set session wait_timeout=1000";
mysql_query_decide($sql);

$sql="SELECT DISTINCT(PROFILEID) FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT < '2007-10-19' AND STATUS='DONE' ORDER BY RECEIPTID DESC";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($i<=10000)
{
	if($row=mysql_fetch_assoc($res))
	{
		$sqlopt="SELECT ID FROM billing.VOUCHER_NUMBER WHERE ISSUED='Y' AND PROFILEID='$row[PROFILEID]' AND CLIENTID='JOH65'";
		$resopt=mysql_query_decide($sqlopt) or die("$sqlopt".mysql_error_js());
		if(mysql_num_rows($resopt)==0)
		{
			$sqldet="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE  activatedKey=1 and PROFILEID='$row[PROFILEID]'";
			$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
			$rowdet=mysql_fetch_assoc($resdet);
			$smarty->assign("Name",$rowdet["USERNAME"]);
			$to=$rowdet["EMAIL"];
			$sqldet="SELECT MIN(ID) AS ID FROM billing.VOUCHER_NUMBER WHERE ISSUED='' AND CLIENTID='JOH65' AND SOURCE='EMAIL'";	
			$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
			$rowdet=mysql_fetch_assoc($resdet);
			$sqldet="SELECT ID,VOUCHER_NO FROM billing.VOUCHER_NUMBER WHERE ID='$rowdet[ID]'";
			$resdet=mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
			$rowdet=mysql_fetch_assoc($resdet);
			$smarty->assign("voucher_no",$rowdet["VOUCHER_NO"]);
			$msg=$smarty->fetch("voucher_johareez.htm");
			$from="promotions@jeevansathi.com";
			$subject="A Gift Voucher from Johareez";
			send_mail($to,"","",$msg,$subject,$from);
			$sqldet="UPDATE billing.VOUCHER_NUMBER SET ISSUED='Y',PROFILEID='$row[PROFILEID]',ISSUE_DATE=NOW() WHERE ID='$rowdet[ID]'";
			mysql_query_decide($sqldet) or die("$sqldet".mysql_error_js());
			$i++;
		}
	}
}
?>
