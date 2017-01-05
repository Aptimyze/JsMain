<?php
/**
 * @brief This class implements SearchChannelInterface class and defines the functions as per the business logic
 * @author Reshu Rajput
 * @created 1 Sep 15
 */
class SearchJSPC extends SearchJS
{
	private static $featuredProfileCount= 5;
	//Constructor 
	function __construct($params="")
	{
			
		
	}
	
	/* This function will return the channel specific variables
        *@param params : need to be set 
        */
        public function setVariables($params){
            $params["actionObject"]->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsSearchUrl);
                if(is_array($params) && array_key_exists("request",$params)){

			$actionObject = $params["actionObject"];
			$request = $params["request"];

			$actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesPerPage;
			$ResponseArr= $params["ResponseArr"];
			
	
		 if($ResponseArr["responseStatusCode"]==ResponseHandlerConfig::$SEARCH_EXPIRED_SEARCHID["statusCode"])
		 {
				$request->setAttribute("ERROR",ResponseHandlerConfig::$SEARCH_EXPIRED_SEARCHID["statusCode"]); // Search Id Expire Case
				$actionObject->forward("profile","noprofile");
			}
			/* This condition is placed to handle invalid post params
			 * It remove all serach parameters from request so that same vlaidation doesnt fail in loop
			 * In case of logged in profile dpp matches page gets open else normal search with no criteria is searched 
			 * Forward is done instead of redirect to keep remaining request intact 
			 */
			if($ResponseArr["responseStatusCode"]==ResponseHandlerConfig::$POST_PARAM_INVALID["statusCode"])
      {
				
				$request->setAttribute("ERROR",$ResponseArr["responseStatusCode"]); // invalid params cases
				foreach(explode(",",SearchConfig::$possibleSearchParamters) as $k=>$v)
				{
					
					$request->getParameterHolder()->remove(strtolower($v));
				}
					$request->getParameterHolder()->remove("searchId");
					$request->getParameterHolder()->remove("ignoreProfile");
					if($actionObject->loggedIn==1)
					{
						$request->setParameter("partnermatches",1);
						$request->setParameter("searchBasedParam","partnermatches");
						
	         }
					
					$actionObject->forward("search","perform");
			}
		
			$actionObject->resultCount = $ResponseArr["no_of_results"];
			$actionObject->pageHeading =$ResponseArr["result_count"];
			$actionObject->totalNoOfPages = ceil($ResponseArr["no_of_results"]/SearchConfig::$profilesPerPage);
			$actionObject->profilesPerPage = SearchConfig::$profilesPerPage;
			$actionObject->noresultmessage = $ResponseArr["noresultmessage"];
			$actionObject->pageSubHeading = $ResponseArr["pageSubHeading"];
			$actionObject->Sorting  = ($ResponseArr["sorting"]=='O' ? 'fresh' : 'rel');
			$searchId = $ResponseArr["searchid"];
			$actionObject->searchId = $searchId;
			$this->searchTabsSettings($params);
                        
                        //Save Search
			if($request->getParameter("type")=='AS' || $request->getParameter("QuickSearchBand")==1)
			{
	                        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
        	                if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()){
                	        	$actionObject->loggedin=1;
                        	        $saveSearchResult = $this->saveSearch($params);
                                	$actionObject->savedSearches = $saveSearchResult["savedSearches"];
	                                $actionObject->maxSaveSearches = $saveSearchResult["maxSaveSearches"];
			                if($ResponseArr["diffGenderSearch"])
                			        $actionObject->showSaveSearchIcon =1;
	                       }	
			}
                        
