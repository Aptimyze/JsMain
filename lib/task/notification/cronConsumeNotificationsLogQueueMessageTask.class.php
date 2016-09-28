<?
/*
This php script is run to create object of rabbitmq Notifications Consumer class and call 
the receiveMessage function to let the consumer receive notification messages on first server.
*/

class cronConsumeNotificationsLogQueueMessageTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronConsumeNotificationsQueueMessage
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronConsumeNotificationsLogQueueMessage';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeNotificationsLogQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony cron:cronConsumeNotificationsLogQueueMessage] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- creates JsNotificationsConsume class object and calls receiveMessage func to consume notification messages on FIRST_SERVER.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);

    // Notification Logging Consume
    $consumerLogObj=new JsNotificationsLogConsume('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $consumerLogObj->receiveMessage();

  }
}
?>
