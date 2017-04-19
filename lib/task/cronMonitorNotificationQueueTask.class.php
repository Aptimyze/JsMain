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

	$this->instantNotificationArr 	=NotificationEnums::$monitorInstantKeyArr;
	$this->deliveryKeyArr 		=array('DELIVERY_TRACKING_API');
	$this->scheduledKeyArr		=NotificationEnums::$monitorScheduledKeyArr;	
	$notificationArr		=array_merge($this->instantNotificationArr,$this->deliveryKeyArr);	

    	$JsMemcacheObj 		=JsMemcache::getInstance();
	$instantKeyParam 	='APP_INSTANT#';
	$scheduledKeyParam	='APP_NOTIFICATION#';	
	$mqParam                ='#MQ';
	$acceptanceValCheck	=1000;

	// Notification handling with Message Queue
	foreach($notificationArr as $key=>$notificationKey){

		if(in_array("$notificationKey", $this->instantNotificationArr))
			$keyWithourMq =$instantKeyParam.$notificationKey;
		elseif(in_array("$notificationKey", $this->deliveryKeyArr))	
			$keyWithourMq =$notificationKey;
	
		// Key with rabbitMq
		$keyWithMq      =$keyWithourMq.$mqParam;

		$valWithoutMq 	=$JsMemcacheObj->get($keyWithourMq,'','',0);
		$valWithMq 	=$JsMemcacheObj->get($keyWithMq,'','',0);

		$maxVal		=max($valWithoutMq,$valWithMq);
		$minVal		=min($valWithoutMq,$valWithMq);
		$netCount 	=$maxVal-$minVal;
		$diffPercent	=abs(($netCount/$maxVal)*100);

		// Handling for ACCEPTANCE
		if($notificationKey=='ACCEPTANCE'){
			if($valWithMq<$acceptanceValCheck)		
				$this->consumerHandling($notificationKey,$valWithMq);
		}
		// end

		// Others
		if($diffPercent>20){
			$this->consumerHandling($notificationKey,$diffPercent);	
		}
		unset($valWithoutMq);
		unset($valWithMq);
		unset($netCount);	
	}
	// Ends

	// Scheduled App Notification Handling Without Message Queue
	$curHour =date("H");
        foreach($this->scheduledKeyArr as $notificationKey=>$time){

                $keyWithourMq =$scheduledKeyParam.$notificationKey;
		$keyToCheck =$keyWithourMq.$mqParam;
		list($start,$end) =explode("-",$time);

		if($curHour>=$start && $curHour<=$end){
	                $valWithoutMq   =$JsMemcacheObj->get($keyWithourMq,'','',0);
			$valToCheck	=$JsMemcacheObj->get($keyToCheck,'','',0);

			/*		
			echo $keyWithourMq."=".$keyToCheck."-----------";
			echo $valWithoutMq."=".$valToCheck."\n";
			*/	

			if($valWithoutMq>$valToCheck){
				$JsMemcacheObj->set($keyToCheck,$valWithoutMq,'','','X');
			}
			else{
				$this->consumerHandling($notificationKey);	
			}
		}
	}	
	// Ends
  }

	public function consumerHandling($notificationKey,$diffPercent=0)
	{ 
     		$rmqObj = new RabbitmqHelper();
                if(in_array("$notificationKey", $this->instantNotificationArr)){
			$this->type	="Instant";
                        $queue		=MessageQueues::CRONCONSUMER_STARTCOMMAND;
			$subject 	="[Instant] Notification Key: $notificationKey, Difference: $diffPercentc%";
			$msgBody	="[Instant] Notification Queue(SmsGcmQueue) Consumer($queue) killed";	
			$rmqObj->killConsumerForCommand($queue);
		}
                elseif(in_array("$notificationKey", $this->deliveryKeyArr)){
			$this->type     ="Delivery";
			$queue  	=MessageQueues::CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND;
			$subject 	="[Delivery] Notification Key: $notificationKey, Difference: $diffPercentc%";
			$msgBody        ="[Delivery] Notification Queue(NOTIFICATION_LOG_QUEUE) Consumer($queue) killed";
			$rmqObj->killConsumerForCommand($queue);
		}
                elseif(array_key_exists("$notificationKey", $this->scheduledKeyArr)){
			$this->type     ="Scheduled";
			$subject 	="[Scheduled] Notification Key: $notificationKey";
			$msgBody        ="[Scheduled] Hanged";
		}

        	$to = "manoj.rana@naukri.com";
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
        	$message        = "Mysql Error Count have reached for Notification($this->type) killed at $t";
        	$from           = "JSSRVR";
        	$profileid      = "144111";
        	$smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        	$date = date("Y-m-d h");
    	}
}
?>
