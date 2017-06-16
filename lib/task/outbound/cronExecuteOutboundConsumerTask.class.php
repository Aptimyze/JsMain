<?php
/**
 * This php script reads no. of instances of rabbitmq ProfileCacheDeleteQueue from MessageQueues.enum.class.php to be run and executes that many instances of Outbound:ConsumeQueue (cronProfileCacheConsumerTask).
 * <code>
 *  ./symfony Outbound:cronExecuteOutboundConsumer
 * </code>
 * @author Kunal Verma
 * @created 15th June 2017
 */
class cronExecuteOutboundConsumerTask extends sfBaseTask
{
    /**
     *
     * Configuration details for Outbound:cronExecuteOutboundConsumer
     *
     * @access protected
     * @param none
     */
    protected function configure()
    {
        $this->namespace = 'Outbound';
        $this->name = 'cronExecuteOutboundConsumer';
        $this->briefDescription = 'reads no. of instances of rabbitmq OutboundQueue from MessageQueues.enum.class.php to be run and executes that many instances of cronProfileCacheConsumerTask.';
        $this->detailedDescription = <<<EOF
     The [ProfileCache:cronExecuteProfileCacheConsumer|INFO] reads no. of instances of rabbitmq OutboundQueue from MessageQueues.enum.class.php to be run and executes that many instances of cronOutboundConsumerTask:
     [php symfony cron:cronExecuteNotificationConsumer] 
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }

    /**
     * Function for executing cron. Executes cron and sets memory and disk alarms for First and Second Server as false
     * @param array $arguments
     * @param array $options
     * @return void
     */
    protected function execute($arguments = array(), $options = array())
    {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $instancesNum = MessageQueues::OUTBOUND_CONSUMER_COUNT;
        for ($i = 1; $i <= $instancesNum; $i++) {
            passthru(JsConstants::$php5path . " " . MessageQueues::OUTBOUND_STARTCOMMAND . " > /dev/null &");
        }
    }
}

?>