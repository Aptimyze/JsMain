<?php

/**
 * profileVerification actions.
 *
 * @package    jeevansathi
 * @subpackage profileVerification
 * @author     Reshu Rajput / Lavesh rawat
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileVerificationActions extends sfActions
{
  /**
  * This function handles screening action
  * @param sfRequest $request A request object
  **/
  public function executeScreenSubmit(sfWebRequest $request)
  {
        $this->cid = $request->getParameter("cid");
        $this->name = $request->getAttribute('name');
	$formArr = $request->getParameterHolder()->getAll();
	$tempArr = $formArr["actionTaken"];
	$profileId = $formArr["profileid"];

	if(is_array($tempArr))
	{
		foreach($formArr["allDocIds"] as $k=>$v)
			if(!$tempArr[$v])
				$notAllActionTaken = 1;
	}
	else
		$notAllActionTaken=1;
			
	if($notAllActionTaken)
	/* handling erorr case(one of checkbox is not checked) : should hv been handled by js*/
	{
		$this->SubmissionError = 1;
	}
	else
	{
		$updateArr = $tempArr;
		$PROFILE_VERIFICATION_DOCUMENTS = new PROFILE_VERIFICATION_DOCUMENTS;
		$PROFILE_VERIFICATION_DOCUMENTS->multipleDocumentIdUpdate($updateArr,'VERIFIED_FLAG');

		foreach($updateArr as $k=>$v)			
			$docIds[]['DOCUMENT_ID'] = $k;
		$ProfileDocumentVerificationService = new ProfileDocumentVerificationService();
		$ProfileDocumentVerificationService->trackScreening($this->name,$docIds);
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING = new PROFILE_VERIFICATION_DOCUMENTS_SCREENING;
		$PROFILE_VERIFICATION_DOCUMENTS_SCREENING->del($profileId,$this->name);
		$this->successfulSubmission = 1;
        	$this->totalUnscreened = $ProfileDocumentVerificationService->getTotalUnscreenedProfileCount();
                /**
                 * Verification Seal for setting ProfileID in SWAP_JPROFILE for Solr search
                 */
                $profileObj = Operator::getInstance("",$profileId);
                $searchSetObj=new VerificationSealLib($profileObj);
                $searchSetObj->setForSolrSearch();
                //End
	}
	$this->setTemplate('screen');
  }


  /**
  * This function is used to 
  * 1. Allote user for screening of documents
  * 2. Display documents of user to be screened
  * 3. A search Option to screen user by searching by username
  * @param sfRequest $request A request object
  **/
  public function executeScreen(sfWebRequest $request)
  {
        $this->cid = $request->getParameter("cid");
        $this->name = $request->getAttribute('name');
        $this->execName = $this->name;
	$inputArr = $request->getParameterHolder()->getAll();

        //start: memcache functionality implemented to avoid user refreshing the page
        if($_GET['skipMemcache']!=1)
        {
                $key="PROFILE_VERIFICATION_".$this->name;

                if(JsMemcache::getInstance()->get($key))
                {
                        JsMemcache::getInstance()->set($key,$this->name,5);
                        exit("Please refresh after 5 seconds.");
                }
                else
                        JsMemcache::getInstance()->set($key,$this->name,5);
        }
        //end: memcache functionality implemented to avoid user refreshing the page


	/* Locking mechanism will make sure a single profile cannot be alloted to two differnt user at same time */
	$LockingService = new LockingService;
	$lockName =  'operations_profileVerification_action'; 
	$lock = $LockingService->getMysqlLock($lockName);

	$ProfileDocumentVerificationService = new ProfileDocumentVerificationService();
	$profileObj = new Operator;
	$this->totalUnscreened = $ProfileDocumentVerificationService->getTotalUnscreenedProfileCount();
	if($inputArr["username"]) 
	{
		/* allot profile based on searched user */
		$userName = $inputArr["username"];
		$profileObj->getDetail($userName,"USERNAME",'PROFILEID');
		$pid = $profileObj->getPROFILEID();
		if(!$pid)
			$this->userNameInvalid=1;
		$this->username = $userName;
	}
	else 
	{
		/* auto allocation logic */
		$fetchProfileAllocatinArr = $ProfileDocumentVerificationService->fetchProfileToAllot($this->name);
		if($fetchProfileAllocatinArr)
		{
			$pid = $fetchProfileAllocatinArr["PROFILEID"];
			$profileObj->getDetail($pid,"PROFILEID",'USERNAME');
			$this->username = $profileObj->getUSERNAME();	
			if(!$fetchProfileAllocatinArr["updateAllotTime"])
				$dontUpdateAllocationTime=1;
		}
	}


	if($pid)
	{
		$arr = $ProfileDocumentVerificationService->getUnscreenedDocuments($pid);
		if(is_array($arr))
		{
			if(!$dontUpdateAllocationTime)
				$ProfileDocumentVerificationService->allotProfile($pid,$this->name);
			$this->documentArr = $arr;
			$this->profileid = $pid;
			$this->docAttributes = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
			$this->docs = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
		}
		else
		{
			$this->noDocsAvailable = 1;
			$PROFILE_VERIFICATION_DOCUMENTS_SCREENING = new PROFILE_VERIFICATION_DOCUMENTS_SCREENING;
	                $PROFILE_VERIFICATION_DOCUMENTS_SCREENING->del($pid);
		}

	}
	else	
	{
		if(!$this->userNameInvalid)
			$this->noProfileAvailable = 1;
	}
	$LockingService->releaseMysqlLock($lockName);
  }

  /*
  *This action is to provide interface for uploading Profile Documents
   1. Search form is provided
   2. Search Action is performed in case of search form is submitted
   3. Upload action is performed in case upload form is submitted
  */
  public function executeProfileDocumentsUpload(sfWebRequest $request)
  {
	$formArr = $request->getParameterHolder()->getAll();
	$this->execname = $request->getAttribute("name");
	$this->cid = $request->getAttribute("cid");
	if(!$this->execname)
		$this->execname = $formArr["execname"];
	if(!$this->cid)
       		$this->cid = $formArr["cid"];
	$this->username = $formArr["username"];
	if($this->username)
	{
		$this->profile = Operator::getInstance();
		$this->profile->getDetail($this->username,'USERNAME','PROFILEID,MSTATUS,DTOFBIRTH,INCOME,EDU_LEVEL_NEW,PARENTS_CONTACT,CONTACT');
		if($this->profile->getPROFILEID()==NULL || $this->profile->getPROFILEID()=='') //if invalid username
			$this->error = 1;
		else
		{
			$this->profileId= $this->profile->getPROFILEID();
			$profileDocumentsVerificationServiceObj = new ProfileDocumentVerificationService();
			if($formArr["Submit"]=="Upload")
			{
				$files = $profileDocumentsVerificationServiceObj->validateFiles($_FILES);
				$this->fileError = $files["Error"];
				if(is_null($files["Error"]) && is_array($files["Valid"]))
				{
					$documentsToUpload = $profileDocumentsVerificationServiceObj->getDocumentsToInsert($formArr["doc"],$files["Valid"]);
					if(is_array($documentsToUpload))
					{
						$docToInsert = $profileDocumentsVerificationServiceObj->performUpload($documentsToUpload,$this->profileId);
			
						if($docToInsert)
							$result = $profileDocumentsVerificationServiceObj->performDbInsert($this->profile,$this->execname,$docToInsert);
						if($result)
							$this->output="Success";
						else
							$this->output ="Fail";
					}
				}
			}
		//	if($formArr["Submit"]=="Search" || !is_null($files["Error"])) condition Commented after PAT comment
			{
				$this->documentListMapping = $profileDocumentsVerificationServiceObj->getDocumentsList($this->profile);
				$this->documentView = $profileDocumentsVerificationServiceObj->getDocumentViewList($this->profileId);
				$this->docAttributes = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
        			$this->docs = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
				$this->attributeValues = $profileDocumentsVerificationServiceObj->getProfileVerificationValue($this->profile);
			}
		}
	}
  }

  /*
  * This function is used to perform View action of particular profile and given attribute
  */
  public function executeView(sfWebRequest $request)
  {
	$formArr = $request->getParameterHolder()->getAll();
        $this->execname = $request->getAttribute("name");
        $this->cid = $request->getAttribute("cid");
        if(!$this->execname)
                $this->execname = $formArr["execname"];
        if(!$this->cid)
                $this->cid = $formArr["cid"];
        $this->username = $formArr["username"];
	$this->doc = $formArr["doc"];
	if($this->username)
        {
                $this->profile = Operator::getInstance();
                $this->profile->getDetail($this->username,'USERNAME','PROFILEID');
                if($this->profile->getPROFILEID()==NULL || $this->profile->getPROFILEID()=='') //if invalid username
                        $this->error = 1;
                else
                {
                        $this->profileId= $this->profile->getPROFILEID();
                        $profileDocumentsVerificationServiceObj = new ProfileDocumentVerificationService();
			$details = "DOCUMENT_ID,DOCUMENT_TYPE,VERIFIED_FLAG,DOCURL";
			$this->documents = $profileDocumentsVerificationServiceObj->getAllProfileDocuments(array('PROFILEID'=>$this->profileId,'ATTRIBUTE'=>$this->doc,'DELETED_FLAG'=>PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DELETED_FLAG_ENUM["NOT_DELETED"]),$details);
			$this->docTypes = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
			$this->attribute = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES[$this->doc];
		}
	}
  }

  /*
  * This function is used to execute delete particular document and call view action after deletion
  */
  public function executeDelete(sfWebRequest $request)
  {
        $formArr = $request->getParameterHolder()->getAll();
        $this->execname = $request->getAttribute("name");
        $this->cid = $request->getAttribute("cid");
        if(!$this->execname)
                $this->execname = $formArr["execname"];
        if(!$this->cid)
                $this->cid = $formArr["cid"];
	$this->username = $formArr["username"];
        $this->doc =  $formArr["doc"];
	$this->id = $formArr["id"];
	if($this->id && $this->execname)
	{
		$profileDocumentsVerificationServiceObj = new ProfileDocumentVerificationService();
		$profileDocumentsVerificationServiceObj->deleteDocumentById($this->id,$this->execname);
	}
	$this->redirect(sfConfig::get("app_site_url")."/operations.php/profileVerification/view?cid=$this->cid&name=$this->execname&username=$this->username&doc=$this->doc");

  }
        /**
         * This action function is used to execute FSO Visit deletion for the purpose of Verification Seal Removal 
         */
        public function executeFsoRemoval(sfWebRequest $request) {
                $this->cid = $request->getParameter("cid");
                $this->name = $request->getAttribute('name');
                $this->execName = $this->name;
                $inputArr = $request->getParameterHolder()->getAll();
                $profileObj = new Operator;
                if ($inputArr["username"]) {
                        $userName = trim($inputArr["username"]);
                        if(strstr($userName, '@'))
                        {
                                $profileObj->getDetail($userName, "EMAIL", 'PROFILEID');
                                $pid = $profileObj->getPROFILEID();
                        }
                        else
                        {
                        $profileObj->getDetail($userName, "USERNAME", 'PROFILEID');
                        $pid = $profileObj->getPROFILEID();
                        }
                        
                        if (!$pid)
                                $this->userNameInvalid = 1;
                        $this->username = $userName;
                        $this->profileid = $pid;
                        $fsoCheckObj = new VerificationSealLib($profileObj);
                        $this->visit = $fsoCheckObj->checkFsoVisitSeal();
                        $this->reason = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$FSO_REMOVAL_REASON;
                }
        }

        /**
         * This action function is used to execute Subit FSO Visit deletion for the purpose of Verification Seal Removal 
         */
        public function executeFsoRemovalSubmit(sfWebRequest $request) {
                $this->cid = $request->getParameter("cid");
                $this->name = $request->getAttribute('name');
                $this->execName = $this->name;
                $inputArr = $request->getParameterHolder()->getAll();

                $profileObj = new Operator;
                if ($inputArr['profileid'] && $inputArr['reason'] != "-1" && ($inputArr['reason'] != "100" || strlen($inputArr['reasonOther']) > 4 )) {
                        $profileObj->getDetail($inputArr['profileid'], "PROFILEID", 'USERNAME');
                        $fsoRemoveObj = new VerificationSealLib($profileObj);
                        $fsoRemoveObj->unsetFsoVisitSeal($inputArr['reason'],$inputArr['reasonOther'], $inputArr['name']);
                        $this->username = $profileObj->getUSERNAME();
                        $this->output = "Success";
                }
                else {
                        $this->SubmissionError = "1";
                }

                $this->setTemplate('fsoRemoval');
        }

        /*interface to request field sales visit for user
        *@param : $request 
        */
        public function executeRequestFieldSalesVisit(sfWebRequest $request)
        {
        	$this->agentName = $request->getParameter("name");
        	$this->cid = $request->getParameter("cid");
        	$this->startYear = date("Y");
        	$this->endYear = date("Y",strtotime("+ 2 year"));
        	//show error message for invalid username
        	if($request->getParameter("ERROR")=="INVALID_USERNAME")
        		$this->errorMsg = "Invalid username !!!!";
          if($request->getParameter("SUCCESS")=="REQUEST_SAVED")
            $this->successMsg = "Field visit request has been saved successfully.";
          if($request->getParameter("ERROR")=="VISIT_ALREADY_DONE")
            $this->errorMsg = "Field visit for this profile has already done.";
          if($request->getParameter("ERROR")=="VISIT_ALREADY_PENDING")
            $this->errorMsg = "Field visit for this profile is already in pending state.";
         
        }

        /*save the field request visit submitted by agent for profile
        * @param : $request
        */
        public function executeSaveFieldVisitRequest(sfWebRequest $request)
        {
        	$inputArr = $request->getParameterHolder()->getAll();
        	$username = trim($inputArr["username"]);
        	
        		//update DB
            $curDatetime = date("Y-m-d H:i:s");
            $paramsArr = array("USERNAME"=>$username,"ENTRY_DT"=>$curDatetime,"REQUESTED_BY"=>$inputArr["agentName"]);
            //enter requested date 
            $paramsArr["REQUESTED_VISIT_DT"] = $inputArr["visit_date_dateLists_year_list"]."-".(intval($inputArr["visit_date_dateLists_month_list"])+1)."-".$inputArr["visit_date_dateLists_day_list"];
            //call api to insert entry
            $request->setParameter("fromInternal",1);
            $request->setParameter("FSAction","ADD");
            $request->setParameter("visitDetails",$paramsArr);
            $request->setParameter("sendSms",true);
            $request->setParameter("sendMail",true);
            unset($paramsArr);
            ob_start();    
            sfContext::getInstance()->getController()->getPresentationFor('profileVerification','manageFieldVisitsApi');
            $jsonResponse = ob_get_contents(); 
            ob_end_clean();
            if($request->getParameter("ERROR")=="INVALID_USERNAME")
            {
              //invalid username case
              $this->forwardTo("profileVerification","requestFieldSalesVisit?ERROR=INVALID_USERNAME");
            }
            else if($request->getParameter("ERROR")=="VISIT_ALREADY_DONE")
            {
              //invalid username case
              $this->forwardTo("profileVerification","requestFieldSalesVisit?ERROR=VISIT_ALREADY_DONE");
            }
            else if($request->getParameter("ERROR")=="VISIT_ALREADY_PENDING")
            {
              //invalid username case
              $this->forwardTo("profileVerification","requestFieldSalesVisit?ERROR=VISIT_ALREADY_PENDING");
            }
            else
            {
              //successful entry case
              $this->forwardTo("profileVerification","requestFieldSalesVisit?SUCCESS=REQUEST_SAVED");
            }
          
        }

		/*function to get list of pending field sales visits
		*@param : $request
		*/
		public function executeShowPendingFSVisits(sfWebRequest $request)
		{
			//columns list for interface
			$this->columnNamesArr = fsoInterfaceDisplay::$visitInterfacecolumnLabels;

      //fetch pending visits from FIELD_SALES_WIDGET
      $FSObj = new FieldSales();
      $pendingVisitsDetails = $FSObj->getFieldVisitDetails('N',"VISITED","PROFILEID,REQUESTED_BY,REQUESTED_VISIT_DT","ENTRY_DT");
      unset($FSObj);
      if(is_array($pendingVisitsDetails) && $pendingVisitsDetails)
      {
        $profileIdArr = array_keys($pendingVisitsDetails);

        //fetch profile details from JPROFILE
        $jprofileObj = new JPROFILE("newjs_slave");
        $jprofileDetails = $jprofileObj->getProfileSelectedDetails($profileIdArr,"PROFILEID,USERNAME,PHONE_MOB,EMAIL,CITY_RES");
        unset($profileIdArr);
      }

      //merge visit details and profile details
      if($pendingVisitsDetails && is_array($pendingVisitsDetails) && $jprofileDetails && is_array($jprofileDetails))
      {
        foreach ($pendingVisitsDetails as $profileId => $visitDetails) 
        {
          $pendingVisitsDetails[$profileId]["USERNAME"] = $jprofileDetails[$profileId]["USERNAME"];
          $pendingVisitsDetails[$profileId]["PHONE_MOB"] = $jprofileDetails[$profileId]["PHONE_MOB"];
          $pendingVisitsDetails[$profileId]["EMAIL"] = $jprofileDetails[$profileId]["EMAIL"];
          $pendingVisitsDetails[$profileId]["LOCATION"] = FieldMap::getFieldLabel("city_india",$jprofileDetails[$profileId]["CITY_RES"]);
        }
      }
      $this->result = $pendingVisitsDetails;
      unset($pendingVisitsDetails);
      unset($jprofileDetails);
		}

		/*api to handle field visit request(add/remove) for profiles
		* @params : $request
		*/
		public function executeManageFieldVisitsApi(sfWebRequest $request)
		{
      $success = false;
      $fromInternal = $request->getParameter("fromInternal");
      $action = $request->getParameter("FSAction");
      $visitDetails = $request->getParameter("visitDetails");
      //validate username
      $backendactionLibObj = new backendActionsLib(array("JPROFILE"=>"newjs_slave"));
      $profileObj = $backendactionLibObj->validateProfileUsername($visitDetails["USERNAME"],"PROFILEID");
      unset($backendactionLibObj);
      unset($visitDetails["USERNAME"]);
      if($profileObj==null)
        $success = false; 
      else
      {
        $fieldSalesObj = new FieldSales();
        if($action=="ADD")
        {
          $profileid = $profileObj->getPROFILEID();
          $existingVisitDetails = $fieldSalesObj->checkProfileid($profileid);
          if($existingVisitDetails['VISITED']=="Y" || $existingVisitDetails['VISITED']=="N")
          {
            $visited = $existingVisitDetails["VISITED"];
            $success =false;
          }
          else
          {
            //add new field visit request
            $sendMail = $request->getParameter("sendMail");
            $sendSms = $request->getParameter("sendSms");
            $visitDetails["VISITED"] = 'N';
            $visitDetails["PROFILEID"] = $profileid;
            $fieldSalesObj->insertSelectedParams($visitDetails); 
            $fieldSalesObj->postFieldVisitRequestSubmit($profileid,$sendMail,$sendSms);
            $success = true;
          }
        }
        else if($action=="REMOVE")
        {
          //mark field visit (done)
          $updateArr = array("VISITED"=>'Y');
          $fieldSalesObj->updateEntry("PROFILEID",$profileObj->getPROFILEID(),$updateArr);
          $success = true;
        }
        unset($fieldSalesObj);
      }
			$respObj = ApiResponseHandler::getInstance();
      if($success==true) 
      {
        $respObj->setHttpArray(CrmResponseHandlerConfig::$CRM_SUCCESS);
      } 
      else 
      {
        if($visited=="Y" || $visited=="N")
          $respObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_VISIT_REQUEST);
        else
        $respObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_USERNAME);
      } 
      $output["done"] = $success;
      $respObj->setResponseBody($output);

      if($respObj->getResponseBody() && !$fromInternal)
      {
        $respObj->generateResponse();
      }
      if($fromInternal==1)
      {
        $code = $respObj->getResponseStatusCode();
        if($code == "1")
        {
          $request->setParameter("ERROR","INVALID_USERNAME");
        }
        else if($code=="2")
        {
          if($visited=="Y")
            $request->setParameter("ERROR","VISIT_ALREADY_DONE");
          else if($visited=="N")
            $request->setParameter("ERROR","VISIT_ALREADY_PENDING");
        }
        else
        {
          $request->setParameter("SUCCESS","REQUEST_SAVED");
        }
        return sfView::NONE;
      }
      die();
		}

      /*forwards the request to given module action
      * @param : $module,$action
      */
      public function forwardTo($module,$action)
  		{
		    $url="/operations.php/$module/$action";
		    $this->redirect($url);
		}
                
        public function executeInappropriateUsersReport(sfWebRequest $request)
  {  

      $endDate=$request->getParameter('RAStartDate');
      $resultArr=(new MIS_INAPPROPRIATE_USERS_REPORT())->getReportForADate($endDate,10);
      ob_end_clean();
      if(sizeof($resultArr) == 0 )
          die;
      $i=0;

      echo json_encode($resultArr);
     // print_r($resultArr);
      return sfView::NONE;
      die;

  }
    public function executeInappropriateUsers(sfWebRequest $request)
    {
            $this->setTemplate('inappropriateUsers');

    }   

    public function executeFetchAbuseInvalidData(sfWebRequest $request)
    {
            $this->setTemplate('fetchAbuseInvalidData');
    } 


    public function executeFetchAbuseInvalidDataReport(sfWebRequest $request)
  {
      $Uname=$request->getParameter('inputUser');

      $obj = NEWJS_JPROFILE::getInstance();
      $profileID =  $obj->getProfileIdFromUsername($Uname);

      if($profileID == NULL)
      { 
      ob_end_clean();
      $resultArr = "No User With this name";  
      echo json_encode($resultArr);
      return sfView::NONE;
      die;
      }

      $resultAbuseArr=(new REPORT_ABUSE_LOG())->getReportAbuseHistoryOfUser($profileID);
      if($resultAbuseArr != NULL && is_array($resultAbuseArr))
      {  
      foreach ($resultAbuseArr as $key => $value) {
        $resultAbuseArr[$key]['TYPE'] = 'ABUSE';  
        $reporterName = $obj->getUsername($value['REPORTER']);
        $resultAbuseArr[$key]['REPORTER'] = $reporterName;
      }
      }
      $resultInvalidArr = (new JSADMIN_REPORT_INVALID_PHONE())->getReportInvalidForUser($profileID);
      if($resultInvalidArr != NULL && is_array($resultInvalidArr))
      {
      foreach ($resultInvalidArr as $key => $value) {
        $resultInvalidArr[$key]['DATE'] = $value['SUBMIT_DATE'];
        $resultInvalidArr[$key]['TYPE'] = 'INVALID';
        $reporterName = $obj->getUsername($value['SUBMITTER']);
        unset($resultInvalidArr[$key]['SUBMIT_DATE']);
        unset($resultInvalidArr[$key]['SUBMITTER']);
        $resultInvalidArr[$key]['REPORTER'] = $reporterName;
      }
    }

    if(is_array($resultAbuseArr) && is_array($resultInvalidArr)){
     $resultArr = array_merge($resultAbuseArr,$resultInvalidArr);
    }
    else if(is_array($resultAbuseArr) && !is_array($resultInvalidArr))
    {
      $resultArr = $resultAbuseArr;
    }
    else
    {
      $resultArr = $resultInvalidArr;
    }


     foreach ($resultArr as $key => $value) {
       $sortingArray[$key] = $value['DATE'];
     }

     array_multisort($sortingArray,SORT_DESC,$resultArr);

      ob_end_clean();
      if(sizeof($resultArr) == 0 )
          die;
      $i=0;
      echo json_encode($resultArr);
      return sfView::NONE;
      die;

  }      
                
}
