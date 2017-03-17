<?php

/**
 * Browser Notification Library
 */ 
class BrowserNotification{
    public function __construct($method="",$processObj="") 
    {
        include(JsConstants::$docRoot."/commonFiles/dropdowns.php");
        $this->casteDetail = $CASTE_DROP;
        $this->cityDetail = $CITY_INDIA_DROP;
        $this->countryDetail = $COUNTRY_DROP;
        if($method=="SCHEDULED")
            $this->allNotificationsTemplate = $this->getAllNotificationsTemplate();
        else if($method=="INSTANT" && $processObj!="")
            $this->allNotificationsTemplate = $this->getNotificationTemplate($processObj->getnotificationKey());
    }
    /**
     * Process the notification data
     * @param : $processObj(to process the object)
     * @return : none
     */
    public function addNotification($processObj){
        $currentNotificationSetting = $this->allNotificationsTemplate[$processObj->getnotificationKey()];
        
        if($currentNotificationSetting && is_array($currentNotificationSetting))
        {
            $channels = $this->getTopIndexElement($currentNotificationSetting, "CHANNEL");
            $channelsArr = explode(",", $channels);
            $processObj->setchannel($channelsArr);
            unset($channelsArr);
            
            if($processObj->getmethod() == "INSTANT")
            {
                //set agentid or profileid for instant notification based on channel type
                if(in_array("CRM_AND", $processObj->getchannel()))
                    $processObj->setagentId($processObj->getselfUserId());
                else
                    $processObj->setprofileId($processObj->getselfUserId());
                $browserRegistrationIdArr = $this->getChannelWiseRegId($processObj);
                //print_r($browserRegistrationIdArr);die("123");
                //filter profiles based either on login history or daily limit if set
                $browserRegistrationIdArr = $this->filterProfiles($browserRegistrationIdArr,$processObj);
                if($browserRegistrationIdArr){
                    $browserProfilesData = array("SELF"=>$browserRegistrationIdArr,"OTHER"=>$processObj->getotherUserId());
                    $notificationData = $this->getNotificationData($browserProfilesData, $processObj);
                    //print_r($notificationData);die;
                    $this->insert($notificationData,$processObj);
                }
            }
            else if($processObj->getmethod() == "SCHEDULED")
            {
                $notificationDay = $this->getTopIndexElement($currentNotificationSetting, "FREQUENCY");
                //Check if notification day is Daily('D') or ('Mon' to 'Sun')
                $today = date('D',strtotime(date('Y-m-d')));
                $pos = strpos($notificationDay, $today);
                if($notificationDay == 'D' || $pos!==false )
                {
                    $browserProfilesData = $this->getChannelWiseRegId($processObj);
                    //print_r($browserProfilesData);
                    $browserProfilesData = $this->filterProfiles($browserProfilesData,$processObj);
                    //print_r($browserProfilesData);
                    $notificationData = $this->getNotificationData($browserProfilesData, $processObj);
                    //print_r($notificationData);
                    $this->insert($notificationData,$processObj);
                }
            }
        }
    }
    
    /**
     * Function to get channel wise chrome registration ids
     * @param : $processObj (The process object)
     * @return : registration id array
     */
    public function getChannelWiseRegId($processObj){
        $browserRegistrationObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION("newjs_masterRep");
        $channel = $processObj->getchannel();
        if($channel){
            $regIdArr = $browserRegistrationObj->getRegId($processObj->getprofileId(),$processObj->getagentId(), $channel);
        }
        return $regIdArr;
    }
    
