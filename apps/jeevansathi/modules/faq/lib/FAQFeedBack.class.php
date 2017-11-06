<?php

class FAQFeedBack
{
	private $m_arrLoginData;
	private $m_szUserName;
	private $m_szName;
	private $m_szEmail;
	public  $m_objForm;
	private $m_szCategory;
	private $m_szMessage;
	private $m_bValidForm;
	private $m_iTracePath;
	private $errorReq;
	private $webRequest;
	private static $REASON_MAP=array('duplicate profile','incorrect details/photo','already married/engaged','inappropriate content','spam','looks like a fake profile','other','one or more of profile details are incorrect','photo on profile doesn\'t belong to the person','user is using abusive/indecent language','user is stalking me with messages/calls','user is asking for money','user has no intent to marry','user is already married / engaged','user is not picking up phone calls','person on phone denied owning this profile','user\'s phone is switched off/not reachable','User\'s phone is invalid');
    
    private $m_bAttachmentExist;
    private $m_bAttachmentError;
    private $m_arrAttachmentErrorMsg;
    private $m_iAbuseAttachmentID;
    private $m_arrTempAttachments;
    const MAX_ALLOWED_ATTACHMENTS = 5;
    const UPLOAD_PATH = "/uploads/Abuse/";
    
        public function __construct($api=0)
	{
		if($api)
			$this->api=1;
		else
			$this->api=0;
		$this->m_bValidForm = false;
        $this->m_iAbuseAttachmentID = -1;
	}
	public function getTracePath()
	{
		return $this->m_iTracePath;
	}



	private function insertReportAbuseLog(){
		$newReasonsAndroid = array(7,8,9,10,11,12,13,14,16,18);
		$askOtherReasonAndroid = 0;
		$reasonNew=$this->webRequest->getParameter('reason');
		 $reasonMap=$this->webRequest->getParameter('reason_map');
                if($this->webRequest->getParameter('fromCRM')){
				$this->otherProfile=new Profile('',$this->webRequest->getParameter('reporteePFID'));  
		}
			else
			{
				$this->otherProfile=new Profile();	
			}

		if($this->webRequest->getParameter('profilechecksum'))
		{ 
			$otherProfileId = JsCommon::getProfileFromChecksum($this->webRequest->getParameter('profilechecksum'));
			$feed=$this->webRequest->getParameter('feed');
		}
		
		else {


                        $feed=$this->webRequest->getParameter('feed');
                        $reason=$feed['message'];

                        $pos=strpos($reason,':');
                        $reasonNew=trim(substr($reason,$pos+1));
                       
                        $pos2=strpos($reason,'by');
                        $arr2=split(' ',trim(substr($reason,$pos2+2)));
                        $otherUsername=trim($arr2[0]);
                        if(!$this->webRequest->getParameter('fromCRM')){  
                        $this->otherProfile->getDetail($otherUsername,"USERNAME");
                    }
                        $otherProfileId=$this->otherProfile->getPROFILEID();  

                }
	
                if($reasonMap)
                {	
                	if(in_array($reasonMap,$newReasonsAndroid))
                	{    
                		$askOtherReasonAndroid = 1;
                	}

                    $reasonMapArray=self::$REASON_MAP;
                    $reasonNew=$reasonMapArray[$reasonMap-1];
                    
                    if($reasonNew=='other')
                        $reasonNew=$this->webRequest->getParameter('other_reason');
                }

		$reasonsCategory=array('duplicate profile','incorrect details/photo','already married/engaged','looks like fake profile','inappropriate content','spam','looks like a fake profile','one or more of profile details are incorrect','photo on profile doesn\'t belong to the person','user is using abusive/indecent language','user is stalking me with messages/calls','user is asking for money','user has no intent to marry',
			'user is already married / engaged','user is not picking up phone calls','person on phone denied owning this profile','user\'s phone is switched off/not reachable','user\'s phone is invalid');

		if(in_array(strtolower($reasonNew),$reasonsCategory))
		{ 
			$categoryNew=$reasonNew;
			if($askOtherReasonAndroid == 1){
				$otherReason=$this->webRequest->getParameter('other_reason');
			}
			else{	 
						$otherReason = '';
					}
		}
		else
		{   
			$categoryNew='other';

			if($feed['mainReason'] != '' || $feed['mainReason'] != NULL )
			{   
				$categoryNew = $feed['mainReason'];
			}
			$otherReason=$reasonNew; 
		}

		$category = '';
		$crmUserName = '';

		if($this->webRequest->getParameter('fromCRM'))
		{
		 $reporterPFID = $this->webRequest->getParameter('reporterPFID');
		 $loginProfile = new Profile('',$reporterPFID);
		 $category = 'Ops';
		 $restInfo = $this->webRequest->getParameter('feed') ;
		 $crmUserName = $restInfo['crmUser'];
		}
		else{
		$loginProfile=LoggedInProfile::getInstance();
		}
		if(!$categoryNew || !$loginProfile->getPROFILEID() || !$otherProfileId) return;


		//extra work
		reportAbuseLib::reportAbuseAction(
			$loginProfile->getPROFILEID(), 
			$loginProfile->getUSERNAME(), 
			$otherProfileId, 
			$categoryNew, 
			$otherReason, 
			$category, 
			$crmUserName, 
			$this->m_iAbuseAttachmentID);
		
		$this->webRequest->setParameter('blockedOnAbuse',1);
		if(stristr($categoryNew, 'Already married/engaged') || stristr($categoryNew,'User is already married / engaged')){
			$ReportAbuseMailObj = new requestUserToDelete();
			$ReportAbuseMailObj->sendMailForDeletion($otherProfileId,'0');
		}


		

	}
    
