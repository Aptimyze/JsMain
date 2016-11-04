<?php
/*
This php script reads no. of instances of rabbitmq JsNotificationsConsume from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeNotificationsQueueMessage.
*/

class cronExecuteNotificationConsumerTask extends sfBaseTask
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
    $this->name                = 'cronExecuteNotificationConsumer';
    $this->briefDescription    = 'reads no. of instances of rabbitmq JsNotificationsConsume from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeNotificationsQueueMessage.';
    $this->detailedDescription = <<<EOF
     The [cronexecuteConsumer|INFO] reads no. of instances of rabbitmq JsNotificationsConsume from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeNotificationsQueueMessage:
     [php symfony cron:cronExecuteNotificationConsumer] 
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
    
    $instancesNum=MessageQueues::NOTIFICATIONCONSUMERCOUNT;
    for($i=1;$i<=$instancesNum;$i++)
    {
      passthru(JsConstants::$php5path." ".MessageQueues::CRONNOTIFICATION_CONSUMER_STARTCOMMAND." > /dev/null &");
    }
  }


}
?>
