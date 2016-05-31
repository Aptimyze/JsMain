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
        * Featured Profile
        */
        public function showFeaturedProfile($featuredProfile,$currentPage,$loggedInProfileObj,$SearchParamtersObj,$responseObj,$SearchServiceObj,$noOfProfiles,$searchId,$actionObject) {
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
}
?>
