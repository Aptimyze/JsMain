<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class FilterMailerEOISendTask extends sfBaseTask
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
	    $this->name             = 'FilterEOISend';
	    $this->briefDescription = 'send the mail to jeevansathi users for pending interests';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:FilterEOISend totalScript currentScript] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	    sfContext::createInstance($this->configuration);
	    //open rate tracking by nitesh as per vibhor        
	    $curdate =date("Y-m-d");
            /** code for daily count monitoring**/
            $cronDocRoot = JsConstants::$cronDocRoot;
            $php5 = JsConstants::$php5path;
            passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring FILTERED_MAILER#INSERT");
            /**code ends*/
	    $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
            $instanceID = $countObj->getID('FILTERED_MAILER');     
	    
	    $mailerEOIFilterObj = new MAIL_FilterEOI("newjs_master");
	    $profileMailData=$mailerEOIFilterObj->SelectFilterEOI($arguments["totalScript"],$arguments["currentScript"]);
	    		
		$count=$profileMailData[COUNT];
		unset($profileMailData[COUNT]);
		//print_r($profileMailData);
		$index=0;
		foreach ($profileMailData as $key => $value) {
			//print_r($key);
			if($key)
				$mailStatus = $this->sendMail($key,$value,$count[$index++],$instanceID);
			else
				$index++;
				$mailerEOIFilterObj->UpdateFilterEOI($key,$mailStatus);
		}
			$totalScriptVar = $arguments["totalScript"];
			$currentScriptVar = $arguments["currentScript"];
			if($currentScriptVar%$totalScriptVar==3)
			{
           if($instanceID)
				{
			/** code for daily count monitoring**/
                       passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring FILTERED_MAILER");
                    	/**code ends*/
				}  
            }
		}
		
	private static function sendMail($viewedProfileId, $viewerProfileArray,$countArray,$instanceID)
    {
    	$viewerProfileArray=explode(',',$viewerProfileArray); 
		$count      = $countArray;
		$countLimit=count($viewerProfileArray);
        $emailSender = new EmailSender(MailerGroup::EOI, 1802);
        $tpl = $emailSender->setProfileId($viewedProfileId);
		$profileObj = new Profile('', $viewedProfileId);
        $profileObj->getDetail('', '', 'SUBSCRIPTION,USERNAME,LAST_LOGIN_DT');
        $username=$profileObj->getUSERNAME();
        $lastActive=$profileObj->getLAST_LOGIN_DT();
        $date=date("Y-m-d");
        $diff = abs(strtotime($date) - strtotime($lastActive));
        $diff = floor(($diff)/ (60*60*24));
        if($diff>90)
        	return 'A';
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
				
       
        if ($count == 1)
            $tpl->getSmarty()->assign("otherProfileId", $viewerProfileArray[0]);
        
        $partialObj = new PartialList();
        $partialObj->addPartial("eoi_profile", "eoi_profile_filter", $viewerProfileArray);
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        $EmailTemplateObj= new EmailTemplate(1802);
        $date= strtotime(date("Y-m-d"));
    	$date = date('d M ', $date);
        $subject = "Did you see the $count Interests in your Filtered Inbox? | ".$date;
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
