<?php
class NotificationDataPool
{
	/*fetch profiles data
	* @params : $profiles,$className="JPROFILE",$db=""
	* @return : $details
	*/
	public function getProfilesData($profiles,$className="JPROFILE",$db="")
    {
        if(is_array($profiles))
        {
            $varArray['PROFILEID'] = implode(",",$profiles);
            $smsTempTableObj = new $className($db);
            $profiledetails = $smsTempTableObj->getArray($varArray,'',"",$fields="*");
        }
        if(is_array($profiledetails))
        {
            foreach($profiledetails as $k=>$v)
                $details[$v['PROFILEID']] = $v;
        }
        unset($profiledetails);
        return $details;	
    }

    /*fetch agents data
	* @params : $agents,$className="jsadmin_PSWRDS",$db=""
	* @return : $details
	*/
    public function getAgentsData($agents,$className="jsadmin_PSWRDS",$db="")
    {
        if(is_array($agents))
        {
            $smsTempTableObj = new $className($db);
            $agentDetails = $smsTempTableObj->getArray("","RESID","*",null,null,implode(",",$agents));
        }
        if(is_array($agentDetails))
        {
            foreach($agentDetails as $k=>$v)
                $details[$v['RESID']] = $v;
        }
        unset($agentDetails);
        return $details;    
    }
    
    /*fetch instant agent notifications pool for FSO
    * @inputs: $browserProfilesArr
    *@return : $dataAccumulated
    */
    public function getAgentInstantNotificationsPool($browserProfilesArr)
    {
		$profileDetails = $this->getProfilesData(array($browserProfilesArr["OTHER"]),"JPROFILE"); 
		$agentDetails = $this->getAgentsData(array($browserProfilesArr["SELF"]),"jsadmin_PSWRDS","newjs_masterRep");

		if($profileDetails && $agentDetails)
		{
			if(is_array($browserProfilesArr) && $browserProfilesArr)
			{	
				foreach($browserProfilesArr as $k=>$v)
				{
				    if($k=="OTHER")
				        $dataAccumulated[0][$k][0] = $profileDetails[$v];
				    else
				        $dataAccumulated[0][$k] = $agentDetails[$v]; 
				}
				$dataAccumulated[0]['COUNT'] = "SINGLE";
			}
		}
		return $dataAccumulated;
    }

  public function getJustJoinData($applicableProfiles)
  {
    if(is_array($applicableProfiles))
    {
        foreach($applicableProfiles as $profileid=>$profiledetails)
        {
            //if($applicableProfilesData[$profileid])
            {
                $loggedInProfileObj = Profile::getInstance('newjs_master',$profileid);
                $loggedInProfileObj->setDetail($profiledetails);
                $dppMatchDetails[$profileid] = SearchCommonFunctions::getJustJoinedMatches($loggedInProfileObj);
                $matchCount[$profileid] = $dppMatchDetails[$profileid]['CNT'];
                if($matchCount[$profileid]>0)
                    $matchedProfiles[$profileid] = $dppMatchDetails[$profileid]['PIDS'];
            }
        }
        unset($loggedInProfileObj);
        unset($dppMatchDetails);
        unset($applicableProfilesData);
        if(is_array($matchedProfiles))
        {
            foreach($matchedProfiles as $k1=>$v1)
            {
                if(is_array($v1))
                foreach($v1 as $k2=>$v2)
                    $otherProfiles[] = $v2;
            }
        }
        if(is_array($otherProfiles))
        {
            $getOtherProfilesData = $this->getProfilesData($otherProfiles,$className="newjs_SMS_TEMP_TABLE","newjs_masterRep");
        }
        unset($otherProfiles);
        $counter = 0;
        if(is_array($matchedProfiles))
        {
            foreach($matchedProfiles as $k1=>$v1)
            {
                if($matchCount[$k1]>0)
                {
                    $dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
                    if(is_array($matchedProfiles[$k1]))
                    foreach($matchedProfiles[$k1] as $k2=> $v2)
                    {
                        if(count($dataAccumulated[$counter]['OTHER'])>=2)
                            break;
                        if($getOtherProfilesData[$v2]){
                            $dataAccumulated[$counter]['OTHER'][]=$getOtherProfilesData[$v2];
                            if(!$dataAccumulated[$counter]['ICON_PROFILEID']){
                                $dataAccumulated[$counter]['ICON_PROFILEID']=$getOtherProfilesData[$v2]["PROFILEID"];
                            }
                        }
                    }
                    $dataAccumulated[$counter]['COUNT'] = ($matchCount[$k1]==1)?"SINGLE":"MUL";
                    $dataAccumulated[$counter]['MATCH_COUNT'] = $matchCount[$k1];
                    $dataAccumulated[$counter]['COUNT_BELL'] = $matchCount[$k1];
                $counter++;
                }
            }
        }
        unset($applicableProfiles);
        unset($getOtherProfilesData);
        unset($matchedProfiles);
        unset($matchCount);
    }
    return $dataAccumulated;
  }
  
