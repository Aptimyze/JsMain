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
	private static $REASON_MAP=array('duplicate profile','incorrect details/photo','already married/engaged','inappropriate content','spam','looks like a fake profile','other');

        public function __construct($api=0)
	{
		if($api)
			$this->api=1;
		else
			$this->api=0;
		$this->m_bValidForm = false;
	}
	public function getTracePath()
	{
		return $this->m_iTracePath;
	}



	private function insertReportAbuseLog(){

		$reasonNew=$this->webRequest->getParameter('reason');
                $reasonMap=$this->webRequest->getParameter('reason_map');
                if($this->webRequest->getParameter('fromCRM')){
				$this->otherProfile=new Profile('',$this->webRequest->getParameter('reporteePFID'));  
		}
			else
			{
				$this->otherProfile=new Profile();	
			}

		if($this->webRequest->getParameter('profilechecksum') && ($reasonNew || $reasonMap))
		{ 
			$otherProfileId = JsCommon::getProfileFromChecksum($this->webRequest->getParameter('profilechecksum'));
		}
		
		else {

                        $feed=$this->webRequest->getParameter('feed');
                        $reason=$feed['message'];

                        $pos=strpos($reason,':');
                        $reasonNew=trim(substr($reason,$pos+1));
                        $pos2=strpos($reason,'by');
                        $arr2=split(' ',trim(substr($reason,$pos2+2)));
                        $otherUsername=trim($arr2[0]);

                        if(!$this->webRequest->getParameter('fromCRM'))
                        $this->otherProfile->getDetail($otherUsername,"USERNAME");

                        $otherProfileId=$this->otherProfile->getPROFILEID();  

                }
	
                if($reasonMap)
                {
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
			$otherReason=''; 
		}
		else
		{ 
			$categoryNew='other';
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
		if(!$reasonNew || !$loginProfile->getPROFILEID() || !$otherProfileId) return;
		(new REPORT_ABUSE_LOG())->insertReport($loginProfile->getPROFILEID(),$otherProfileId,$categoryNew,$otherReason,$category,$crmUserName);
			
				// block for blocking the reported abuse added by Palash
		
				$ignore_Store_Obj = new IgnoredProfiles("newjs_master");
				$ignore_Store_Obj->ignoreProfile($loginProfile->getPROFILEID(),$otherProfileId);
				//Entry in Chat Roster
				try {
					$this->ignoreProfile = new Profile("",$otherProfileId);
					$this->ignoreProfile->getDetail("","","*");
					$producerObj = new Producer();
					if ($producerObj->getRabbitMQServerConnected()) {
						$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'BLOCK', 'body' => array('sender' => array('profileid'=>$loginProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($loginProfile->getPROFILEID()),'username'=>$loginProfile->getUSERNAME()), 'receiver' => array('profileid'=>$this->ignoreProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->ignoreProfile->getPROFILEID()),"username"=>$this->ignoreProfile->getUSERNAME()))), 'redeliveryCount' => 0);
						$producerObj->sendMessage($chatData);
					}
					unset($producerObj);
				} catch (Exception $e) {
					throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
				}
				
				//End
				JsMemcache::getInstance()->remove($loginProfile->getPROFILEID());
				JsMemcache::getInstance()->remove($otherProfileId);

				//////////////////////////////////////////////////

	}
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
			$objNameStore = new incentive_NAME_OF_USER;
			$name=$objNameStore->getName($loginProfile->getPROFILEID());
			if($name)
				$this->m_szName = $objNameStore->getName($loginProfile->getPROFILEID());
			else
				$this->m_szName = "";
			unset($objNameStore);
		}
		else
		{	
			if($requestArr['email'])
			$this->m_szEmail = $requestArr['email'];	
		}
		if($request->getParameter('setOption'))
			$this->setOption = $request->getParameter('setOption');

	}
	
	public function ProcessData(sfWebRequest $request)
	{ 
		$this->extractInfo($request);
		$this->webRequest=$request;
		$this->m_objForm = new FeedBackForm($this->api);
		$arrDeafults = array('name'=>$this->m_szName,'username'=>$this->m_szUserName,'email'=>$this->m_szEmail);
		$dataArray = $this->webRequest->getParameter('feed');

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
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		

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
			$objTicket_Message_STORE->Insert($iTicketID,$szMsg,$ip);
			
			//Insert in MIS_FEEDBACK_RESULT Store
			$objMIS_FeedBack_Result->Insert($this->m_szCategory,$iTicketID);

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
} 
?>
