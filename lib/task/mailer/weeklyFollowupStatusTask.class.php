<?php 

/**
* 
*/
class WeeklyFollowupStatusTask extends sfBaseTask {
	
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'weeklyFollowupStatusTask';
		$this->briefDescription = 'Weekly Followup Status - JS Exclusive';
		$this->detailedDescription = <<<EOF
		The [WeeklyFollowupStatusTask|INFO] task does things.
		Call it with:
		[php symfony mailer:weeklyFollowupStatus|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array()) {
		if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
     	$exclusiveMailer = new ExclusiveMatchMailer();
     	$date = date('Y-m-d');
     	$count = 0; // represents number of mails successfully sent
        $result = $exclusiveMailer->getClientAndAgentForToday();
     	$subject = "Weekly Followup status Mail $date";
	 	foreach ($result as $profileId => $memberDetails) {
                    $top8Mailer = new EmailSender(MailerGroup::TOP8, '1885');
                    $tpl = $top8Mailer->setProfileId($profileId);
                    $tpl->getSmarty()->assign("memberDetails",$memberDetails);
                    $tpl->getSmarty()->assign("senderEmail",$memberDetails["AGENT_DETAIL"]["EMAIL"]);
                    $tpl->getSmarty()->assign("senderName",$memberDetails["AGENT_DETAIL"]["NAME"]);
                    $tpl->setSubject($subject);
                    $jprofile = new JPROFILE();
                    $row = $jprofile->get($profileId, "PROFILEID", "EMAIL");
                    $bccList = "sandhya.singh@jeevansathi.com,anjali.singh@jeevansathi.com";
                    $mailSentStatus = $top8Mailer->send($row["EMAIL"],'',$memberDetails["AGENT_DETAIL"]["EMAIL"],$bccList);

                    if($mailSentStatus) {
                        $count++;
                    }
                }
        
        // the count mail is sent to the operation team 
        $this->sendmail($date, $count);
                
	}
        
        private function sendmail($date, $count) {
            
            $to = "sandhya.singh@jeevansathi.com, anjali.singh@jeevansathi.com, "
                . "anurag.tripathi@jeevansathi.com, piyush.joshi@jeevansathi.com";
            $body = "<br>Welcome Mails sent on $date = $count ";
            $subject = "Daily JS exclusive Weekly followup Mailer stats report ".$date;
            
            SendMail::send_email($to, $body, $subject);
        }
}

?>