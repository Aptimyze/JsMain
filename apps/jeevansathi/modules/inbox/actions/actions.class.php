<?php

/**
 * inbox actions.
 *
 * @package    jeevansathi
 * @subpackage inbox
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class inboxActions extends sfActions
{
 /**
  * Executes index action - load the contact center listings
  *
  * @param sfRequest $request A request object
  */
  
  public function executeIndex(sfWebRequest $request)
  {	
  	//print_r($request->getParameterHolder()->getAll());
  	$params["request"] = $request;

  	//handle mobile switch
  	if(MobileCommon::isMobile())
  	{
  		$this->handleMobileSwitchForInbox($params);
  	}
	$inboxChannelFactoryObj = new InboxApiChannelFactory();
	$inboxChannelObj = $inboxChannelFactoryObj->getChannel($params);

	//set postParams for api request
	$inboxChannelObj->setPostParamsForApiRequest($request);

	//fetch api response using InboxApiChannelFactory
    ob_start();    
    sfContext::getInstance()->getController()->getPresentationFor('inbox','performV2');
	$jsonResponse = ob_get_contents(); 
	ob_end_clean();

	$this->firstResponse = $jsonResponse;
	$ResponseArr = json_decode($jsonResponse,true);

	$params["actionObject"] = $this;
	$params["ResponseArr"]= $ResponseArr;

	//set object variables using InboxApiChannelFactory
    $inboxChannelObj->setVariables($params);	
  }

  /**
  * handle mobile switch for cc listings
  *
  * @param $params
  */
  private function handleMobileSwitchForInbox($params)
  {
  	$request = $params["request"];
  	if(!$request->getParameter('infoTypeId'))
    {
        $infoId = InboxConfig::$cctabArr[InboxConfig::$defaultVerticalTabID]["defaultHtabInfoID"];
    }
    else
    {
    	$infoId = $request->getParameter('infoTypeId');
    }
   
    $ccParams = InboxEnums::$INBOX_ACTION_MAPPING[$infoId];
    $redirectUrl = $SITE_URL."/profile/contacts_made_received.php?page=".$ccParams["page"]."&filter=".$ccParams["filter"];
    header("Location:".$redirectUrl);die;
  }

  public function executePerformV1(sfWebRequest $request)
 {	
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj->validateRequestInboxData($request);
		$output = $inputValidateObj->getResponse();
		if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
			$module= "ContactCenterAPP";
			$infoTypeId = $request->getParameter("infoTypeId");
			$pageNo = $request->getParameter("pageNo");
			if($request->getParameter("myjs") == 1)
				$module = "ContactCenterMYJS";
			$profileList = $request->getParameter("profilelist");
			
			/********Recommended matches handling******
			//If api requested from DPP search
			if($infoTypeId == 7 && $request->getParameter("my_matches_flag") == "true"){
				$request->setParameter("partnermatches",1);
				$this->forward("search","performV1");
			}
			********Ends here******/

			$profileCommunication = new ProfileCommunication();
			$profileObj=LoggedInProfile::getInstance('newjs_master');
			if($profileObj==null || $profileObj->getPROFILEID()==null || $profileObj->getPROFILEID()==''){
			     $this->forward("static","logoutPage");
			}
			$pid=$profileObj->getPROFILEID();
			$response = array();
			if(!$profileObj->getAGE())
				$profileObj->getDetail("","","HAVEPHOTO");
      
			if($infoTypeId)
			{
				$json=1;
				$infoType = ProfileInformationModuleMap::getInfoTypeById($module,$infoTypeId);
				$infoTypenav["PAGE"] = $infoType;
				$infoTypenav["NUMBER"]=$pageNo;
			}
      
      if($infoType == "MATCH_ALERT") {
        $this->matchAlertCountResetLogic($profileObj);
      }
      
                        if ($infoType == "VISITORS") {
                            $infoTypenav["matchedOrAll"] = $request->getParameter("matchedOrAll");
                            if($infoTypenav["matchedOrAll"]=='')
                                $infoTypenav["matchedOrAll"]='A';

                        }
      
			if(PROFILE_COMMUNICATION_ENUM_INFO::ifModuleExists($module))
			{
				$this->count= $profileCommunication->getCount($module,$profileObj,$infoTypenav);
				$this->displayObj= $profileCommunication->getDisplay($module,$profileObj,$infoTypenav);
				$inboxApiObj = new InboxMobileAppV1();
			
				$response = $inboxApiObj->getJsonAppV1($this->displayObj,$pid,$profileObj->getGENDER(),$profileObj->getSUBSCRIPTION());
			}
			/********Recommended matches handling******
			if($infoTypeId == 7){
				if(count($response) == 0){ 
					$request->setParameter("partnermatches",1);
					$this->forward("search","performV1");
				}
				else $response['searchid'] = 0;
			}
			********Ends here******/

			//next 2 lines are for photorequest page to set all photorequests as seen. Added by Palash .. isApp check introduced because for mobile and pc its done in contacts_made_received.php
			$profileMemcacheObj = new ProfileMemcacheService($profileObj);
			switch($infoTypeId)
			{
				case "1":
					$currentCount =  $profileMemcacheObj->get("AWAITING_RESPONSE_NEW");
					if($currentCount)
					{
						if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
						{
							$producerObj=new Producer();
							if($producerObj->getRabbitMQServerConnected())
							{
                                                                $currentTime = date('Y-m-j H:i:s');
								$updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::INITIATED,'time'=>$currentTime)), 'redeliveryCount'=>0 );
								$producerObj->sendMessage($updateSeenData);
							}
							else
							{
								$this->sendMail();
							}
						}
						else
						{
							$contactRObj=new EoiViewLog();
							$contactRObj->setEoiViewedForAReceiver($pid,'N');
							$contactsObj = new ContactsRecords();
							$contactsObj->makeAllContactSeen($pid,ContactHandler::INITIATED);
                                                        
						}
						$profileMemcacheObj->update("AWAITING_RESPONSE_NEW",-$currentCount);
						$profileMemcacheObj->updateMemcache();
					}
					break;
				case "9":
					$photoRCurrentCount=$profileMemcacheObj->get("PHOTO_REQUEST_NEW");
					if ($photoRCurrentCount!='0'){
                                                if(JsConstants::$updateSeenQueueConfig['PHOTO_REQUEST'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'PHOTO_REQUEST','body'=>array('profileid'=>$pid)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
							Inbox::setAllPhotoRequestsSeen($pid);
						}
						$profileMemcacheObj->update("PHOTO_REQUEST_NEW",-$photoRCurrentCount);
						$profileMemcacheObj->updateMemcache();
					}
					break;
				case "2":
					$currentCount =  $profileMemcacheObj->get("ACC_ME_NEW");
					if($currentCount)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::ACCEPT)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
							$contactsObj = new ContactsRecords();
							$contactsObj->makeAllContactSeen($pid,ContactHandler::ACCEPT);
                                                }
						$profileMemcacheObj->update("ACC_ME_NEW",-$currentCount);
						$profileMemcacheObj->updateMemcache();
					}
					break;
				case "12":
					$currentCount =  $profileMemcacheObj->get("FILTERED_NEW");
					if($currentCount)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $currentTime = date('Y-m-j H:i:s');
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::FILTERED,'time'=>$currentTime)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
													 $contactRObj=new EoiViewLog();
                                                        $contactRObj->setEoiViewedForAReceiver($pid,'Y');
							$contactsObj = new ContactsRecords();
							$contactsObj->makeAllContactSeen($pid,ContactHandler::FILTERED);
                                                       
                                                }
						$profileMemcacheObj->update("FILTERED_NEW",-$currentCount);
						$profileMemcacheObj->updateMemcache();
					}
					break;
				case "4":
					$currentCount =  $profileMemcacheObj->get("MESSAGE_NEW");
					if($currentCount)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_MESSAGES'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_MESSAGES','body'=>array('profileid'=>$pid)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
							MessageLog::makeAllMessagesSeen($pid);
							ChatLog::makeAllChatsSeen($pid);
						}
						$profileMemcacheObj->update("MESSAGE_NEW",-$currentCount);
						$profileMemcacheObj->updateMemcache();
					}
					break;
					//
					case "10":
					$currentCount =  $profileMemcacheObj->get("DEC_ME_NEW");
					if($currentCount)
					{	
                                                if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::DECLINE)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::CANCEL_ALL)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                                
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                { 
												$contactsObj = new ContactsRecords();
												$contactsObj->makeAllContactSeen($pid,ContactHandler::DECLINE);
												$contactsObj->makeAllContactSeen($pid,ContactHandler::CANCEL_ALL);

                                                       
                                                }
						$profileMemcacheObj->update("DEC_ME_NEW",-$currentCount);

						$profileMemcacheObj->updateMemcache();
					}
					break;

			}
			$respObj = ApiResponseHandler::getInstance();
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$respObj->setResponseBody($response);
			$respObj->generateResponse();
		}
		else
		{
			$respObj = ApiResponseHandler::getInstance();
			if(is_array($output))
				$respObj->setHttpArray($output);
			else
				$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$respObj->generateResponse();
		}
		die;
  }

