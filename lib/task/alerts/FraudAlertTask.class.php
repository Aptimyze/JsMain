<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the fraud alert mail to the users
 */
 class FraudAlertTask extends sfBaseTask
{
	private $profilemail=array();
	private $OneTimeInterval = '180';
	private $Interval = '1';
	protected function configure()
  	{
                $this->addArguments(array(
                	new sfCommandArgument('totalScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
                    new sfCommandArgument('oneTime', sfCommandArgument::REQUIRED, 'My argument')
		));
                
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'alert';
	    $this->name             = 'NEWJS_LogFraudAlert';
	    $this->briefDescription = 'send the mail to alert jeevansathi users for fraud cases';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony alert:NEWJS_LogFraudAlert totalScript currentScript oneTime] 
	  where oneTime is 1 for first time of cron otherwise 0 for regular basis
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
				if(!sfContext::hasInstance())
	                sfContext::createInstance($this->configuration);
	            $fraudAlertMasterobj = new NEWJS_LogFraudAlert("newjs_master");
	            $fraudAlertSlaveobj = new NEWJS_LogFraudAlert("newjs_slave");
	            if($arguments["oneTime"]){
	           		$profilemail=$fraudAlertSlaveobj->ProfilesActivated($arguments["totalScript"],$arguments["currentScript"],$this->OneTimeInterval);
	           	}
	           		
	            else{  	
	            	$profilemail = $fraudAlertSlaveobj->ProfileNewlyRegister($this->Interval);
	            }
	         
	            foreach ($profilemail as $key => $value) {
	            	$email_sender = new EmailSender(MailerGroup::FRAUD_ALERT, 1788);
					$emailTpl = $email_sender->setProfileId($key);
					$email_sender->send();
					$status=$fraudAlertMasterobj->InsertStatusAlert($key);
				}
	}

}
