<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MailerCountCalculationTask
 *
 * @author tushar
 */
class MailerCountCalculationTask extends sfBaseTask {

    protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'MailerCountCalculationTask';
		$this->briefDescription = 'Mailer Count for all mails sent - JS Exclusive';
		$this->detailedDescription = <<<EOF
		The [PendingInterestMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:MailerCountCalculationTask|INFO]
EOF;
	}
    
    // @override
    protected function execute($arguments = array(), $options = array()) {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
    
        $emailStageType = 'C'; // This value represents all those entry to which the mail has been sent
        $proposalMailerSentStatus = 'Y'; // This value represents all those entry to which the mail has been sent
        $matchMailerSentStatus = 'Y';
        $pendingInterestMailerSentStatus = 'Y';
        
        $mailerCheckDate = date("Y-m-d", strtotime("- 1 day"));

        // calculating the welcome mail count
        $exclusiveServicing = new billing_EXCLUSIVE_SERVICING();
        // welcomeMailCount represents the count for the welcome mail on mailerCheckDate
        $welcomeMailCount = $exclusiveServicing->getWelcomeMailCount($mailerCheckDate, $emailStageType);
        
        // calculating the proposal mail count
        $exclusiveProposalMailer = new billing_ExclusiveProposalMailer();
        $proposalMailCount = $exclusiveProposalMailer->getUnderprocessIDsCount($mailerCheckDate, $proposalMailerSentStatus);

        
        $exclusiveMailLog = new billing_EXCLUSIVE_MAIL_LOG();
        $matchMailCount = $exclusiveMailLog->getMatchMailCount($mailerCheckDate, $matchMailerSentStatus);

        $pendingInterestMailerCount = $exclusiveMailLog->getPendingInterestMailsCount($mailerCheckDate, $pendingInterestMailerSentStatus);
        
        $this->sendmail($mailerCheckDate, $welcomeMailCount, $proposalMailCount, 
                $matchMailCount, $pendingInterestMailerCount);
    }
    
    private function sendmail($mailerCheckDate, $welcomeMailCount, $proposalMailCount, 
                $matchMailCount, $pendingInterestMailerCount) {
        
        $subject = "Daily JS exclusive Mailer stats report ".$mailerCheckDate;
        $body = "<br>Welcome Mails sent on $mailerCheckDate = $welcomeMailCount "
                . "<br>Proposal mails sent on  $mailerCheckDate = $proposalMailCount "
                . "<br>Matchmails sent on $mailerCheckDate = $matchMailCount "
                . "<br>Awaiting responses mail sent on $mailerCheckDate = $pendingInterestMailerCount";
        
        $to = "sandhya.singh@jeevansathi.com, anjali.singh@jeevansathi.com, "
                . "anurag.tripathi@jeevansathi.com, piyush.joshi@jeevansathi.com, "
                . " tushar.gandhi@jeevansathi.com, manoj.rana@naukri.com";
        
        SendMail::send_email($to, $body, $subject);
    }
}
