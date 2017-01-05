<?php
/*
This php script reads no. of instances of rabbitmq JsNotificationsLogConsume from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeNotificationsQueueMessage.
*/

class cronExecuteNotificationLogConsumerTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronExecuteNotificationConsumer
   * 
   * @access protected
   * @param none
  */
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronExecuteNotificationLogConsumerTask';
    $this->briefDescription    = 'reads no. of instances of rabbitmq JsNotificationsConsume from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeNotificationsQueueMessage.';
    $this->detailedDescription = <<<EOF
     The [cronexecuteConsumer|INFO] reads no. of instances of rabbitmq JsNotificationsLogConsume from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeNotificationsLogQueueMessage:
     [php symfony cron:cronExecuteNotificationLogConsumer] 
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

    $loginstancesNum=MessageQueues::NOTIFICATION_LOG_CONSUMER_COUNT;
    for($i=1;$i<=$loginstancesNum;$i++){
      passthru(JsConstants::$php5path." ".MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND." > /dev/null &");
    }

  }


}
?>
