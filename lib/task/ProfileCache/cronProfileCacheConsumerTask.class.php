<?php
/**
 * Cron job for consuming queue message of ProfileCacheDeleteQueue, this cron job will run continuosly
 * <code>
 * To execute : $ symfony ProfileCache:ConsumeQueue
 * example  $ symfony ProfileCache:ConsumeQueue
 * </code>
 * @author Kunal Verma
 * @created 9th Aug 2016
 */
class cronProfileCacheConsumerTask extends sfBaseTask
{
    /**
     *
     * Configuration details for ProfileCache:ConsumeQueue
     *
     * @access protected
     * @param none
     */
    protected function configure()
    {
        $this->namespace = 'ProfileCache';
        $this->name = 'ConsumeQueue';
        $this->briefDescription = 'Cron Job for consuming queue message of ProfileCacheDeleteQueue so this cron initialises instance of rabbitmq consumer class to retrieve messages on first server';
        $this->detailedDescription = <<<EOF
     The [ProfileCache:ConsumeQueue|INFO] calls receiveMessage function of ProfileCacheConsumer class through its instance to retrieve messages on first server:
     [php symfony ProfileCache:ConsumeQueue] 
EOF;
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
        ));
    }

    /**
     *
     * Function for executing cron- creates consumer class object and calls receiveMessage func to consume messages on FIRST_SERVER.
     *
     * @access protected
     * @param $arguments ,$options
     */
    protected function execute($arguments = array(), $options = array())
    {
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $consumerObj = new ProfileCacheConsumer('FIRST_SERVER', 0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $consumerObj->receiveMessage();
    }
}

?>
