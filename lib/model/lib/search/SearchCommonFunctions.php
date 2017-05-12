<?php
/*
* This class will handle common functions related to search.
*/
class SearchCommonFunctions
{
	public static function getProfilesBasedOnParams($paramArr,$callingSource,$nonSymfony='',$SITE_URL='',$noOfResults='')
	{
		if($nonSymfony)
		{
			$url = $SITE_URL."/search/perform";

			$postParams="callingSource=".$callingSource;
			if($paramArr["LAGE"])
                	{
                        	if(is_array($paramArr["LAGE"]))
                        	        $lage = implode(",",$paramArr["LAGE"]);
                        	else
                        	        $lage = $paramArr["LAGE"];
				$postParams = $postParams."&lage=".$lage;
                	}
                	if($paramArr["HAGE"])
                	{
                        	if(is_array($paramArr["HAGE"]))
                                	$hage = implode(",",$paramArr["HAGE"]);
                        	else
                                	$hage = $paramArr["HAGE"];
				$postParams = $postParams."&hage=".$hage;
                	}
                	if($paramArr["HAVEPHOTO"])
                	{
                        	if(is_array($paramArr["HAVEPHOTO"]))
                                	$havephoto = implode(",",$paramArr["HAVEPHOTO"]);
                        	else
                                	$havephoto = $paramArr["HAVEPHOTO"];
				$postParams = $postParams."&havePhoto=".$havephoto;
                	}
			if($paramArr["GENDER"])
                	{
                        	if(is_array($paramArr["GENDER"]))
                                	$gender = implode(",",$paramArr["GENDER"]);
                        	else
                                	$gender = $paramArr["GENDER"];
				$postParams = $postParams."&gender=".$gender;
                	}
			if($paramArr["PRIVACY"])
                	{
                        	if(is_array($paramArr["PRIVACY"]))
                                	$privacy = implode(",",$paramArr["PRIVACY"]);
                        	else
                                	$privacy = $paramArr["PRIVACY"];
				$postParams = $postParams."&privacy=".$privacy;
                	}
			if($paramArr["PHOTO_DISPLAY"])
                	{
                        	if(is_array($paramArr["PHOTO_DISPLAY"]))
                                	$photo_disp = implode(",",$paramArr["PHOTO_DISPLAY"]);
                        	else
                                	$photo_disp = $paramArr["PHOTO_DISPLAY"];
				$postParams = $postParams."&photo_display=".$photo_disp;
                	}
			if($paramArr["SORT_LOGIC"])
			{
				$postParams = $postParams."&sort_logic=".$paramArr["SORT_LOGIC"];
			}

			$returnArr =  SearchCommonFunctions::performErrorHandling($url,$postParams,"array");
                	return $returnArr;
		}
		else
		{
			$SearchParametersObj = new SearchBasedOnParameters;
			$SearchParametersObj->getSearchCriteria($paramArr);
			if($noOfResults)
	                       	$SearchParametersObj->setNoOfResults($noOfResults);
			$SearchServiceObj = new SearchService('solr','array',1);
                        $responseObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults");
			$returnArr["TOTAL_SEARCH_RESULTS"] = $responseObj->getTotalResults();
                        $returnArr["SEARCH_RESULTS"] = $responseObj->getSearchResultsPidArr();
			return $returnArr;
		}
	}

	/*
	* This function will return  number of partner matches 
	* profileid integer id for which partner matches need to be calculated.
	* callingSource string this will identify number of results to be shown , awaiting contacts to be shown of not (exmple 'fto_offer')
	* sortType string sorting logic to be used. All search sort types are defined in 'SearchSort.class.php'
	* onlyPhotoProfile char only profiles with photos(Y) need to be shown or not. Pass this variable as blank if photos condition is not relevant.
	* outputFormat Default is array 
	* return array partner matches ids/with search count.
	*/
	static public function getDppMatches($profileId,$callingSource,$sortType='',$onlyPhotoProfile='Y',$outputFormat='array')
	{
		$SITE_URL = sfConfig::get("app_site_url");
		$profileChecksum = md5($profileId)."i".$profileId;

		$url = $SITE_URL."/search/partnermatches";

		$postParams="profileChecksum=".$profileChecksum."&callingSource=$callingSource";
		if($sortType)
			$postParams.="&sort_logic=$sortType";
		if($onlyPhotoProfile=="Y")
			$postParams.="&havePhoto=Y";
		$returnArr =  SearchCommonFunctions::performErrorHandling($url,$postParams,$outputFormat);
		return $returnArr;
	}

