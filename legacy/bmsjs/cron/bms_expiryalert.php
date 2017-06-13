<?php
//include("../includes/bms_connections.php");
chdir(dirname(__FILE__));
include("../includes/bms_connect.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$db99 = getConnection99acres();

$now=time();
$now+=4*24*60*60;
$end_date1=date("Y-m-d",$now);

$from = "webmaster@ieplads.com";
//$email1 = "shobha.solanki@gmail.com";

$email1 = "lijuv@naukri.com";
$email2 = "rizwan@naukri.com";

$subject= "BMS campaign will expire in 4 days duration ";

$sql="Select CampaignName , SITE , REF_ID , CampaignExecutiveId  from bms2.CAMPAIGN where CampaignEndDt = '$end_date1' and CampaignEndDt!='0000-00-00'";
$result=mysql_query($sql) or mail("shobha.kumari@jeevansathi.com","error in bms_durationexpiry",mysql_error().$sql);
if($row=mysql_fetch_array($result))
{
	if ($row["SITE"] == 'JS')
	{
		$sql_exec = "SELECT EMAIL FROM jsadmin.PSWRDS WHERE EMP_ID = $row[CampaignExecutiveId]";
		$res_exec=mysql_query($sql_exec) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive. <br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
		$row_exec=mysql_fetch_array($res_exec);
		$em = $row_exec['EMAIL'];
	}
	else
	{
		$sql_exec = "SELECT EMAIL FROM sums.PSWRDS WHERE EMP_ID = $row[CampaignExecutiveId]";
		$res_exec=mysql_query($sql_exec,$db99) or logErrorBms("bms_expiryalert.php: Could not retrieve email-id of the sales executive.<br> <!--$sql<br>". mysql_error()."-->: ". mysql_errno(), $sql_exec, "ShowErrTemplate");
		$row_exec=mysql_fetch_array($res_exec);
		$em = $row_exec['EMAIL'];
	}
	if ($em)
	{
		$message = "Dear Sir/Madam,<br><br>This is to inform you that the campaign <b>".$row["CampaignName"]."</b> will expire on <b>".$end_date1."</b>. In case you wish to extend the campaign expiry date, kindly mail us the revised date. Once the subscription is over, the campaign will be expired and you would be required to make fresh entry.";
		send_email($em,$message,$subject,$from,$email1,"");
	}
}
unset($campaign);
unset($message);
?>
