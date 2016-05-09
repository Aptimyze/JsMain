<?php
include_once(JsConstants::$alertDocRoot."/commonFiles/comfunc.inc");
/*
 * Author: Lavesh Rawat
 * This cron is used to run 
 * A) The actual mailer based on mailerid.
 * B) All the pending test mailers.
*/
class FireMailTask extends sfBaseTask
{
	private $limit = 5000;
	private $fireActualMail = 'ACTUAL';
	private $fireTestMail  = 'TEST';
	private $smarty;
	private $mailerId;
	private $mailersDetailsArr;	
	private $website;
	private $MmmMailerDetailedInfo;
	private $MmmMailerBasicInfo;
	private $mailerName;

 	protected function configure()
  	{
		$this->addArguments(array(
			new sfCommandArgument('TYPE', sfCommandArgument::REQUIRED, 'My argument'),
			new sfCommandArgument('MAILERID', sfCommandArgument::REQUIRED, 'My argument'),
                ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'masscomm'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'FireMail';
	    $this->briefDescription = 'fire both test and actutal mailer for mmm interface';
	    $this->detailedDescription = <<<EOF
	    This cron will fire
            1. Test mails     : argument TYPE=TEST
            2. Actual mails   : argument TYPE=ACTUAL
	  [php symfony cron:FireMail TYPE MAILERID] 
EOF;
  	}

	/**
	* Send mail / Test Mail .....
	*/ 
	protected function execute($arguments = array(), $options = array())
  	{
		//sleep(1000);
                if(!sfContext::hasInstance())
                         sfContext::createInstance($this->configuration);

		$this->smarty = MmmUtility::createSmartyObject();

		$type = $arguments["TYPE"];
		$this->mailerId = $arguments["MAILERID"];

		/* locking */
		$LockingService = new LockingService;
		$file = $type.$this->mailerId;
		$lock = $LockingService->getFileLock($file,1);
		if(!$lock)
			die;
		/* locking */

		$this->MmmMailerDetailedInfo = new MmmMailerDetailedInfo;
		$this->MmmMailerBasicInfo = new MmmMailerBasicInfo;

		if($type==$this->fireTestMail)
			$this->smarty->assign("isTestMailer",1);

		if($type==$this->fireActualMail || $type==$this->fireTestMail)
		{
			/** List Mailer Details of Inidividual Mailer like subject,sender....*/
			$this->mailersDetailsArr  = $this->MmmMailerDetailedInfo->getMailerInfo($this->mailerId);	
			$this->mailerBasicInfoArr = $this->MmmMailerBasicInfo->retreiveMailerInfo($this->mailerId);


			if($type == $this->fireActualMail)
			{
				$proceed = $this->scheduleCheck($this->mailersDetailsArr['SCHEDULE_TIME']);
				if(!$proceed) die;
			}

			$this->webSite = $this->mailerBasicInfoArr["MAILER_FOR"];

			$this->createDumpUsingSearchQuery($type);
			$this->serviceMail= $this->isServiceMail();
			$this->smarty->assign("serviceMail",$this->serviceMail);

			if($this->webSite == '9'){
				$this->responseType = $this->mailerBasicInfoArr["RESPONSE_TYPE"];
				$this->smarty->assign('response_type',$this->responseType);
                        	$this->mailerId = $this->mailerBasicInfoArr['MAILER_ID'];
				$this->mmm_99acresUtility = new mmm_99acresUtility;
				$url = JsConstants::$baseUrl99."/do/MMM_Response/UpdateMailerFiredDate";
				$postParams = "q=$this->mailerId";
				$output = mmm_99acresUtility::sendCurlRequestFor99($url."?".$postParams);
			}
			$this->loadPartials();
			
			$individualMailer = new Individual_Mailers;
                        $cntTillYesterday = $individualMailer->getTotalCountTillYesterday($this->mailerId);
			$dateCurrent = date('Y-m-d');

			/****For PPC - Start*****/
			if($this->webSite == '9')
			{
            	$templateName = MmmUtility::getTemplateName($this->mailerId);
	            $filePath =  JsConstants::$docRoot.'/uploads/mmm/templates/individual_mailer_templates/'.$templateName;
    	        $clientTemplate = file_get_contents($filePath); 
            	if(strpos($clientTemplate,'ppcqueryform'))
					$this->mailerName = 'PPC';
			}
            /****For PPC - End*******/

			/*
			* Loop untill no mail is left to be sent. 
			* list mailer-info for which mailers need to sent. A limit is added to avoid exceeding the size of array to be too big.
			*/
			$run = TRUE;
			while($run)
			{	
				$mailerArr = $this->retrieveEmailsToBeSend($type);
				if(!empty($mailerArr))
				{
					/* (A) Creating message to be fired (B) Sending message. */
					foreach($mailerArr as $pid => $mailInfoArr)
					{
						if($type==$this->fireTestMail)
							$pid = substr($pid,0,strpos($pid,'#'));

						if($pid==0 || !$pid)
						{
							mail("lavesh.rawat@gmail.com","pid=0","pid=0");
							die;
						}

						if($this->webSite == '9')
                        			{
                                			$smartyArr = $this->mmm_99acresUtility->getSmartyArray($this->responseType,$this->mailerId,$pid,$mailInfoArr);
							if(is_array($smartyArr))
							foreach($smartyArr as $key=>$val)
								$this->smarty->assign($key,$val);
							$isMrc = $this->mmm_99acresUtility->is15DigitProfileId($pid);
							if($isMrc)
								$this->smarty->assign("isMrc",'Y');
							else
								$this->smarty->assign("isMrc",'N');
                        			}
						$toEmail = $mailInfoArr["EMAIL"];

						if($this->mailerBasicInfoArr["MAIL_TYPE"] == 'hcm')
							$msg = nl2br($this->mailersDetailsArr["DATA"]);
						else
							$msg = $this->getEmailMessage($pid,$mailInfoArr);
						if($msg)
						{
							if($this->mailerBasicInfoArr["MAIL_TYPE"] == 'hcm')
							{
								if(strlen($msg)<20)
								{
									mail("lavesh.rawat@gmail.com","blankEmail","blankEmail:$this->mailerId");
									die;
								}
							}
							elseif(strlen($msg)<200)
							{
								mail("lavesh.rawat@gmail.com","blankEmail2","blankEmail2:$this->mailerId");
								die;
							}


						//$mmm_99acresUtility = new mmm_99acresUtility();
						//if($this->webSite == '9') $msg = $mmm_99acresUtility->parseHTML($msg,$toEmail,$this->mailerId);
							


							$statusMail = $this->fireEmailToUser($toEmail,$msg,$mailInfoArr);

							if($type!=$this->fireTestMail)
								$this->updateStatus($pid,$statusMail);
						}
					}

					/* updating cnt */
					if($type==$this->fireActualMail)
					{
						$individualMailer = new Individual_Mailers;
						$dateCurrent2 = date('Y-m-d');
						if($dateCurrent != $dateCurrent2){
							$cntTillYesterday = $individualMailer->getTotalCountTillYesterday($this->mailerId);
							$dateCurrent = $dateCurrent2;
						}
 						$cnt = $individualMailer->getCountOfMails($this->mailerId,'1');
						$cntToBeInserted = ($cnt - $cntTillYesterday);
 						$mmmjs_MAIL_DATA_NEW =  new mmmjs_MAIL_SENT;
						$mmmjs_MAIL_DATA_NEW->insert($this->mailerId,$cntToBeInserted);
					}
					/* updating cnt */

					if($type==$this->fireTestMail)
					{
						$this->MmmMailerBasicInfo->updateStatus($this->mailerId, MmmConfig::$status['TEST_COMPLETED']);
						break;
					}
					else
					{
						$individualMailer = new Individual_Mailers;
						$cnt = $individualMailer->getCountOfMails($this->mailerId,'0');
						if($cnt==0){
							$this->MmmMailerBasicInfo->updateStatus($this->mailerId, MmmConfig::$status['RUNNING_COMPLETED']);
							$this->MmmMailerBasicInfo->updateLastRtime($this->mailerId);

							$domainData = new DOMAIN_SENT_DATA;
							$domainData->domainCountForMIS($this->mailerId);
						}
					}
				}
				else
					$run = FALSE;
			}

		}
		else
			die("Invalid 1st arguments value");
	}


	/**
	* This function create dump based on search query specified.
	* This is not done at real time to avoid delay and also we will get latest details of users.
	* Also we skip creating of dump, if it was done before.
	* @param type if atcual/test mailer is fired.
	*/
	public function createDumpUsingSearchQuery($type)
	{
		if($type==$this->fireActualMail)
		{
			$isDumpCreated = $this->mailersDetailsArr["DUMP"];
			if($isDumpCreated != 'Y')
			{
				$individualMailer = new Individual_Mailers;
                		$individualMailer->createDumpPerform($this->mailerId,$this->webSite,'');
		                $this->MmmMailerBasicInfo->updateRTime($this->mailerId); /* update run time of mailer */
			}
		}
		else
		{
			$individualMailer = new Individual_Mailers;
			$individualMailer->createDumpPerform($this->mailerId,$this->webSite,'test');
		}
	}

	/** 
	* check if mailer is promotion or not.
	* @return 'Y' if mailer is promo.
	*/
	public function isServiceMail()
	{
		$mmmSearchQuery = SearchQueryFactory::getObject($this->webSite);
		$tempArr = $mmmSearchQuery->showMailerSpecs($this->mailerId);
		if($tempArr['TYPE'] == 'S')
			return 'Y';
		return 'N';
	}

	/** 
	* Partials needs to be loaded here based on website.
	* A pattern need to be followed for naming convention of the partials.
	*/
	private function loadPartials()
	{
		sfProjectConfiguration::getActive()->loadHelpers("Partial","footer".$this->webSite."Mailer");
		sfProjectConfiguration::getActive()->loadHelpers("Partial","header".$this->webSite."Mailer");
	}

	/**
	* This function will list all the mailer id with the information.
	* @param type if atcual/test mailer is fired.
	* @return mailerArr array containing mailer info.
	*/
	public function retrieveEmailsToBeSend($type)
	{
		if($type==$this->fireActualMail)
		{
			$totalStaggerTime = $this->mailersDetailsArr["STAGGER"];
			$rtime = $this->mailerBasicInfoArr["RTIME"];

			$todayDate = date('y-m-d');

			$day = $this->dayDiff($rtime, $todayDate);

			$individualMailer = new Individual_Mailers;
			$mailerArr = $individualMailer->retrieveEmails(array('mailer_id' => $this->mailerId, 'limit' => $this->limit, 'totalStaggerTime' => $totalStaggerTime, 'day' => $day));
		}
		else
		{	
			$TestMail = new TestMail;
			$mailerArr = $TestMail->getAllTestEmails($this->webSite, $this->mailerId, 'Y');
		}
		return $mailerArr;
	}

	/**
	* This function will generate mailer message content.
	*/
	private function getEmailMessage($pid,$mailInfoArr)
	{
		$toEmail  = $mailInfoArr['EMAIL'];
		foreach(MmmConfig::$variableMapping as $k=>$v)
			$this->smarty->assign($k,$mailInfoArr[$v]);
		$mailerId = $this->mailerId;
		//$promoMail = 'Y';
		$this->smarty->assign("promoMail",$this->promoMail);
		$this->smarty->assign("email",$mailInfoArr["EMAIL"]);
		$this->smarty->assign("profileid",$pid);

		$this->smarty->assign("browserUrl",$this->mailersDetailsArr["BROWSERURL"]);
		$this->smarty->assign("mailerId",$this->mailerId);
		if($this->webSite=='J') 
		{
			$echecksum = JsAuthentication::jsEncrypt($pid,$toEmail);
			$this->smarty->assign("echecksum",$echecksum);

			$checksum = JsAuthentication::jsEncryptProfilechecksum($pid);
			$this->smarty->assign("checksum",$checksum);
		}
		if($this->webSite == '9')
		{
			$this->smarty->assign('name',$mailInfoArr["NAME"]);
			$this->smarty->assign('phone',$mailInfoArr["PHONE"]);
			$timestamp = time();
			$param = base64_encode($timestamp.'|'.$pid);

			if($this->mailerName == 'PPC')
			{
                $ppcSmarty = base64_encode($mailInfoArr["NAME"].'|'.$mailInfoArr["EMAIL"].'|'.$mailInfoArr["PHONE"]);
                $this->smarty->assign('ppcqueryform',$ppcSmarty);
            }

			$this->smarty->assign("profileid",$param);  // assinging profile id and timestamp base64 encoded for security reason.
			$url = JsConstants::$mmmjs99acres."/do/MMM_Utility/getToken";
   		        $postParams = "pid=$pid";
                        $checksum = mmm_99acresUtility::sendCurlRequestFor99($url."?".$postParams);
			$this->smarty->assign("checksum",$checksum);
			
		}
		$templateName = MmmUtility::getTemplateName($mailerId);
		$msg = $this->smarty->fetch("individual_mailer_templates/".$templateName);
		return $msg;
	}

	/**
	* This function will fire email to user.
	*/
        private function fireEmailToUser($toEmail,$msg,$mailInfoArr)
        {
                $from    = $this->mailersDetailsArr["F_EMAIL"];
                $subject = $this->mailersDetailsArr["SUBJECT"];
                if(strstr($subject,'$'))
                        foreach(MmmConfig::$variableMapping as $k=>$v)
                                $subject = str_replace("~$".$k."`",$mailInfoArr[$v],$subject);
                if($this->mailersDetailsArr["F_NAME"])
                        $fname = $this->mailersDetailsArr["F_NAME"];
                $canSendObj= canSendFactory::initiateClass($channel=CanSendEnums::$channelEnums[EMAIL],array("EMAIL"=>$toEmail,"EMAIL_TYPE"=>"PROMO_MMM"),$pid=1);
		$canSend = $canSendObj->canSendIt();
		if($canSend)                
			$status = send_email($toEmail,$msg,$subject,$from,"","","","","","",1,"",$fname);

                if(!$status)
                        $status=2;
                return $status;
        }

	/**
	* This function will mark the mailer to be sent to a particular user
	*/		
	private function updateStatus($pid,$sent)
	{
		$individualMailer = new Individual_Mailers;
		$mailerArr = $individualMailer->updateStatus(array('mailer_id' => $this->mailerId, 'profileId'=> $pid),$sent);
	}

	private function dayDiff($start, $end)
	{
		$start = substr($start,0,10);
		$end = substr($end,0,10);
		$datetime1 = new DateTime($start);
		$datetime2 = new DateTime($end);
		$interval = $datetime1->diff($datetime2);
		$diff = $interval->format('%a');
		return $diff;
	}

	private function scheduleCheck($scheduleTime)
        {
                $machineTime =  date("Y/m/d H:i");
                $curTime = $this->getIST($machineTime);
                $scheduleTime = str_replace('T',' ',$scheduleTime);

                $curTime = strtotime($curTime);
                $scheduleTime = strtotime($scheduleTime);

                if($curTime < $scheduleTime) return false;
                                return true;
        }
        public function getIST($time_est) {
                //NOTE: %M is infact correct!!
                $ISTtime=strftime("%Y-%m-%d %H:%M",strtotime("$time_est + 10 hours 30 minutes"));
                return $ISTtime;
        }

}
