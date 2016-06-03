<?
/*
This php script runs to check whether node server for crm notifications is running else restart node server if not.
*/

class cronNodeServerRecoveryForCRMNotificationsTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for CRM:cronNodeServerRecoveryForCRMNotifications
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'CRM';
    $this->name                = 'cronNodeServerRecoveryForCRMNotifications';
    $this->briefDescription    = 'runs to check whether node server for crm notifications is running else restart node server if not.';
    $this->detailedDescription = <<<EOF
     The [cronRunNodeServerForCRMNotifications|INFO] does things.
     [php symfony CRM:cronNodeServerRecoveryForCRMNotifications] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function checks whether node server for crm notifications is running else restart node server if not.
   *
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
   
    $activeNum=shell_exec("ps ax | grep "."'/usr/local/node-v0.10.25/bin/node /var/www/html/nodeServer/crmAgentNotifyServer.js'"." | wc -l") -2; //reason for -2:additional count for cron and tile line subtracted.    
    
    if($activeNum == 0)
    {
      $message="Node server for crm notifications is not running,hence restarting it again...";
     CRMAlertManager::sendMailAlert($message,"AgentNotifications");  
      //restart node server again
      passthru("/usr/local/node-v0.10.25/bin/node /var/www/html/nodeServer/crmAgentNotifyServer.js > /dev/null &");     
    }
	}
}
?>
