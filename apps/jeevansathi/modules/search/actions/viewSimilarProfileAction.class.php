<?php
/**
 * profile actions.
 * ViewSimilarProfile
 * Controller to view similar profiles after expressing interest
 * @package    jeevansathi
 * @subpackage profiles
 * @author     Nitesh Sethi
 */
class viewSimilarProfileAction extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$suggAlgoNoOfResults = viewSimilarConfig::$suggAlgoNoOfResults_Mobile;
		include_once(JsConstants::$docRoot."/classes/Membership.class.php");
		$this->getResponse()->setSlot("optionaljsb9Key", "SIMILAR_PROFILE_PAGE");
		//$this->pageName = "viewSimilarPage";
		$this->isMobile=MobileCommon::isMobile("JS_MOBILE");
		if($this->isMobile)
		{
			$profileChecksum=$request->getParameter("profilechecksum");
			$url=JsConstants::$siteUrl.'/search/MobSimilarProfiles?fromViewSimilarActionMobile=1&page=idd1&profilechecksum='.$profileChecksum.'';
                        header('Location: '.$url);
                        die;

		}
		//Contains login credentials
		$this->loginData = $request->getAttribute("loginData");
		$this->loginProfile = LoggedInProfile::getInstance();
		$this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
		$this->loggedInGender = $this->loginProfile->getGENDER();
		$this->loggedInProfileid=$this->loginProfile->getPROFILEID();
		$this->loggedIn = 1;
		//Profile status incomplete/under screening
		if($this->loginProfile->getINCOMPLETE()=="Y")
			$this->profileStatus='I';
		else if($this->loginProfile->getACTIVATED()=="N" || $this->loginProfile->getACTIVATED()=="U" || $this->loginProfile->getACTIVATED()=="P")
			$this->profileStatus="U";
		//Profile Authentication Variables		
		$this->profileChecksum = JsAuthentication::jsEncryptProfilechecksum($this->loggedInProfileid);
		$JsAuthenticationObj = new JsAuthentication;
		$this->checksum = $JsAuthenticationObj->js_encrypt($this->loggedInProfileid);
		$this->havephoto = $this->loginProfile->getHAVEPHOTO();		
		if($request->getParameter("actions_buttons"))
				$this->actions_buttonsVSP=$request->getParameter("actions_buttons");
			else
				$this->actions_buttonsVSP=0;
		//Contacted Profile Details
		if($request->getParameter("profilechecksum"))
		{
			$contactedProfileId = JsAuthentication::jsDecryptProfilechecksum($request->getParameter("profilechecksum"));
			$request->setParameter("contact",$contactedProfileId);	
			$this->contactedProfilechecksum=$request->getParameter("profilechecksum");
		}
		else
			$contactedProfileId=$request->getParameter("contact");

		if(!$contactedProfileId)
		{
			$errorString = "contactedProfileId is blank in viewSimilarProfileAction.class.php";
                        jsException::nonCriticalError($errorString);
			header("Location: $SITE_URL/search/partnermatches");
			die;
		}
		if($request->getParameter("SIM_USERNAME"))
			$contactedUsername=$request->getParameter("SIM_USERNAME");
		else
		{
			$contactedProfileObj= new Profile('',$contactedProfileId);
			$contactedProfileObj->getDetail($contactedProfileId,'','USERNAME');
			$contactedUsername=$contactedProfileObj->getUSERNAME();
		}
		$this->arrOutDisplay = $this->getContactedProfileData($contactedUsername,$request);
		//handling of no profile case
		if($request->getAttribute("ERROR"))
		{
			$request->setAttribute("ERROR",$request->getAttribute("ERROR"));
			$this->forward("profile","noprofile");
		}
		$this->Username = $contactedUsername;
		$MESSAGE=$request->getParameter("MESSAGE");
		$this->TRIM_MESSAGE=stripslashes(htmlspecialchars($MESSAGE));
		$this->d_status=$request->getParameter("type_of_con");
		$ProfileDrafts= new ProfileDrafts($this->loginProfile);
		$eoiArr=$ProfileDrafts->getEoiDrafts('Y');
		$overwrite=1;
		foreach($eoiArr as $key=>$val)
		{
			$DRAFT[$val[2]]=htmlspecialchars($val[0],ENT_QUOTES);
			//No need to show replace option , since the message is already from draft.
			if($val[1]==$MESSAGE)
				$overwrite=0;
			$DRA_MES[$val[2]]=htmlspecialchars($val[1],ENT_QUOTES);
			$start++;
		}
		if($start>=5 && $overwrite==1)
		{
			$this->DRA_MES_OPTION=$DRAFT;
			$this->OVERFLOW=1;
		}
		if($MESSAGE=="")
			$overwrite=0;
		if($overwrite)
			$this->SAVE_MESSAGE=1;
		$message=stripslashes($MESSAGE);
		$this->CUST_MESSAGE=htmlspecialchars($message,ENT_QUOTES);
		$memHandlerObj = new MembershipHandler();
			$data2 = $memHandlerObj->fetchHamburgerMessage($request);
		$this->MembershipMessage = $data2['hamburger_message']['top'];
        $this->MembershipMessage = $memHandlerObj->modifiedMessage($data2);
		//print_r($this->MembershipMessage);die; 

		//validation Handler
		if($contact && !is_numeric($contact))
		{
			ValidationHandler::getValidationHandler("","Non numeric contact Id in view_similar_profile.php:".$contact,"Y");
		}
		else
		{
			$this->Profile=new Profile("newjs_masterRep");
			$this->Profile->getDetail($contactedProfileId,"PROFILEID","*");
			if($this->Profile->getUSERNAME()!=$contactedUsername)
			{
				ValidationHandler::getValidationHandler("","contact Id in view_similar_profile page is not correct as there is a mismatch in username and profile username:".$contact."not equals".$contactedUsername,"Y");
			}
		}
		
		//Bread crumb navigation in view similar profile
		$naviObj=new Navigator();
		$naviObj->navigation("CVS","",$this->Profile->getUSERNAME(),'Symfony');
		$this->BREADCRUMB=$naviObj->BREADCRUMB;
		//$this->NAVIGATOR = $this->BREADCRUMB;
		$this->NAVIGATOR =navigation("CVS","",$this->Profile->getUSERNAME(),'Symfony');
                //for viewSimilar page response
		if(MobileCommon::isDesktop() && $this->loginProfile && $this->loginProfile->getPROFILEID() !="" && $this->loginProfile->getPROFILEID() != $this->Profile->getPROFILEID()){

		//if($request->getParameter("stype")=='V' || $request->getParameter("Stype") =="V")
		if($request->getParameter("contactedProfileDetails")=='hide')
                        $stype=SearchTypesEnums::VIEW_SIMILAR_ACCEPT_PC;
                else
                        $stype=SearchTypesEnums::VIEW_SIMILAR_ECP_PC;

			$arrPass = array('stype'=>$stype,"responseTracking"=>$this->responseTracking,'page_source'=>"VDP_VSP",'isIgnored'=>$this->arrOutDisplay['page_info']['is_ignored']);
			$arrPass["USERNAME"] = $this->Profile->getUSERNAME();
			$arrPass["OTHER_PROFILEID"] = $this->Profile->getPROFILEID();
			$buttonObj = new ButtonResponse($this->loginProfile,$this->Profile,$arrPass);
			$this->arrOutDisplay["button_details"] = $buttonObj->getButtonArray();
			$this->finalResponse=json_encode($this->arrOutDisplay);
		}                
		//View Similar Profile Object to set Search Criteria
                $modVal = 9;
                $loggedinMod = $this->loginProfile->getPROFILEID()%$modVal;
                $modResult =  array(1);
                if(JsConstants::$vspServer != 'live' || !in_array($loggedinMod,$modResult)){
                    $viewSimilarProfileObj=new viewSimilarfiltering($this->loginProfile,$this->Profile);
                    $viewSimilarProfileObj->getViewSimilarCriteria();
                    if($viewSimilarProfileObj->getProfilesToShow() && $viewSimilarProfileObj->getProfilesToShow()!=='9999999999')
                            $this->similarPageShow=1;
                    else
                            $this->similarPageShow=0;
                }
                else
                    $viewSimilarProfileObj=new viewSimilarfiltering($this->loginProfile,$this->Profile,$removeFilters=1);
		//EOI Successsfull Confirmation Message
		if($request->getParameter('contactEngineConfirmation'))
		{
			$this->contactEngineConfirmation=stripslashes(urldecode($request->getParameter('contactEngineConfirmation')));
		}		
		
		//Search Tuple Fields Array			
		$this->fieldsDisplayedInSearchTuple = SearchConfig::$fieldsDisplayedInSearchTuple;
		
		//Solar Search
		$searchEngine = 'solr';
		$outputFormat = 'array';
                
                $requestTimeout = 300;
                if(JsConstants::$vspServer == 'live' && in_array($loggedinMod,$modResult)){
                    if($this->loginProfile->getGENDER() == 'M')
                      $feedURL = JsConstants::$vspMaleUrl;
                    else
                      $feedURL = JsConstants::$vspFemaleUrl;
                    $profileListObj = new IgnoredContactedProfiles();
                    $ignoredContactedProfiles = $profileListObj->getProfileList($this->loginProfile->getPROFILEID(),'');
                    $postParams = json_encode(array("PROFILEID"=>$this->loggedInProfileid,"PROFILEID_POG"=>$this->Profile->getPROFILEID(),'removeProfiles'=>$ignoredContactedProfiles));
                    $profilesList = CommonUtility::sendCurlPostRequest($feedURL,$postParams,$requestTimeout);
                    if($profilesList === false){
                        $date = date("Y-m-d");
                        $file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/vspTimedout_".$date.".txt","a");
                        $stringToWrite = $this->loginProfile->getPROFILEID().",".$this->Profile->getPROFILEID().",".date("H:i:s",time());
                        fwrite($file,$stringToWrite."\n");
                        fclose($file);
                    }
                    if($profilesList == "Error"){
                        $profileidsort='';
                        $this->similarPageShow=0;
                    }
                    else if($profilesList =='')
                        $this->similarPageShow=0;
                    else{
                        $profileidsort = str_replace(","," ",$profilesList);
			$viewSimilarProfileObj->profilesToShow = $profileidsort;
                        $viewSimilarProfileObj->setProfilesToShow($profileidsort);
                        $this->similarPageShow=1;
                    }
                  }
                else 
                    $profileidsort=$viewSimilarProfileObj->getViewSimilarCriteria();
		$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
		$viewSimilarProfileObj->setNoOfResults(viewSimilarConfig::$suggAlgoNoOfResultsNoFilter);
		$responseObj = $SearchServiceObj->performSearch($viewSimilarProfileObj,$results_orAnd_cluster,$clustersToShow,$currentPage,$cachedSearch,$this->loginProfile);
         //print_r($responseObj);die;
        //search template info array
		if(MobileCommon::isDesktop())
		{
			$SearchDisplayObj = new SearchApiDisplay();
			$resultsArray = $SearchDisplayObj->searchPageDisplayInfo($this->isMobile,$this->loginProfile,$responseObj,'','','','ProfilePic450Url');
			//print_r($resultsArray);die;
		}
		else
		{
			$SearchDisplayObj = new SearchDisplay();
			$resultsArray = $SearchDisplayObj->searchPageTemplateInfo($this->isMobile,$this->loginProfile,$responseObj);
		}
		
		$profileidsort=explode(" ",$profileidsort);

		$l=0;
		foreach($profileidsort as $v)	
		{
			if($l<viewSimilarConfig::$suggAlgoNoOfResults_Mobile) 
			{
				if($resultsArray[$v])
				{
					$resultsArraySort[$v] = $resultsArray[$v];
					$l++;
				}
			}
			else
				break;
		}
		$resultsArray = $resultsArraySort;
		$this->finalResultsArray = $resultsArray;
                $dateHourToAppend = date('m-d', time())."__".(date('H')-date('H')%3)."-".(date('H')+3-date('H')%3);
                $noOfResultsToStore = min(count($this->finalResultsArray),25);
                JsMemcache::getInstance()->hIncrBy("ECP_SIMILAR_PROFILES_COUNT_".MobileCommon::getChannel(),$dateHourToAppend."__".$noOfResultsToStore,1);
		if(!$responseObj->getTotalResults())
			$this->similarPageShow=0;
		//To be used for search eoi
		$this->loginProfile=$this->loginProfile;
		$state=str_split(strtolower($this->loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState()));
		Messages::setUserChecksum(JsCommon::createChecksumForProfile($this->loggedInProfileid));
		$flag=$this->loginProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
		if(in_array($state[0],array("c","d")))
			$this->FREE_TRIAL_OFFER=$state[0];
		if($this->loginProfile->getPROFILE_STATE()->getFTOStates()->getSubState()=="E4" && $flag=="T")
			$this->FREE_TRIAL_OFFER="d";
		if($this->loginProfile->getGENDER()=="M")
		{
			$this->himher='her';
			$this->heshe="she";
		}			
		else
		{
			$this->himher='him';
			$this->heshe="he";
		}
		
		$this->profileOrExpressButton= 'E';
		if($request->getParameter("Stype")=='V')
			$this->stype=SearchTypesEnums::VIEW_SIMILAR_ACCEPT_PC;
		else
			$this->stype=SearchTypesEnums::VIEW_SIMILAR_ECP_PC;
		
		if($viewSimilarProfileObj->getGENDER()=='M')
			$this->userGender = "He";
		else
			$this->userGender = "She";
		$this->draftsLogic($this->loginProfile);
		
		//Premium Member or Free Member
		if(PremiumMember::isDummyProfile($this->loggedInProfileid))
			$this->premiumDummyUser = 1;

		//Subscription Status
		$subscr = $this->loginProfile->getSUBSCRIPTION();
		if(strstr($subscr,'R'))
			$this->featured='Y';
		if(strstr($subscr,'B'))
			$this->boldListing='B';			
		$this->PaidStatus='free';
		if(CommonFunction::isPaid($subscr))
		{
			//$this->drafts=ProfileDrafts::
			
			$this->drafts=CommonUtility::fetchDrafts($this->loggedInProfileid,'N');
			
			$this->PaidStatus='paid';
			
		}
		
		//phone Verified Status
		if(!(JsCommon::isContactVerified($this->loginProfile)))
		{
			$this->PH_UNVERIFIED_STATUS=1;
			if(CommonUtility::InvalidLimitReached($this->loginProfile))
				$this->SHOW_UNVERIFIED_LAYER=1;
		}
		
		//Profile Percent Section
		$this->ProfilePercentSection($this->loggedInProfileid);
		
		//Membership Section
		$this->membershipSection($subscr,$this->loggedInProfileid);

		if(!MobileCommon::isDesktop())
		{
		//right panel success story
			$rightPanelStory = IndividualStories::showSuccessPoolStory("ecp");
			$this->rightPanelStory = $rightPanelStory;
		}
		
		//transform array to search like response array for JSPC
		if(MobileCommon::isDesktop())
		{
			$vspObj = new ViewSimilarProfile();
			//print_r($this->finalResultsArray);die;
			$transformedResponse = $vspObj->transformVSPResponseForPC($this->finalResultsArray,$this->Username,$this->similarPageShow,$this->userGender,$stype,$this->loginProfile);
			$this->defaultImage = $transformedResponse["defaultImage"];
			$this->firstResponse = json_encode($transformedResponse);
			
			//unset($this->finalResultsArray); 
			//print_r($this->firstResponse);die; 
			unset($vspObj);
			//handle visibility of contacted user details top section
			if($request->getParameter("contactedProfileDetails"))
				$this->contactedProfileDetails = $request->getParameter("contactedProfileDetails");
			else
				$this->contactedProfileDetails = "show";
			if($request->getParameter("queryStringParams"))
				$this->viewProfileBackParams = $request->getParameter("queryStringParams");
			else
				$this->viewProfileBackParams="noParams=1";
			if($request->getParameter("from_mailer")==1)
				$this->dontShowBreadcrumb=1;
			else
				$this->dontShowBreadcrumb=0;
						$this->setTemplate("JSPC/jspcViewSimilarProfile");
		}		
	}
	
	/*
 * function works for drafts logic
 */
	public function draftsLogic($profileObj)
	{
		$draftObj=ProfileDrafts::getInstance($profileObj);
		$drafts=$draftObj->getEoiDrafts();
		$this->drafts=ProfileDrafts::UpdateDraftsKey($drafts);
		$this->presetEoiMessage=ProfileDrafts::getMessage($this->drafts,ProfileDrafts::PRESET_DRAFTID);
		$drafts=$draftObj->getAcceptDrafts();
		$drafts=ProfileDrafts::UpdateDraftsKey($drafts);
		$this->presetAccMessage=ProfileDrafts::getMessage($drafts,ProfileDrafts::PRESET_ACCEPT_DRAFTID);
		$drafts=$draftObj->getDeclineDrafts();
		$drafts=ProfileDrafts::UpdateDraftsKey($drafts);
		$this->presetDecMessage=ProfileDrafts::getMessage($drafts,ProfileDrafts::PRESET_DECLINE_DRAFTID);
	}


 	/*
 * function works for profile percent section
 */
 	public function ProfilePercentSection($mypid)
 	{
 		$cScoreObject = ProfileCompletionFactory::getInstance(null,null,$mypid);
 		$iPCS = $cScoreObject->getProfileCompletionScore();
 		$p_percent = $iPCS;
 		$arrMsgDetails = $cScoreObject->GetIncompleteDetails();
 		$arrLinkDetails = $cScoreObject->GetLink("MyJS");

 		$this->iPCS=$iPCS;
 		$this->arrMsgDetails=$arrMsgDetails;
 		$this->arrLinkDetails=$arrLinkDetails;
 		$this->PROFILE_PERCENT=$p_percent;
 	}

 /*
 * function to fetch basic data for a profile
 */
 private function getContactedProfileData($contactedProfileUsername,$request){
 	$request->setParameter("fromVSP",1);
 	ob_start();
 	$this->request->setParameter("username",$contactedProfileUsername);
 	$this->request->setParameter("forViewProfile","1");
 	$this->request->setParameter("internal",1);
 	$fieldValues = sfContext::getInstance()->getController()->getPresentationFor("profile","apidetailedv1");
 	$profileData = json_decode(ob_get_contents(),true);
  
 	ob_end_clean();

 	return $profileData;
 }
 
 /*
 * function works for membership section
 */
 
 public function membershipSection($subscription,$mypid)
 {

	//===============   MemberShip Section  =====================
 	if(strstr($subscription,'F'))
 		$fpaid=1;
 	else
 		$paymessage='';

 	$paymessage='<li> Instantly see Phone/Email of people you like</li> <li> Initiate Messages and Chat with them </li> <li> Receive more interests and faster responses </li> <li> Publish your Phone/Email to other members</li>';

 	$membershipObj=new  Membership;
 	$service_array=$membershipObj->lastMainExpiryDate($mypid);
 	if($fpaid)
 	{
 		if(strstr($subscription,'D'))
 		{
 			$paymessage='<li> Instantly see Phone/Email of people you like</li> <li> Initiate Messages and Chat with them </li> <li> Receive more interests and faster responses </li> <li> Publish your Phone/Email to other members</li>';
 			$this->evalue=1;
 		}
 		else
 		{
 			$paymessage='<li> Instantly see Phone/Email of people you like</li> <li> Initiate Messages and Chat with them </li> <li> Receive more interests and faster responses </li>';
 			$this->erishta=1;
 		}

 		if(is_array($service_array))
 		{
 			$this->EXPIRY_DT=$service_array["EXPIRY_DT"];
 			$this->EXPIRY_ALERT=$service_array["EXPIRY_IN_15"];
 			$this->SHOW_DT=$service_array["SHOW_10"];
 			$serviced=$service_array["SERVICEID"].",B".$service_array["SERVICEID"][1];	
 			$this->SHOW_SERVICE=$serviced;
 		}
 	}
 	else
 		$this->freeMember=1;
 	$this->logo=$logo;
 	$this->paymessage=$paymessage;

 	if(strlen($membershipObj->isRenewable($mypid))>2)
 	{
 		global $renew_discount_rate;
 		$renew_dt=$service_array["RENEW_DT"];
 		if($renew_dt && $renew_dt!='L')
 		{
 			$this->gadgetdateformat=$renew_dt;
 			$this->discount=$renew_discount_rate;
 		}
 	}
		//================= MemberShip Section ===================== 	 
 }
 
}
