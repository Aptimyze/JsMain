<?php

/**
 * jsexclusive actions.
 *
 * @package    jeevansathi
 * @subpackage jsexclusive
 */

class jsexclusiveActions extends sfActions {

    
    /**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function preExecute()
	{
		$request=sfContext::getInstance()->getRequest();
		$this->cid=$request->getParameter("cid");
		$this->name=$request->getParameter("name");
		$this->module = $request->getParameter("module");
		$this->action = $request->getParameter("action");

		//put module wise condition
		if($this->name && $this->module=="jsexclusive" && in_array($this->action, array("screenRBInterests","menu"))){
			$exclusiveObj = new billing_EXCLUSIVE_SERVICING();
			$this->assignedClients = $exclusiveObj->getUnScreenedExclusiveMembers($this->name,"ASSIGNED_DT");
			unset($exclusiveObj);
			if(is_array($this->assignedClients) && count($this->assignedClients)>0){
                $this->unscreenedClientsCount = count($this->assignedClients);
			}
			else{
				$this->unscreenedClientsCount = 0;
			}
		}
		else{
			$this->unscreenedClientsCount = 0;
		}
	}

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }
    
    	/*forwards the request to given module action
    * @param : $module,$action
    */
	public function forwardTo($module,$action,$params=""){
		$url="/operations.php/$module/$action";
		if(is_array($params)){
			foreach ($params as $key => $value) {
				if(strpos($url, "?")){
					$url .= "&".$key."=".$value;
				}
				else{
					$url .= "?".$key."=".$value;
				}
			}
		}
		$this->redirect($url);
	}

	/*ScreenRBInterests - screen RB interests for clients assigned to logged in RM
    * @param : $request
    */
	public function executeScreenRBInterests(sfWebRequest $request){
		$exclusiveObj = new billing_EXCLUSIVE_SERVICING();
		//$assignedClients = $exclusiveObj->getUnScreenedExclusiveMembers($this->name,"ASSIGNED_DT");
		$this->clientIndex = $request->getParameter("clientIndex");
		$this->showNextButton = 'N';
                
		if(empty($this->clientIndex) || !is_numeric($this->clientIndex)){
			$this->clientIndex = 0;
		}
		if(!is_array($this->assignedClients) || count($this->assignedClients)==0){
			$this->infoMsg = "No assigned clients corresponding to logged in RM found..";
		}
		else if(!empty($this->clientIndex) && $this->clientIndex>=count($this->assignedClients)){
			$this->infoMsg = "No more clients left for screening for logged in RM..";
		}
		else{
			$this->clientId = $this->assignedClients[$this->clientIndex];
			$assistedProductObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
			$pogRBInterestsPids = $assistedProductObj->getPOGInterestEligibleProfiles($this->clientId);
			//$pogRBInterestsPids = array(543);
			unset($assistedProductObj);

                        $clientProfileObj = new Operator;
			$clientProfileObj->getDetail($this->clientId,"PROFILEID","PROFILEID,USERNAME,GENDER,HOROSCOPE_MATCH,CASTE");

                        if($clientProfileObj) {
                                
                                // get client's Name
                                $nameOfUserObject = new incentive_NAME_OF_USER();
                                $name = $nameOfUserObject->getName($this->clientId);
                            
                                // get client's Image
                                $clientImage = $this->getClientImage($clientProfileObj);
                                
                                // get client's Notes
                                $exclusiveClientNotes = new incentive_EXCLUSIVE_CLIENT_NOTES();
                                $notes = $exclusiveClientNotes->getClientNotes($this->clientId);
                                $this->horoscopeMatch = $clientProfileObj->getHOROSCOPE_MATCH();
				$this->clientData = array("clientUsername"=>$clientProfileObj->getUSERNAME(),"HoroscopeMatch"=>"N",
                                    "PROFILEID"=>$this->clientId,"clientCaste"=>$clientProfileObj->getCASTE(),
                                    "clientName"=>$name, "clientImage"=>$clientImage, "clientNotes"=>$notes);
				$this->clientData["HoroscopeMatch"] = $this->horoscopeMatch;
				$this->clientData["gender"] = $clientProfileObj->getGENDER();
				unset($clientProfileObj);

				if(is_array($pogRBInterestsPids) && count($pogRBInterestsPids)>0){
					$exclusiveLib = new ExclusiveFunctions();
					$this->pogRBInterestsPool = $exclusiveLib->formatScreenRBInterestsData($this->clientData,$pogRBInterestsPids);
					unset($exclusiveLib);
				}
				else{
					$this->infoMsg = "No RB interests were generated for the following user. Please note down this ID and revise/expand their DPP";
					$this->showNextButton = 'Y';
				}
			}
			unset($clientProfileObj);
		}
	}

