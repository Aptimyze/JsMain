<?php
/**
 * search actions.
 * ViewSimilarProfilesV1
 * Controller to send similar profiles id with their info data to app
 * @package    jeevansathi
 * @subpackage search
 * @author     Prashant Pal
 */
class ViewSimilarProfilesV1Action extends sfActions {

        const No_search_results_1 = "There are no profiles similar to ";
        const No_search_results_2 = " right now.";

        public function execute($request) {
                $photoDisplayType = "ProfilePic120Url";
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $pid = $loggedInProfileObj->getPROFILEID();
                $vspPage = $request->getParameter('vspPage');
                if($pid){
                    if(MobileCommon::isIOSApp() && $vspPage=='PD' && in_array($pid%4, array(2,3))){
                        $paramArray["profiles"] = null;
                        $paramArray[noresultmessage] = '';
                        $paramArray["no_of_results"] = 0;
                        $paramArray["result_count"] = "Similar Profiles 0";

                        $outputMsg = $paramArray;
                        $respObj = ApiResponseHandler::getInstance();
                        $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                        $respObj->setResponseBody($outputMsg);
                        $respObj->generateResponse();
                        return sfView::NONE;
                        die;
                    }
                $loggedInProfileObj->getDetail("", "", "USERNAME,AGE,GENDER,RELIGION,HEIGHT,CASTE,INCOME,MTONGUE,ENTRY_DT,HAVEPHOTO,SHOW_HOROSCOPE,COUNTRY_RES,BTYPE,COMPLEXION,EDU_LEVEL_NEW,OCCUPATION,MSTATUS,CITY_RES,DRINK,SMOKE,DIET,HANDICAPPED,MANGLIK,RELATION,HANDICAPPED,HIV,SUBSCRIPTION,BTIME,MOB_STATUS,LANDL_STATUS,ACTIVATED,INCOMPLETE");
                $viewerGender = $loggedInProfileObj->getGENDER();
                }
                elseif(!MobileCommon::isDesktop() && !(MobileCommon::isIOSApp() && $vspPage=='PD')) {
                        $context = sfContext::getInstance();
                        $context->getController()->forward("static", "logoutPage"); //Logout page
                        throw new sfStopException();
                }
                $inputValidateObj = ValidateInputFactory::getModuleObject('search');
                $respObj = ApiResponseHandler::getInstance();
                if ($request->getParameter("actionName") == "similarprofile" || $request->getParameter("actionName") == "ViewSimilarProfiles") {
                        $memObject=JsMemcache::getInstance();
                        $inputValidateObj->validateSimilarProfile($request);
                        $resp = $inputValidateObj->getResponse();
                        if ($resp["statusCode"] == ResponseHandlerConfig::$SUCCESS["statusCode"]) {
                                $viewedProfileChecksum = $request->getParameter('profilechecksum');
                                $viewedProfileID = JsCommon::getProfileFromChecksum($viewedProfileChecksum);

				if($viewedProfileID == "0") {
                                  $respObj = ApiResponseHandler::getInstance();
                                  $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                                  $respObj->generateResponse();
                                  return sfView::NONE;
                                }

                                $this->Profile = new Profile("newjs_masterRep");
                                $this->Profile->getDetail($viewedProfileID, "PROFILEID");
                                $viewedGender = $this->Profile->getGENDER();
                                //$viewSimilarProfileObj = new ViewSimilarPageProfiles($loggedInProfileObj, $this->Profile);
                                //View Similar Profile Object to set Search Criteria
                                if($pid){
                                    $modVal = 9;
                                    $loggedinMod = $loggedInProfileObj->getPROFILEID()%$modVal;
                                    $modResult =  array(1);
                                    if(JsConstants::$vspServer == 'live' && !MobileCommon::isDesktop() && in_array($loggedinMod,$modResult)){
                                      $viewSimilarProfileObj=new viewSimilarfiltering($loggedInProfileObj,$this->Profile,$removeFilters=1);
                                      if($viewerGender == 'M')
                                        $feedURL = JsConstants::$vspMaleUrl;
                                      else
                                        $feedURL = JsConstants::$vspFemaleUrl;
                                      $profileListObj = new IgnoredContactedProfiles();
                                      $ignoredContactedProfiles = $profileListObj->getProfileList($loggedInProfileObj->getPROFILEID(),'');
                                      $postParams = json_encode(array("PROFILEID"=>$pid,"PROFILEID_POG"=>$viewedProfileID,'removeProfiles'=>$ignoredContactedProfiles));
                                      $profilesList = CommonUtility::sendCurlPostRequest($feedURL,$postParams);
                                      if($profilesList == "Error") {
                                          $respObj = ApiResponseHandler::getInstance();
                                          $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                                          $respObj->generateResponse();
                                          return sfView::NONE;
                                      }
                                      $profileidsort = str_replace(","," ",$profilesList);
				      $viewSimilarProfileObj->profilesToShow = $profileidsort;
				      $viewSimilarProfileObj->setProfilesToShow($profileidsort);
                                    }
                                    else 
                                    {
                                          $viewSimilarProfileObj=new viewSimilarfiltering($loggedInProfileObj,$this->Profile);
                                          if(MobileCommon::isDesktop() || MobileCommon::isAndroidApp())
                                            $profileidsort = $viewSimilarProfileObj->getViewSimilarCriteria();
                                          else
                                            $profileidsort = $viewSimilarProfileObj->getViewSimilarCriteria("","ios");
                                    }
                                }
                                else{
                                   $viewSimilarProfileObj=new viewSimilarfiltering($loggedInProfileObj,$this->Profile);
                                  $profileidsort = $viewSimilarProfileObj->getViewSimilarCriteria($request->getParameter('searchid'),"ios");
                                  
                                }
                                $searchEngine = 'solr';
                                $outputFormat = 'array';
                                if(MobileCommon::isDesktop())
		                  $viewSimilarProfileObj->setNoOfResults(viewSimilarConfig::$suggAlgoNoOfResultsNoFilter);
                                else
		                  $viewSimilarProfileObj->setNoOfResults(viewSimilarConfig::$suggAlgoNoOfResults_Mobile);
                                $SearchServiceObj = new SearchService($searchEngine, $outputFormat, $showAllClustersOptions);
                                $responseObj = $SearchServiceObj->performSearch($viewSimilarProfileObj, $results_orAnd_cluster, $clustersToShow, $currentPage, $cachedSearch, $loggedInProfileObj);
                                $SearchDisplayObj = new SearchDisplay('', $photoDisplayType);
                                $resultsArray = $SearchDisplayObj->searchPageTemplateInfo($this->isMobile, $loggedInProfileObj, $responseObj);
                                $profileidsort = explode(" ", $profileidsort);

                                $paramArray = array("searchBasedParam" => null,
                                    "dppLinkAtEnd" => null,
                                    "myPageTitle" => null,
                                    "result_count" => "Similar Profiles",
                                    "no_of_results" => 0,
                                    "page_index" => "1",
                                    "searchid" => "",
                                    "sorting" => "T",
                                    "sortType" => "Relevance",
                                    "stype" => "WC",
                                    "defaultImage" => PictureFunctions::getNoPhotoJSMS($viewedGender),
                                    "next_avail" => "false",
                                    "relaxation_text1" => null,
                                    "relaxation_text2" => null,
                                    "relaxation_text_params" => null);
                                $l = 0;
                                if(MobileCommon::isDesktop())
                                    $paramArray["stype"]=  SearchTypesEnums::ViewSimilarDesktop;
                                if(MobileCommon::isAndroidApp())
                                    $paramArray["stype"]=  SearchTypesEnums::VIEW_SIMILAR_ANDROID;
                                if(MobileCommon::isIOSApp()){
                                    if($vspPage == 'PD')
                                        $paramArray["stype"]=  SearchTypesEnums::VIEW_SIMILAR_IOS_ON_PD;
                                    else
                                        $paramArray["stype"]=  SearchTypesEnums::VIEW_SIMILAR_IOS;
                                }
                                if(is_array($resultsArray))
                                {   
                                     foreach ($resultsArray as $k => $v) {
                                            if($resultsArray[$k]["CONTACT_SENT"]=='Y')
                                                    $resultsArray[$k]["CONTACT_STATUS"] = 'RI';
                                            else
                                                    $resultsArray[$k]["CONTACT_STATUS"] = 'N';
                                    }
                                }
                                $contactButtonObj = new SearchApiStrategyV1($responseObj);
                                if(MobileCommon::isAndroidApp())
                                    $fromVspAndroid=1;
                              	if($resultsArray)
                                	$button = $contactButtonObj->setSearchResults($loggedInProfileObj, "", "", $resultsArray,'',$fromVspAndroid);
                                $i = 0;
                                $nameOfUserObj = new NameOfUser;
                                $nameData = $nameOfUserObj->getNameData($loggedInProfileObj->getPROFILEID());
       	                        if(is_array($resultsArray))
                                { 
                                    foreach ($resultsArray as $k => $v) {
                                            $resultsArray[$k] = array_change_key_case($resultsArray[$k], CASE_LOWER);
                                            if($fromVspAndroid)
                                                $resultsArray[$k][buttonDetails] = $button[$i];
                                            else
                                                $resultsArray[$k][buttonDetailsJSMS] = $button[$i];
                                            $resultsArray[$k][photo] = PictureFunctions::mapUrlToMessageInfoArr($resultsArray[$k][photo], $photoDisplayType,'',$viewedGender);
                                            if ($resultsArray[$k][photo][label])
                                                    $resultsArray[$k][photo][url] = PictureFunctions::getNoPhotoJSMS($viewedGender);
                                            $resultsArray[$k][buttonDetailsJSMS][photo][url]=$resultsArray[$k][photo][url];
                                            $resultsArray[$k][location] = $resultsArray[$k][decorated_city_res];
                                            $resultsArray[$k][subscription_icon] = $resultsArray[$k][paidlabel];
                                            $resultsArray[$k][subscription_text] = $resultsArray[$k][paidlabel];
                                            $name = '';
                                            if(is_array($nameData)&& $nameData[$loggedInProfileObj->getPROFILEID()]['DISPLAY']=="Y" && $nameData[$loggedInProfileObj->getPROFILEID()]['NAME']!='')
                                                {
                                                        $name = $nameOfUserObj->getNameStr($resultsArray[$k][name_of_user],$loggedInProfileObj->getSUBSCRIPTION());
                                                }
                                            $resultsArray[$k][name_of_user]=$name;
                                            if($fromVspAndroid)
                                                $resultsArray[$k][apiLinkToProfile] = "/api/v1/profile/detail?profilechecksum=".$resultsArray[$k][profilechecksum];
                                            if ($resultsArray[$k][userloginstatus] == "gtalk" || $resultsArray[$k][userloginstatus] == "jschat")
                                            	$resultsArray[$k][userloginstatus] = "Online";
                                            if ($resultsArray[$k][verification_seal]!=0)
                                                    $resultsArray[$k][verification_seal] = "In-Person Verified";
                                            else
                                                    $resultsArray[$k][verification_seal] = "";

                                            foreach ($resultsArray[$k] as $key => $value) {
                                                    $remDecorated = str_replace("decorated_", "", $key);
                                                    if ($resultsArray[$k][$remDecorated] != $resultsArray[$k][$key]) {
                                                            $resultsArray[$k][$remDecorated] = $resultsArray[$k][$key];
                                                    }
                                            }
                                            $i++;
                                    }
                                }
                                foreach ($profileidsort as $v) {
                                        if ($l < viewSimilarConfig::$suggAlgoNoOfResults_Mobile) {
                                                if ($resultsArray[$v] && $v!=$viewedProfileID) {
                                                        $output[$v] = $resultsArray[$v];
                                                       $memProfile[]=$resultsArray[$v]["profileid"];
                                                        $l++;
                                                }

                                        } else
                                                break;
                                }
                                if(!$memObject->get('similar-'.$viewedProfileID.$pid)){
                                        $memObject->set('similar-'.$viewedProfileID.$pid,$memProfile);
                                }
                                if(is_array($output))
                                {
                                    foreach ($output as $key => $value) {
                                            $outputArr[] = $value;
                                    }
                                }
                                if(!MobileCommon::isNewMobileSite() && !MobileCommon::isDesktop() && !(MobileCommon::isIOSApp() && $vspPage=='PD')){
                                     $dateHourToAppend = date('Y-m-d-H', time());
                                     JsMemcache::getInstance()->hIncrBy("ECP_SIMILAR_PROFILES_COUNT",$dateHourToAppend."_".MobileCommon::getChannel(),count($output));
                                 }
                                $paramArray["profiles"] = $outputArr;
                                $paramArray[noresultmessage] = null;
                                $paramArray["no_of_results"] = count($output);
                                $paramArray["result_count"] = "Similar Profiles ".count($output);
                                $paramArray["username"] = $this->Profile->getUSERNAME();
                                if (count($output) == 0) {
                                        $paramArray[noresultmessage] = self::No_search_results_1 . $this->Profile->getUSERNAME() . self::No_search_results_2;
                                }
                                
                                $outputMsg = $paramArray;
                                $respObj = ApiResponseHandler::getInstance();
                                $respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
                                $respObj->setResponseBody($outputMsg);
                                $respObj->generateResponse();
                        } else {
                                $respObj = ApiResponseHandler::getInstance();
                                $respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
                                $respObj->generateResponse();
                        }
                }
                return sfView::NONE;
                unset($inputValidateObj);
                die;
        }
}
