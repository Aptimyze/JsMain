<?php

class ExpiringInterestMailerSendingTaskTask extends sfBaseTask
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
		$this->name             = 'NEWJS_ExpiringInterestMailerSend';
		$this->briefDescription = 'send the mail to jeevansathi users for expiring interests';
		$this->detailedDescription = <<<EOF
		Call it with:
		[php symfony mailer:NEWJS_ExpiringInterestMailerSend totalScript currentScript] 
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit','512M');
		ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);
		$mailerEIObj = new MAIL_ExpiringInterest("newjs_master");
		//open rate tracking by nitesh as per vibhor        
		$cronDocRoot = JsConstants::$cronDocRoot;
		$php5 = JsConstants::$php5path;
		passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring ExpiringInterest_MAILER#INSERT");
		$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
		$instanceID = $countObj->getID('Expiring_MAILER');

		$profileMailData=$mailerEIObj->SelectMailerEI($arguments["totalScript"],$arguments["currentScript"]);
		$count = $profileMailData[COUNT];
		unset($profileMailData[COUNT]);
		$index=0;
		foreach ($profileMailData as $key => $value)
		{
			$mailStatus = $this->sendMail($key,$value,$count[$index++],$instanceID);
			$mailerEIObj->UpdateMailerEI($key,$mailStatus);
		}
		$totalScriptVar = $arguments["totalScript"];
		$currentScriptVar = $arguments["currentScript"];
		if($currentScriptVar%$totalScriptVar==1)
		{
			if($instanceID)
			{
				/** code for daily count monitoring**/
				   passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring ExpiringInterest_MAILER");
				/**code ends*/
			}
		}
	}
	
  private static function sendMail($viewedProfileId, $viewerProfileArray,$countArray,$instanceID)
	{
		$viewerProfileArray=explode(',',$viewerProfileArray[0]); 
		$count = $countArray;
		$countLimit	= count($viewerProfileArray);
		$emailSender = new EmailSender(MailerGroup::EOI, 1843);
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
			if($isRenewal && ($isRenewal!=1))
			{
				$discountMax = $memHandlerObj->getMaxVdDiscount($variableDiscount["DISCOUNT"]);
			}
			else
			{
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
		$partialObj->addPartial("eoi_profile", "eoi_profile_ei", $viewerProfileArray);
		$partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
		$tpl->setPartials($partialObj);
		//$EmailTemplateObj= new EmailTemplate(1796);
		$date= strtotime(date("Y-m-d"));
		$date = date('d M ', $date);
		$subject = $count." Interests are expiring in the next one week | ".$date;
  		$tpl->setSubject($subject);

		if(CommonConstants::contactMailersCC)
		{
			$contactNumOb=new newjs_JPROFILE_CONTACT();
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
