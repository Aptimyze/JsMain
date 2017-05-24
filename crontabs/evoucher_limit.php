<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include ("connect.inc");
include("$_SERVER[DOCUMENT_ROOT]/crm/func_sky.php");

	$db=connect_db();
	$flag=0;
	$msg="E-Vouchers available from the following clients have gone below 450. <br />";
	$sql="SELECT COUNT( * ) AS TOTAL, CLIENT_NAME FROM billing.VOUCHER_NUMBER, billing.VOUCHER_CLIENTS WHERE ISSUED = '' AND VOUCHER_NUMBER.TYPE = 'E' AND VOUCHER_NUMBER.CLIENTID = VOUCHER_CLIENTS.CLIENTID AND VOUCHER_CLIENTS.SERVICE='Y' GROUP BY VOUCHER_NUMBER.CLIENTID HAVING COUNT( * ) <450";
	$result=mysql_query($sql);
	while($myrow=mysql_fetch_assoc($result))
	{
		$flag=1;
		$msg.=$myrow["CLIENT_NAME"];
		$msg.="  :  ";
		$msg.=$myrow["TOTAL"];
		$msg.="<br />";
	
	}
	if($flag==1)
	send_mail('krishnan.ramaswami@naukri.com,puneet.chawla@jeevansathi.com,vikas@jeevansathi.com','','',$msg,'E-voucher limit information','Promotions@jeevansathi.com');
?>
