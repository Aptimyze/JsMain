<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LowAcceptanceMailerTask
 *
 * @author tushar
 */
class PendingInterestMailerTask extends sfBaseTask {

    private $smarty;
    private $mailerName = "PENDING_INTEREST_MAIL";
    private $acceptanceCount = 2; // provides the upper bound for the AcceptanceCount
    
    protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'PendingInterestMailerTask';
		$this->briefDescription = 'Pending Interest Mail - JS Exclusive';
		$this->detailedDescription = <<<EOF
		The [PendingInterestMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:PendingInterestMailerTask|INFO]
EOF;
	}
    
    // @override
    protected function execute($arguments = array(), $options = array()) {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        // Get the low acceptances profile
        $pendingInterest = new billing_EXCLUSIVE_MAIL_LOG();
        $pendingInterestList = $pendingInterest->getLowAcceptanceProfiles($this->acceptanceCount);

        // get details of each profile
        $utility = new ExclusivePendingInterestUtility();
        
        $interestDetails2 = $utility->getProfileInterestDetails($pendingInterestList);
        
        // preparing the input format for receiving the Receiver Details
        $interestDetails = $utility->castToInputObject($interestDetails2);
        $widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);
        
        $mailerServiceObj = new MailerService();
        
        foreach($interestDetails as $key => $value) {
            
            $values = $utility->populateValueParameter($key, $value);
            $receiverDetails = $mailerServiceObj->getRecieverDetails($key, $values, $this->mailerName, $widgetArray);
            $usersList = $receiverDetails["USERS"];
            
            // check if no user is received, no further processing is needed.
            if(empty($usersList)) {
                break;
            }
            $receiverDetails["USERS"] = $utility->bumpUpPhotoListing($usersList);
            if (is_array($receiverDetails)) {
                
                $mailerServiceObj = new MailerService();
                $this->smarty = $mailerServiceObj->getMailerSmarty();
            
                $mailerLinks = $mailerServiceObj->getLinks();
                $this->smarty->assign('mailerLinks',$mailerLinks);

                $agentDetails = $utility->getAgentDetails($key);        
                $this->smarty->assign('mailerName',$agentDetails["EMAIL"]);
                
                $receiverDetails["AGENT_PHONE"] = $agentDetails["AGENT_PHONE"];
                $receiverDetails["AGENT_NAME"] = $agentDetails["AGENT_NAME"];
                        
                $subjectAndBody = $utility->getSubjectAndBody();
                $receiverDetails["body"]=$subjectAndBody["body"];
                $subject = $subjectAndBody["subject"];
                        
                $this->smarty->assign('data',$receiverDetails);
                $msg = $this->smarty->fetch(MAILER_COMMON_ENUM::getTemplate($this->mailerName).".tpl");

                //Sending mail and tracking sent status
                $flag = $mailerServiceObj->sendAndVerifyMail($receiverDetails["RECEIVER"]["EMAILID"],$msg,$subject,$this->mailerName,$key,$agentDetails["EMAIL"],$agentDetails["AGENT_NAME"],'','',$agentDetails["EMAIL"]);
                if ($flag == 'Y') {
                    // if mail is sent successfully, update the exclusive mail log table with the status
                    // of mail sent to pending interest column. Refer table
                    $billingExclusiveMailLog = new billing_EXCLUSIVE_MAIL_LOG();
                    $billingExclusiveMailLog->updatePendingInterestMailStatus($key, 'Y');
                    }
                }
        }
    }
}
