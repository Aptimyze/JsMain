<?php
/*
This php script reads no. of instances of rabbitmq DiscountLoggingConsumer from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeDiscountTrackingQueueMessage.
*/

class cronExecuteDiscountTrackingConsumerTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronExecuteDiscountTrackingConsumer
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronExecuteDiscountTrackingConsumer';
    $this->briefDescription    = 'reads no. of instances of rabbitmq DiscountLoggingConsumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeDiscountTrackingQueueMessage.';
    $this->detailedDescription = <<<EOF
     The [cronexecuteConsumer|INFO] reads no. of instances of rabbitmq DiscountLoggingConsumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeDiscountTrackingQueueMessage:
     [php symfony cron:cronExecuteDiscountTrackingConsumer] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron. Executes cron and sets memory and disk alarms for First and Second Server as false
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
    sfContext::createInstance($this->configuration);
    
    $instancesNum=MessageQueues::DISCOUNT_TRACKING_CONSUMER_COUNT;
    
    for($i=1;$i<=$instancesNum;$i++)
    {
      passthru(JsConstants::$php5path." ".MessageQueues::CRON_DISCOUNT_TRACKING_CONSUMER_STARTCOMMAND." > /dev/null &");
    }
  }


}
?>
