<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class remindUnrespondedInterestsMailerSendingTask extends sfBaseTask
{
	protected function configure()
  	{
                $this->addArguments(array(
                	new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
		));
  		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'MAIL_remindUnrespondedInterestsMailerSend';
	    $this->briefDescription = 'send the mail to jeevansathi users for pending interests';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:MAIL_remindUnrespondedInterestsMailerSend totalScript currentScript] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	       sfContext::createInstance($this->configuration);
		$reminderMailer = new MAIL_UNRESPONDED_CONTACTS("newjs_master");
	    //open rate tracking by nitesh as per vibhor        
            $cronDocRoot = JsConstants::$cronDocRoot;
            $php5 = JsConstants::$php5path;
            passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring REMINDER_MAILER#INSERT");
	    $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
            $instanceID = $countObj->getID('REMINDER_MAILER');       
	            
	    $profileMailData=$reminderMailer->SelectMailerData($arguments["totalScript"],$arguments["currentScript"]);
			//var_dump($profileMailData);die;
			$count=$profileMailData[COUNT];
			unset($profileMailData[COUNT]);
			$index=0;
			foreach ($profileMailData as $key => $value) {
				$mailStatus = $this->sendMail($key,$value,'',$instanceID);
				$reminderMailer->UpdateMailer($key,$mailStatus);
				
			}
			$totalScriptVar = $arguments["totalScript"];
			$currentScriptVar = $arguments["currentScript"];
			if($currentScriptVar%$totalScriptVar==1)
			{
                if($instanceID)
				{
					/** code for daily count monitoring**/
                       passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring REMINDER_MAILER");
                    /**code ends*/
				}
            }
			
		}
		
	private static function sendMail($viewedProfileId, $viewerProfileArray,$countArray,$instanceID)
    {
    	$viewerProfileArray=explode(',',$viewerProfileArray[0]); 
		$countLimit=count($viewerProfileArray);
        $emailSender = new EmailSender(MailerGroup::REMINDER_MAILER, 1840);
        $tpl = $emailSender->setProfileId($viewedProfileId);
		$tpl->getSmarty()->assign("count", $countLimit);
        $tpl->getSmarty()->assign("countLimit", $countLimit);
        $tpl->getSmarty()->assign("instanceID", $instanceID);
        $tpl->getSmarty()->assign("profileid", $viewedProfileId);
       


//variable discount


        $profileObj = new Profile('', $viewedProfileId);
        $profileObj->getDetail('', '', 'SUBSCRIPTION');
        $subscriptionStatus = $profileObj->getPROFILE_STATE()->getPaymentStates()->isPaid();
        $tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
        $variableDiscountObj = new VariableDiscount;
		$variableDiscount = $variableDiscountObj->getDiscDetails($viewedProfileId);
		if(!empty($variableDiscount))
		{
			// pick max of VD,RD
			$memHandlerObj = new MembershipHandler();
			$isRenewal = $memHandlerObj->isRenewable($viewedProfileId);
			if($isRenewal && ($isRenewal!=1)){
				$discountMax = $memHandlerObj->getMaxVdDiscount($variableDiscount["DISCOUNT"]);
			} else {
				$discountMax = $variableDiscount["DISCOUNT"];
			}
			//$tpl->getSmarty()->assign("variableDiscount",$variableDiscount["DISCOUNT"]);
			$tpl->getSmarty()->assign("variableDiscount",$discountMax);
			$tpl->getSmarty()->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
			$tpl->getSmarty()->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
			//$tpl->getSmarty()->assign("topSource","VDEOI1".$variableDiscount["DISCOUNT"]);
			//$tpl->getSmarty()->assign("BottomSource","VDEOI2".$variableDiscount["DISCOUNT"]);
			$tpl->getSmarty()->assign("topSource","SHLST1".$discountMax);
			$tpl->getSmarty()->assign("BottomSource","SHLST2".$discountMax);
		}
		else
		{
			$tpl->getSmarty()->assign("BottomSource","SHLST2");
		}
		////////////////////////////////////////////////		
       
		$partialObj = new PartialList();
        $partialObj->addPartial("reminderMailerTuple", "reminderMailerTuple", $viewerProfileArray);
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    		 $date = date('d M ', $date);
        $subject = "Send Reminders to these profiles to get more responses | ".$date;
		$tpl->setSubject($subject);
        $emailSender->send();
        $status = $emailSender->getEmailDeliveryStatus();
        ProfileCacheLib::getInstance()->__destruct();
        return $status;
    }
}
