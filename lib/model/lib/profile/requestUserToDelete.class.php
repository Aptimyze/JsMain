<?php
/**
 * Library For Sending request to delete profile.
 * 
 */
 
/**
 *
 * @package    jeevansathi
 * @subpackage profile
 * @version    16-01-2017
 */
class requestUserToDelete 
{


		public function deleteRequestedBySelf($profileId)
	{	
		 include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
		 $sms = new InstantSMS("REQ_CRM_DEL_SELF", $profileId); 
         $sms->send();
         $this->sendMailForDeletion($profileId,'1');

	}


		public function deleteRequestedByOther($profileId)
	{	
		include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
		$sms = new InstantSMS("REQ_CRM_DEL_OTHER", $profileId);
        $sms->send();
        $this->sendMailForDeletion($profileId,'0');
	}


		public function sendMailForDeletion($profileId,$selfDEL,$sendTo='')
	{
		if(!$profileId)return false;
		$emailSender = new EmailSender(MailerGroup::TOP8, '1846');
        $tpl = $emailSender->setProfileId($profileId);
		$memObject=JsMemcache::getInstance();
//		$instanceID=$memObject->get('emailVerInstanceId');	
/*
		if(!$instanceID)
		{
			$countObj=new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
			$instanceID = $countObj->getID('EMAIL_VER_MAILER');
			$memObject->remove('emailVerInstanceId');
			$memObject->set('emailVerInstanceId',$instanceID,3600*24);
		}
*/
//        die($emailSender->getProfile()->getEMAIL());
        $tpl->getSmarty()->assign("profileid", $profileId);
        if($selfDEL == '1'){  
        $subject = "Delete your profile on Jeevansathi";
        $tpl->getSmarty()->assign("emailType", 1);
    	}
    	else{
        $subject = "Are you already married/engaged? Please delete your profile on Jeevansathi";
        $tpl->getSmarty()->assign("emailType", 2);
    	}
        

		$partialObj = new PartialList();
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    	$date = date('d M ', $date);
		$tpl->setSubject($subject);
        $emailSender->send($sendTo);
        $status = $emailSender->getEmailDeliveryStatus();
       	(new MAIL_EMAIL_VER_MAILER())->makeEntry($profileId,$status);
        return true;

	}
/*
	
*/

}	