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
		 //Mailer dashboard
		 $cronDocRoot = JsConstants::$cronDocRoot;
	         $php5 = JsConstants::$php5path;
            	 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_90#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_120#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_145#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_15#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_30#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_45#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_60#INSERT");
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_75#INSERT");
                 $countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
		 
		// print_r(MailerGroup::INCOMPLETE);die;
		 foreach ($profilemail as $key => $value) {
		 	//$value=45;
		 		//echo $value;die;
		 		if($value==85)
				{
					$instanceID = $countObj->getID('INCOMPLETE_90');
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_90, 1827);
				}
				elseif($value==120)
				{
					$instanceID = $countObj->getID('INCOMPLETE_120');
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_120, 1828);
				}
				elseif($value==145)
				{
					$instanceID = $countObj->getID('INCOMPLETE_145');
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_145, 1829);
				}
				elseif($value==15)
				{
					$instanceID = $countObj->getID('INCOMPLETE_15');
					$email_sender = new EmailSender(MailerGroup::INACTIVE_15, 1822);
				}
				elseif($value==30)
				{
					$instanceID = $countObj->getID('INCOMPLETE_30');	
					$email_sender = new EmailSender(MailerGroup::INACTIVE_30, 1823);	
				}
				elseif($value==45)
				{
					$instanceID = $countObj->getID('INCOMPLETE_45');
					$email_sender = new EmailSender(MailerGroup::INACTIVE_45, 1824);
				}
				elseif($value==60)
				{
					$instanceID = $countObj->getID('INCOMPLETE_60');
					$email_sender = new EmailSender(MailerGroup::INACTIVE_60, 1825);
				}
				elseif($value==75)
				{
					$instanceID = $countObj->getID('INCOMPLETE_75');
					$email_sender = new EmailSender(MailerGroup::INCOMPLETE_120, 1828);
				}
				
				$emailTpl = $email_sender->setProfileId($key);
				$smartyObj = $emailTpl->getSmarty();
				$smartyObj->assign("interval",$value);
				$smartyObj->assign("instanceID",$instanceID);
		
			$email_sender->send();
			$status = $email_sender->getEmailDeliveryStatus();
			ProfileCacheLib::getInstance()->__destruct();

			$IncompleteMasterobj->UpdateStatusIncomplete($key,$status);
			}
			/** code for daily count monitoring**/
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_15");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_30");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_45");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_60");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_75");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_90");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_120");
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring INCOMPLETE_145");
			/**code ends*/
	}
}