	/*
	* This function finds the best profile from the given set of profiles on the basis of sorting algos
	* @params - 1) array of profileids, 2) calling source 3) sort type 4) optional
	* @return array partner matches ids/with search count
	*/
	public static function findMostSortTypeProfile($profileidArr,$callingSource,$sortType='',$outputFormat='array')
	{
		$SITE_URL = sfConfig::get("app_site_url");
		$url = $SITE_URL."/search/perform";
		$postParams="profileList=".implode(",",$profileidArr)."&callingSource=$callingSource";
		if($sortType)
                        $postParams.="&sort_logic=$sortType";

		$returnArr =  SearchCommonFunctions::performErrorHandling($url,$postParams,$outputFormat);
                return $returnArr;
	}

	/*
	* This function performs error handling
	* @params - 1) url, 2) post parameters 3) output format
	* @return array partner matches ids/with search count
	*/
	private static function performErrorHandling($url,$postParams,$outputFormat)
	{
		$timesTried = 5;

                while($timesTried)
                {
                        $output = CommonUtility::sendCurlPostRequest($url,$postParams);
                        if($output)
                        {
                                $outputArr = explode("#",$output);
                                $resMatchesString = $outputArr[1];
                                $totalCnt = $outputArr[0];
                                if(preg_match('/^(\d+,)*\d+$/', $resMatchesString))
                                {
                                        $timesTried=0;
                                        if($outputFormat=='array')
                                        {
                                                $arr = explode(",",$resMatchesString);
                                                $returnArr["TOTAL_SEARCH_RESULTS"] = $totalCnt;
                                                $returnArr["SEARCH_RESULTS"] = $arr;
                                                return $returnArr;
                                        }
                                        else if ($outputFormat === "comma") {
                                          return array("SEARCH_RESULTS" => $resMatchesString, "TOTAL_SEARCH_RESULTS" => $totalCnt);
                                        }
                                }
                                else
                                {
                                        $timesTried--;
                                }
                        }
                        else
                                $timesTried=0;
                }
                return NULL;
	}

	/*
	This function is used to get the number of results that are to be displayed on the search page
	@param - search parameters object (optional but should be passed wherever possible)
	@return - count
	*/
	public static function getProfilesPerPageOnSearch($SearchParametersObj="")
	{
		if($SearchParametersObj && $SearchParametersObj->getNoOfResults()==SearchConfig::$matchMaxLimit)
			return $SearchParametersObj->getNoOfResults();

		if($SearchParametersObj && $SearchParametersObj->getNoOfResults()==viewSimilarConfig::$suggAlgoNoOfResults_Mobile)
			return $SearchParametersObj->getNoOfResults();

                if(MobileCommon::isApp()=='A')
                    return SearchConfig::$profilesPerPageOnApp;
                if(MobileCommon::isNewMobileSite() || MobileCommon::isApp()=='I')
                    return SearchConfig::$profilesPerPageOnWapSite;
		if($SearchParametersObj && $SearchParametersObj->getNoOfResults())
			return $SearchParametersObj->getNoOfResults();
		else
			return SearchConfig::$profilesPerPage;
	}

