<?php
/*
 * Author: Prashant Pal
 * @param oneTime is 1 when cron is for the first time for all active users & 0 for daily basis new register users
 * @param $profilemail are users to whom mail has to be send
 * @param $Interval time interval for daily basis for new users 
 * @param $OneTimeInerval is time period for the active users in that particular time period
 * This task send the pendingt mail to the users
 */
 class emailVerificationLogTask extends sfBaseTask
 {

	protected function configure()
  	{
  		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'mailer';
	    $this->name             = 'VER_MAILER_TABLE_LOG';
	    $this->briefDescription = 'truncates the table ';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony mailer:VER_MAILER_TABLE_LOG] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		
		$cronDocRoot = JsConstants::$cronDocRoot;
        $php5 = JsConstants::$php5path;

        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring EMAIL_VER_MAILER");
		(new MAIL_EMAIL_VER_MAILER('newjs_master'))->EmptyMailer();

		passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring EMAIL_VER_MAILER#INSERT");	
		$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
		$instanceID = $countObj->getID('EMAIL_VER_MAILER');
		$memObject=JsMemcache::getInstance();
		$memObject->set('emailVerInstanceId',$instanceID);

		
	}


}




