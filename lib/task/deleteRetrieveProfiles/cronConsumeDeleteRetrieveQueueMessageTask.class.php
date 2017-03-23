<?
/*
This php script is run to create object of rabbitmq Notifications Consumer class and call 
the receiveMessage function to let the consumer receive notification messages on first server.
*/

class cronConsumeDeleteRetrieveQueueMessageTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronConsumeDeleteRetrieveQueueMessage
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronConsumeDeleteRetrieveQueueMessage';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeDeleteRetrieveQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony cron:cronConsumeDeleteRetrieveQueueMessage] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- creates deleteRetrieveConsumer class object and calls receiveMessage func to consume notification messages on FIRST_SERVER.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);

    if(0 === CommonUtility::runFeatureAtNonPeak() && JsConstants::$whichMachine == 'prod')
        	successfullDie();

    $delRetrieveConsumerObj=new deleteRetrieveConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $delRetrieveConsumerObj->receiveMessage(); 
  }
}
?>
