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
        $top8Mailer->send($row["EMAIL"],'',$memberDetails["AGENT_DETAIL"]["EMAIL"],$bccList);
	    }
	}
}

?>