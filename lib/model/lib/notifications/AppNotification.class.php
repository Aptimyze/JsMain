<?php
/**
 * class AppNotificationScheduler
 * 
 */
class AppNotification extends Notification
{
  public function __construct()
  {
	$this->messageDelimiters=NotificationEnums::$appMessageDelimiters;
	$this->setNotificationSettingClass(NotificationEnums::AppNotificationSettingClass);
	$this->setVariablesMaxlength();
	parent::__construct();
  }
  public function setVariablesMaxlength()
  {
	  $this->variablesMaxlength = NotificationEnums::$appVariablesMaxlength;
  }
  public function organiseNotificationSettings($settings)
  {
	  foreach($settings as $k=>$v)
	  {
            $notificationSettings[$v["NOTIFICATION_KEY"]][$v["ID"]]              = $v;
            $notificationSettings[$v["NOTIFICATION_KEY"]][$v["ID"]]["NOTIFICATION_BREAKUP"] = $this->getNotificationBreakup($v["MESSAGE"]);
	    $notificationSettings[$v["NOTIFICATION_KEY"]][$v["ID"]]["NOTIFICATION_BREAKUP_TITLE"] = $this->getNotificationBreakup($v["TITLE"]);
            $notificationSettings['TIME_CRITERIA'][$v["NOTIFICATION_KEY"]] = $v["TIME_CRITERIA"];
	  }
	  return $notificationSettings;
  }
public function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
  public function getNotificationData($appProfiles,$notificationKey, $message='',$count='')
  {
	  switch($notificationKey)
	  {
		  case "VISITOR":
			// OBSELETE CODE
		  	$applicableProfiles=array();
		  	$newVisitors = array();
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
			if(is_array($applicableProfiles))
			{
				foreach($applicableProfiles as $profileid=>$profiledetails)
				{
					$visitorObj = new Visitors($profileid);
					$visitorDetails[$profileid] = array_slice($visitorObj->getVisitorProfile(),0,10,true);
					unset($visitorObj);
					if(is_array($visitorDetails))
					{
						$profileMemcacheServiceObj = new ProfileMemcacheService($profileid);
						$visitorCount[$profileid] = $profileMemcacheServiceObj->get("VISITOR_ALERT");;
						unset($profileMemcacheServiceObj);
					}
					if($visitorCount[$profileid]>0)
					{
						$dateTimeBack = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-1, date("Y"));
						$dateTime = date("Y-m-d H:i:s");
						$dateStr1 = JSstrToTime($dateTimeBack);
						$dateStr2 = JSstrToTime($dateTime);
						$newVisitors[$profileid] = false;
						foreach($visitorDetails[$profileid] as $k=>$v)
						{
							$otherProfiles[] = $k;
							$visitedTime = JSstrToTime($v['TIME']);
							if($dateStr1<=$visitedTime && $visitedTime<=$dateStr2)
								$newVisitors[$profileid] = true;
						}
					}
				}
				if(is_array($otherProfiles))
					$getOtherProfilesData = $this->getProfilesData($otherProfiles,$className="newjs_SMS_TEMP_TABLE");
				unset($otherProfiles);
				$counter = 0;
				foreach($visitorDetails as $k1=>$v1)
				{
					if($visitorCount[$k1]>0 && $newVisitors[$k1]==true)
					{
						$dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
						foreach($visitorDetails[$k1] as $k2=> $v2)
						{ 
							if(count($dataAccumulated[$counter]['OTHER'])>=2)
								break;
							if($getOtherProfilesData[$k2])
								$dataAccumulated[$counter]['OTHER'][]=$getOtherProfilesData[$k2];
						}
						$dataAccumulated[$counter]['COUNT'] = ($visitorCount[$k1]==1)?"SINGLE":(($visitorCount[$k1]==2)?"DOUBLE":"MUL");
						$dataAccumulated[$counter]['VISITOR_COUNT'] = ($visitorCount[$k1]>2)?($visitorCount[$k1]-2):0;
						$dataAccumulated[$counter]['COUNT_BELL'] = $visitorCount[$k1];
						$counter++;
					}
				}
				unset($applicableProfiles);
				unset($getOtherProfilesData);
				unset($visitorDetails);
				unset($visitorCount);
				unset($newVisitors);
			}
			break;
		  case "JUST_JOIN":
			$applicableProfiles=array();
            //$xx1 = count($appProfiles);
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
            //$xx2 = count($applicableProfiles);
            		$applicableProfilesArr = array_keys($applicableProfiles);
            		$applicableProfilesData = $this->getProfilesData($applicableProfilesArr,$className="newjs_SMS_TEMP_TABLE");
            //$xx3 = count($applicableProfilesData);
			unset($applicableProfilesArr);
            
            		$poolObj = new NotificationDataPool();
            		$dataAccumulated = $poolObj->getJustJoinData($applicableProfiles);
            //$xx4 = count($dataAccumulated);
            //$mailMsg  = "AppProfiles = $xx1<br>ApplicableProfiles = $xx2<br>AfterProfileData = $xx3<br>FinalData = $xx4";
            //mail("nitish.sharma@jeevansathi.com","Just Join Data",$mailMsg);
            //unset($xx1,$xx2,$xx3,$xx4,$mailMsg);
            		unset($poolObj);
			break;



		  case "MATCHALERT":
			$applicableProfiles=array();
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
			$applicableProfilesArr = array_keys($applicableProfiles);
			$applicableProfilesData = $this->getProfilesData($applicableProfilesArr,$className="newjs_SMS_TEMP_TABLE");
			//print_r($applicableProfilesData);
			unset($applicableProfilesArr);
			if(is_array($applicableProfiles))
			{
				foreach($applicableProfiles as $profileid=>$profiledetails)
				{
					if($applicableProfilesData[$profileid])
					{
						//$loggedInProfileObj = Profile::getInstance('newjs_master',$profileid);
						//$loggedInProfileObj->setDetail($applicableProfilesData[$profileid]);
						//$matchCount[$profileid] = MatchalertNotification::getCount($profileid);
						$matchalertData 	=MatchalertNotification::getCount($profileid);
						$matchCount[$profileid] =$matchalertData['COUNT'];
						$matchProfilePhoto[$profileid] =$matchalertData['PHOTO'];;
					}
				}
				//unset($loggedInProfileObj);
				unset($dppMatchDetails);
				unset($applicableProfilesData);
				$counter = 0;
					foreach($matchCount as $k1=>$v1)
					{
						if($v1>0)
						{
							$dataAccumulated[$counter]['PHOTO_URL'] =$matchProfilePhoto[$k1];
							$dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
							$dataAccumulated[$counter]['COUNT'] = ($v1==1)?"SINGLE":"MUL";
							$dataAccumulated[$counter]['MATCHALERT_COUNT'] = $v1;
							$dataAccumulated[$counter]['COUNT_BELL'] = $v1;
							$counter++;
						}
					}
				unset($applicableProfiles);
				unset($getOtherProfilesData);
				unset($matchedProfiles);
				unset($matchCount);
			}
			break;
		  case "PENDING_EOI":
		    $applicableProfiles=array();
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
			$poolObj = new NotificationDataPool();
            		$dataAccumulated = $poolObj->getPendingInterestData($applicableProfiles);
            		unset($poolObj);
			break;
		  case "ACCEPTANCE":
		  case "PHOTO_REQUEST":
		  case "EOI":
		  case "EOI_REMINDER":
		  case "MESSAGE_RECEIVED":
			$details = $this->getProfilesData($appProfiles,$className="JPROFILE");
			$poolObj = new NotificationDataPool();
			$dataAccumulated = $poolObj->getProfileInstantNotificationData($notificationKey,$appProfiles,$details,$message);
			// print_r($dataAccumulated);
			unset($poolObj);
			break;
                  case "CSV_UPLOAD":
                        $details = $this->getProfilesData($appProfiles,$className="JPROFILE");
                        foreach($appProfiles as $k=>$v)
                        {
                                if($k=="OTHER")
                                        $dataAccumulated[0][$k][0] = $details[$v];
                                else
                                        $dataAccumulated[0][$k] = $details[$v];
                        }
                        $dataAccumulated[0]['COUNT'] = "SINGLE";
                        if($message)
                                $dataAccumulated[0]['MESSAGE_RECEIVED'] = $message;
                        break;
           	case "EOI_DIGEST": //eoi digest notification
           		if($count)
				{
					$details = $this->getProfilesData($appProfiles,"JPROFILE");
					$poolObj = new NotificationDataPool();
					$dataAccumulated = $poolObj->getProfileDigestNotificationData($notificationKey,$appProfiles,$details,$count);
					unset($poolObj);
				}
				else
					$dataAccumulated = null;
            		break;

		  case "PROFILE_VISITOR":
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
			//$details = $this->getProfilesData($applicableProfiles,$className="newjs_SMS_TEMP_TABLE");
            		$poolObj = new NotificationDataPool();
			$applicableProfilesArr =array_keys($applicableProfiles);
			$appSelfProfile =$appProfiles['SELF'];
			//$applicableProfilesNew =array('SELF'=>$applicableProfilesArr[0],'OTHER'=>$appProfiles['OTHER']);
			if(in_array("$appSelfProfile", $applicableProfilesArr)){
				$applicableProfilesNew =array('SELF'=>$appSelfProfile,'OTHER'=>$appProfiles['OTHER']);
	            		$dataAccumulated = $poolObj->getProfileVisitorData($applicableProfilesNew, $applicableProfiles,$message);
			}
            		unset($poolObj);
			break;
                  case "BUY_MEMB":
                        $applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
                        $details = $this->getProfilesData($applicableProfiles,$className="newjs_SMS_TEMP_TABLE");
                        foreach($applicableProfiles as $k=>$v)
                        {
                                if($k=="OTHER")
                                        $dataAccumulated[0][$k][0] = $details[$v];
                                else
                                        $dataAccumulated[0][$k] = $details[$v];
                        }
                        $dataAccumulated[0]['COUNT'] = "SINGLE";
                        if($message)
                                $dataAccumulated[0]['MESSAGE_RECEIVED'] = $message;
                        break;
		  case "ATN":
                    	$applicableProfiles=array();
                        $applicableProfiles = $this->getProfilesApplicableForTriggeredNotification($appProfiles,$notificationKey);
			$counter =0;	
                        if(is_array($applicableProfiles)){
				foreach($applicableProfiles as $profileid=>$value){
					$dataAccumulated[$counter]['SELF']=$value;
					$counter++;
				}
				$dataAccumulated[0]['COUNT'] = "SINGLE";
			}
			unset($applicableProfiles);
			break;
                  case "ETN":
                        $applicableProfiles=array();
                        $applicableProfiles = $this->getProfilesApplicableForTriggeredNotification($appProfiles,$notificationKey);
                        $counter =0;
                        if(is_array($applicableProfiles)){
                                foreach($applicableProfiles as $profileid=>$value){
                                        $dataAccumulated[$counter]['SELF']=$value;
                                        $counter++;
                                }
				$dataAccumulated[0]['COUNT'] = "SINGLE";
                        }
			unset($applicableProfiles);
			break;
                  case "VD":
                        $applicableProfiles=array();
			$applicableProfilesNonNri =array();
			$applicableProfilesNri =array();
                        $applicableProfilesNonNri = $this->getVDProfilesApplicableForNotification($appProfiles);
                        if(count($applicableProfilesNonNri)>0){
                                $applicableProfilesNri = $this->getVDNriProfilesApplicable($appProfiles,$applicableProfilesNonNri);
                        }
                        $applicableProfiles =array_merge($applicableProfilesNonNri,$applicableProfilesNri);
                        $counter =0;
                        if(is_array($applicableProfiles)){
                                foreach($applicableProfiles as $profileid=>$value){
                                        $dataAccumulated[$counter]['SELF']=$value;
                                        $counter++;
                                }
                                $dataAccumulated[0]['COUNT'] = "SINGLE";
                        }
                        unset($applicableProfiles);
                        break;
		  case "MEM_DISCOUNT":
			$applicableProfiles=array();			
			$applicableProfiles =$this->getMembershipDiscountProfilesApplicable($appProfiles);
			$counter =0;
			if(is_array($applicableProfiles)){
				foreach($applicableProfiles as $profileid=>$value){
					$dataAccumulated[$counter]['SELF']=$value;
					$counter++;	
				}
				$dataAccumulated[0]['COUNT'] = "SINGLE";
			}
			unset($applicableProfiles);
			break;
		  case "MEM_EXPIRE_A5":
		  case "MEM_EXPIRE_A10":
		  case "MEM_EXPIRE_A15":
		  case "MEM_EXPIRE_B1":	 
                  case "MEM_EXPIRE_B5":
                        $applicableProfiles=array();
                        $poolObj = new NotificationDataPool();
                        $applicableProfiles = $poolObj->getMembershipProfilesForNotification($appProfiles);
                        $dataAccumulated = $poolObj->getRenewalReminderData($applicableProfiles);
                        break;
		case "PHOTO_UPLOAD":
			$applicableProfiles=array();
                        $details = $this->getProfilesData($appProfiles,$className="JPROFILE");
                        foreach($appProfiles as $k=>$v)
                        {
                                if($k=="OTHER")
                                        $dataAccumulated[0][$k][0] = $details[$v];
                                else
                                        $dataAccumulated[0][$k] = $details[$v];
                        }
                        $dataAccumulated[0]['COUNT'] = "SINGLE";
                        break;
        	case "FILTERED_EOI":
		    $applicableProfiles=array();
			$applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
			if(is_array($applicableProfiles))
			{
				$contactRecordsObj = new ContactsRecords;
				foreach($applicableProfiles as $profileid=>$profiledetails){
					$cntArr = $contactRecordsObj->getContactsCount(array("RECEIVER"=>$profileid,"TYPE"=>ContactHandler::INITIATED),"FILTERED",1);
					if(is_array($cntArr)){
						$cp=0;
						foreach($cntArr as $ck=>$cv){
							if($cv['FILTERED']=='Y'&& $cv["TIME1"] == 0)
								$cp = $cp+$cv['COUNT'];
						}
						$eoiCount[$profileid] = $cp;
						unset($cp);
						unset($cntArr);
					}
					if($eoiCount[$profileid]==0){
						unset($applicableProfiles[$profileid]);
					}
				}
				// New logic
				foreach($applicableProfiles as $profileid=>$profiledetails)
				{
				    $condition['WHERE']['IN']["RECEIVER"] = $profileid;
				    $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
				    $condition["WHERE"]["IN"]["COUNT"]    = 1;
				    $condition["WHERE"]["IN"]["FILTERED"] = 'Y';
				    $condition["LIMIT"] 		  = "0,1";
				    $condition["ORDER"] 		  ='TIME';
				    if($eoiCount[$profileid]>0){
					$eoiProfiles[$profileid] =$contactRecordsObj->getContactedProfileArray($profileid,$condition,$skipArray);
				    }
        			}
				//print_r($eoiProfiles);
				//additional condition for others details req in future
				/*if(!in_array($notificationKey, NotificationEnums::$staticContentNotification))
            			}*/
			        if(is_array($eoiProfiles))
				{
				    foreach($eoiProfiles as $k1=>$v1){
					foreach($v1 as $k2=>$v2){
					    $otherProfiles[$k1] = $k2;
					    break;	
					}
				    }
				}
				$counter = 0;
				if(is_array($applicableProfiles)){
					foreach($applicableProfiles as $k1=>$v1){
						if($eoiCount[$k1]>0){
			        	                $dataAccumulated[$counter]['ICON_PROFILEID']=$otherProfiles[$k1];
							$dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
							$dataAccumulated[$counter]['COUNT'] = ($eoiCount[$k1]==1)?"SINGLE":(($eoiCount[$k1]==2)?"DOUBLE":"MUL");
							$dataAccumulated[$counter]['EOI_COUNT'] = ($eoiCount[$k1]>2)?($eoiCount[$k1]-2):0;
							$dataAccumulated[$counter]['COUNT_BELL'] = $eoiCount[$k1];
							$counter++;
						}
					}
				}
				unset($otherProfiles);
				unset($applicableProfiles);
				unset($getOtherProfilesData);
				unset($eoiProfiles);
				unset($eoiCount);
			}
			break;
        case "CONTACTS_VIEWED_BY":
            $applicableProfiles=array();
            $applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
            if(is_array($applicableProfiles)){
                $viewContactsLog = new JSADMIN_VIEW_CONTACTS_LOG("newjs_local111");
                $endDt = date("Y-m-d H:i:s");
                $startDt = date("Y-m-d H:i:s", strtotime('-1 day 1 sec', strtotime($endDt)));
                $viewedContactData = $viewContactsLog->getViewedContact($applicableProfiles, $startDt, $endDt);
                if($viewedContactData){
                    foreach($viewedContactData as $viewedId => $viewerArr){
                        $notificationProfiles[$viewedId] = count($viewerArr);
                    }
                    $counter = 0;
                    foreach($notificationProfiles as $profile => $count){
                        $dataAccumulated[$counter]['SELF'] = $applicableProfiles[$profile];
                        $dataAccumulated[$counter]['COUNT'] = ($count == 1)?"SINGLE":(($count == 2)?"DOUBLE":"MUL");
                        $dataAccumulated[$counter]['MATCH_COUNT'] = $count;
                        $dataAccumulated[$counter]['COUNT_BELL'] = $count;
                        $counter++;
                    }
                    unset($notificationProfiles);
                }
                unset($viewedContactData);
            }
            unset($applicableProfiles);
            break;
        case "CONTACT_VIEWS":  //notification for remaining contact views in subscription
        	$applicableProfiles=array();
            $applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey);
            if(is_array($applicableProfiles))
            {
            	$applicableProfilesIdArr = array_keys($applicableProfiles);
            	
            	//get remaining contacts for profiles
                $contactObj  = new jsadmin_CONTACTS_ALLOTED("newjs_masterRep");
                $contactViewsData = $contactObj->getContactViewsDataForProfiles($applicableProfilesIdArr);
                unset($applicableProfilesIdArr);
                unset($contactObj);
                
                $curDate = date('Y-m-d');
                if(is_array($contactViewsData))
                {
	                //get current membership details of only paid profiles
	                $profileIdArr = array_keys($contactViewsData);
	                $serviceObj = new billing_SERVICE_STATUS("newjs_masterRep");
	                $membershipData = $serviceObj->getLatestActiveMemInfoForProfiles($profileIdArr);
	                unset($profileIdArr);
	                unset($serviceObj);
            	}
            	else
            		$membershipData = null;
                if(is_array($membershipData))
                {
                    $counter = 0;
                    //schedule notification only at 35 days interval from latest service activation date
                    foreach($membershipData as $profileid => $details){
                    	$remainingContacts = $contactViewsData[$profileid]['REMAINING'];
                    	if($remainingContacts > 0 && $this->filterProfileServiceActivationBased($membershipData[$profileid],$curDate,$notificationKey)==true)
                        {
                   
                        	$dataAccumulated[$counter]['SELF'] = $applicableProfiles[$profileid];
                        	$dataAccumulated[$counter]['COUNT'] = "SINGLE";
                        	$dataAccumulated[$counter]['CONTACTS_COUNT'] = $remainingContacts;
                        	$dataAccumulated[$counter]['COUNT_BELL'] = 1;
                        	$counter++;
                        }
                    }
                }
                unset($membershipData);
                unset($contactViewsData);
            }
            unset($applicableProfiles);
        	break;
    	case "INCOMPLETE_SCREENING":
			// single profile should be passed here
			$details = $this->getProfilesData(array($appProfiles['SELF']),$className="JPROFILE");
			$counter = 0;
			foreach($details as $key=>$val){
				$dataAccumulated[$counter]['SELF']  = $details[$key];
				$dataAccumulated[$counter]['COUNT'] = "SINGLE";
			}
			// print_r($dataAccumulated);
			break;
        case "MATCH_OF_DAY":
            $applicableProfiles=array();
            $applicableProfiles = $this->getProfileApplicableForNotification($appProfiles,$notificationKey,"JPROFILE");
            $notificationDataPoolObj = new NotificationDataPool();
            $dataAccumulated = $notificationDataPoolObj->getMatchOfDayData($applicableProfiles);
            //print_r($dataAccumulated);
            
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
				  // print_r($completeNotificationInfo); die;
				  $notificationDataPoolObj = new NotificationDataPool();
				  if($notificationKey=='MATCHALERT')	
				  	$completeNotificationInfo[$counter]["PHOTO_URL"] =$dataPerNotification['PHOTO_URL'];
				  else
			                $completeNotificationInfo[$counter]["PHOTO_URL"] = $notificationDataPoolObj->getNotificationImage($completeNotificationInfo[$counter]["PHOTO_URL"],$dataPerNotification['ICON_PROFILEID']);
				  $completeNotificationInfo[$counter]['SELF'] = $dataPerNotification['SELF'];
				  //$completeNotificationInfo[$counter]['MSG_ID']=time().rand(0,99);
				  $completeNotificationInfo[$counter]['MSG_ID']=rand(0,99).time().rand(0,99).rand(0,99).rand(0,9);
				  $completeNotificationInfo[$counter]['OTHER_PROFILE_CHECKSUM'] = JsCommon::createChecksumForProfile($dataPerNotification['OTHER'][0]['PROFILEID']);
                  
