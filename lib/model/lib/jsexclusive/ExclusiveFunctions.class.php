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
		if(is_array($params) && $params["clientId"] && $params["agentUsername"] && ($params["button"]!="SKIP")){
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
		} else if(is_array($params) && $params["clientId"] && $params["agentUsername"] && $params["button"]=="SKIP"){
			$exMappingObj = new billing_EXCLUSIVE_CLIENT_MEMBER_MAPPING();
			if(is_array($params["acceptArr"]) && count($params["acceptArr"])>0){
				$assistedEoiObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
				$assistedEoiObj->deleteEntry($params["clientId"],$params["acceptArr"]);
				unset($assistedEoiObj);
				foreach ($params["acceptArr"] as $key => $value) {
					$exMappingObj->addClientMemberEntry(array("CLIENT_ID"=>$params["clientId"],"MEMBER_ID"=>$value,"SCREENED_STATUS"=>"S"));
				}
				unset($exMappingObj);
				
				$exServicingObj = new billing_EXCLUSIVE_SERVICING();
				$exServicingObj->updateScreenedStatus($params["agentUsername"],$params["clientId"],'Y');
				unset($exServicingObj);
				$to = "sandhya.singh@jeevansathi.com,anjali.singh@jeevansathi.com";
				$from = "info@jeevansathi.com";
				$subject = "Skip feature used";
				$pswrdsObj = new jsadmin_PSWRDS();
				$agentNameArr = array($params["agentUsername"]);
				
				$agentDetail = $pswrdsObj->getAgentDetailsForMatchMail($agentNameArr);
				$firstName = $agentDetail[$params["agentUsername"]]["FIRST_NAME"];
				
				$LastName = $agentDetail[$params["agentUsername"]]["LAST_NAME"];
				if(!empty($firstName)){
					$msgBody = "Skip feature used by ".$firstName.' '.$LastName.'('.$params["agentUsername"].") on ".$params["clientUsername"];
				}else{
					$msgBody = "Skip feature used by ".$params["agentUsername"]." on ".$params["clientUsername"];
					}
				SendMail::send_email($to, $msgBody, $subject, $from, "", "", "", "", "", "", "1", $email, "Jeevansathi Support");
			}
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
        
        public function formatDataForMatchMail($data,$matchMailData){
            foreach ($matchMailData as $key => $value){
                $matchMailFormattedData[$value["PROFILEID"]] = $value;
                $profileidArr[]=$value["PROFILEID"];
            }
            $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
            if(is_array($profileidArr) && count($profileidArr)>0){
                $profileidStr = implode(",", $profileidArr);
                $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID" => $profileidStr), "", "", "PROFILEID,NAME,DISPLAY");
                foreach($clientNameArr as $index => $val){
                    $modifiedNameArr[$val["PROFILEID"]] = $val;
                }
            }
            foreach($data as $date => $val){
                foreach($val as $index => $dataValue){
                    unset($temp);
                    $temp["ACCEPTANCE_ID"] = $dataValue["ACCEPTANCE_ID"];
                    $temp["USERNAME"] = $matchMailFormattedData[$dataValue["ACCEPTANCE_ID"]]["USERNAME"];
                    $temp["PHOTO_URL"] = $matchMailFormattedData[$dataValue["ACCEPTANCE_ID"]]["PHOTO_URL"];
                    if($modifiedNameArr[$dataValue["ACCEPTANCE_ID"]]["DISPLAY"] == "Y")
                        $temp["NAME_OF_USER"] = $modifiedNameArr[$dataValue["ACCEPTANCE_ID"]]["NAME"];
                    $finalData[$date][]=$temp;
                }
            }
            return $finalData;
        }
        
        public function returnAcceptanceIdArr($dataArr){
            if(is_array($dataArr)){
                foreach($dataArr as $date => $value){
                    foreach($value as $index => $tableData){
                        $resultSet[] = $tableData['ACCEPTANCE_ID'];
                    }
                }
                return $resultSet;
            }
        }
        
        public function getUsernameForArr($arr){
            if(is_array($arr)){
                $jprofileObj = new JPROFILE('newjs_masterRep');
                $result = $jprofileObj->getAllSubscriptionsArr($eligibleProfileArr);
            }
        }
        
        public function actionsToBeTakenForProfilesToBeFollowedup($arr,$client,$agent,$skipUpdate=false){
            if(is_array($arr)){
                if($skipUpdate == false){
                    $followupObj = new billing_EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS("newjs_masterRep");
                    $followupObj->updateStatusForClientId(implode(",", $arr),'Y');
                }
                $exclusiveFollowupObj = new billing_EXCLUSIVE_FOLLOWUPS();
                $todaysDate = date('Y-m-d H:i:s');
                $params["ENTRY_DT"] = $todaysDate;
                $params["CLIENT_ID"] = $client;
                $params["AGENT_USERNAME"] = $agent;
                //$params["FOLLOWUP1_DT"] = date('Y-m-d', strtotime('+1 day',  strtotime($todaysDate)));
                $params["FOLLOWUP1_DT"] = date('Y-m-d');
                $params["STATUS"] = "F0";
                foreach($arr as $key => $val){
                    $params["MEMBER_ID"] = $val;
                    $exclusiveFollowupObj->insertIntoExclusiveFollowups($params);
                    $mailerInfo = array();
                    $mailerInfo[0]["MEMBER_ID"] = $val;
                    $mailerInfo[0]["CLIENT_ID"] = $client;
                    $agentUsernames = array();
                    $agentUsernames[] = $agent;
                    $pswrdsObj = new jsadmin_PSWRDS();
                    $agentDetail = $pswrdsObj->getAgentDetailsForMatchMail($agentUsernames);
                    $mailerInfo[0]["EMAIL"] = $agentDetail[$agent]["EMAIL"];
                    $mailerInfo[0]["PHONE"] = $agentDetail[$agent]["PHONE"];
                    $mailerInfo[0]["NAME"] = $agentDetail[$agent]["FIRST_NAME"];
                    $mailerInfo[0]["STATUS"] = "F0";
                    if ($lastName = $agentDetail[$agent]["LAST_NAME"])
                        $mailerInfo[0]["NAME"] .= " ".$lastName;
                    $result = $this->getProfilesToSendProposalMail($mailerInfo,true);
                    $this->sendProposalMail($result);
                }
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
        if(empty($params["date1"])){
            if($params["followupStatus"]=='F'){
                $params["date1"] = date('Y-m-d',strtotime($currentDt));
            }
            else{
                $params["date1"] = $currentDt;
            }
        }
      	else if($params["followupStatus"]=='Y' || $params["followupStatus"]=='N'){
      		$params["date1"] = $currentDt;
      	}
      	/* else if($params["date1"]==$currentDt){
      		$params["date1"] = date('Y-m-d',strtotime($currentDt));
      	} */

        $updateArr = array();
        switch($params["followUpDetails"]["STATUS"]){
            case "F0":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F1";
                    //$updateArr["FOLLOWUP_1"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                   // $updateArr["FOLLOWUP_1"] = $params["reason"];
                    //if(!empty($params["reasonText"]))
                    	$updateArr["FOLLOWUP_1"] = $params["reason"]."|".$params["reasonText"];
                    $updateArr["FOLLOWUP2_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N' || $params["followupStatus"]=='Y'){
                		//if(!empty($params["reasonText"]))
                			$updateArr["FOLLOWUP_1"] = "|".$params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP1_DT"] = $currentDt;
                $updateArr["FOLLOWUP_1"] .= "|".$params["operator"];
                break;
            case "F1":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F2";
                    //$updateArr["FOLLOWUP_2"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                    //$updateArr["FOLLOWUP_2"] = $params["reason"];
                    //if(!empty($params["reasonText"]))
                    	$updateArr["FOLLOWUP_2"] = $params["reason"]."|".$params["reasonText"];
                    $updateArr["FOLLOWUP3_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N'|| $params["followupStatus"]=='Y'){
                		//if(!empty($params["reasonText"]))
                			$updateArr["FOLLOWUP_2"] = "|".$params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_2"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP2_DT"] = $currentDt;
                break;
            case "F2":
                if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F3";
                    //$updateArr["FOLLOWUP_3"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                    //$updateArr["FOLLOWUP_3"] = $params["reason"];
                    //if(!empty($params["reasonText"]))
                    $updateArr["FOLLOWUP_3"] = $params["reason"]."|".$params["reasonText"];
                    $updateArr["FOLLOWUP4_DT"] = $params["date1"];
                }
                else{
                	if($params["followupStatus"]=='N'|| $params["followupStatus"]=='Y'){
                		//if(!empty($params["reasonText"]))
                			$updateArr["FOLLOWUP_3"] = "|".$params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_3"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP3_DT"] = $currentDt;
                break;
            case "F3":
            	if($params["followupStatus"]=='F'){
                    $updateArr["STATUS"] = "F4";
                    //$updateArr["FOLLOWUP_4"] = ($params["reason"]=="Others"?$params["reasonText"]:$params["reason"]);
                    //$updateArr["FOLLOWUP_4"] = $params["reason"];
                    //if(!empty($params["reasonText"]))
                    $updateArr["FOLLOWUP_4"] = $params["reason"]."|".$params["reasonText"];
            	}
                else{
                	if($params["followupStatus"]=='N'|| $params["followupStatus"]=='Y'){
                		//if(!empty($params["reasonText"]))
                			$updateArr["FOLLOWUP_4"] = "|".$params["reasonText"];
                	}
                    $updateArr["STATUS"] = $params["followupStatus"];
                }
                $updateArr["FOLLOWUP_4"] .= "|".$params["operator"];
                $updateArr["FOLLOWUP4_DT"] = $currentDt;
                break;
        }
        if($params["followupStatus"]=='Y'){
       	    $updateArr["CONCALL_SCH_DT"] = date('Y-m-d');
        }
        
        $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS();      
        $followUpObj->updateFollowUp($params["ifollowUpId"],$updateArr);
        unset($followUpObj);
        unset($updateArr);
    }

	public function getRMDetails($profileid)
	{
        	$exServicingObj = new billing_EXCLUSIVE_SERVICING('newjs_masterRep');
                $rmDetails =$exServicingObj->checkBioData($profileid);
		$rmName =$rmDetails['AGENT_USERNAME'];
		$pswrdsObj =new jsadmin_PSWRDS('newjs_masterRep');
		$executiveDetails =$pswrdsObj->getExecutiveDetails($rmName);		
		return $executiveDetails;
	}

    public function deleteEntryFromExclusiveServicing($profileid,$flag,$billid=0) {
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $profilesInfo = $exclusiveServicingObj->getAllDataForClient($profileid,$billid);
        $exclusiveServicingLogObj = new billing_EXCLUSIVE_SERVICING_LOG();
        if($flag == 'D' || ($flag == 'X' && $billid !=0))
            $success = $exclusiveServicingLogObj->addDeletedProfileFromExclusiveServicing($profilesInfo);
        if($success == true){
            if($flag == 'X' && $billid != 0){
                $exclusiveServicingObj->removeExclusiveClientEntry($profileid,$billid);
            }else if($flag == 'D'){
                $exclusiveServicingObj->removeExclusiveClientEntry($profileid);
            }
        }
	}
	
	public function addDataToRedisObject($key,$value){
		JsMemcache::getInstance()->lpush($key,$value);
	}
	
	public  function deleteRedisKey($Key){
		JsMemcache::getInstance()->delete($Key);
	}

	public function getReceiverAndAgentDetailsforProposalMail(){
        $followupObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $result = $followupObj->getDetailsForProposalMail();

        if (!is_array($result))
            $result = array();

        $agentUsernames = array();
        foreach ($result as $key=>$value){
            if(!in_array($value["AGENT_USERNAME"],$agentUsernames))
                $agentUsernames[]=$value["AGENT_USERNAME"];
        }
        if (!empty($agentUsernames)){
            $pswrdsObj = new jsadmin_PSWRDS();
            $agentDetail = $pswrdsObj->getAgentDetailsForMatchMail($agentUsernames);
        }

        if (!is_array($agentDetail))
            $agentDetail = array();

        foreach ($result as $key=>$value){
            $agentUserName = $value["AGENT_USERNAME"];
            $result[$key]["EMAIL"] = $agentDetail[$agentUserName]["EMAIL"];
            $result[$key]["PHONE"] = $agentDetail[$agentUserName]["PHONE"];
            $result[$key]["NAME"] = $agentDetail[$agentUserName]["FIRST_NAME"];
            if ($lastName = $agentDetail[$agentUserName]["LAST_NAME"])
                $result[$key]["NAME"] .= " ".$lastName;
        }

        $result = $this->getProfilesToSendProposalMail($result,false);

        return $result;
    }

    public function sendProposalMail($mailerInfo){
	    if(!is_array($mailerInfo) || empty($mailerInfo))
	        return false;

	    foreach ($mailerInfo as $key=>$value){
            $userIdArr[] = $value["USER1"];
        }
        $userIdStr = implode(",",$userIdArr);
        $nameOfUserObj = new incentive_NAME_OF_USER();
        $nameOfUserArr = $nameOfUserObj->getArray(array("PROFILEID" => $userIdStr), "", "", "PROFILEID,NAME,DISPLAY");

        foreach ($nameOfUserArr as $key=>$value){
            $userNameArr[$value["PROFILEID"]] = $value;
        }

	    $producerObj = new Producer();
        if($producerObj->getRabbitMQServerConnected()){
            foreach ($mailerInfo as $key=>$value){
                $pid = $value["USER1"];
                $name = $userNameArr[$pid]["NAME"];
                $display = $userNameArr[$pid]["DISPLAY"];
                $agentName = $value["AGENT_NAME"];
                $agentPhone = $value["AGENT_PHONE"];
                $userName = $value["USERNAME"];
                $subjectAndBody = $this->subjectAndBodyForProposalMail($pid,$name,$display,$agentName,$userName);
                $sendMailData = array('process' =>'EXCLUSIVE_MAIL',
                    'data'=>array('type' => 'EXCLUSIVE_PROPOSAL_EMAIL',
                        'RECEIVER'=>$value["RECEIVER"],
                    	'USERNAME'=>$userName,
                        'AGENT_NAME'=>$value["AGENT_NAME"],
                        'AGENT_EMAIL'=>$value["AGENT_EMAIL"],
                        'USER1'=>$value["USER1"],
                        'AGENT_PHONE'=>$value["AGENT_PHONE"],
                        'SUBJECT'=>$subjectAndBody["subject"],
                        'BODY'=>$subjectAndBody["body"]),
                    'redeliveryCount'=>0 );
                $this->updateStatusForProposalMail($value["RECEIVER"],$value["USER1"],'U');
                $producerObj->sendMessage($sendMailData);
            }
        }
    }

    public function subjectAndBodyForProposalMail($pid,$name,$display,$agentName,$userName){
        $subject = "Marriage Proposal of JS Exclusive Client (";
        if($display == "Y")
            $subject .= $name.",";
        $subject .= "Profile ID: ".$userName.")";
        $email["subject"] = $subject;

        $body = "Hi, This is $agentName from Jeevansathi Exclusive team reaching out to you on behalf of my Client as they are interested in your profile and want to proceed further.<br><br>We will get in touch with you soon to discuss about this profile and take next steps.<br><br>Please find below the details of our Exclusive client (";
        if($display == "Y")
            $body .= $name.",";
        $body .= "Profile ID: $userName). For more details kindly view the full profile on Jeevansathi.com";
        $email["body"] = $body;

        return $email;
    }

    public function updateStatusForProposalMail($receiver,$user,$status){
        $date = date("Y-m-d",strtotime(' +1 day'));
        $proposalMailerObj = new billing_ExclusiveProposalMailer();
        $proposalMailerObj->updateStatus($receiver,$user,$status,$date);
    }


    public function getClientBioData($client){
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $biodata = $exclusiveServicingObj->checkBioData($client);
        $biodataLocation = $biodata['BIODATA_LOCATION'];
        $clientBioData = array();
        if($biodata == false || $biodataLocation == null){
            $clientBioData["isUploaded"] = false;
            $clientBioData["BIODATA"] = "";
            $clientBioData["FILENAME"] = "";
            return $clientBioData;
        } else{
            $clientBioData["isUploaded"] = true;
        }
        $ext = end(explode('.', $biodataLocation));
        $file = "BioData-$this->client.".$ext;
        $xlData=file_get_contents($biodataLocation);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $clientBioData["BIODATA"] = $xlData;
        $clientBioData["FILENAME"] = $file;
        return $clientBioData;
    }

    public function getProfilesToSendProposalMail($mailerArr,$isInstant){
        foreach($mailerArr as $key=>$value){
            $clientID = $value["CLIENT_ID"];
            $clientProfileObj = new Operator;
            $res = $clientProfileObj->getDetail($clientID,"PROFILEID","PROFILEID,USERNAME");
            $mailerArr[$key]["USERNAME"] = $res["USERNAME"];
        }
        $proposalObj = new billing_ExclusiveProposalMailer();
        $proposalObj->insertMailLog($mailerArr);
        if($isInstant){
            $result = $proposalObj->getProfilesToSendProposalMail($mailerArr[0]["EMAIL"],$isInstant);
        } else{
            $result = $proposalObj->getProfilesToSendProposalMail('',$isInstant);
        }

        if(!is_array($result))
            $result = array();
        return $result;
    }
}
?>
