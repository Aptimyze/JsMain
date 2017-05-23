<?
class astroReport
{
	//This function is used to send mails (sample astro report/actual astro report) to the user who requested it
	public function sendAstroMail($mailID,$otherUsername,$otherProfileId,$file,$type,$loggedInProfileId,$extraParameter="")
	{
		$email_sender = new EmailSender(MailerGroup::ASTRO_COMPATIBILTY,$mailID);
		
		$emailTpl = $email_sender->setProfileId($loggedInProfileId);
		$smartyObj = $emailTpl->getSmarty();
		$smartyObj->assign('otherUsername',$otherUsername);
		$smartyObj->assign('otherProfile',$otherProfileId);
		if($extraParameter == "noData")
		{
			$smartyObj->assign('noAttachment',"1");
		}
		$email_sender->setAttachment($file);
		if($type == "sample")
		{
			$email_sender->setAttachmentName("SampleAstroReport.pdf");
			$successArr["MESSAGE"]  = "Sample Mail Sent";
		}
		else
		{
			$email_sender->setAttachmentName("astroCompatibility-".$otherUsername.".pdf");
			$successArr["MESSAGE"]  = "Astro Report Sent";
		}
		$email_sender->setAttachmentType('application/pdf');
		$email_sender->send();
		return $successArr;		
	}

	//this function checks whether the desired key is set in redis or not and accordingly returns the value
	public function getSampleAstroFlag($loggedInProfileId)
	{
		$key = $loggedInProfileId."_sampleAstro";
		$value = JsMemcache::getInstance()->getSetsAllValue($key);				
		if(isset($value[0]))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	//this function calls jsMemcache to set the sampleAstrokey
	public function setSampleReportFlag($loggedInProfileId)
	{
		$key = $loggedInProfileId."_sampleAstro";
		JsMemcache::getInstance()->addDataToCache($key,"1",21600);
	}

	//this function gets the actual report flag of pg with respect to pog
	public function getActualReportFlag($loggedInProfileId,$otherProfileId)
	{
		$key = $loggedInProfileId."_pog_".$otherProfileId."_actualAstro";
		$value = JsMemcache::getInstance()->getSetsAllValue($key);		
		if(isset($value[0]))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	//this function gets the count of acutal reports sent to a profile
	public function getNumberOfActualReportSent($loggedInProfileId)
	{
		$key = $loggedInProfileId."_actualAstro";
		$value = JsMemcache::getInstance()->get($key,"",'',0);
		if($value == NULL)
			$value="0";
		return $value;
	}

	//this sets the flag with respect to a pg and pog
	public function setActualReportFlag($loggedInProfileId,$otherProfileId)
	{
		$key = $loggedInProfileId."_pog_".$otherProfileId."_actualAstro";
		JsMemcache::getInstance()->addDataToCache($key,"1",21600);
	}

	//this increments the count in actual report for a profile
	public function addDataForActualReport($loggedInProfileId)
	{
		$key = $loggedInProfileId."_actualAstro";
		JsMemcache::getInstance()->incrCount($key);
	}

	public function setExpiryTime($loggedInProfileId)
	{
		$key = $loggedInProfileId."_actualAstro";
		JsMemcache::getInstance()->setExpiryTime($key,86400);
	}
}
?>