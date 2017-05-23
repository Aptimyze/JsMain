<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 include_once (JsConstants::$docRoot."/profile/InstantSMS.php");

/********************************************************************************************************************
Filename    : track_bounce_email.php
Description : Track the bounced email id list. 
Created By  : Vibhor Garg
Created On  : 04 Sep 2008
********************************************************************************************************************/
ini_set('memory_limit',-1);

include_once("connect.inc");
$db_select=connect_bouncelog();
$db_slave=connect_slave();
$ts = time()-2*86400;
$mod_dt=date("Y-m-d",$ts);
$ts1 =time()-86400;
$mod_dt1=date("Y-m-d",$ts1);
$smsOnBounce = array();
$sql="select email_id,ecelerity_Errcode from bouncelog.bouncelog_js where modified_date >= '$mod_dt' and modified_date < '$mod_dt1'";
$result=mysql_query($sql,$db_select) or die(mysql_error($db_select));
$db_update=connect_db();
while($myrow=mysql_fetch_array($result))
{
	$email=$myrow["email_id"];
	$ecelerity_Errcode=$myrow["ecelerity_Errcode"];
	if($ecelerity_Errcode == '10' || $ecelerity_Errcode == '1')
	{
	        $sql="insert ignore into bounces.BOUNCED_MAILS (EMAIL) VALUES ('" . mysql_real_escape_string($email) . "')";
        	mysql_query($sql,$db_update) or die(mysql_error($db_update));
        	
        	//to fetch all emails for which mails are getting bounced
        	$smsOnBounce[]=$email;
	}
	else
	{
		$sql="delete from bounces.BOUNCED_MAILS where EMAIL='" . mysql_real_escape_string($email) . "'";
	        mysql_query($sql,$db_update) or die(mysql_error($db_update));
	}
}

if(empty($smsOnBounce)){
	echo("No Emails found.");
}
else{
	//added this code to send sms to all users whose mails are getting bounced.
	$mailString ="'".implode("','",$smsOnBounce)."'";
	//fetch profileId's for profiles whose mails are getting bounced
	$sql_profileId = "select PROFILEID from newjs.JPROFILE where ACTIVATED!='D' AND PHONE_MOB!='' AND ISD='91' AND activatedKey='1' AND EMAIL IN ($mailString)";
	$result=mysql_query($sql_profileId,$db_slave) or die(mysql_error($db_slave));
	$db_update=connect_db();
	while($resultrow=mysql_fetch_array($result))
	{
		$profileId = $resultrow["PROFILEID"];
		//creating an object of InstantSMS and sending the SMS
		$smsObj = new InstantSMS("BOUNCED_MAILS",$profileId);
		$smsObj->send();

	}
}
?>
