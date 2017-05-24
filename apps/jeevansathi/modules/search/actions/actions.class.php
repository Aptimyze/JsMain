<?php
/**
 * search actions.
 *
 * @package    Action class of search.
 * @subpackage search
 * @author     Lavesh Rawat
 * @created 2012-06-30
 */
class searchActions extends sfActions
{
        /**
        * Automatically calls before the action to execute.
	* bms-zone is set,needed for banner display.
        */
        public function preExecute()
        {
                $searchChannelFactoryObj = new SearchChannelFactory();
                $this->SearchChannelObj = $searchChannelFactoryObj->getChannel();
                $this->request->setAttribute('bms_topright',4);
                $this->request->setAttribute('bms_bottom',6);
                $this->request->setAttribute('bms_sideBanner',674);
                $this->request->setAttribute('bms_searchMid',568);
                $this->request->setAttribute('bms_topright_loggedin',675);
        }

	public function getProfileOrExpressButtonValue()
	{
		return 'E';

		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($loggedInProfileObj->getPROFILEID()%2 == 0)
			return 'E';
		else
			return 'P';
	}
        public function execute15(sfWebRequest $request)
	{
		//Redirecting to refrrer
                if(!$_SERVER["HTTP_REFERER"])
                        $refral="/search/perform?15";
                else
                        $refral = $_SERVER["HTTP_REFERER"];
                
                $this->redirect($refral);
	}


	/**
	* Executes search action
	* @param sfRequest $request A request object
	*/
	public function executePerform(sfWebRequest $request)
	{
		if(!in_array($request->getParameter("callingSource"),array('ap','ap_eoi','myjs','sms')) )
			MobileCommon::forwardmobilesite($this,'','',1);
		$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsSearchUrl);
		$this->szNavType = 'SR';
		if($request->getParameter("searchBasedParam")=='twowaymatch' || $request->getParameter("reverseDpp")=='twowaymatch' || $request->getParameter("twowaymatch")==1)
		{
			$request->setParameter("twowaymatch",1);
			$request->setParameter("reverseDpp",'');
			$this->searchBasedParam = 'twowaymatch';
		}
		elseif($request->getParameter("searchBasedParam")=='matchalerts')
		{
			$request->setParameter("matchalerts",'All');
			$request->setParameter("sort_logic",SearchSortTypesEnums::matchAlertFlag);
			$this->searchBasedParam = 'matchalerts';
			$this->szNavType = 'MA';
		}
		elseif($request->getParameter("searchBasedParam")=='kundlialerts')
		{
			$request->setParameter("kundlialerts",'All');
			$request->setParameter("sort_logic",SearchSortTypesEnums::kundliAlertFlag);
			$this->searchBasedParam = 'kundlialerts';
			$this->szNavType = 'KM';
		}
                elseif($request->getParameter("searchBasedParam")=='justJoinedMatches' || $request->getParameter("justJoinedMatches")==1)
		{
			$request->setParameter("justJoinedMatches",'1');
			$this->searchBasedParam = 'justJoinedMatches';
		}
                elseif($request->getParameter("searchBasedParam")=='partnermatches' || $request->getParameter("reverseDpp")=='partnermatches' || $request->getParameter("partnermatches")==1)
		{
			$request->setParameter("partnermatches",'1');
			$request->setParameter("reverseDpp",'');
			$this->searchBasedParam = 'partnermatches';
		}
		//This has been added to show last search Results
		elseif($request->getParameter("searchBasedParam")=='lastSearchResults' || $request->getParameter("lastSearchResults")==1)
		{
			$request->setParameter("lastSearchResults",'1');
			$this->searchBasedParam = 'lastSearchResults';
		}


		$searchEngine = 'solr';
		$outputFormat = 'array';
		$searchId = $request->getParameter("searchId");
		$lastUsedCluster = $request->getParameter("NEWSEARCH_CLUSTERING");
		$partnermatches = $request->getParameter("partnermatches");
		$this->reverseDpp = $request->getParameter("reverseDpp");
                $currentPage = $request->getParameter("currentPage");
                $this->moreLinkCluster = $request->getParameter("moreLinkCluster");
		$this->originalCluster = $request->getParameter("originalCluster");
		$specialCriteria = $request->getParameter("specialCriteria");
		$noRelaxation = $request->getParameter("noRelaxation");
		$callingSource = $request->getParameter("callingSource");
		$profileList = $request->getParameter("profileList");
		$this->sort_logic = $request->getParameter("sort_logic"); 
		$this->twowaymatch = $request->getParameter("twowaymatch");
		$this->partnermatchesPage=$partnermatches;
	        $isearchCookie = $_COOKIE["ISEARCH"];

		if($this->searchBasedParam == 'partnermatches')
                        $noRelaxation=1;                                
                if($this->twowaymatch)
                {
                        $noRelaxation = 1;
                        $noCasteMapping = 1;
			$this->showSaveDppAndCriteria=0;
                }
		elseif($request->getParameter("matchalerts") || $request->getParameter("kundlialerts"))
                {
                        $noRelaxation = 1;
                        $noCasteMapping = 1;
			$this->showSaveDppAndCriteria=0;
			$this->hideSorting=1;
			$this->hideTextSearch=1;
			$hideFeatureProfile=1;
			//if($request->getParameter("matchalerts"))
				//$this->setTitle('Match Alerts - Jeevansathi.com');
			//else
				//$this->setTitle('Kundli Matches - Jeevansathi.com');
                }
                if($callingSource=='ap_eoi' || $callingSource=='ap' || $callingSource == "fto_offer" || $callingSource == "mailer_photo_request" || $callingSource == "sugar")
                {
			if($callingSource!="mailer_photo_request")
                        	$noAwaitingContacts=1;
                        $noRelaxation = 1;
                }
                $profileChecksumPassed = $request->getParameter("profileChecksum");
		$uri = $request->getUri();
		if($this->reverseDpp)
		{
			$noRelaxation = 1;
			$this->showSaveDppAndCriteria=0;
		}

		$this->searchId = $searchId;
		$this->pageName = "SearchPage";
		$this->isMobile=MobileCommon::isMobile("JS_MOBILE");

		if(!$this->isMobile)
			$this->isTablet = MobileCommon::isTabletMobile();

		if($this->isMobile || $this->isTablet)
			$mobileOrTablet=1;

		if($searchId)
			$noRelaxation = 1;
		$results_orAnd_cluster = ''; // '' => both clusters and results
		$searchResultsCountForAutoRelaxation = SearchConfig::$searchResultsCountForAutoRelaxation;
		//$limitClustersOptions = SearchConfig::$limitClustersOptions;

		if($searchId && $currentPage)
		{
			$SearchResultscacheObj = new SearchResultscache;
			$cachedSearch = $SearchResultscacheObj->get($searchId,$searchEngine);
		}


		/* Fetching Loggedin User Details (backend+cron)*/
		if($profileChecksumPassed)
		{
			$tempPid = JsAuthentication::jsDecryptProfilechecksum($profileChecksumPassed);
			if($tempPid)
				$loggedInProfileObj = Profile::getInstance('newjs_master',$tempPid);
			else
			{
				echo "Invalid ID";
				die;
			}
		}
		else
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			
		$this->profileOrExpressButton = $this->getProfileOrExpressButtonValue();		
		/* Fetching Details of logged-in profile */
		if($loggedInProfileObj->getPROFILEID()!='')
		{
			if($loggedInProfileObj->getAGE()=="")
				$loggedInProfileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
			$this->loggedInProfileid = $loggedInProfileObj->getPROFILEID();

			//Profile status incoplete/under screening
			if($loggedInProfileObj->getINCOMPLETE()=="Y")
				$this->profileStatus='I';
			else if($loggedInProfileObj->getACTIVATED()=="N" || $loggedInProfileObj->getACTIVATED()=="U" || $loggedInProfileObj->getACTIVATED()=="P")
				$this->profileStatus="U";


			$this->loggedIn = 1;
			$this->profileChecksum = JsAuthentication::jsEncryptProfilechecksum($this->loggedInProfileid);
			$JsAuthenticationObj = new JsAuthentication;
			$this->checksum = $JsAuthenticationObj->js_encrypt($this->loggedInProfileid);
			$this->havephoto = $loggedInProfileObj->getHAVEPHOTO();
			$this->PaidStatus='free';
			$this->loggedInGender = $loggedInProfileObj->getGENDER();
			
			//To be used for search eoi
			$this->loginProfile=$loggedInProfileObj;
			$state=str_split(strtolower($loggedInProfileObj->getPROFILE_STATE()->getFTOStates()->getSubState()));
			Messages::setUserChecksum(JsCommon::createChecksumForProfile($loggedInProfileObj->getPROFILEID()));
			$flag=$this->loginProfile->getPROFILE_STATE()->getFTOStates()->getAcceptanceFlag();
			if(in_array($state[0],array("c","d")))
				$this->FREE_TRIAL_OFFER=$state[0];
			if($loggedInProfileObj->getPROFILE_STATE()->getFTOStates()->getSubState()=="E4" && $flag=="T")
				$this->FREE_TRIAL_OFFER="d";
			if($loggedInProfileObj->getGENDER()=="M")
			{
						$this->himher='her';
						$this->heshe="she";
			}			
			else
			{
						$this->himher='him';
						$this->heshe="he";
			}	
			$subscr = $loggedInProfileObj->getSUBSCRIPTION();
			
			if(strstr($subscr,'R'))
				$this->featured='Y';
			if(strstr($subscr,'B'))
				$this->boldListing='B';

			if(PremiumMember::isDummyProfile($loggedInProfileObj->getPROFILEID()))
				$this->premiumDummyUser = 1;
			
			$this->draftsLogic($loggedInProfileObj);
			//$this->drafts=CommonUtility::fetchDrafts($loggedInProfileObj->getPROFILEID(),'N');
			
			if(CommonFunction::isPaid($loggedInProfileObj->getSUBSCRIPTION()))
			{
				//$this->drafts=ProfileDrafts::
				
				$this->drafts=CommonUtility::fetchDrafts($loggedInProfileObj->getPROFILEID(),'N');
				
				$this->PaidStatus='paid';
				
			}
			if(!(JsCommon::isContactVerified($loggedInProfileObj)))
			{
				$this->PH_UNVERIFIED_STATUS=1;
				if(CommonUtility::InvalidLimitReached($loggedInProfileObj))
					$this->SHOW_UNVERIFIED_LAYER=1;
			}
		}
		else
			$this->loggedIn = 0;

