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
				$apObj = new ASSISTED_PRODUCT_AP_SEND_INTEREST_PROFILES();
				$this->assignedClients = $apObj->getSendersAfterDate($this->assignedClients);

				unset($apObj);
				if(is_array($this->assignedClients)){
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

			if($clientProfileObj){
				$this->horoscopeMatch = $clientProfileObj->getHOROSCOPE_MATCH();
				$this->clientData = array("clientUsername"=>$clientProfileObj->getUSERNAME(),"HoroscopeMatch"=>"N","PROFILEID"=>$this->clientId,"clientCaste"=>$clientProfileObj->getCASTE());
				$this->clientData["HoroscopeMatch"] = $this->horoscopeMatch;
				$this->clientData["gender"] = $clientProfileObj->getGENDER();
				unset($clientProfileObj);

				if(is_array($pogRBInterestsPids) && count($pogRBInterestsPids)>0){
					$exclusiveLib = new ExclusiveFunctions();
					$this->pogRBInterestsPool = $exclusiveLib->formatScreenRBInterestsData($this->clientData,$pogRBInterestsPids);
					unset($exclusiveLib);
				}
				else{
					$this->infoMsg = "No members for this client found..";
					$this->showNextButton = 'Y';
				}
			}
			unset($clientProfileObj);
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
				$exclusiveObj->processScreenedEois(array("agentUsername"=>$this->name,"clientId"=>$request->getParameter("clientId"),"acceptArr"=>$acceptArr,"discardArr"=>$discardArr));
				unset($exclusiveObj);
			}
			else{
				++$this->clientIndex;
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

    public function executeWelcomeCalls(sfWebRequest $request) {

        $agent = $request['name'];
        //Get all clients here
        $exclusiveServicingObj = new billing_EXCLUSIVE_SERVICING();
        $this->welcomeCallsProfiles = $exclusiveServicingObj->getClientsForWelcomeCall('CLIENT_ID', $agent, 'ASSIGNED_DT');
        $this->welcomeCallsProfilesCount = count($this->welcomeCallsProfiles);
    }

    public function executeWelcomeCallsPage2(sfWebRequest $request) {

        $agent = $request['name'];
        $this->cid = $request['cid'];
        $this->client = $request['client'];

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
                    $exclusiveLib->actionsToBeTakenForProfilesToBeFollowedup(array($details["PROFILEID"]),$this->client,$agent,true);
                    $this->message = "Follow up added successfully. Proposal mail sent to $username. Please DO NOT add the same ID again.";
                }
                else{
                    $this->message = "Invalid Username: ".$username;
                }
            }
        }

        $from = $request['from'];
        
        //check if user is eligible for new handling
        if($from == 'search'){
            $username = $request['username'];
            $jprofileObj = new JPROFILE("newjs_slave");
            $details = $jprofileObj->get($username,"USERNAME","USERNAME,PROFILEID");
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
                                                            'senderEmail'=>$fromEmail),
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
    
    public function executeAddFollowUpFromMatchMail($request){
        $this->cid = $request['cid'];
        $this->client = $request->getParameter('client');
        $this->agent = $request->getParameter('name');
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
                $exclusiveLib->actionsToBeTakenForProfilesToBeFollowedup($yesArr,$this->client,$this->agent);
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
                $this->forwardTo("jsexclusive","followupCaller");
            }
            else{
                $this->clientUsername = $formArr["iclient"];
                $this->memberUsername = $formArr["imember"];
                
                $this->todayDay = date('d',strtotime(date("Y-m-d") . "+1 day"));
                $this->todayMonth   = date('m',strtotime(date("Y-m-d") . "+1 day"));
                $this->todayYear  = date('Y',strtotime(date("Y-m-d") . "+1 day"));
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
