<?php
/*
This php script reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeQueueMessage.
*/

class cronExecuteUpdateMatchAlertsLastSeenConsumerTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronExecuteSingleConsumer
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronExecuteUpdateMatchAlertsLastSeenConsumerTask';
    $this->briefDescription    = 'reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeQueueMessage.';
    $this->detailedDescription = <<<EOF
     The [cronExecuteSingleConsumer|INFO] reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeQueueMessage:
     [php symfony cron:cronExecuteUpdateMatchAlertsLastSeenConsumerTask] 
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
    JsMemcache::getInstance()->set("mqMemoryAlarmFIRST_SERVER",false);
    JsMemcache::getInstance()->set("mqMemoryAlarmSECOND_SERVER",false);
    JsMemcache::getInstance()->set("mqDiskAlarmFIRST_SERVER",false);
    JsMemcache::getInstance()->set("mqDiskAlarmSECOND_SERVER",false);
    $instancesNum=MessageQueues::MATCHALERT_LAST_SEEN_CONSUMER_COUNT;
    for($i=1;$i<=$instancesNum;$i++)
    {
      passthru(JsConstants::$php5path." ".MessageQueues::CRONMATCHALERTSLASTSEEN_STARTCOMMAND." > /dev/null &");
    }
	}
}
?>
