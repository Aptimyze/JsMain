<?php

/* * ******************************************************************************
 * Library Class to find similar profiles & their details
 *
 * @Description     This class is used to find similar profiles
 * @author     	    Akash Kumar
 * ****************************************************************************** */

class ViewSimilarProfile {

        private $tupleName = "VIEW_SIMILAR";
        
        //mapping of platform type to default pic size for displayed pic in vsp listings
        public static $defaultPicSize = array("PC"=>"ProfilePic450Url",
                                            "JSMS"=>"MobileAppPicUrl",
                                            "IOS"=>"MobileAppPicUrl",
                                            "APP"=>"MobileAppPicUrl");
        
        private $reverseParamsMapping = array('PARTNER_MSTATUS'=>'','PARTNER_CITYRES','PARTNER_COUNTRYRES','PARTNER_HANDICAPPED','PARTNER_RELIGION','PARTNER_CASTE','LPARTNER_LAGE','HPARTNER_LAGE','LPARTNER_HAGE','HPARTNER_HAGE','LPARTNER_LHEIGHT','HPARTNER_LHEIGHT','LPARTNER_HHEIGHT','HPARTNER_HHEIGHT','PARTNER_ELEVEL_NEW','PARTNER_INCOME');
        /**
         * Function to find similar profile IDs
         * @param: $profileObj Profile Object of viwed profile
         * @param: $loginProfile LoggedIn Profile Object for logged in viewer
         */
        public function getSimilarProfiles($profileObj, $loginProfile,$fromPage="") {
                $viewed = $profileObj->getPROFILEID();
                $viewedGender = $profileObj->getGENDER();
                $viewer = $loginProfile->getPROFILEID();
		$viewerAge = $loginProfile->getAGE();
                $suggAlgoMinimumNoOfContactsRequired = viewSimilarConfig::$suggAlgoMinimumNoOfContactsRequired;
                //$suggAlgoMinimumNoOfContactsRequired=0; // to check directly for Normal search operation
                $suggAlgoScoreConst = viewSimilarConfig::$suggAlgoScoreConst;
                $suggAlgoNoOfResults = viewSimilarConfig::$suggAlgoNoOfResults_Mobile;
                $suggAlgoNoOfResultsNoFilter = viewSimilarConfig::$suggAlgoNoOfResultsNoFilter;
                //view Opposite Gender
                if ($viewedGender == 'M') {
                        $viewedGender = 'MALE';
                        $viewedOppositeGender = 'FEMALE';
                } elseif ($viewedGender == 'F') {
                        $viewedGender = 'FEMALE';
                        $viewedOppositeGender = 'MALE';
                }
                //Store Object
                $similarProfileObj = new viewSimilar_CONTACTS_CACHE_LEVEL();
                $ContactsRecordsObj = new ContactsRecords();
                $ignoredProfileObj = new IgnoredProfiles();
                // contacts viewed
                $contactsViewed = $similarProfileObj->getViewedProfiles($viewedGender, $viewed);
         
                //track no of contacts for each profile
                /*$shardToAppend = ($viewed%3)+1;
                $dateHourToAppend = date('m-d', time());
                $noOfResultsToStore = sizeof($contactsViewed);
                JsMemcache::getInstance()->hIncrBy("ECP_CL_CONTACTS_COUNT_".$shardToAppend,$dateHourToAppend."__".$noOfResultsToStore,1);*/
                
                // ENOUGH contacts viewed check
                if (sizeof($contactsViewed) >= $suggAlgoMinimumNoOfContactsRequired) {
                        $suggProfAlgo = 'contacts';
                        $viewedContactsStr = implode(",", $contactsViewed);
                        // Get Receiver
                        $WhereArr = array('SENDER' => $viewer);
                        $contacts1 = $ContactsRecordsObj->getResultSet("RECEIVER", $WhereArr);
                        if (is_array($contacts1)) {
                                foreach ($contacts1 as $values) {
                                        $contactsViewer[$values['RECEIVER']] = 1;
                                }
                        }
                        // get Sender
                        $WhereArr = array('RECEIVER' => $viewer);
                        $contacts2 = $ContactsRecordsObj->getResultSet("SENDER,TYPE", $WhereArr);
                        if (is_array($contacts2)) {
                                foreach ($contacts2 as $values) {
                                    $contactsViewer[$values['SENDER']] = 1;

                                }
                        }
                        // contacts viewed
$profileObj->getDetail("","","USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
						$viewedAge = $profileObj->getAGE();

						$AgeViewed =  $this->AgeInterval($viewedOppositeGender,$viewedAge,$viewerAge);
                                                $whereParams = $this->getWhereParamsForReverseDpp($viewedOppositeGender,$loginProfile);
                                                $whereParams['lage']=$AgeViewed['lAge'];
						$whereParams['hage']=$AgeViewed['hAge'];
                        $resultTemp = $similarProfileObj->getSuggestedProf($viewedOppositeGender, $viewedContactsStr, $whereParams);
                        $suggestedProf = $resultTemp['suggestedProf'];
                        $constantVal = $resultTemp['constantVal'];
						$priority = $resultTemp['priority'];
                        // contacts viewed 
                        foreach ($contactsViewed as $key => $val) {
                                unset($intersect1);
                                if (is_array($suggestedProf[$val]) && is_array($contactsViewer)) {
                                        foreach ($suggestedProf[$val] as $prof)
                                                $intersect1[$prof] = 1;

                                        $inter = sizeof(array_intersect_key($intersect1, $contactsViewer));
                                } else {
                                        $inter = 0;
                                }
                                $scoreNum = $suggAlgoScoreConst + $inter;
                                $scoreDen = sizeof($contactsViewer) + sizeof($suggestedProf[$val]) - $scoreNum + $suggAlgoScoreConst;
                                $scoreDen = sqrt($scoreDen);

                                if ($scoreDen != 0)
                                        $scoreViewed[$val] = $scoreNum / $scoreDen;
                        }

                        //GET IGNORED LIST
                        $ignoredList = $ignoredProfileObj->ifProfilesIgnored('0',$viewer, 1);
                        if (is_array($suggestedProf)) {
                                foreach ($suggestedProf as $key => $value) {
                                        foreach ($value as $k => $v) {
                                                if ($contactsViewer[$v] != 1 && $ignoredList[$v] != 1)
                                                        $scores[$v] = 0;
                                        }
                                }

                                foreach ($suggestedProf as $key => $value) {
                                        foreach ($value as $k => $v) {
                                                if ($contactsViewer[$v] != 1 && $ignoredList[$v] != 1) {
                                                        $score = $constantVal[$key][$k] * $scoreViewed[$key]+10000/$priority[$key][$k];
                                                        $scores[$v] += $score;
                                                }
                                        }
                                }

                                arsort($scores);

                                $i = 0;
                                foreach ($scores as $s => $x) {
                                        if ($i++ < $suggAlgoNoOfResultsNoFilter)
                                                $finalScores[] = $s;
                                        else
                                                break;
                                }
                        }
                        //TRACK FOR ZERO
                        if (sizeof($finalScores) == 0)
                                $this->trackContactsAlgoZeroResults($viewer, $viewed);  // TO BE TRACKED
                }
                else {
                        //Normal Search
                        $loggedIn = 1;
                        $includeCaste = 2;
                        $includeAwaitingContacts = 1;
                        $finalScores = $this->getSimilarProfilesFromSearch($loggedIn, $viewed, $viewedGender, $includeCaste, $includeAwaitingContacts, $suggAlgoNoOfResultsNoFilter, $viewer);
                }
                //TRACK FOR hit on algo
                $this->trackSimilarProfilesAlgo('wapConfirmation');
                if(sizeof($finalScores)==0)
                        $this->trackZeroResultsForSimilar($viewer,$viewed);
                else                
                        $SimilarProfileDetails = $this->getSimilarProfilesDetails($finalScores, $viewer);
                unset($similarProfileObj);
                
                if($fromPage=="fromViewSimilar")
					return $finalScores;
				else
					return $SimilarProfileDetails;
        }
        
        /**
         * Function to return profileIds of similar profiles from memcache
         * @param: $profileObj Profile Object of viwed profile
         * @param: $loginProfile LoggedIn Profile Object for logged in viewer
         */
        public function getSimilarProfilesFromMemcache($viewed, $viewer,$offset=0) {
                $memObject=JsMemcache::getInstance();
                if($memObject->get('similar-'.$viewed.$viewer)){
                        $profileIds = $memObject->get('similar-'.$viewed.$viewer);
                        if($profileIds[$offset])
                                return  $profileIds[$offset];
                        else    
                                return null;
                }
                else{
                        
                        $viewedObj=Profile::getInstance("",$viewed);
                        $viewedObj->getDetail($viewed, "PROFILEID","*");

                        $viewerObj=LoggedInProfile::getInstance('newjs_master');
                        $viewerObj->getDetail($viewerObj->getPROFILEID(), "PROFILEID","*");
                        $profileIdArray=$this->getSimilarProfiles($viewedObj,$viewerObj,"fromViewSimilar");
                        $memObject->set('similar-'.$viewed.$viewerObj->getPROFILEID(),$profileIdArray);
                        if($profileIdArray[$offset])
                                return $profileIdArray[$offset];
                        else
                                return null;
                        
                }
                return null;
        }
        
        /**
         * Function to search similar profile for user as NORMAL SEARCH based on Gender, age etc
         * @param $loggedIn Profile Object of viwed profile
         * @param $viewed LoggedIn Profile Object for logged in viewer
         * @param $viewedGender Gender of Viewed Profile
         * @param $includeCaste Whether to include caste or not
         * @param $includeAwaitingContacts Whether to include awaiting contacts or not 
         * @param $suggAlgoNoOfResults No. of results to be given
         * @param $viewer Optional Field as profile ID
         * @return 3 similar profiles 
         */
        function AgeInterval($viewedOppositeGender,$viewedAge,$viewerAge)
        {
        	if($viewedOppositeGender == MALE){                
        		$viewerAgeMin = $viewerAge - 5;
                $Age['lAge'] = min($viewerAgeMin,$viewedAge);
                $Age['hAge'] = max($viewedAge,$viewerAge);
            }
           	else {
           		$viewedAgeMax = $viewedAge + 5;
                $Age['lAge'] = min($viewedAge,$viewerAge);
                $Age['hAge'] = max($viewedAgeMax,$viewerAge);
            }
            return $Age;
        }

        function getSimilarProfilesFromSearch($loggedIn, $viewed, $viewedGender, $includeCaste, $includeAwaitingContacts, $suggAlgoNoOfResults, $viewer = '') {
                $profileObj = Profile::getInstance("newjs_masterRep", $viewed);
                $row = $profileObj->getDetail($viewed, "PROFILEID","*");
                
                if ($row) {
                        $AgeGroupSuggAlgo = viewSimilarConfig::getFieldLabel('AgeGroupSuggAlgo', '', 1);
                        $allHindiMtongues = FieldMap::getFieldLabel('allHindiMtongues', '', 1);
                        $allMarriedMstatus = FieldMap::getFieldLabel('allMarriedMstatus', '', 1);
                        if ($includeCaste == 2) {//from logged in case
                                if ($row['MSTATUS'] != 'N') {
                                        $includeCaste = 0; //dont consider caste for profiles whose MSTATUS != 'N'
                                }
                        }


                        if (in_array($row['MTONGUE'], $allHindiMtongues)) {
                                $result['MTONGUE'] = $allHindiMtongues; //if mtongue belongs to any hindi community, then change mtongue value to 'all-hindi'
                        } else
                                $result["MTONGUE"][] = $row['MTONGUE'];

                        $result['RELIGION'][] = $row['RELIGION'];

                        if ($row['GENDER'] == 'M')
                                $age = $AgeGroupSuggAlgo["MALE"][$row['AGE']];
                        if ($row['GENDER'] == 'F')
                                $age = $AgeGroupSuggAlgo["FEMALE"][$row['AGE']];

                        if (!$age) {
                                if ($age >= 36 && $row['GENDER'] == 'M')
                                        $result['AGE'] = $AgeGroupSuggAlgo["MALE"]['MAX'];
                                elseif ($age >= 34 && $row['GENDER'] == 'F')
                                        $result['AGE'] = $AgeGroupSuggAlgo["FEMALE"]['MAX'];
                        }
                        $age = explode(",", $age);

                        $result['LAGE'] = $age[0];
                        $result['HAGE'] = $age[1];

                        if ($row['MSTATUS'] == 'N')
                                $result['MSTATUS'][] = 'N';
                        else
                                $result['MSTATUS'] = $allMarriedMstatus;
                        if (($includeCaste == 1) || ($loggedIn == 1 && $includeCaste == 2 && $result['MSTATUS'][0] == 'N')) {
                                $groupObj = new RevampCasteFunctions();
                                if ($groupObj->isPartOfGroup($row['CASTE']) == 1)
                                        $result['CASTE'] = explode(",", $groupObj->showGroupMembers($row['CASTE']));
                                else
                                        $result['CASTE'][] = $row['CASTE'];
                        } else
                                $result['CASTE'][] = '0';

                        $suggAlgoIncomeFilter;
                        if ($row['GENDER'] == 'M')
                                $suggAlgoIncomeFilter = $row['INCOME'];
                        elseif ($row['GENDER'] == 'F')
                                $suggAlgoIncomeFilter = '';

                        if ($viewedGender == 'MALE')
                                $genderVal = 'M';
                        elseif ($viewedGender == 'FEMALE')
                                $genderVal = 'F';

                      
                                $skipClusters = 1;
                                $limitedSearchResults = $suggAlgoNoOfResults;
                                $skipRelaxation = 1;
                                $viewSimilarFromProfilePage = 1;
                                $suggAlgoIncludeAwaitingContacts = $include_awaiting_contacts;
                                $suggAlgoLoginStatus = $loggedIn;
                                $paramArr["GENDER"] = $genderVal;
                                $paramArr["RELIGION"] = $result['RELIGION'];
                                $paramArr["CASTE"] = $result['CASTE'];
                                $paramArr["MTONGUE"] = $result['MTONGUE'];
                                $paramArr["LAGE"] = $result['LAGE'];
                                $paramArr["HAGE"] = $result['HAGE'];
                                $paramArr["HAVEPHOTO"] = 'Y';
                                $paramArr["MANGLIK"] = '';
                                $paramArr["MSTATUS"] = $result['MSTATUS'];
                                $paramArr["IS_VSP"] = 1;
                                
                                if(SearchConfig::$VspWithoutSolr){
                                    $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master', $viewer);
                                    if($paramArr["GENDER"]=='M'){
                                        $reverseParams = SearchConfig::$reverseParamsFemaleLoggedIn;
                                    }
                                    else{
                                        $reverseParams = SearchConfig::$reverseParamsMaleLoggedIn;
                                    }

                                    $reverseCriteria = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$loggedInProfileObj);
                                    $reverseCriteria->getSearchCriteria();
                                    foreach($reverseParams as $k=>$v)
                                    {
                                        eval('$tempVal = $reverseCriteria->get'.$v.'();');
                                        if($tempVal)
                                            eval('$paramArr['.$v.']='.$tempVal.';');
                                    }
                                }

                                $results = $this->suggestedAlgoSearch($paramArr, $viewer);
                        if ($results)
                                $results = array_slice(explode(",", $results), 0, $suggAlgoNoOfResults);

                        return $results;
                }
        }