  public function getPendingInterestData($applicableProfiles)
  {
    if(is_array($applicableProfiles))
    {
        $contactRecordsObj = new ContactsRecords;

        foreach($applicableProfiles as $profileid=>$profiledetails)
        {
            $condition['WHERE']['IN']["RECEIVER"] = $profileid;
            $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED; 
            $condition["WHERE"]["IN"]["COUNT"]    = 1;
            $condition["WHERE"]["NOT_IN"]["FILTERED"]    = 'Y';
            $condition["LIMIT"] = "0,10";//safe in case if some of the profiles are not valid and their data is not present in sms_temp_table
	    $condition["ORDER"]			  ='TIME';			
            $cntArr = $contactRecordsObj->getContactsCount(array("RECEIVER"=>$profileid,"TYPE"=>ContactHandler::INITIATED,"COUNT"=>1),"FILTERED",1);
            if(is_array($cntArr))
            {
                $cp=0;
                foreach($cntArr as $ck=>$cv)
                {
                    if($cv['FILTERED']!='Y'&& $cv["TIME1"] == 0)
                        $cp = $cp+$cv['COUNT'];
                }
                $eoiCount[$profileid] = $cp;
                unset($cp);
                unset($cntArr);
            }
            if($eoiCount[$profileid]>0)
            {
                $eoiProfiles[$profileid] = $contactRecordsObj->getContactedProfileArray($profileid,$condition,$skipArray);
            }
        }
        if(is_array($eoiProfiles))
        {
            foreach($eoiProfiles as $k1=>$v1)
            {
                foreach($v1 as $k2=>$v2)
                    $otherProfiles[] = $k2;
            }
        }
        if(is_array($otherProfiles))
                        {
            $getOtherProfilesData = $this->getProfilesData($otherProfiles,$className="newjs_SMS_TEMP_TABLE","newjs_masterRep");
                        }
        unset($otherProfiles);
        $counter = 0;
        if(is_array($eoiProfiles))
        {
            foreach($eoiProfiles as $k1=>$v1)
            {
                if($eoiCount[$k1]>0)
                {
                    $dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
                    foreach($eoiProfiles[$k1] as $k2=> $v2)
                    {
                        if(count($dataAccumulated[$counter]['OTHER'])>=2)
                            break;
                        if($getOtherProfilesData[$k2]){
                            $dataAccumulated[$counter]['OTHER'][]=$getOtherProfilesData[$k2];
                            if(!$dataAccumulated[$counter]['ICON_PROFILEID']){
                                $dataAccumulated[$counter]['ICON_PROFILEID']=$getOtherProfilesData[$k2]["PROFILEID"];
                            }
                        }
                    }
                    $dataAccumulated[$counter]['COUNT'] = ($eoiCount[$k1]==1)?"SINGLE":(($eoiCount[$k1]==2)?"DOUBLE":"MUL");
                    $dataAccumulated[$counter]['EOI_COUNT'] = ($eoiCount[$k1]>2)?($eoiCount[$k1]-2):0;
                    $dataAccumulated[$counter]['COUNT_BELL'] = $eoiCount[$k1];
                    $counter++;
                }
            }
        }
        unset($applicableProfiles);
        unset($getOtherProfilesData);
        unset($eoiProfiles);
        unset($eoiCount);
    }  
    return $dataAccumulated;
  }
  
  public function getProfileVisitorData($applicableProfiles, $details, $message)
  {
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
        if($applicableProfiles["OTHER"]){
            $dataAccumulated[0]['ICON_PROFILEID'] = $applicableProfiles["OTHER"];
        }
        unset($applicableProfiles);
        unset($details);
        unset($message);
        //print_r($dataAccumulated);die;
        return $dataAccumulated;
  }

