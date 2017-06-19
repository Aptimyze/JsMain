<?php

// Email sent to user  who have been requested for a voice profile.
function voiceprofile_requestee($emailid="",$requester_pid,$requestee_pid)
{
        if($emailid =='')
                return;
	$ivr_number="";
	$emailStr ="";
	$emailStr ="Dear $requestee_pid,<p>

A Jeevansathi user, $requester_pid is interested in your profile and has requested for a voice profile from you.<p>

Jeevansathi’s voice profile feature allows you to record a three minute audio clip in your own voice. It is the best way for you to talk about yourself and in your own language. To record your voice profile, <b>Dial our IVR number $ivr_number now and follow the instructions.</b><p>

Wishing you the best,<br>
The Jeevansathi.com team<br>";
	$subject ="Request for Voice Profile";
	$mail = send_email($emailid,$emailStr,$subject,"info@jeevansathi.com");	
	if($mail)
		return true;
}

// Email to be sent to user who request for voice profiles from others but do not have a voice profile of his own
function voiceprofile_requester($emailid="", $requester_pid,$requestee_pid)
{
        if($emailid =='')
                return;
	$emailStr ="";
	$ivr_number="";
	$emailStr ="Dear $requestee_pid,<p>

We are delighted to know that you found Jeevansathi’s voice profile feature to be interesting and have requested other users to upload their voice profiles.<p>

Jeevansathi’s voice profile feature allows you to record a three minute audio clip in your own voice. It is the best way for you to talk about yourself and in your own language. We suggest that you too go ahead and record your own voice profile. To do that, <b>Dial our IVR number $ivr_number now and follow the instructions.</b><p>

Wishing you the best,<br>
The Jeevansathi.com team";
        $subject ="Request for Voice Profile";
	$mail = send_email($emailid,$emailStr,$subject,"info@jeevansathi.com"); 
	if($mail)
		return true;
}

//Email to be sent to users who requested others for a voice profile which has now been created
function voiceprofile_uploaded($emailid="",$requester_pid,$requestee_pid)
{
	if($emailid =='')
		return;
	$emailStr ="";
	$emailStr ="Dear $requester_pid,<p>

Congratulations! A Jeevansathi user, $requestee_pid has created a voice profile on your request. To hear it, visit their detailed profile by <a href='' target=_blank > clicking here </a> and click on the ‘Voice Profile’ link in this page.<p>

Wishing you the best,<br>
The Jeevansathi.com team";
        $subject ="Voice Profile Uploaded";
        $mail = send_email($emailid,$emailStr,$subject,"info@jeevansathi.com"); 
	if($mail)
		return true;
}

// Email sent ot the user whose voive profile has been rejected after screening
function voiceprofile_rejected($emailid="",$username)
{
	if($emailid =='')
		return;
	$emailStr ="";
	$emailStr ="Dear $username,<p> 

Your voice profile was rejected since it was found unsuitable to be uploaded into our database. Should you like to record a different voice profile again, please <a href='www.jeevansathi.com/profile/login.php'>login</a> to the site.";
	$subject ="Voice Profile Rejected";
	$mail = send_email($emailid,$emailStr,$subject,"info@jeevansathi.com");
	if($mail)
		return true;
}

// sms to call receiver after the post call between the caller and receiver using ivr function 
function callReceiver_sms($profileid,$mobile,$callerUsername,$caller_phone,$callStatus)
{
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
	$smsMSG ="";
	if($callStatus =='R')
		$smsMSG ="Jeevansathi.com user $callerUsername called from $caller_phone. You can find the profile details in My Contacts under 'Calls Received' folder.";
	else
		$smsMSG ="Jeevansathi.com user $callerUsername tried to call from $caller_phone. You can find the profile details in My Contacts under 'Calls Missed' folder.";
	$smsMSG=urlencode($smsMSG);
	$res = send_sms($smsMSG,'',$mobile,$profileid,'','');
	if($res)
		return true;
	return false;
}











?>