        /**
         * function to return Profile details for similar profiles IDs given as parameter
         * @param $userList List of IDs of Similar profiles
         * @param $pid Profile ID of viewer
         * @return array of tuple object of profile details
         */
        public function getSimilarProfilesDetails($userList, $pid) {
                if ($userList) {
                        foreach ($userList as $key => $Pvalue) {
                                $finalUserList["VIEW_SIMILAR"][$Pvalue] = array("PROFILEID" => $Pvalue);
                        }
                }
                
                $profileObj = LoggedInProfile::getInstance('newjs_master');
                $tupleService = new TupleService();
                $tupleService->setLoginProfileObj($profileObj);
                $tupleService->setLoginProfile($pid);
                $tupleFields = $tupleService->getFields($this->tupleName);
                $tupleService->setProfileInfo($finalUserList, $tupleFields);
                $similarProfileDetail = $tupleService->getVIEW_SIMILAR();
                $loginObj = new Tuple();
                foreach ($similarProfileDetail as $pKey => $pValue) {
                        $pValue->LAST_LOGIN_DT = $loginObj->getLastLogin($pValue->getLAST_LOGIN_DT());
                
                        if(strlen($pValue->getUSERNAME())>8)
                        {
                                $username=substr($pValue->getUSERNAME(),0,6)."..";
                                $pValue->setUSERNAME($username);
                        }
                }
                
                return $similarProfileDetail;
        }