    /*function to get notification data pool for instant JSPC/JSMS notifications
    @inputs: $notificationKey,$profilesArr,$details,$message
    @output : $dataAccumulated
    */
    public function getProfileInstantNotificationData($notificationKey,$profilesArr,$details,$message="")
    {
        foreach($profilesArr as $k=>$v)
        {
            if($k=="OTHER")
                $dataAccumulated[0][$k][0] = $details[$v];
            else
                $dataAccumulated[0][$k] = $details[$v]; 
        }
        $dataAccumulated[0]['COUNT'] = "SINGLE";           
        
        if($message)
            $dataAccumulated[0]['MESSAGE_RECEIVED'] = $message;

        $dataAccumulated[0]['ICON_PROFILEID']=$profilesArr["OTHER"];
        if($notificationKey == 'CHAT_MSG' || $notificationKey == "CHAT_EOI_MSG"){
            $dataAccumulated[0]['OTHER_PROFILEID']=$profilesArr["OTHER"];
            $dataAccumulated[0]['OTHER_USERNAME']=$details[$profilesArr["OTHER"]]["USERNAME"];
        }
        unset($profilesArr);
        unset($details);
        return $dataAccumulated;
    }

    /*function to get notification data pool for digest notifications
    @inputs: $notificationKey,$profilesArr,$details,$count=""
    @output : $dataAccumulated
    */
    public function getProfileDigestNotificationData($notificationKey,$profilesArr,$details,$count="")
    {
        foreach($profilesArr as $k=>$v)
        {
            if($k=="OTHER")
                $dataAccumulated[0][$k][0] = $details[$v];
            else
                $dataAccumulated[0][$k] = $details[$v]; 
        }
        $dataAccumulated[0]['COUNT'] = "MULTIPLE";
        
        if($count)
            $dataAccumulated[0]['EOI_COUNT'] = $count;
        if($profilesArr["OTHER"])
            $dataAccumulated[0]['ICON_PROFILEID']=$profilesArr["OTHER"];
        unset($profilesArr);
        unset($details);
        return $dataAccumulated;
    }
  
  public function getRenewalReminderData($applicableProfiles)
  {
        $counter =0;
        if(is_array($applicableProfiles))
        {
            foreach($applicableProfiles as $profileid=>$value)
            {
                $dataAccumulated[$counter]['SELF']=$value;
                $counter++;
            }
            $dataAccumulated[0]['COUNT'] = "SINGLE";
        }
        unset($applicableProfiles);
        return $dataAccumulated;
  }
  
