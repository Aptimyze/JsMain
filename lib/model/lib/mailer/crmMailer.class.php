<?php 
class crmMailer {
    
    public function sendEmailForFeedback($mailid, $profileid, $instanceID, $campaign=NULL, $phoneMob=NULL)
    {
        $email_sender = new EmailSender(MailerGroup::CRM_MAILER, $mailid);
        $emailTpl = $email_sender->setProfileId($profileid);
        $smartyObj = $emailTpl->getSmarty();
		$smartyObj->assign("instanceID",$instanceID);
		if(isset($campaign)){
			if($campaign == "IB_Sales"){
				$surveyLink = "https://www.surveymonkey.com/r/B6NGKY8";
			}
			if($campaign == "IB_Service"){
				$surveyLink = "https://www.surveymonkey.com/r/BZ8GXK7";
			}
		}
		$smartyObj->assign("SURVEY_LINK",$surveyLink);
        $email_sender->send();
        if($phoneMob) {
	        // Code to send Instant SMS
	        $SMS_MESSAGE = "Rate your experience with Jeevansathi customer service. Take this short survey: ".$surveyLink;
	        include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
	    	$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
			if(is_array($alertArr)){
				foreach($alertArr as $key=>$val){
					$xmlData1 = $xmlData1 . $smsVendorObj->generateXml($profileid,$phoneMob,$SMS_MESSAGE);
				}
			}
			if($xmlData1){
				$smsVendorObj->send($xmlData1,"transaction");
			}
			unset($xmlData1);
		}
        $deliveryStatus =$email_sender->getEmailDeliveryStatus();
	return $deliveryStatus;	
    }
    public function getProfileForFeedbackMailer()
    {
        $salesCampaignObj = new incentive_SALES_CAMPAIGN_PROFILE_DETAILS('newjs_slave');
        $profiles = $salesCampaignObj->getProfiles();
        return $profiles;
    }
    public function updateMailerSentStatus($profileid,$deliveryStatus)
    {
        $salesCampaignObj = new incentive_SALES_CAMPAIGN_PROFILE_DETAILS();
        $salesCampaignObj->updateMailerStatus($profileid,$deliveryStatus);
    }
    
}

?>