		//Pixel code to run only when coming from mobile registration page 4
		//if(strstr($uri,'partnermatches') && $this->loggedIn)
		if($partnermatches && $this->loggedIn)
                {
                        //If coming directly from registration, used for google pixel code
                        if (trim($request->getParameter('groupname'))) {
                                $this->pixelcode = PixelCode::fetchPixelcode($request->getParameter('groupname'), $request->getParameter('adnetwork1'),$this->loginProfile);
			    }
                }
		/** 
		* This Section will pass the request paratmeter to a intermediate layer which sets searchParamersObj based 
		on save-id / or direct search input
		*/
                $SearchParamtersObj = SearchParamtersLayer::setSearchParamters($request,$loggedInProfileObj);
                if($this->searchId && $SearchParamtersObj->getGENDER()=='')
		{	
			  //Search Id Expire Case
			  $this->forward("static","searchIdExpire");
		}
		if($this->reverseDpp || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::ReverseDpp)
		{
            		$this->reverseDpp = 1;
            		$this->searchBasedParam = "reverseDpp";
	//		$this->setTitle('Members looking for me - Jeevansathi.com');
		}
                elseif($partnermatches || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::Dpp)
		{
                        $this->partnermatchesPage = 1;		//TO BE USED IN HEADER TO BLACKEN THE MY MATCHES LINK
                        $this->searchBasedParam = "partnermatches";
	//		$this->setTitle('My Matches - Jeevansathi.com');
		}
                elseif($this->searchBasedParam == 'justJoinedMatches' || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::JustJoinedMatchesDesktop)
		{
                        $this->searchBasedParam == 'justJoinedMatches';	//TO BE USED IN HEADER TO BLACKEN THE MY MATCHES LINK
	//		$this->setTitle('Just Joined Matches - Jeevansathi.com');
                        $request->setParameter("justJoinedMatches",'1');
                        $this->searchBasedParam = 'justJoinedMatches';
		}
                elseif($this->twowaymatch || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::TwoWayMatch)
		{
	//		$this->setTitle('Mutual Matches - Jeevansathi.com');
			$gotItBandObj = new GotItBand($this->loggedInProfileid);
                        
			$this->showGotItBand = $gotItBandObj->showBand(GotItBand::$MATCH_2WAY,$loggedInProfileObj->getENTRY_DT());
			$this->GotItBandPage = GotItBand::$MATCH_2WAY;
			$this->GotItBandMessage = GotItBand::$educationMATCH_2WAY;
		}
                if($request->getParameter("justJoinedMatches")==1){
                        $this->hideSorting=1;
                        $noRelaxation = 1;
			$noCasteMapping = 1;
                        $this->showSaveDppAndCriteria=0;
                        $hideFeatureProfile = 1;
                }
                
                if(($this->searchBasedParam == 'justJoinedMatches' || $this->searchBasedParam == 'twowaymatch' || $partnermatches) && $loggedInProfileObj->getPROFILEID()){
                        $this->searchCriteriaText = "Your Desired Partner Profile";
                        $this->editDpp = 1;
                        if($request->getParameter("justJoinedMatches")==1){
						$profileId=$loggedInProfileObj->getPROFILEID();
						$mprofileMemcache=new ProfileMemcacheService($profileId);
						$tempJustJoined=$mprofileMemcache->memcache->getJUST_JOINED_MATCHES_NEW()*(-1);//print_r($tempJustJoined); 
						$mprofileMemcache->update('JUST_JOINED_MATCHES_NEW',$tempJustJoined); 
						$mprofileMemcache->updateMemcache();
			
			}
                }
                else{
                        $this->searchCriteriaText = "You searched for";
                        $this->editDpp = 0;
                }
                
                $this->_getGoogleRemarketingTags($SearchParamtersObj, $isearchCookie, $currentPage);
		
    		$this->stype = $SearchParamtersObj->getSEARCH_TYPE();
		$this->searchedGender = $SearchParamtersObj->getGENDER();

		$this->getNoOfResultsRequired($callingSource,$SearchParamtersObj,$profileList);
		if($this->premiumDummyUser)
		{
			$SearchParamtersObj->setNoOfResults(SearchConfig::$premium_dummy_user_search_count);
			$noAwaitingContacts=1;
		}

		if($this->searchedGender==$this->loggedInGender)
			$this->sameGenderSearch=1;

		if($SearchParamtersObj->getGENDER()=='M')
			$this->userGender = "He";
		else
			$this->userGender = "She";

