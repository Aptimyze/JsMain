<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class YesNoMailerSendingTask extends sfBaseTask
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
	    $this->name             = 'NEWJS_YesNoMailerSend';
	    $this->briefDescription = 'send the mail to jeevansathi users for pending interests';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:NEWJS_YesNoMailerSend totalScript currentScript] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	       sfContext::createInstance($this->configuration);
		$mailerYNObj = new MAIL_YesNoMail("newjs_master");
	    //open rate tracking by nitesh as per vibhor        
            $cronDocRoot = JsConstants::$cronDocRoot;
            $php5 = JsConstants::$php5path;
            passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring YESNO_MAILER#INSERT");
	    $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
            $instanceID = $countObj->getID('YESNO_MAILER');       
	            
	    $profileMailData=$mailerYNObj->SelectMailerYN($arguments["totalScript"],$arguments["currentScript"]);
			//var_dump($profileMailData);die;
			$count=$profileMailData[COUNT];
			unset($profileMailData[COUNT]);
			$index=0;
			foreach ($profileMailData as $key => $value) {
				$mailStatus = $this->sendMail($key,$value,$count[$index++],$instanceID);
				$mailerYNObj->UpdateMailerYN($key,$mailStatus);
				
			}
			$totalScriptVar = $arguments["totalScript"];
			$currentScriptVar = $arguments["currentScript"];
			if($currentScriptVar%$totalScriptVar==1)
			{
                if($instanceID)
				{
					/** code for daily count monitoring**/
                       passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring YESNO_MAILER");
                    /**code ends*/
				}
            }
			
		}
		
	private static function sendMail($viewedProfileId, $viewerProfileArray,$countArray,$instanceID)
    {
    	$viewerProfileArray=explode(',',$viewerProfileArray[0]); 
		$count      = $countArray;
		$countLimit=count($viewerProfileArray);
        $emailSender = new EmailSender(MailerGroup::EOI, 1796);
        $tpl = $emailSender->setProfileId($viewedProfileId);
		$profileObj = new Profile('', $viewedProfileId);
        $profileObj->getDetail('', '', 'SUBSCRIPTION');
        $subscriptionStatus = $profileObj->getPROFILE_STATE()->getPaymentStates()->isPaid();
        $tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
        $tpl->getSmarty()->assign("count", $count);
        $tpl->getSmarty()->assign("countLimit", $countLimit);
        $tpl->getSmarty()->assign("instanceID", $instanceID);
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
			$tpl->getSmarty()->assign("topSource","VDEOI1".$discountMax);
			$tpl->getSmarty()->assign("BottomSource","VDEOI2".$discountMax);
		}
		else
		{
			$tpl->getSmarty()->assign("BottomSource","EOI2");
		}
				
       
        if ($count == 1){
            $tpl->getSmarty()->assign("otherProfileId", $viewerProfileArray[0]);
            $tpl->getSmarty()->assign("otherProfile", $viewerProfileArray[0]);
        }
        
        $partialObj = new PartialList();
        $partialObj->addPartial("eoi_profile", "eoi_profile_yn", $viewerProfileArray);
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    		 $date = date('d M ', $date);
        $subject = "Respond to ".($count)." member(s) waiting for your response | ".$date;
	$tpl->setSubject($subject);

        if(CommonConstants::contactMailersCC)
        {    

        $contactNumOb=new ProfileContact();
        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$viewedProfileId),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
        {
           $ccEmail =  $numArray['0']['ALT_EMAIL'];    
        }
        else $ccEmail = "";
        }
        else $ccEmail = "";

        $emailSender->send('','',$ccEmail);
        $status = $emailSender->getEmailDeliveryStatus();
        return $status;
    }
}