        /**
         * This function is used to insert an entry into the table MIS.SIMILAR_PROFILES_ZERO_RESULTS whenever the similar profiles algo returns zero results.
         * @param - $viewer - user whose viewing a profile
         * @param - $viewed - user whose profile is being viewed
         * */
        function trackZeroResultsForSimilar($viewer, $viewed) {
                $trackObj = new MIS_SIMILAR_PROFILES_ZERO_RESULTS();
                $result = $trackObj->trackZeroResultsForSimilar($viewer,$viewed);
         }

        /**
         * This function is used to track the no of hits sent to each similar profiles algo in 1 day.
         * @param - $algo - name of the algo
         * @param - $db - database connection
         * */
        function trackSimilarProfilesAlgo($algo) {
                $trackObj = new MIS_TRACK_SIMILAR_PROFILES_ALGO();
                $result = $trackObj->trackSimilarProfilesAlgoUpdate($algo);
                if ($result == 0) {
                        $result = $trackObj->trackSimilarProfilesAlgoInsert($algo);
                }
                unset($trackObj);
        }

        /**
         * This function is used to insert an entry into the table MIS.CONTACTS_ALGO_ZERO_RESULTS whenever the similar profiles algo returns zero results.
         * @param - $viewer - user whose viewing a profile
         * @param - $viewed - user whose profile is being viewed
         * */
        function trackContactsAlgoZeroResults($viewer, $viewed) {
                $trackObj = new MIS_CONTACTS_ALGO_ZERO_RESULTS();
                $result = $trackObj->trackContactsAlgoZeroResultsInsert($viewer, $viewed);
                unset($trackObj);
        }

