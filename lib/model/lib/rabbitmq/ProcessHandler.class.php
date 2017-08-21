<?php
/*
This class includes functions for sending mail, sms and notifications.
*/
include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");

class ProcessHandler
{
  /**
   * 
   * Function for sending e-mail
   * 
   * @access public
   * @param $type,$body
   */
	public function sendMail($type,$body,$delayedMail=false)
	{
    $senderid=$body['senderid'];
    $receiverid=$body['receiverid'];
    $message = $body['message'];
    
    if($delayedMail) {
      $currentConatacType = $this->processDelayedMail($senderid, $receiverid, $type);
      //if current contact type is different then type of this message then return
      //
      if($currentConatacType != $type) {
        return ;
      }
    }

    if($type!='INITIATECONTACT')
    {
        if($senderid)
        {
          $senderObj = new Profile('',$senderid);   
          $senderObj->getDetail("","","*");
        }
         
        if($receiverid)
        {
          $receiverObj = new Profile('',$receiverid);
          $receiverObj->getDetail("","","*");
        }
    }    
    switch($type)
    {
      case 'CANCELCONTACT' :  ContactMailer::sendCancelledMailer($receiverObj,$senderObj);
                              break;
      case 'ACCEPTCONTACT' :  ContactMailer::sendAcceptanceMailer($receiverObj,$senderObj);  
                              LoggingManager::getInstance()->writeToFileForCoolMetric($body);                              
                              break;
      case 'DECLINECONTACT':  ContactMailer::sendDeclineMail($receiverObj,$senderObj); 
                              break;
      case 'INITIATECONTACT': $viewedSubscriptionStatus=$body['viewedSubscriptionStatus'];
                          // the variable onlylogging ensures that if it is 0 then mail will be sent and logging done .. if it is 1 then no mail is sent and only logging is done

                              if($body['onlyLogging']==0)
                                  ContactMailer::InstantEOIMailer($receiverid, $senderid, $message, $viewedSubscriptionStatus);
                              LoggingManager::getInstance()->writeToFileForCoolMetric($body);                              
                              break;
      case 'MESSAGE'       :  ContactMailer::sendMessageMailer($receiverObj, $senderObj,$message);
                              break;
      case 'PHOTO_SCREENED':  
                              $memObj = new ProfileMemcacheService($senderid);
                              $receiverArray =   unserialize($memObj->get('CONTACTED_BY_ME'));
                              if(is_array($receiverArray['I'])){
                                  foreach ($receiverArray['I'] as $key => $value) {
                                $receiverObj = new Profile();
                                $receiverObj->getDetail($value, "PROFILEID");
   
                                ContactMailer::sendAutoReminderMailer($receiverObj,$senderObj);
                               //   $this->sendAutoReminder($value,$senderid);
                                  }    
                              
                              }
                              break;
      case 'REMINDERCONTACT':
            ContactMailer::InstantReminderMailer($receiverid, $senderid, $message, $body['viewedSubscriptionStatus']);
          break;
      case 'CANCEL_ACCEPT_CONTACT':
          ContactMailer::sendCancelledMailer($receiverObj,$senderObj);
          break;
      case 'EXCLUSIVE_WELCOME_EMAIL':
          $firstName = $body['firstname'];   //First Name of  RM
          $senderName = $body['fromName'];     //Full name of RM
          $profileid = $body['profileid'];
          $phone = $body['phone'];
          $serviceDay = $body['serviceDay'];
          $senderEmail = $body['senderEmail'];
          $subject = "It was nice talking to you !";
          //print_r("1.".$serviceDay."\n2.$alias\n3.$profileid\n4.$phone\n5.$firstName\n6.$from\n7.".$body);
          //print_r($body);die;
          //die;
          //Send Email here
          $top8Mailer = new EmailSender(MailerGroup::TOP8, '1857');
          $tpl = $top8Mailer->setProfileId($profileid);
          $tpl->getSmarty()->assign("senderName",$senderName);
          $tpl->getSmarty()->assign("senderEmail",$senderEmail);
          $tpl->getSmarty()->assign("firstName",$firstName);
          $tpl->getSmarty()->assign("phone",$phone);
          $tpl->getSmarty()->assign("serviceDay",$serviceDay);
          $tpl->setSubject($subject);
          $top8Mailer->send();
          $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
          $exclusiveServicingObj->updateMailerStatus($profileid,'C');//Updating email stage as completed
          break;
      case 'EXCLUSIVE_PROPOSAL_EMAIL':
          $mailerServiceObj = new MailerService();
          $exclsuiveFuncObj = new ExclusiveFunctions();
          $smarty = $mailerServiceObj->getMailerSmarty();
          $mailerName = "EXCLUSIVE_PROPOSAL_MAIL";
          $mailerLinks = $mailerServiceObj->getLinks();
          $smarty->assign('mailerLinks',$mailerLinks);

          $widgetArray = Array("autoLogin"=>true,"nameFlag"=>true,"dppFlag"=>false,"membershipFlag"=>true,"openTrackingFlag"=>true,"filterGenderFlag"=>true,"sortPhotoFlag"=>true,"logicLevelFlag"=>true,"googleAppTrackingFlag"=>true);
          print_r($body);
          $agentEmail = $body["AGENT_EMAIL"];
          $agentName = $body["AGENT_NAME"];
          $agentPhone = $body["AGENT_PHONE"];
          $smarty->assign('mailerName',$agentEmail);
          $pid = $body["RECEIVER"];
          $isUploaded = $body["BIODATAUPLOADED"];
          $bioData = $body["BIODATA"];
          $fileName = $body["FILENAME"];
          $data = $mailerServiceObj->getRecieverDetails($pid,$body,$mailerName,$widgetArray);
          if (is_array($data)) {
              $data["AGENT_PHONE"] = $agentPhone;
              $data["AGENT_NAME"] = $agentName;
              $data["body"]=$body["BODY"];
              $data["isUploaded"] = $isUploaded;
              $subject = $body["SUBJECT"];
              $smarty->assign('data',$data);
              $msg = $smarty->fetch(MAILER_COMMON_ENUM::getTemplate($mailerName).".tpl");
              //$file = fopen("/var/www/html/branch1/web/sampleMailer.html","w");
              //fwrite($file,$msg);
              //die;
              //Sending mail and tracking sent status
              $flag = $mailerServiceObj->sendAndVerifyMail($data["RECEIVER"]["EMAILID"],$msg,$subject,$mailerName,$pid,$agentEmail,$agentName,$bioData,$fileName);
              if ($flag) {
                  $exclsuiveFuncObj->updateStatusForProposalMail($pid,$body["USER1"],'Y');
              } else {
                  $exclsuiveFuncObj->updateStatusForProposalMail($pid,$body["USER1"],'N');
              }
          }
          break;
    }
	}

