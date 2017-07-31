<?php
/*
This php script is run to create object of rabbitmq ConsumeDiscountTrackingConsumer class and call the receiveMessage function to let the consumer receive messages on first server.
*/

class cronConsumeExclusiveWelcomeEmailTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronConsumeDiscountTrackingQueueMessage
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronConsumeExclusiveWelcomeEmailTask';
    $this->briefDescription    = 'Initialises instance of rabbitmq cronConsumeExclusiveWelcomeEmailTask class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeExclusiveWelcomeEmailTask|INFO] calls receiveMessage function of cronConsumeExclusiveWelcomeEmailTask class through its instance to retrieve messages on first server:
     [php symfony cron:cronConsumeExclusiveWelcomeEmailTask] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- creates DiscountTrackingConsumer class object and calls receiveMessage func to consume notification messages on FIRST_SERVER.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
        sfContext::createInstance($this->configuration);

    $consumerObj=new WelcomeMailConsumer('FIRST_SERVER',0);
    $consumerObj->receiveMessage();
  }
}
?>
