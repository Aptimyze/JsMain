<?php




class emailVerification

{
	


	public function sendVerificationMail($profileId,$uniqueId,$sendTo="") 
	{

		if(!$profileId || !$uniqueId)return false;
		$emailSender = new EmailSender('4', '1834');
        $tpl = $emailSender->setProfileId($profileId);
		$memObject=JsMemcache::getInstance();
		$instanceID=$memObject->get('emailVerInstanceId');	

		if(!$instanceID)
		{
			$countObj=new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
			$instanceID = $countObj->getID('EMAIL_VER_MAILER');
			$memObject->remove('emailVerInstanceId');
			$memObject->set('emailVerInstanceId',$instanceID,3600*24);
		}
//        die($emailSender->getProfile()->getEMAIL());
        $tpl->getSmarty()->assign("profileid", $profileId);
        $tpl->getSmarty()->assign("uniqueId", $uniqueId);
        $tpl->getSmarty()->assign("instanceID", $instanceID);
        $tpl->getSmarty()->assign("emailType", 1);

		$partialObj = new PartialList();
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    	$date = date('d M ', $date);
        $subject = "Verify your Email ID";
		$tpl->setSubject($subject);
        $emailSender->send($sendTo);
        $status = $emailSender->getEmailDeliveryStatus();
       	(new MAIL_EMAIL_VER_MAILER())->makeEntry($profileId,$status);
        return true;
	}


	public function sendAlternateVerificationMail($profileId,$uniqueId,$alternateEmail) 
	{

		if(!$profileId || !$uniqueId || !$alternateEmail)return false;
		$emailSender = new EmailSender('4', '1834');
                $tpl = $emailSender->setProfileId($profileId);
		$memObject=JsMemcache::getInstance();
		$instanceID=$memObject->get('altEmailVerInstanceId');	

		if(!$instanceID)
		{
			$countObj=new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
			$instanceID = $countObj->getID('ALTERNATE_EMAIL_VER_MAILER');
			$memObject->remove('altEmailVerInstanceId');
			$memObject->set('altEmailVerInstanceId',$instanceID,3600*24);
		}
//        die($emailSender->getProfile()->getEMAIL());
        $tpl->getSmarty()->assign("profileid", $profileId);
        $tpl->getSmarty()->assign("uniqueId", $uniqueId);
        $tpl->getSmarty()->assign("instanceID", $instanceID);
        $tpl->getSmarty()->assign("emailType", 2);
		$partialObj = new PartialList();
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    	$date = date('d M ', $date);
        $subject = "Verify your Alternate Email ID";
		$tpl->setSubject($subject);
        $emailSender->send($alternateEmail);
        $status = $emailSender->getEmailDeliveryStatus();
       	(new MAIL_ALTERNATE_EMAIL_MAILER())->makeEntry($profileId,$status);
        return true;
	}


	public function markVerifiedInEmailLog($profileid,$email)
	{

		
		(new NEWJS_EMAIL_CHANGE_LOG())->markAsVerified($profileid,$email);


	}








}