        private function getClientImage($clientProfileObj) {
            $imageStatus = $clientProfileObj->getHAVEPHOTO();
            
            if (!empty($imageStatus) && $imageStatus != 'N') {
                    $pictureServiceObj=new PictureService($clientProfileObj);
                    $profilePicObj = $pictureServiceObj->getProfilePic();
	    
                    if(!empty($profilePicObj)) {
                        $photoArray = PictureFunctions::mapUrlToMessageInfoArr($profilePicObj->getProfilePic120Url(),'ProfilePic120Url','',$oppGender,true);
		    
                        if($photoArray[label] == '' && $photoArray["url"] != null){
                                return $photoArray["url"];
		        }
		    
                    unset($photoArray);
                    }
            }
            
            if(empty($clientImage)) {
                if($clientProfileObj->getGENDER()== "M") {
                    return sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoMaleProfilePic120Url');
                }
                else {
                    return sfConfig::get("app_img_url").constant('StaticPhotoUrls::noPhotoFemaleProfilePic120Url');
                }
            }
        }
	/*SubmitScreenRBInterests - submit screened RB interests for clients assigned to logged in RM and filtered by RM
    * @param : $request
    */
	public function executeSubmitScreenRBInterests(sfWebRequest $request){
		$formArr = $request->getParameterHolder()->getAll();

		if(is_numeric($formArr["clientIndex"])){
			$this->clientIndex = $formArr["clientIndex"];
			if(empty($this->clientIndex)){
				$this->clientIndex = 0;
			}
			if($formArr["submit"] == "SUBMIT"){
				$acceptArr = $formArr["ACCEPT"];
				$discardArr = $formArr["DISCARD"];
				if(is_array($acceptArr) && is_array($discardArr)){
					$acceptArr = array_diff($acceptArr, $discardArr);
					$acceptArr = array_values($acceptArr);
				}
				
				$email = $request->getParameter("email");
				$exclusiveObj = new ExclusiveFunctions();
				$exclusiveObj->processScreenedEois(array("agentUsername"=>$this->name,"clientId"=>$request->getParameter("clientId"),"acceptArr"=>$acceptArr,"discardArr"=>$discardArr,"button"=>$formArr["submit"]));
				unset($exclusiveObj);
			}
			else if($formArr["submit"] == "SKIP"){
					$acceptArr = $formArr["ACCEPT"];
				 	$acceptArr = array_values($acceptArr);
				 	$email = $request->getParameter("email");
					$exclusiveObj = new ExclusiveFunctions();
					$exclusiveObj->processScreenedEois(array("agentUsername"=>$this->name,"clientId"=>$request->getParameter("clientId"),"acceptArr"=>$acceptArr,"button"=>$formArr["submit"],"clientUsername"=>$request->getParameter("clientUsername")));
					unset($exclusiveObj);
			} else if($formArr["submit"] == "NEXT"){
			    $this->clientIndex += 1;
            }
			$this->forwardTo("jsexclusive","screenRBInterests",array("clientIndex"=>$this->clientIndex));
		}
		else{
			$this->forwardTo("jsexclusive","screenRBInterests");
		}
    }

