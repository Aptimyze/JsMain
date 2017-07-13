<?php
/*
This php script is run to create object of rabbitmq Consumer class and call 
the receiveMessage function to let the consumer receive messages  on first server.
*/

class cronConsumeUpdateCriticalInfoQueueMessageTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronConsumeQueueMessage
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronConsumeUpdateCriticalInfoQueueMessage';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony cron:cronConsumeUpdateCriticalInfoQueueMessage] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- creates consumer class object and calls receiveMessage func to consume messages on FIRST_SERVER.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $consumerObj=new updateCriticalInfoConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $consumerObj->receiveMessage(); 
  }
}
?>
