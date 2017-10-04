<?php

/**
 * crmApi actions.
 *
 * @package    jeevansathi
 * @subpackage crmApi
 * @author     Ankita Gupta
 */
class crmApiActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }

  /**
  * Executes ProfileVerificationDocsSync api action
  * api to transfer profile verification files(input) in disk and DB
  * @param sfRequest $request A request object
  * @return api response
  */
  public function executeProfileVerificationDocsSyncV1(sfWebRequest $request)
  {
  	$apiObj=ApiResponseHandler::getInstance();
    //$_FILES = $request->getParameter("DOCUMENTS");
    $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
    if(!$_FILES["docs"])
    {
    	$successFlag = false;
    	$apiObj->setHttpArray(CrmResponseHandlerConfig::$NO_SYNC_DATA);
    }
    else if(!$request->getParameter("proofType") || !$request->getParameter("docType") || !$request->getParameter("profileid"))
    {
      $successFlag = false;
      $apiObj->setHttpArray(CrmResponseHandlerConfig::$MISSING_PARAM);
    }
    else
	{
    $_FILES["docs"]["proofType"] = $request->getParameter("proofType");
    $_FILES["docs"]["docType"] = $request->getParameter("docType");
    $_FILES["docs"]["profileid"] = $request->getParameter("profileid");

	    $execName = $request->getAttribute('operatorName');
	    $profileDocumentsVerificationServiceObj = new ProfileDocumentVerificationService();
	    //filter allowed docs to sync based on proof type
	    $filesToSync = $profileDocumentsVerificationServiceObj->filterAllowedVerificationDocs($_FILES["docs"]);
      $successFlag = true;
	    if(is_array($filesToSync) && $filesToSync)
	    {
	    	foreach ($filesToSync as $profileid => $docs) {
	    		$profileObj=OPERATOR::getInstance('newjs_master',$profileid);
	    		$profileObj->getDetail($profileid,'PROFILEID','MSTATUS,DTOFBIRTH,INCOME,EDU_LEVEL_NEW,PARENTS_CONTACT,CONTACT');
	    		//save docs in disk
	    		$docToInsert = $profileDocumentsVerificationServiceObj->performUpload($docs,$profileid);
	    		//add entries in PROFILE_VERIFICATION.DOCUMENTS table
          if($docToInsert)
					$result = $profileDocumentsVerificationServiceObj->performDbInsert($profileObj,$execName,$docToInsert);
				else
					$result = null;
				if(!$result)
					$successFlag = false;
				unset($profileObj);
	    	}
	    }
	    else
	    {
	    	$successFlag = false;
        $apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_FILE_FORMAT); 
        $output["syncDone"] = $successFlag;
        $apiObj->setResponseBody($output);
        $apiObj->generateResponse();
        die();
	    }
	    unset($filesToSync);
	    if($successFlag==true)
	    {
	    	//sync of all docs successful
	    	$apiObj->setHttpArray(CrmResponseHandlerConfig::$CRM_SYNC_SUCCESS);  
	    }
	    else
	    {
	    	//sync of all docs failed
	    	$apiObj->setHttpArray(CrmResponseHandlerConfig::$CRM_SYNC_FAILURE);
	    }
	}
	$output["syncDone"] = $successFlag;
	$apiObj->setResponseBody($output);
	$apiObj->generateResponse();
	die();
  }
  /*
   * API for Profile Document Search for CRM App
   * API Details:
   *    Path: http://<branch>/operations.php/crmApi/ProfileDocumentSearchV1
   *    Input Parameters:
   *        crmAppApi = 1;
   *        username = <username>
   *        AUTHCHECKSUM = <AUTHCHECKSUM>
   *        HTTP_USER_AGENT = JSCAndroid
   */
  public function executeProfileDocumentSearchV1(sfWebRequest $request)
  {
      $apiObj = ApiResponseHandler::getInstance();
      $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
      $username = $request->getParameter('username');
      if($username)
      {
        $backendObj = new backendActionsLib(array("JPROFILE"=>"newjs_master"));
        $this->profile = $backendObj->validateProfileUsername($username,$detailsRequired="PROFILEID,MSTATUS,DTOFBIRTH,INCOME,EDU_LEVEL_NEW,PARENTS_CONTACT,CONTACT,SCREENING");
        unset($backendObj);
        if($this->profile)
          $profileid = $this->profile->getPROFILEID();
        else
          $profileid = null;
         /*$this->profile = Operator::getInstance();
         $this->profile->getDetail($username,'USERNAME','PROFILEID,MSTATUS,DTOFBIRTH,INCOME,EDU_LEVEL_NEW,PARENTS_CONTACT,CONTACT');
         $profileid = $this->profile->getPROFILEID();
         if($profileid == NULL || $profileid == '') //if invalid username*/
          if($this->profile==null)
         {
             $result = '';
             $apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_USERNAME);
         }
		    else
        {
            $profileDocumentsVerificationServiceObj = new ProfileDocumentVerificationService();
            $documentListMapping = $profileDocumentsVerificationServiceObj->getDocumentsList($this->profile);
            $docAttributes = PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
            $docs= PROFILE_VERIFICATION_DOCUMENTS_ENUM::$DOCUMENTS;
            $attributeValues = $profileDocumentsVerificationServiceObj->getProfileVerificationValue($this->profile);
            foreach($documentListMapping as $key => $val){
                foreach($val as $k => $v){
                    $docType[$v] = $docs[$v];
                }
                $output[] = array('attribute' => $key,
                                'attributeName' => $docAttributes[$key],
                                'attributeValue' => $attributeValues[$key],
                                'documentType' => $docType,
                                );
                unset($docType);
            }
            $address = $this->profile->getCONTACT();
            if(!Flag::isFlagSet("CONTACT",$this->profile->getSCREENING()))
                $address.= "[Under Screen]";
            $result =array('username' => $username,
                           'profileId' => $profileid,
                           'address' => $address,
                           'profileDocument' => $output);
            $apiObj->setHttpArray(CrmResponseHandlerConfig::$VALID_USERNAME);
        }
      }
      else
      {
          $result = '';
          $apiObj->setHttpArray(CrmResponseHandlerConfig::$MISSING_PARAM);
      }
      $request->setParameter("crmAppApi",1);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die;
  }

  /**
  * Executes editProfileAddressApiV1 action
  *
  * @param sfRequest $request A request object
  */
  public function executeEditProfileAddressApiV1(sfWebRequest $request)
  {
    $apiObj=ApiResponseHandler::getInstance();
    $backendObj = new backendActionsLib(array("JPROFILE"=>"newjs_master"));
    $result["editDone"] = true;  //flag for edit success/failure
    $username = $request->getParameter("USERNAME");
    $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
    $profileObj=$backendObj->validateProfileUsername($username,"PROFILEID,SCREENING");
    //validate username if provided as input else show error
    if(!$username || ($profileObj==null))
    {
      $result["editDone"] = false;
      $apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_USERNAME);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die();
    }
    if($request->getParameter("CONTACT"))
    {
      $newContact = htmlentities($request->getParameter("CONTACT"));
      //truncate address to defined limit(1000 char) if longer
      if(strlen($newContact)>EditProfileEnum::$lengthArr['CONTACT'])
        $newContact= substr($newContact,0,EditProfileEnum::$lengthArr['CONTACT']);
      $editFieldsArray = array("CONTACT"=>$newContact);

      //set screening field
      $oldScreenFlag = $profileObj->getSCREENING();

      $newScreenFlag = Flag::removeFlag("CONTACT", $oldScreenFlag);
      if($newScreenFlag!=$oldScreenFlag)
          $editFieldsArray['SCREENING']=$newScreenFlag;

      //update edit log
      $updateLog = $editFieldsArray;
      if(count($updateLog))
      {
        $szAgentName = $request->getAttribute('operatorName');
        $profileid = $profileObj->getPROFILEID();
        $updateLog["MOD_DT"] = date('Y-m-d H:i:s');
        $updateLog["PROFILEID"] = $profileid;
        $updateLog["SOURCE"] = "B_" . substr($szAgentName,0,8);
        $editLog =new EDIT_LOG();
        $editLog->log_edit($updateLog,$profileid,JsConstants::$useMongoDb,true);
      }
      
      //update details of profile
      $backendObj->editProfileDetails($editFieldsArray,$username,"USERNAME");    
      unset($backendObj);
      
      $result["editDone"] = true;
      $apiObj->setHttpArray(CrmResponseHandlerConfig::$EDIT_PROFILE_SUCCESS); 
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die();
    }
    else    //new address not provided as input--error
    {
      $result["editDone"] = false;
      $apiObj->setHttpArray(CrmResponseHandlerConfig::$MISSING_PARAM);
      $apiObj->setResponseBody($result);
      $apiObj->generateResponse();
      die();
    }
  }
  /**
  * Executes logout api action
  * api to logout from crm(used only in FSO at this time)
  * @param sfRequest $request A request object
  * @return api response
  */
  public function executeApiLogoutV1(sfWebRequest $request)
  {
    $apiObj=ApiResponseHandler::getInstance();
    $registrationid=$request->getParameter("REGISTRATION_ID");

    if(MobileCommon::isCrmApp()=="A")
    {
      //unregister from FSO app notifications
      $notificationObj = new BrowserNotification();
      $notificationObj->manageRegistrationid($registrationid,'','',"CRM_AND");
      unset($notificationObj);
    }

    $output['logoutDone'] = true;
    $apiObj->setHttpArray(CrmResponseHandlerConfig::$CRM_LOGOUT_SUCCESS);
    $apiObj->setAuthChecksum("");
    $apiObj->setResponseBody($output);
    $apiObj->generateResponse();
    die();
  }

  /**
  * Executes CHECKIN/CHECKOUT api action
  * api to CHECKIN/CHECKOUT from crm(used only in FSO at this time)
  * @param sfRequest $request A request object
  * @return api response
  */
  public function executeLogCheckinCheckoutV1(sfWebRequest $request)
  {
    $apiObj = ApiResponseHandler::getInstance();
    if(!$request->getParameter("logType") || !$request->getParameter("latitude") || !$request->getParameter("longitude") || !$request->getParameter("timestamp") || !$request->getAttribute('operatorName') || !$request->getParameter('clientName') || !$request->getParameter('agentLocation'))
    {
      	$successFlag = false;
      	$apiObj->setHttpArray(CrmResponseHandlerConfig::$MISSING_PARAM);
    } else {
    	$operatorName = $request->getAttribute('operatorName');
    	$logType = $request->getParameter('logType');
    	$latitude = $request->getParameter('latitude');
    	$longitude = $request->getParameter('longitude');
    	$tempTime = $request->getParameter('timestamp'); // unix timestamp
    	$timestamp = date("Y-m-d H:i:s", $tempTime);
    	$clientName = $request->getParameter('clientName');
    	$agentLocation = $request->getParameter('agentLocation');
    	$jprofileObj = new JPROFILE('newjs_slave');
    	$check = $jprofileObj->checkUsername(strtoupper($clientName));
    	if($check == 1){
    		$incCrmChkObj = new incentive_CRM_AGENT_CHECKIN_CHECKOUT_LOG();
	    	$incCrmChkObj->insert($operatorName, $clientName, $logType, $agentLocation, $latitude, $longitude, $timestamp);
	    	$successFlag = true;
	    	$apiObj->setHttpArray(CrmResponseHandlerConfig::$CRM_SYNC_SUCCESS);	
    	} else {	
	    	$successFlag = false;
    		$apiObj->setHttpArray(CrmResponseHandlerConfig::$INVALID_USERNAME);
    	}
    }
    $output['syncDone'] = $successFlag;
    $apiObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
    $apiObj->setResponseBody($output);
    $apiObj->generateResponse();
    die();
  }
  
  /*crm api to track notification delivery 
  * @input: $request - sfRequest object
  * @return : api response
  */
  public function executeApiNotificationDeliveryV1(sfRequest $request)
  {
    $output["DeliveryStatus"] = false;
    $respObj = ApiResponseHandler::getInstance();
    $respObj->setAuthChecksum($request->getAttribute("AUTHCHECKSUM"));
    $notificationKey = $request->getParameter('NOTIFICATION_KEY');
    $messageId = $request->getParameter('MSG_ID');
    if(!$messageId || !$notificationKey)
    {
      $respObj->setHttpArray(CrmResponseHandlerConfig::$MISSING_PARAM);
    }
    else
    {
      $currentDate = date('Y-m-d H:i:s');
      $browserNotificationObj = new BrowserNotification();
      //update status for delivered notification
      $updateArr = array("RECEIVED_STATUS"=>'Y',"RECEIVED_DATE"=>$currentDate); 
      $browserNotificationObj->updateSentNotificationDetails("MSG_ID",$messageId,$updateArr);
      unset($browserNotificationObj);
      $output["DeliveryStatus"] = true;
      if($output["DeliveryStatus"] == true)
        $respObj->setHttpArray(CrmResponseHandlerConfig::$CRM_SUCCESS);
      else
        $respObj->setHttpArray(CrmResponseHandlerConfig::$CRM_FAILURE);
    }
    $respObj->setResponseBody($output);
    $respObj->generateResponse();
    die;
  }

}
?>