    public function insert($notificationData,$processObj){
        $browserNotificationStoreObj = new MOBILE_API_BROWSER_NOTIFICATION();
        $producerObj = new JsNotificationProduce();
        if(is_array($notificationData) && $notificationData)
        foreach ($notificationData as $key => $val){
            unset($paramsArr);
            if(is_array($val['REG_IDS']))
                foreach($val['REG_IDS'] as $regId => $regIdVal)
                {
                    $paramsArr["REG_ID"] = $regIdVal;
                    $paramsArr["NOTIFICATION_KEY"] = $val["NOTIFICATION_KEY"];
                    $paramsArr["NOTIFICATION_TYPE"] = $processObj->getmethod();
                    $paramsArr["MESSAGE"] = $val["NOTIFICATION_MESSAGE"];
                    $paramsArr["TITLE"] = $val["TITLE"];
                    $paramsArr["ICON"] = $val["ICON"];
                    $paramsArr["TAG"] = $val["TAG"];
                    $paramsArr["PROFILE_CHECKSUM"] = $val["OTHER_PROFILE_CHECKSUM"];
                    $paramsArr["LANDING_ID"] = $this->mapNotificationLandingID($val['LANDING_ID'],$processObj->getchannel(),$val["NOTIFICATION_KEY"],$val["OTHER_PROFILE_CHECKSUM"]);
                    $paramsArr["MSG_ID"] = $val["MSG_ID"];
                    $paramsArr["USERNAME"] = $val["SELF"]["USERNAME"];
                    $paramsArr["SENT_TO_QUEUE"] = 'Y';
                    $paramsArr["TTL"] = $val["TTL"];
                    if($producerObj->getRabbitMQServerConnected()){
                        $notificationKey = $processObj->getnotificationKey();
                        $msgdata = FormatNotification::formatPushNotification($paramsArr,$processObj->getchannel(),true);
                        $producerObj->sendMessage($msgdata,BrowserNotificationEnums::$addNotificationLog);
                    }
                    else{
                        $paramsArr["SENT_TO_QUEUE"] = "N";
                        $str = "\nRabbitmq Notification Error Alert: Rabbitmq Server is down.";
                        RabbitmqHelper::sendAlert($str,"browserNotification");
                    }
                    $res = $browserNotificationStoreObj->insertNotification($paramsArr); 
                }
        }
    }

