<?php
/**
 * @class SearchParamtersLayer
 * @brief This class is an immediate layer which sets searchParamersObj based on save-id / or direct search input
 * @author Lavesh Rawat
 * @created 2012-07-15
 */

class SearchParamtersLayer
{
	static public function setSearchParamters($request,$loggedInProfileObj='',$specialSearch='')
	{
		$mySaveSearchId = $request->getParameter("mySaveSearchId");
		$reverseDpp = $request->getParameter("reverseDpp");
		$partnermatches = $request->getParameter("partnermatches");
        	$twowaymatch = $request->getParameter("twowaymatch");
        	$matchalerts = $request->getParameter("matchalerts");
        	$kundlialerts = $request->getParameter("kundlialerts");
		$searchId = $request->getParameter("searchId");
		$addRemoveCluster = $request->getParameter("addRemoveCluster");
		$relaxRefinementCluster = $request->getParameter("relaxRefinementCluster");
		$noRelaxation = $request->getParameter("noRelaxation");
		$moreLinkCluster = $request->getParameter("moreLinkCluster");
		$tempDPPCreator = $request->getParameter("tempDPPCreator");//This is for the file jsadmin/ap_dpp_common.php
		$addEthnicities = $request->getParameter("addEthnicities");
        	$twowaymatch = $request->getParameter("twowaymatch");
		$justJoinedMatches = $request->getParameter("justJoinedMatches");
		$QuickSearchBand = $request->getParameter("QuickSearchBand");
                $verifiedMatches = $request->getParameter("verifiedMatches");
                $ContactViewAttempts = $request->getParameter("contactViewAttempts");
		$matchofday = $request->getParameter("matchofday");
        $lastSearchResults = $request->getParameter("lastSearchResults");

		$uri = $request->getUri();
		if($specialSearch=='AppSearch')
			$AppSearch=1;
		if($partnermatches)
			$dpp=1;
		elseif($reverseDpp)
			$membersLookingForMe=1;
		elseif($request->getParameter("appnotification")==1)
			$appnotification=1;

		if($mySaveSearchId || $searchId || $dpp || $membersLookingForMe || $appnotification || $twowaymatch || $justJoinedMatches || $matchalerts || $kundlialerts || $verifiedMatches || $ContactViewAttempts || $lastSearchResults || $matchofday)
		/**
		* If predifined searches like save-search , dpp , reversedpp is run
		*/
		{
			if($dpp && !$searchId)
			/* Dpp Search Is Run */
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('PartnerProfile',$loggedInProfileObj);
				if($specialSearch && MobileCommon::isApp()=='A')
                                        $SearchParamtersObj->getDppCriteria($tempDPPCreator,"mobileAppDpp");
                                else
                                        $SearchParamtersObj->getDppCriteria($tempDPPCreator);

			}	
			elseif($mySaveSearchId)
			/* Save Search Is Performed */
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('mySavedSearch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria($mySaveSearchId);
			}
			elseif($membersLookingForMe && !$searchId)	
			/* Members Looking For Me is performed */
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
			}
            		elseif($justJoinedMatches && !$searchId)	
			/* Members Looking For Me is performed */
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('JustJoinedMatches',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria('FirstCall');
			}
			elseif($appnotification==1)
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('appNotificationSearch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
			}
			elseif($twowaymatch && !$searchId)
			/* two way match*/
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('TwoWayMatch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
				//print_r($SearchParamtersObj);
				//die;
			}
			elseif($matchalerts && !$searchId)
			/* two way match*/
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MatchAlertsSearch',$loggedInProfileObj);
        
                                //Get Reset Match Alert Count Params
                                $request=sfContext::getInstance()->getRequest();
                                /*
                                $bResetMatchAlertCount = $request->getParameter("resetMatchAlertCount") == 1 ? true : false;
                                $bResetMatchAlertCount = !$bResetMatchAlertCount && 1 == $request->getAttribute("resetMatchAlertCount") ? true : false;
                                if ($bResetMatchAlertCount) {
                                */
                                if ($request->getParameter("resetMatchAlertCount") || $request->getAttribute("resetMatchAlertCount")) {
                                  $profileCacheObj = new ProfileMemcacheService($loggedInProfileObj);
                                  $profileCacheObj->unsetKey("MATCHALERT");
                                  $SearchParamtersObj->storeLastVistTime();
                                }
        