        /**
         * This function is used search similar profile based on normal search
         * @param - $paramArr - user whose viewing a profile
         * @param - $pid - Optional field for profile ID
         * @return - $output - List of profile IDs
         * */
        function suggestedAlgoSearch($paramArr, $pid = '') {
                $SearchParametersObj = new SearchBasedOnParameters;
                $SearchParametersObj->getSearchCriteria($paramArr);
                $SearchParametersObj->setNoOfResults(viewSimilarConfig::$suggAlgoNoOfResultsNoFilter);

                if ($pid) {
                        $SearchUtilityObj = new SearchUtility;
                        $noAwaitingContacts = 1;
                        $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master', $pid);
                        

                        $SearchParametersObj->setWhereParams(SearchConfig::$searchWhereParameters.",".SearchConfig::$membersLookingForMeWhereParameters);
                        $SearchParametersObj->setRangeParams(SearchConfig::$searchRangeParameters.",".SearchConfig::$membersLookingForMeRangeParameters);

                        $ageToSet = $this->AgeInterval($loggedInProfileObj->getGENDER(),$profileObj->getAGE(),$loggedInProfileObj->getAGE());    
                        $SearchParametersObj->setLAGE($ageToSet['lAge']);
                        $SearchParametersObj->setHAGE($ageToSet['hAge']);
                        
                        //Call VSP from different URL
                        $SearchParametersObj->setIS_VSP(1);
                        $SearchUtilityObj->removeProfileFromSearch($SearchParametersObj, 'spaceSeperator', $loggedInProfileObj, '', $noAwaitingContacts);
                }

                $SearchServiceObj = new SearchService;
                $respObj = $SearchServiceObj->performSearch($SearchParametersObj, "onlyResults");
                if ($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
                        $output = implode(",", $respObj->getSearchResultsPidArr());
                return $output;
        }
        
        /*
         *function for showing similar profiles of users using old algo
         * @param - contacted by - ProfileID
         * @param - contacted - ProfileChecksum
         * @param - contactedByGender - Gender of contacted one - M/F
         */

        public function viewOldSimilarProfileResults($profileObj, $loginProfile, $limit = "20",$mobOrPC="PC") {
                
                include_once(JsConstants::$docRoot."/P/search.inc");
        
                
                $data["PROFILEID"] = $loginProfile->getPROFILEID();
                $data["GENDER"] = $loginProfile->getGENDER();
                $contacted = $profileObj->getPROFILEID();
                $contacted = createChecksumForSearch($contacted);
                $from = "single_contact_aj";
                $scriptname = "view_similar_profile.php";
                $similarProfiles = revamp_get_other_relevant_pro($data, $contacted, $from, $scriptname, $limit, 1, "hide");
		$similarProfilesFromOldAlgo = "";
                while (($myrow = mysql_fetch_array($similarProfiles)) && $count_i < $limit) {
                        $similarProfilesFromOldAlgo[]=$myrow["PROFILEID"];
                        $count_i++;
                }
		if(MobileCommon::isMobile())
			$mobOrPC= 'mob';
		else
			$mobOrPC="PC";

		if(!$similarProfilesFromOldAlgo)
			return NULL;
                if($mobOrPC=="PC")
                        return $similarProfilesFromOldAlgo;
                else
                        return $this->getSimilarProfilesDetails($similarProfilesFromOldAlgo, $loginProfile->getPROFILEID());
        }

        /*
         *function for transforming vsp response to search like response
         * @param - vspArray,$contactedUsername,$similarPageShow,$userGender
         * @return - new array
         */
        public function transformVSPResponseForPC($vspArray,$contactedUsername,$similarPageShow,$userGender,$stype='V',$loggedInProfileObj)
        {
            if($userGender=="She")
                $gender = 'F';
            else
                $gender = 'M';
            $key = 0;
            $nameOfUserObj = new NameOfUser;
            $nameData = $nameOfUserObj->getNameData($loggedInProfileObj->getPROFILEID());
            foreach($vspArray as $profileid=>$detailsArray)
            {
                //$key = $detailsArray["OFFSET"]-1;
                foreach(viewSimilarConfig::$SearchToVSPResponseMappingArr as $searchField=>$vspField)
                {
                    if($searchField == "age")
                    {
                         $jspcVSPArray["profiles"][$key][$searchField] = $detailsArray[$vspField]." yrs";
                    }
                    else if($searchField == "photo")
                        $jspcVSPArray["profiles"][$key][$searchField]= PictureFunctions::mapUrlToMessageInfoArr($detailsArray[$vspField],ViewSimilarProfile::$defaultPicSize["PC"],$detailsArray["PHOTO_REQUESTED"],$gender);
                    else if($searchField == "subscription_icon")
                    {
  			$searchApiObj = new SearchApiStrategyV1();
                        $jspcVSPArray["profiles"][$key][$searchField]= $searchApiObj->handlingSpecialCasesForSearch($searchField,$detailsArray[$vspField],$detailsArray["PHOTO_REQUESTED"],$gender);                        
                        unset($searchApiObj);
                    }  
                    else if($searchField == "name_of_user")
                    {
  			if(is_array($nameData)&& $nameData[$loggedInProfileObj->getPROFILEID()]['DISPLAY']=="Y" && $nameData[$loggedInProfileObj->getPROFILEID()]['NAME']!='')
                        {
                                $name = $nameOfUserObj->getNameStr($detailsArray[$vspField],$loggedInProfileObj->getSUBSCRIPTION());
                        }

                        $jspcVSPArray["profiles"][$key][$searchField]=$name;
                    }  
                    elseif(in_array($searchField,array("pg_college","college","company_name")))
                    {
                         if($detailsArray[$vspField]!= ''){
                              $searchApiObj = new SearchApiStrategyV1();
                              $jspcVSPArray["profiles"][$key][$searchField]= $detailsArray[$vspField];      
                         }else{
                              $jspcVSPArray["profiles"][$key][$searchField]= '';       
                         }
                    }else
                        $jspcVSPArray["profiles"][$key][$searchField] = $detailsArray[$vspField];
                }
                $params = array("SHORTLIST"=>$detailsArray["BOOKMARKED"],
                        "PAGE"=>array("stype"=>$stype),
                        "STYPE"=>$stype,
                        "IGNORE"=>0,
                        );
                $jspcVSPArray["profiles"][$key]['buttonDetailsJSMS'] = ButtonResponseFinal::getListingButtons("S","P","","",$params);
            ++$key;
            }
            if($similarPageShow==1)
            {
                $jspcVSPArray["pageSubHeading"] = "Similar profiles to ".$contactedUsername." you may wish to Express Interest in";
                $jspcVSPArray["noresultmessage"] = "";
            }
            else
            {
                $jspcVSPArray["pageSubHeading"] = "";
                $jspcVSPArray["noresultmessage"] = "<span class='bold f28'>0 </span>"."Similar profiles to ".$contactedUsername;
            }
            $jspcVSPArray["listType"] = "vsp";
            $jspcVSPArray["stype"] = $stype;
            $jspcVSPArray["responseTracking"] = JSTrackingPageType::SEARCH;
            
            
            if(MobileCommon::isDesktop())
                $jspcVSPArray["defaultImage"] = PictureFunctions::getNoPhotoJSMS($gender,ViewSimilarProfile::$defaultPicSize["PC"]);
            return $jspcVSPArray;
        }
        
        
        private function getWhereParamsForReverseDpp($viewerGender,$loggedInProfileObj){
            if($viewerGender == 'M')
                $reverseParams = SearchConfig::$reverseParamsFemaleLoggedIn;
            else
                $reverseParams = SearchConfig::$reverseParamsMaleLoggedIn;
            
            $reverseCriteria = PredefinedSearchFactory::getSetterBy('MembersLookingForMe',$loggedInProfileObj);
            $reverseCriteria->getSearchCriteria();
            foreach($reverseParams as $k=>$v)
            {
                    eval('$tempVal = $reverseCriteria->get'.$v.'();');
                    if($tempVal){
                            $tempVal = str_replace(',99999', '', $tempVal);
                            $whereParams[$v]= "'".$tempVal."'";
                    }
            }
            
            return $whereParams;
        }

}
?>

