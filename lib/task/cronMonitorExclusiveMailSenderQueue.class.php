<?php
/*
This php script is run to create object of rabbitmq Consumer class and call
the receiveMessage function to let the consumer receive messages  on first server.
*/

class cronMonitorExclusiveMailSenderQueue extends sfBaseTask {
    /**
     *
     * Configuration details for cron:cronMonitorExclusiveMailSenderQueue
     *
     * @access protected
     * @param none
     */
    protected function configure() {
        $this->namespace = 'notification';
        $this->name = 'cronMonitorExclusiveMailSenderQueue';
        $this->briefDescription = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
        $this->detailedDescription = <<<EOF
     The [cronConsumeQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony notification:cronMonitorExclusiveMailSenderQueue] 
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
    protected function execute($arguments = array(), $options = array()){
        if (!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $proposalObj = new billing_ExclusiveProposalMailer();
        $count = $proposalObj->getUnderprocessIDsCount(date("Y-m-d"));
        $hour = date("H");
        if($count>4 && $hour > 1 && $hour < 11){
            $rmqObj = new RabbitmqHelper();
            $rmqObj->killConsumerForCommand(MessageQueues::EXCLUSIVE_MAIL_SENDING_QUEUE);
            $to = "ayush.chauhan@jeevansathi.com,manoj.rana@naukri.com";
            if(JsConstants::$whichMachine == "test"){
                $to = "ayush.chauhan@jeevansathi";
            }
            $msg = "ExclusiveMailSender Queue Consumer killed";
            $this->sendAlertMail($to, $msg, $msg);
        }
    }

    public function sendAlertMail($to,$msgBody,$subject){
        $from = "info@jeevansathi.com";
        $from_name = "Jeevansathi Info";
        SendMail::send_email($to,$msgBody, $subject, $from,"","","","","","","1","",$from_name);
    }
}
?>