                  $this->checkNotificationExtension($completeNotificationInfo[$counter]["PHOTO_URL"],$notificationKey,$dataPerNotification['ICON_PROFILEID']);
				  $counter++;
			  }
		  }
		  unset($notificationId);
		  unset($dataAccumulated);
		  return $completeNotificationInfo;
	  }
  }
  public function generateNotification($notificationId, $notificationKey,$dataPerNotification)
  {
	  $notifications = $this->getNotifications();
	  //print_r($notifications);
	  $variableValues = array();
	  if(is_array($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE']) && $notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE'])
		{  
			foreach($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['VARIABLE'] as $k=>$tokenVariable)
			$variableValues[$tokenVariable] = $this->getVariableValue($tokenVariable, $dataPerNotification);
		}
	  if($notificationKey =='VD'){	
          	foreach($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['VARIABLE'] as $k=>$tokenVariable)
                	$variableValuesTitle[$tokenVariable] = $this->getVariableValue($tokenVariable, $dataPerNotification);
	  }	
	  if($variableValues || in_array($notificationKey,NotificationEnums::$staticContentNotification))
	  {
		  if($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['flagPosition']=="STATIC")
			$finalNotificationMessage = $this->mergeNotification($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['STATIC'],$variableValues);
		  else
			$finalNotificationMessage = $this->mergeNotification($variableValues, $notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP']['STATIC']);

		  if($notificationKey =='VD'){	
                  	if($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['flagPosition']=="STATIC")
                        	$finalNotificationMessageTitle = $this->mergeNotification($notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUP_TITLE']['STATIC'],$variableValuesTitle);
                  	else
                        	$finalNotificationMessageTitle = $this->mergeNotification($variableValuesTitle, $notifications[$notificationKey][$notificationId]['NOTIFICATION_BREAKUPI_TITLE']['STATIC']);
		  }
	
		  $completeNotificationInfo["USERNAME"] = $this->getVariableValue("USERNAME_SELF", $dataPerNotification);
		  $completeNotificationInfo = $notifications[$notificationKey][$notificationId];
		  $completeNotificationInfo['NOTIFICATION_MESSAGE'] = $finalNotificationMessage;
		  $completeNotificationInfo['NOTIFICATION_MESSAGE_TITLE'] = $finalNotificationMessageTitle;	
		  $completeNotificationInfo['COUNT'] = $dataPerNotification['COUNT_BELL'];
		  // print_r($completeNotificationInfo);
		  return $completeNotificationInfo;
	  }
  }
  public function getProfilesData($profiles,$className="JPROFILE")
  {
	  if(is_array($profiles))
	  {
		  $varArray['PROFILEID'] = implode(",",$profiles);
		  $smsTempTableObj = new $className;
		  $profiledetails = $smsTempTableObj->getArray($varArray,'',"",$fields="PROFILEID,USERNAME,SUBSCRIPTION,GENDER,AGE,CASTE,CITY_RES,COUNTRY_RES");
	  }
	  if(is_array($profiledetails))
	  {
		  foreach($profiledetails as $k=>$v)
			  $details[$v['PROFILEID']] = $v;
	  }
	  unset($profiledetails);
	  return $details;	
  }
  public function matchNotificationKeyData($notificationKey,$accumulatedDataForMessage)
  {
	$notifications = $this->getNotifications();
	foreach($notifications[$notificationKey] as $k=> $criteria)
	{
		$subscription = explode(",",$criteria["SUBSCRIPTION"]);//if($criteria['SUBSCRIPTION']);
		foreach ($subscription as $key => $value)
			$temp[$value][$criteria["GENDER"]][$criteria["COUNT"]] = $criteria["ID"];
	}
	$count = ($accumulatedDataForMessage['COUNT'])?$accumulatedDataForMessage['COUNT']:"SINGLE";
        $subscription = (strstr($accumulatedDataForMessage['SELF']["SUBSCRIPTION"],"F"))?"P":"F";
        if ($temp[$subscription][$accumulatedDataForMessage['SELF']["GENDER"]][$count])
            $mess = $temp[$subscription][$accumulatedDataForMessage['SELF']["GENDER"]][$count];
        elseif ($temp[$subscription]["A"][$count])
            $mess = $temp[$subscription]["A"][$count];
        elseif ($temp["A"][$saccumulatedDataForMessage['SELF']["GENDER"]][$count])
            $mess = $temp["A"][$accumulatedDataForMessage['SELF']["GENDER"]][$count];
        else
            $mess = $temp["A"]["A"][$count];
        return $mess;
  }   
  public function getProfileApplicableForNotification($profiles,$notificationKey,$className="")
  {

	  unset($applicableProfiles);
	  $notifications = $this->getNotifications();
	  foreach($notifications[$notificationKey] as $k=>$notificationKeyDetails)
		$timeCriteria = $notificationKeyDetails['TIME_CRITERIA'];
	  unset($notifications);
      if($className == "JPROFILE"){
          $smsTempTableObj = new JPROFILE("crm_slave");
      }
      else{
        $smsTempTableObj = new newjs_SMS_TEMP_TABLE("newjs_masterRep");
      }
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
  public function getProfilesApplicableForTriggeredNotification($profiles,$notificationKey)
  {
  	  unset($applicableProfiles);
	  unset($profilesArr);
	  unset($profiledetails);

	// filter check for already sent ATN/ETN notification	
	  $varArray['NOTIFICATION_KEY'] =$notificationKey;
	  $varArray['SENT'] 		='Y';
	  if($notificationKey=='ATN')
	  	$notificationLogObj	=new MOBILE_API_NOTIFICATION_LOG_ATN("newjs_local111");          		           	  	  		else if($notificationKey=='ETN')
		$notificationLogObj     =new MOBILE_API_NOTIFICATION_LOG_ETN("newjs_local111");
	  else
		$notificationLogObj     =new MOBILE_API_NOTIFICATION_LOG("newjs_local111");
	  $profilesOld			=$notificationLogObj->getNotificationProfiles();
	  if(is_array($profilesOld))
		  $profilesArr 		=array_diff($profiles, $profilesOld);
	  else
		  $profilesArr		=$profiles;
	  $profilesArr =array_values($profilesArr);			
          unset($varArray);

	// filter check in ETN notification, CASE: if profile is already considered for ATN  on the same day. 
	  /*if($notificationKey=='ETN'){	
	  	$scheduledAppNotificationObj  =new MOBILE_API_SCHEDULED_APP_NOTIFICATIONS();					
          	$varArray['NOTIFICATION_KEY'] ='ATN';
          	$profilesAtn                  =$scheduledAppNotificationObj->getArray($varArray);
		if(is_array($profilesAtn)){	
			foreach($profilesAtn as $key=>$value)
				$profilesAtnNew[] =$value['PROFILEID'];
          		$profilesNew =@array_diff($profilesArr, $profilesAtnNew);
			$profilesNew =array_values($profilesNew);
			unset($profilesArr);
			$profilesArr =$profilesNew;
		}
		unset($varArray);
	  }*/

	// get PROFILE data
          if(is_array($profilesArr)){
		  $smsTempTableObj 	= new newjs_SMS_TEMP_TABLE("newjs_masterRep");
		  $varArray['PROFILEID']=implode(",",$profilesArr);
		  $fields		='PROFILEID,USERNAME,SUBSCRIPTION,GENDER';
                  $todayDate      	=date("Y-m-d");
                  $greaterDt   		=date("Y-m-d",strtotime("$todayDate -89 days"));
		  $greaterThan['ENTRY_DT']=$greaterDt." 00:00:00";	 
		  $lessThan['ENTRY_DT']	=$todayDate." 23:59:59";
		  $profiledetails	= $smsTempTableObj->getArray($varArray,'',$greaterThan,$fields,$lessThan);
          }
	// filter Paid check 	
          if(is_array($profiledetails)){
                  foreach($profiledetails as $k=>$v){
			if((strstr($v['SUBSCRIPTION'],"F")=="") && (strstr($v['SUBSCRIPTION'],"D")=="")) 
 	                       	$details[$v['PROFILEID']] = $v;
		  }
          }
	// First Acceptance/Eoi filter 
	  if(is_array($details)){
	  	foreach($details as $profileid=>$value){
			$dbName		=JsDbSharding::getShardNo($profileid,'Y');
			$messageLogObj 	=new NEWJS_MESSAGE_LOG($dbName);
			if($notificationKey=='ATN'){
				$acceptanceCnt 	=$messageLogObj->getFirstAcceptanceCount($profileid);
				if($acceptanceCnt>='2')
					 $applicableProfiles[$profileid] =$value;
			}
			elseif($notificationKey=='ETN'){
				$eoiCnt  =$messageLogObj->getFirstEoiCount($profileid);
				if($eoiCnt>='3')
					$applicableProfiles[$profileid] =$value;
			}
         	 }
	  }
	// return eligible profiles
	  if($applicableProfiles)   	
		  return $applicableProfiles;
          return false;
  }
  public function getMembershipDiscountProfilesApplicable($profilesArr)
  {
  	//print_r($profilesArr);
        if(is_array($profilesArr)){
		unset($applicableProfiles);
		$todayDate		=date("Y-m-d");
		$cashDiscountActive	=false;
		$replaceStr		=array('<','>','/','strong');
		$memHandlerObj 		=new MembershipHandler();
		$vdObj 			=new VariableDiscount();
		$discountOfferLogObj 	=new billing_DISCOUNT_OFFER_LOG('newjs_masterRep');
		$renewalDisObj 		=new billing_RENEWAL_DISCOUNT('newjs_masterRep');
		$cashDiscountArray	=$discountOfferLogObj->getActiveOfferDetails();

		if(is_array($cashDiscountArray)){
			$cashDiscountStartDate =$cashDiscountArray['START_DT'];
			if($cashDiscountStartDate == $todayDate)
				$cashDiscountActive =true;		
		} 
		if(is_array($profilesArr)){	
		        $profilesStr           	=implode(",",$profilesArr);
	               	$discountDetArr        	=$vdObj->getVDProfilesActivatedForDate($profilesStr);
			if(is_array($discountDetArr)){	
				foreach($discountDetArr as $profileid=>$value){
					$vdProfiles[]           =$profileid;
					if($value['SDATE']!=$todayDate)
						continue;
					$discount		=$value['DISCOUNT'];
					$messageArr 		=$memHandlerObj->getOCBTextMessage($profileid, 'VD', $discount, $value['EDATE']); 
					$dataArr['DISCOUNT']    =$discount;
					$dataArr['UPTO']        =$messageArr['discountText'];
					$dataArr['MESSAGE']	=str_replace($replaceStr,'',$messageArr['top'].", ".$messageArr['bottom']);
					$dataArr['PROFILEID']   =$profileid; 			
					$applicableProfiles[$profileid] =$dataArr;					
				}
				//print_r($applicableProfiles);die;
			}
			unset($discountDetArr);
		}
		if($cashDiscountActive){
			$profilesStr =implode(",",$profilesArr);
			$renewalProfiles =$renewalDisObj->getRenewalProfiles($profilesStr);		
			if(is_array($renewalProfiles)){
				$profilesArr =array_diff($profilesArr, $renewalProfiles);
				$profilesArr =array_values($profilesArr);
			}
			if(count($vdProfiles)>0){
				$profilesArr =array_diff($profilesArr, $vdProfiles);
				$profilesArr =array_values($profilesArr);
				unset($vdProfiles);
			}
			$applicableProfilesArr =$this->getProfilesData($profilesArr, $className="newjs_SMS_TEMP_TABLE");
        		if(is_array($applicableProfilesArr)){
		                foreach($applicableProfilesArr as $profileid=>$v){
        	                	$subscription   =$v['SUBSCRIPTION'];
        	                	$activated      =$v['ACTIVATED'];
        	                	$subArr         =@explode(",",$subscription);
        	                	if(in_array("F",$subArr) || in_array("D",$subArr) || $activated!='Y')
        	                        	continue;
        	                	$profilesArrNew[] =$profileid;
        	        	}
			}	
			$cashDiscount 		=$vdObj->getCashDiscount();
			$cashDiscountExpDt 	=$cashDiscountArray['END_DT'];
			$messageArr     	=$memHandlerObj->getOCBTextMessage('1', 'CASH', $cashDiscount,$cashDiscountExpDt);
			$message		=str_replace($replaceStr,'',$messageArr['top'].", ".$messageArr['bottom']);
			$dataArr		=array('DISCOUNT'=>$cashDiscount,'UPTO'=>$messageArr['discountText'],'MESSAGE'=>$message);
			foreach($profilesArrNew as $key=>$profileid){
				$dataArr['PROFILEID']   =$profileid;
				$applicableProfiles[$profileid] =$dataArr;
			}
		}
		//print_r($applicableProfiles);die;
		return $applicableProfiles;
	}
  }	
  public function getVDProfilesApplicableForNotification($profiles)
  {
        unset($applicableProfiles);
	unset($profilesArr);
	unset($profilesNewArr);
	unset($discountDetArr);
	//$profiles =array(223,243);	//test

	// filter to get vd-sms eligible profiles
	if(is_array($profiles)){
		$tempSmsObj 		=new newjs_TEMP_SMS_DETAIL();
		$valueArr['PROFILEID']  =@implode(",",$profiles);
		$valueArr['SMS_KEY']    ='VD1,VD2';
		$profilesSmsArr		=$tempSmsObj->getArray($valueArr,'','','PROFILEID');
		if(count($profilesSmsArr)>0){
			foreach($profilesSmsArr as $key1=>$val1)
				$profilesNewArr[] =$val1['PROFILEID'];
		}
	}
	// filter-in last 7days logged-in app profiles
	if(is_array($profilesNewArr)){
		$loginTrackingObj     	=new MIS_LOGIN_TRACKING('newjs_local111');
		$profilesNewStr        	=@implode(",",$profilesNewArr);
		$profilesArr  		=$loginTrackingObj->getLast7DaysLoginProfiles($profilesNewStr);
	}
	// get VD PROFILE discount details 
	$applicableProfiles =$this->getVdDetails($profilesArr);

	//update sms send status
        if(is_array($profilesArr)){
		$profilesStr =implode(",",$profilesArr);	
		$tempSmsObj->updateSentForVD($profilesStr);
	}	 				

        // return eligible profiles
        if($applicableProfiles)
        	return $applicableProfiles;
        return false;
  }
  public function getVDNriProfilesApplicable($profilesArr,$applicableProfilesNonNri)
  {
        unset($applicableProfiles);
	$nonNriArr =array();
	$todayDate =date("Y-m-d");

        //$profilesArr =array(243,286);    //test
	foreach($applicableProfilesNonNri as $key=>$val){
		$nonNriArr[] =$val['PROFILEID'];
	}
	unset($applicableProfilesNonNri);
	$profilesArr =array_diff($profilesArr,$nonNriArr);
	$profilesArr =array_values($profilesArr);

        // Negative Treatment check
        if(is_array($profilesArr)){
                $negTreatObj = new INCENTIVE_NEGATIVE_TREATMENT_LIST('newjs_masterRep');
                $parameters['FLAG_OUTBOUND_CALL'] = 'N';
                $profilesArr =$negTreatObj->removeNegativeIdsFromList($parameters, $profilesArr);
        }
        // filter-in last 15days logged-in app profiles
        if(is_array($profilesArr)){
                $loginTrackingObj       =new MIS_LOGIN_TRACKING('newjs_local111');
                $profilesNewStr         =@implode(",",$profilesArr);
                $date15DaysBack         =date("Y-m-d",strtotime("$todayDate -14 days"))." 00:00:00";
                $channelStr = "'A','I'";
                $profilesArr            =$loginTrackingObj->getLastLoginProfilesForDate($profilesNewStr, $date15DaysBack, $channelStr);
        }
        // get profile details  
        if(is_array($profilesArr)){
                $smsTempTableObj      = new newjs_SMS_TEMP_TABLE("newjs_masterRep");
                $varArray['PROFILEID']=implode(",",$profilesArr);
                $fields               ='PROFILEID,SUBSCRIPTION,GENDER,ISD,AGE';
                $profiledetails       =$smsTempTableObj->getArray($varArray,'','',$fields);
        }
        // Paid check
	unset($profilesArr);
        if(is_array($profiledetails)){
                  foreach($profiledetails as $k=>$v){
                        $subscription 	=$v['SUBSCRIPTION'];
                        $isd 		=$v['ISD'];
                        $genderVal 	=$v['GENDER'];
                        $ageVal 	=$v['AGE'];
                        $subArr 	=@explode(",",$subscription);
                        if($genderVal=='M' && $ageVal<=23)
	                       continue;
                        if(in_array("F",$subArr) || in_array("D",$subArr))
                                continue;
			if($isd && ($isd==91 || $isd=='+91' || $isd=='091'))
                                continue;
                        $profilesArr[] = $v['PROFILEID'];
                }
        }
	$applicableProfiles =$this->getVdDetails($profilesArr);
	unset($profilesArr);

        // return eligible profiles
        if($applicableProfiles)
                return $applicableProfiles;
        return false;
  }
  public function getVdDetails($profilesArr)
  {
	$applicableProfiles =array();
        if(is_array($profilesArr)){
                $variableDiscountObj            =new billing_VARIABLE_DISCOUNT();
                $profilesStr                    =implode(",",$profilesArr);
                $discountDetArr                 =$variableDiscountObj->getDiscount($profilesStr);
                $variableDisObj                 =new VariableDiscount();
                foreach($discountDetArr as $key=>$val){
                        $edate                  =$val['EDATE'];
                        $discount               =$val['DISCOUNT'];
                        $dataArr['EDATE']       =date("d-M", strtotime($edate));
                        $dataArr['PROFILEID']   =$key;
                        $dataArr['DISCOUNT']    =$discount;
			$discountLimitTextVal   =$variableDisObj->getVdDisplayText($key,'small');
			$dataArr['UPTO']	=$discountLimitTextVal;
                        $applicableProfiles[$key] =$dataArr;
                        unset($uptoSet);
                        unset($dataArr);
                }
		return $applicableProfiles;
	}	
  }

  private function filterProfileServiceActivationBased($membershipData,$curDate,$notificationKey)
  {
  	$matchDateOnly = (new dateTime($membershipData['ACTIVATED_ON']))->format('Y-m-d');
  	if($curDate!=$matchDateOnly &&(CommonUtility::dateDiff($curDate,$matchDateOnly) % NotificationEnums::$registrationOffsetForNotification[$notificationKey]==0))
  	{ 
  		return true;
  	}
  	else
  		return false;
  }
  
  public function checkNotificationExtension($url,$notificationKey,$profileid){
      $validPicArray = array('P','D','O');
      if(!in_array($url, $validPicArray)){
          $validExtensionArr = array("jpg", "jpeg", "png");
          $imgname = "";
          $ext = explode(".",$url);
          $l = end($ext);
          if(!in_array($l,$validExtensionArr)){
              /*
              $to = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com";
              $cc = "nitishpost@gmail.com";
              $sub = "Invalid Extension for notification key $notificationKey";
              $from = "info@jeevansathi.com";
              $msg = "Url Generated $url for $profileid for $notificationKey";
              SendMail::send_email($to, '', $sub, $from,$cc);
              */
              $date = date('Y-m-d');
              $msg = "Url:$url, Key:$notificationKey, pid: $profileid\n";
              file_put_contents(sfConfig::get("sf_upload_dir")."/wrongImageUrlNew".$date.".txt",$msg,FILE_APPEND);
          }
      }
      unset($validPicArray);
      unset($validExtensionArr);
      unset($ext);
  }
}
?>
