<?
/*
This php script is run to create object of rabbitmq Consumer class and call 
the receiveMessage function to let the consumer receive messages  on first server.
*/

class cronMonitorNotificationQueueTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronMonitorNotificationQueueTask
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'notification';
    $this->name                = 'cronMonitorNotificationQueue';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony notification:cronMonitorNotificationQueue] 
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
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    	include(JsConstants::$docRoot."/commonFiles/sms_inc.php");

	$notificationArr =array('EOI'=>'10000','ACCEPTANCE'=>'1000','MESSAGE_RECEIVED'=>'2000','PHOTO_REQUEST'=>'2000','EOI_REMINDER'=>'2000','BUY_MEMB'=>'500','PROFILE_VISITOR'=>'5000','PHOTO_UPLOAD'=>'2000','INCOMPLETE_SCREENING'=>'500','CHAT_MSG'=>'1000','CHAT_EOI_MSG'=>'1000');

    	$JsMemcacheObj =JsMemcache::getInstance();
	$keyParam ='APP_INST#';
    	$mqParam ='#MQ';

	foreach($notificationArr as $notificationKey=>$thresholdVal){
		$keyWithourMq   =$keyParam.$notificationKey;
		$keyWithMq      =$keyWithourMq.$mqParam;

		$valWithourMq 	=$JsMemcacheObj->get($keyWithourMq,'','',0);
		$valWithMq 	=$JsMemcacheObj->get($keyWithMq,'','',0);

		// Handling for ACCEPTANCE
		if($notificationKey=='ACCEPTANCE'){
			if($valWithMq<100)		
				$this->consumerHandling($notificationKey,$valWithMq);
		}
		// end

		$netcount =abs($valwithourmq-$valwithmq);
		if($netCount>$thresholdVal){
			$this->consumerHandling($notificationKey,$netCount);	
		}
		unset($valWithourMq);
		unset($valWithMq);
		unset($netcount);	
	}
  }
	public function consumerHandling($notificationKey,$netCount=0)
	{ 
     		$rmqObj = new RabbitmqHelper();
        	$rmqObj->killConsumerForCommand(MessageQueues::CRONCONSUMER_STARTCOMMAND);
        	$to = "manoj.rana@naukri.com";
        	$msgBody = "[Instant] Notification Queue(SmsGcmQueue) Consumer(cronConsumeQueueMessage) killed";
		$subject = "[Instant] Notification Key: $notificationKey \n Difference Count: $netCount";
        	SendMail::send_email($to, $msgBody, $subject);
        	$this->sendAlertSMS();
		die();
	}
  	public function sendAlertSMS(){
  	  	$mobileNumberArr = array("manoj"=>"9999216910");
  	  	foreach($mobileNumberArr as $k=>$v){
  	  	    $this->sms($v);
  	  	}
  	}
  	public function sms($mobile){
        	$t = time();
        	$message        = "Mysql Error Count have reached InstantNotificationConsumer for Queue-SmsGcmQueue killed at $t";
        	$from           = "JSSRVR";
        	$profileid      = "144111";
        	$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        	$date = date("Y-m-d h");
    	}
}
?>
