<?php
/********************************************************************************
 * Task to record percentage of sent Emails of various service providers
 *
 * @Author		Akash Kumar
 * @Purpose     This TASK is used to analyse email rates for spam control
 * @execution   Terminal - php symfony spamcontrol:emailRate
 * @version     0.1
 ********************************************************************************/
class matchalertEmailRateTask extends sfBaseTask
{
  protected function configure()
  {
	$this->namespace        = 'mailer';
    $this->name             = 'matchalertEmailRate';
    $this->briefDescription = 'Check for rate of emails sent to different serive providers';
    $this->detailedDescription = <<<EOF
The [emailRate|INFO] task takes the number of email sent to different domains and calculate open rate to timely alert developer.
Call it with:

  [php symfony mailer:matchalertEmailRate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  { 
	  $emailObject=new SpamControl();           // Object of library class SpamControl to check and INSERT or UPDATE Database for the number of emails sent by different service providers 
    $this->emailer=$emailObject->emailRate();
  }
}
