<?
/*
This php script is run to create object of rabbitmq Consumer class and call 
the receiveMessage function to let the consumer receive messages  on first server.
*/

class cronMonitorMatchAlertNotificationTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronMonitorMatchAlertNotificationTask
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->namespace           = 'notification';
    $this->name                = 'cronMonitorMatchAlertNotification';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony notification:cronMonitorMatchAlertNotification] 
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

    //$to 	= "manoj.rana@naukri.com"; 	
    $to 	= "lavesh.rawat@jeevansathi.com,bhavana.kadwal@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";	
    $curTime 	= date('Y-m-d H:i:s', strtotime('+10 hour 30 minutes'));
    $stTime 	= date('Y-m-d H:i:s', strtotime('+10 hour 20 minutes'));
    $hr 	= date('H', strtotime('+10 hour 30 minutes'));
    $hrArr 	= array('01','02','03','04','05','06','07','08','09');	

    $notificationLogObj = new MOBILE_API_NOTIFICATION_LOG();
    $count = $notificationLogObj->getDataForDuration("MATCHALERT",$stTime,$curTime);

    $notificationDailyObj = new MOBILE_API_DAILY_MATCHALERT_NOTIFICATION();
    $countArr = $notificationDailyObj->getDataForDuration($stTime, $curTime);
    $countN =$countArr['N'];
    $countY =$countArr['Y'];		
    $countT =$countN+$countY;	

    if(($count==0 || $countN==0 || $countT==0) && !in_array("$hr",$hrArr)){

        $monitoringKey = "MA_N_".date('Y-m-d');
        $mailerStartTime = JsMemcache::getInstance()->get($monitoringKey);

        $offsetTime = date('Y-m-d H:i:s', strtotime("+1 hour",  strtotime($mailerStartTime)));
        if(strtotime(date('Y-m-d H:i:s')) > strtotime($offsetTime)){ 
		
                $matchalertSentObject = new matchalerts_MATCHALERTS_TO_BE_SENT();
                $countTbs = $matchalertSentObject->getTotalCountWithScript(1, 0);

                $matchalertMailertObject = new matchalerts_MAILER("matchalerts_slave");
                $MailersCount = $matchalertMailertObject->getMailerProfiles("COUNT(*) as CNT");
		$MailerCountNet = $MailersCount[0]["CNT"];

                if($MailerCountNet!=0){
			$msg = $this->checkCount($countN, $countY, $countT, $count);
                }
		elseif($countTbs==0 && $MailerCountNet ==0){
    			$startTime = date('Y-m-d', strtotime('+10 hour 30 minutes'))." 00:00:00"; 
    			$countArr = $notificationDailyObj->getDataForDuration($startTime, $curTime);
			$msg = $this->checkTotalCount($countArr);
		}
		else{
			$msg = $this->checkCount($countN, $countY, $countT, $count);
		}
		if($msg){
			$this->sendAlertMail($to, $msg, $msg);
			$this->sendAlertSMS($msg);
		}
        }
    }
  }

  public function checkTotalCount($countArr){
	$msg ='';
        $countN =$countArr['N'];
        $countY =$countArr['Y'];
	$countT =$countN+$countY;
	if($countY < 450000){
		$msg ="All Notification not sent";
		$msg .="\n Total Generated:$countT##Pending:$countN##Sent:$countY";	
	}
	return $msg;
  } 

  public function checkCount($countN=0, $countY=0, $countT=0, $count=0){
	$msg ='';
	if($countT!=0){
		if($countN==0)
			$msg = "MatchAlert Notification Generation Issue from Mailer";
	}
	elseif($countT==0){
		$msg = "MatchAlert Notification Not Generated from Mailer";
	}	
	if($countT!=0 && $count==0){
		$msg = " + MatchAlert Notification Delivery Issue";
	}
	if($msg)
		$msg .="\n Pending:$countN##Delivered:$count";   
	return $msg;
  } 


  public function sendAlertSMS($msg=''){
    //$mobileNumberArr = array("manoj"=>"9999216910");
    $mobileNumberArr = array("vibhor"=>"9868673709","manoj"=>"9999216910","lavesh"=>"9818424749","bhavna"=>"9773889652");
    foreach($mobileNumberArr as $k=>$v){
        $this->sms($v,$msg);
    }
  }

  public function sendAlertMail($to,$msgBody,$subject){
        $from = "info@jeevansathi.com";
        $from_name = "Jeevansathi Info";
        SendMail::send_email($to,$msgBody, $subject, $from,"","","","","","","1","",$from_name);
  }

  public function sms($mobile,$msg){
        $t = time();
        if($msg){
            $message    = "Mysql Error Count have reached".$msg." $t";
        }
        else{
            $message    = "Mysql Error Count have reached Matchalert Notification $t";
        }
        $from           = "JSSRVR";
        $profileid      = "144111";
        $smsState = send_sms($message,$from,$mobile,$profileid,'','Y');
        CommonUtility::logTechAlertSms($message, $mobile);
        $date = date("Y-m-d h");
    }
}
?>