  /**
   * 
   * Function for sending SMS.      
   * 
   * @access public
   * @param $type,$body
   */
  public function sendSMS($type,$body)
  {
    
    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
    $senderid=$body['senderid']; 
    $receiverid=$body['receiverid'];
    switch($type)
    {
      case 'ACCEPTANCE_VIEWER' : $smsViewer = new InstantSMS($type,$senderid,'',$receiverid);
                                 $smsViewer->send();  
                                 break;
      case 'ACCEPTANCE_VIEWED' : $smsViewer = new InstantSMS($type,$receiverid,'',$senderid);
                                 $smsViewer->send();  
                                 break; 
      case 'CRITICAL_INFORMATION_CHANGE' : 
                                $fieldLabel= array();
                                $impFields = ProfileEnums::$sendInstantMessagesForFields;
                                if(!empty($body["editedFields"])){
                                        foreach($body["editedFields"] as $field){
                                                if(array_key_exists($field, $impFields)){
                                                       $fieldLabel[] =  $impFields[$field];
                                                }
                                        }
                                }
                                $varArray["editedFields"] = implode(", ", $fieldLabel);
                                $varArray["editedFieldsCount"] = count($fieldLabel);
                                $varArray["PHONE_MOB"] = $body["PHONE"];
                                $smsViewer = new InstantSMS("CRITICAL_INFORMATION",$receiverid,$varArray);
                                $smsViewer->send();  
                                JsMemcache::getInstance()->set($receiverid."_5MINS", 1,300);
                                 break; 
    }
  }
public function sendAutoReminder($receiver,$sender){

try{            
            $receiverObj = new Profile();
            $receiverObj->getDetail($receiver, "PROFILEID");

            $senderObj = new Profile();
            $senderObj->getDetail($sender, "PROFILEID");
            $contactObj = new Contacts($senderObj, $receiverObj);
            $contactHandlerObj = new ContactHandler($senderObj,$receiverObj,"EOI",$contactObj,'R',ContactHandler::POST);
            $contactHandlerObj->setElement("MESSAGE","");
            $contactHandlerObj->setElement("DRAFT_NAME","preset");
            $contactHandlerObj->setElement("STATUS","R");
            $contactHandlerObj->setElement("MAIL_AND_NOT","N");
            $contactEngineObj=ContactFactory::event($contactHandlerObj);
    }
    catch(jsException $e){
        
        return;
    }          


}
  /**
   * 
   * Function for sending notifications.
   * 
   * @access public
   * @param $type,$body
   */
  public function sendGCM($type,$body)
  {
    $senderid		=$body['senderid'];   
    $receiverid		=$body['receiverid'];
    $message 		=$body['message'];
    $exUrl		=$body['exUrl'];
    $extraParams 	=$body['extraParams'];		    

    /*switch($type)
    {
      case 'ACCEPTANCE' :  $instantNotificationObj = new InstantAppNotification("ACCEPTANCE");
                           $instantNotificationObj->sendNotification($receiverid, $senderid);
                           break;
      case 'MESSAGE'    :  $instantNotificationObj = new InstantAppNotification("MESSAGE_RECEIVED");
                           $instantNotificationObj->sendNotification($receiverid, $senderid, $message);  
                           break;
    }*/

    // Handle All Instant App Notification	
    $notificationKey =$type;	
    $rabbitMq =1;	
    $instantNotificationObj = new InstantAppNotification($notificationKey);
    $instantNotificationObj->sendNotification($receiverid, $senderid, $message, $exUrl, $extraParams, $rabbitMq);		

  } 

