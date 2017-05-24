<?php
/*
This php script reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cron:cronConsumeQueueMessage.
*/

class cronExecuteProductMetricLoggingTask extends sfBaseTask
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
    $this->name                = 'cronExecuteProductMetricLogging';
    $this->briefDescription    = 'reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeQueueMessage.';
    $this->detailedDescription = <<<EOF
     The [cronExecuteSingleConsumer|INFO] reads no. of instances of rabbitmq consumer from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeQueueMessage:
     [php symfony cron:cronExecuteProductMetricLogging] 
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
    $consumerObj=new ProductMetricConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $consumerObj->receiveMessage(); 
	}
}
?>
