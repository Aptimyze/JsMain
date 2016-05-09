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
		 $profilemail=$IncompleteSlaveobj->SelectProfilesInactivated($arguments["totalScript"],$arguments["currentScript"]);
		// print_r(MailerGroup::INCOMPLETE);die;
		 foreach ($profilemail as $key => $value) {
		 	//$value=45;
		 	if($value>=90)
		 	{
		 		//echo $value;die;
		        $email_sender = new EmailSender(MailerGroup::INCOMPLETE, 1819);
				$emailTpl = $email_sender->setProfileId($key);
			}
			else
			{
				$email_sender = new EmailSender(MailerGroup::INACTIVE_2, 1822);
				$emailTpl = $email_sender->setProfileId($key);
				$smartyObj = $emailTpl->getSmarty();
				$smartyObj->assign("interval",$value);
			}
			$email_sender->send();
			$IncompleteMasterobj->UpdateStatusIncomplete($key);
			}
	}
}