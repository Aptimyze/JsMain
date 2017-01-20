<?php
/**
 * @brief This class implements SearchChannelInterface class and is base class for all other channels
 * @author Akash Kumar
 * @created 24 Sep 15
 */
class SearchJS implements SearchChannelInterface
{
	private static $featuredProfileCount= 0;
	//Constructor 
	function __construct($params="")
	{
			
		
	}
	
        /* This function will return the channel specific variables
        *@param params : need to be set 
        */
        public function setVariables($params){
                
        }


	/**
	* This function will give search type corresponding to quick search band.
	*/
	public static function getSearchTypeQuick(){
                
        }

	/**
        * getMatchalertsListing.
        */
        public static function getSearchTypeMatchalerts()
        {
                
        }
        /**
        * getSearchTypeContactViewAttempt.
        */
        public static function getSearchTypeContactViewAttempt()
        {        
                
        }
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe(){
                
        }
        
        /**
        * getJJMatches
        */
        public static function getSearchTypeJJMatches(){
                 
        }
        
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches(){
                 
        }
        
        /**
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches()
        {
                 
        }        
	public static function getSearchTypeMatchOfDay()
	{
		return SearchTypesEnums::MatchOfDay;
	}
        
        /**
	* This function will set the No. Of results for search Page
	*/
	public function getNoOfResults(){
                
        }
        
        /**
        * get Education and occupation detailed clusters
        */
        public function eduAndOccClusters($clustersToShow,$params=""){
                return $clustersToShow;
        }
        
        /**
        * Zero Result Message
        */
        function searchZeroResultMessage(){
                return ResponseHandlerConfig::$SEARCH_O_RESULTS;
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
                  return array();
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
                                                        $this->featurePosition = 'single';
                                                $actionObject->featuredResultNo=0;
                                                $actionObject->totalFeaturedProfiles = count($respObj->getSearchResultsPidArr());
                                        }
                                }
                                else
                                { 
																	
                                        $SearchParamtersObjF = new SearchParamters;
                                        $SearchParamtersObjF->setProfilesToShow($featureProfileIdArr["All"]);
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
                       
                        //$featuredProfileToShow = array_diff($featuredProfilesArr,$searchedProfilesArr);
                        $featuredProfileToShow = array_slice($featuredProfilesArr,0,$noOfProfiles);
                        foreach($featuredProfileToShow as $key=>$value){
                                if($value){
                                        $featuredProfileDetailsToShow = $respObj->getResultsArr()[$key];
                                        $featuredProfileArray[]=$featuredProfileDetailsToShow;
                                }
                        } 
                        if(count($featuredProfileArray)>0)
                                $responseObj->setFeturedProfileArr($featuredProfileArray);
                }
								
                return $responseObj;
                
        }
        
        
        
}
?>
