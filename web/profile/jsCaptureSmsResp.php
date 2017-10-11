<?php
/*
Author: @Esha
Date: 30-nov-2011
* Input: Message text and mobile number 
*/
include $_SERVER['DOCUMENT_ROOT']."/classes/SmsDetails.class.php";
include_once "connect.inc";
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once("mobile_verification_sms.php");
include $_SERVER['DOCUMENT_ROOT']."/classes/IgnoreDetails.class.php";

connect_db();

$message_txt=$_GET["messagetext"];
$message1=preg_replace('/[^a-zA-Z *]/','',$message_txt);
$message_txt=trim(preg_replace( '/ */', '', $message_txt));  
$message=preg_replace("/[^a-zA-Z0-9]/","",$message_txt);
$mobile=trim($_GET["mobileno"]);

if($message && $mobile)
{
$smsDetail= new SmsDetails();
/*if(!strncasecmp($message,"M",1))
	$sms_key='MTONGUE_CONFIRM';
else*/
if(!strncasecmp($message,"NO",2))
	$sms_key='REGISTER_CONFIRM';
elseif((!strncasecmp($message,"Y",1))||is_numeric($message))
	verify_mobile_smsphpFunction($message,$mobile);
elseif(stristr($message1,' Y') || stristr($message_txt,"verif") || stristr($message_txt,"stamp") || (!strncasecmp($message,"V",1)))
	verify_mobile_smsphpFunction('Y',$mobile);
/*elseif(!strncasecmp($message,"BLOCK",5))
	jsCaptureIgnoreSmsphpFunction($message_txt,$mobile);*/
else
{
	//1014 capturing the msg other then require msg
	$sql = "INSERT INTO MIS.SMS_RESPONSE_LOG (MOBILENO,MESSAGE) VALUES('".$mobile."','".$_GET["messagetext"]."')";
	$res=mysql_query_decide($sql) or logError($sql);
	
/* Ticket #1771
$cc='esha.jain@jeevansathi.com';
$to='nitesh.s@jeevansathi.com';
$subject="jsCapturSmsResp.phh unknown response error mail";

$msg='The message : '.$_GET["messagetext"].' is received from the number '.$mobile.'.<br/><br/>Warm Regards';
                                send_email($to,$msg,$subject,"",$cc);*/
}
	

if($sms_key=='MTONGUE_CONFIRM' || $sms_key=='REGISTER_CONFIRM')
{
	if($smsDetail->findProfile($mobile,$sms_key)==1)
	{
		if($sms_key=='REGISTER_CONFIRM')
		{
			include_once "InstantSMS.php";
			$sms= new InstantSMS("REGISTER_RESPONSE",$smsDetail->profileid);
			$sms->send();
		}
		$smsDetail->insertSmsConfrm();
	}
	else
		$smsDetail->errormail();
}
}


function jsCaptureIgnoreSmsphpFunction($message,$mobileno)
{
$cc='esha.jain@jeevansathi.com';
$to='nitesh.s@jeevansathi.com';
$subject="ignoreSms error mail";
$mobile=substr($mobileno,-10);
$userName=substr($message, 5);

$ignoreDetail= new IgnoreDetails();

if(!strncasecmp($message,"BLOCK",5) && $userName)
{
	if($ignoreDetail->findProfileId($userName)==1)
	{
		if($callnowList=$ignoreDetail->findPossibleIgnorer())
		{
			$listString=implode("','",$callnowList);
			if($ignoreDetail->searchInJprofile($listString,$mobile)==1)
				$ignoreDetail->insertInIgnoreProfile();
			elseif($ignoreDetail->searchInJprofileContacts($listString,$mobile)==1)
				$ignoreDetail->insertInIgnoreProfile();
			else
			{
				$msg='The number '.$mobile.' is not found in both the tables Jprofile and Jprofile Contacts searched for the list of profileids received from callnow(contacted in last 24hrs). The message received was '.$_GET["messagetext"].'.<br/><br/>Warm Regards';
				send_email($to,$msg,$subject,"",$cc);
			}
		}
		else
		{
			$msg='There are no records of any call made or received for the user '.$userName.' in last 24hrs. The message is '. $_GET["messagetext"].' from the number '.$mobile.'.<br><br>Warm Regards';
			send_email($to,$msg,$subject,"",$cc);
		}
	}
	else
		{
			$msg='The username '.$userName.' does not exists in Jprofile. The message received was '.$_GET["messagetext"].' from the number '.$mobile.'.<br><br>Warm Regards';  //username not found in Jprofile
			send_email($to,$msg,$subject,"",$cc);	
		}	
}
else   //incorrect message
{
	$msg='Incorrect mesaage or username not given. The message received is '.$_GET["messagetext"].' from the number '.$mobile.'.<br><br>Warm Regards';
	send_email($to,$msg,$subject,"",$cc);
}			

}