    /**
     * 
     * @param sfWebRequest $request
     */
	private function extractInfo(sfWebRequest $request)
	{
		$this->m_iTracePath = $request->getParameter('tracepath');
		$requestArr= $request->getParameter('feed');
		if($this->m_iTracePath === null)
			$this->m_iTracePath = 0;
		
		$loginProfile=LoggedInProfile::getInstance();
		if($loginProfile->getPROFILEID() !== null)
		{
			$loginProfile=LoggedInProfile::getInstance();
			$this->m_szUserName = $loginProfile->getUSERNAME();// User Name
			$this->m_szEmail = $loginProfile->getEMAIL();
			$this->profileid = $loginProfile->getPROFILEID();
			//added this for caching
        	$nameOfUserOb=new NameOfUser();        
        	$nameOfUserArr = $nameOfUserOb->getNameData($profileid);
        	$name = $nameOfUserArr[$profileid]["NAME"];			

        	if($name)
        	{
        		$this->m_szName = $name;
        	}
        	else
        	{
        		$this->m_szName = "";
        	}
			/*$objNameStore = new incentive_NAME_OF_USER;
			$name=$objNameStore->getName($loginProfile->getPROFILEID());
			if($name)
				$this->m_szName = $objNameStore->getName($loginProfile->getPROFILEID());
			else
				$this->m_szName = "";*/
			unset($nameOfUserOb);
		}
		else
		{	
			if($requestArr['email'])
			$this->m_szEmail = $requestArr['email'];	
		}
		if($request->getParameter('setOption'))
			$this->setOption = $request->getParameter('setOption');
        
        $this->m_bAttachmentExist = false;
        if($requestArr['attachment'] == 1) {
          $this->m_bAttachmentExist = true;
          $this->processFileAttachment();
        }
	}
	
    /**
     * 
     * @param sfWebRequest $request
     * @return boolean
     */
	public function ProcessData(sfWebRequest $request)
	{   
        $this->webRequest=$request;
		$this->extractInfo($request);
		
		$this->m_objForm = new FeedBackForm($this->api);
		$arrDeafults = array('name'=>$this->m_szName,'username'=>$this->m_szUserName,'email'=>$this->m_szEmail);
		$dataArray = $this->webRequest->getParameter('feed');

        $apiResponseHandlerObj=ApiResponseHandler::getInstance();
        
        //If Attachement Exist and Error in attachement
        if($this->m_bAttachmentExist && $this->m_bAttachmentError) {
          $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$ABUSE_ATTACHMENT_ERROR); 
          //$error[message]='You cannot report abuse against the same person more than twice.';
          $apiResponseHandlerObj->setResponseBody(array("error"=>$this->m_arrAttachmentErrorMsg));
          $apiResponseHandlerObj->generateResponse();
          die;
        }
        
