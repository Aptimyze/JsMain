<?php 

/**
* 
*/
class ExclusiveMatchMailerTask extends sfBaseTask {
	
	private $smarty;
    private $mailerName = "EXCLUSIVE_MATCH_MAIL";

	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'ExclusiveMatchMailerTask';
		$this->briefDescription = 'Matchmail - JS Exclusive';
		$this->detailedDescription = <<<EOF
		The [ExclusiveMatchMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:ExclusiveMatchMailerTask|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
     	
     	$exclusiveMailer = new ExclusiveMatchMailer();
     	
     	//Populating ExclusiveMatchMailer
     	$result = $exclusiveMailer->getClientAndAgentDetails();
     	if(is_array($result))
	     	$this->populateMatchMailer($result);
	    unset($result);

	    //Calculating ExclusiveMatchMailer
	    $calculatetable = new incentive_ExclusiveMatchMailer();
	    $receivers = $calculatetable->getReceivers();
	    $result = $exclusiveMailer->getAcceptances($receivers);
	    foreach ($result as $key => $value) {
	    	if(!empty($value)){
	    		$str_value = "";
	    		foreach ($value as $k => $v) {
		    		$str_value[] = $v;
		    	}
		    	$str_value = implode(",", $str_value);
		    	$calculatetable->updateAcceptancesAndStatus($str_value,$key);
	    	}
	    }
	    $exclusiveMailer->logMails();
	    //Sending Mail
	    $receivers = $exclusiveMailer->getMailerProfiles();
	    $mailerServiceObj = new MailerService();
	    $this->smarty = $mailerServiceObj->getMailerSmarty();
	    if (is_array($receivers)) {
	    	$mailerLinks = $mailerServiceObj->getLinks();
    		$this->smarty->assign('mailerLinks',$mailerLinks);

    		$widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);

    		foreach ($receivers as $key => $value) {
    			$agentEmail = $value["AGENT_EMAIL"];
    			$agentName = $value["AGENT_NAME"];
    			$agentPhone = $value["AGENT_PHONE"];
    			unset($receivers[$key]["AGENT_PHONE"]);
    			unset($value["AGENT_PHONE"]);
    			unset($receivers[$key]["AGENT_NAME"]);
    			unset($value["AGENT_NAME"]);
    			unset($receivers[$key]["AGENT_EMAIL"]);
    			unset($value["AGENT_EMAIL"]);
    			$this->smarty->assign('mailerName',$agentEmail);
    			$pid = $value["RECEIVER"];
                $data = $mailerServiceObj->getRecieverDetails($pid,$value,$this->mailerName,$widgetArray);
                if (is_array($data)) {
                	$data["AGENT_PHONE"] = $agentPhone;
                	$data["AGENT_NAME"] = $agentName;
					$subjectAndBody = $this->getSubjectAndBody();
                    $data["body"]=$subjectAndBody["body"];
                    $subject = $subjectAndBody["subject"];
					$this->smarty->assign('data',$data);
                    $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");
//                     $file = fopen("/var/www/html/trunk/web/sampleMailer.html","w");
//                     fwrite($file,$msg);
                    //Sending mail and tracking sent status
                    $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$pid,$agentEmail,$agentName);
                    if ($flag) {
                    	$this->updateStatus($pid,'Y');
                    } else {
                    	$this->updateStatus($pid,'N');
                    }
                }
    		}
	    }
	}

	public function populateMatchMailer($data) {
        $date = date('Y-m-d');
		$exclusiveMatchMailerObj = new incentive_ExclusiveMatchMailer('newjs_master');
		$exclusiveMatchMailerObj->truncate();
		$exclusiveMailLogObj = new billing_EXCLUSIVE_MAIL_LOG();
		$profiles = $exclusiveMailLogObj->getProfiles('Y',"MATCH_MAIL",$date);
		if(!is_array($profiles))
			$profiles = array();
		foreach ($data as $key => $value) {
		    foreach ($value as $k => $v){
                if(MemberShipHandler::isEligibleForRBHandling($v["CLIENT_ID"]) && !in_array($v["CLIENT_ID"],$profiles))
                    $exclusiveMatchMailerObj->insertReceiversAndAgentDetails($v);
            }
		}
		unset($populateTable);
	}

	protected function getSubjectAndBody() {        
    	$subject["subject"] = "JS Exclusive Matchmail ".date('d/M/Y'); 
    	$subject["body"] = "These are the acceptances for the past week. Please go through the profiles and let me know which of them you would like us to pursue on your behalf. We will discuss these profiles on our weekly scheduled call.
Glad to be of service";
        return $subject;
  	}

  	public function updateStatus($pid,$status) {
        $date = date('Y-m-d');
  		$updateStatusObj = new billing_EXCLUSIVE_MAIL_LOG();
  		$updateStatusObj->updateStatus($pid,$status,$date);
  	}

}

?>