public function executePerformV2(sfWebRequest $request)
  {    
  	LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO,'in inbox api v2 '. $request->getParameter("infoTypeId") ); 
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj = ValidateInputFactory::getModuleObject('inbox'); //added for contact center

		$inputValidateObj->validateRequestInboxData($request);
		$output = $inputValidateObj->getResponse();

		if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
			/** caching **/
			$ifApiCached = InboxUtility::cachedInboxApi('get',$request);
                        if($ifApiCached)
                        {
                                $response2  = $ifApiCached;
                                unset($ifApiCached);
                                $date = date("Y-m-d");
                                file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/ifApiCached".$date.".txt","\n",FILE_APPEND);
                        }
                        else
			{
				$module= "ContactCenterAPP";
				$infoTypeId = $request->getParameter("infoTypeId");
						$pageNo = $request->getParameter("pageNo");
				if($request->getParameter("myjs") == 1)
					$module = "ContactCenterMYJS";
				if($request->getParameter("ContactCenterDesktop") == 1)
					$module = "ContactCenterDesktop";
				$profileList = $request->getParameter("profilelist");
				if(!$infoTypeId)
					$infoTypeId = $request->getParameter("searchId");
				if(!$pageNo)
					$pageNo = $request->getParameter("currentPage");
				/********Recommended matches handling******
				//If api requested from DPP search
				if($infoTypeId == 7 && $request->getParameter("my_matches_flag") == "true"){
					$request->setParameter("partnermatches",1);
					$this->forward("search","performV1");
				}
				********Ends here******/

				$profileCommunication = new ProfileCommunication();
				$profileObj=LoggedInProfile::getInstance('newjs_master');
				if($profileObj==null || $profileObj->getPROFILEID()==null || $profileObj->getPROFILEID()==''){
			     $this->forward("static","logoutPage");
				}
				$pid=$profileObj->getPROFILEID();
				$response = array();
				if(!$profileObj->getAGE())
					$profileObj->getDetail("","","HAVEPHOTO,GENDER");
				if($infoTypeId)
				{
					$json=1;
					$infoType = ProfileInformationModuleMap::getInfoTypeById($module,$infoTypeId);
					$infoTypenav["PAGE"] = $infoType;
					
					$infoTypenav["NUMBER"]=$pageNo;
				}
        
        if ($infoType == "MATCH_ALERT") {
          $this->matchAlertCountResetLogic($profileObj);
        }
                                if ($infoType == "VISITORS") {
                                    $infoTypenav["matchedOrAll"] = $request->getParameter("matchedOrAll");
                                    if(MobileCommon::isIOSApp() && $infoTypenav["matchedOrAll"]=='')
                                    {
                                           $infoTypenav["matchedOrAll"] = "A";
                                    }

                                }
       
				if(PROFILE_COMMUNICATION_ENUM_INFO::ifModuleExists($module))
				{
					$this->count= $profileCommunication->getCount($module,$profileObj,$infoTypenav);
					$this->displayObj= $profileCommunication->getDisplay($module,$profileObj,$infoTypenav);
					$inboxApiObj = new InboxMobileAppV2();
					$response = $inboxApiObj->getJsonAppV2($this->displayObj,$pid,$profileObj);
				}
					
				/********Recommended matches handling******
				if($infoTypeId == 7){
					if(count($response) == 0){ 
						$request->setParameter("partnermatches",1);
						$this->forward("search","performV1");
					}
					else $response['searchid'] = 0;
				}
				********Ends here******/
				$response2=array();
				
				 foreach ($response as $key => $value)
				{
					if ($key=="currentpage") continue;
					$response2[$key]=$value;
				}
                                $freeMemberCount = 0;
				if(is_array($response2["profiles"]))
				{
				foreach ($response2['profiles'] as $key=>$value)
				{
					$str=$response2['profiles'][$key]['last_message'];
					if (strlen($str)>=100) 
					$response2['profiles'][$key]['last_message']=substr($response2['profiles'][$key]['last_message'],0,100).'...';
					switch ($value['subscription_icon'])
					{
						case 'evalue' :
							$response2['profiles'][$key]['subscription_icon']=mainMem::EVALUE_LABEL;
							break;
						case 'erishta' :
							$response2['profiles'][$key]['subscription_icon']=mainMem::ERISHTA_LABEL;
							break;
						case 'eadvantage':
							$response2['profiles'][$key]['subscription_icon']=mainMem::EADVANTAGE_LABEL;
							break;
                                                default:
                                                        $freeMemberCount++;
                                                        break;
							
					}
				}
				}
				$response2["no_of_results"]=count($response['profiles']);
				$response2["page_index"]=$pageNo;//$response["currentpage"];
				$response2["searchBasedParam"]=null;
				$response2["searchid"]=$infoTypeId;
				$response2["dppLinkAtEnd"]=null;
                                if ($infoType == "MATCH_ALERT") {
                                        $response2["dppLinkAtEnd"] = 'Go To Desired Partner Matches.';
                                }
                $response2["archivedInterestLinkAtEnd"] = null;
                if ( $infoType == "INTEREST_RECEIVED")
                {
                	$response2["archivedInterestLinkAtEnd"] = 'Archived Interests'; 
                }

				$response2["sorting"]=0;
				$response2["sortType"]=null;
				$response2["stype"]=null;
				$response2["defaultImage"]=null;//PictureFunctions::getNoPhotoJSMS($profileObj->getGENDER());
				$response2["next_avail"]=$response['nextpossible'];
				$response2["relaxation_text1"]=null;
				$response2["relaxation_text2"]=null;
				$response2["relaxation_text_params"]=null;
				$response2["clusters"]=null;
				//set navigator
				if(MobileCommon::isDesktop() && $module=="ContactCenterDesktop")
				{
					$searchid = $infoTypeId;
					$requestedPage = $request->getParameter('pageNo');
					$navObj = new NAVIGATOR();
					$response2["ccnavigator"] = $navObj->navigation($response2["navigation_type"],"infoTypeId__$searchid@pageNo__$requestedPage",'','Symfony');
				}
				
				if($response2["infotype"]=="PHOTO_REQUEST_RECEIVED")
				{
					if(in_array($profileObj->getHAVEPHOTO(),array("","N")))
						$response2["havephoto"] = "N";
					else
						$response2["havephoto"] = "Y";
					if($response2["havephoto"] == "N")
			    {
				$response2["EditWhatNew"] = "FocusPhoto";
				$response2["requestMessage"] = "These people have requested for your photo";
				$response2["requestButton"] = "Upload Photo";
			    }
		    }
				if($response2["infotype"]=="HOROSCOPE_REQUEST_RECEIVED")
				{
			$horoscopeObj = new Horoscope();
			$response2["haveHoroscope"] = $horoscopeObj->ifHoroscopePresent($pid);
			if($response2["haveHoroscope"]=='N')
			{
				$response2["EditWhatNew"] = "FocusHoroscope";
				$response2["requestMessage"] = "These people have requested for your horoscope";
						$response2["requestButton"] = "Upload Horoscope";
					}
				}


				switch ($response2["infotype"])
				{
					case 'ACCEPTANCES_RECEIVED':
					$response2["subtitle"]='Accepted Me ' . $response2['total'];
					$response2["title2"]='I Accepted' ; 
					$response2["infotypeid2"]=3; 
					$response2["url"]="/profile/contacts_made_received.php?page=accept&filter=M";
					//if(MobileCommon::isDesktop()==false)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::ACCEPT)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
							$contactsObj = new ContactsRecords();
							$contactsObj->makeAllContactSeen($pid,ContactHandler::ACCEPT);
						}
						$profileMemcacheObj = new ProfileMemcacheService($profileObj);
						$currentCount =  $profileMemcacheObj->get("ACC_ME_NEW");
						if($currentCount)
						{
							$profileMemcacheObj->update("ACC_ME_NEW",-$currentCount);
							$profileMemcacheObj->updateMemcache();
						}
					}
					break;
					                                   
					case 'ACCEPTANCES_SENT': 
					$response2["subtitle"]='I Accepted '.$response2['total']; 
					$response2["title2"]='Accepted Me';$response2["infotypeid2"]=2;
					$response2["url"]="/profile/contacts_made_received.php?page=accept&filter=R";
					break;
				   
					case 'INTEREST_SENT': 
					$response2["subtitle"]='Sent '.$response2['total']; 
					$response2["title2"]='Received';
					$response2["infotypeid2"]=1;
					$response2["url"]="/profile/contacts_made_received.php?page=eoi&filter=R";
					$response2['subheading'] = InboxEnums::getInboxSubHeading($response2);
					 break;  
					// later modified the data sent as we have to update the seen status of the receiver in the contacts table and also eoi viewed log
					case 'INTEREST_RECEIVED':
					case 'INTEREST_RECEIVED_FILTER':
					$response2["subtitle"]='Received '.$response2['total'];
					$response2["title2"]='Sent';
					$response2["infotypeid2"]=6 ;
					$response2["url"]="/profile/contacts_made_received.php?page=eoi&filter=M";
					//if(MobileCommon::isDesktop()==false)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $currentTime = date('Y-m-j H:i:s');
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::INITIATED,'time'=>$currentTime)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
													 $contactRObj=new EoiViewLog();
                                                        $contactRObj->setEoiViewedForAReceiver($pid,'N');
							$contactsObj = new ContactsRecords();
							$contactsObj->makeAllContactSeen($pid,ContactHandler::INITIATED);
                                                       
						}
						$profileMemcacheObj = new ProfileMemcacheService($profileObj);
						$currentCount =  $profileMemcacheObj->get("AWAITING_RESPONSE_NEW");
						if($currentCount)
						{	
							$profileMemcacheObj->update("AWAITING_RESPONSE_NEW",-$currentCount);
							$profileMemcacheObj->updateMemcache();
						}
					}
					break;
					
					case 'NOT_INTERESTED_BY_ME': 
					$response2["subtitle"]='I Declined '.$response2['total']; 
					$response2["title2"]='They Declined';
					$response2["infotypeid2"]=10; 
					$response2["url"]="/profile/contacts_made_received.php?page=decline&filter=R";
					break;
					
					case 'NOT_INTERESTED': 
					$response2["subtitle"]='They Declined '.$response2['total']; 
					$response2["title2"]='I Declined';
					$response2["infotypeid2"]=11; 
					$response2["url"]="/profile/contacts_made_received.php?page=decline&filter=M";
					$profileMemcacheObj = new ProfileMemcacheService($profileObj);
						$currentCount =  $profileMemcacheObj->get("DEC_ME_NEW");
						if($currentCount)
						{	
							if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
							{
								$producerObj=new Producer();
								if($producerObj->getRabbitMQServerConnected())
								{
									$updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::DECLINE)), 'redeliveryCount'=>0 );
									$producerObj->sendMessage($updateSeenData);
									$updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::CANCEL_ALL)), 'redeliveryCount'=>0 );
									$producerObj->sendMessage($updateSeenData);
								
								}
								else
								{
							              $this->sendMail();
								}
							}
							else
							{

								$contactsObj = new ContactsRecords();
								$contactsObj->makeAllContactSeen($pid,ContactHandler::DECLINE);
								$contactsObj->makeAllContactSeen($pid,ContactHandler::CANCEL_ALL);
//								$contactsUpdateCancelObj->updateCancelSeen($pid);
								
                                                               
							}
							$profileMemcacheObj->update("DEC_ME_NEW",-$currentCount);
							$profileMemcacheObj->updateMemcache();
						}
					break;
					
					case 'MATCH_ALERT': 
					$response2["subtitle"]='Daily Recommen.. '.$response2['total'];					
					if(MobileCommon::isDesktop())
						$response2["subtitle"]='Daily Recommendations '.$response2['total'];
					$response2["title2"]=null;
					break;
					
					case 'PHOTO_REQUEST_RECEIVED': 
						$response2["subtitle"]='Photo Requests Received'.$response2['total'];
						$response2["title2"]=null;
						//if(MobileCommon::isDesktop()==false)
						{
							$profileMemcacheObj = new ProfileMemcacheService($profileObj);
							$photoRCurrentCount=$profileMemcacheObj->get("PHOTO_REQUEST_NEW");
							if ($photoRCurrentCount!='0'){
								if(JsConstants::$updateSeenQueueConfig['PHOTO_REQUEST'])
								{
									$producerObj=new Producer();
									if($producerObj->getRabbitMQServerConnected())
									{
										$updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'PHOTO_REQUEST','body'=>array('profileid'=>$pid)), 'redeliveryCount'=>0 );
										$producerObj->sendMessage($updateSeenData);
									}
									else
									{
								              $this->sendMail();
									}
								}
								else
								{
									Inbox::setAllPhotoRequestsSeen($pid);
								}
								$profileMemcacheObj->update("PHOTO_REQUEST_NEW",-$photoRCurrentCount);
								$profileMemcacheObj->updateMemcache();
							}
						}
						break;

					case 'PHOTO_REQUEST_SENT': 
						$response2["subtitle"]='Photo Requests Sent'.$response2['total'];
						$response2["title2"]=null;
						break;
				    case 'HOROSCOPE_REQUEST_RECEIVED': 
						$response2["subtitle"]='Horoscope Requests Received'.$response2['total'];
						$response2["title2"]=null;
						//if(MobileCommon::isDesktop()==false)
						{
							$profileMemcacheObj = new ProfileMemcacheService($profileObj);
							$horoscopeRCurrentCount=$profileMemcacheObj->get("HOROSCOPE_NEW");
							if ($horoscopeRCurrentCount!='0'){
                                                                if(JsConstants::$updateSeenQueueConfig['HOROSCOPE_REQUEST'])
                                                                {
                                                                        $producerObj=new Producer();
                                                                        if($producerObj->getRabbitMQServerConnected())
                                                                        {
                                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'HOROSCOPE_REQUEST','body'=>array('profileid'=>$pid)), 'redeliveryCount'=>0 );
                                                                                $producerObj->sendMessage($updateSeenData);
                                                                        }
                                                                        else
                                                                        {
                                                                              $this->sendMail();
                                                                        }
                                                                }
                                                                else
                                                                {
									Inbox::setAllHoroscopeRequestsSeen($pid);
								}
								$profileMemcacheObj->update("HOROSCOPE_NEW",-$horoscopeRCurrentCount);
								$profileMemcacheObj->updateMemcache();
							}
						}
						break;
					case 'INTRO_CALLS': 
						$response2["subtitle"]='Intro calls'.$response2['total'];
						$response2["title2"]=null;
					break;
					case 'INTRO_CALLS_COMPLETE': 
						$response2["subtitle"]='Intro calls'.$response2['total'];
						$response2["title2"]=null;
					break;
					case 'HOROSCOPE_REQUEST_SENT': 
						$response2["subtitle"]='Horoscope Requests Sent'.$response2['total'];
						$response2["title2"]=null;
						break;

					case 'VISITORS': 
                                        if(MobileCommon::isDesktop()){
                                            if($infoTypenav["matchedOrAll"]=="A")
                                                $response2["subtitle"]='All Profile Visitors '.$response2['total'];
                                            else
                                                $response2["subtitle"]="Matching Visitors ".$response2['total'];
                                            $response2["title2"]=null;
                                        }
                                        else if($infoTypenav["matchedOrAll"]=="" && !MobileCommon::isNewMobileSite()){
                                            $response2["subtitle"]='Profile Visitors '.$response2['total'];
                                            $response2["title2"]=null;
                                        }
                                        elseif($infoTypenav["matchedOrAll"]=="A"){
                                            $response2["subtitle"]='All Visitors '.$response2['total'];
                                            $response2["title2"]="Matching"; 
                                            $response2["url"]="/profile/contacts_made_received.php?page=visitors&filter=R&matchedOrAll=M";
                                            $response2["visitorAllOrMatching"]='A';
                                        }
                                        else{
                                            $response2["title2"]='All Visitors';
                                            $response2["subtitle"]="Matching ".$response2['total']; 
                                            $response2["url"]="/profile/contacts_made_received.php?page=visitors&filter=R&matchedOrAll=A";
                                            $response2["visitorAllOrMatching"]='M';
                                        }
					break;
					
					case 'SHORTLIST': 
					$response2["subtitle"]='Shortlisted Pro.. '.$response2['total'];
					if(MobileCommon::isDesktop())
						$response2["subtitle"]='Shortlisted Profiles '.$response2['total'];
					$response2["title2"]=null;
					break;

					case 'FILTERED_INTEREST': 
					$response2["subtitle"]='Filtered Interests '.$response2['total'];
					$response2["title2"]=null;
						$profileMemcacheObj = new ProfileMemcacheService($profileObj);
						$currentCount =  $profileMemcacheObj->get("FILTERED_NEW");
						if($currentCount)
						{
							if(JsConstants::$updateSeenQueueConfig['ALL_CONTACTS'])
							{
								$producerObj=new Producer();
								if($producerObj->getRabbitMQServerConnected())
								{
                                                                        $currentTime = date('Y-m-j H:i:s');
									$updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_CONTACTS','body'=>array('profileid'=>$pid,'contactType'=>ContactHandler::FILTERED,'time'=>$currentTime)), 'redeliveryCount'=>0 );
									$producerObj->sendMessage($updateSeenData);
								}
								else
								{
							              $this->sendMail();
								}
							}
							else
							{
								 $contactRObj=new EoiViewLog();
                                                                $contactRObj->setEoiViewedForAReceiver($pid,'Y');
								$contactsObj = new ContactsRecords();
								$contactsObj->makeAllContactSeen($pid,ContactHandler::FILTERED);
                                                               
							}
							$profileMemcacheObj->update("FILTERED_NEW",-$currentCount);
							$profileMemcacheObj->updateMemcache();
						}
					break;

					case 'PEOPLE_WHO_VIEWED_MY_CONTACTS': 
					$response2["subtitle"]='Who Viewed My.. '.$response2['total'];
					$response2["title2"]=null;
					break;
                                        case "MY_MESSAGE":$response2["hidePaginationCount"] = 1;
					case "MY_MESSAGE_RECEIVED":
					//if(MobileCommon::isDesktop()==false)
					{
                                                if(JsConstants::$updateSeenQueueConfig['ALL_MESSAGES'])
                                                {
                                                        $producerObj=new Producer();
                                                        if($producerObj->getRabbitMQServerConnected())
                                                        {
                                                                $updateSeenData = array('process' =>'UPDATE_SEEN','data'=>array('type' => 'ALL_MESSAGES','body'=>array('profileid'=>$pid)), 'redeliveryCount'=>0 );
                                                                $producerObj->sendMessage($updateSeenData);
                                                        }
                                                        else
                                                        {
                                                              $this->sendMail();
                                                        }
                                                }
                                                else
                                                {
							MessageLog::makeAllMessagesSeen($pid);
							ChatLog::makeAllChatsSeen($pid);
						}
						$profileMemcacheObj = new ProfileMemcacheService($profileObj);
						$currentCount =  $profileMemcacheObj->get("MESSAGE_NEW");
						if($currentCount)
						{
							$profileMemcacheObj->update("MESSAGE_NEW",-$currentCount);
							$profileMemcacheObj->updateMemcache();
						}
					}
					break;
					default:
					$response2["subtitle"]=$response2['title'];
					$response2["title2"]=null;
					break;
				}
                                if($response2["infotype"] != "PEOPLE_WHO_VIEWED_MY_CONTACTS" || (($freeMemberCount > 0) && (CommonFunction::getMainMembership($profileObj->getSUBSCRIPTION()) == mainMem::EVALUE || CommonFunction::getMainMembership($profileObj->getSUBSCRIPTION()) == mainMem::EADVANTAGE)) ){
                                        $response2["headingCount"]=$freeMemberCount;
                                        $response2["headingTotalCount"]=count($response2['profiles']);
                                        $response2["loggedin_subscription"] = '';
                                        if(CommonFunction::getMainMembership($profileObj->getSUBSCRIPTION()) == mainMem::EVALUE){
                                                $response2["loggedin_subscription"]=  mainMem::EVALUE_LABEL;
                                        }elseif(CommonFunction::getMainMembership($profileObj->getSUBSCRIPTION()) == mainMem::EADVANTAGE){
                                                $response2["loggedin_subscription"]=  mainMem::EADVANTAGE_LABEL;
                                        }
                                        $response2['subheading'] = InboxEnums::getInboxSubHeading($response2);
                                }
			}
                        
			$response2["result_count"]=$response2['subtitle'];

			/** caching **/
                        $ifApiCached = InboxUtility::cachedInboxApi('set',$request,'',$response2);
                        /** caching **/		
			$respObj = ApiResponseHandler::getInstance();
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$respObj->setResponseBody($response2);
			$respObj->generateResponse();
			//die; //LAVESH

			//print_r($response2);
			// die();
			if(MobileCommon::isApp()==null)
			   return sfView::NONE;
		}
		else
		{
			$respObj = ApiResponseHandler::getInstance();
			if(is_array($output))
				$respObj->setHttpArray($output);
			else
				$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$respObj->generateResponse();
		}
		die;
  }
  
  
  
  public function executeJsmsPerform($request)
	{
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj->validateRequestInboxData($request);
		$output = $inputValidateObj->getResponse();
		if($output["statusCode"]==ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
			$request->setParameter("infoTypeId",$request->getParameter("searchId"));
			$request->setParameter("pageNo",$request->getParameter("currentPage"));
			$request->setParameter("fromPage","contacts");
			$request->setParameter('useSfViewNone','1');

			$profileObj=LoggedInProfile::getInstance('newjs_master');
			if($profileObj==null || $profileObj->getPROFILEID()==null || $profileObj->getPROFILEID()==''){
			     $this->forward("static","logoutPage");
			}
			if(!$profileObj->getUSERNAME())
				$profileObj->getDetail($profileObj->getPROFILEID(),"PROFILEID","*");
			if($request->getParameter("searchId")==16 && !CommonFunction::isPaid($profileObj->getSUBSCRIPTION()))
			{
					$this->isEvalue='N';
					$this->firstResponse ="V";
					$this->responseJS="V";
					$this->gender=$profileObj->getGENDER();
					$this->setTemplate("jsmsContactsViewed");
					
			}
			else
			{		
				ob_start();
				$navigation_type = $request->getParameter("navigation_type");
				
				$navigatorObj=new Navigator();
				$durl=$navigatorObj->develop_url();
				$navigatorObj->navigation($navigation_type,$durl);
				$this->BREADCRUMB=$navigatorObj->onlyBackBreadCrumb;
				$this->NAVIGATOR=$navigatorObj->NAVIGATOR;
				$this->nav_type=$navigation_type;

				sfContext::getInstance()->getController()->getPresentationFor('inbox','performV2');
				$jsonResponse = ob_get_contents(); //we can also get output from above command.
				ob_end_clean();
				$ResponseArr = json_decode($jsonResponse,true);
				$this->title=$ResponseArr['subtitle'];
				$this->title2=$ResponseArr['title2'];
				$this->infotypeid2=$ResponseArr['infotypeid2'];
				$this->infotype=$ResponseArr['infotype'];
                                $this->visitorAllOrMatching = $ResponseArr['visitorAllOrMatching'];
				$this->noresultmessage = $ResponseArr["noresultmessage"];
				$this->_SEARCH_RESULTS_PER_PAGE = ProfileInformationModuleMap::$ContactCenterAPP[$ResponseArr['infotype']]['COUNT'];	
				$this->heading = $ResponseArr['subtitle'];
				$this->noresultmessage = $ResponseArr["noresultmessage"];
				$this->tracking = $ResponseArr["tracking"]?"&".$ResponseArr["tracking"]:"";
				if ($ResponseArr['url'])
					$this->url=$ResponseArr['url'];
				if($request->getParameter("searchId")==4)
				{
					$this->setTemplate("jsmsMessages");
					$this->firstResponse =$ResponseArr;
					$this->responseJS=$jsonResponse;
				}
				else if($request->getParameter("searchId")==9)
				{
					$this->setTemplate("jsmsPhotoRequest");
					$this->firstResponse =$ResponseArr;
					$this->responseJS=$jsonResponse;

				}
				else if($request->getParameter("searchId")==16)
				{
					$this->isEvalue='Y';
					$this->gender=$profileObj->getGENDER();
					$this->setTemplate("jsmsContactsViewed");
					$this->firstResponse =$ResponseArr;
					$this->responseJS=$jsonResponse;
				}
				else
				{
				   $this->firstResponse = $jsonResponse;
				}
				 //   print_r($ResponseArr)  ; die();
				 $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobContactPageUrl);
				 if($request->getParameter('fromReg')==1)
					{
						//If coming directly from registration, used for google pixel code
						if (trim($request->getParameter('groupname'))) {
							$this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$profileObj);
						}
					}
			}
				//  print_r($ResponseArr); die();
		}
		else
		{
			$respObj = ApiResponseHandler::getInstance();
			if(is_array($output))
				$respObj->setHttpArray($output);
			else
				$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$respObj->generateResponse();
		}
	}

	/*function to remove profile from intro call list if present
	* return : postdata(false set) and removedFlag
	*/
	public function executeRemoveFromICList(sfRequest $request)
	{
		$match_profileChecksum = $request->getParameter("profilechecksum");
		$removedFlag = "false";
		if($match_profileChecksum)
		{
			$params["MATCH_ID"] = JsAuthentication::jsDecryptProfilechecksum($match_profileChecksum);
			$loginProfileObj=LoggedInProfile::getInstance('newjs_master');
        	$params["PROFILEID"] = $loginProfileObj->getPROFILEID();
        	$params["CALL_STATUS"] = 'N';
			$introObj = new getIntroCallHistory();
			$introObj->removeFromprofileICList($params);
			unset($introObj);
			$removedFlag = "true";
		}
		$output = array("postdata"=>"false","removedFlag"=>$removedFlag);
		$respObj = ApiResponseHandler::getInstance();
        if($removedFlag=="true")
            $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
        else
            $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
        $respObj->setResponseBody($output);  
        $respObj->generateResponse();
        die;
	}	

  /**
   * matchAlertCountResetLogic
   * Check wheather we need to reset match Alert count
   * @param : void
   * @return : void
   * @access : private
   */
  private function matchAlertCountResetLogic($profileObj)
  {
    $request=sfContext::getInstance()->getRequest();
    $bNoFlushMatchAlertCount = $request->getParameter("noFlushMatchAlertCount") === 1 ? true : false;
    $bNoFlushMatchAlertCount = !$bNoFlushMatchAlertCount && 1 === $request->getAttribute("noFlushMatchAlertCount") ? true : false;
    
    $bMyJs= $request->getParameter("myjs") == 1 ? true : false;
    if(false === $bNoFlushMatchAlertCount && false === $bMyJs) {
      $profileCacheObj = new ProfileMemcacheService($profileObj);
      $profileCacheObj->unsetKey("MATCHALERT");
      $request->setAttribute("resetMatchAlertCount",1);
    }
  }
  private function sendMail()
  {
	$http_msg=print_r($_SERVER,true);
	mail("eshajain88@gmail.com,lavesh.rawat@gmail.com","rabbit mq server issue","rabbit mq server issue");
  }
}
