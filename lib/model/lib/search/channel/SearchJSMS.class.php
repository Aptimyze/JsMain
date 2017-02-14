<?php
/**
 * @brief This class implements SearchChannelInterface class and defines the functions as per the business logic
 * @author Reshu Rajput
 * @created 1 Sep 15
 */
class SearchJSMS extends SearchJS
{
	private static $featuredProfileCount= 1;
	//Constructor 
	function __construct($params)
	{
		if(is_array($params) && array_key_exists("request",$params)){
			/**In case of zero results, /search/perform is called instead of search/topSearchBand**/
			$request = $params["request"];
			if($request->getParameter("isMobile")=='Y')
			{
				echo("<html><script type=\"text/javascript\" language=\"javascript\">javascript:history.go(-1)</script></html>");
				die;
			}
			/**In case of zero results, /search/perform is called instead of search/topSearchBand**/
		
		}
		//else
			//throw new JsException("", "Params with request required in SearchJSMS.class.php");
	}
	
	
	/* This function will return the channel specific variables
        *@param params : need to be set 
        */
        public function setVariables($params){
		$actionObject = $params["actionObject"];
		$ResponseArr= $params["ResponseArr"];
		$request = $params["request"];
		$loggedInProfileObj= $params["loggedInProfileObj"];
		$actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesPerPageOnWapSite;
		if($param["isLogout"]==1)
			$actionObject->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobSearchPageLogOutUrl);
                else
			$actionObject->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsMobSearchPageUrl);

		// Back Handling
                if($ResponseArr["searchid"]){
                        $actionObject->backSearchId = $ResponseArr["searchid"];
                        $actionObject->backReferer = $_SERVER['HTTP_REFERER'];
                }
                if($request->getParameter("fmBack")){
                        $actionObject->fmBackECP = 1;
                }else{
                       $actionObject->fmBackECP = 0;
                }
                if($ResponseArr["stype"]==SearchTypesEnums::MobileSearchBand)
		{
			if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()!='')
			{
				$actionObject->showSaveSearchIcon =1;
			}
		}
		
         
    if($ResponseArr["stype"]==self::getSearchTypeKundliMatches())
    {
			$actionObject->dontShowSorting=1;
		}          
    if($ResponseArr["responseStatusCode"]==ResponseHandlerConfig::$POST_PARAM_INVALID["statusCode"] || ($ResponseArr["stype"]==SearchTypesEnums::MobileSearchBand && is_array($ResponseArr) && $ResponseArr["no_of_results"]==0))
		{
			
			$actionObject->dontShowSorting=1;
			$actionObject->getRequest()->setParameter('searchId',$ResponseArr["searchid"]);
			$actionObject->getRequest()->setParameter('noResultFound',1);
			$actionObject->getRequest()->setParameter('isMobile',"Y");
			$actionObject->forward("search","topSearchBand");
		}
		else
		{
			$actionObject->firstResponse = $jsonResponse;
			if($ResponseArr["stype"]==SearchTypesEnums::JustJoinedMatches)
				$actionObject->dontShowSorting=1;
			$actionObject->noresultmessage = $ResponseArr["noresultmessage"];
			$actionObject->_SEARCH_RESULTS_PER_PAGE = SearchConfig::$profilesPerPageOnWapSite;
			$actionObject->setTemplate("mobile/MobSearch");
		}
               
	}

	/**
        * Quick/Top Search Band.
        */
        public static function getSearchTypeQuick()
        {        
                 return SearchTypesEnums::MobileSearchBand;
        }

	/**
        * getMatchalertsListing.
        */
        public static function getSearchTypeMatchalerts()
        {
                 return SearchTypesEnums::WapMatchAlertsCC;
        }
        
	/**
        * getSearchTypeContactViewAttempt.
        */
        public static function getSearchTypeContactViewAttempt()
        {        
                 return SearchTypesEnums::contactViewAttemptJSMS;
        }
        /**
        * getMembersLookingForMe
        */
        public static function getSearchTypeMembersLookingForMe()
        {
                 return SearchTypesEnums::wapRevDPP;
        }
        /**
        * getJustJoinedMatches
        */
        public static function getSearchTypeJJMatches()
        {
                 return SearchTypesEnums::JustJoinedMatches;
        }
        /**
        * getSearchTypeTwoWayMatches
        */
        public static function getSearchTypeTwoWayMatches()
        {
                 return SearchTypesEnums::WapTwoWayMatch;
        }
         /**
        * getSearchTypeVerifiedMatches
        */
        public static function getSearchTypeVerifiedMatches()
        {
                 return SearchTypesEnums::VERIFIED_MATCHES_JSMS;
        }
        
         /**
        * get No of results for srp page
        */
        public function getNoOfResults()
        {        
                 return SearchConfig::$profilesPerPageOnWapSite;
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
				return SearchTypesEnums::JSMSFeatureProfile;
			}
        
        
     /**
        * getSearchTypeKundliMatches
        */
        public static function getSearchTypeKundliMatches()
        {
                 return SearchTypesEnums::KundliAlertsJSMS;
        }
        
        	/**
	* This function will set the channel type
	*/
		public function getChannelType()
		{
			return "JSMS";
		}
        
}
?>
