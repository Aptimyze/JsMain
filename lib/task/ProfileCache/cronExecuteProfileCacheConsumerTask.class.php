<?php
/**
 * This php script reads no. of instances of rabbitmq ProfileCacheDeleteQueue from MessageQueues.enum.class.php to be run and executes that many instances of profileCache:ConsumeQueue (cronProfileCacheConsumerTask).
 * <code>
 *  ./symfony ProfileCache:cronExecuteProfileCacheConsumer
 * </code>
 * @author Kunal Verma
 * @created 9th Aug 2016
 */
class cronExecuteProfileCacheConsumerTask extends sfBaseTask
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
        $this->namespace = 'ProfileCache';
        $this->name = 'cronExecuteProfileCacheConsumer';
        $this->briefDescription = 'reads no. of instances of rabbitmq ProfileCacheDeleteQueue from MessageQueues.enum.class.php to be run and executes that many instances of cronProfileCacheConsumerTask.';
        $this->detailedDescription = <<<EOF
     The [ProfileCache:cronExecuteProfileCacheConsumer|INFO] reads no. of instances of rabbitmq ProfileCacheDeleteQueue from MessageQueues.enum.class.php to be run and executes that many instances of cronProfileCacheConsumerTask:
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

        $instancesNum = MessageQueues::PROFILE_CACHE_CONSUMER_COUNT;
        for ($i = 1; $i <= $instancesNum; $i++) {
            passthru(JsConstants::$php5path . " " . MessageQueues::PROFILE_CACHE_STARTCOMMAND . " > /dev/null &");
        }
    }
}

?>