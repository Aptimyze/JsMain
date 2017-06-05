<?php
/**
 * @brief This class implements SearchChannelInterface class and defines the functions as per the business logic
 * @author Akash Kumar
 * @created 24 Sep 15
 */
class SearchJSAPP extends SearchJS
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
			if(sfContext::getInstance()->getRequest()->getParameter('androidMyjsNew')==1)
				$actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesOnMyjsOnApp;
			else
                $actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesPerPageOnApp;
			
		}
		else
			throw new JsException("", "Params with request required in SearchJSAPP.class.php");
	}

	/**
        * Quick/Top Search Band.
        */
        public static function getSearchTypeQuick()
        {        
                 return SearchTypesEnums::App;
        }

	/**
        * getMatchalertsListing.
        */
        public static function getSearchTypeMatchalerts()
        {        
                 if(sfContext::getInstance()->getRequest()->getParameter('myjs')==1)
        	        return SearchTypesEnums::AppMyJsMatchAlertSection;
	         return SearchTypesEnums::AppMatchAlertsCC;
        }
	/**
        * getSearchTypeContactViewAttempt.
        */
        public static function getSearchTypeContactViewAttempt()
        {        
                 return SearchTypesEnums::contactViewAttemptAndroid;
        }
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe()
        {
                 return SearchTypesEnums::AppRevDPP;
        }
         /**
        * getSearchTypeJJMatches
        */
        public static function getSearchTypeJJMatches()
        {
                 return SearchTypesEnums::AppJustJoinedMatches;
        }
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches()
        {
                 return SearchTypesEnums::AppTwoWayMatch;
        }
         /**
        * getSearchTypeVerifiedMatches
        */
        public static function getSearchTypeVerifiedMatches()
        {
                 return SearchTypesEnums::VERIFIED_MATCHES_ANDROID;
        }
        
        
        /**
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches()
        {
                 return SearchTypesEnums::KundliAlertsAndroid;
        }
        
        /**
        * get No of results for srp page
        */
        public function getNoOfResults()
        {        
			if(sfContext::getInstance()->getRequest()->getParameter('androidMyjsNew')==1)
				return SearchConfig::$profilesOnMyjsOnApp;
			else
                return SearchConfig::$profilesPerPageOnApp;
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
        * Zero Result Message
        */
        function searchZeroResultMessage(){
                return ResponseHandlerConfig::$SEARCH_O_RESULTS;
        }
        
            
        	/**
	* This function will set the channel type
	*/
		public function getChannelType()
		{
			return "APP";
			
		}
		
								/**
	* This function will set the featured profiles stype for search Page
	*/
		public function getFeaturedProfilesStype()
			{
				return SearchTypesEnums::AAFeatureProfile;
			}
		
		
		 /**
	* This function will set the No. Of featured profiles results for search Page
	*/
        
        public function getFeaturedProfilesCount()
        {
					return self::$featuredProfileCount;
		}
				
				
        /**
        * getMatchOfDay.
        */
        public static function getSearchTypeMatchOfDay()
        {
                 return SearchTypesEnums::AndroidMatchOfDay;
        }
        
         public function setRequestParameters($params){
            $output = array();
            $request = $params["request"];
            if($params["searchCat"] == 'kundlialerts'){
				
				if($params['profileCount']==0 && $params["nextAvail"]=='false' && $params['noOfResults']!="")
				{
					$output['noresultmessage'] = SearchTitleAndTextEnums::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["kundlialerts"]["withHoro"];
            	}
               
            }
            return $output;
       }
}
?>
