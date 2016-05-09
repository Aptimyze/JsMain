<?php
/*
Author: @Esha
Date: 30-nov-2011
* File to be called by valueFirst
* This file captures the response sent by sender/ receiver 
* This file inserts data in ignore list  
* Input: Message text and mobile number form valueFirst 
*/
//include "IgnoreDetails.class.php";
die;
include "../profile/connect.inc";
$cc='esha.jain@jeevansathi.com';
$to='tanu.gupta@jeevansathi.com';
$subject="jsCaptureIgnoreSms.php track mail";
$msg='The api mentioned is being called. The message : '.$_GET["messagetext"].' is received from the number '.$mobile.'.<br/><br/>Warm Regards';
send_email($to,$msg,$subject,"",$cc);


connect_db();

$message=trim(preg_replace( '/ */', '', $_GET["messagetext"]));    //message type: BLOCK dial_code.
$mobile=substr($_GET["mobileno"],-10);
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
?>			
