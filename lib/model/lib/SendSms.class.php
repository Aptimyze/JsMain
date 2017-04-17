<?php
include_once(sfConfig::get("sf_web_dir")."/profile/InstantSMS.php");

class SendSms
{
	public function send_sms($profileid,$messageType,$userType='',$photoRejectReason="")
	{		
		if ($messageType == "accepted")
		{
			$sms = new InstantSMS("PHOTO_APPROVE",$profileid);
			$sms->send();
		}
		else if($messageType == "rejected")
		{			
			$sms = new InstantSMS("PHOTO_DISAPPROVE",$profileid,$photoRejectReason);
			$sms->send();
		}
	}
}
?>
