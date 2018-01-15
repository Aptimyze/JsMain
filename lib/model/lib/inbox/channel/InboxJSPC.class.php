<?php
/**
 * @brief This class implements InboxChannelInterface class and defines the functions as per the business logic
 */
class InboxJSPC extends InboxJS
{
	
	//Constructor 
	function __construct($params="")
	{
				
		
	}

	
	/** 
	* This function will return the channel specific variables
    	*@param : params
    	*/
        public function setVariables($params)
        {
        	$params["actionObject"]->getResponse()->setSlot("optionaljsb9Key", Jsb9Enum::jsContactCenterUrl);
        	if(is_array($params) && array_key_exists("request",$params))
	        {
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			if($loggedInProfileObj->getAGE()=='')
				$loggedInProfileObj->getDetail("","","RELIGION,SUBSCRIPTION");
			$actionObject = $params["actionObject"];
			$request = $params["request"];
			$actionObject->CC_RESULTS_PER_PAGE = InboxConfig::$ccPCProfilesPerPage;
			$ResponseArr = $params["ResponseArr"];
			$actionObject->resultCount = $ResponseArr["no_of_results"];
			$actionObject->pageHeading =$ResponseArr["result_count"];
			$actionObject->pageSubHeading=$ResponseArr["pageSubHeading"]; 
			$actionObject->noresultmessage = $ResponseArr["noresultmessage"];
			$actionObject->pageSubHeading = $ResponseArr["pageSubHeading"];
			$actionObject->vspStype = SearchTypesEnums::VIEW_SIMILAR_ACCEPT_PC;
			//set page no for full response api's
			if($ResponseArr["page_index"]==""||$ResponseArr["searchid"]=="16"||$ResponseArr["no_of_results"]=="")
				$actionObject->pageNoForFullResponseApis = $params["request"]->getParameter("pageNo");
			$this->setDefaultInboxTabs($params);
			$actionObject->ccRequestTypeListMapping=InboxConfig::$ccRequestTypeListArr;
                        if($ResponseArr["hidePaginationCount"])
                            $actionObject->hidePaginationCount=1;
			$actionObject->ccRequestTypeListArr=json_encode(InboxConfig::$ccRequestTypeListArr);
			
			//check religion(should be 'Hindu/Jain/Sikh/Buddhist' for horoscope case)
            if(in_array($loggedInProfileObj->getRELIGION(), array('1','4','7','9')))
            {
                $actionObject->showRequestTypeList = 'Y';
            }
            else
                $actionObject->showRequestTypeList = 'N';

            //show or hide intro call list depending on subscription
            $introCallParams = array("actionObject"=>$actionObject,"loggedInProfileObj"=>$loggedInProfileObj);
            $this->setIntroCallListVisibiltyStatus($introCallParams);
     
			//set navigator
			$navigation_type = $ResponseArr["navigation_type"];

			//$navObj = new NAVIGATOR();
			//$searchid = $request->getParameter('infoTypeId');
			//$page = $request->getParameter('pageNo');
			//$actionObject->NAVIGATOR = $navObj->navigation($navigation_type,"infoTypeId__$searchid@pageNo__$page",'','Symfony');
			$actionObject->NAVIGATOR = $ResponseArr["NAVIGATOR"];
			$actionObject->setTemplate("index");
		}
		else
			throw new JsException("", "Params with request required in InboxJSPC.class.php");
	}	

	/**
	* for inbox listings set default tab settings 
	* @param : $params
	**/
	private function setDefaultInboxTabs($params)
	{
		$actionObject = $params["actionObject"];
		$request = $params["request"];
		$actionObject->contactCenterTabMapping = InboxConfig::$cctabArr;	
		$actionObject->ccTabsMappingData = json_encode($actionObject->contactCenterTabMapping);
		if($request->getParameter('infoTypeId'))
    	{
    		$actionObject->activeHorizontalTab = $request->getParameter('infoTypeId');
	    	$actionObject->activeVerticalTab = InboxConfig::getCorrespondingVerticalTabID($actionObject->contactCenterTabMapping,$actionObject->activeHorizontalTab);
	    	if($actionObject->activeVerticalTab==1)
	    	{
	    		$actionObject->activeRequestTypeID = InboxConfig::getCorrespondingRequestTypeTabID($actionObject->activeHorizontalTab,InboxConfig::$ccRequestTypeListArr);
	    	}
	    	else
	    		$actionObject->activeRequestTypeID = InboxConfig::$defaultRequestTypeID; //for photo option in Requests vertical tab
    	}
		else
		{
			$actionObject->activeVerticalTab = InboxConfig::$defaultVerticalTabID; //for photo option in Requests vertical tab
			$actionObject->activeHorizontalTab = $actionObject->contactCenterTabMapping[$actionObject->activeVerticalTab]["defaultHtabInfoID"];
	    	$actionObject->activeRequestTypeID = InboxConfig::$defaultRequestTypeID;
	    }
	    $actionObject->defaultRequestTypeID = InboxConfig::$defaultRequestTypeID;
	}

	/**
	* for inbox listings set intro call list visibilty 
	* @param : $params
	**/
	private function setIntroCallListVisibiltyStatus($params)
	{
		$loggedInProfileObj = $params["loggedInProfileObj"];
		$actionObject = $params["actionObject"];
		$introCallObj = new getIntroCallHistory();
        $isProfileAPMember = $introCallObj->isProfileApMember($loggedInProfileObj->getSUBSCRIPTION());
        unset($introCallObj);
    	if(!$isProfileAPMember)
        	$actionObject->showIntroCallsList = 'N';
        else
        {
        	$membershipObj = new MembershipHandler;
        	$offlineCallCountArr = $membershipObj->getAllCount($loggedInProfileObj->getPROFILEID());
        	unset($membershipObj);
        	if($offlineCallCountArr["TOTAL"])
        		$actionObject->showIntroCallsList = 'Y';
        	else
        		$actionObject->showIntroCallsList = 'N';
        }	
	}
}
?>

