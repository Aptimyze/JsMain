<?php
/*
 * Author: Ankit Shukla
 * @param $profilemail are users to whom mail has to be send
 * This task send the fraud alert mail to the female users fortnightly
 */
//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit','256M');
//for preventing timeout to maximum possible
 class FraudAlertFemaleTask extends sfBaseTask
{
  private $profilemail=array();
  const MAIL_ID = "1801";
  protected function configure()
  {

    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
    ));

    $this->namespace        = 'alert';
    $this->name             = 'FraudAlertFemale';
    $this->briefDescription = 'send the mail to alert jeevansathi female users for fraud cases';
    $this->detailedDescription = <<<EOF
    Call it with:
    [php symfony alert:FraudAlertFemale] 
EOF;
  }
/*
 * function to fetch profiles and send email
 */
  protected function execute($arguments = array(), $options = array())
  {
    if(!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $fraudAlertFemaleSlaveobj = new NEWJS_SEARCH_FEMALE("newjs_slave");
    //fetch profiles to send email
    $profilemail = $fraudAlertFemaleSlaveobj->getProfilesAndEmail();
    foreach ($profilemail as $key => $value) {
      $email_sender = new EmailSender(MailerGroup::FRAUD_ALERT, self::MAIL_ID);
      $emailTpl = $email_sender->setProfileId($key);
      //send email
      $email_sender->send($value);
    }
  }

}