function verify_mobile_smsphpFunction($msg,$mobile)
{
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
	markProfileInvalid($mobile);
	die("Profile is marked as Invalid");
}

$messageVal =checkMessageReceived($msg,$mobile);
if($messageVal&& $mobileno&& $mobile)
{
	if(is_numeric($messageVal))
		$sql="SELECT PROFILEID,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES FROM newjs.JPROFILE WHERE PHONE_MOB in('$mobileno','0$mobileno','$mobile') AND  activatedKey=1 and PROFILEID='$messageVal'";
	else
		$sql="SELECT PROFILEID,SUBSCRIPTION,SHOWPHONE_MOB,SHOWPHONE_RES FROM newjs.JPROFILE WHERE PHONE_MOB in('$mobileno','0$mobileno','$mobile') ORDER BY LAST_LOGIN_DT desc limit 1";
	$res=mysql_query_decide($sql) or logError($sql);
	if(mysql_num_rows($res))
	{
		while($row=mysql_fetch_array($res)){
			$pid=$row['PROFILEID'];
//			SEND_MOBSMS('111',$mobile,'Y');
			phoneUpdateProcess($pid,$mobile,'M','Y','SMS');
			if($row['SUBSCRIPTION']=='' && ($row['SHOWPHONE_MOB']=='Y' || $row['SHOWPHONE_RES']=='Y')){
				$sql="UPDATE jsadmin.OFFLINE_MATCHES SET CATEGORY='6' WHERE MATCH_ID='$pid' AND CATEGORY=''";
				mysql_query_decide($sql) or logError($sql);
			}
		}
	}
	else
	{
		if(is_numeric($messageVal))
			$sqlAlt ="SELECT PROFILEID, ALT_MOBILE, ALT_MOBILE_ISD,ALT_MOB_STATUS,SHOWALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE in('$mobileno','0$mobileno','$mobile') AND PROFILEID='".$messageVal."'";
		else
			$sqlAlt ="SELECT PROFILEID, ALT_MOBILE, ALT_MOBILE_ISD,ALT_MOB_STATUS,SHOWALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE ALT_MOBILE in('$mobileno','0$mobileno','$mobile')";
                $resAlt =mysql_query_decide($sqlAlt);
		if(mysql_num_rows($resAlt))
		{
			while($rowAlt =mysql_fetch_array($resAlt))
			{
				$pid=$rowAlt['PROFILEID'];
				phoneUpdateProcess($pid,$mobile,'A','Y','SMS');
				
			}
		}

	}
	die("Profile is marked as Verified");
}
else
	die("Profile could not be Verified");

}

// function to check the message validity
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
	
// function to mark the profile Invalid 
function markProfileInvalid($mobile='')
{
	if($mobile=='')
		return;
	$mobileno =substr($mobile,-10,10);
        $sql="SELECT PROFILEID FROM newjs.JPROFILE WHERE PHONE_MOB IN('$mobileno','0$mobileno','$mobile') ORDER BY 'LAST_LOGIN_DT' desc limit 1";
        $res=mysql_query_decide($sql) or logError($sql);
        if(mysql_num_rows($res)){
                $row=mysql_fetch_array($res);
                $profileid=$row['PROFILEID'];
		phoneUpdateProcess($profileid,'','','I','SMS');
        }
	//SEND_MOBSMS($profileid,$mobile,'I');
}

?>			
