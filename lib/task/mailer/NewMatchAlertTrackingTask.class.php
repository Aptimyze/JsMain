<?php

/* This task is used to track new matchalert mailer
 *@author : Reshu Rajput
 *created on : 24 Jun 2014 
 */

class NewMatchAlertTrackingTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'NewMatchAlertTracking';
    $this->briefDescription = 'new matchalert mailer';
    $this->detailedDescription = <<<EOF
      The task send new matchalert mailer .
      Call it with:

      [php symfony mailer:NewMatchAlertTracking] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
        	sfContext::createInstance($this->configuration);

	$mailerServiceObj = new MailerService();
	$mailerServiceObj->newMatchesEmailsTracking();
  }

}
