<?
/*
This php script runs node server to listen to agent_notification messages in background.
*/

class cronRunNodeServerForCRMNotificationsTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for CRM:cronRunNodeServerForCRMNotifications
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'CRM';
    $this->name                = 'cronRunNodeServerForCRMNotifications';
    $this->briefDescription    = 'runs node server to listen to agent_notification messages in background';
    $this->detailedDescription = <<<EOF
     The [cronRunNodeServerForCRMNotifications|INFO] runs node server to listen to agent_notification messages in background:
     [php symfony CRM:cronRunNodeServerForCRMNotifications] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function to run node server to listen to agent_notification messages in background
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    //run node server
    passthru("/usr/local/node-v0.10.25/bin/node /var/www/html/nodeServer/crmAgentNotifyServer.js > /dev/null &");
	}
}
?>