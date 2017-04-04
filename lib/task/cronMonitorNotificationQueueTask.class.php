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

	$notificationArr =array('EOI'=>'1000','ACCEPTANCE'=>'1000','PHOTO_REQUEST'=>'1000','MESSAGE_RECEIVED'=>'1000','EOI_REMINDER'=>'1000','BUY_MEMB'=>'1000','PROFILE_VISITOR'=>'1000','PHOTO_UPLOAD'=>'1000','INCOMPLETE_SCREENING'=>'1000','CHAT_MSG'=>'1000','CHAT_EOI_MSG'=>'1000');

    	$JsMemcacheObj =JsMemcache::getInstance();
	$keyParam ='APP_INST#';
    	$mqParam ='#MQ';

	foreach($notificationArr as $notificationKey=>$thresholdVal){
		$keyWithourMq   =$keyParam.$notificationKey;
		$keyWithMq      =$keyWithourMq.$mqParam;

		$valWithourMq 	=$JsMemcacheObj->get($keyWithourMq);
		$valWithMq 	=$JsMemcacheObj->get($keyWithMq);

		$netCount =abs($valWithourMq-$valWithMq);
		if($netCount>$thresholdVal){
			$this->consumerHandling();	
		}
		unset($thresholdVal);	
	}
  }
	public function consumerHandling()
	{ 
     		$rmqObj = new RabbitmqHelper();
        	$rmqObj->killConsumerForCommand(MessageQueues::CRONCONSUMER_STARTCOMMAND);
        	$to = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,ankita.g@jeevansathi.com";
        	$subject = $msgBody = "Instant Notification Queue Consumer killed";
        	SendMail::send_email($to, $msgBody, $subject);
        	$this->sendAlertSMS();
	}
  	public function sendAlertSMS(){
  	  	$mobileNumberArr = array("vibhor"=>"9868673709","manoj"=>"9999216910","nitish"=>"8989931104","ankita"=>"9650879575");
  	  	foreach($mobileNumberArr as $k=>$v){
  	  	    $this->sms($v);
  	  	}
  	}
  	public function sms($mobile){
        	$t = time();
        	$message        = "Mysql Error Count have reached App InstantNotificationConsumer killed at $t";
        	$from           = "JSSRVR";
        	$profileid      = "144111";
        	$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        	$date = date("Y-m-d h");
    	}
}
?>