  /**
   * 
   * Function for sending gcm notifications(fso app/Browser Scheduled Notification).
   * 
   * @access public
   * @param $type,$body
   */
  public function sendGcmNotification($type,$body)
  {
    if(in_array($type, BrowserNotificationEnums::$notificationChannelType))
    {
      switch($type)
      {
        case "BROWSER_NOTIFICATION" : GcmNotificationsSender::handleNotification($type,$body,false);
                                      break;
        case "FSOAPP_NOTIFICATION"  : GcmNotificationsSender::handleNotification($type,$body,true);
                                      break;
      }
    }    
  }
 
  // Instant Browser Notification	 
  public function sendInstantNotification($type, $body)
  {
    if($body){
        $notificationType = "INSTANT"; //INSTANT
        $notificationKey = $body["notificationKey"];
        $selfUserId = $body["selfUserId"];    //profileid/agentid to whom notification is to be sent
        $otherUserId = $body["otherUserId"]; //comma separated list of other profileids(whose data is used in notification)
        $message = $body["message"]; //For any other detail which needs to be passed as parameter
        if($otherUserId)
            $otherUserId = explode(",", $otherUserId);
        $processObj = new BrowserNotificationProcess();
        if(in_array($notificationKey, BrowserNotificationEnums::$instantNotifications))
        {
            $processObj->setDetails(array("method"=>$notificationType,"notificationKey"=>$notificationKey,"selfUserId"=>$selfUserId,"otherUserId"=>$otherUserId, "message" => $message));
            $browserNotificationObj = new BrowserNotification($notificationType,$processObj);
            $browserNotificationObj->addNotification($processObj);
        }
    }
  }

  
  /**
   * 
   * Function to delete/retrieve user
   * 
   * @access public
   * @param $type,$body
   */
  public function deleteRetrieveProfileId($type,$body)
  {
    switch($type)
    {
      case "RETRIEVE" : 
                      passthru(JsConstants::$php5path." $_SERVER[DOCUMENT_ROOT]/profile/retrieveprofile_bg.php " . $body['profileId'] . " > /dev/null");  
                      break;
      case "DELETING" :
                      if($body['current_time']){
                        passthru(JsConstants::$php5path." $_SERVER[DOCUMENT_ROOT]/profile/deleteprofile_bg.php " . $body['profileId'] . " '". $body['current_time'] ."' > /dev/null");
                      } else {
                        passthru(JsConstants::$php5path." $_SERVER[DOCUMENT_ROOT]/profile/deleteprofile_bg.php " . $body['profileId'] . " > /dev/null");
                      }
                      
                      break;

    }

 }