    public function executeMenu(sfWebRequest $request) {
        //Get Count for welcome calls module on menu page 
        $agent = $request['name'];
        $notFound = $request['notFound'];
        $exclusiveObj = new billing_EXCLUSIVE_SERVICING();
        $this->activeClientsCount = $exclusiveObj->getActiveClientCount($agent);
        //Counter for welcome calls
        $this->welcomeCallsCount = $exclusiveObj->getWelcomeCallsCount($agent);
        $todaysClient = $exclusiveObj->getDayWiseAssignedCount($agent);
        if(is_array($todaysClient)){
            $this->todaysClientCount = $todaysClient[strtoupper(date('D'))]?$todaysClient[strtoupper(date('D'))]:0;
        }
        else{
            $this->todaysClientCount = 0;
        }
        unset($exclusiveObj);
        
        //To get count for pending con calls for menu page
        $exclFollowupsObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $date = date('Y-m-d');
        $this->pendingConcallsCount = $exclFollowupsObj->getPendingConcallsCount($date,$agent);
        unset($exclFollowupsObj);
        if($notFound==true){
            $this->notFound=1;
        }
    }
    public function executeActiveClientList(sfWebRequest $request)
    {
        $agent = $request['name'];
        $exclusiveObj = new billing_EXCLUSIVE_SERVICING("crm_slave"); //connection
        $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");//connection
        $expiryDateObj = new billing_SERVICE_STATUS("newjs_slave");//connection
        $clientUsername = new JPROFILE("newjs_slave");
        $clientInfoArr = $exclusiveObj->getActiveClientInfo($agent);
        if(is_array($clientInfoArr))
        {
            foreach ($clientInfoArr as $key => $value) {
                $clientIdArr[] = $value["CLIENT_ID"];
                $billIdArr[] = $value["BILLID"];
            }
            $this->columnNamesArr = array("Client ID", "Client Name", "Assign Date", "Service Day", "Expiry Date");
            $clientIdStr = implode(",", $clientIdArr);
            $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID" => $clientIdStr), "", "", "PROFILEID,NAME,DISPLAY");
            foreach($clientNameArr as $key => $val){
                $nameTempArr[$val["PROFILEID"]] = $val;
            }
            $expiryDateArr = $expiryDateObj->fetchServiceDetailsByBillId($billIdArr,"PROFILEID,EXPIRY_DT");
            $usernameArr = $clientUsername->getAllSubscriptionsArr($clientIdArr);
            $this->count = count($clientInfoArr);
            for ($i = 0; $i < $this->count; $i++) {
                $dataArray[$i]['CLIENT_ID'] = $clientInfoArr[$i]['CLIENT_ID'];
                $dataArray[$i]['USERNAME'] = $usernameArr[$clientInfoArr[$i]['CLIENT_ID']]["USERNAME"];
                $dataArray[$i]['ASSIGNED_DT'] = $clientInfoArr[$i]['ASSIGNED_DT'];
                $dataArray[$i]['SERVICE_DAY'] = $clientInfoArr[$i]['SERVICE_DAY'];
                $dataArray[$i]['EXPIRY_DT'] = $expiryDateArr[$clientInfoArr[$i]['CLIENT_ID']]['EXPIRY_DT'];
                if($nameTempArr[$clientInfoArr[$i]['CLIENT_ID']]['DISPLAY'] == 'Y')
                     $dataArray[$i]['CLIENT_NAME'] = $nameTempArr[$clientInfoArr[$i]['CLIENT_ID']]['NAME'];
            }
            $this->dataArray = $dataArray;
        }
        unset($exclusiveObj,$nameOfUserObj,$expiryDateObj,$clientUsername);
    }
    public function executeClientFollowupHistory(sfWebRequest $request)
    {
        $agent = $request['name'];
        $this->columnNamesArr = array("Member ID","Client ID" ,"Added On(Date)", "Followup Status 1", "Followup Status 2", "Followup Status 3", "Followup Status 4");
        $exclusiveObj = new billing_EXCLUSIVE_FOLLOWUPS("newjs_slave");
        $clientUsername = new JPROFILE("newjs_slave");
        $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
        $clientfollowupArr = $exclusiveObj->clientFollowupHistory($agent);
        if(is_array($clientfollowupArr)){
           foreach ($clientfollowupArr as $key => $value) {
                $clientIdArr[] = $value["MEMBER_ID"];
                $clientId[] = $value["CLIENT_ID"];
                $dateOld = $value["ENTRY_DT"];
                $dateOld = explode(" ", $dateOld);
                $newDateArr[] = date("d-m-Y", strtotime($dateOld[0]));
                if(!($value["STATUS"]=="F0"))
                	$date_1 = $value["FOLLOWUP1_DT"];
                else 
                	$date_1 = null;
                
                if(!($value["STATUS"]=="F1"))
                	$date_2 = $value["FOLLOWUP2_DT"];
                else
                	$date_2 = null;
                if(!($value["STATUS"]=="F2"))
                	$date_3 = $value["FOLLOWUP3_DT"];
                else
                	$date_3 = null;
                if(!($value["STATUS"]=="F3"))
                	$date_4 = $value["FOLLOWUP4_DT"];
                else
                	$date_4 = null;
                
                if($date_1 != NULL && $date_1 != '0000-00-00'){
               		$date_1 = explode(" ", $date_1);
                $newFollow1Arr[] = date("d-m-Y", strtotime($date_1[0]));
                }
                else{
                    $newFollow1Arr[] = "";
                }
                if($date_2 != NULL && $date_2 != '0000-00-00'){
                $date_2 = explode(" ", $date_2);
                $newFollow2Arr[] = date("d-m-Y", strtotime($date_2[0]));
                }
                else{
                    $newFollow2Arr[] = "";
                }
                if($date_3 != NULL && $date_3 != '0000-00-00'){
                $date_3 = explode(" ", $date_3);
                $newFollow3Arr[] = date("d-m-Y", strtotime($date_3[0]));
                }
                else{
                    $newFollow3Arr[] = "";
                }
                if($date_4 != NULL && $date_4 != '0000-00-00'){
                $date_4 = explode(" ", $date_4);
                $newFollow4Arr[] = date("d-m-Y", strtotime($date_4[0]));
                }
                else{
                    $newFollow4Arr[] = "";
                }
            }
            $clientIdStr = implode(",", $clientIdArr);
            //$clientName = implode(",", $clientId);
            $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID" => $clientIdStr), "", "", "PROFILEID,NAME,DISPLAY");
            foreach($clientNameArr as $key => $val){
                $nameTempArr[$val["PROFILEID"]] = $val;
            }
            $usernameArr = $clientUsername->getAllSubscriptionsArr($clientIdArr);
            $clientUserName = $clientUsername->getAllSubscriptionsArr($clientId);
            $count = count($newDateArr);
            for($i = 0; $i<$count; $i++){
                $statusArr1[$i] = NULL;
                $statusArr2[$i] = NULL;
                $statusArr3[$i] = NULL;
                $statusArr4[$i] = NULL;
                if(!($value["STATUS"]=="F0"))
                	$date_1 = $value["FOLLOWUP1_DT"];
                	else
                		$date_1 = null;
                
                if(!($clientfollowupArr[$i]["STATUS"] == "F0"))		
                	$followDate1 = $clientfollowupArr[$i]['FOLLOWUP1_DT'];
                else 
                	$followDate1 = null;
                
                if(!($clientfollowupArr[$i]["STATUS"] == "F1"))
               		$followDate2 = $clientfollowupArr[$i]['FOLLOWUP2_DT'];
            	else
            		$followDate2 = null;
                	
                if(!($clientfollowupArr[$i]["STATUS"] == "F2"))
                	$followDate3 = $clientfollowupArr[$i]['FOLLOWUP3_DT'];
                else
                	$followDate3 = null;
                	
                if(!($clientfollowupArr[$i]["STATUS"] == "F3"))
                	$followDate4 = $clientfollowupArr[$i]['FOLLOWUP4_DT'];
                else
                	$followDate4 = null;
                	
                /* $followDate2 = $clientfollowupArr[$i]['FOLLOWUP2_DT'];
                $followDate3 = $clientfollowupArr[$i]['FOLLOWUP3_DT'];
                $followDate4 = $clientfollowupArr[$i]['FOLLOWUP4_DT']; */
                	
                	
                $followupStatus = $clientfollowupArr[$i]['STATUS'];
                
                if($followDate1 != null && $followDate1 !='0000-00-00' && $followDate2 != null && $followDate2 !='0000-00-00'){
                	$statusArr1[$i] = 'Follow Up';
                }else if($followDate1 != null && $followDate1 !='0000-00-00'){
                	if($followupStatus == 'Y'){
                		$statusArr1[$i] = 'CONFIRMED';
                	}else if($followupStatus == 'N'){
                		$statusArr1[$i] = 'DECLINE';
                	}else{
                		$statusArr1[$i] = 'Follow Up';
                	}
                }
                
                if($followDate2 != null && $followDate2 !='0000-00-00' && $followDate3 != null && $followDate3 !='0000-00-00'){
                	$statusArr2[$i] = 'Follow Up';
                }else if($followDate2 != null && $followDate2 !='0000-00-00'){
                	if($followupStatus == 'Y'){
                		$statusArr2[$i] = 'CONFIRMED';
                	}else if($followupStatus == 'N'){
                		$statusArr2[$i] = 'DECLINE';
                	}else{
                		$statusArr2[$i] = 'Follow Up';
                	}
                }
                
                if($followDate3 != null && $followDate3 !='0000-00-00' && $followDate4 != null && $followDate4 !='0000-00-00'){
                	$statusArr3[$i] = 'Follow Up';
                }else if($followDate3 != null && $followDate3 !='0000-00-00'){
                	if($followupStatus == 'Y'){
                		$statusArr3[$i] = 'CONFIRMED';
                	}else if($followupStatus == 'N'){
                		$statusArr3[$i] = 'DECLINE';
                	}else{
                		$statusArr3[$i] = 'Follow Up';
                	}
                }
                
                if($followDate4 != null && $followDate4 !='0000-00-00'){
                	if($followupStatus == 'Y'){
                		$statusArr4[$i] = 'CONFIRMED';
                	}else if($followupStatus == 'N'){
                		$statusArr4[$i] = 'DECLINE';
                	}else{
                		$statusArr4[$i] = 'Follow Up';
                	}
                }
     
                /* if($followDate1 !=NULL && $followDate1 !='0000-00-00')
                {
                    $statusArr1[$i] = 'Follow Up';
                    if($followDate2 !=NULL && $followDate2 !='0000-00-00')
                    {
                        $statusArr2[$i] = 'Follow Up';
                        if($followDate3 !=NULL && $followDate3 !='0000-00-00')
                        {
                            $statusArr3[$i] = 'Follow Up';
                            if($followDate4 !=NULL && $followDate4 !='0000-00-00')
                            {
                                $statusArr4[$i] = 'Follow Up';
                            }
                            else{
                                if($followupStatus == 'Y'){
                                    $statusArr4[$i] = 'Confirm';
                                }
                                elseif ($followupStatus == 'N') {
                                    $statusArr4[$i] = 'Decline';
                                }
                            }
                        }
                        else{
                                if($followupStatus == 'Y'){
                                    $statusArr3[$i] = 'Confirm';
                                }
                                elseif ($followupStatus == 'N') {
                                    $statusArr3[$i] = 'Decline';
                                }
                        }
                    }
                    else{
                        if($followupStatus == 'Y')
                        {
                            $statusArr2[$i] = 'Confirm';
                        }
                        elseif ($followupStatus == 'N') {
                            $statusArr2[$i] = 'Decline';
                        }
                    }
                }
                else
                {
                    if($followupStatus == 'Y')
                    {
                        $statusArr1[$i] = 'CONFIRMED';
                    }
                    elseif ($followupStatus == 'N') {
                        $statusArr1[$i] = 'DECLINE';
                    }
                } */
            }
            for ($i = 0; $i < $count; $i++) {
                $clientfollowupArr[$i]['ENTRY_DT'] = $newDateArr[$i];
                
                $followupArr[$i] = explode("|", $clientfollowupArr[$i]['FOLLOWUP_1']);
             	$clientfollowupArr[$i]['FOLLOWUP_1'] = $followupArr[$i][0];
             	if(count($followupArr[$i])==3 && !empty($followupArr[$i][1])){
             		$clientfollowupArr[$i]['FOLLOWUP_1'] = $clientfollowupArr[$i]['FOLLOWUP_1'].'('.$followupArr[$i][1].')';
             	}
             	
             	$followupArr[$i] = explode("|", $clientfollowupArr[$i]['FOLLOWUP_2']);
                $clientfollowupArr[$i]['FOLLOWUP_2'] = $followupArr[$i][0];
                if(count($followupArr[$i])==3 && !empty($followupArr[$i][1])){
                	$clientfollowupArr[$i]['FOLLOWUP_2'] = $clientfollowupArr[$i]['FOLLOWUP_2'].'('.$followupArr[$i][1].')';
                }
                
                $followupArr[$i] = explode("|", $clientfollowupArr[$i]['FOLLOWUP_3']);
                $clientfollowupArr[$i]['FOLLOWUP_3'] = $followupArr[$i][0];
                if(count($followupArr[$i])==3 && !empty($followupArr[$i][1])){
                	$clientfollowupArr[$i]['FOLLOWUP_3'] = $clientfollowupArr[$i]['FOLLOWUP_3'].'('.$followupArr[$i][1].')';
                }
                
                $followupArr[$i] = explode("|", $clientfollowupArr[$i]['FOLLOWUP_4']);
                $clientfollowupArr[$i]['FOLLOWUP_4'] = $followupArr[$i][0];
                if(count($followupArr[$i])==3 && !empty($followupArr[$i][1])){
                	$clientfollowupArr[$i]['FOLLOWUP_4'] = $clientfollowupArr[$i]['FOLLOWUP_4'].'('.$followupArr[$i][1].')';
                }
                $clientfollowupArr[$i]['FOLLOWUP1_DT'] = $newFollow1Arr[$i];
                $clientfollowupArr[$i]['FOLLOWUP2_DT'] = $newFollow2Arr[$i];
                $clientfollowupArr[$i]['FOLLOWUP3_DT'] = $newFollow3Arr[$i];
                $clientfollowupArr[$i]['FOLLOWUP4_DT'] = $newFollow4Arr[$i];
                $clientfollowupArr[$i]['USERNAME'] = $usernameArr[$clientfollowupArr[$i]['MEMBER_ID']]["USERNAME"];
                
                $clientfollowupArr[$i]['Client_User_Name'] = $clientUserName[$clientfollowupArr[$i]['CLIENT_ID']]["USERNAME"];
                
                $clientfollowupArr[$i]['STATUS1'] = $statusArr1[$i];
                $clientfollowupArr[$i]['STATUS2'] = $statusArr2[$i];
                $clientfollowupArr[$i]['STATUS3'] = $statusArr3[$i];
                $clientfollowupArr[$i]['STATUS4'] = $statusArr4[$i];
                if($nameTempArr[$clientfollowupArr[$i]['MEMBER_ID']]['DISPLAY'] == 'Y'){
                    $clientfollowupArr[$i]['CLIENT_NAME'] = $nameTempArr[$clientfollowupArr[$i]['MEMBER_ID']]['NAME'];
                }
            }
            $this->dataArray = $clientfollowupArr; 
           
        }
        unset($exclusiveObj,$clientUsername,$nameOfUserObj);
    }
    public function executeWelcomeCalls(sfWebRequest $request) {
        $agent = $request['name'];
        //Get all clients here
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $nameOfUserObj = new incentive_NAME_OF_USER();
        $purchaseObj = new BILLING_PURCHASES();


        $combinedIdArr = $exclusiveServicingObj->getClientsForWelcomeCall('CLIENT_ID', $agent, 'ASSIGNED_DT');

        if(is_array($combinedIdArr) && !empty($combinedIdArr)){
            $combinedIdArr = array_keys($combinedIdArr);
            $combinedIdStr = implode(",",$combinedIdArr);

            $nameOfUserArr = $nameOfUserObj->getArray(array("PROFILEID" => $combinedIdStr), "", "", "PROFILEID,NAME,DISPLAY");

            $userNames = $purchaseObj->getUserName($combinedIdArr);

            foreach($nameOfUserArr as $key=>$value){
                $nameOfUserArr[$key]["USERNAME"] = $userNames[$value["PROFILEID"]];
            }

            $this->welcomeCallsProfiles = $nameOfUserArr;
        } else{
            $this->welcomeCallsProfiles = $combinedIdArr;
        }
        $this->welcomeCallsProfilesCount = count($this->welcomeCallsProfiles);
    }

