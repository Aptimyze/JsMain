<?php
/**
 * @brief This class implements SearchChannelInterface class and defines the functions as per the business logic
 * @author Reshu Rajput / Lavesh Rawat
 * @created 1 Sep 15
 */
class SearchJSIOS extends SearchJS
{
	private static $featuredProfileCount= 1;
	//Constructor 
	function __construct($params="")
	{
	}
	
	
	/* 
	* This function will return the channel specific variables
        * @param params : need to be set 
        */
        public function setVariables($params){
		if(is_array($params) && array_key_exists("request",$params)){
			$actionObject = $params["actionObject"];
			$actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesPerPageOnApp;
		}
		else
			throw new JsException("", "Params with request required in SearchJSIOS.class.php");
	}

	/**
        * Quick/Top Search Band.
        */
        public static function getSearchTypeQuick()
        {        
                 return SearchTypesEnums::iOS;
        }

	/**
        * getMatchalertsListing.
        */
        public static function getSearchTypeMatchalerts()
        {
                 return SearchTypesEnums::iOSMatchAlertsCC;
        }
        
	/**
        * getSearchTypeContactViewAttempt.
        */
        public static function getSearchTypeContactViewAttempt()
        {        
                 return SearchTypesEnums::contactViewAttemptIOS;
        }
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe()
        {
                 return SearchTypesEnums::iOSRevDPP;
        }
        /**
        * getSearchTypeJJMatches
        */
        public static function getSearchTypeJJMatches()
        {
                 return SearchTypesEnums::iOSJustJoinedMatches;
        }
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches()
        {
                 return SearchTypesEnums::iOSTwoWayMatch;
        }
        /**
        * getSearchTypeVerifiedMatches
        */
        public static function getSearchTypeVerifiedMatches()
        {
                 return SearchTypesEnums::VERIFIED_MATCHES_IOS;
        }

        
         /**
        * get No of results for srp page
        */
        public function getNoOfResults()
        {        
                 return SearchConfig::$profilesPerPageOnApp;
        }
        
        /**
        * Zero Result Message
        */
        function searchZeroResultMessage(){
                return ResponseHandlerConfig::$SEARCH_O_RESULTS_I;
        }
        
        
        /**
        * Featured Profile
        */
	public function showFeaturedProfile($featuredProfile,$currentPage,$loggedInProfileObj,$SearchParamtersObj,$responseObj,$SearchServiceObj,$noOfProfiles,$searchId,$actionObject) {
                if(count($responseObj->getsearchResultsPidArr())<1)
                        return $responseObj;
                /* feature profile */
                if($featuredProfile)
                {
                        if($currentPage==1)
                        { 
                                $featuredProfileObj = new FeaturedProfile($loggedInProfileObj);
                                $featureProfileIdArr = $featuredProfileObj->getProfile("","",$searchId);
                                $featureProfileId = $featureProfileIdArr["PROFILEID"];
                                if(!$featureProfileId)
                                {
                                        $featuredProfileObj->getFeaturedSearchCriteria($SearchParamtersObj);
                                        $SearchServiceObj->setSearchSortLogic($featuredProfileObj,$loggedInProfileObj,'FP');
                                        $respObj = $SearchServiceObj->performSearch($featuredProfileObj,"onlyResults",'','','',$loggedInProfileObj);
                                        if(count($respObj->getSearchResultsPidArr())==0)
                                        {
                                                unset($featuredProfileObj);
                                                $featuredProfileObj = new FeaturedProfile($loggedInProfileObj);
                                                $featuredProfileObj->getFeaturedSearchCriteria($SearchParamtersObj,1);
                                                $SearchServiceObj->setSearchSortLogic($featuredProfileObj,$loggedInProfileObj,'FP');
                                                $respObj = $SearchServiceObj->performSearch($featuredProfileObj,"onlyResults",'','','',$loggedInProfileObj);
                                        }

                                        if(count($respObj->getSearchResultsPidArr())>0)
                                        {
                                                $featureProfileId = $featuredProfileObj->performDbAction($searchId,$respObj->getSearchResultsPidArr());
                                                if(count($respObj->getSearchResultsPidArr())>1)
                                                        $actionObject->featurePosition = 'first';
                                                else
                                                        $actionObject->featurePosition = 'single';
                                                $actionObject->featuredResultNo=0;
                                                $actionObject->totalFeaturedProfiles = count($respObj->getSearchResultsPidArr());
                                        }
                                }
                                else
                                { 
                                        $SearchParamtersObjF = new SearchParamters;
                                        $SearchParamtersObjF->setProfilesToShow($featureProfileId);
                                        $SearchParamtersObjF->setGENDER('ALL');
                                        $respObj = $SearchServiceObj->performSearch($SearchParamtersObjF,'onlyResults','','',0,$loggedInProfileObj);
                                        $actionObject->featurePosition = $featureProfileIdArr["POSITION"];
                                        $actionObject->featuredResultNo=0;
                                        $actionObject->totalFeaturedProfiles = $featureProfileIdArr["TOTAL"];
                                        unset($SearchParamtersObjF);
                                }
                                unset($featuredProfileObj);
                        }
                }
                /* feature profile */

                if($respObj && is_array($respObj->getsearchResultsPidArr())){
                        $featuredProfilesArr = $respObj->getsearchResultsPidArr();
                        $searchedProfilesArr = $responseObj->getsearchResultsPidArr();
                        $featuredProfileToShow = array_diff($featuredProfilesArr,$searchedProfilesArr);
                        if($featuredProfileToShow){
                                $featuredProfileToShowArr[0]=$featuredProfileToShow[0];
                                $indexOfFeatured = array_search($featuredProfileToShowArr[0],$featuredProfilesArr);
                                $featuredProfileDetailsToShow[0] = $respObj->getResultsArr()[$indexOfFeatured];
                                $featuredProfileDetailsToShow[0]["FEATURED"] = "Y";
                                $featuredAndSearched  = array_merge($featuredProfileDetailsToShow,$responseObj->getResultsArr());
                                $featuredAndSearchedPidArr  = array_merge($featuredProfileToShowArr,$searchedProfilesArr);
                                $responseObj->setResultsArr($featuredAndSearched);
                                $responseObj->setSearchResultsPidArr($featuredAndSearchedPidArr);
                                $responseObj->setFeturedProfileArr($featuredProfileDetailsToShow[0]);
                        }
                }
                return $responseObj;
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
				return SearchTypesEnums::IOSFeatureProfile;
			}
			
			
			    
   /**
	* This function will set the channel type
	*/
		public function getChannelType()
		{
			return "JSMS";
		}
		
		
	/**
        * get Education and occupation detailed clusters
        */
        public function eduAndOccClusters($clustersToShow,$params="")
        {        
                foreach($clustersToShow as $k=>$v)
                {
                        if($v=='OCCUPATION_GROUPING')
                                $clustersToShow[$k] = 'OCCUPATION';
                        elseif($v=='EDUCATION_GROUPING')
                                $clustersToShow[$k] = 'EDU_LEVEL_NEW';
                }
                return $clustersToShow;
        }
}
?>