 public function insertChatMessage($type,$body)
 {
     switch($type)
     {
         case "PUSH":
             $sender = $body["from"];
             $receiver = $body["to"];
             $communicationType = $body["communicationType"];
             $message = $body["message"];
             $chatId = $body["chatid"];
             $ip = $body["ip"];
             $date = $body["date"];
             $js_communication=new JS_Communication($sender,$receiver,$communicationType,$message,$chatId,$ip,$date);
             $js_communication->storeCommunication();
             break;
     }
 }
 public function updateSeen($type,$body)
 {
        $currentTime = $body['time'];
	if($body['contactType']==ContactHandler::FILTERED)
        {
                $contactRObj=new EoiViewLog();
                $contactRObj->setEoiViewedForAReceiver($body['profileid'],'Y',$currentTime);
        }

        if($body['contactType']==ContactHandler::INITIATED)
        {
                $contactRObj=new EoiViewLog();
                $contactRObj->setEoiViewedForAReceiver($body['profileid'],'N',$currentTime);
        }

	switch($type)
	{
		case "ALL_CONTACTS":
			$contactsObj = new ContactsRecords();
			$contactsObj->makeAllContactSeen($body['profileid'],$body['contactType']);
			break;
		case "ALL_MESSAGES":
			MessageLog::makeAllMessagesSeen($body['profileid']);
			ChatLog::makeAllChatsSeen($body['profileid']);
			break;
		case "PHOTO_REQUEST":
			Inbox::setAllPhotoRequestsSeen($body['profileid']);
			break;
		case "HOROSCOPE_REQUEST":
			Inbox::setAllHoroscopeRequestsSeen($body['profileid']);
			break;
	}
        
 }
 public function updateSeenProfile($typeInfo,$body)
 {
	if(array_key_exists("UPDATE_SEEN",$body))
	{
		$updateSeenData = $body["UPDATE_SEEN"];
		$fromSym=$updateSeenData['fromSym'];
		$type = $updateSeenData['type'];
		$mypid = $updateSeenData['mypid'];
		$updatecontact = $updateSeenData['updatecontact'];
		$profileid = $updateSeenData['profileid']; 
		include(sfConfig::get("sf_web_dir")."/profile/alter_seen_table.php");
	}
	if(array_key_exists("VIEW_LOG",$body))
	{
		$viewLogData = $body['VIEW_LOG'];
		$this->updateViewLogTable($viewLogData,$viewLogData['triggerOrNot']);
	}
 }
 public function sendMatchAlertsReg($body)
 {
        $profileId = $body["profileid"];
        $memObject = JsMemcache::getInstance();
        $tableEmpty = $memObject->get('MATCHALERT_POPULATE_EMPTY');
        unset($memObject);
        $table = "main";
        if($tableEmpty == 1){
                $table = "temp";
        }
        $matchalerts_MATCHALERTS_TO_BE_SENT = new matchalerts_MATCHALERTS_TO_BE_SENT();
        $matchalerts_MATCHALERTS_TO_BE_SENT->insertIntoMatchAlertsTempTable($table, $profileId,'1');  
        unset($matchalerts_MATCHALERTS_TO_BE_SENT);
        if($tableEmpty != 1){
                $php5 = JsConstants::$php5path;
                $cronDocRoot = JsConstants::$cronDocRoot;
                passthru("$php5 $cronDocRoot/symfony alert:MatchAlertCalculation $profileId 0 1");
        }
 }
 public function updateCriticalInfo($body)
 {
	$prevMstatus=  $body['PREV_MSTATUS'];
	$mstatus=  $body['MSTATUS'];
	$prevDob = $body['PREV_DTOFBIRTH'];
	$dob = $body['DTOFBIRTH'];
	$profileid = $body['profileid']; 
	$current_time = $body['current_time']; 
        $deleteInterest = 0;
	if($dob && $prevDob){
                $createDate = new DateTime($prevDob);
                $date = $createDate->format('Y-m-d');
                $todayDate = new DateTime($dob);
                $actionDate = new DateTime($date);
                $diff = $actionDate->diff($todayDate);
                if ($diff->y >= 2) {
                        $deleteInterest = 1;
                }
        }
        if($prevMstatus!= "" && $mstatus != "" &&(($prevMstatus == "N" && $mstatus != "N") || ($prevMstatus != "N" && $mstatus == "N"))){
                $deleteInterest = 1;
        }
        if($deleteInterest == 1){
                $producerObj=new Producer();
		if($producerObj->getRabbitMQServerConnected())
		{
			$sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$profileid,'current_time'=>$current_time)), 'redeliveryCount'=>0 );
			$producerObj->sendMessage($sendMailData);
                }else
		{
			$path = $_SERVER[DOCUMENT_ROOT]."/profile/deleteprofile_bg.php $profileid '$current_time' > /dev/null &";
                        $cmd = JsConstants::$php5path." -q ".$path;
                        passthru($cmd);
		}
    
        }else{
                $maileobj = new CriticalInformationMailer($profileid,$body);
                $maileobj->sendMailer(); 
        }
 }
 public function updateMatchAlertsLaseSeen($body)
 {
	$seenOn = $body['seen_date'];
	$profileid = $body['profileid']; 
        $obj = new seach_MATCH_ALERT_LAST_VISIT(SearchConfig::getSearchDb());
        $obj->ins($profileid,$seenOn);
 }
 
 public function updateJustJoinedLastSeen($body)
 {
	$seenOn = $body['seen_date'];
	$profileid = $body['profileid']; 
        $obj = new search_JUST_JOINED_LAST_USED(SearchConfig::getSearchDb());
        $obj->ins($profileid,$seenOn);
 }
 public function updateFeaturedProfile($type,$body)
 {
	if($body['profileid']!=null|| $body['profileid']!='')
	{
		$loggedInProfile = new LoggedInProfile('',$body['profileid']);
		$loggedInProfile->getDetail('', '', '*');
	}
	$featuredProfileObj = new FeaturedProfile($loggedInProfile);
	$featuredProfileObj->performDbActionFunction($body['id']);
	unset($loggedInProfile);
 }
 /**
 * HandleProfileCacheQueue
 * @param $process
 * @param $body
 */
 public function HandleProfileCacheQueue($process, $body)
 {
     try{
         $key = $body['PROFILEID'];
         if(0 === strlen($key)) {
             return ;
         }
         $key = ProfileCacheConstants::PROFILE_CACHE_PREFIX . $key;
         JsMemcache::getInstance()->delete($key, true);
     } catch (Exception $ex) {
         //Requeue the data
         $reSendData = array('process' =>$process,'data'=>array('type' => '','body'=>$body), 'redeliveryCount'=> 0);
         $producerObj=new Producer();
         $producerObj->sendMessage($reSendData);
     }

 }

  public function logDuplicate($phone,$profileId)
 {
	$profileObj=new Profile();
        $profileObj->getDetail($profileId, 'PROFILEID',"*");
        
        Duplicate::logIfDuplicate($profileObj,$phone);
 }
 