		if($this->moreLinkCluster) /* When user click on more link of clusters */
		{
			$this->searchId = $request->getParameter("searchId");
			$clustersToShow = array($this->moreLinkCluster);
			$results_orAnd_cluster = 'onlyClusters';
			$noRelaxation = 1;$showAllClustersOptions = 1;$moreClusterSoring=1;
		}
		else 
		{
			$ClusterOrderingObj = new ClusterOrdering($SearchParamtersObj);
			$clustersToShow = $ClusterOrderingObj->getClusterOrdering($loggedInProfileObj,2,'',$mobileOrTablet);
			//$clustersToShow = array('VIEWED','LAST_ACTIVITY','RELATION','MSTATUS','HAVECHILD','HEIGHT','MTONGUE','RELIGION','CASTE','EDUCATION_GROUPING','INCOME','OCCUPATION_GROUPING','INDIA_NRI','STATE','CITY_INDIA','MANGLIK','DIET','HAVEPHOTO','HOROSCOPE','HANDICAPPED','HIV','AGE','GOING_ABROAD','MARRIED_WORKING','CASTE_GROUP');
		}
		$this->jsonClustersToShow = json_encode($clustersToShow);
		if($cachedSearch) 
		/* Fetch Results From cache */
		{
                	$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
                        $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,$cachedSearch,$loggedInProfileObj);
			$this->sort_logic = $SearchParamtersObj->getSORT_LOGIC();
		}
		else
		{
			/* remove profile*/
			// Added by Reshu for trac# 2850, now in search we need to exclude awaiting contacts
			$noAwaitingContacts = 1;

			$SearchUtilityObj =  new SearchUtility;
			$SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
			unset($SearchUtilityObj);

			/** 
			* SearchParamtersObj will be passed to the service class which will return the response object.
			* The response object is used to access the search Results Atrributes like : 
			  totalResults , clusters , resultsArr , results-ids
			*/
                	$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
			$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$this->sort_logic);
			$this->sort_logic = $SearchParamtersObj->getSORT_LOGIC();
                        
			if($currentPage>1)
			{
				$noCasteMapping = 1;
		                $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
			}
			else
	                	$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,'','',$loggedInProfileObj);
            //print_r($responseObj->getClustersResults());die;
			if($SearchParamtersObj->getONLINE()==SearchConfig::$onlineSearchFlag)	
			{
				$noRelaxation = 1;
				$noCasteMapping = 1;
				$hideFeatureProfile = 1;
			}
			
			/** Auto Relaxation Section
			* increasing search results by changing some search paramters
			*/

	                if($noRelaxation!=1 && $responseObj->getTotalResults() < $searchResultsCountForAutoRelaxation)
        	        {
                                $keyAuto = "autoRelaxedCount";
                                if(JsMemcache::getInstance()->get($keyAuto))
                                {
                                    $countVal = JsMemcache::getInstance()->get($keyAuto) + 1;
                                }else{
                                        $countVal = 1;
                                }
                                JsMemcache::getInstance()->set($keyAuto,$countVal);
				/*$this->relaxedResults = 1;
				$AutoRelaxationObj = new AutoRelaxation($SearchParamtersObj);
				$relaxCriteria = $AutoRelaxationObj->autoRelax($loggedInProfileObj);
				unset($responseObj);
                		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,'','',$loggedInProfileObj);*/

	                }

			/** 
			* Broadening results section
			* increasing search results by asking user to broaden them via caste mapping
			*/
			if(strpos($SearchParamtersObj->getCASTE(),",")===false && !$SearchParamtersObj->getCASTE_GROUP() && !$noCasteMapping)
			{
				if(!$this->relaxedResults && $responseObj->getTotalResults() <= SearchConfig::$searchResultsCountForBroadeningLimit)
				{
					if($responseObj->getTotalResults()<=SearchConfig::$searchResultsCountForBroadeningDivisionLimit)
						$this->showBroadeningBreadCrumbPosition = "up";
					else
						$this->showBroadeningBreadCrumbPosition = "down";
				    	$casteMappingCountObj = $SearchServiceObj->getCasteMappingCount($SearchParamtersObj,$loggedInProfileObj);
				    	if($casteMappingCountObj)
				    	$casteMappingCount = $casteMappingCountObj->getTotalResults() - $responseObj->getTotalResults();

					if($casteMappingCount > 0)
					{
						$casteSuggestObj = new CasteSuggest;
						$suggestedCaste = $casteSuggestObj->getSuggestedCastes($SearchParamtersObj->getCASTE(),2);
						$this->casteSuggestMessage = $casteSuggestObj->getMessage($SearchParamtersObj->getCASTE(),$suggestedCaste);
						$this->moreProfiles = $casteMappingCount;
						$this->showCasteMapping = 1;
						unset($suggestedCaste);
						unset($casteSuggestObj);
					}
				}
			}
		}

		$this->legacyHandlingAndCrons($callingSource,$responseObj);

		/**Save Search Criteria Section : */
		if($this->loggedIn)
		{
			$this->maxSaveSearchesAllowed = SearchConfig::$maxSaveSearchesAllowed;
			$savedSearches = $this->getSaveSearch($loggedInProfileObj);	
			if($savedSearches)
			{
				if(count($savedSearches)==SearchConfig::$maxSaveSearchesAllowed)
					$this->maxLimit = 1;
				else
					$this->maxLimit = 0;
				$this->savedSearches = $savedSearches;
				$this->savedSearchExists=1;
			}
			else
			{
				$this->savedSearchExists=0;
				$this->maxLimit = 0;
			}
		}
		else
		{
			$this->savedSearchExists=0;
			$this->maxLimit = 0;
		}


		/* Breadcrumb section */
		$breadCrumbObj = new BreadCrumb;
		$this->searchedParamsTextArr = $breadCrumbObj->getSearchParametersLabels($SearchParamtersObj,$searchEngine);
		$this->searchedParamsText = $breadCrumbObj->getSearchParametersLabelsText($this->isMobile);
		unset($breadCrumbObj);
		/* Breadcrumb section */


		/* Format Clusters as Required */
		if($responseObj->getTotalResults() > 0 && $results_orAnd_cluster!='onlyResults')
		{
			$this->clusterLabelMappingArray = searchConfig::clusterLabelMapping($SearchParamtersObj->getRELIGION());
			$this->searchClustersArray = $SearchServiceObj->getFormatedClusterResults($responseObj->getClustersResults(),$clustersToShow,$SearchParamtersObj,$moreClusterSoring);
		}

		$SearchUtilityObj =  new SearchUtility;
		$this->openClusters = $SearchUtilityObj->getListOfOpenClusters($SearchParamtersObj,$clustersToShow,$this->searchClustersArray);
		unset($SearchUtilityObj);
		/* Format Clusters as Required */

		if($this->showSaveDppAndCriteria!='0')
			$this->showSaveDppAndCriteria = $this->showSaveDppAndCriteria($SearchParamtersObj,$loggedInProfileObj,$responseObj);

		if(!$cachedSearch)
		{
			/* User Clicks On More Link on Clusters search Results Page */
			if($this->moreLinkCluster)
			{
				$this->clusterTitle = $this->clusterLabelMappingArray[$this->moreLinkCluster];
				if(!$this->clusterTitle)
					$this->clusterTitle = $this->clusterLabelMappingArray[$this->originalCluster]; 
				$this->setTemplate('moreClusterLayer');
				return;
			}
			$this->searchId = $this->logAndCacheSearchResults($loggedInProfileObj,$SearchParamtersObj,$responseObj,$noCache);
		}
		if(!$currentPage)
			$currentPage=1;
		$this->NAVIGATOR = navigation($this->szNavType,$this->searchId.":".$currentPage,'','Symfony');

		if($currentPage && $this->searchId)
			SearchService::trackingMis($currentPage,$this->searchId);

		/* feature profile */
		if(!$this->isMobile && !$hideFeatureProfile)
		{
			if($currentPage==1)
			{
				$featuredProfileObj = new FeaturedProfile($loggedInProfileObj);
				$featureProfileIdArr = $featuredProfileObj->getProfile("","",$this->searchId);
				$featureProfileId = $featureProfileIdArr["PROFILEID"];
				if(!$featureProfileId)
				{
					$featuredProfileObj->getFeaturedSearchCriteria($SearchParamtersObj);
					$SearchServiceObj->setSearchSortLogic($featuredProfileObj,$loggedInProfileObj,'FP');
					$respObj = $SearchServiceObj->performSearch($featuredProfileObj,"onlyResults",'','','',$loggedInProfileObj);
					if(count($respObj->getSearchResultsPidArr())==0)
					{
                                                JsMemcache::getInstance()->incrCount("FEATURE_PROFILE_RELAX_HITS");
						/*unset($featuredProfileObj);
						$featuredProfileObj = new FeaturedProfile($loggedInProfileObj);
						$featuredProfileObj->getFeaturedSearchCriteria($SearchParamtersObj,1);
						$SearchServiceObj->setSearchSortLogic($featuredProfileObj,$loggedInProfileObj,'FP');
						$respObj = $SearchServiceObj->performSearch($featuredProfileObj,"onlyResults",'','','',$loggedInProfileObj);
                                                 */
					}

					if(count($respObj->getSearchResultsPidArr())>0)
					{
						$featureProfileId = $featuredProfileObj->performDbAction($this->searchId,$respObj->getSearchResultsPidArr());
						if(count($respObj->getSearchResultsPidArr())>1)
                                                	$this->featurePosition = 'first';
                                        	else
                                                	$this->featurePosition = 'single';
						$this->featuredResultNo=0;
						$this->totalFeaturedProfiles = count($respObj->getSearchResultsPidArr());
					}
				}
				else
				{
					$SearchParamtersObjF = new SearchParamters;
					$SearchParamtersObjF->setProfilesToShow($featureProfileId);
					$SearchParamtersObjF->setGENDER('ALL');
					$respObj = $SearchServiceObj->performSearch($SearchParamtersObjF,'onlyResults','','',0,$loggedInProfileObj);
					$this->featurePosition = $featureProfileIdArr["POSITION"];
					$this->featuredResultNo=0;
					$this->totalFeaturedProfiles = $featureProfileIdArr["TOTAL"];
					unset($SearchParamtersObjF);
				}
				unset($featuredProfileObj);
			}
		}
		/* feature profile */

		if($responseObj->getTotalResults()==0)
		{
			$this->zeroResults = 1;
			$this->hideClusters = 1;
			$this->noOfResults = 0;
			if($lastUsedCluster)
			{
				if(!in_array($lastUsedCluster,searchConfig::$sliderClusters))
				{
					if($lastUsedCluster!='INCOME_DOL')
					{
						$this->lastUsedCluster = $lastUsedCluster;
						$this->lastUsedClusterValue = $SearchParamtersObj->{"get".$lastUsedCluster}();
					}
				}
			}
                        //no idea why added ?
			$SearchDisplayObj = new SearchDisplay($SearchParamtersObj);
			$resultsArray = $SearchDisplayObj->searchPageTemplateInfo($this->isMobile,$loggedInProfileObj,$responseObj,$this->searchId,$SearchParamtersObj->getSORT_LOGIC());
			$this->finalResultsArray = $resultsArray;
		}
		else
		{
			$this->paginationArr = CommonUtility::pagination($currentPage,$responseObj->getTotalResults(),$SearchParamtersObj);
			$this->currentPage = $currentPage;
			$this->noOfResults = $responseObj->getTotalResults();
			$this->noOfPages = max($this->paginationArr);
                        $resVal = (SearchCommonFunctions::getProfilesPerPageOnSearch($SearchParamtersObj))*$this->currentPage;
                        if($resVal > $this->noOfResults)
                        	$this->noOfResultsOnCurrentPage = $this->noOfResults-((SearchCommonFunctions::getProfilesPerPageOnSearch($SearchParamtersObj))*($this->currentPage - 1));
                        else
                        	$this->noOfResultsOnCurrentPage = $resVal-((SearchCommonFunctions::getProfilesPerPageOnSearch($SearchParamtersObj))*($this->currentPage - 1));
			/**
			* generate neccesary profile information to be displayed.
			*/
                        $SearchDisplayObj = new SearchDisplay($SearchParamtersObj);
			if($currentPage == 1)
				$resultsArray = $SearchDisplayObj->searchPageTemplateInfo($this->isMobile,$loggedInProfileObj,$responseObj,$this->searchId,$SearchParamtersObj->getSORT_LOGIC(),$respObj);
			else
				$resultsArray = $SearchDisplayObj->searchPageTemplateInfo($this->isMobile,$loggedInProfileObj,$responseObj,$this->searchId,$SearchParamtersObj->getSORT_LOGIC());
			$this->finalResultsArray = $resultsArray;
                        $this->fieldsDisplayedInSearchTuple = SearchConfig::$fieldsDisplayedInSearchTuple;
		}	
		$this->formatNumber_format = CommonUtility::moneyFormatIndia($this->noOfResults);
                $params["Version"]="V1";
		$params["Channel"]= "PC";
		$params["SearchType"]= $this->searchBasedParam;
		$params["Count"]=$this->noOfResults;
		$title = SearchTitleAndTextEnums::getTitle($params);
		$this->setTitle($title);
		$this->heading = SearchTitleAndTextEnums::getHeading($params);
		$this->subHeading = SearchTitleAndTextEnums::getSubHeading($params);
                if($request->getParameter("searchBasedParam")=='matchalerts'){
                        $profileID = $loggedInProfileObj->getPROFILEID();
                        $matchAlertObj = new MatchAlerts();
                        $subHeadingArr = $matchAlertObj->getMatchAlertHeading($profileID,$this->subHeading);
                        
                        $this->subHeading = $subHeadingArr["Heading"];
                        $this->subHeadingLinkText = $subHeadingArr["subHeading"];
                        $this->subHeadingLogic = $subHeadingArr["Logic"];
                }
		//Income Array
		if($this->zeroResults!=1)
		{
		$this->income_arr_rupee_html = $this->searchClustersArray["INCOME"]["income_arr_rupee_html"];
		$this->income_arr_rupee_mapping_html = $this->searchClustersArray["INCOME"]["income_arr_rupee_mapping_html"];
		$this->income_arr_dollar_html = $this->searchClustersArray["INCOME"]["income_arr_dollar_html"];
		$this->income_arr_dollar_mapping_html = $this->searchClustersArray["INCOME"]["income_arr_dollar_mapping_html"];
		unset($this->searchClustersArray["INCOME"]["income_arr_rupee_html"]);
		unset($this->searchClustersArray["INCOME"]["income_arr_rupee_mapping_html"]);
		unset($this->searchClustersArray["INCOME"]["income_arr_dollar_html"]);
		unset($this->searchClustersArray["INCOME"]["income_arr_dollar_mapping_html"]);
		}

		if($this->isMobile)
		{
			 //JSB9 Mobile Tracking
			$this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobSearchPageUrl);	
			$this->maxPages = ceil($this->noOfResults/SearchConfig::$profilesPerPage);  // wrong on live.
			$this->length = SearchCommonFunctions::getProfilesPerPageOnSearch($SearchParamtersObj);
			$this->setTemplate("mobile/mobileSearch");
		}
        }


        public function executeMatchAlertToggle(sfWebRequest $request)
	{
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                 $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
                
                //Logic Code setting
                if($request->getParameter("logic")=="dpp")
                        $newOrOld = MailerConfigVariables::$oldMatchAlertLogic;
                else
                        $newOrOld = MailerConfigVariables::$newMatchAlertLogic;
                
                $newjsMatchLogicObj->setNewOrOldLogic($loggedInProfileObj->getPROFILEID(),$newOrOld);
                
                //Redirecting to refrrer
                if(!$_SERVER["HTTP_REFERER"])
                        $refral="/search/matchalerts";
                else
                        $refral = $_SERVER["HTTP_REFERER"];
                
                $this->redirect($refral);
                
        }
	/*
	This action is for performing search for bandhan.com and doctors republic. It is the revamped code of profile/summary_profiles.php
	*/
	public function executeSummaryProfiles(sfWebRequest $request)
	{
		ini_set("max_execution_time",0);
		ini_set("memory_limit","512M");
		ini_set("mysql.connect_timeout",-1);
		ini_set("default_socket_timeout",259200); // 3 days
		ini_set("log_errors_max_len",0);

		if($request->getParameter("app_key")==SearchConfig::$doctorsrepublic_key)
		{
			$source = 1;
			SendMail::send_email("lavesh.rawat@gmail.com","Doctor Republic url called from /search/summaryProfiles","Doctor Republic called");
		}
		elseif($request->getParameter("app_key")==SearchConfig::$bandhan_key)
			$source = 2;
		else
			die;

		$bsObj = new BandhanSearch($source);
		if($request->getParameter("lastDay"))		//For keywords
			$bsObj->perfromSearch(1);
		elseif($request->getParameter("onlyUrls"))	//URL's of profiles of last 15 days
			$bsObj->perfromSearch(2);
		elseif($request->getParameter("oneDay"))
			$bsObj->perfromSearch(4);		//All data of profiles of last 1 day
		else
			$bsObj->perfromSearch(3);		//All data of profiles of last 15 days
		unset($bsObj);
		die;	
	}

  /**
   * Google Remarketing function
   *
   * <p>
   * This function generates tags for Google Remarketing on Date, Religion, Education & Occupation, Manglik, Marital Status, Residence, Caste, and Gender. The logic generating these tags is present in lib/model/lib/GoogleRemarketing.class.php 
   * The precondition to generate remarketing tags for search page is:
   * <ol>
   * <li>User should be logged out</li>
   * <li>Isearch Cookie should be unset</li>
   * <li>Remarketing tags should appear only on Search Results page #1</li>
   * </ol>
   * </p>
   * 
   * @access private
   * @param $isearchCookie Holds Isearch Cookie val.
   * @param $currentPage Holds the current page status.
   */
  private function _getGoogleRemarketingTags($SearchParamtersObj, $isearchCookie, $currentPage) {
  
     $this->GR_LOGGEDIN = $this->loggedIn;
     $this->GR_ISEARCH = $isearchCookie;
    if ($this->loggedIn === 0 && 
        !isset($isearchCookie) && 
        !isset($currentPage)
       ) 
    {
      $this->GR_PAGE = 1;

      $this->GR_DATE = date('Y-m-d');

      $this->GR_RELIGION = GoogleRemarketing::getReligionTag($SearchParamtersObj->getRELIGION());

      $this->GR_EDU_OCC = GoogleRemarketing::getEducationOccupationTag($SearchParamtersObj->getOCCUPATION(), $SearchParamtersObj->getEDU_LEVEL_NEW());

      $this->GR_MANGLIK = GoogleRemarketing::getManglikTag($SearchParamtersObj->getMANGLIK());

      $this->GR_MSTATUS = GoogleRemarketing::getMstatusTag($SearchParamtersObj->getMSTATUS());


      $cityInput = $SearchParamtersObj->getCITY_RES() ? $SearchParamtersObj->getCITY_RES() : $SearchParamtersObj->getCITY_INDIA();
      $countryInput = (strpos($SearchParamtersObj->getINDIA_NRI(), "2") !== false) ? "NRI" : $SearchParamtersObj->getCOUNTRY_RES();
      $this->GR_RESIDENCE = GoogleRemarketing::getResidenceTag($countryInput, $cityInput);


      $casteInput = $SearchParamtersObj->getCASTE() ? $SearchParamtersObj->getCASTE() : $SearchParamtersObj->getCASTE_GROUP();
      $this->GR_CASTE = GoogleRemarketing::getCasteTag($casteInput);


      $this->GR_GENDER = GoogleRemarketing::getGenderTag($SearchParamtersObj->getGENDER());

      $this->GR_MTONGUE = GoogleRemarketing::getMtongueTag($SearchParamtersObj->getMTONGUE());
    }
  } // Google Remarketing Ends

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
	* This function is used perform save search operation(ajax)
	*/
	public function executeSaveSearch(sfWebRequest $request)
	{
		$saveSearchName = trim($request->getParameter('saveSearchName'));
		$saveSearchId = $request->getParameter('saveSearchId');
		$searchId = $request->getParameter('searchId');

		if(!$saveSearchName && !$saveSearchId)
			die("No name");

                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');

                if($loggedInProfileObj->getPROFILEID()!='')
                {
			$loggedInProfileObj->getDetail("","","GENDER,PROFILEID");
			$savedSearches = $this->getSaveSearch($loggedInProfileObj);	//Get all the saved searches of the user	
			if($savedSearches && is_array($savedSearches))
			{
				if(count($savedSearches)==SearchConfig::$maxSaveSearchesAllowed)	//If the saved searches are 5
				{
					if(!$saveSearchId)						//If non replace case
						die("Maximum Count Reached.");
					else					//Replace case. If saveSearchId does not exist in the saved searches then die;
					{
						$flag = 0;
						foreach($savedSearches as $k=>$v)
						{
							if(in_array($saveSearchId,$v))
							{
								$flag = 1;
								break;
							}
						}
						if($flag==0)
							die("Maximum Count Reached.");
						unset($flag);
					}
				}
			}

			/* If blank is entered for the label in save search then to retain old search name , we need to get the exisiting search name of the saveSearchId.*/
			if($saveSearchId && !$saveSearchName)
			{
				if($savedSearches && is_array($savedSearches))
				{
					foreach($savedSearches as $k=>$v)
					{
						if(in_array($saveSearchId,$v))
                                             	{       
							$saveSearchName = $v["SEARCH_NAME"];
							break;
                                              	}
					}
				}
			}
			//DONE

			if(!$saveSearchName)		//At this stage if saveSearchName is not set then die
				die("save search cannot be blank");

			if($saveSearchId)		//If replacing case
			{
				foreach($savedSearches as $k=>$v)	//If saveSearchName exists but for a different id than the replacing one, then die
				{
					if(in_array($saveSearchName,$v) && !in_array($saveSearchId,$v))
						die("Search Name Same");
				}
			}
			else		//Non replacing case
			{
				foreach($savedSearches as $k=>$v)
                                {
                                        if(in_array($saveSearchName,$v))	//If saveSearchName already exists in the saved searches then die
                                                die("Search Name Same");
                                }
			}

                        $this->loggedInProfileid = $loggedInProfileObj->getPROFILEID();

               		$SearchParamtersLayer = new SearchParamtersLayer;
	                $SearchParamtersObj = $SearchParamtersLayer->setSearchParamters($request,$loggedInProfileObj);

			if($loggedInProfileObj->getGENDER()==$SearchParamtersObj->getGENDER())
                        	die("no success");

 	                $UserSavedSearches = new UserSavedSearches($loggedInProfileObj);

			$success = $UserSavedSearches->SaveSearch($SearchParamtersObj,$saveSearchName,$saveSearchId);	//Insert into database
			if($success == '0')		//If no row inserted then error
				die("Insert Error");
			
			$key=$loggedInProfileObj->getPROFILEID()."SAVESEARCH";

			/* If search is saved , remove the cache to build new cache */
                	if(JsMemcache::getInstance()->get($key))
                	{
                        	JsMemcache::getInstance()->set($key,"");
				$profileMemcacheObj=new ProfileMemcacheService($loggedInProfileObj->getPROFILEID());
				$profileMemcacheObj->clearInstance();
                	}
			die("success");
                }
		else
		{
			die("logout");
		}
	}

        /*
        * This function is used to save a search criteria as dpp(ajax)
        */
        public function executeSaveDpp(sfWebRequest $request)
        {
                $searchId = $request->getParameter('searchId');

                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');

                if($loggedInProfileObj->getPROFILEID()!='')
                {
			$loggedInProfileObj->getDetail("","","GENDER,PROFILEID,SUBSCRIPTION");
                        $this->loggedInProfileid = $loggedInProfileObj->getPROFILEID();

                        $SearchParamtersLayer = new SearchParamtersLayer;
                        $SearchParamtersObj = $SearchParamtersLayer->setSearchParamters($request,$loggedInProfileObj);
			if($loggedInProfileObj->getGENDER()==$SearchParamtersObj->getGENDER())
                        	die("no success");
	
			/* mapping groups to individual values*/
			if($SearchParamtersObj->getCASTE_GROUP()!='' && $SearchParamtersObj->getCASTE()=='')
			{
				$mapped[] = 'CASTE';
				$mappedArr = FieldMap::getFieldLabel("caste_group_array",1,1);
				$grp = $SearchParamtersObj->getCASTE_GROUP();
				$tempArr = explode(",",$grp);
				foreach($tempArr as $k=>$v)
					$tempArr2[] = $mappedArr[$v];
				$val = implode(",",$tempArr2);
				$SearchParamtersObj->setCASTE($val);
			}
			if($SearchParamtersObj->getEDUCATION_GROUPING()!='' && $SearchParamtersObj->getEDU_LEVEL_NEW()=='')
			{	
				$mapped[] = 'EDU_LEVEL_NEW';
				$mappedArr = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",1,1);
				$grp = $SearchParamtersObj->getEDUCATION_GROUPING();
				$tempArr = explode(",",$grp);
				foreach($tempArr as $k=>$v)
					$tempArr2[] = $mappedArr[$v];
				$val = implode(",",$tempArr2);
				$SearchParamtersObj->setEDU_LEVEL_NEW($val);
			}
			if($SearchParamtersObj->getOCCUPATION_GROUPING()!='' && $SearchParamtersObj->getOCCUPATION()=='')
			{	
				$mapped[] = 'OCCUPATION';
				$mappedArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",1,1);
				$grp = $SearchParamtersObj->getOCCUPATION_GROUPING();
				$tempArr = explode(",",$grp);
				foreach($tempArr as $k=>$v)
					$tempArr2[] = $mappedArr[$v];
				$val = implode(",",$tempArr2);
				$SearchParamtersObj->setOCCUPATION($val);
			}
			if($SearchParamtersObj->getCITY_INDIA()!='')
			{
				$val = $SearchParamtersObj->getCITY_INDIA();
				if(strstr($val,'METRO'))
				{
					$val = str_replace(',METRO,','',$val);
					$val = str_replace('METRO,','',$val);
					$val = str_replace(',METRO','',$val);
					$SearchParamtersObj->setCITY_INDIA($val);
				}
			}
			if($SearchParamtersObj->getSTATE()!='')
			{
				$val = $SearchParamtersObj->getSTATE();
				if(strstr($val,'NCR'))
				{
					$val = str_replace(',NCR,','',$val);
					$val = str_replace('NCR,','',$val);
					$val = str_replace(',NCR','',$val);
					$SearchParamtersObj->setSTATE($val);
				}
			}
			if($SearchParamtersObj->getLHEIGHT()=='0' && $SearchParamtersObj->getHHEIGHT()=='0')
			{
				$temp = FieldMap::getFieldLabel('min_height', '0');
				$SearchParamtersObj->setLHEIGHT($temp);
				$temp = FieldMap::getFieldLabel('max_height', '0');
				$SearchParamtersObj->setHHEIGHT($temp);
				unset($temp);
			}

			if($SearchParamtersObj->getLAGE()=='0' && $SearchParamtersObj->getHAGE()=='0')
			{
				if($loggedInProfileObj->getGENDER()=='M')
					$temp = 18;
				else
					$temp = 21;
				$SearchParamtersObj->setLAGE($temp);
				$temp = 70;
				$SearchParamtersObj->setHAGE($temp);
				unset($temp);
			}

			$india_nri = $SearchParamtersObj->getINDIA_NRI();
			$state =  $SearchParamtersObj->getSTATE();
			$city_india = $SearchParamtersObj->getCITY_INDIA();

			if($india_nri && !$SearchParamtersObj->getCOUNTRY_RES())
			{
				$countryArr = FieldMap::getFieldLabel('country','',1);
				if($india_nri == 1)
					$country_res = 51;
				else
				{
					foreach($countryArr as $k=>$v)
					{
						if($k!=51)
							$country_res.= $k.",";
					}
					$country_res = rtrim($country_res,",");
				}	
				$SearchParamtersObj->setCOUNTRY_RES($country_res);
				$mapped[] = 'COUNTRY_RES';
			}

			if($city_india && !$SearchParamtersObj->getCITY_RES())
			{
				$mapped[] = 'CITY_RES';
				$append='';
				if($city_india)
					$city_res.=$append.$city_india;
				$SearchParamtersObj->setCITY_RES($city_res);
			}

			foreach(PartnerProfile::$addNonFilledValuesAttributeArr as $kD => $vD)
			{
				eval ('$temp = $SearchParamtersObj->get'.$vD.'();');
				if($temp)
				{
					$updateMe=0;
					$tempArr = explode(",",$temp);
					foreach($tempArr as $kk=>$vv)
					{
						if($vv==SearchConfig::_nullValueAttributeLabel)
						{
							$updateMe=1;
							unset($tempArr[$kk]);
						}
					}
					if($updateMe)
					{
						$newVal = implode(",",$tempArr);
						eval ('$SearchParamtersObj->set'.$vD.'($newVal);');
					}
				}
			}
                        
                        //setting state and city from memcache which user has selected
                        $memObject=JsMemcache::getInstance();
                        if($stateToSet = $memObject->get('stateToSet-'.$searchId))
                            $SearchParamtersObj->setSTATE($stateToSet,'',1);
                        if($cityToSet = $memObject->get('cityToSet-'.$searchId))
                            $SearchParamtersObj->setCITY_RES($cityToSet,'',1);
			/* mapping groups to individual values*/
			
			/*if(strstr($loggedInProfileObj->getSUBSCRIPTION(),"T"))
			{
				$apObj = new SaveDppForAP;
				$success = $apObj->SaveDppFromSearch($SearchParamtersObj,$loggedInProfileObj->getPROFILEID());
				unset($apObj);
			}
			else
			{*/			
				$UserSavedSearches = new PartnerProfile($loggedInProfileObj);
				$success = $UserSavedSearches->saveSearchAsDpp($SearchParamtersObj,$mapped);
			//}
			if(MobileCommon::isDesktop())
			{
				if($success)
				{
					$this->done=true;
					$isDummy = PremiumMember::isDummyProfile($loggedInProfileObj->getPROFILEID());
					if($isDummy)
					{
						 $dummyDppKeywordsObj = new DummyDppKeywords($loggedInProfileObj->getPROFILEID());
						 $dummyDppKeywordsObj->setDummyDPPKeywords($SearchParamtersObj);
					}
				}
				else
					$this->done=false;
				$respObj = ApiResponseHandler::getInstance();
				$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$respObj->setResponseBody(array("done"=>$this->done));
				$respObj->generateResponse();
				return sfView::NONE;
				die;
			}
			if($success)
				die("success");
			else
				die("no success");
		}
                else
                {
                        if(MobileCommon::isDesktop())
                        {
				$this->done=false;
				$respObj = ApiResponseHandler::getInstance();
				$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$respObj->setResponseBody(array("done"=>$this->done));
				$respObj->generateResponse();
                                return sfView::NONE;
                                die;
                        }
                        die("logout");
                }
        }

	/*
	This function performs the action on clicking the next and prev buttons of feature profile tuple.
	*/
	public function executeFeaturedAction(sfWebRequest $request)
	{
		navigation("SR","","");
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($loggedInProfileObj->getPROFILEID() != '')
			$loggedIn = 1;
		else
			$loggedIn = 0;
		$action = $request->getParameter("actionType");
		$profilechecksum = $request->getParameter("profileChecksum");
		$searchId = $request->getParameter("searchId");
		$featuredResultNo = $request->getParameter("featuredResultNo");
		$profileId = JsAuthentication::jsDecryptProfilechecksum($profilechecksum);
		if($profileId)
		{
			$featuredProfileObj = new FeaturedProfile($loggedInProfileObj);
			$featureProfileIdArr = $featuredProfileObj->getProfile($action,$profileId,$searchId);
			$featureProfileId = $featureProfileIdArr["PROFILEID"];
			$totalFeaturedProfiles = $featureProfileIdArr["TOTAL"];

			if($featureProfileId)
			{
				/*lavesh - a change in this section has to be copied at another section close to line 335 as well*/
				$searchEngine = 'solr';
				$outputFormat = 'array';
				$results_orAnd_cluster ='onlyResults';
				$SearchParamtersObj = new SearchParamters;
				$SearchParamtersObj->setProfilesToShow($featureProfileId);
				$SearchParamtersObj->setGENDER('ALL');
				$SearchServiceObj = new SearchService($searchEngine,$outputFormat);
				$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,'','',$cachedSearch,$loggedInProfileObj);
				/*lavesh*/
				$SearchDisplayObj = new SearchDisplay($SearchParamtersObj);
				$resultsArray = $SearchDisplayObj->featuredProfileTemplateInfo($loggedInProfileObj,$this->isMobile,$responseObj,$SearchParamtersObj->getSORT_LOGIC(),$featureProfileId);

				$profileOrExpressButton = $this->getProfileOrExpressButtonValue();

				$NAVIGATOR = navigation("SR",$searchId.":".$currentPage,'','Symfony');

				echo $this->getPartial('searchTuple',array('detailsArr'=>$resultsArray[$featureProfileId],'profileid'=>$featureProfileId,'resultNumber'=>'1','fieldsDisplayedInSearchTuple'=>SearchConfig::$fieldsDisplayedInSearchTuple,'loggedIn'=>$loggedIn,'featurePosition'=>$featureProfileIdArr['POSITION'],'profileOrExpressButton'=>$profileOrExpressButton,'userGender'=>$userGender,'featuredResultNo'=>$featuredResultNo,'searchId'=>$searchId,'currentPage'=>$currentPage,'NAVIGATOR'=>$NAVIGATOR,'totalFeaturedProfiles'=>$totalFeaturedProfiles));

			}
			unset($featuredProfileObj);
		}
		die;
	}

        public function executeMoreClusterLayer(sfWebRequest $request)
        {
		//die("done");
        }

	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}

	function legacyHandlingAndCrons($callingSource,$responseObj)
	{
		/*
		Values for callingSource
		sms = classes/SMSLib.class.php
		ap = jsadmin/ap_dpp_common.php
		myjs = profile/extraDetails_MT.php
		ap_eoi = profile/ap_send_eoi.php
		*/

		if($callingSource=="sms")		
		//For SMS Cron classes/SMSLib.class.php
		{
			if($this->reverseDpp)
				$key = "SMS_MEM_LOOK_ME";
			else
				$key = "SMS_MEM_LOOK";
			JsMemcache::getInstance()->set($key,$responseObj->getTotalResults(),60);
			die;
		}
		elseif($callingSource=="ap" || $callingSource=="myjs")		
		//For jsadmin/ap_dpp_common.php and profile/extraDetails_MT.php
		{
			echo $responseObj->getTotalResults();
			die;
		}
		elseif($callingSource=="ap_eoi" || $callingSource=="fto_offer" || $callingSource=="mailer_photo_upload" || $callingSource=="mailer_photo_request" || $callingSource=="sugar")	
		//For ap_send_eoi.php
		{
			$output = "";
			if($responseObj->getTotalResults())
			{
				$output = implode(",",$responseObj->getSearchResultsPidArr());
				//if($callingSource!="ap_eoi")
					$output = $responseObj->getTotalResults()."#".$output;
			}
			echo $output;
			die;
		}
	}

	/** 
	* save dpp / search link on search results page will be displayed.
	* @return 1 if need to displayed.
	*/
	function showSaveDppAndCriteria($SearchParamtersObj,$loggedInProfileObj,$responseObj)
	{
		$showSaveDppAndCriteria = 1;
		if($SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::ReverseDpp || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::TwoWayMatch || $SearchParamtersObj->getSEARCH_TYPE()==SearchTypesEnums::Dpp || ($this->loggedInProfileid && $SearchParamtersObj->getGENDER()==$loggedInProfileObj->getGENDER()) || $responseObj->getTotalResults()==0)
			$showSaveDppAndCriteria = 0;
		return $showSaveDppAndCriteria;
	}

	/*
	* This Function is used to log search results table and cache information into memcache/table useful for next-previus handling.
	*/
	function logAndCacheSearchResults($loggedInProfileObj,$SearchParamtersObj,$responseObj,$noCache)
	{
		$SearchLoggerObj = new SearchLogger($loggedInProfileObj);
		$searchId = $SearchLoggerObj->logSearchCriteria($SearchParamtersObj,$responseObj->getTotalResults());
		if($responseObj->getTotalResults()>0 && !$noCache)
		{
			$searchResultscache = new SearchResultscache;
			if(is_array($responseObj->getSearchResultsPidArr()) && is_array($responseObj->getFeturedProfileArr()))
                        	$searchPidArr = array_diff($responseObj->getSearchResultsPidArr(),$responseObj->getFeturedProfileArr());
			else
                                $searchPidArr = $responseObj->getSearchResultsPidArr();
			$searchResultscache->add($searchId,$responseObj->getUrlToSave(),$searchPidArr);	
		}
		return $searchId;
	}

	/*
	* This function returns the save searches of a user
	*/
	private function getSaveSearch($loggedInProfileObj)
	{
		$key=$loggedInProfileObj->getPROFILEID()."SAVESEARCH";
		$savedSearchesTemp = unserialize(JsMemcache::getInstance()->get($key));
		if($savedSearchesTemp && is_array($savedSearchesTemp[0]))
		{
			foreach($savedSearchesTemp[0] as $k=>$v)
			{
				$savedSearches[$k]["SEARCH_NAME"] = $v;
				$savedSearches[$k]["ID"] = $savedSearchesTemp[1][$k];
			}
			unset($savedSearchesTemp);
		}
		else
		{
			$userSavedSearchesObj = new UserSavedSearches($loggedInProfileObj);
			$savedSearches = $userSavedSearchesObj->getSavedSearches();
			if($savedSearches && is_array($savedSearches))
			{
				foreach($savedSearches as $k=>$v)
				{
					$arr1[] = $v["SEARCH_NAME"];
					$arr2[] = $v["ID"];
				}
				$memcache_data[0]=$arr1;
				$memcache_data[1]=$arr2;
				JsMemcache::getInstance()->set($key,serialize($memcache_data),3600);
				unset($arr1);
				unset($arr2);
			}
             	}
		return $savedSearches;
	}

	/*
	* This function is used to perform the action when a user clicks on My Saved Searches
	*/
	public function executeDisplayMySaveSearch(sfWebRequest $request)
	{
		$frmSearch = $request->getParameter("frmSearch");
		$list_save = $request->getParameter("list_save");
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
		{
			$savedSearches = $this->getSaveSearch($loggedInProfileObj);	
			if($list_save)
			{
				$temp="";
				if($savedSearches && is_array($savedSearches))
                                {
					foreach($savedSearches as $k=>$v)
						$temp = $temp.$v["SEARCH_NAME"]."#".$v["ID"]."$";
					$temp = rtrim($temp,"$");
				}
				if($temp)
				{
					if($frmSearch)
						$temp = "SearchPage**".$temp;
				}
				else
				{
					if($frmSearch)
						$temp = "zeroSearch";
					else
						$temp = "zero";
				}
				echo $temp;
				die;
			}
			else
				return $savedSearches;
		}
		else
		{
			if($frmSearch)
				echo "logoutSearch";
			else
				echo "logout";
			die;
		}
	}

	/*
	This functions sets the number of results to be returned from the search performed
	*/
	private function getNoOfResultsRequired($callingSource,$SearchParamtersObj,$profileList='')
	{
		if($callingSource == "ap_eoi")
			$SearchParamtersObj->setNoOfResults(SearchConfig::$ap_send_eoi_count);
                elseif($callingSource == "fto_offer")
			$SearchParamtersObj->setNoOfResults(FTOLiveFlags::SuggestedMatchNoOfResults);
		elseif($callingSource == "mailer_photo_upload")		//For mailers sent after photo is screened
			$SearchParamtersObj->setNoOfResults(5);
		elseif($callingSource == "sugar")		//For sugar crm
			$SearchParamtersObj->setNoOfResults(4);
		elseif($callingSource == "mailer_photo_request")
		{
			$profileListArr = explode(",",$profileList);
			$SearchParamtersObj->setNoOfResults(1);
			$SearchParamtersObj->setProfilesToShow(implode(" ",$profileListArr));
			$SearchParamtersObj->setGENDER('ALL');
		}
	}

	/*
	This function acts as the action to populate the top search band data
	@return - XML data
	*/
	public function executeTopSearchBand(sfWebRequest $request)
	{ 
		if($request->getParameter("isMobile")!="Y")
		{
			$offset=60*60*1;//time to be cached:1 hrs
			header("Cache-Control: public,max-age=$offset,s-maxage=$offset");
		}

		if($request->getParameter("bigBand"))
			$parameters["BIGBAND"] = $request->getParameter("bigBand");
		if($request->getParameter("searchId"))
			$parameters["SEARCHID"] = $request->getParameter("searchId");
		if($request->getParameter("newfooter"))
		{
			$parameters["SEO"] = 1;
			$parameters["SEO_FIELD"] = $request->getParameter("field");
			$parameters["SEO_VALUE"] = $request->getParameter("value");
		}

		$topSearchBandObj = new TopSearchBandPopulate($parameters);
		if($request->getParameter("isMobile")=="Y")
		{ 
			MobileCommon::forwardmobilesite($this);
			$this->dataArray = $topSearchBandObj->generateDataArray();
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                	if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
				$this->loggedIn = 1;
			else
				$this->loggedIn = 0;
                        
                        if($this->loggedIn)
                                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobTopSearchBandPageUrl);
                        else
                                $this->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobTopSearchBandPageLogOutUrl);
                
			$seoLinks = JsCommon::SeoFooter();
			$seoLinks = $seoLinks["MTONGUE"];
			foreach($seoLinks as $k=>$v)
			{
				$seoLinks[$k] = $seoLinks[$k]["N"];
				unset($seoLinks[$k][2]);
			}
			$this->seoLinks = $seoLinks;
                        $this->setTemplate("mobileSearchBand");
		}
		else
		{ 
			$output = $topSearchBandObj->generateXML();
			header('content-type: text/xml');
			echo $output;
			die;	
		}
		unset($topSearchBandObj);
	}

	/**
	* Mobile Api version 1.0 action class
	*/
	public function executePerformV1(sfWebRequest $request)
	{
		
		//sleep(10);
		$showAllClustersOptions=1;
		$inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
		$inputValidateObj = ValidateInputFactory::getModuleObject('search');
		$inputValidateObj->validateAppSearchForm($request);
		$resp = $inputValidateObj->getResponse();
    $featuredProfile=1;
		//print_r($request->getParameterHolder()->getAll());
	
		/** Desktop loggedout case **/	
		if(MobileCommon::isDesktop())
		{       
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');			
        	        if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()=='')
			{
				if(($request->getParameter("justJoinedMatches")==1 || $request->getParameter("twowaymatch")==1 || $request->getParameter("reverseDpp")==1 || $request->getParameter("partnermatches")==1 || $request->getParameter("contactViewAttempts")==1 || $request->getParameter("lastSearchResults")==1 || $request->getParameter("matchofday")==1 || in_array($request->getParameter("searchBasedParam"),array('shortlisted','visitors','justJoinedMatches','twowaymatch','reverseDpp','partnermatches','matchalerts','kundlialerts','contactViewAttempts','lastSearchResults','matchofday')) || $request->getParameter("dashboard")==1))
				{
					$statusArr = ResponseHandlerConfig::$LOGOUT_PROFILE;
					$respObj = ApiResponseHandler::getInstance();
					$respObj->setHttpArray($statusArr);//print_r($resultArr);die;
					$respObj->setResponseBody($resultArr);
					$respObj->generateResponse();
					if($request->getParameter("useSfViewNone"))
						return sfView::NONE;
					die;
				}
			}
		}
                
		/** caching **/
		$ifApiCached = SearchUtility::cachedSearchApi('get',$request);
		if($ifApiCached)
		{
			$statusArr = $ifApiCached["statusArr"];
			$resultArr = $ifApiCached["resultArr"];
			$date = date("Y-m-d");
                        //file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/ifApiCached".$date.".txt","\n",FILE_APPEND);
		}
                elseif($resp["statusCode"] == ResponseHandlerConfig::$SUCCESS["statusCode"])
		{
                        $searchTypeArray = Array('twowaymatch','reverseDpp','justJoinedMatches','partnermatches','matchalerts','kundlialerts','contactViewAttempts','verifiedMatches','lastSearchResults','matchofday');
                        $searchType = $request->getParameter("searchBasedParam");
                        if(in_array($searchType,$searchTypeArray))
                        {
                                $request->setParameter($searchType,1);
                                $this->searchBasedParam = $searchType;
                        }
                        if($request->getParameter("newTagJustJoinDate"))
                        {
                                $request->setParameter("newTagJustJoinDate",$request->getParameter("newTagJustJoinDate"));
                        }
			if($searchType=='matchalerts')
				$sort_logic=SearchSortTypesEnums::matchAlertFlag;
			$searchEngine = 'solr';
			$outputFormat = 'array';
			$searchId = $request->getParameter("searchId");
			$lastUsedCluster = $request->getParameter("NEWSEARCH_CLUSTERING");
			$results_orAnd_cluster = $request->getParameter("results_orAnd_cluster");
			$currentPage = $request->getParameter("currentPage");
                        $noRelaxation = $request->getParameter("noRelaxation");
			$this->searchId = $searchId;
			if(!$sort_logic)
	    			$sort_logic = $request->getParameter("sort_logic");
			if($searchId)
				$noRelaxation = 1;
			$searchResultsCountForAutoRelaxation = SearchConfig::$searchResultsCountForAutoRelaxation;
                        
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                        $this->premiumDummyUser = 0;
			if($loggedInProfileObj->getPROFILEID()!='' && PremiumMember::isDummyProfile($loggedInProfileObj->getPROFILEID()))
				$this->premiumDummyUser = 1;
                        
			if($loggedInProfileObj->getPROFILEID()!='')
			{
				if($loggedInProfileObj->getAGE()=="")
					$loggedInProfileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
				$this->loggedInProfileid = $loggedInProfileObj->getPROFILEID();
				$this->loggedInGender = $loggedInProfileObj->getGENDER();
			}
			else
				$this->loggedIn = 0;
      
      $bNoFlushMatchAlertCount = $request->getParameter("noFlushMatchAlertCount") === 1 ? true : false;
      $bNoFlushMatchAlertCount = !$bNoFlushMatchAlertCount && 1 === $request->getAttribute("noFlushMatchAlertCount") ? true : false;
      //Flush Match Alert count in profile mecache service
      if($this->searchBasedParam == "matchalerts" && false === $bNoFlushMatchAlertCount) {
        $request->setAttribute("resetMatchAlertCount",1);
      }		
      if($searchId && $currentPage)
			{
				
				$SearchResultscacheObj = new SearchResultscache;
				$cachedSearch = $SearchResultscacheObj->get($searchId,$searchEngine);
			}
			$SearchParamtersObj = SearchParamtersLayer::setSearchParamters($request,$loggedInProfileObj,'AppSearch');
			if($this->searchId && $SearchParamtersObj->getGENDER()=='')
				$statusArr = ResponseHandlerConfig::$SEARCH_EXPIRED_SEARCHID;
			else
			{
				$this->stype = $SearchParamtersObj->getSEARCH_TYPE();				
				$this->searchedGender = $SearchParamtersObj->getGENDER();
				$this->getNoOfResultsRequired($callingSource,$SearchParamtersObj,$profileList);

                                
                                $noOfResult = $this->SearchChannelObj->getNoOfResults();
                                
                                // Setting No. Of profiles per Page for different channel
                                $SearchParamtersObj->setNoOfResults($noOfResult);
                                

				$ClusterOrderingObj = new ClusterOrdering($SearchParamtersObj);
				$clustersToShow = $ClusterOrderingObj->getClusterOrdering($loggedInProfileObj,2,'',$mobileOrTablet);
				//$clustersToShow = array('RELIGION','DIET','CASTE');
				//$clustersToShow = array('MTONGUE');
                                
                                $param["request"] = $request;
                                $clustersToShow = $this->SearchChannelObj->eduAndOccClusters($clustersToShow,$param);
				

				if($cachedSearch) 
				{
					$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
					$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,$cachedSearch,$loggedInProfileObj);
				}
				else
				{
					$SearchUtilityObj =  new SearchUtility;
					if($loggedInProfileObj->getACTIVATED() == "N") //CHANGE THIS TO "N"
					{
						$tempContacts = 1;						
					}
					else
					{
						$tempContacts = 0;
					}
					/* remove profile*/ 
					$noAwaitingContacts = 1;					
					$SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts,"","","","",$tempContacts);
					unset($SearchUtilityObj);

					/** 
					* SearchParamtersObj will be passed to the service class which will return the response object.
					* The response object is used to access the search Results Atrributes like : 
					  totalResults , clusters , resultsArr , results-ids
					*/
					$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
					$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$sort_logic);
					if($currentPage>1)
					{
						$noCasteMapping = 1;
						$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
					}
					else
					{
						$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,'','',$loggedInProfileObj);
					}
		
					if($SearchParamtersObj->getONLINE()==SearchConfig::$onlineSearchFlag)	
					{
						$noRelaxation = 1;
						$noCasteMapping = 1;
						$hideFeatureProfile = 1;
					}
					if($request->getParameter("justJoinedMatches")==1 || $request->getParameter("partnermatches")==1 || $request->getParameter("reverseDpp")==1 || $request->getParameter("twowaymatch")==1 || $request->getParameter("verifiedMatches") == 1 || $request->getParameter("contactViewAttempts") == 1 || $request->getParameter("searchBasedParam") == "matchalerts" || $request->getParameter("matchofday")==1)
					{
						$noRelaxation = 1;
						$noCasteMapping = 1;
						if($request->getParameter("justJoinedMatches")==1){
						$profileId=$loggedInProfileObj->getPROFILEID();
						$mprofileMemcache=new ProfileMemcacheService($profileId);
						$tempJustJoined=$mprofileMemcache->memcache->getJUST_JOINED_MATCHES_NEW()*(-1);//print_r($tempJustJoined); 
						$mprofileMemcache->update('JUST_JOINED_MATCHES_NEW',$tempJustJoined); 
						$mprofileMemcache->updateMemcache();
			
						}
					}
					if($request->getParameter("kundlialerts") == 1 || $request->getParameter("searchBasedParam")=='kundlialerts'){
						$results_orAnd_cluster = "onlyResults";
						$noRelaxation = 1;
						$noCasteMapping = 1;
					}
					if($request->getParameter("lastSearchResults") == 1 || $request->getParameter("searchBasedParam")=='lastSearchResults')
					{						
						$noRelaxation = 1;
						$noCasteMapping = 1;
					}
					if(JsConstants::$hideUnimportantFeatureAtPeakLoad >=5)
					{
						$noRelaxation = 1;
						$noCasteMapping = 1;
					}
					/** Auto Relaxation Section
					* increasing search results by changing some search paramters
					*/
					
					if($noRelaxation!=1 && $responseObj->getTotalResults() < $searchResultsCountForAutoRelaxation)
					{ 
                                                $keyAuto = "autoRelaxedCount";
                                                if(JsMemcache::getInstance()->get($keyAuto))
                                                {
                                                    $countVal = JsMemcache::getInstance()->get($keyAuto) + 1;
                                                }else{
                                                        $countVal = 1;
                                                }
                                                JsMemcache::getInstance()->set($keyAuto,$countVal);
						/*$this->relaxedResults = 1;
                                                $AutoRelaxationObj = new AutoRelaxation($SearchParamtersObj);
						$relaxCriteria = $AutoRelaxationObj->autoRelax($loggedInProfileObj);
						unset($responseObj);
						$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,'','',$loggedInProfileObj);*/

					}
					
					/** 
					* Broadening results section
					* increasing search results by asking user to broaden them via caste mapping
					*/
					if(strpos($SearchParamtersObj->getCASTE(),",")===false && !$SearchParamtersObj->getCASTE_GROUP() && !$noCasteMapping)
					{
						if(!$this->relaxedResults && $responseObj->getTotalResults() <= SearchConfig::$searchResultsCountForBroadeningLimit)
						{
							if($responseObj->getTotalResults()<=SearchConfig::$searchResultsCountForBroadeningDivisionLimit)
								$this->showBroadeningBreadCrumbPosition = "up";
							else
								$this->showBroadeningBreadCrumbPosition = "down";
							$casteMappingCountObj = $SearchServiceObj->getCasteMappingCount($SearchParamtersObj,$loggedInProfileObj);
							if($casteMappingCountObj)
							$casteMappingCount = $casteMappingCountObj->getTotalResults() - $responseObj->getTotalResults();

							if($casteMappingCount > 0)
							{
								$casteSuggestObj = new CasteSuggest;
								$suggestedCaste = $casteSuggestObj->getSuggestedCastes($SearchParamtersObj->getCASTE(),2);
								$this->casteSuggestMessage = $casteSuggestObj->getMessage($SearchParamtersObj->getCASTE(),$suggestedCaste);
								$this->moreProfiles = $casteMappingCount;
								$this->showCasteMapping = 1;
								unset($suggestedCaste);
								unset($casteSuggestObj);
							}
						}
					}
				}
                                $beforeFeaturedResonseObj=$responseObj;
                                if(!$currentPage)
                                        $currentPageFeatured=1;
                                else
                                        $currentPageFeatured = $currentPage;
       
				if($request->getParameter("justJoinedMatches")==1 || $request->getParameter("matchalerts")==1 || $request->getParameter("verifiedMatches")==1 || $request->getParameter("kundlialerts")==1 || $request->getParameter("contactViewAttempts")==1 || $request->getParameter("matchofday")==1 || in_array($request->getParameter("searchBasedParam"),array('justJoinedMatches','matchalerts','kundlialerts','contactViewAttempts','verifiedMatches','matchofday')))
				;
				else{
					if(JsConstants::$hideUnimportantFeatureAtPeakLoad >=7)
					{
						$request->setParameter("showFeaturedProfiles",0);
					}
					else
						$request->setParameter("showFeaturedProfiles",$this->SearchChannelObj->getFeaturedProfilesCount());
				}
				
				if(!$cachedSearch && !$request->getParameter("myjs"))
				{
					$this->searchId = $this->logAndCacheSearchResults($loggedInProfileObj,$SearchParamtersObj,$beforeFeaturedResonseObj,$noCache);
				}
							
				if($request->getParameter("showFeaturedProfiles") && $request->getParameter("showFeaturedProfiles")>0)
								$responseObj = $this->SearchChannelObj->showFeaturedProfile($featuredProfile,$currentPageFeatured,$loggedInProfileObj,$SearchParamtersObj,$responseObj,$SearchServiceObj,$request->getParameter("showFeaturedProfiles"),$this->searchId,$this);
				/* Format Clusters as Required */
				if($responseObj->getTotalResults() > 0 && $results_orAnd_cluster!='onlyResults')
				{
					$this->clusterLabelMappingArray = searchConfig::clusterLabelMapping($SearchParamtersObj->getRELIGION());
					$this->searchClustersArray = $SearchServiceObj->getFormatedClusterResultsApi($responseObj->getClustersResults(),$clustersToShow,$SearchParamtersObj,$moreClusterSoring);
				}

				$SearchUtilityObj =  new SearchUtility;
				$this->openClusters = $SearchUtilityObj->getListOfOpenClusters($SearchParamtersObj,$clustersToShow,$this->searchClustersArray);
				unset($SearchUtilityObj);
				/* Format Clusters as Required */

				
				if(!$currentPage)
					$currentPage=1;
                                if($currentPage && $this->searchId)
					SearchService::trackingMis($currentPage,$this->searchId);

				$this->paginationArr = CommonUtility::pagination($currentPage,$responseObj->getTotalResults(),$SearchParamtersObj);
				$this->currentPage = $currentPage;
				$this->noOfResults = $responseObj->getTotalResults();
				
				$this->noOfPages = max($this->paginationArr);
                                if(!$relaxCriteria)
                                        $relaxCriteria="";
                                
                                
                                $SearchApiStrategy = SearchApiStrategyFactory::getApiStrategy('V1',$responseObj,$results_orAnd_cluster);
                                $resultArr = $SearchApiStrategy->convertResponseToApiFormat($loggedInProfileObj,$this->searchClustersArray,$this->searchId,$SearchParamtersObj,$this->relaxedResults,$this->moreProfiles,$this->casteSuggestMessage,$currentPage,$this->noOfPages,$request,$relaxCriteria);
				
				if($resultArr["no_of_results"]==0)
				{
                                        $statusArr = $this->SearchChannelObj->searchZeroResultMessage();
                                        if($request->getParameter("myjs") && $this->SearchChannelObj->getChannelType()=="APP")
                                        {
											$statusArr["statusCode"]='0'; // For App Myjs it is not error case
										}
				}
				else
				{
					$resultArr["paginationArray"]= $this->paginationArr; 
					$statusArr = $inputValidateObj->getResponse();
				}

			}
			
			/** caching **/
			$ifApiCached = SearchUtility::cachedSearchApi('set',$request,'',$statusArr,$resultArr);

			/** caching **/

			$resultArr["searchIdForNavigation"]= $this->searchId;

		}
		else
		{
			//validation are logged in search validation.
			$statusArr = $resp;
		}   


        		unset($inputValidateObj);
                $respObj = ApiResponseHandler::getInstance();
                $respObj->setHttpArray($statusArr);//print_r($resultArr);
                $respObj->setResponseBody($resultArr);
                $respObj->generateResponse();    
                      
		if($request->getParameter("useSfViewNone"))
			return sfView::NONE;
		die;
        }


	/**
	* This function will set the search band defaults values to the dpp.
	*/
	public function executePopulateDefaultValuesV1(sfWebRequest $request)
	{
                $app54 = $request->getParameter("app54");
                $inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
                $inputValidateObj = ValidateInputFactory::getModuleObject('search');
                $inputValidateObj->validatePopulateDefaultValues($request);
                $resp = $inputValidateObj->getResponse();

                if($resp["code"] == ResponseHandlerConfig::$SUCCESS["code"])
                {
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			{
                                
                                if(isset($app54) && $app54 == 1){
                                        $parameters["SETMULTIPLE"] = 'Y';
                                        $parameters["app54"] = 1;
                                }
				
                                $TopSearchBandPopulate =  new TopSearchBandPopulate($parameters);
				$resultArr = $TopSearchBandPopulate->populateSelectedValuesForApp();
			}
		}
		$statusArr = $inputValidateObj->getResponse();
		unset($inputValidateObj);
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray($statusArr);
		$respObj->setResponseBody($resultArr);
		$respObj->generateResponse();
		return sfView::NONE;	
		die;
	}


	/**
	* This function will set the search band defaults values to the dpp.
	*/
	public function executePopulateDefaultValuesV2(sfWebRequest $request)
	{
                $inputValidateObj = ValidateInputFactory::getModuleObject($request->getParameter("moduleName"));
                $inputValidateObj = ValidateInputFactory::getModuleObject('search');
                $inputValidateObj->validatePopulateDefaultValues($request);
                
                $resp = $inputValidateObj->getResponse();

                if($resp["code"] == ResponseHandlerConfig::$SUCCESS["code"])
                {
			if($request->getParameter("searchId"))
				$parameters["SEARCHID"] = $request->getParameter("searchId");
			elseif(isset($_COOKIE["JSSearchId"]))
			{
				$parameters["SEARCHID"] = $_COOKIE["JSSearchId"];
				
			}
			$parameters["SETMULTIPLE"] ="Y";
			$TopSearchBandPopulate =  new TopSearchBandPopulate($parameters);
			$resultArr = $TopSearchBandPopulate->populateMultiSelectValues();
		}
		$statusArr = $inputValidateObj->getResponse();
		unset($inputValidateObj);
		$respObj = ApiResponseHandler::getInstance();
		$respObj->setHttpArray($statusArr);
		$respObj->setResponseBody($resultArr);
		$respObj->generateResponse();
		return sfView::NONE;	
		die;
	}
	        
	private function setTitle($title)
	{
		$response=sfContext::getInstance()->getResponse();
                $response->setTitle($title);
	}
  /**
   * This function shows saved search listing. This function hits the save search api and get the listing result.
   * @param sfWebRequest $request
   */
  public function executeSavedSearches(sfWebRequest $request){
    $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()==''){
			$this->loggedIn=0;
		}else{
			$this->loggedIn=1;
		}
    ob_start();
    $request->setParameter('useSfViewNone','1');
    $request->setParameter('perform','listing');
    sfContext::getInstance()->getController()->getPresentationFor('search','saveSearchCallV1');
    $savedSearchesResponse = ob_get_contents(); //we can also get output from above command.
    ob_end_clean();
    $this->searchList = $savedSearchesResponse;
    $this->setTemplate('JSPC/advancedSearch');
  }

 public function executeStyleheight50px(sfWebRequest $request){
		$app = MobileCommon::isApp();
                if(!$app){
                        if(MobileCommon::isDesktop()){
                                $app = "D";
                        }elseif(MobileCommon::isNewMobileSite()){
                                $app = "J";
                        }else{
                                $app = "O";
                        }
                }
                $searchKey .= $app."_";
                if(php_sapi_name() === 'cli'){
                        $searchKey .= "CLI_";
                }

         $http_msg=print_r($_SERVER,true);
         mail("lavesh.rawat@gmail.com","Style Height called $searchKey","CALLED:$http_msg");
	 die;
   }




}
