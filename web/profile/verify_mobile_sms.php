<?php
include_once "connect.inc";
include_once(JsConstants::$docRoot."/ivr/jsivrFunctions.php");
connect_db();
$cc='esha.jain@jeevansathi.com';
$to='tanu.gupta@jeevansathi.com';
$subject="verify_mobile_sms.php track mail";

$emailMsg='The api mention is being called. The message : '.$msg.' is received from the number '.$mobile.'.<br/><br/>Warm Regards';
                                send_email($to,$emailMsg,$subject,"",$cc);

if($mobile=="" || $msg=="")
	die("Mobile number or Message field is blank");
else{
	$mobile =mobileformat($mobile);
	$mobileno =substr($mobile,-10,10);

	// Message received values:- 1:YES, 2:NO, 3:Y, 4:any numeric 4 digit code
	$msg=addslashes(stripslashes($msg));
	$msg=strtolower($msg);	
}

if($msg=='no'){
	die("Profile Invalid");
}

$messageVal =checkMessageReceived($msg,$mobile);
if($messageVal)
{
	if(is_numeric($messageVal))
		$sql="SELECT PROFILEID,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES FROM newjs.JPROFILE WHERE PHONE_MOB in('$mobileno','0$mobileno','$mobile') AND  activatedKey=1 and PROFILEID='$messageVal'";
	else
		$sql="SELECT PROFILEID,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES FROM newjs.JPROFILE WHERE PHONE_MOB in('$mobileno','0$mobileno','$mobile') ORDER BY LAST_LOGIN_DT desc limit 1";
	$res=mysql_query_decide($sql) or logError($sql);
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_array($res))
			phoneUpdateProcess($row['PROFILEID'],$mobile,'M','Y','SMS');
	}
	die("Profile is marked as Verified");
}
function checkMessageReceived($msg,$mobile)
{
	if($msg=='y' || $msg=='yes'){
		return true;
	}
	else{
		$codeValue =validate_verificationCode($msg,'',$mobile,'SMS');
		return $codeValue;
	}
	return false;
}
?>
