<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include_once (JsConstants::$docRoot."/profile/InstantSMS.php");

 /********************************************************************************************************************
Filename    : BounceEmailsLegacyHandling.php
Description : Send an sms to all users who are in the bounce list and are active, non deleted. One time cron 
Created By  : Sanyam Chopra
Created On  : 11 May 2016
********************************************************************************************************************/
ini_set('memory_limit',-1);

include_once("../connect.inc");
$db_slave=connect_slave();

$timeSpan = date("Y-m-d",strtotime("-5 months"));
$sql = "select J.PROFILEID AS PROFILEID from bounces.BOUNCED_MAILS AS B JOIN newjs.JPROFILE AS J ON J.EMAIL = B.EMAIL WHERE J.ACTIVATED!='D' AND J.PHONE_MOB!='' AND J.ISD='91' AND J.activatedKey='1' AND J.LAST_LOGIN_DT>='$timeSpan'";
$result=mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
$db_update=connect_db();
while($myrow=mysql_fetch_array($result))
{
	$profileId=$myrow["PROFILEID"];
	//creating an object of InstantSMS and sending the SMS
	$smsObj = new InstantSMS("BOUNCED_MAILS",$profileId);
    $smsObj->send();
}

?>