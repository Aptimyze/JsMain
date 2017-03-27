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
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches()
        {
                 return SearchTypesEnums::KundliAlertsIOS;
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
        /**
        * getMatchOfDay.
        */
        public static function getSearchTypeMatchOfDay()
        {
                 return SearchTypesEnums::IOSMatchOfDay;
        }
        
        public function setRequestParameters($params){
            $output = array();
            $request = $params["request"];
            if($params["searchCat"] == 'kundlialerts'){
				
				if($params['profileCount']==0 && $params["nextAvail"]=='false' && $params['noOfResults']!="")
				{
					$output['noresultmessage'] = SearchTitleAndTextEnums::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["kundlialerts"]["withHoro"];
            	}
               
            }
            return $output;
       }
        
}
?>