				$SearchParamtersObj->getSearchCriteria();
				//print_r($SearchParamtersObj);
				//die;
			}
			elseif($ContactViewAttempts && !$searchId)
			/* two way match*/
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('ContactViewAttempts',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
                                //echo '<pre>';print_r($SearchParamtersObj);die;
			}
			elseif($kundlialerts && !$searchId)
			/* two way match*/
			{
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('KundliAlertsSearch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
			}
                        elseif($verifiedMatches && !$searchId)
                        //fso Verifired match
                        {
                                $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('verifiedMatches',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria();
                        }
			elseif($matchofday && !$searchId)
			{
                                $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MatchOfDay',$loggedInProfileObj);
                                $SearchParamtersObj->getSearchCriteria();
			}
            //last search results
		        elseif($lastSearchResults && !$searchId)
		        {
				$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('lastSearchResults',$loggedInProfileObj);
				$SearchParamtersObj->getLastSearchResultCriteria();
		        }
			elseif($searchId)
			/* Search is performed based on search-id */
			{
				if($membersLookingForMe)
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$loggedInProfileObj);
				elseif($twowaymatch)
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('TwoWayMatch',$loggedInProfileObj);
        elseif($verifiedMatches)
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('verifiedMatches',$loggedInProfileObj);
			  elseif($ContactViewAttempts)
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('ContactViewAttempts',$loggedInProfileObj);
				elseif($kundlialerts)
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('KundliAlertsSearch',$loggedInProfileObj);
				else
					$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('searchId',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria($searchId);
				//$specialCriteria = $request->getParameter('specialCriteria');

                                if($justJoinedMatches)
                                {
                                        $SearchParamtersObj->setShowFilteredProfiles(JustJoinedMatches::SHOW_FILTERED_PROFILES);
                                        if($request->getParameter("newTagJustJoinDate"))
                                                $SearchParamtersObj->setNewTagJustJoinDate($request->getParameter("newTagJustJoinDate"));
                                        else
                                                $SearchParamtersObj->setNewTagJustJoinDate("0000:00:00");
                                }

				if($noRelaxation)
				/* use chooses to revert auto relaxation. */
				{
	                        	$AutoRelaxationObj = new AutoRelaxation($SearchParamtersObj);
	                                $AutoRelaxationObj->revertAutoRelax();
				}
				if($addRemoveCluster)
				/* if cluster is applied. */
				{
					$SearchUtilityObj = new SearchUtility;
					$SearchUtilityObj->getSearchCriteriaAfterClusterApplication($request,$addRemoveCluster,$SearchParamtersObj);//this will update the $SearchParamtersObj
					unset($SearchUtilityObj);
				}
				if($relaxRefinementCluster)
				/* relax last cluster applied to avoid zero results */
				{
					$SearchUtilityObj = new SearchUtility;
					$SearchUtilityObj->relaxLastClusterOptionsToAvoidZeroResults($SearchParamtersObj,$relaxRefinementCluster);//this will update the $SearchParamtersObj
					unset($SearchUtilityObj);
				}
				if(strstr($uri,'addEthnicities') || $addEthnicities==1)
				/* caste mapping is applied. */
				{
					$SearchUtilityObj = new SearchUtility;
					$temp = $SearchUtilityObj->addCasteMapping($SearchParamtersObj);
					unset($SearchUtilityObj);
					unset($temp);
				}
				if($moreLinkCluster)
				{
					$SearchUtilityObj = new SearchUtility;
					$SearchUtilityObj->specialClusterOnMore($SearchParamtersObj,$moreLinkCluster);
					unset($SearchUtilityObj);
				}
			}
		}
		else
		/* If a direct search is run. */
		{
			$topSearchBand = $request->getParameter('TOP_BAND_SEARCH');
			$mobileSearch  = $request->getParameter('MOBILE_SEARCH');
			$advanceSearchType = $request->getParameter('type');

			if($topSearchBand=='Y' || $mobileSearch=='Y')
			/* Direct Search : Quick Search is performed */
			{
				$SearchParamtersObj = UserInputSearchFactory::getSetterBy('TopSearchBand');
				$SearchParamtersObj->getSearchCriteria($request);
			}
			elseif($QuickSearchBand)
			/* Direct Search : Quick Search is performed */
			{
				$SearchParamtersObj = UserInputSearchFactory::getSetterBy('QuickSearchBand',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria($request);
			}
			elseif($advanceSearchType=='AS')
			/* Direct Search : Advance Search is performed */
			{
				$SearchParamtersObj = UserInputSearchFactory::getSetterBy('AdvanceSearch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria($request);
			}
			elseif($advanceSearchType=="NME")
			{
				$SearchParamtersObj = UserInputSearchFactory::getSetterBy('NewMatchesMailer',$loggedInProfileObj);
				$paramArr["logic_used"] = $request->getParameter("logic_used");
				$paramArr["relax"] = $request->getParameter("relax");
				$paramArr["sent_date"] = $request->getParameter("sent_date");
                                $SearchParamtersObj->getSearchCriteria($paramArr);
			}
			elseif($AppSearch==1)
			/* Direct Search : App Search is performed */
			{
				$SearchParamtersObj = UserInputSearchFactory::getSetterBy('AppSearch',$loggedInProfileObj);
				$SearchParamtersObj->getSearchCriteria($request);
			}
			else
			/* if search.php is called directly */
			{
				$SearchUtilityObj = new SearchUtility;
				$SearchParamtersObj = $SearchUtilityObj->directSeachUrl($loggedInProfileObj);
				unset($SearchUtilityObj);
			}
		}

		/*This function is called to set the parameters passed in the url*/
		$SearchUtilityObj = new SearchUtility;
		$SearchUtilityObj->setParametersPassedThroughUrl($request,$SearchParamtersObj);

		/***add india to search criteria, if indian city is choosen ****/
		$cities     = $SearchParamtersObj->getCITY_INDIA();
		$countryRes = $SearchParamtersObj->getCOUNTRY_RES();
		if($cities && !$countryRes && $cities!='DONT_MATTER')
		{
			$cityArr = explode(",",$cities);
			foreach($cityArr as $k=>$v)
			{
				if(CommonUtility::isIndia($v))
					$india=1;
				else
					$nonIndia=1;
			}
			if($india && !$nonIndia)
			{
				$countryStr = 51;
				$SearchParamtersObj->setCOUNTRY_RES($countryStr);
			}
		}
		/***add india to search criteria, if indian city is choosen ****/

		return $SearchParamtersObj;
	}
}
