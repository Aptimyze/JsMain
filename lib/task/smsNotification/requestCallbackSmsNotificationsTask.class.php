<?php
/* 
 * Cron sends SMS to people who are:
 * 1.  Never Paid
 * 2.  Have Received X = 4 or more acceptances
 * 3.  Logged-in in last 15 days
*/

class requestCallbackSmsNotifications extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'smsNotification';
    $this->name             = 'requestCallbackSmsNotifications';
    $this->briefDescription = 'Cron runs daily sending SMS to people who are:Never Paid,Have Received X = 4 or more acceptances and Logged-in in last 15 days';
    $this->detailedDescription = <<<EOF
      this task Cron runs daily sending SMS to people who are :Never Paid,Have Received X = 4 or more acceptances and Logged-in in last 15 days 
      Call it with:

      [php symfony smsNotification:requestCallbackSmsNotifications] 
EOF;

    $this->addOptions(array(
    new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
  }
  /*
   * function to execute cron
   * @param - $arguments- array of arguments, $options - array of options
   */
  protected function execute($arguments = array(), $options = array())
  {
    ini_set('max_execution_time',0);
    ini_set('memory_limit',-1);
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    include_once(JsConstants::$docRoot."/profile/connect_db.php");
    include_once(JsConstants::$docRoot."/classes/ScheduleSms.class.php");
    //include_once(JsConstants::$docRoot."/classes/Membership.class.php");
      
    $entry_dt = date("Y-m-d");
    $sms = new ScheduleSms;
    $sms->processData("REQUEST_CALLBACK",$entry_dt);
    
  }
}