public function updateViewLogTable($body,$type)
 {
        $viewer = $body["VIEWER"];
        $viewed = $body["VIEWED"];
	$viewLogObj=new VIEW_LOG_TRIGGER();
        if($type == "inTrigger")
            $viewLogObj->updateViewTrigger($viewer,$viewed);
        
        $viewLogObj->updateViewLog($viewer,$viewed);
 }
 
public function sendEOI($body, $type)
{
	if($type == "SCREENING")
	{
		$deliverContactsObj = new deliverTempContacts;
		$deliverContactsObj->deliverContactsTemp($body['profileId']);
	}
}

public function logDiscount($body,$type){
    if($type == "DISCOUNT_LOG"){
        $msgDate = $body["DATE"];
        $currentHour = date('H');
        $notAllowedHrs = array("00","01","02","03");
        if(date('Y-m-d') == $msgDate && in_array($currentHour, $notAllowedHrs)){
            $prodObj=new Producer();
            $type = "DISCOUNT_LOG";
            $queueData = array('process' =>'DISCOUNT_HISTORY',
                                'data'=>array('body'=>$body,'type'=>$type),'redeliveryCount'=>0
                              );
            $prodObj->sendMessage($queueData);
            unset($prodObj);
        }
        else{
            $profileid = $body["PROFILEID"];
            $displayPage = 1;$device = "desktop";$ignoreShowOnlineCheck = false;
            $this->userObj = new memUser($profileid);
            $this->userObj->setMemStatus();
            $memHandlerObj = new MembershipHandler();
            
            list($discountType, $discountActive, $discount_expiry, $discountPercent, $specialActive, $variable_discount_expiry, $discountSpecial, $fest, $festEndDt, $festDurBanner, $renewalPercent, $renewalActive, $expiry_date, $discPerc, $code) = $memHandlerObj->getUserDiscountDetailsArray($this->userObj, "L");
            list($allMainMem, $minPriceArr) = $memHandlerObj->getMembershipDurationsAndPrices($this->userObj, $discountType, $displayPage, $device, $ignoreShowOnlineCheck);
            $allMainMem["PROFILEID"] = $profileid;
            $memHandlerObj->computeMaximumDiscount($allMainMem);
            unset($memHandlerObj);
        }
    }
}

    public function addCommunityDiscount($body,$type){
        if($type == "COMMUNITY_DISCOUNT_LOG"){
            $memHandlerObj = new MembershipHandler();
            $memHandlerObj->processCommunityWelcomeDiscount($body["PROFILEID"],$body["COMMUNITY"]);
            unset($memHandlerObj);
        }
    }

  /*
   * Send instant eoi notification
   */
  public function sendInstantEOINotification($body, $type)
  {
    $rabbitMq = 1;
    // consumption logging
    $currdate = date('Y-m-d');
    $file = fopen(JsConstants::$docRoot."/uploads/SearchLogs/InstantEoiQConsume-$currdate", "a+");
    if($type == "INSTANT_EOI")
    {
      $x = json_encode($body);
      fwrite($file, "$type with $x\n");
      $instantNotificationObj = new InstantAppNotification("EOI");
      $instantNotificationObj->sendNotification($body['otherUserId'], $body['selfUserId'], '', '', '', $rabbitMq);
    }
    elseif($type == "INSTANT_CHAT_EOI_MSG")
    {
      $x = json_encode($body);
      fwrite($file, "$type with $x\n");
      $instantNotificationObj = new InstantAppNotification("CHAT_EOI_MSG");
      $instantNotificationObj->sendNotification($body["otherUserId"], $body["selfUserId"], $body["message"], $body["exUrl"], $body["extraParams"], $rabbitMq);
    }
    fclose($file);
  }

    public function processMatchAlertNotification($type,$body){
        $instantNotificationObj =new InstantAppNotification("MATCHALERT");
        $notificationParams["RECEIVER"] = $body["PROFILEID"];
        $cacheKey = "MA_NOTIFICATION_".$notificationParams["RECEIVER"];
        $seperator = "#";
        $preSetCache = JsMemcache::getInstance()->get($cacheKey);

        if($preSetCache){
            $explodedVal = explode($seperator,$preSetCache);
            $notificationParams["COUNT"] = $explodedVal[0];
            $notificationParams["OTHER_PROFILE"] = $explodedVal[1];
            $notificationParams["OTHER_PROFILE_URL"] = $explodedVal[2];
            $lastLoginDt = $explodedVal[3];
            $notificationParams["OTHER_PROFILE_IOS_URL"] = $explodedVal[4];
            $notificationKey = "MATCHALERT";
            $condition = $instantNotificationObj->notificationObj->checkNotificationOnLastLogin($notificationKey,$lastLoginDt);
            if($condition){
                $instantNotificationObj->sendMatchAlertNotification($notificationParams);
            }
            unset($notificationParams,$instantNotificationObj);
            JsMemcache::getInstance()->remove($cacheKey);
        }        
    }
    
    /**
     * 
     * @param type $iSenderId
     * @param type $iReceiverId
     * @param type $szContactTypeInMsg
     */
    private function processDelayedMail($iSenderId, $iReceiverId, $szContactTypeInMsg)
    {
      $arrConatactTypeProcess = array(
                                      "I"=>"INITIATECONTACT",
                                      "E"=>"CANCELCONTACT",
                                      "A"=>"ACCEPTCONTACT",
                                      "D"=>"DECLINECONTACT",
                                      "R"=>"REMINDERCONTACT",
                                      "C"=>"CANCEL_ACCEPT_CONTACT"
                                       );
      
      $szContactType = explode("_", Contacts::getContactsTypeCache($iSenderId, $iReceiverId))[0];

      if($szContactTypeInMsg == "REMINDERCONTACT" && $szContactType == "I") {
        $szContactType = "R";
      }
      return $arrConatactTypeProcess[$szContactType];
    }
 }
?>