                        //search Summary
                        $searchSummaryObj = new SearchService();
                        $searchSummaryResult = $searchSummaryObj->searchSummary($params["ResponseArr"]["searchid"]);
                        $actionObject->searchSummaryFormatted = $searchSummaryResult["searchSummaryFormatted"];
                        $actionObject->searchId=$searchSummaryResult["searchId"];
                        $actionObject->defaultImage=$ResponseArr["defaultImage"];
                        $this->getQSBData($params);
			$actionObject->setTemplate("JSPC/search");
		}
		else
			throw new JsException("", "Params with request required in SearchJSPC.class.php");
		
	}

	/**
	* for search listings set default settings 
	**/
	private function searchTabsSettings($params)
	{
		$request = $params["request"];
		if($params["ResponseArr"]["searchBasedParam"] && $request->getParameter("searchBasedParam")=='')
		{
			$request->setParameter("searchBasedParam",$params["ResponseArr"]["searchBasedParam"]);
		}

		$actionObject = $params["actionObject"];
                if($request->getParameter("searchBasedParam")){
			$actionObject->searchListings=1;

			//Check that allows QuickSearchBand to appear in case last Search Results are viewed
				if($request->getParameter("searchBasedParam") == 'lastSearchResults')
				{
                        $actionObject->searchListings=0;
				}
				$actionObject->isRightListing=0;
			if(in_array($request->getParameter("searchBasedParam"),array('kundlialerts','reverseDpp','shortlisted','visitors','contactViewAttempts')))
                                $actionObject->isRightListing=1;
                        switch ($request->getParameter("searchBasedParam")){
                                case 'matchalerts':
                                        $clickOn = 1;
                                        break;
                                case 'partnermatches':
                                        $clickOn = 2;
                                        break;
                                case 'reverseDpp':
                                        $clickOn = 6;
                                        break;
                                case 'justJoinedMatches':
                                        $clickOn = 3;
                                        break;
                                case 'twowaymatch':
                                        $clickOn = 5;
                                        break;
                                case 'kundlialerts':
                                        $clickOn = 7;
                                        break;
                                case 'shortlisted':
                                        $clickOn = 8;
                                        break;
                                case 'visitors':
                                        $clickOn = 9;
                                        break;
                                case 'verifiedMatches':
                                        $clickOn = 4;
                                        break;
                                case 'contactViewAttempts':
                                        $clickOn = 10;
                                        break;
                            }
                }
                else{
                        if($request->getParameter("dashboard"))
                        {
                                $actionObject->searchListings=1;
                                $clickOn = 1;
                        }
                        $actionObject->isRightListing=0;
                }
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $subscriptionType = $loggedInProfileObj->getSUBSCRIPTION();
                $actionObject->subscriptionType = $subscriptionType;
		if($request->getParameter("showKundliList")==1)
		{
                        $actionObject->setGap = 810;
			$actionObject->showKundliList=1;
		}
		else
		{
			$actionObject->showKundliList=0;
			$actionObject->setGap = 610;
			if($clickOn>6)
				$clickOn--;
		}
                $clickOn = $clickOn>0?($clickOn-1)*200:0;
                $actionObject->clickOn = $clickOn>999?$clickOn-$actionObject->setGap:$clickOn;
	}
	
	
	/*This function will return populate default values and static serach form data
	 */
	public function getQSBData($params){
		$request = $params["request"];
		$actionObject = $params["actionObject"];
		
		if($request->getParameter("QuickSearchBand"))
		{
			
			$request->setParameter('searchId',$params["ResponseArr"]["searchid"]);
			ob_start();
			sfContext::getInstance()->getController()->getPresentationFor('search','populateDefaultValuesV2');
			$actionObject->populateDefaultValues = (array)json_decode(ob_get_contents(),true); //we can also get output from above command.
			ob_end_clean();
			
		}
		else
			$request->setParameter("forClusters",'1');
		
		$request->setParameter('useSfViewNone','1');
		$request->setParameter('json','{"searchForm":"2013-12-25 00:00:00"}');
		$request->setParameter('actionName','searchFormData');
		$request->setParameter('moduleName','search');
		ob_start();
		sfContext::getInstance()->getController()->getPresentationFor('search','SearchFormDataV1');
		
		$searchData = (array)json_decode(ob_get_contents(),true); //we can also get output from above command.
		
		$actionObject->staticSearchData=json_encode($searchData["services"]["searchForm"]["data"]);
		$actionObject->staticSearchDataArray = $searchData["services"]["searchForm"]["data"];
		ob_end_clean();
		
		$this->handleQSBDefaults($params);
	}

	/** 
	 * This function is used to modify QSB data as per JSPC requirements in null and default cases
	 */
	public function handleQSBDefaults($params){
		$request = $params["request"];
		$actionObject = $params["actionObject"];
		$populateDefaultValues= $actionObject->populateDefaultValues;
	
		if(is_array($populateDefaultValues))		
		foreach($populateDefaultValues as $key=>$value)
		{
			switch($key){
				case "gender" : $populateDefaultValues["gender_label"] = ($value=="M")?"Groom":"Bride";
				case "mtongue": //$populateDefaultValues["mtongue"] = $value?$value:"DONT_MATTER"; 
												$populateDefaultValues["mtongue_label"] = $value?preg_replace('/\s+/', '&nbsp;',$populateDefaultValues["mtongue_label"]):""; 
												break;
				case "location": $populateDefaultValues["location_label"] = $value?preg_replace('/\s+/', '&nbsp;',$populateDefaultValues["location_label"]):""; 
												//$populateDefaultValues["location"] = $value?$value:"DONT_MATTER";
												break;
				case "mstatus": $populateDefaultValues["mstatus_label"] = $value?preg_replace('/\s+/', '&nbsp;',$populateDefaultValues["mstatus_label"]):""; 
												//$populateDefaultValues["mstatus"] =$value?$value:"DONT_MATTER";
												break;															
				case "caste": $populateDefaultValues["caste_label"] = $value?preg_replace('/\s+/', '&nbsp;',$populateDefaultValues["caste_label"]):""; 
												//$populateDefaultValues["caste"] =$value?$value:"DONT_MATTER";
												break;
				case "religion": $populateDefaultValues["religion_label"] = $value?preg_replace('/\s+/', '&nbsp;',$populateDefaultValues["religion_label"]):""; 
												//$populateDefaultValues["religion"] =$value?$value:"DONT_MATTER";
												break;								
				
			}
		}
		
		$actionObject->populateDefaultValues = $populateDefaultValues;
		
		$staticSearchDataArray =$actionObject->staticSearchDataArray;
		$qsbFields=["mtongue","location","mstatus","religion"];
		
		foreach($qsbFields as $key=>$value)
		{
			switch($value){
				case "mtongue": $data[0]["VALUE"]="DONT_MATTER";
												$data[0]["LABEL"]="Select Mother Tongue";
												$staticSearchDataArray[$value] = array_merge($data,$staticSearchDataArray[$value]);
												break;
				case "location": $data[0]["VALUE"]="DONT_MATTER";
												$data[0]["LABEL"]="Select City/ Country";
												$staticSearchDataArray[$value] = array_merge($data,$staticSearchDataArray[$value]);
												break;
				case "mstatus": $data[0]["VALUE"]="DONT_MATTER";
												$data[0]["LABEL"]="Select Marital Status";
												$staticSearchDataArray[$value] = array_merge($data,$staticSearchDataArray[$value]);
												break;
				case "religion": $data[0]["VALUE"]="DONT_MATTER";
												$data[0]["LABEL"]="Select Religion";
												$staticSearchDataArray[$value] = array_merge($data,$staticSearchDataArray[$value]);
												break;
							
			}
		}
		
		//$actionObject->staticSearchDataArray = $staticSearchDataArray ;
		
	}


	/**
        * Quick/Top Search Band.
        */
        public static function getSearchTypeQuick()
        {        
                 return SearchTypesEnums::Quick;
        }


	/**
        * getMatchalertsListing.
        */
        public static function getSearchTypeMatchalerts()
        {
                 return SearchTypesEnums::MatchAlerts;
        }
        
	/**
        * getSearchTypeContactViewAttempt.
        */
        public static function getSearchTypeContactViewAttempt()
        {        
                 return SearchTypesEnums::contactViewAttempt;
        }
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe()
        {
                 return SearchTypesEnums::ReverseDpp;
        }
        /**
        * getJJMatches
        */
        public static function getSearchTypeJJMatches()
        {
                 return SearchTypesEnums::JustJoinedMatchesDesktop;
        }
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches()
        {
                 return SearchTypesEnums::TwoWayMatch;
        }
         /**
        * getSearchTypeVerifiedMatches
        */
        public static function getSearchTypeVerifiedMatches()
        {
                 return SearchTypesEnums::VERIFIED_MATCHES_JSPC;
        }
        
        
        /**
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches()
        {
                 return SearchTypesEnums::KundliAlerts;
        }
        
        function saveSearch($params){
                $loggedInProfileObj = $params["loggedInProfileObj"];
                $request = $params["request"];
                $this->maxSaveSearches=0;
                $this->loggedIn = true;
                $profileMemcacheObj = new ProfileMemcacheService($loggedInProfileObj);
                $saveSearchCount=$profileMemcacheObj->get("SAVED_SEARCH");
                
                if($saveSearchCount && $saveSearchCount>0)
                {
                  ob_start();
                  $request->setParameter('useSfViewNone','1');
                  $request->setParameter('perform','listing');
                  sfContext::getInstance()->getController()->getPresentationFor('search','saveSearchCallV1');
                  $savedSearchesResponse = json_decode(ob_get_contents()); //we can also get output from above command.
                  //print_r($savedSearchesResponse);die;
                  ob_end_clean();
                  
                  if($savedSearchesResponse->saveDetails && $savedSearchesResponse->saveDetails->details)
                        $this->savedSearches = $savedSearchesResponse->saveDetails->details;
                  if(sizeOf($this->savedSearches)>=SearchConfig::$maxSaveSearchesAllowed)
                        $this->maxSaveSearches=1;

                  return array("savedSearches"=>$this->savedSearches,"maxSaveSearches"=>$this->maxSaveSearches);
                }

                
        }
        
         /**
        * get Education and occupation detailed clusters
        */
        public function eduAndOccClusters($clustersToShow,$params="")
        {       
                $request = $params["request"];
                $clusterMore = $request->getParameter('clusterMore');
                
                if($clusterMore==1){
                        foreach($clustersToShow as $k=>$v)
                        {
                                if($v=='OCCUPATION_GROUPING')
                                        $clustersToShow[$k] = 'OCCUPATION';
                                elseif($v=='EDUCATION_GROUPING')
                                        $clustersToShow[$k] = 'EDU_LEVEL_NEW';
                        }
                }
                return $clustersToShow;
        }
        
        /**
        * get No of results for srp page
        */
        public function getNoOfResults()
        {        
                 return SearchConfig::$profilesPerPage;
        }
        
       /**
	* This function will set the No. Of featured profiles results for search Page
	*/
        public function getFeaturedProfilesCount()
        {
					return self::$featuredProfileCount;
				}
				
				      						/**
	* This function will set the featured profiles stype for search Page
	*/
		public function getFeaturedProfilesStype()
			{
				return SearchTypesEnums::FeatureProfile;
			}
			
				/**
	* This function will set the channel type
	*/
		public function getChannelType()
		{
			return "PC";
		}
                
       public function setRequestParameters($params){
            $output = array();
            $request = $params["request"];
            if($params["searchCat"] == 'contactViewAttempts'){
                    $output['listType'] = 'noClusSearch';
                    $output['clusters'] = null;
                    $output['heading'] = 'Contact View Attempts';
                    $output['pageHeading'] = null;
                    $output['total'] = $params['noOfResults'];
                    $output['ccmessage'] = 'These are people who tried to view your contact details in the last 3 months and match your desired partner preferences';
                    $output['searchBasedParam'] = 'contactViewAttempts';
                    $rcbObj = new RequestCallBack($params['loggedInProfileObj']);
                    $output['display_rcb_comm'] = $rcbObj->getRCBStatus();
                    unset($rcbObj);
            }elseif($params["searchCat"] == 'kundlialerts'){
            	$output['listType'] = 'noClusSearch';
                $output['clusters'] = null;
                $output['heading'] = $params['result_count'];
                $output['pageHeading'] = null;
                $output['total'] = null;
                $output['ccmessage'] =  $params['pageSubHeading'];
                $output['pageSubHeading'] =null;
                $output['searchBasedParam'] = $params["searchCat"];
                $output['DefaultZeroMsg'] = SearchTitleAndTextEnums::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["kundlialerts"]["withHoro"];
                $output['minAcceptedGunaScore'] = SearchConfig::$minAcceptedGunaScore;
            }
            return $output;
       }
        
}
?>
