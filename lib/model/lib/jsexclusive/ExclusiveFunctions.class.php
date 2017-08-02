<?php
class ExclusiveFunctions{

	/*formatScreenRBInterestsData - format screened RB interests for clients assigned to logged in RM and filtered by RM
    * @param :$clientParams=array(),$pogRBInterestsPids=array()
    */
	public function formatScreenRBInterestsData($clientParams=array(),$pogRBInterestsPids=array()){
		$pogRBInterestsPool = array();

		if(count($pogRBInterestsPids)>0 && $clientParams["HoroscopeMatch"] == 'Y'){
			$gunaScoreObj = new gunaScore();
			$gunaScoreArr = $gunaScoreObj->getGunaScore($clientParams['PROFILEID'],$clientParams['clientCaste'],implode(",", $pogRBInterestsPids),$clientParams["gender"],1);
			unset($gunaScoreObj);
			
			foreach ($gunaScoreArr as $key => $valueArr) {
				foreach ($valueArr as $k => $v) {
					$formattedGunaScoreArr[$k] = $v;
				}
			}
			unset($gunaScoreArr);
		} 
		foreach ($pogRBInterestsPids as $key => $pid) {
			$profileObj = new Operator;
			$profileObj->getDetail($pid,"PROFILEID","PROFILEID,USERNAME,YOURINFO,HAVEPHOTO,GENDER,HOROSCOPE_MATCH");
			if($profileObj){
				$pogRBInterestsPool[$key]['PROFILEID'] = $pid;
				$pogRBInterestsPool[$key]['USERNAME'] = $profileObj->getUSERNAME();
				$pogRBInterestsPool[$key]['ABOUT_ME'] = $profileObj->getYOURINFO();
				if(!empty($pogRBInterestsPool[$key]['ABOUT_ME'])){
					$pogRBInterestsPool[$key]['ABOUT_ME'] = substr($pogRBInterestsPool[$key]['ABOUT_ME'], 0,1000);
				}
				$profilePic = $profileObj->getHAVEPHOTO();
				$oppGender = $profileObj->getGENDER();
				
				if($oppGender!=$clientParams["gender"]){
	        		if (!empty($profilePic) && $profilePic != 'N'){
	        			$pictureServiceObj=new PictureService($profileObj);
	            		$profilePicObj = $pictureServiceObj->getProfilePic();
	            		if(!empty($profilePicObj)){
		            		$photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$oppGender,true);
		            		if($photoArray[label] == '' && $photoArray["url"] != null){
		                   		$pogRBInterestsPool[$key]['PHOTO_URL'] = $photoArray['url'];
		            		}
		            		unset($photoArray);
		            	}
	            		unset($profilePicObj);
	            		unset($pictureServiceObj);
	        		}
	        		if(empty($pogRBInterestsPool[$key]['PHOTO_URL'])){
	        			if($oppGender=="M"){
	        				$pogRBInterestsPool[$key]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoMaleProfilePic120Url');
	        			}
	        			else{
	        				$pogRBInterestsPool[$key]['PHOTO_URL'] = sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoFemaleProfilePic120Url');
	        			}
	        		}
	        		if(is_array($formattedGunaScoreArr) && $formattedGunaScoreArr[$pid]){
	        			$pogRBInterestsPool[$key]['GUNA_SCORE'] = $formattedGunaScoreArr[$pid];
	        		}
	        	}
			}
			unset($profileObj);
		}
		//print_r($pogRBInterestsPool);die;
		return $pogRBInterestsPool;
	}

	/*formatRabbitmqData - format screened RB data for rabbitmq
    * @param :$inputArr=""
    */
	public function formatRabbitmqData($inputArr=""){
		if(is_array($inputArr)){
			$outputArr = array('process' =>'RBSendInterests','data'=>array('type' => 'RB_EOI_SCREENING','body'=>array("MEMBERSHIP"=>"JsExclusive","SENDER"=>$inputArr["clientId"],"RECEIVER"=>$inputArr["acceptArr"],"SCREENED_DT"=>date("Y-m-d H:i:s"))), 'redeliveryCount'=>0);
			return $outputArr;
		}
		else{
			return null;
		}
	}

	/*processScreenedEois - process screened accepted and declined RB eois
    * @param :$params=""
    */
	public function processScreenedEois($params=""){
		if(is_array($params) && $params["clientId"] && $params["agentUsername"]){
			$exMappingObj = new billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING();
			if(is_array($params["acceptArr"]) && count($params["acceptArr"])>0){
				/*$mqData = $this->formatRabbitmqData($params);
				if(is_array($mqData)){
					$producerObj = new Producer();
					if($producerObj->getRabbitMQServerConnected()){
						$producerObj->sendMessage($mqData);
						foreach ($params["acceptArr"] as $key => $value) {
							$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"P"));
						}
					/*} 
					else{
						foreach ($params["acceptArr"] as $key => $value) {
							$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"PY"));
						}
					}*/
					$assistedEoiObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
					$assistedEoiObj->deleteEntry($params["clientId"],$params["acceptArr"]);
					unset($assistedEoiObj);
					foreach ($params["acceptArr"] as $key => $value) {
						$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"P"));
					}
					//unset($producerObj);	
				//}
			}
			
			if(is_array($params["discardArr"]) && count($params["discardArr"])>0){
				$assistedEoiObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
				$assistedEoiObj->deleteEntry($params["clientId"],$params["discardArr"]);
				unset($assistedEoiObj);
				foreach ($params["discardArr"] as $key => $value) {
					$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"N"));
				}
			}
			unset($exMappingObj);
			$exServicingObj = new billing_EXCLUSIVE_SERVICING();
			$exServicingObj->updateScreenedStatus($params["agentUsername"],$params["clientId"],'Y');
			unset($exServicingObj);
		}
	}
        
        public function getCompleteDay($shortDay){
            if($shortDay=='MON'){
                return 'Monday';
            }elseif($shortDay=='TUE'){
                return 'Tuesday';
            }elseif($shortDay=='WED'){
                return 'Wednesday';
            }elseif($shortDay=='THU'){
                return 'Thursday';
            }elseif($shortDay=='FRI'){
                return 'Friday';
            }elseif($shortDay=='SAT'){
                return 'Saturday';
            }elseif($shortDay=='SUN'){
                return 'Sunday';
            }
        }

    public function formatFollowUpsData($followUpsCount){
    	$currentDt = date("Y-m-d");
    	$start = 0;
        $limit = crmCommonConfig::$followupslimit;
        $jprofileObj = new JPROFILE("newjs_slave");
        $contactObj = new ProfileContact("newjs_slave");
        $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
        $finalFollowUpsPool = array("followUpData"=>array(),"membersData"=>array(),"clientsData"=>array());
        $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS();
        while($start<=$followUpsCount){
            //fetch followup data
            $origFollowUpsPool = $followUpObj->getPendingFollowUpEntries($currentDt,$limit,$start); 

            $membersIds = array();
            $clientIds = array();
            if(is_array($origFollowUpsPool))
	            foreach ($origFollowUpsPool as $mid => $clients) {
	            	foreach ($clients as $k => $v) {
	            		$followUpsPool[] = $v;
	            		if(!in_array($v['CLIENT_ID'], $clientIds)){
	            			$clientIds[] = $v['CLIENT_ID'];
	            		}
	            		if(!in_array($v['MEMBER_ID'], $membersIds)){
	            			$membersIds[] = $v['MEMBER_ID'];
	            		}
	            	}
	            }
            if(is_array($followUpsPool)){
                //merge the follow up pool
                $finalFollowUpsPool["followUpData"] = array_merge($finalFollowUpsPool["followUpData"],$followUpsPool);
    
                $memberIdStr = implode($membersIds,",");

                //fetch primary and alternate contact nos of member ids     
                $phoneDetails = $jprofileObj->getArray(array("PROFILEID"=>$memberIdStr),"","","PROFILEID,USERNAME,PHONE_MOB");
                $altPhoneDetails = $contactObj->getArray(array("PROFILEID"=>$memberIdStr),"","","PROFILEID,ALT_MOBILE");
                unset($memberIdStr);
                
                //merge contact details
                if(is_array($phoneDetails)){
                    foreach ($phoneDetails as $key => $value) {
                        $finalFollowUpsPool["membersData"][$value['PROFILEID']]['PHONE_MOB'] = $value['PHONE_MOB'];
                        $finalFollowUpsPool["membersData"][$value['PROFILEID']]['USERNAME'] = $value['USERNAME'];
                    }
                }
                unset($phoneDetails);
                if(is_array($altPhoneDetails)){
                    foreach ($altPhoneDetails as $key => $value) {
                        $finalFollowUpsPool["membersData"][$value['PROFILEID']]['ALT_MOBILE'] = $value['ALT_MOBILE'];
                    }
                }
                unset($altPhoneDetails);
               
                $clientIdStr = implode($clientIds,",");

                //fetch name,username of clients
                $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID"=>$clientIdStr),"","","PROFILEID,NAME,DISPLAY");
                $clientUsernameArr = $jprofileObj->getArray(array("PROFILEID"=>$clientIdStr),"","","PROFILEID,USERNAME");
                if(is_array($clientNameArr)){
                    foreach ($clientNameArr as $key => $value) {
                        if($value["DISPLAY"]=='Y'){
                            $finalFollowUpsPool["clientsData"][$value['PROFILEID']]['NAME'] = $value['NAME'];
                        }
                    }
                }
                unset($clientNameArr);
                if(is_array($clientUsernameArr)){
                    foreach ($clientUsernameArr as $key => $value) {
                        $finalFollowUpsPool["clientsData"][$value['PROFILEID']]['USERNAME'] = $value['USERNAME'];
                    }
                }
                unset($clientUsernameArr);
            }  
            unset($clientIds);
            unset($membersIds); 
            unset($followUpsPool); 
            $start += $limit;
        }
        unset($followUpObj);
        unset($nameOfUserObj);
        unset($jprofileObj);
        unset($contactObj);
        return $finalFollowUpsPool;
    }

    public function updateFollowUpDetails($params=array()){
        $currentDt = date("Y-m-d");
        var_dump($params["date1"]);die;
        if(empty($params["date1"])){
            if($params["followupStatus"]=='F'){
                $params["date1"] = date('Y-m-d',strtotime($currentDt . "+1 day"));
            }
            else{
                $params["date1"] = $currentDt;
            }
        }
      	else if($params["followupStatus"]=='Y' || $params["followupStatus"]=='N'){
      		$params["date1"] = $currentDt;
      	}
      	

        $updateArr = array();
        switch($params["followUpDetails"]["STATUS"]){
            case "F0":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F1";
                    $updateArr["FOLLOWUP_1"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                    $updateArr["FOLLOWUP2_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N'){
                		$updateArr["FOLLOWUP_1"] = $params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP1_DT"] = $currentDt;
                $updateArr["FOLLOWUP_1"] .= "|".$params["operator"];
                break;
            case "F1":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F2";
                    $updateArr["FOLLOWUP_2"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                     $updateArr["FOLLOWUP3_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N'){
                		$updateArr["FOLLOWUP_2"] = $params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_2"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP2_DT"] = $currentDt;
                break;
            case "F2":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] == "F3";
                    $updateArr["FOLLOWUP_3"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                     $updateArr["FOLLOWUP4_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N'){
                		$updateArr["FOLLOWUP_3"] = $params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_3"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP3_DT"] = $currentDt;
                break;
            case "F3":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F4";
                    $updateArr["FOLLOWUP_4"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                }
                else{
                	if($params["followupStatus"]=='N'){
                		$updateArr["FOLLOWUP_4"] = $params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_4"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP4_DT"] = $currentDt;
                break;
        }
       	$updateArr["CONCALL_SCH_DT"] = date('Y-m-d',strtotime($currentDt . "+1 day"));
        $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS();      
        $followUpObj->updateFollowUp($params["ifollowUpId"],$updateArr);
        unset($followUpObj);
        unset($updateArr);
    }
}
?>