	if($dataArray['category'] == FeedbackEnum::CAT_ABUSE)
	{    
		if($this->webRequest->getParameter('fromCRM')){  
			$reporteeId=$this->webRequest->getParameter('reporteePFID');
			$profileObj = NEWJS_JPROFILE::getInstance();
			$dataArray = $this->webRequest->getParameter('feed');
			$reporterId = $profileObj->getProfileIdFromUsername($dataArray['reporter']);
			unset($profileObj);
		}
		else{  
			//print_r($this->m_szName);die('aaa');
			if(MobileCommon::isIOSApp())
			{    
				$loginProfile=LoggedInProfile::getInstance();
				$reporterId = $loginProfile->getPROFILEID();
     	$feed=$this->webRequest->getParameter('feed');
                        $reason=$feed['message'];
     	$pos2=strpos($reason,'by');
                        $arr2=split(' ',trim(substr($reason,$pos2+2)));
                        $otherUsername=trim($arr2[0]);
                 $profileObj = NEWJS_JPROFILE::getInstance();
     	$reporteeId = $profileObj->getProfileIdFromUsername($otherUsername); 
     	unset($profileObj);      
			}
			else
			{ 
		$reporteeId = JsCommon::getProfileFromChecksum($this->webRequest->getParameter('profilechecksum'));
		$profileObj = NEWJS_JPROFILE::getInstance();
     	$reporterId = $profileObj->getProfileIdFromUsername($this->m_szUserName);
     		}
     	unset($profileObj);
		}

		$reportAbuseObj = new REPORT_ABUSE_LOG();
		
	 	if(!$reportAbuseObj->canReportAbuse($reporteeId,$reporterId))
		{   
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$ABUSE_ATTEMPTS_OVER); 
			$error[message]='You cannot report abuse against the same person more than twice.';
			$apiResponseHandlerObj->setResponseBody($error);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		
	}	
		$this->m_objForm->setDefaults($arrDeafults); 
		if($request->isMethod('POST') && $request->getParameter('CMDSubmit'))
		{   
			$arrFeed = $request->getParameter('feed');
			if($arrFeed["email"])
				$this->m_objForm->bind($arrFeed);
			else
			{  
				$arrFeed["email"]=$this->m_szEmail;
				$this->m_objForm->bind($arrFeed);
			}

			if($this->m_objForm->isValid())
			{   
				if($this->m_objForm->getValue('name'))
					$this->m_szName 	= $this->m_objForm->getValue('name');
				if($this->m_objForm->getValue('username'))
					$this->m_szUserName 	= $this->m_objForm->getValue('username');
				
				if(!$this->api)
					$this->m_szEmail  		= $this->m_objForm->getValue('email');
				$this->m_szCategory  	= $this->m_objForm->getValue('category');
				if($this->m_iTracePath ==0)
					$this->m_iTracePath =$this->m_szCategory;
				$this->m_szMessage  	= $this->m_objForm->getValue('message');
				
				$this->m_bValidForm = true;

				$this->InsertFeedBack();		
                
			if($this->m_szCategory!=FeedbackEnum::CAT_ABUSE)
			{	
				$this->FwdMail();
				
			}
            
			return true;


			}
			else
			{  
				if($this->api)
				{
					$errorObj=$this->m_objForm->getErrorSchema();
					foreach ($errorObj as  $name => $error)
					{
						$errorArr[$name]=$error->getMessageFormat();
						//echo "--->".$errMes=$error->getMessage();
					}
				return $errorArr;
				}
			}

		}
		