	/**
	* This section will show the dpp matches.
	*/
	public static function getMyDppMatches($sort="",$loggedInProfileObj='',$limit='',$currentPage="",$paramArr='',$removeMatchAlerts="",$dontShowFilteredProfiles="",$twoWayMatches='',$clustersToShow='',$results_orAnd_cluster='',$notInProfiles='',$completeResponse = '', $verifiedProfilesDate = '',$removeShortlisted='',$showOnlineOnly='',$source='')
	{
                $searchEngine = 'solr';
                $outputFormat = 'array';
		$noAwaitingContacts=1;
		if(!$loggedInProfileObj)
	                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                if($twoWayMatches)
                    $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('TwoWayMatch',$loggedInProfileObj);
                else
                    $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('PartnerProfile',$loggedInProfileObj);
		if($dontShowFilteredProfiles)
			$SearchParamtersObj->setShowFilteredProfiles('N');
		if($limit)
			$SearchParamtersObj->setNoOfResults($limit);
                if($twoWayMatches)
                    $SearchParamtersObj->getSearchCriteria();
                else
                    $SearchParamtersObj->getDppCriteria('',$source);
                if($verifiedProfilesDate){
                    $SearchParamtersObj->setHVERIFY_ACTIVATED_DT($verifiedProfilesDate);
                    $SearchParamtersObj->setLVERIFY_ACTIVATED_DT('2001-01-01 00:00:00');
                }
		if($paramArr && is_array($paramArr))
		{
			foreach($paramArr as $k=>$v)
			{
				$SearchParamtersObj->{"set" . $k}($v);
			}
		}
                $SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
		$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$sort);
		$SearchUtilityObj =  new SearchUtility;
		if($removeShortlisted)
		{
				$shortlistObj							= new Bookmarks("newjs_masterRep");
				$condition["WHERE"]["IN"]["BOOKMARKER"] = $loggedInProfileObj->getPROFILEID();
				$notInProfiles = implode(array_keys($shortlistObj->getBookmarkedProfile($loggedInProfileObj->getPROFILEID(),$condition))," ");
				unset($shortlistObj);
		}
		$showOnlineArr = '';
		if($showOnlineOnly)
		{
			$ChatLibraryObj = new ChatLibrary(SearchConfig::getSearchDb());
			$showOnlineArr = $ChatLibraryObj->findOnlineProfiles(" ",$SearchParamtersObj);
			
		}
		if($notInProfiles)
			 $SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts,$removeMatchAlerts,$notInProfiles,$showOnlineArr);
		else
				$SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts,$removeMatchAlerts,'',$showOnlineArr);
		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
		if($completeResponse)
			return $responseObj;
		$arr['PIDS'] = $responseObj->getsearchResultsPidArr();
		$arr['CNT']  = $responseObj->getTotalResults();
                if($clustersToShow)
                    $arr['ClusterCount'] = $responseObj->getClustersResults();
		return $arr;
	}
        /**
         * set country india if city india present
         * @param type $cities
         * @param type $countryRes
         * @return int
         */
         public static function setCountryIfcityPresent($cities,$countryRes){
                $countryStr = '';
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
			}
		}
                if($countryStr == ""){
                        $countryStr = $countryRes;
                }
                return $countryStr;
         }
        /**
        * This section will give count for justJoinedMatches and top10 results
		* @return array containing count and ids info.
        */
        public static function getJustJoinedMatches($loggedInProfileObj='',$searchCriteria="CountOnly",$havePhotoCriteria="")
        {
                $searchEngine = 'solr';
				$noAwaitingContacts=1;
				
				$sort = SearchSortTypesEnums::justJoinedSortFlag;
                if(!$loggedInProfileObj)
                        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $SearchParamtersObj = PredefinedSearchFactory::getSetterBy('JustJoinedMatches',$loggedInProfileObj);
                $SearchParamtersObj->getSearchCriteria($searchCriteria);
                if($havePhotoCriteria!="")
                {
                	$SearchParamtersObj->setHAVEPHOTO("Y");
                }
                $countryStr = self::setCountryIfcityPresent($SearchParamtersObj->getCITY_INDIA(),$SearchParamtersObj->getCOUNTRY_RES());
                if($countryStr != ''){
                        $SearchParamtersObj->setCOUNTRY_RES($countryStr);
                }
                $SearchServiceObj = new SearchService($searchEngine);
                $SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj,"",$sort);
                $SearchUtilityObj =  new SearchUtility;
                $SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
                $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,'','','','',$loggedInProfileObj);

                $resultsArr = $responseObj->getResultsArr();
                $arr['PIDS'] = $responseObj->getsearchResultsPidArr();
                $arr['CNT']  = $responseObj->getTotalResults();                            
                return $arr;
        }


	/**
	* This section will show the dpp matches.
	*/
	public static function getMatchAlertsMatches($limit=2000,$currentPage=0,$profileid=null,$alertLogic='')
	{
		$limit = $limit?$limit:SearchConfig::$matchMaxLimit;
		$searchEngine = 'solr';
		$outputFormat = 'array';
		$noAwaitingContacts=1;
		if($profileid)
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master',$profileid);
		else
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$loggedInProfileObj->getDetail("","","AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE,INCOME");
		
		$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MatchAlertsSearch',$loggedInProfileObj);
		if($limit)
			$SearchParamtersObj->setNoOfResults($limit);
    
    //Get Reset Match Alert Count Params
    $request=sfContext::getInstance()->getRequest();
    $bResetMatchAlertCount = $request->getParameter("resetMatchAlertCount") === 1 ? true : false;
    $bResetMatchAlertCount = !$bResetMatchAlertCount && 1 === $request->getAttribute("resetMatchAlertCount") ? true : false;
    if ($bResetMatchAlertCount) {
      $SearchParamtersObj->storeLastVistTime();
    }
    
		$SearchParamtersObj->getSearchCriteria();
		$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
		$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj);
		$SearchUtilityObj =  new SearchUtility;
		$SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
		$date = $SearchParamtersObj->getAlertsDateConditionArr();
		$profileids = $responseObj->getsearchResultsPidArr();    
		
    if($alertLogic == MatchAlertLogicEnum::MATCHES_LAST_SENT){
      $arr['PIDS_NEW'] = $SearchParamtersObj->getLastSentProfiles($profileids);
    }else {//Match Since Last Seen
      $arr['PIDS_NEW'] = $SearchParamtersObj->getLastestProfiles($profileids);
    }
    
		$arr['PIDS'] = $profileids;
		$arr['TIME'] = $date;
		$arr['CNT']  = count($arr['PIDS']);
		$arr['CNT_NEW']  = count($arr['PIDS_NEW']);
		return $arr;
	}
	/**
	* This section will show the match of the matches.
	*/
	public static function getMatchofTheDay($profileid=null,$limit=10,$currentPage=0,$alertLogic='')
	{
		$limit = $limit?$limit:SearchConfig::$matchMaxLimit;
		$searchEngine = 'solr';
		$outputFormat = 'array';
		$noAwaitingContacts=1;
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master',$profileid);
		$loggedInProfileObj->getDetail("","","AGE,MSTATUS,RELIGION,CASTE,COUNTRY_RES,CITY_RES,MTONGUE,INCOME");
		
		$SearchParamtersObj = PredefinedSearchFactory::getSetterBy('MatchOfDay',$loggedInProfileObj);
		$SearchParamtersObj->setNoOfResults($limit);
    
	    //Get Reset Match Alert Count Params
	    $request=sfContext::getInstance()->getRequest();   
		$SearchParamtersObj->getSearchCriteria();
		$SearchServiceObj = new SearchService($searchEngine,$outputFormat,$showAllClustersOptions);
		$SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj);
		$SearchUtilityObj =  new SearchUtility;
		$SearchUtilityObj->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
		$results_orAnd_cluster = 'onlyResults';
		$responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,$results_orAnd_cluster,$clustersToShow,$currentPage,'',$loggedInProfileObj);
		$profileids = $responseObj->getsearchResultsPidArr();    
		//$arr["profiles"] = $responseObj->getResultsArr();
		$arr['PIDS'] = $profileids;
		$arr['TIME'] = $date;
		$arr['CNT']  = count($arr['PIDS']);
		//$arr['CNT_NEW']  = count($arr['PIDS_NEW']);
		return $arr;
	}
        public static function getOccupationMappingData($occupationArray = array()){
                $mappingOccupationData = array();
                if(!empty($occupationArray)){
                        $mappedArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",1,1);
                        $map = array();
                        foreach($mappedArr as $key=>$mappedOcc){
                                $map[$key] = explode(",", $mappedOcc);
                        }
                        foreach($occupationArray as $occupation){
                                foreach($map as $k=>$occupations){
                                        if(in_array($occupation, $occupations)){
                                                $mappingOccupationData = array_merge($mappingOccupationData,$occupations);
                                        }
                                }
                        }
                }
                $mappingOccupationData = array_unique($mappingOccupationData);
                return $mappingOccupationData;
        }
}
?>
