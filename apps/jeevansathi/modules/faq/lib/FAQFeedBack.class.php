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

		$this->otherProfile=new Profile();
		if($this->webRequest->getParameter('profilechecksum') && $reasonNew)
		{ 
			$otherProfileId = JsCommon::getProfileFromChecksum($this->webRequest->getParameter('profilechecksum'));
		}
		
		else{
			$feed=$this->webRequest->getParameter('feed');
			$reason=$feed['message'];
			$pos=strpos($reason,':');
			$reasonNew=trim(substr($reason,$pos+1));
			$arr2=split(' ',$reason);
			$otherUsername=trim($arr2[0]);
			$this->otherProfile->getDetail($otherUsername,"USERNAME");
			$otherProfileId=$this->otherProfile->getPROFILEID();
			
		}

		

		$loginProfile=LoggedInProfile::getInstance();
		if(!$reasonNew || !$loginProfile->getPROFILEID() || !$otherProfileId) return;
		(new REPORT_ABUSE_LOG())->insertReport($loginProfile->getPROFILEID(),$otherProfileId,$reasonNew);

				// block for blocking the reported abuse added by Palash

				$ignore_Store_Obj = new NEWJS_IGNORE;
				$ignore_Store_Obj->ignoreProfile($loginProfile->getPROFILEID(),$otherProfileId);
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
				$this->FwdMail();
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
		
		if($bNewTicket)
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
