<?php
/**
 * contacts actions.
 *
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Your name hesimre
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contactsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function preExecute()
  {
		
	  //Contains login credentials
				$request=$this->getRequest();
		 
			$this->loginData=$data=$request->getAttribute("loginData");
			
			//required if database error comes
			if($this->getRequest()->getParameter("page_source")=="search")
			{
				if($this->getRequest()->getParameter("to_do")=="view_contact")
					sfConfig::set("OnlyError",2);
				else		
					sfConfig::set("OnlyError",1);
			}	
			//print_r($this->loginData);die;
			if(!sfConfig::get("login_redirect"))	
			if(!$this->loginData[PROFILEID])
			{
				sfConfig::set("login_redirect",1);
				if(MobileCommon::isMobile())
					$this->forward("static","logoutPage");
				else
					$this->forward("contacts","login");
			}
			if(!sfConfig::get("login_redirect"))
			{
				//Contains logined Profile information;
				$this->loginProfile=LoggedInProfile::getInstance();
				$this->loginProfile->getDetail($this->loginData["PROFILEID"],"PROFILEID");
				
				$this->multi=0;
				 

				if($request->getParameter('profilechecksum'))
				{
					$this->userProfile=explode(",",$request->getParameter('profilechecksum'));
					
					if($this->userProfile[0] && count($this->userProfile)==1)
					{ 
						try{
							$this->Profile=new Profile();
							$profileid = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
							$this->Profile->getDetail($profileid,"PROFILEID");
							$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
						}
						catch(Exception $ex)
				                {
				                        $this->forward("static","exceptionMessage");
                				}
						 
					}
					if(count($this->userProfile)>1)
						$this->multi=1;
						 //viewed obj

				}
				$this->setTemplate("post");
				
				if($request->getParameter("id"))
					$this->messagelayerid=$request->getParameter("id");
				$this->FROM_SEARCH=$request->getParameter("fromNewSearch");	
			}
  }
  public function postExecute()
  {
		$request=$this->getRequest();
		if(!sfConfig::get("login_redirect") && $this->contactEngineObj)
		{
			$page_source=$this->contactEngineObj->contactHandler->getPageSource();
			$innerTpl=$this->contactEngineObj->getComponent()->innerTpl;
			if(($page_source=="search" || $page_source=="VSM") && $innerTpl=="profile_eoi_error")
				$this->onlyInnerTpl=1;
			if($innerTpl=="profile_eoi_error")
				$this->errorTpl = 1;
		}
		if(MobileCommon::isMobile() && $this->contactEngineObj)
		{
			$naviObj=new Navigator();
			$szNavType = $request->getAttribute("nav_type");
			if($request->getParameter('overwrite'))
			{
				$szNavType = $request->getParameter("nav_type");
			}
			$naviObj->navigation($szNavType,"","");
			$this->BREADCRUMB=$naviObj->onlyBackBreadCrumb;
			$this->NAVIGATOR=$naviObj->NAVIGATOR;
			$this->nav_type=$szNavType;
			if($this->contactEngineObj->contactHandler->getAction()==ContactHandler::POST)
				$this->FROMPOST=1;
			$this->setTemplate("mobile");
			if(sfConfig::get("login_redirect"))
				$this->setTemplate("mobLogout");
			else
				$this->LOGGEDIN=1;
			if(sfContext::getInstance()->getRequest()->getParameter("action") == "MessageHandle")
				$this->setTemplate("MessageHandler");
		}	
		$this->setPostAction();
		if($this->getRequest()->getParameter("page_source")=="search")
			$this->fromSearch=1;
		if($this->getRequest()->getParameter("page_source")=="VSM")
			$this->fromVSM=1;
		
	}
	private function setPostAction()
	{
		$actionUrl=sfContext::getInstance()->getRequest()->getParameter("action");
		if($actionUrl=="PreContactDetails")
		$actionUrl=$this->returnPostUrl();
		else
		{
			$postArray=array("PreEoi"=>"PostEOI","PreAccept"=>"PostAccept","PreNotinterest"=>"PostNotinterest","PreWrite"=>"PostWrite","PreSendReminder"=>"PostSendReminder","PostEOI"=>"MessageHandle","PostSendReminder"=>"MessageHandle","PostAccept"=>"MessageHandle");
			$this->PostActionUrl=$postArray[$actionUrl];
		}
		
	}
	private function returnPostUrl()
	{
		if($this->contactObj)
		{
			$type=$this->contactObj->getTYPE();
			$postArray=array("A"=>"PostEOI","C"=>"PostAccept","E"=>"PostNotinterest","N"=>"PostEOI");
		
			$this->PostActionUrl=$postArray[$type];
		}
	}
  public function executeLogin(sfWebRequest $request)
  {
  	if($this->getRequestParameter('DRAFT_NAME'))
  	{
  		$this->FromDraftMsg="You need to login first to save message.";	
		}
  }
  public function executeIndex(sfWebRequest $request)
  {
		
  }
  private function multiActionPerform($tobe,$actionType)
  {
		try{
				
			$request=$this->getRequest();
			if($tobe && $actionType)
			for($i=0;$i<count($this->userProfile);$i++)
				{
					$this->Profile=new Profile();
					
					$profileid = JsCommon::getProfileFromChecksum($this->userProfile[$i]);
					$this->Profile->getDetail($profileid,"PROFILEID");
					$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,$tobe,$actionType);
					$this->updateContactHandler($request);
					$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
				}
			$this->contactEngineObj->getComponent()->numberOfProfiles = count($this->userProfile);
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
  }
  
  public function executePreEoi(sfWebRequest $request)
	{
		try
		{
			if($this->multi)
			{
				$this->multiActionPerform("I",ContactHandler::PRE);
				$this->contactEngineObj->getComponent()->innerTpl = $this->contactEngineObj->getComponent()->innerTpl."_multi";
			}
			else
			{
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::PRE);
					$this->updateContactHandler($request);
					$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	/**
	 * 
	 * Handle the Save Draft request for Post Accept and Decline.
	 * @access public
	 * @return void
	 */
	public function executeSaveDraft()
	{
		try
		{
			
			if($this->loginProfile->getPROFILEID())
			{
					$id=$this->getRequestParameter('DRAFT_ID');
					$name=urldecode($this->getRequestParameter('DRAFT_NAME'));
					$mes=urldecode($this->getRequestParameter('DRAFT_MES'));
					$d_status=$this->getRequestParameter('contactType');
					if($d_status)
					{
						$draftObj = new NEWJS_DRAFTS(); 
						if($d_status=="D")
							$result = $draftObj->getDrafts($this->loginProfile->getPROFILEID(),'Y');
						else
							$result = $draftObj->getDrafts($this->loginProfile->getPROFILEID(),'N');
						 $total_dra=count($result);
						if(is_numeric($id) && $total_dra >= 5 )
						{
							$draftObj->updateDrafts($id,addslashes(stripslashes($name)),addslashes(stripslashes($mes)));
						}
						else
						{
							if($d_status=="D")
								$draftObj->insertDrafts($this->loginProfile->getPROFILEID(),addslashes(stripslashes($name)),addslashes(stripslashes($mes)),'Y');
							else
								$draftObj->insertDrafts($this->loginProfile->getPROFILEID(),addslashes(stripslashes($name)),addslashes(stripslashes($mes)),'N');
						}
					}	
			}
			echo htmlspecialchars(stripslashes($name),ENT_QUOTES);		
			return sfView::NONE;
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}	
	}
	
	public function executePostWrite(sfWebRequest $request)
	{
		
		$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'M',ContactHandler::POST);
		$this->updateContactHandler($request);
		$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
		
	}
	public function updateContactHandler( sfWebRequest $request,$force_layer=0)
	{
		//if($request->getParameter("page_source")!='VDP')
			$this->Layer=1;
		$this->contactHandlerObj->setPageSource($this->getParameter($request,"page_source"));
		
		//For mobile update it
		if(MobileCommon::isMobile())
			$this->contactHandlerObj->setPageSource("MOBILE");
		if( !$this->getParameter($request,"draft"))
		{
			$this->contactHandlerObj->setElement("MESSAGE",PresetMessage::getPresentMessage($this->loginProfile,$this->contactHandlerObj->getToBeType()));
            
            //If Event is Decline then No Preset Msg
            if(ContactHandler::DECLINE == $this->contactHandlerObj->getToBeType()) {
                $this->contactHandlerObj->setElement("MESSAGE","");
            }
            
			$this->contactHandlerObj->setElement("DRAFT_NAME","preset");
		}
		else
		{
			$this->contactHandlerObj->setElement("MESSAGE",$this->getParameter($request,"draft"));	
			$this->contactHandlerObj->setElement("DRAFT_NAME",$this->getParameter($request,"draft_name"));
		}
		$this->contactHandlerObj->setElement("STYPE",$this->getParameter($request,"stype"));
		
		$this->contactHandlerObj->setElement("STYPE",$this->getParameter($request,"stype"));
		$this->contactHandlerObj->setElement("CLICKSOURCE",$this->getParameter($request,"clicksource"));
		$this->contactHandlerObj->setElement("COUNTLOGIC",$this->getParameter($request,"countlogic"));
		$this->contactHandlerObj->setElement("PROFILECHECKSUM",$this->getParameter($request,"profilechecksum"));
		$this->contactHandlerObj->setElement("RESPONSETRACKING",$this->getParameter($request,"responseTracking"));
		
		
		if($this->multi)
			$this->contactHandlerObj->setElement("MULTI",$this->multi);
		
		if($this->contactHandlerObj->getAction()==ContactHandler::POST || $force_layer)
			$this->allow = 1;
		//$this->contactHandlerObj->setElements("MESSAGE",$request->getParameter("draft"));
		
		
		
	}
	private function getParameter($request,$type)
	{
		if($request->getParameter($type))
				return $request->getParameter($type);
		elseif($request->getParameter(strtoupper($type)))
				return $request->getParameter(strtoupper($type));
		else
				return $request->getParameter(strtolower($type));
				
	}
	/**
	 * 
	 * Handle the Accept Reqeuest for Post Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */
	
	public function executePostAccept(sfWebRequest $request)
	{
		try
		{
				
			if($this->multi)
			{
				$this->multiActionPerform("A",ContactHandler::POST);
			}
			else
			{
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'A',ContactHandler::POST);
				$this->updateContactHandler($request);
				$this->contactHandlerObj->setElement("STATUS","A");
				$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	
	/**
	 * 
	 * Handle the Accept Reqeuest for Post Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
  	public function executePostEOI(sfWebRequest $request)
	{
		try
		{
			$request->setAttribute('nav_type',"MVS");
                       if($request->getParameter('fmBack')==1)
                        {/** Similar Profile **/
                               $this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::POST);
				$this->updateContactHandler($request);
				
				$this->contactHandlerObj->setElement("STATUS","I");
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
                              
				$viewSimilarProfilesObj = new ViewSimilarProfile();  // Library handling similar profile search operation
                                $this->finalResultsArray = $viewSimilarProfilesObj->getSimilarProfiles($this->Profile,$this->loginProfile);
				//OLD LOGIC
                                //$this->finalResultsArray = $viewSimilarProfilesObj->viewOldSimilarProfileResults($this->Profile,$this->loginProfile,"20","mob");
                                $this->sType=SearchTypesEnums::MobileEOIConfirmationPage;       // Setting stype in this case it is "WC"
                                /** End Similar Profile **/
				$this->setTemplate("eoi");
                        }
                        else
                        {
			
			if($this->multi)
			{
				if (true === UserFilterCheck::isSpam($this->loginProfile->getPROFILEID(), 'sendContact')) {
					$this->Profile=new Profile();
					$profileid = JsCommon::getProfileFromChecksum($this->userProfile[0]);
					$this->Profile->getDetail($profileid,"PROFILEID");
					 $this->contactObj = new Contacts($this->loginProfile, $this->Profile);
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::PRE);
					$this->updateContactHandler($request);
					$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);	
					$this->contactEngineObj->getComponent()->errorMessage = Messages::MULTI_EOI_SPAMMER;
					$this->contactEngineObj->getComponent()->innerTpl = "profile_eoi_error";
				}
				else {
					$this->multiActionPerform("I",ContactHandler::POST);
					if($this->contactEngineObj->getComponent()->innerTpl != 'profile_eoi_error') 
						$this->contactEngineObj->getComponent()->innerTpl = "profile_postMultiEOI";
				}
			}
			else
			{ 	
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'I',ContactHandler::POST);
				$this->updateContactHandler($request);
				
				$this->contactHandlerObj->setElement("STATUS","I");
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
                             
                                /** Similar Profile **/
				$viewSimilarProfilesObj = new ViewSimilarProfile();  // Library handling similar profile search operation
                                //NEW LOGIC
                                $this->finalResultsArray = $viewSimilarProfilesObj->getSimilarProfiles($this->Profile,$this->loginProfile);
				//OLD LOGIC
                                //$this->finalResultsArray = $viewSimilarProfilesObj->viewOldSimilarProfileResults($this->Profile,$this->loginProfile,"20","mob");
                                $this->sType=SearchTypesEnums::MobileEOIConfirmationPage;       // Setting stype in this case it is "WC"
                                /** End Similar Profile **/
				
                                $this->setTemplate("eoi");
				$FTOState = $this->loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState();
				if($FTOState == FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD)
				{
					$EditLayerobj = new EditOnFtoContactConfirmation($this->loginProfile);
					if($EditLayerobj->toOpenLayer())
					$this->layerToShow = $EditLayerobj->getLinkToShowHref();
				}
			}
                        }
                        
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");

		}
	}
	
	/**
	 * 
	 * Handle the Send Reminder Reqeuest for Post Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
  	public function executePostSendReminder(sfWebRequest $request)
	{
		try
		{
			$request->setAttribute('nav_type',"RVS");
			if($this->multi)
			{
				$this->multiActionPerform("R",ContactHandler::POST);
			}
			else
			{
                                
                                $this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'R',ContactHandler::POST);
                                if($request->getParameter('fmBack')!=1)
                                {
                                        $this->updateContactHandler($request);
                                }
                                $this->contactHandlerObj->setElement("STATUS","R");
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
                                
                                //print_r($this->contactEngineObj->getComponent()->eoiDrafts);die;
				/** Similar Profile **/
				$viewSimilarProfilesObj = new ViewSimilarProfile();  // Library handling similar profile search operation
                                $this->finalResultsArray = $viewSimilarProfilesObj->getSimilarProfiles($this->Profile,$this->loginProfile);
				//OLD LOGIC
                                //$this->finalResultsArray = $viewSimilarProfilesObj->viewOldSimilarProfileResults($this->Profile,$this->loginProfile,"20","mob");
                                
				$this->sType=SearchTypesEnums::MobileEOIConfirmationPage;       // Setting stype in this case it is "WC"
                                /** End Similar Profile **/
                                $this->setTemplate("eoi");
				$FTOState = $this->loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState();
				if($FTOState == FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD)
				{
					$EditLayerobj = new EditOnFtoContactConfirmation($this->loginProfile);
					if($EditLayerobj->toOpenLayer())
					$this->layerToShow = $EditLayerobj->getLinkToShowHref();
					
				}
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}

	/**
	 * 
	 * Handle the Cancel Contact,Cancel Accept and Decline Reqeuest for Post Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */
	public function executePostNotinterest(sfWebRequest $request)
	{
		
		try
		{   
			$updateMsg = 0;
			$this->toBeStatus = 'D';
			if($this->multi && $this->toBeStatus == 'D')
			{
				$this->multiActionPerform($this->toBeStatus,ContactHandler::POST);
			}
			else
			{
				if($this->contactObj->getReceiverObj()->getPROFILEID() == $this->loginProfile->getPROFILEID() && ($this->contactObj->getTYPE()=='I' || $this->contactObj->getTYPE()=='A' ))
				{			
					$this->toBeStatus = 'D';
                    if($this->contactObj->getTYPE()=='A')
						$updateMsg = 1;
				}
				elseif($this->contactObj->getTYPE()=='I' && $this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					$this->toBeStatus = 'E';
                    $updateMsg = 1;			
				}
				elseif($this->contactObj->getTYPE()=='A' && $this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					$this->toBeStatus = 'C';
                    $updateMsg = 1;
				}
				
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,$this->toBeStatus,ContactHandler::POST);
				if($updateMsg)
				{	
				    $draftObj=ProfileDrafts::getInstance($this->contactHandlerObj->getViewer());
					$preMsg = $draftObj->getDeclineDrafts();
					$request->setParameter("draft",ProfileDrafts::getMessage($preMsg,ProfileDrafts::PRESET_DECLINE_DRAFTID,1));
				}
				                             
				$this->updateContactHandler($request);
				$this->contactHandlerObj->setElement("STATUS",$this->toBeStatus);
				$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
								
			}

			//Checking if error is coming.
			if($this->contactEngineObj->getComponent()->errorMessage)
			{
			}
			else if($this->toBeStatus == 'D' && $this->contactHandlerObj->getPageSource()=='VDP' && !(MobileCommon::isMobile()))
			{
				$this->redirectionOnDecline();
				$this->contactEngineObj->getComponent()->innerTpl="redirect";
			}
			elseif($request->getParameter('contactdetail'))//If calling from contat detail section
			{
				unset($this->contactHandlerObj);
				//$this->contactObj = new Contacts($this->loginProfile, $this->Profile);
				
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
				$this->updateContactHandler($request,1);
				$this->contactHandlerObj->setPageSource('layer');
				$this->contactHandlerObj->setElement("STATUS",$this->toBeStatus);
				$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
			}
		}	
			catch(Exception $ex)
		{
			//jsException::log($ex->getMessage());
			$this->forward("static","exceptionMessage");
		}
					
	}
	
	/**
	 * 
	 * Handle the Call Direct Reqeuest for Post Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePostCalldirect(sfWebRequest $request)
	{
		try
		{
				
			$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"INFO",$this->contactObj,'CALL_DIRECT',ContactHandler::POST);
			$this->contactHandlerObj->setElement("ALLOWED",$request->getParameter("CAll_DIRECT_ALLOWED"));
			$this->updateContactHandler($request);
			$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	/**
	 * 
	 * Handle the Call Direct Reqeuest for PRE Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreContactDetails(sfWebRequest $request)
	{
		try
		{
				
			$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
			$this->updateContactHandler($request);
			$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	
	/**
	 * 
	 * Handle the Accept Reqeuest for Pre Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreAccept(sfWebRequest $request)
	{
		try
		{
			if($this->multi)
			{
				$this->multiActionPerform("A",ContactHandler::PRE);
				//$this->contactEngineObj->contactHandler->setElement(CONTACT_ELEMENTS::PROFILECHECKSUM);
			}
			else
			{
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'A',ContactHandler::PRE);
				$this->updateContactHandler($request);
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
			}
			
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}	
	}
	
  /**
	 * 
	 * Handle the Send Reminder Reqeuest for Pre Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreSendReminder(sfWebRequest $request)
	{
		try
		{
				
			if($this->multi)
			{
				$this->multiActionPerform("R",ContactHandler::PRE);
				//$this->contactEngineObj->contactHandler->setElement(CONTACT_ELEMENTS::PROFILECHECKSUM);
			}
			else
			{
			
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'R',ContactHandler::PRE);
				$this->updateContactHandler($request);
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	
  /**
	 * 
	 * Handle the Write Reqeuest for Pre Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreWrite(sfWebRequest $request)
	{	
		try
		{
			$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'M',ContactHandler::PRE);
			$this->updateContactHandler($request);
			$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}	
	}
	
	
	/**
	 * 
	 * Handle the Cancel Contact,Cancel Accept and Decline Reqeuest for Pre Action
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreNotinterest(sfWebRequest $request)
	{
		try
		{
			
			$this->toBeStatus = 'D';
			if($this->multi && $this->toBeStatus == 'D')
			{
				$this->multiActionPerform($this->toBeStatus,ContactHandler::PRE);
				//$this->contactEngineObj->contactHandler->setElement(CONTACT_ELEMENTS::PROFILECHECKSUM);
			}
			else
			{
				if($this->contactObj->getReceiverObj()->getPROFILEID() == $this->loginProfile->getPROFILEID() && ($this->contactObj->getTYPE()=='I' || $this->contactObj->getTYPE()=='A' ))
				{
					$this->toBeStatus = 'D';
				}
				elseif($this->contactObj->getTYPE()=='I' && $this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					$this->toBeStatus = 'E';
				}
				elseif($this->contactObj->getTYPE()=='A' && $this->contactObj->getSenderObj()->getPROFILEID() == $this->loginProfile->getPROFILEID())
				{
					$this->toBeStatus = 'C';
				}
				
				$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,$this->toBeStatus,ContactHandler::PRE);
				$this->updateContactHandler($request);
				$this->contactEngineObj = ContactFactory::event($this->contactHandlerObj);
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}	
	}
		
	/**
	 * 
	 * Handle Which action need to be called if nothing is known beforehand
	 * @param sfWebRequest $request
	 * @access public
	 * @return void
	 */	
	public function executePreUnknown(sfWebRequest $request)
	{
		try
		{
			if($this->loginProfile->getPROFILEID())
			{
				if($request->getParameter('tabname')=="exp")
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"EOI",$this->contactObj,'',ContactHandler::PRE);
				elseif($request->getParameter('tabname')=="con")
					$this->contactHandlerObj = new ContactHandler($this->loginProfile,$this->Profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
				else
					return sfView::NONE;
				
				$this->updateContactHandler($request);
				$this->contactEngineObj=ContactFactory::event($this->contactHandlerObj);
				
				
			}
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	
	private function redirectionOnDecline()
	{
		try
		{
			$dbName = JsDbSharding::getShardNo($this->loginProfile->getPROFILEID(),'');
			$dbObj = new newjs_CONTACTS($dbName);
			$b90=mktime(0,0,0,date("m"),date("d")-90,date("Y"));
				$back_90_days=date("Y-m-d",$b90);
			
			$respondArr = $dbObj->getRespondedCount($this->loginProfile->getPROFILEID(),"TIME > '$back_90_days 00:00:00'");
			if(is_array($respondArr))
				{
						foreach ($respondArr as $key=>$val)
						{
							if($val['TYPE']=='I' && $val['FILTERED']!='Y')
							{
								$countAwaiting += $val['COUNT'];
							}
						}
				 }
				 if($countAwaiting>0)
					$this->contactEngineObj->toPage="/P/contacts_made_received.php?page=eoi&filter=R";
				 else
				 {
					$profileMemcacheObj = new ProfileMemcacheService($this->loginProfile);
					$alertCount["TOTAL"] = $profileMemcacheObj->get("MATCHALERT_TOTAL");
					$alertCount["NEW"] = $profileMemcacheObj->get("MATCHALERT");
					if($alertCount["TOTAL"]>0)
						$this->contactEngineObj->toPage='/profile/contacts_made_received.php?page=matches&filter=R';
					else
						$this->contactEngineObj->toPage='/search/partnermatches';
				 }
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
	public function executeMessageHandle(sfWebRequest $request)
	{
		try
		{
			if ($this->contactObj->getTYPE() == "I") { 
                                /** Similar Profile * */
                                $this->Profile=new Profile();
				$profileid = JsCommon::getProfileFromChecksum($request->getParameter('profilechecksum'));
				$this->Profile->getDetail($profileid,"PROFILEID");
                                $viewSimilarProfilesObj = new ViewSimilarProfile();  // Library handling similar profile search operation
                                $this->finalResultsArray = $viewSimilarProfilesObj->getSimilarProfiles($this->Profile, $this->loginProfile);
                                //OLD LOGIC
                                //$this->finalResultsArray = $viewSimilarProfilesObj->viewOldSimilarProfileResults($this->Profile,$this->loginProfile,"20","mob");
                                
                                $this->sType = SearchTypesEnums::MobileEOIConfirmationPage;       // Setting stype in this case it is "WC"
                                /** End Similar Profile * */
                        }
			//print_r($request);die;
                        if($request->getParameter("messageid"))
			{
                                $messageCommunication = new MessageCommunication('',$this->loginProfile->getPROFILEID());
                                $messageCommunication->insertMessage();
                        }
			$naviObj=new Navigator();
			$naviObj->navigation("MMH","","");
			$this->BREADCRUMB=$naviObj->onlyBackBreadCrumb;
			$this->NAVIGATOR=$naviObj->NAVIGATOR;
			$this->nav_type="MMH";
			$this->setTemplate("MessageHandler");
			
		}	
			catch(Exception $ex)
		{
			$this->forward("static","exceptionMessage");
		}
	}
		
  
}
?>
