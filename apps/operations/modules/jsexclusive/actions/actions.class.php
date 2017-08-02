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
        $exclusiveObj = new billing_EXCLUSIVE_SERVICING();
        //Counter for welcome calls
        $this->welcomeCallsCount = $exclusiveObj->getWelcomeCallsCount($agent);
        unset($exclusiveObj);
        
        //To get count for pending con calls for menu page
        $exclFollowupsObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $date = date('Y-m-d');
        $this->pendingConcallsCount = $exclFollowupsObj->getPendingConcallsCount($date,$agent);
        unset($exclFollowupsObj);
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
            $biodataLocation = $biodata[0];
            $biodataUploadDate = $biodata[1];
            if ($biodataLocation == NULL || $biodataLocation == "") {
                $this->freshUpload = true;
            } else {
                $this->freshUpload = false;
            }
        } else if ($action == 'deleteBioData') {
            $biodata = $exclusiveServicingObj->checkBioData($this->client);
            $biodataLocation = $biodata[0];
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
            $biodataLocation = $biodata[0];
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


    public function executePendingConcalls(sfWebRequest $request) {
        $agent = $request['name'];
        $date = date('Y-m-d');
        $exclFollowupsObj = new billing_EXCLUSIVE_FOLLOWUPS();
        $dataArray = $exclFollowupsObj->getPendingConcallsEntries($date,$agent);
        $this->columnNamesArr=array("S.No.","DateAdded","Client ID","Client Name","Client Number 1","Client Number 2","Member ID","Member Name","Member Phone No 1","Member Phone No 2","Action");
        $count= count($dataArray);
        $clientIdArr = array();
        for($i=0;$i<$count;$i++){
            $clientIdArr[$i]=$dataArray[$i]['CLIENT_ID'];
            $memberIdArr[$i]=$dataArray[$i]['MEMBER_ID'];
        }
        $combinedIdArr= array_merge($clientIdArr,$memberIdArr);
        $combinedIdArr=array_unique($combinedIdArr); 
        $combinedIdArr=array_values($combinedIdArr); 
        //Getting information for all ids
        $jprofileObj = new JPROFILE("newjs_slave");
        $contactObj = new ProfileContact("newjs_slave");
        $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");

        //Start:fetch primary mobile num and username of all ids 
        $combinedIdStr = implode($combinedIdArr,",");     
        $phoneDetails = $jprofileObj->getArray(array("PROFILEID"=>$combinedIdStr),"","","PROFILEID,USERNAME,PHONE_MOB");
        $n=count($phoneDetails);
        for($i=0;$i<$n;$i++){
            $phoneDetailsAltered[$phoneDetails[$i]['PROFILEID']]=$phoneDetails[$i];
        }
        unset($phoneDetails);
        $phoneDetails = $phoneDetailsAltered;
        unset($phoneDetailsAltered);
        unset($i);
        unset($n);
        //End:fetch primary mobile num and username of all ids 
        
        //Start:fetch alternate mobile num for all ids
        $altPhoneDetails = $contactObj->getArray(array("PROFILEID"=>$combinedIdStr),"","","PROFILEID,ALT_MOBILE");
        $n=count($altPhoneDetails);
        for($i=0;$i<$n;$i++){
            $altPhoneDetailsAltered[$altPhoneDetails[$i]['PROFILEID']]=$altPhoneDetails[$i];
        }
        unset($altPhoneDetails);
        $altPhoneDetails = $altPhoneDetailsAltered;
        unset($phoneDetailsAltered);
        unset($i);
        unset($n);
        //End:fetch alternate mobile num for all ids

        //Start: fetch name of all ids
        $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID"=>$combinedIdStr),"","","PROFILEID,NAME,DISPLAY");
        $n=count($clientNameArr);
        for($i=0;$i<$n;$i++){
            $clientNameArrAltered[$clientNameArr[$i]['PROFILEID']]=$clientNameArr[$i];
        }
        unset($clientNameArr);
        $clientNameArr = $clientNameArrAltered;
        unset($clientNameArrAltered);
        unset($i);
        unset($n);
        //End: Fetch name of all ids

        //Putting all details in a single array for displaying
        for($i=0;$i<count($combinedIdArr);$i++){
            $detailsArray[$combinedIdArr[$i]]['USERNAME']=$phoneDetails[$combinedIdArr[$i]]['USERNAME'];
            $detailsArray[$combinedIdArr[$i]]['PHONE_MOB']=$phoneDetails[$combinedIdArr[$i]]['PHONE_MOB'];
            $detailsArray[$combinedIdArr[$i]]['ALT_MOBILE']=$altPhoneDetails[$combinedIdArr[$i]]['ALT_MOBILE'];
            if($clientNameArr[$combinedIdArr[$i]]['DISPLAY']=='Y'){
                $detailsArray[$combinedIdArr[$i]]['NAME']=$clientNameArr[$combinedIdArr[$i]]['NAME'];
            }
        }
            
        
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

        $followUpObj = new billing_EXCLUSIVE_FOLLOWUPS("newjs_masterRep");
        $this->followUpsCount = $followUpObj->getPendingFollowUpEntriesCount($currentDt);
        
        if($this->followUpsCount == 0){
           $this->infoMsg = "No followUps found.."; 
        }
        else{
            $start = 0;
            $limit = crmCommonConfig::$followupslimit;
            $jprofileObj = new JPROFILE("newjs_slave");
            $contactObj = new ProfileContact("newjs_slave");
            $nameOfUserObj = new incentive_NAME_OF_USER("newjs_slave");
            $this->finalFollowUpsPool = array("followUpData"=>array(),"membersData"=>array(),"clientsData"=>array());

            while($start<=$this->followUpsCount){
                //fetch followup data
                $followUpsPool = $followUpObj->getPendingFollowUpEntries($currentDt,$limit,$start); 

                if(is_array($followUpsPool)){
                    //merge the follow up pool
                    $this->finalFollowUpsPool["followUpData"] = array_merge($this->finalFollowUpsPool["followUpData"],$followUpsPool);

                    //fetch distinct member ids
                    $membersIds = array_map(function ($arr) { return $arr['MEMBER_ID']; }, $followUpsPool);  
                    $membersIds = array_values($membersIds);   
                    $memberIdStr = implode($membersIds,",");
                    unset($membersIds);

                    //fetch primary and alternate contact nos of member ids     
                    $phoneDetails = $jprofileObj->getArray(array("PROFILEID"=>$memberIdStr),"","","PROFILEID,USERNAME,PHONE_MOB");
                    $altPhoneDetails = $contactObj->getArray(array("PROFILEID"=>$memberIdStr),"","","PROFILEID,ALT_MOBILE");
                    unset($memberIdStr);
                    
                    //merge contact details
                    if(is_array($phoneDetails)){
                        foreach ($phoneDetails as $key => $value) {
                            $this->finalFollowUpsPool["membersData"][$value['PROFILEID']]['PHONE_MOB'] = $value['PHONE_MOB'];
                            $this->finalFollowUpsPool["membersData"][$value['PROFILEID']]['USERNAME'] = $value['USERNAME'];
                        }
                    }
                    unset($phoneDetails);
                    if(is_array($altPhoneDetails)){
                        foreach ($altPhoneDetails as $key => $value) {
                            $this->finalFollowUpsPool["membersData"][$value['PROFILEID']]['ALT_MOBILE'] = $value['ALT_MOBILE'];
                        }
                    }
                    unset($altPhoneDetails);

                    //fetch distinct client ids
                    $clientIds = array_map(function ($arr) { return $arr['CLIENT_ID']; }, $followUpsPool);  
                    $clientIds = array_values($clientIds); 
                   
                    $clientIdStr = implode($clientIds,",");
                    unset($clientIds);

                    //fetch name,username of clients
                    $clientNameArr = $nameOfUserObj->getArray(array("PROFILEID"=>$clientIdStr),"","","PROFILEID,NAME,DISPLAY");
                    $clientUsernameArr = $jprofileObj->getArray(array("PROFILEID"=>$clientIdStr),"","","PROFILEID,USERNAME");
                    if(is_array($clientNameArr)){
                        foreach ($clientNameArr as $key => $value) {
                            if($value["DISPLAY"]=='Y'){
                                $this->finalFollowUpsPool["clientsData"][$value['PROFILEID']]['NAME'] = $value['NAME'];
                            }
                        }
                    }
                    unset($clientNameArr);
                    if(is_array($clientUsernameArr)){
                        foreach ($clientUsernameArr as $key => $value) {
                            $this->finalFollowUpsPool["clientsData"][$value['PROFILEID']]['USERNAME'] = $value['USERNAME'];
                        }
                    }
                    unset($clientUsernameArr);
                }   
                unset($followUpsPool); 
                $start += $limit;
            }
            unset($nameOfUserObj);
            unset($jprofileObj);
            unset($contactObj);
        }
        unset($followUpObj);
        //print_r($this->finalFollowUpsPool);die;
    }

    public function executeSubmitFollowupStatus(sfWebRequest $request){
        var_dump($request->getParameter("followUp"));die;
    }

}
?>