  public function getMembershipProfilesForNotification($profiles, $notificationKey, $channelArr)
  {
    unset($applicableProfiles);
    unset($profilesArr);
	unset($profilesSmsArr);
	unset($profilesNewArr);
	unset($profileMsgArr);	

    // filter to get sms eligible profiles
    if(is_array($profiles))
    {
        $tempSmsObj            = new newjs_TEMP_SMS_DETAIL();
        $valueArr['PROFILEID'] = @implode(",",$profiles);
        $valueArr['SMS_KEY']   = $notificationKey;
        $profilesSmsArr        = $tempSmsObj->getArray($valueArr,'','','PROFILEID,MESSAGE');
        if(count($profilesSmsArr)>0)
        {
            foreach($profilesSmsArr as $key=>$val)
            {
				$pid =$val['PROFILEID'];
                $profilesNewArr[] =$pid;
				$profileMsgArr[$pid] =$val['MESSAGE'];
			}
        }
    }
    if(!(in_array("M", $channelArr))){
        // filter last 7days logged-in app profiles
        if(is_array($profilesNewArr))
        {
            $loginTrackingObj = new MIS_LOGIN_TRACKING('newjs_local111');
            $profilesNewStr   = @implode(",",$profilesNewArr);
            $profilesArr      = $loginTrackingObj->getLast7DaysLoginProfiles($profilesNewStr);
        }
    }
    else{
        $profilesArr = $profilesNewArr;
    }
    // set PROFILE details for Notification 
    if(is_array($profilesArr))
    {
        $profilesStr =implode(",",$profilesArr);
        foreach($profilesArr as $key1=>$val1)
        {
        	$profileid =$val1;
            $dataArr['PROFILEID'] = $profileid;
            $dataArr['MESSAGE'] =$profileMsgArr[$profileid];
            $applicableProfiles[$profileid] =$dataArr;
            unset($dataArr);
        }
        //update sms send status
        $tempSmsObj->updateSentForNotification($profilesStr, $notificationKey);
    }
    // return eligible profiles
    if($applicableProfiles)
        return $applicableProfiles;
    return false;
  }
  
  
    public function getNotificationImage($icon, $iconProfileid){
        if($icon == 'P' && $iconProfileid){
            $profile=new Profile();
            $profile->getDetail($iconProfileid,"PROFILEID");
            $profilePic = $profile->getHAVEPHOTO();
            if (empty($profilePic) || $profilePic == 'U')
                $profilePic="N";
            if($profilePic!="N"){
                $pictureServiceObj=new PictureService($profile);
                $profilePicObj = $pictureServiceObj->getProfilePic();
                if($profilePicObj){
                    $photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$this->gender,true);
                    if($photoArray[label] != '' || $photoArray["url"] == null)
                       $icon = 'D';
                    else
                       $icon = $photoArray['url'];
                }
                else{
                    $icon = 'D';
                }
            }
            else{
                $icon = 'D';
            }
        }
        else{
            $icon = 'D';
        }
        return $icon;
    }
    
    public function getInterestReceivedForDuration($profileid, $stDate, $endDate){
        $loggedInDb = JsDbSharding::getShardNo($profileid,'');
		$contactsLoggedInObj = new newjs_CONTACTS($loggedInDb);
        $data = $contactsLoggedInObj->getInterestReceivedDataForDuration($profileid, $stDate, $endDate);
        //Remove blocked profiles. Those that have been blocked by the sender
        $ignoreProfileObj = new newjs_IGNORE_PROFILE("newjs_slave");
		$ignoredProfiles = $ignoreProfileObj->getIgnoredProfiles($data["IGNORED_STRING"],$data['SELF']);
        if($ignoredProfiles){
            foreach($ignoredProfiles as $key => $val){
                unset($data['SENDER'][$val]);
            }
        }
        if($data['SENDER']){
            $tempArray = $data['SENDER'];
            end($tempArray);
            $data['OTHER_PROFILEID'] = key($tempArray);
        }
        $data['COUNT'] = count($data['SENDER']);
        unset($tempArray);
        return $data;
    }
    
    public function getMatchOfDayData($applicableProfiles){
        if($applicableProfiles){
            $date = date("Y-m-d", strtotime("-30 days",strtotime(date('Y-m-d'))));
            $matchOfDayMasterObj = new MOBILE_API_MATCH_OF_DAY();
            $matchOfDayMasterObj->deleteLessthanDays($date);
            $counter = 0;
            $matchOfDayObj = new MOBILE_API_MATCH_OF_DAY("newjs_slave");
            $curDate = date('Y-m-d');
            $paramsArr["ENTRY_DT"] = date('Y-m-d', strtotime('-30 day',  strtotime($curDate)));
            $matchCount = $matchOfDayObj->getCountForMatchProfile();
            foreach($applicableProfiles as $profileid => $details){
                $searchResult = SearchCommonFunctions::getMatchofTheDay($profileid);
                $resultSet = $searchResult["PIDS"];
                $paramsArr["PROFILEID"] = $profileid;
                $nameOfUserProfiles[] = $profileid;
                if($searchResult["CNT"] > 0){
                    unset($resultToFilter);
                    $resultToFilter = $matchOfDayObj->getMatchForProfileTillDays($paramsArr);
                    if($resultToFilter){
                        $resultSet = array_diff($resultSet, $resultToFilter);
                    }
                    $copyResultSet = $resultSet;
                    unset($firstResult);
                    unset($minResult);
                    foreach($resultSet as $key => $value){
                        if($matchCount[$value]){
                            if(!$minResult){
                                $minResult["MATCH_PROFILEID"] = $value;
                                $minResult["MATCH_COUNT"] = $matchCount[$value];
                            }
                            if($minResult && ($matchCount[$value] < $minResult["MATCH_COUNT"])){
                                $minResult["MATCH_PROFILEID"] = $value;
                                $minResult["MATCH_COUNT"] = $matchCount[$value];
                            }
                            unset($copyResultSet[$key]);
                        }
                    }
                    if($copyResultSet){
                        reset($copyResultSet);
                        $resultMatchProfileid = current($copyResultSet);
                    }
                    else{
                        $resultMatchProfileid = $minResult["MATCH_PROFILEID"];
                    }
                    if($resultMatchProfileid){
                        $matchedProfiles[$profileid] = $resultMatchProfileid;
                        $otherProfiles[] = $resultMatchProfileid;
                        $nameOfUserProfiles[] = $resultMatchProfileid;
                        //$dataAccumulated[$counter];
                        //print_r($resultMatchProfileid."\n");
                    }
                }
            }
            if(is_array($otherProfiles))
            {
                $getOtherProfilesData = $this->getProfilesData($otherProfiles,$className="JPROFILE","newjs_masterRep");
                /*
                $profileStr = implode(",",$nameOfUserProfiles);
                $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
                $queryParam["PROFILEID"] = $profileStr;
                $nameOfUserDetails = $nameOfUserObj->getArray($queryParam,'',"",$fields="*");
                foreach ($nameOfUserDetails as $key => $val){
                    $nameDetails[$val["PROFILEID"]] = $val;
                }
                */
            }
            unset($otherProfiles);
            unset($nameOfUserProfiles);
            $counter = 0;
            if(is_array($matchedProfiles))
            {
                foreach($matchedProfiles as $k1=>$v1)
                {
                    
                    $dataAccumulated[$counter]['SELF']=$applicableProfiles[$k1];
                    if($getOtherProfilesData[$v1]){
                        $dataAccumulated[$counter]['OTHER'][]=$getOtherProfilesData[$v1];
                        $dataAccumulated[$counter]['ICON_PROFILEID']=$getOtherProfilesData[$v1]["PROFILEID"];
                        
                        unset($selfProfileObj);
                        unset($otherProfileObj);
                        $selfProfileObj = Profile::getInstance('crm_slave',$k1);
                        $selfProfileObj->setDetail($applicableProfiles[$k1]);

                        $otherProfileObj = Profile::getInstance('crm_slave',$v1);
                        $otherProfileObj->setDetail($getOtherProfilesData[$v1]);

                        $nameOfUserClassObj = new NameOfUser();
                        $res = $nameOfUserClassObj->showNameToProfiles($selfProfileObj, array($otherProfileObj));
                        
                        if($res[$v1]["SHOW"] == "1" && $res[$v1]["NAME"] != ""){
                            $dataAccumulated[$counter]['NAME_OF_USER']= $res[$v1]["NAME"];
                        }
                        /*
                        if($nameDetails[$k1]["DISPLAY"] == "Y" && $nameDetails[$v1]["DISPLAY"] == "Y"){
                            $dataAccumulated[$counter]['NAME_OF_USER']= $nameDetails[$v1]["NAME"];
                        }
                        */
                    }
                    $dataAccumulated[$counter]['COUNT'] = "SINGLE";
                    $counter++;
                    JsMemcache::getInstance()->delete("MATCHOFTHEDAY_".$k1);
                    JsMemcache::getInstance()->delete("MATCHOFTHEDAY_VIEWALLCOUNT_".$k1);
                    $matchOfDayMasterObj->insert($k1,$v1);
                }
            }
            unset($matchOfDayMasterObj);
            unset($matchedProfiles);
            return $dataAccumulated;
        }
    }
    
    public function notificationLogging($logArr,$logPoint){
        if (JsConstants::$whichMachine == 'test' && NotificationEnums::$enableNotificationLogging == true) {
            print_r($logPoint);
            print_r("\n");
            foreach($logArr as $key => $val){
                print_r($key);
                print_r($val);
                print_r("\n");
            }
        }
    }
    
    public function getNotificationServiceData(){
        $notificationServiceUrl = JsConstants::$chatNotificationService."/communication/v1/notification";
        $headerArr[] = "JB-Internal: true";
        $response = CommonUtility::sendCurlPostRequest($notificationServiceUrl,'','',$headerArr);
        $modifiedData = json_decode($response,true);
        return $modifiedData;
    }
    
    public function sendChatNotification($notificationData){
        if(!empty($notificationData) && is_array($notificationData)){
            $chatMsgInstantNotObj = new InstantAppNotification("CHAT_MSG");
            foreach($notificationData as $key => $valOld){
                    $val = json_decode($valOld, true);
                    $chatMsgInstantNotObj->sendNotification($val["to"], $val["from"], $val["msg"],'',array('CHAT_ID'=>$val["id"]));
            }
            unset($chatMsgInstantNotObj);
        }
    }
}
?>