    public function executeWelcomeCallsPage2(sfWebRequest $request) {

        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];

        $from = $request['from'];
        
        $mailType = $request->getParameter('mailType');
        //check if user is eligible for new handling
        if($from == 'search'){
            $username = $request['username'];
            if($username){
                $jprofileObj = new JPROFILE("newjs_slave");
                $details = $jprofileObj->get($username,"USERNAME","USERNAME,PROFILEID");
            } else{
                $details=false;
            }
            if(!details){
                $module="jsexclusive";
                $action="welcomeCallsPage2";
                $params=array("notFound"=>true);
                $this->notFound=true;
                //$this->forwardTo($module,$action,$params);
            }
            $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
            $userDetails = $exclusiveServicingObj->getAllDataForClient($details['PROFILEID']);
            if(!$userDetails){
                $module="jsexclusive";
                $action="welcomeCallsPage2";
                $params=array("notFound"=>true);
                $this->notFound=true;
                //$this->forwardTo($module,$action,$params);
            }
            $this->client=$details['PROFILEID'];
        }

        $this->profileChecksum= JsOpsCommon::createChecksumForProfile($this->client);
        //Get all clients here
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        if($request["submit"]){
            $username = $request->getParameter("clientUserid");
            $username = preg_replace('/\s+/', '', $username);
            if($username == ""){
                $this->message = "Please enter a username";
                $this->error = 1;
            }
            else{
                $jprofileObj = new JPROFILE("newjs_slave");
                $details = $jprofileObj->get($username,"USERNAME","USERNAME,PROFILEID");
                if(is_array($details)){
                    $exclusiveLib = new ExclusiveFunctions();
                    $exclusiveLib->actionsToBeTakenForProfilesToBeFollowedup(array($details["PROFILEID"]),$this->client,$agent,$mailType, true);
                    $this->message = "Follow up added successfully. Proposal mail sent to $username. Please DO NOT add the same ID again.";
                }
                else{
                    $this->message = "Invalid Username: ".$username;
                }
            }
        }
        
