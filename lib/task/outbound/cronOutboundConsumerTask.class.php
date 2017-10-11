<?php
/**
 * Cron job for consuming queue message of OutboundQueue, this cron job will run continuosly
 * <code>
 * To execute : $ symfony Outbound:ConsumeQueue
 * example  $ symfony Outbound:ConsumeQueue
 * </code>
 * @author Kunal Verma
 * @created 16th June 2017
 */
class cronOutboundConsumerTask extends sfBaseTask
{
    /**
     *
     * Configuration details for Outbound:ConsumeQueue
     *
     * @access protected
     * @param none
     */
    protected function configure()
    {
        $this->namespace = 'Outbound';
        $this->name = 'ConsumeQueue';
        $this->briefDescription = 'Cron Job for consuming queue message of OutboundQueue so this cron initialises instance of rabbitmq consumer class to retrieve messages on first server';
        $this->detailedDescription = <<<EOF
     The [Outbound:ConsumeQueue|INFO] calls receiveMessage function of OutboundQueue class through its instance to retrieve messages on first server:
     [php symfony Outbound:ConsumeQueue] 
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
        $consumerObj = new OutboundEventConsumer('FIRST_SERVER', 0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $consumerObj->receiveMessage();
    }
}

?>