    /**
     * update details for sent browser notification in MOBILE_API_BROWSER_NOTIFICATION---wrapper fun
     * @param : $criteria(name of key to be searched),$value(nee value),$updateArr(array of fields with values)
     * @return : none
     */
    public function updateSentNotificationDetails($criteria="REG_ID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
    {
        $browserNotificationStoreObj = new MOBILE_API_BROWSER_NOTIFICATION();
        $browserNotificationStoreObj->updateEntryDetails($criteria,$value,$updateArr,$extraWhereClause,$inWhereStr);
        unset($browserNotificationStoreObj);
    }

    /**
     * update details for regsitration in MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION---wrapper fun
     * @param : $criteria(name of key to be searched),$value(nee value),$updateArr(array of fields with values)
     * @return : none
     */
    public function updateRegistrationDetails($criteria="REG_ID",$value="",$updateArr,$extraWhereClause="",$inWhereStr="")
    {
        $browserRegistrationObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $browserRegistrationObj->updateRegistrationDetails($criteria,$value,$updateArr,$extraWhereClause,$inWhereStr);
        unset($browserRegistrationObj);
    }
    
    public function getNotificationTemplate($notificationKey)
    {
        $browserNotificationTemplateObj = new MOBILE_API_BROWSER_NOTIFICATION_TEMPLATE();
        $templateDetails = $browserNotificationTemplateObj->getArray($notificationKey,'NOTIFICATION_KEY',"*","","",array('STATUS'=>'Y'));
        
        if(is_array($templateDetails) && $templateDetails)
        {    
            foreach($templateDetails as $notificationName => $value)
            {
                foreach($value as $key => $val)
                {
                    $templateDetails[$notificationName][$key]["NOTIFICATION_BREAKUP"] = $this->getNotificationBreakup($val["MESSAGE"]);
                    $templateDetails[$notificationName][$key]["NOTIFICATION_BREAKUP_TITLE"] = $this->getNotificationBreakup($val["TITLE"]);
                }
                
            }
        }
        return $templateDetails;
    }
    
    /*get notification data based on notification
    * @param : $browserProfilesData,$processObj
    * @return: $completenotificationinfo
    */
    public function getNotificationData($browserProfilesData,$processObj)
    {
        $notificationMethod = $processObj->getmethod();
        $notificationKey = $processObj->getnotificationKey();
        if(is_array($browserProfilesData))
        {
            if($notificationMethod=="SCHEDULED")
                $browserProfilesArr = array_keys($browserProfilesData);
            else
                $browserProfilesArr = array("SELF"=>array_keys($browserProfilesData["SELF"])[0],"OTHER"=>$browserProfilesData["OTHER"][0]);
        }
        switch($notificationKey)
        {
            case "JUST_JOIN":
    			$applicableProfiles=array();
    			$applicableProfiles = $this->getProfileApplicableForNotification($browserProfilesArr,$notificationKey);
                $poolObj = new NotificationDataPool();
                $dataAccumulated = $poolObj->getJustJoinData($applicableProfiles);
                unset($poolObj);
			 break;

        case "AGENT_ONLINE_PROFILE":  //instant notification sent to agents for online profile intimation

        case "AGENT_FP_PROFILE": //instant notification sent to agents for FP profile intimation
            $poolObj = new NotificationDataPool();
            $dataAccumulated = $poolObj->getAgentInstantNotificationsPool($browserProfilesArr);
            unset($poolObj);
            break; 
        
        case "PENDING_EOI":
            $applicableProfiles=array();
			$applicableProfiles = $this->getProfileApplicableForNotification($browserProfilesArr,$notificationKey);
			$poolObj = new NotificationDataPool();
            $dataAccumulated = $poolObj->getPendingInterestData($applicableProfiles);
            unset($poolObj);
            break;
        
        case "PROFILE_VISITOR":
            //$applicableProfiles=array();
            //$applicableProfiles = $this->getProfileApplicableForNotification($browserProfilesArr,$notificationKey);

            //$profileArr = array_keys($applicableProfiles);

            $poolObj = new NotificationDataPool();
			$details = $poolObj->getProfilesData($browserProfilesArr,"newjs_SMS_TEMP_TABLE","newjs_masterRep");
            if(count($details) <= 1){
                $details = $poolObj->getProfilesData($browserProfilesArr,"JPROFILE","newjs_masterRep");
            }
            //print_r($details);die;
            $dataAccumulated = $poolObj->getProfileVisitorData($browserProfilesArr, $details, $processObj->getmessage());
            //print_r($dataAccumulated);die;
            unset($poolObj);
            
            break;
           
        case "MEM_EXPIRE_A5":
        case "MEM_EXPIRE_A10":
        case "MEM_EXPIRE_A15":
        case "MEM_EXPIRE_B1":	 
        case "MEM_EXPIRE_B5":
            $applicableProfiles=array();
            $poolObj = new NotificationDataPool();
            $applicableProfiles = $poolObj->getMembershipProfilesForNotification($browserProfilesArr, $processObj->getchannel());
            $dataAccumulated = $poolObj->getRenewalReminderData($applicableProfiles);
            unset($applicableProfiles);
            break;

        case "EOI":
        case "MESSAGE_RECEIVED":
        case "EOI_REMINDER":
            $poolObj = new NotificationDataPool();
            $details = $poolObj->getProfilesData(array($browserProfilesArr['SELF'],$browserProfilesArr['OTHER']),"newjs_SMS_TEMP_TABLE","newjs_masterRep");
            if(count($details) <= 1){
                $details = $poolObj->getProfilesData(array($browserProfilesArr['SELF'],$browserProfilesArr['OTHER']),"JPROFILE","newjs_masterRep");
            }
            $dataAccumulated = $poolObj->getProfileInstantNotificationData($notificationKey,$browserProfilesArr, $details,$processObj->getmessage());
            unset($poolObj);
            break;
        }
        
        $completeNotificationInfo = array();
        $counter = 0;
        
        if(is_array($dataAccumulated))
        {
            foreach($dataAccumulated as $x=>$dataPerNotification)
            {
                $notificationId = NULL;
                $notificationId = $this->matchNotificationKeyData($notificationKey,$dataPerNotification);
                if($notificationId)
                {
                    $completeNotificationInfo[$counter] = $this->generateNotification($notificationId, $notificationKey,$dataPerNotification);
                    $notificationDataPoolObj = new NotificationDataPool();
                    $completeNotificationInfo[$counter]["ICON"] = $notificationDataPoolObj->getNotificationImage($completeNotificationInfo[$counter]["ICON"],$dataPerNotification['ICON_PROFILEID']);
                    unset($notificationDataPoolObj);
                    $completeNotificationInfo[$counter]['SELF'] = $dataPerNotification['SELF'];
                    $completeNotificationInfo[$counter]['MSG_ID']=rand(0,99).time().rand(0,99).rand(0,99).rand(0,9);
                    $completeNotificationInfo[$counter]['OTHER_PROFILE_CHECKSUM'] = JsCommon::createChecksumForProfile($dataPerNotification['OTHER'][0]['PROFILEID']);

                    if($notificationMethod=="INSTANT")
                    {

                        if(in_array("CRM_AND",$processObj->getchannel()))
                            $completeNotificationInfo[$counter]['REG_IDS'] = $browserProfilesData['SELF'][$dataPerNotification['SELF']['RESID']];
                        else
                            $completeNotificationInfo[$counter]['REG_IDS'] = $browserProfilesData['SELF'][$dataPerNotification['SELF']['PROFILEID']];
                    }
                    else
                        $completeNotificationInfo[$counter]['REG_IDS'] = $browserProfilesData[$dataPerNotification['SELF']['PROFILEID']];
                    $counter++;
                }
            }
            unset($notificationId);
            unset($dataAccumulated);
            //print_r($completenotificationinfo);die;
            return $completeNotificationInfo;
        }
        
    }
    
    public function getProfileApplicableForNotification($profiles,$notificationKey)
    {
        unset($applicableProfiles);
        $currentNotificationSetting = $this->allNotificationsTemplate[$notificationKey];
        $timeCriteria = $currentNotificationSetting['TIME_CRITERIA'];
        unset($notifications);
        $smsTempTableObj = new newjs_SMS_TEMP_TABLE("newjs_masterRep");
        $varArray['PROFILEID']=implode(",",array_filter($profiles));
        unset($profiles);
        if($timeCriteria!='')
        {
            $timeCriteriaArr = explode("|",$timeCriteria);
            if($timeCriteriaArr[0]!='')
            {
                $dateformatGreaterThan = $this->getDate($timeCriteriaArr[0]);
                $greaterThan['LAST_LOGIN_DT']=$dateformatGreaterThan;
            }
            if($timeCriteriaArr[1]!='')
            {
                $dateformatLessThan = $this->getDate($timeCriteriaArr[1]);
                $lessThan['LAST_LOGIN_DT']=$dateformatLessThan;
            }
        }
        $profiles = $smsTempTableObj->getArray($varArray,'',$greaterThan,$fields="*",$lessThan);
        if(is_array($profiles))
        {
            foreach($profiles as $k=>$v)
                $applicableProfiles[$v['PROFILEID']] =$v;
            return $applicableProfiles;
        }
        return false;
    }
  
    public function getDate($days)
    {
        if ($days == 0) {
            $timestamp = mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y"));
        }
        else
        {
            $hrs                = $days * 24;
            $timestamp = mktime(date("H") - $hrs, date("i"), date("s"), date("m"), date("d"), date("Y"));
        }
        return $dateformat     = date("Y-m-d", $timestamp);
    }
    
    public function getAllNotificationsTemplate(){
        $notificationsTempObj = new MOBILE_API_BROWSER_NOTIFICATION_TEMPLATE("newjs_masterRep");
        $notificationArr = $notificationsTempObj->getAll();
        foreach($notificationArr as $notificationName => $value){
            foreach($value as $key => $val){
                $notificationArr[$notificationName][$key]["NOTIFICATION_BREAKUP"] = $this->getNotificationBreakup($val["MESSAGE"]);
                $notificationArr[$notificationName][$key]["NOTIFICATION_BREAKUP_TITLE"] = $this->getNotificationBreakup($val["TITLE"]);
            }
            
        }
        return $notificationArr;
    }

    public function manageRegistrationid($registrationid,$profileid='',$agentid='',$channel='',$appVersion='')
    {
        if(!$registrationid)
            return;

        $registrationIdObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
        $registrationIdData = $registrationIdObj->findRegId($registrationid,$channel);
        $currentDate = date('Y-m-d H:i:s');
        
        if(is_array($registrationIdData))
        {
            if($agentid && $registrationIdData[0]['AGENTID']!=$agentid)
            {
                $updateArr = array("AGENTID"=>$agentid,"ENTRY_DT"=>$currentDate);
                $registrationIdObj->updateRegistrationDetails("REG_ID",$registrationid,$updateArr);
            }
            else if($profileid && $registrationIdData[0]['PROFILEID']!=$profileid)
            {
                $updateArr = array("PROFILEID"=>$profileid,"ENTRY_DT"=>$currentDate);
                $registrationIdObj->updateRegistrationDetails("REG_ID",$registrationid,$updateArr);
            }
            else
            {
                $updateArr = array("PROFILEID"=>NULL,"AGENTID"=>NULL,"ENTRY_DT"=>$currentDate);
                $registrationIdObj->updateRegistrationDetails("REG_ID",$registrationid,$updateArr);
            }
        }
        else
        {
            $paramsArr = array("ENTRY_DT"=>$currentDate,"CHANNEL"=>$channel,"USER_AGENT"=>$appVersion,"REG_ID"=>$registrationid);
            if($profileid)
                $paramsArr["PROFILEID"] = $profileid;
            else if($agentid)
                $paramsArr["AGENTID"] = $agentid;
          
            $registrationIdObj->insertRegistrationDetails($paramsArr);
        }
        return true;
    }
    
    
    public function matchNotificationKeyData($notificationKey,$accumulatedDataForMessage)
    {
        $notifications = $this->allNotificationsTemplate;
        foreach($notifications[$notificationKey] as $k=> $criteria)
        {
            $subscription = explode(",",$criteria["SUBSCRIPTION"]);//if($criteria['SUBSCRIPTION']);
            foreach ($subscription as $key => $value)
                $temp[$value][$criteria["COUNT"]] = $criteria["ID"];
        }
        $count = ($accumulatedDataForMessage['COUNT'])?$accumulatedDataForMessage['COUNT']:"SINGLE";
        $subscription = (strstr($accumulatedDataForMessage['SELF']["SUBSCRIPTION"],"F"))?"P":"F";
        if ($temp[$subscription][$count])
            $mess = $temp[$subscription][$count];
        elseif ($temp[$subscription][$count])
            $mess = $temp[$subscription][$count];
        elseif ($temp["A"][$count])
            $mess = $temp["A"][$count];
        else
            $mess = $temp["A"][$count];
        return $mess;
    }
    
    public function getTopIndexElement($array, $key){
        foreach($array as $k => $val){
            $result = $val[$key];
            break;
        }
        return $result;
    }
    
    
    public function generateNotification($notificationId, $notificationKey,$dataPerNotification)
    {
        $notifications = $this->allNotificationsTemplate[$notificationKey];
        $variableValues = array();
        
        if(is_array($notifications[$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE']) && $notifications[$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE'])
        {  
            foreach($notifications[$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE'] as $k=>$tokenVariable)
            $variableValues[$tokenVariable] = $this->getVariableValue($tokenVariable, $dataPerNotification);
        }	
        if(is_array($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['VARIABLE'])&& $notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['VARIABLE'])
        {
            foreach($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['VARIABLE'] as $k=>$tokenVariable)
                    $variableValuesTitle[$tokenVariable] = $this->getVariableValue($tokenVariable, $dataPerNotification);
        }
        if($variableValues || $variableValuesTitle || in_array($notificationKey,BrowserNotificationEnums::$staticContentNotification))
        {
            if($notifications[$notificationId]['NOTIFICATION_BREAKUP']['flagPosition']=="STATIC")
              $finalNotificationMessage = $this->mergeNotification($notifications[$notificationId]['NOTIFICATION_BREAKUP']['STATIC'],$variableValues);
            else
              $finalNotificationMessage = $this->mergeNotification($variableValues, $notifications[$notificationId]['NOTIFICATION_BREAKUP']['STATIC']);
    
            $finalNotificationMessageTitle = $this->mergeNotification($variableValuesTitle, $notifications[$notificationId]['NOTIFICATION_BREAKUP_TITLE']['STATIC']);
            $completeNotificationInfo["USERNAME"] = $this->getVariableValue("USERNAME_SELF", $dataPerNotification);
            $completeNotificationInfo = $notifications[$notificationId];
            $completeNotificationInfo['NOTIFICATION_MESSAGE'] = $finalNotificationMessage;
            $completeNotificationInfo['NOTIFICATION_MESSAGE_TITLE'] = $finalNotificationMessageTitle;	
            $completeNotificationInfo['COUNT'] = $dataPerNotification['COUNT_BELL'];
            return $completeNotificationInfo;
        }
    }
    
    public function getNotificationBreakup($notificationMessage)
    {
        $notificationBreakup = array($notificationMessage);
        foreach(BrowserNotificationEnums::$messageDelimiters as $delimiter)
        {
            $newNotificationBreakup = array();
            foreach($notificationBreakup as $stringToSplit)
            {
                $tempBreakup = explode($delimiter, $stringToSplit);
                foreach($tempBreakup as $temp)
                {
                    $newNotificationBreakup[]=$temp;
                }
            }
            $notificationBreakup=$newNotificationBreakup;
        }
        //return $notificationBreakup;
        if (is_numeric($notificationBreakup[0]))
        {
            $flagPosition = "VARIABLE";        $nextPosition = "STATIC";
        }
        else
        {
            $flagPosition = "STATIC";        $nextPosition = "VARIABLE";
        }
        foreach ($notificationBreakup as $key => $value)
        {
            if ($key % 2 == 0)
              $return[$flagPosition][] = $value;
            else
              $return[$nextPosition][] = $value;
        }
        $return["flagPosition"]   = $flagPosition;
        return $return;
    }
    
    public function mergeNotification($arr1, $arr2)
    {
        $mrgMsg = $arr1[0];
        $cnt    = 0;
        foreach ($arr2 as $key => $value) {
            $mrgMsg .= $value;
            $cnt++;
            $mrgMsg .= $arr1[$cnt];
        }
        return $mrgMsg;
    }
    
    
    public function getVariableValue($variable, $details)
    {
      $maxlength = BrowserNotificationEnums::$variablesMaxlength[$variable];
      $maxlength = ($maxlength)? $maxlength:200;
      switch($variable)
      {
      case "USERNAME_SELF":
              return strlen($details["SELF"]["USERNAME"])<=$maxlength ? $details["SELF"]["USERNAME"] : substr($details["SELF"]["USERNAME"],0,$maxlength-2) . "..";
          case "USERNAME_OTHER_1":
              return strlen($details['OTHER'][0]["USERNAME"])<=$maxlength ? $details['OTHER'][0]["USERNAME"] : substr($details['OTHER'][0]["USERNAME"],0,$maxlength-2) . "..";
          case "USERNAME_OTHER_2":
              return strlen($details['OTHER'][1]["USERNAME"])<=$maxlength ? $details['OTHER'][1]["USERNAME"] : substr($details['OTHER'][1]["USERNAME"],0,$maxlength-2) . "..";
          case "DISCOUNT":
              return $details["SELF"]["DISCOUNT"];
          case "MESSAGE_RECEIVED":
              return $details["MESSAGE_RECEIVED"];
          case "MESSAGE":
          return $details["SELF"]["MESSAGE"];	
      case "EDATE":
        return $details["SELF"]['EDATE'];
      case "UPTO":
        return $details["SELF"]['UPTO'];
          case "MATCH_COUNT":
              $count = $details["MATCH_COUNT"];
          if($count<10)
            return $count;
          elseif($count<100)
            return $return =($count-($count%10))."+";
          elseif($count<1000)
            return $return =($count-($count%100))."+";
          else
            return $return =floor($count/1000)."k+";
    case "CONTACTS_COUNT":
      $count = $details["CONTACTS_COUNT"];
      return $count;
      case "MATCHALERT_COUNT":
            return $details["MATCHALERT_COUNT"];
          case "EOI_COUNT":
              return $details['EOI_COUNT'];
          case "AGE_OTHER_1":
              return $details['OTHER'][0]["AGE"];
          case "VISITOR_COUNT":
              return $details["VISITOR_COUNT"];
      case "CASTE_OTHER_1":
        $html = $this->casteDetail[$details['OTHER'][0]["CASTE"]];
        if(strstr($html,": "))
        {
            $first = strpos($html, ': ');
            $casteValue = substr($html, $first+2);
        }
        else 
            $casteValue = $html;
        return strlen($casteValue)<=$maxlength ? $casteValue : substr($casteValue,0,$maxlength-2)."..";
      case "CITY_RES_OTHER_1":
        if($details['OTHER'][0]["COUNTRY_RES"]=="51")
            $CITY_RES= $this->cityDetail[$details['OTHER'][0]["CITY_RES"]];		
        else
            $CITY_RES= $this->countryDetail[$details['OTHER'][0]["COUNTRY_RES"]];
        return strlen($CITY_RES)<=$maxlength ? $CITY_RES : substr($CITY_RES,0,$maxlength-2)."..";
      }
    }
    
    /**
     * Function to filter profiles based on channel login
     * @param : $browserProfilesData(all channels data),$processObj (The process object)
     * @return : $allChannelFilteredProfiles
     */
    public function filterProfiles($browserProfilesData,$processObj)
    {
        $channelsArr = $processObj->getchannel();
        $todayDate =date("Y-m-d");
        $notificationKey = $processObj->getnotificationKey();
        $allChannelFilteredProfiles = array();
        $dateToday = date("Y-m-d");
        $greaterThanDt=$dateToday." 00:00:00";
        $lessThanDt=$dateToday." 23:59:59";
        foreach ($channelsArr as $key => $channel) 
        {
            //echo "----".$channel."----".$notificationKey."----";
            $channelWiseProfiles = array();
            if(is_array($browserProfilesData[$channel]))
            {
                //filter profile based on daily limit of instant notification
                if($processObj->getmethod()=="INSTANT" && in_array($channel,BrowserNotificationEnums::$channelForDailyLimitNotificationFilter))
                {
                    $channelWiseProfiles = array_keys($browserProfilesData[$channel]);
                    foreach ($this->allNotificationsTemplate[$notificationKey] as $key => $value) 
                    {
                        $dailyLimit = $value["DAILY_LIMIT"];
                    }
                    if($dailyLimit)
                    {
                        $notificationObj = new MOBILE_API_BROWSER_NOTIFICATION();//???slave
                        $count = $notificationObj->getSentNotificationCount($browserProfilesData[$channel][$channelWiseProfiles[0]],$channel,$notificationKey,$lessThanDt,$greaterThanDt);
                        if($count>=$dailyLimit)
                            unset($browserProfilesData[$channel][$channelWiseProfiles[0]]);
                        unset($notificationObj);
                    }    
                }
                if(is_array($browserProfilesData[$channel]) && $browserProfilesData[$channel])
                {
                    $channelWiseProfiles = array_keys($browserProfilesData[$channel]);
                    //filter profiles based on login history
                    if(BrowserNotificationEnums::$loginBasedNotificationProfileFilter[$channel] && !in_array($notificationKey, BrowserNotificationEnums::$notificationWithoutLoginFilter))
                    {        
                        $loginTrackingObj = new MIS_LOGIN_TRACKING("newjs_local111");
                        $date15DaysBack = date("Y-m-d", strtotime("$todayDate -14 days"))." 00:00:00";
                        $profileStr = implode(",", $channelWiseProfiles);
                        $channelStr = BrowserNotificationEnums::$loginBasedNotificationProfileFilter[$channel];
                        $loginDataForProfiles = $loginTrackingObj->getLastLoginDataForDate($profileStr, $date15DaysBack, $channelStr);
                        //print_r($loginDataForProfiles);
                        $browserProfilesData = $this->filterChannelWiseLoginDataPool($channel,$channelStr,$loginDataForProfiles,$browserProfilesData,$channelWiseProfiles);
                        
                    }  
                    //print_r($browserProfilesData[$channel]);
                    foreach ($browserProfilesData[$channel] as $key => $value) 
                    {
                        $allChannelFilteredProfiles[$key][] = $value;
                    }
                }
            }
        }
        unset($channelWiseProfiles);
        //print_r($allChannelFilteredProfiles);die;
        return $allChannelFilteredProfiles;
    }
    
    public function filterChannelWiseLoginDataPool($channel,$channelStr,$loginDataForProfiles,$browserProfilesData,$channelWiseProfiles)
    {
        if(is_array($channelWiseProfiles) && $channelWiseProfiles)
            foreach($channelWiseProfiles as $key => $val)
            {
                if(strpos($channelStr, 'M')!=false || strpos($channelStr, 'N')!=false)
                {
                    if($loginDataForProfiles[$val]["A"] || $loginDataForProfiles[$val]["I"] || !($loginDataForProfiles[$val]["M"]  || $loginDataForProfiles[$val]["N"]))
                    {
                        unset($browserProfilesData[$channel][$val]);
                    }
                }
                else if(strpos($channelStr, 'I')!=false || strpos($channelStr, 'A')!=false)
                {
                    if($loginDataForProfiles[$val]["A"] || $loginDataForProfiles[$val]["I"])
                    {
                        unset($browserProfilesData[$channel][$val]);
                    }
                }
                else if(strpos($channelStr, 'D')!=false)
                {
                    if(!$loginDataForProfiles[$val]["D"])
                    {
                        unset($browserProfilesData[$channel][$val]);
                    }
                }
            }
        return $browserProfilesData;
    }
    
    private function mapNotificationLandingID($landingid,$channelArr,$notificationKey,$profileChecksum)
    {
        if(in_array("CRM_AND", $channelArr))
            $landingid = BrowserNotificationEnums::$landingIdToUrl[$landingid];
        else
        {  
            $extraParams="";
            if($landingid==6)
            {
                $extraParams = "?profilechecksum=".$profileChecksum."&responseTracking=".BrowserNotificationEnums::$notificationResponseTracking[$notificationKey];
            }
            $landingid = JsConstants::$siteUrl.BrowserNotificationEnums::$landingIdToUrl[$landingid].$extraParams;
        }
        return $landingid;
    }
}