        // fetch the notes if any for the current user
        $this->clientNotes = $this->getClientNotes($this->client);
    }
    
    private function getClientNotes($clientId) {
        $exclusiveClientNotesObject = new incentive_EXCLUSIVE_CLIENT_NOTES();
        $clientNotes = $exclusiveClientNotesObject->getClientNotes($this->client);
        return ($clientNotes == null) ? null : $clientNotes;
    }
    
    public function executeSetClientServiceDay(sfWebRequest $request) {

        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];
        $submit = $request['submit'];
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $serviceDayArr = $exclusiveServicingObj->getServiceDay($this->client);
        $this->serviceDay = $serviceDayArr[0];
        $this->serviceDaySetDate = $serviceDayArr[1];
        $emailStage = $serviceDayArr[2];        //Getting current email stage to check if email was sent already
        
        $countArr = $exclusiveServicingObj->getDayWiseAssignedCount($agent);
        $this->dayWiseCountArr = array('MON'=>($countArr['MON']==''?0:$countArr['MON'])
                                    ,'TUE'=>($countArr['TUE']==''?0:$countArr['TUE'])
                                    ,'WED'=>($countArr['WED']==''?0:$countArr['WED'])
                                    ,'THU'=>($countArr['THU']==''?0:$countArr['THU'])
                                    ,'FRI'=>($countArr['FRI']==''?0:$countArr['FRI'])
                                    ,'SAT'=>($countArr['SAT']==''?0:$countArr['SAT'])
                                    ,'SUN'=>($countArr['SUN']==''?0:$countArr['SUN']));
        if($submit){
            $this->serviceDay = $request['serviceDay'];
            $this->serviceDaySetDate = date('Y-m-d');
            $emailStageNew = 'Q';//Marking Email stage as pending in queue
            $status = $exclusiveServicingObj->setServiceDay($this->client,$this->serviceDay,$emailStageNew);
            if($status == true && $emailStage!='Q' && $emailStage!='C'){        ///Send Email only for the first time service day is set
                //Push to RabbitMQ delayed queue to send "After Welcome Call Email"
                $exclusiveObj = new ExclusiveFunctions();
                $pswrd = new jsadmin_PSWRDS();
                $agentDetails = $pswrd->getExecutiveDetails($agent);
                $fromName=$agentDetails['FIRST_NAME'] . $agentDetails['LAST_NAME'];     //Complete Name for alias in email
                $fromEmail=$agentDetails['EMAIL'];
                $firstname=$agentDetails['FIRST_NAME'];
                $phone = $agentDetails['PHONE'];
                $agentEmail = $agentDetails['EMAIL'];
                $serviceDay = $exclusiveObj->getCompleteDay($this->serviceDay); //Get the full day like Saturday from day code like SAT
                $producerObj=new Producer();        //Push the message to delayed queue for sending email after 2 hours
                if($producerObj->getRabbitMQServerConnected()){
                    $sendMailData = array('process' =>'EXCLUSIVE_DELAYED_EMAIL',
                                            'data'=>array('type' => 'EXCLUSIVE_WELCOME_EMAIL',
                                                            'fromName'=>$fromName,
                                                            'profileid'=>$this->client,
                                                            'firstname'=>$firstname,
                                                            'phone'=>$phone,
                                                            'serviceDay'=>$serviceDay,
                                                            'senderEmail'=>$fromEmail,
                                                            'agentEmail'=>$agentEmail),
                                            'redeliveryCount'=>0 );
                    $producerObj->sendMessage($sendMailData);
                }
            }
        }
        
        
    }

    public function executeUploadBiodata(sfWebRequest $request) {
/* Key for:
 * $this->invalidFile:
 *                  1- Size exceeds 5 MB
 *                  2- Invalid file type
 *                  3- General Processing error
 */
        //Location where file will be uploaded and downloaded from
        $fileLocation = sfConfig::get("sf_web_dir"). "/uploads/ExclusiveBiodata/"; 
        //Max size of file allowed
        $maxsize = 5 * 1024 * 1024;
        //Allowed File Types:
        $allowedExtension=array('pdf','rtf','doc','docx','jpg','jpeg','txt');
        $this->allowedExtension = implode(", ", $allowedExtension);
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];
        $action = $request['useraction'];
        if ($action == "") {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata['BIODATA_LOCATION'];
            $biodataUploadDate = $biodata['BIODATA_UPLOAD_DT'];
            if ($biodataLocation == NULL || $biodataLocation == "") {
                $this->freshUpload = true;
            } else {
                $this->freshUpload = false;
            }
        } else if ($action == 'deleteBioData') {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata['BIODATA_LOCATION'];
            if(unlink($biodataLocation)){
                $this->deleteStatus = $exclusiveServicingObj->deleteBioData($this->client);
                $this->freshUpload = true;
            }else{
                $this->deleteStatus = $exclusiveServicingObj->deleteBioData($this->client);
                $this->invalidFile = 3;
                $this->freshUpload = false;
            }
        } else if ($action == 'viewBioData') {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata['BIODATA_LOCATION'];
            $ext = end(explode('.', $biodataLocation));
            $file = "BioData-$this->client.".$ext;
            $xlData=file_get_contents($biodataLocation);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            echo $xlData;
            die;
        } else if ($action == 'uploadBioData') {
            $upload = $request->getParameter('upload');
            $fileParam = $request->getFiles('uploaded_csv');
            $csvUpload = $request->getParameter($csvUpload);
            if ($upload == 'Upload') {
                $fileTemp = $fileParam['tmp_name'];
                $fileName = $fileParam['name'];
                $fileType = $fileParam['type'];
                $filesize = $_FILES["photo"]["size"];
                $ext = end(explode('.', $fileName));
                $nameWithoutExt = explode('.', $fileName)[0];
                if ($filesize > $maxsize)
                    $this->invalidFile = 1;
                if (!in_array($ext, $allowedExtension)) {
                    $this->invalidFile = 2;
                } else {
                    $location = $fileLocation . $this->client . $_FILES['uploaded_csv']['name'];
                    if (move_uploaded_file($_FILES['uploaded_csv']['tmp_name'], $location)) {
                        $exclusiveServicingObj->setBioDataLocation($this->client, $location);
                        $this->uploadSuccess = true;
                        $this->freshUpload = false;
                    } else {
                        $this->invalidFile = 3;
                    }
                }
            }
        }
    }
    
    public function executeTodaysClients($request){
        $this->cid = $request['cid'];
        $exclusiveObj = new billing_EXCLUSIVE_SERVICING();
        $this->todaysClientProfiles = $exclusiveObj->getDayWiseAssignedAgent($request['name'],  strtoupper(date('D')));
    }
    
    public function executeClientNotesSubmission($request) {
        $welcomeCallsPage2Page = "welcomeCallsPage2";
        $screenRBInterestPage = "screenRBInterest";
        $clientId = $request['client'];
        $notes = $request['notes'];
        
        $this->client = $request->getParameter('client');

        $exclusiveClientNotesObject = new incentive_EXCLUSIVE_CLIENT_NOTES();
        $exclusiveClientNotesObject->setClientNotes($clientId, $notes);
        die;
    }
    
    public function executeAddFollowUpFromMatchMail($request){
        $this->cid = $request['cid'];
        $this->client = $request->getParameter('client');
        $this->agent = $request->getParameter('name');
        $this->mailType = $request->getParameter('mailType');
        $formArr = $request->getParameter('followupForm');
        $followupObj = new billing_EXCLUSIVE_MAIL_LOG_FOR_FOLLOWUPS("newjs_masterRep");
        $exclusiveLib = new ExclusiveFunctions();
        if($request->getParameter('submit')){
            foreach($formArr as $profileid => $val){
                if($val == 'Y'){
                    $yesArr[] = $profileid;
                }
                else if ($val == 'N'){
                    $noArr[] = $profileid;
                }
            }
            if(is_array($yesArr)){
                $exclusiveLib->actionsToBeTakenForProfilesToBeFollowedup($yesArr,$this->client,$this->agent, $this->mailType);
            }
            if(is_array($noArr)){
                $followupObj->updateStatusForClientId(implode(",", $noArr),'N');
            }
        }
        
        $undecidedData = $followupObj->getDataDateWise($this->client, 'U');
        
        
        $acceptanceIdArr = $exclusiveLib->returnAcceptanceIdArr($undecidedData);
        
        if($request->getParameter('declined')){
            $noData = $followupObj->getDataDateWise($this->client, 'N');
            $noIdArr = $exclusiveLib->returnAcceptanceIdArr($noData);

            if(is_array($noIdArr)){
                $jprofileObj = new JPROFILE('newjs_masterRep');
                $this->declinedArr = $jprofileObj->getAllSubscriptionsArr($noIdArr);
            }
        }
        else{
        
            $matchMailData = $exclusiveLib->formatScreenRBInterestsData($this->clientData,$acceptanceIdArr);        
            $this->matchMailFollowUpData = $exclusiveLib->formatDataForMatchMail($undecidedData,$matchMailData);
        }
        
        unset($exclusiveLib);
    }


    public function executePendingConcalls(sfWebRequest $request) {
        $agent = $request['name'];
        $executedFor = $request['executedFor'];
        //This if statement will be true if the concall executed button is pressed and the row id of the row to be deleted will be passed
        if ($executedFor) {
            $exclFollowupsObj = new billing_EXCLUSIVE_FOLLOWUPS();
            $date = date('Y-m-d H:i:s');
            $status = 'Y';
            $exclFollowupsObj->markConcallStatusForId($executedFor, $status, $date);
        }
        $date = date('Y-m-d');
        $exclFollowupsObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $dataArray = $exclFollowupsObj->getPendingConcallsEntries($date, $agent);
        $count = count($dataArray);
        
        //Start: Code to Group data array to keep same client ID together
        $this->array_sort_by_column($dataArray, 'CONCALL_SCH_DT',SORT_DESC);
        $perClientArray = array();
        for($i=0;$i<$count;$i++){
            $perClientArray[$dataArray[$i]['CLIENT_ID']][]=$dataArray[$i];
        }
        foreach ($perClientArray as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $sortedDataArray[] = $value2;
            }
        }
        $dataArray = $sortedDataArray;
        //End: Code to Group data array to keep same client ID together
        $this->columnNamesArr = array("S.No.", "DateAdded", "Client ID", "Client Name", "Client Number 1", "Client Number 2", "Member ID", "Member Name", "Member Phone No 1", "Member Phone No 2", "Action");
        $clientIdArr = array();
        $memberIdArr = array();
        
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $clientIdArr[$i] = $dataArray[$i]['CLIENT_ID'];
                $memberIdArr[$i] = $dataArray[$i]['MEMBER_ID'];
            }
            $combinedIdArr = array_merge($clientIdArr, $memberIdArr);
            $combinedIdArr = array_unique($combinedIdArr);
            $combinedIdArr = array_values($combinedIdArr);
            //Getting information for all ids
            $jprofileObj = new JPROFILE("newjs_slave");
            $contactObj = new ProfileContact("newjs_slave");
            $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");

            //Start:fetch primary mobile num and username of all ids 
            $combinedIdStr = implode($combinedIdArr, ",");
            $phoneDetails = $jprofileObj->getArray(array("PROFILEID" => $combinedIdStr), "", "", "PROFILEID,USERNAME,PHONE_MOB,PHONE_WITH_STD");//phonewithstd
            $n = count($phoneDetails);
            $noPhoneWithStd=array();
            for ($i = 0; $i < $n; $i++) {
                $phoneDetailsAltered[$phoneDetails[$i]['PROFILEID']] = $phoneDetails[$i];
                if($phoneDetails[$i]['PHONE_WITH_STD']==""){
                    $noPhoneWithStdArr[]=$phoneDetails[$i]['PROFILEID'];
                }
            }
            unset($phoneDetails);
            $phoneDetails = $phoneDetailsAltered;
            unset($phoneDetailsAltered);
            unset($i);
            unset($n);
            //End:fetch primary mobile num and username of all ids 
            //Start:fetch alternate mobile number for ids where phone_with_std was blank
            $noPhoneWithStdStr = implode($noPhoneWithStdArr, ",");
            $altPhoneDetails = $contactObj->getArray(array("PROFILEID" => $noPhoneWithStdStr), "", "", "PROFILEID,ALT_MOBILE");
            $n = count($altPhoneDetails);
            for ($i = 0; $i < $n; $i++) {
                $altPhoneDetailsAltered[$altPhoneDetails[$i]['PROFILEID']] = $altPhoneDetails[$i];
            }
            unset($altPhoneDetails);
            $altPhoneDetails = $altPhoneDetailsAltered;
            unset($phoneDetailsAltered);
            unset($i);
            unset($n);
            //End:fetch alternate mobile num for all ids
            //Start: fetch name of all ids
            $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID" => $combinedIdStr), "", "", "PROFILEID,NAME,DISPLAY");
            $n = count($clientNameArr);
            for ($i = 0; $i < $n; $i++) {
                $clientNameArrAltered[$clientNameArr[$i]['PROFILEID']] = $clientNameArr[$i];
            }
            unset($clientNameArr);
            $clientNameArr = $clientNameArrAltered;
            unset($clientNameArrAltered);
            unset($i);
            unset($n);
            //End: Fetch name of all ids
            //Start:Putting all details in a single array for displaying
            for ($i = 0; $i < count($combinedIdArr); $i++) {
                $detailsArray[$combinedIdArr[$i]]['USERNAME'] = $phoneDetails[$combinedIdArr[$i]]['USERNAME'];
                $detailsArray[$combinedIdArr[$i]]['PHONE_MOB'] = $phoneDetails[$combinedIdArr[$i]]['PHONE_MOB'];
                if($altPhoneDetails[$combinedIdArr[$i]]['ALT_MOBILE'])
                    $detailsArray[$combinedIdArr[$i]]['ALT_MOBILE'] = $altPhoneDetails[$combinedIdArr[$i]]['ALT_MOBILE'];
                else
                    $detailsArray[$combinedIdArr[$i]]['ALT_MOBILE'] = $phoneDetails[$combinedIdArr[$i]]['PHONE_WITH_STD'];
                if ($clientNameArr[$combinedIdArr[$i]]['DISPLAY'] == 'Y') {
                    $detailsArray[$combinedIdArr[$i]]['NAME'] = $clientNameArr[$combinedIdArr[$i]]['NAME'];
                }
            }
            $count = count($dataArray);
            for ($i = 0; $i < $count; $i++) {
                $dataArray[$i]['CLIENT_USERNAME'] = $detailsArray[$dataArray[$i]['CLIENT_ID']]['USERNAME'];
                $dataArray[$i]['CLIENT_NAME'] = $detailsArray[$dataArray[$i]['CLIENT_ID']]['NAME'];
                $dataArray[$i]['CLIENT_PH1'] = $detailsArray[$dataArray[$i]['CLIENT_ID']]['PHONE_MOB'];
                $dataArray[$i]['CLIENT_PH2'] = $detailsArray[$dataArray[$i]['CLIENT_ID']]['ALT_MOBILE'];
                $dataArray[$i]['MEMBER_USERNAME'] = $detailsArray[$dataArray[$i]['MEMBER_ID']]['USERNAME'];
                $dataArray[$i]['MEMBER_NAME'] = $detailsArray[$dataArray[$i]['MEMBER_ID']]['NAME'];
                $dataArray[$i]['MEMBER_PH1'] = $detailsArray[$dataArray[$i]['MEMBER_ID']]['PHONE_MOB'];
                $dataArray[$i]['MEMBER_PH2'] = $detailsArray[$dataArray[$i]['MEMBER_ID']]['ALT_MOBILE'];
                $dataArray[$i]['SNO'] = $i + 1;
            }
            $this->displayData = $dataArray;
            $this->totalCount = $i;
        }
        else{//Case where no profiles exist for particular agent.
            $this->infoMsg="No Profiles exist for you today!";
        }
        unset($dataArray);
        unset($detailsArray);
        //End:Putting all details in a single array for displaying
    }

    /**
    * Executes followupCaller action
    * follow ups of all exclusive clients done by all RM's
    * @param sfRequest $request A request object
    */
    public function executeFollowupCaller(sfWebRequest $request){
        //columns list for interface
        $this->columnNamesArr = crmCommonConfig::$jsexlusiveFollowUpColumns;
        $currentDt = date("Y-m-d");
        $fetchedList = JsMemcache::getInstance()->lrange('handledProfile','0','-1');
        foreach($fetchedList as $key => $val){
        	$highlighted[$val] = 1;
        }
        $this->highlighted = $highlighted;
        $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $this->followUpsCount = $followUpObj->getPendingFollowUpEntriesCount($currentDt);
        unset($followUpObj);

        if($this->followUpsCount == 0){
           $this->infoMsg = "No followUps found.."; 
        }
        else{
            $this->infoMsg = $request->getParameter("infoMsg");
            $exclusiveLib = new ExclusiveFunctions();
            $this->finalFollowUpsPool = $exclusiveLib->formatFollowUpsData($this->followUpsCount);
            $currentTime = date('Y-m-d H:i:s');
            
           /* $expireTime =  date('Y-m-d', strtotime('+1 day',strtotime(date('Y-m-d'))))." 00:00:00";
            print_r(array($currentTime,$expireTime)); */
            unset($exclusiveLib);
        }
        //print_r($this->finalFollowUpsPool);die;
    }

    public function executeSubmitFollowupStatus(sfWebRequest $request){
        $formArr = $request->getParameterHolder()->getAll();

        if(is_array($formArr)){
            $this->ifollowUpId = intval($formArr["ifollowUpId"]);
            $this->istatus = $formArr["istatus"];

            //submit of followup form
            if(isset($formArr["submit"])){
                if($formArr["submit"]=="Submit"){

                    //fetch follow up details corresponding to follow up id
                    $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS();
                    $followUpDetails = $followUpObj->getFollowUpEntry($this->ifollowUpId);
                    unset($followUpObj);

                    //update followup
                    if(is_array($followUpDetails) && $followUpDetails["STATUS"]==$this->istatus){
                        $exclusiveLib = new ExclusiveFunctions();
                        if($formArr["yearValue"] && $formArr["monthValue"] && $formArr["dayValue"]){
                            $formArr["date1"] = $formArr["yearValue"]."-".$formArr["monthValue"]."-".$formArr["dayValue"];
                        }
                        $exclusiveLib->updateFollowUpDetails(array("operator"=>$this->name,"followupStatus"=>$formArr["followupStatus"],"ifollowUpId"=>$this->ifollowUpId,"followUpDetails"=>$followUpDetails,"reason"=>$formArr["reason"],"reasonText"=>$formArr["reasonText"],"date1"=>$formArr["date1"]));
                        unset($exclusiveLib);
                    }
                    else{
                        $this->forwardTo("jsexclusive","followupCaller",array("infoMsg"=>"Retry followUp submit !"));
                    }
                }
                // add followup ID to the redis object if the followID(set by agent)  date is equal to current date
                if($formArr["date1"] == date('Y-m-d')){
                	$exclusiveLib = new ExclusiveFunctions();
                	$exclusiveLib->addDataToRedisObject('handledProfile',$this->ifollowUpId);
                	$this->forwardTo("jsexclusive","followupCaller");
                	unset($exclusiveLib);
                }
                $this->forwardTo("jsexclusive","followupCaller");
            }
            else{
                $this->clientUsername = $formArr["iclient"];
                $this->memberUsername = $formArr["imember"];
                
                $this->todayDay = date('d',strtotime(date("Y-m-d")));
                $this->todayMonth   = date('m',strtotime(date("Y-m-d")));
                $this->todayYear  = date('Y',strtotime(date("Y-m-d")));
                $this->dayArr = GetDateArrays::getDayArray();
                $this->monthArr   = GetDateArrays::getMonthArray();
                $this->yearArr    = array();
                $dateArr      = GetDateArrays::generateDateDataForRange($this->todayYear,$this->todayYear+1);
                foreach(array_keys($dateArr) as $key=>$value){
                    $this->yearArr[] = array('NAME'=>$value, 'VALUE'=>$value);
                }

            }
        }
    }
    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}
?>
