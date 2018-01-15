<?php
class salesCampaignMailerTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'salesCampaignMailer';
		$this->briefDescription = 'VD Mailer task to send mail to VD Users';
		$this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:salesCampaignMailer|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    	// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		$mailId ='1806';
		/** code for daily count monitoring**/
	        $cronDocRoot = JsConstants::$cronDocRoot;
                $php5 = JsConstants::$php5path;
                passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SALES_FEEDBACK_MAILER#INSERT");
                /**code ends*/
		$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
                $instanceID = $countObj->getID('SALES_FEEDBACK_MAILER');
		$crmMailerObj 	= new crmMailer();
		$profilesArr 	= $crmMailerObj->getProfileForFeedbackMailer();
		$jprofileObj = new JPROFILE('newjs_slave');
		if(is_array($profilesArr))
			$profileDet = $jprofileObj->getAllSubscriptionsArr(array_keys($profilesArr));
		if(count($profilesArr)>0){
			foreach($profilesArr as $profileid=>$campaign){
				$deliveryStatus =$crmMailerObj->sendEmailForFeedback($mailId, $profileid,$instanceID,$campaign,$profileDet[$profileid]['PHONE_MOB']);
				$crmMailerObj->updateMailerSentStatus($profileid,$deliveryStatus);
			}
			/** code for daily count monitoring**/
                        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SALES_FEEDBACK_MAILER");
                        /**code ends*/
		}
		unset($crmMailerObj);
		unset($profilesArr);
	}
}
