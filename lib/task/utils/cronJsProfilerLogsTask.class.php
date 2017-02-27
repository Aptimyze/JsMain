<?php
/**
 * Cron job for consuming queue message of JsProfilerQueue, this cron job will run continuosly
 * <code>
 * To execute : $ symfony Utils:Profiler
 * example  $ symfony Utils:Profiler
 * </code>
 * @author Kunal Verma
 * @created 22nd Feb 2017
 */
class cronJsProfilerLogsTask extends sfBaseTask
{
    /**
     *
     * Configuration details for Utils:Profiler
     *
     * @access protected
     * @param none
     */
    protected function configure()
    {
        $this->namespace = 'Utils';
        $this->name = 'Profiler';
        $this->briefDescription = 'Cron Job for consuming queue message of JsProfilerQueue so this cron initialises instance of rabbitmq consumer class to retrieve messages on first server';
        $this->detailedDescription = <<<EOF
     The [ProfileCache:ConsumeQueue|INFO] calls receiveMessage function of JsProfilerQueue class through its instance to retrieve messages on first server:
     [php symfony Utils:Profiler] 
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
        $consumerObj = new JsProfilerLogsConsumer('FIRST_SERVER', 0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
        $consumerObj->receiveMessage();
    }
}

?>
