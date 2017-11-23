<?
/*
This php script is run to create object of rabbitmq Consumer class and call 
the receiveMessage function to let the consumer receive messages  on first server.
*/

class cronMatchAlertNotificationSendTask extends sfBaseTask
{
  /**
   * 
   * Configuration details for cron:cronMatchAlertNotificationSendTask
   * 
   * @access protected
   * @param none
   */
  protected function configure()
  {
    $this->addArguments(array(new sfCommandArgument('noOfScripts', sfCommandArgument::REQUIRED, 'My argument')));
    $this->addArguments(array(new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument')));

    $this->namespace           = 'notification';
    $this->name                = 'cronMatchAlertNotificationSend';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve messages on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeQueueMessage|INFO] calls receiveMessage function of consumer class through its instance to retrieve messages on first server:
     [php symfony notification:cronMatchAlertNotificationSend] 
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

	// Code added by Bhavna
      if(CommonUtility::runFeatureInDaytime(1,8)){
              $memObject = JsMemcache::getInstance();
              $tableEmpty = $memObject->get('MATCHALERT_POPULATE_EMPTY');
              unset($memObject);
              if($tableEmpty == 1){
              }else{
                      successfullDie();
              }
      }
      $noOfScripts = $arguments["noOfScripts"];
      $currentScript = $arguments["currentScript"];
      $limit = 500;
      $curTime = date('Y-m-d H:i:s', strtotime('+10 hour 30 minutes'));	
      $offsetTime = date('Y-m-d H:i:s', strtotime("-1 hour",  strtotime($curTime)));
      $status='N';	

      $dailyMatchalerNotifObj =new MOBILE_API_DAILY_MATCHALERT_NOTIFICATION();
      $dataArr =$dailyMatchalerNotifObj->getRecords($offsetTime,$status,$noOfScripts, $currentScript,$limit);
      $instantNotificationObj =new InstantAppNotification("MATCHALERT");
	
      if(is_array($dataArr)){
      	foreach($dataArr as $key=>$dataVal){ 
		//print_r($dataVal);
		$this->processMatchAlertNotification($dataVal,$dailyMatchalerNotifObj, $instantNotificationObj);
	}
      }
  }
  public function processMatchAlertNotification($body,$dailyMatchalerNotifObj, $instantNotificationObj){
        //$instantNotificationObj =new InstantAppNotification("MATCHALERT");
        /*$notificationParams["RECEIVER"] = $body["PROFILEID"];
        $cacheKey = "MA_NOTIFICATION_".$notificationParams["RECEIVER"];
        $seperator = "#";
        $preSetCache = JsMemcache::getInstance()->get($cacheKey);*/

        //if($preSetCache){
        if(is_array($body))
            //$explodedVal = explode($seperator,$preSetCache);
            $explodedVal =$body;
	    $id =$explodedVal['ID'];
            $notificationParams["COUNT"] = $explodedVal['COUNT'];
            $notificationParams["OTHER_PROFILE"] = $explodedVal['OT_PROFILEID'];
            $notificationParams["OTHER_PROFILE_URL"] = $explodedVal['OT_PIC_URL'];
            $lastLoginDt = $explodedVal['REC_LAST_LOGIN_DATE'];
            $notificationParams["OTHER_PROFILE_IOS_URL"] = $explodedVal['OT_PIC_IOS_URL'];
            $notificationKey = "MATCHALERT";
            $condition = $instantNotificationObj->notificationObj->checkNotificationOnLastLogin($notificationKey,$lastLoginDt);
	    $sent ='Y';
            if($condition){
                $instantNotificationObj->sendMatchAlertNotification($notificationParams);
            }
	    $dailyMatchalerNotifObj->updateStatus($id, $sent);	

            unset($notificationParams,$instantNotificationObj);
            //JsMemcache::getInstance()->remove($cacheKey);
  }
}
?>