		return false;
	}
	
	public function getForm()
	{
		return $this->m_objForm;
	}
	
	private function InsertFeedBack()
	{    
		if($this->m_bValidForm == false)
			return;

		$objTicketStore 			= new FEEDBACK_TICKETS;
		$objTicket_Message_STORE 	= new FEEDBACK_TICKET_MESSAGES;
		$objMIS_FeedBack_Result		= new MIS_FEEDBACK_RESULT;
		
		$arrResult = array();
		$objTicketStore->fetch_IDs($arrResult,$this->m_szUserName,$this->m_szEmail,$this->m_iTracePath);
		
		$ip=FetchClientIP();//Gets ipaddress of user
		$bNewTicket = true;
		
		foreach($arrResult as $row)
		{
			
			$szQuery = $objTicket_Message_STORE->fetch_QUERY($row['ID']);
			if($szQuery["QUERY"] == addslashes(stripslashes($this->m_szMessage)))
			{
				$bNewTicket = false;
				break;
			}
		}
		
		if($bNewTicket || $this->m_szCategory==FeedbackEnum::CAT_ABUSE)
		{
			// Now Insert in Following Stores 
			// 1) MIS_FEEDBACK_RESULT 
			// 2) TICKET_MESSAGES
			// 3) TICKETS
						
			//Insert in TICKETS Store
			$arrActualPara = array('name'=>$this->m_szName,'uname'=>$this->m_szUserName,'email'=>$this->m_szEmail,'tracepath'=>$this->m_iTracePath);
			//~ var_dump($arrActualPara);
			$iTicketID = $objTicketStore->Insert($arrActualPara);
			
			//Insert Message in TICKET_MESSAGE Store
			$szMsg = addslashes(stripslashes($this->m_szMessage));
            
            if(mb_detect_encoding($szMsg)) {
              $szMsg = urldecode($szMsg);
            }
            
			$objTicket_Message_STORE->Insert($iTicketID,$szMsg,$ip);
			
			//Insert in MIS_FEEDBACK_RESULT Store
			$objMIS_FeedBack_Result->Insert($this->m_szCategory,$iTicketID);

            if($this->m_bAttachmentExist) {
              $this->uploadDocs();
            }
			if($this->m_szCategory==FeedbackEnum::CAT_ABUSE)
			{ 
              
              $this->insertReportAbuseLog();
    		}	
		}
		
	}
	
	private function FwdMail()
	{
		$objFeedBackQAData			= new FEEDBACK_QADATA;
		$arrResult = $objFeedBackQAData->fetchQuestion($this->m_iTracePath);
		
		//Send Email 
		$msg="Name: $this->m_szName<BR>Username: $this->m_szUserName<BR>Message: " .nl2br(htmlspecialchars($this->m_szMessage));
		
		if($this->setOption == 'Y')
			$this->m_szCategory = 'abuse';
		if($this->m_szCategory)
			$subject="Jeevansathi Feedback [$this->m_szCategory] $unresolved";
		else
			$subject="Jeevansathi Feedback [".$arrResult['QUESTION']."] $unresolved";
		
		SendMail::send_email("bug@jeevansathi.com",$msg,$subject,$this->m_szEmail);
	}
  
  /*
   * Function to get category
   */
  public function getCategory()
  {
    return $this->m_szCategory;
  }
  
  /**
   * processFileAttachment
   * @return type
   */
  private function processFileAttachment()
  {
    $request = $this->webRequest;
    $arrFeed = $this->webRequest->getParameter('feed');
    //Get Files and check the file extension
    $arrFileAttachment = $_FILES['feed'];
    if( is_null($arrFileAttachment) && $arrFeed['temp_attachment_id'] ) {
      
      $tempAttachmentsIds = $arrFeed['temp_attachment_id'];
      $objTempAttachmentStore = new FEEDBACK_ABUSE_TEMP_ATTACHMENTS;
      $arrRecord = $objTempAttachmentStore->getRecord($tempAttachmentsIds);
      
      if( false === $arrRecord || false === is_array($arrRecord) ) {
        $this->m_bAttachmentError = true;
        $this->m_arrAttachmentErrorMsg["error"] = "No attachments exist on given temp ids";
      }
      $this->m_arrTempAttachments = $arrRecord[0];
      unset($objTempAttachmentStore);
      return ;
    }
    
    //JPEG, PNG, GIF, BMP
    $arrAllowedDocType = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
    $maxDocSize = 6291456; // 1048576 * 6 // 6MB
    $minDocSize = 1024; // 1 KB
    
    //Do Server Side Validation
    $bResult = true;
    $arrErrorMsg = array();
    
    $count = 0;
    foreach($arrFileAttachment['name'] as $key => $value) {
      $count++;
      if(false === $bResult) {
        break;
      }
      //Check Doc Type
      $bResult = in_array((exif_imagetype($arrFileAttachment['tmp_name'][$key])), $arrAllowedDocType);
      if($bResult == false) {
        $arrErrorMsg[$key] = "Invalid attachment type {$value}";
      }
      //Check Doc Size
      $bResult = $arrFileAttachment['size'][$key] > $minDocSize and $arrFileAttachment['size'][$key] <= $maxDocSize;
      if($bResult == false) {
        $arrErrorMsg[$key] = "You can attach a proof less than 6MB in size";
      }
      //Check for Max Allowed Attachments
      if($count > self::MAX_ALLOWED_ATTACHMENTS) {
        $maxCount = self::MAX_ALLOWED_ATTACHMENTS;
        $arrErrorMsg[$key] = "You can attach maximum {$maxCount} proofs.";
      }
    }
    
    $this->m_bAttachmentError = count($arrErrorMsg) ? true : false;
    $this->m_arrAttachmentErrorMsg = $arrErrorMsg;
  }
  
  /**
   * 
   */
  private function uploadDocs()
  {
    $arrFileAttachment = $_FILES['feed'];
    $arrFeed = $this->webRequest->getParameter('feed');
    if( is_null( $arrFileAttachment ) && $arrFeed[ 'temp_attachment_id' ] ) {
      return $this->uploadFromTempAttachments( $arrFeed[ 'temp_attachment_id' ] );
    }
    
    $strBaseUploadPath = self::UPLOAD_PATH;
    $strUploadPath = JsConstants::$docRoot.$strBaseUploadPath;
    
    if (false === is_dir($strUploadPath)) {
      mkdir($strUploadPath,0777,true);
    }
    
    $uniqueId = uniqid();
    /**
     * 
     */
    $arrUploadedPics = array();
    $count = 1;
    foreach($arrFileAttachment['name'] as $key => $value) {  
     
      if($count > self::MAX_ALLOWED_ATTACHMENTS) {
        break;
      }
      
      //Move to JS Server
      $strTargetFile = $strBaseUploadPath."{$uniqueId}_{$value}";
      $bResult = move_uploaded_file($arrFileAttachment["tmp_name"][$key], JsConstants::$docRoot.$strTargetFile);
      $strUrlPrefix = IMAGE_SERVER_ENUM::$appPicUrl;

      if($bResult) {
        $arrUploadedPics["DOC_{$count}"] = "{$strUrlPrefix}{$strTargetFile}";
        $count++;
      } else {
        //Trigger to alert issue while moving pics
      }
    }
    
    $objStore = new FEEDBACK_ABUSE_ATTACHMENTS();
    $recordID = $objStore->insertRecord($arrUploadedPics);
    
    $this->m_iAbuseAttachmentID = -1;
    if($recordID) {
      $this->m_iAbuseAttachmentID = $recordID;
      $this->moveToCloud($recordID, $arrUploadedPics);
    }
 
    return $recordID;
  }
  
  
  /**
   * 
   * @param type $request
   * @return type
   */
  public function uploadTempAttachments($request)
  {
    $this->webRequest=$request;
    
    $this->processFileAttachment();
    
    $arrFeed = $this->webRequest->getParameter('feed');
    
    if($this->m_bAttachmentError) {
      return $this->m_arrAttachmentErrorMsg;
    }
       
    $arrFileAttachment = $_FILES['feed'];
    $iTempAttachmentId = -1;
    if($arrFeed['attachment_id']) {
      $iTempAttachmentId = $arrFeed['attachment_id'];
    }
    
    $objStore = new FEEDBACK_ABUSE_TEMP_ATTACHMENTS();
    
    $count = 1;
    $arrAvailableKey = array(1=>"DOC_1",2=>"DOC_2",3=>"DOC_3",4=>"DOC_4",5=>"DOC_5");
    if( -1 != $iTempAttachmentId ) {
      $tempAttachmentRecords = $objStore->getRecord($iTempAttachmentId);
      $arrAvailableKey = array();
      if(is_array($tempAttachmentRecords)) {
        $tempAttachmentRecords = $tempAttachmentRecords[0];
        $temp = 1;
        foreach($tempAttachmentRecords as $key => $val) {
          if(0 === strlen($val)) {
            $arrAvailableKey[$temp] = $key;
            $temp++;
          }
        }
      }
    }
  
    $strBaseUploadPath = self::UPLOAD_PATH;
    $strUploadPath = JsConstants::$docRoot.$strBaseUploadPath;
    
    if (false === is_dir($strUploadPath)) {
      mkdir($strUploadPath,0777,true);
    }
    
    $uniqueId = uniqid();
    /**
     * 
     */
    $arrUploadedPics = array();
    $count = self::MAX_ALLOWED_ATTACHMENTS - count($arrAvailableKey);
    $iterator = 1;
    foreach($arrFileAttachment['name'] as $key => $value) {  
     
      if($count > self::MAX_ALLOWED_ATTACHMENTS) {
        $this->m_bAttachmentError = true;
        $this->m_arrAttachmentErrorMsg[$key] = "You can attach maximum ".self::MAX_ALLOWED_ATTACHMENTS. " proofs.";
        break;
      }
      
      //Move to JS Server
      $strTargetFile = $strBaseUploadPath."{$uniqueId}_{$value}";
      $bResult = move_uploaded_file($arrFileAttachment["tmp_name"][$key], JsConstants::$docRoot.$strTargetFile);
      $strUrlPrefix = IMAGE_SERVER_ENUM::$appPicUrl;

      if($bResult) {
        $arrUploadedPics[$arrAvailableKey[$iterator]] = "{$strUrlPrefix}{$strTargetFile}";
        $iterator++;
        $count++;
      } else {
        //Trigger to alert issue while moving pics
      }
    }
    
    if($this->m_bAttachmentError) {
      return $this->m_arrAttachmentErrorMsg;
    }
    
    if(-1 == $iTempAttachmentId) {
      $recordID = $objStore->insertRecord($arrUploadedPics);
    } else {
      $objStore->updateRecord($iTempAttachmentId, $arrUploadedPics);
      $recordID = $iTempAttachmentId;
    }
   
    return $recordID;
  }
  
  /**
   * 
   * @return type
   */  
  public function getIsAttachmentError(){
    return $this->m_bAttachmentError;
  }
  
  /**
   * 
   * @return type
   */
  public function getAttachmentErrorMsg(){
    return $this->m_arrAttachmentErrorMsg;
  }
  
  /**
   * 
   * @return type
   */
  public function getAttachmentId(){
    return $this-m_iAbuseAttachmentID;
  }
  
  /**
   * 
   * @param type $tempAttachmentsIds
   */
  private function uploadFromTempAttachments($tempAttachmentsIds) {
       
    $objAbuseAttachmentStore = new FEEDBACK_ABUSE_ATTACHMENTS;
    $recordId = $objAbuseAttachmentStore->insertFromTempAttachment($tempAttachmentsIds);
    
    $objTempAttachmentStore = new FEEDBACK_ABUSE_TEMP_ATTACHMENTS;
    $objTempAttachmentStore->deleteRecord($tempAttachmentsIds);
    
    $this->moveToCloud($recordId, $this->m_arrTempAttachments);
    
    $this->m_iAbuseAttachmentID = $recordId;
  }
  
  /**
   * 
   * @param type $recordId
   */
  private function moveToCloud($recordId, $arrUploadedPics) {
    
    if($recordId <= 0) {
      //Nothing to move
      return ;
    }
    
    //Insert into image server log
    $moduleName= array();
    $moduleId=array();
    $imageType=array();
    $status=array();
    
    foreach($arrUploadedPics as $key => $val) {
      
      if(0 === strlen($val)) {
        continue;
      }
      
      $moduleName[] = IMAGE_SERVER_MODULE_NAME_ENUM::getModuleName("ABUSE_ATTACHMENTS");
      $moduleId[] = $recordId;
      $imageType[] = $key;
      $status[] = IMAGE_SERVER_STATUS_ENUM::$onAppServer;
    }
    
    $imageServerLogStore = new ImageServerLog;
    $imageServerLogStore->insertBulk($moduleName, $moduleId, $imageType, $status);
  }
  
  /**
   * 
   * @param type $request
   */
  public function deleteTempAttachments($request){
    $this->webRequest=$request;
    $arrFeedback = $this->webRequest->getParameter('feed');
    $szAttachmentFileName = $arrFeedback['file_name'];
    
    $iAttachmentId = $arrFeedback['attachment_id'];
    
    $objTemp = new FEEDBACK_ABUSE_TEMP_ATTACHMENTS();
    $arrResult = $objTemp->getRecord($iAttachmentId);
    
    if(false !== $arrResult) {
      $arrResult = $arrResult[0];
      $columnName = null;
      foreach( $arrResult as $key => $val ) {
        if(false !== stripos($val, $szAttachmentFileName)) {
          $columnName = $key;
          $filePath = $val;
          break;
        }
      }
      //Remove File
      if($columnName) {
        unlink(str_replace(IMAGE_SERVER_ENUM::$appPicUrl, JsConstants::$docRoot, $val));
        $objTemp->updateRecord($iAttachmentId, array($columnName => ""));
        return true;
      }
    }
    return false;
  }
} 
?>
