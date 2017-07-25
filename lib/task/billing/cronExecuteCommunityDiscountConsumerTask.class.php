<?php
/*
This php script reads no. of instances of rabbitmq cronExecuteCommunityDiscountConsumerTask from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeCommunityDiscountConsumerTask.
*/

class cronExecuteCommunityDiscountConsumerTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronExecuteCommunityDiscountConsumerTask
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronExecuteCommunityDiscountConsumerTask';
    $this->briefDescription    = 'reads no. of instances of rabbitmq CommunityDiscountConsumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeCommunityDiscountConsumerTask.';
    $this->detailedDescription = <<<EOF
     The [cronexecuteConsumer|INFO] reads no. of instances of rabbitmq CommunityDiscountConsumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeCommunityDiscountConsumerTask:
     [php symfony cron:cronExecuteCommunityDiscountConsumerTask] 
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
    
    $instancesNum=MessageQueues::COMMUNITY_DISCOUNT_CONSUMER_COUNT;
    
    for($i=1;$i<=$instancesNum;$i++)
    {
      passthru(JsConstants::$php5path." ".MessageQueues::CRON_CONSUME_COMMUNITY_DISCOUNT_STARTCOMMAND." > /dev/null &");
    }
  }


}
?>
