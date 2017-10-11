<?php
/**************************************************************************************************
Filename    : voucher_start_stop.php
Description : Cron script start or stop voucher deals [2190]
Created On  : 8 September 2007
Created By  : Sadaf Alam
****************************************************************************************************/
include("connect.inc");
include("../crm/func_sky.php");

$db=connect_db();

$sql="SELECT CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE STATUS='C' AND START_DATE=CURDATE()";
$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
if(mysql_num_rows($result))
{
	$msg="The following deals have been activated today :";
	while($row=mysql_fetch_assoc($result))
	$msg.="\n".$row["CLIENT_NAME"];
}
$sql="UPDATE billing.VOUCHER_CLIENTS SET SERVICE='Y',STATUS='L' WHERE START_DATE=CURDATE() AND STATUS='C'";
mysql_query_decide($sql) or die("$sql".mysql_error_js());

$sql="SELECT CLIENT_NAME FROM billing.VOUCHER_CLIENTS WHERE SERVICE='Y' AND EXPIRY_DATE=CURDATE()";
$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
if(mysql_num_rows($result))
{
	$msg.="\n\nThe following deals have been deactivated today :";
	while($row=mysql_fetch_assoc($result))
	$msg.="\n".$row["CLIENT_NAME"];
}
$sql="UPDATE billing.VOUCHER_CLIENTS SET SERVICE='N' WHERE SERVICE='Y' AND EXPIRY_DATE=CURDATE()";
mysql_query_decide($sql) or die("$sql".mysql_error_js());

if($msg)
send_mail("shweta.bahl@naukri.com,lotika.sharma@naukri.com","sadaf.alam@jeevansathi.com,vikas@jeevansathi.com",'',$msg,"Live Deals Updated","promotions@jeevansathi.com");
?>
