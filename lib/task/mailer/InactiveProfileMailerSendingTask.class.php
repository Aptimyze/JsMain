<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class InactiveProfileMailerSendingTask extends sfBaseTask
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
	    $this->name             = 'NEWJS_INACTIVE_PROFILES_SEND';
	    $this->briefDescription = 'send the mail to jeevansathi users for pending interests';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:NEWJS_INACTIVE_PROFILES_SEND totalScript currentScript] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		 $IncompleteMasterobj = new NEWJS_INACTIVE_PROFILES("newjs_master");
		 $IncompleteSlaveobj = new NEWJS_INACTIVE_PROFILES("newjs_slave");
		 $profilemail=$IncompleteMasterobj->SelectProfilesInactivated($arguments["totalScript"],$arguments["currentScript"]);
		 
		// print_r(MailerGroup::INCOMPLETE);die;
		 foreach ($profilemail as $key => $value) {
		 	//$value=45;
		 		//echo $value;die;
		 		if($value==90)
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_90, 1827);
				elseif($value==120)
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_120, 1828);
				elseif($value==145)
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_145, 1829);
			
				elseif($value==15)
					$email_sender = new EmailSender(MailerGroup::INACTIVE_15, 1822);
				elseif($value==30)
					$email_sender = new EmailSender(MailerGroup::INACTIVE_30, 1823);	
				elseif($value==45)
					$email_sender = new EmailSender(MailerGroup::INACTIVE_45, 1824);
				elseif($value==60)
					$email_sender = new EmailSender(MailerGroup::INACTIVE_60, 1825);
				elseif($value==75)
					$email_sender = new EmailSender(MailerGroup::INACTIVE_75, 1826);
				$emailTpl = $email_sender->setProfileId($key);
				$smartyObj = $emailTpl->getSmarty();
				$smartyObj->assign("interval",$value);
		
			$email_sender->send();
			$IncompleteMasterobj->UpdateStatusIncomplete($key);
			}
	}
}
