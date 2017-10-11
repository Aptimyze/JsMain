<?php
/*
This php script is run to kill 'cronConsumeDiscountTrackingQueueMessageTask' cron.
*/

class cronKillOutboundConsumerTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronKillDiscountTrackingConsumer
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'Outbound';
    $this->name                = 'KillConsumer';
    $this->briefDescription    = 'kill cronKillOutboundConsumerTask cron';
    $this->detailedDescription = <<<EOF
     The [cronKillOutboundConsumer|INFO] is run to kill 'cronOutboundConsumerTask' cron:
     [php symfony Outbound:KillConsumer] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- to kill 'cronConsumeDiscountTrackingQueueMessageTask' cron.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
        sfContext::createInstance($this->configuration);
    $command = MessageQueues::OUTBOUND_STARTCOMMAND;
    exec("ps aux | grep \"".$command."\" | grep -v grep | awk '{ print $2 }'", $output);
    //echo "\n".$command."-";
    //print_r($output);
    if(!empty($output) && is_array($output))
    {
      foreach ($output as $key => $value) 
      {
        $count1 = shell_exec("ps -p ".$value." | wc -l") -1;
        if($count1 >0)
          exec("kill -9 ".$value);
      }
    }
    unset($output);
  }
}
?>
