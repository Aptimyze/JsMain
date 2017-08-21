<?php
/*
* This class handles display of api format
*/
class SearchApiDisplay
{
	private $profileObjArr;
	private $profileIdStr;
	private $profileids;
	private $viewedProfilesDpp;
	private $partnerFieldDoesntMatter = '99999';  //when a user hasn't specified any value for a partner field, 99999 is returned from solr for that field

	public function __construct($SearchParamtersObj="")
        {
                $this->SearchParamtersObj = $SearchParamtersObj;
        }

	/**
	* This function sets the DPP data of a profile in class variable $viewedProfilesDpp
	* @param - $pid - profileid whose DPP needs to be set
	**/
	public function setViewedProfileDpp($pid,$key)
	{
		$this->profilesWithRestrictedPrivacy[]=$pid;
		if($this->searchResultsData[$key]['PARTNER_LAGE'] != $this->partnerFieldDoesntMatter && $this->searchResultsData[$key]['PARTNER_LAGE'] != '')
			$this->viewedProfilesDpp[$pid]['LAGE'][0]=$this->searchResultsData[$key]['PARTNER_LAGE'];
		if($this->searchResultsData[$key]['PARTNER_HAGE'] != $this->partnerFieldDoesntMatter && $this->searchResultsData[$key]['PARTNER_HAGE'] != '')
			$this->viewedProfilesDpp[$pid]['HAGE'][0]=$this->searchResultsData[$key]['PARTNER_HAGE'];
		if($this->searchResultsData[$key]['PARTNER_RELIGION'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['RELIGION']=explode(",",$this->searchResultsData[$key]['PARTNER_RELIGION']);
		if($this->searchResultsData[$key]['PARTNER_CASTE'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['CASTE']=explode(",",$this->searchResultsData[$key]['PARTNER_CASTE']);
		if($this->searchResultsData[$key]['PARTNER_MTONGUE'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['MTONGUE']=explode(",",$this->searchResultsData[$key]['PARTNER_MTONGUE']);
		if($this->searchResultsData[$key]['PARTNER_COUNTRYRES'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['COUNTRY_RES']=explode(",",$this->searchResultsData[$key]['PARTNER_COUNTRYRES']);
		if($this->searchResultsData[$key]['PARTNER_CITYRES'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['CITY_RES']=explode(",",$this->searchResultsData[$key]['PARTNER_CITYRES']);
		if($this->searchResultsData[$key]['PARTNER_MSTATUS'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['MSTATUS']=explode(",",$this->searchResultsData[$key]['PARTNER_MSTATUS']);
		if($this->searchResultsData[$key]['PARTNER_INCOME'] != $this->partnerFieldDoesntMatter)
			$this->viewedProfilesDpp[$pid]['INCOME']=explode(",",$this->searchResultsData[$key]['PARTNER_INCOME']);
	}

	public function getEducationValue($education,$decoratedFieldName,$ugDegree,$pgDegree,$otherugDegree,$otherpgDegree)
	{
		if($ugDegree == '' && $pgDegree == '' && $otherugDegree=='' && $otherpgDegree=='')
		{
			return FieldMap::getFieldLabel($decoratedFieldName,$education);
		}
		else
		{
			
			$degreeGrouping = FieldMap::getFieldLabel("degree_grouping","","1");
				
                        if($education != '')
												{
																$newEdu = explode(",",$education);
																foreach($newEdu as $k=>$v)
																	$showEducation[]=FieldMap::getFieldLabel($decoratedFieldName,$v);
												}
                        if($pgDegree != '')
                                $showEducation[]=FieldMap::getFieldLabel($decoratedFieldName,$pgDegree);
                        if($ugDegree != '')
                                $showEducation[]=FieldMap::getFieldLabel($decoratedFieldName,$ugDegree);
                        if($otherugDegree != '')
                                $showEducation[]=$otherugDegree;
                        if($otherpgDegree != '')
                                $showEducation[]=$otherpgDegree;
		}
	
                if($showEducation)
                        return implode(", ",array_unique($showEducation));
	}

	/**
	  * This function sets all the data that needs to be shown in the search tuple for all the searched profiles.
	  * This includes the search profiles' personal data as well as data related to their interactions with the viewer (photo request, contact request, etc)
	  * @param - $searchId - searchId of the search that has been executed
	**/

	public function getDisplayData($searchId='')
	{
		$chatObj = new ChatLibrary(searchConfig::getSearchDb());

		//logged in profile object
//		$this->viewerObj = LoggedInProfile::getInstance("newjs_master",'');
		$viewer = $this->viewerObj->getPROFILEID();
		$activated = $this->viewerObj->getACTIVATED();
		$incomplete = $this->viewerObj->getINCOMPLETE();

		if($viewer && $viewer != '')
		{
			//get recommended profiles
			//$recommendedObj = new RecommendedProfile();
			//$recommendedProfiles = $recommendedObj->getting_reverse_trend_in_search($this->viewerObj,$this->searchResultsData);

			//get if photo present
			$havePhoto = $this->viewerObj->getHAVEPHOTO();
			/*Commenting as no more required
			$horoscopeObj = new Horoscope();
			$haveHoroscope = $horoscopeObj->ifHoroscopePresent($viewer);
			*/
			//get if horoscope present
//			if(check_astro_details($this->viewerObj->getPROFILEID(),$this->viewerObj->getSHOW_HOROSCOPE()))
//			if($this->viewerObj->getBTIME() != '')
//				$haveHoroscope = 'Y';
//			else
//				$haveHoroscope = 'N';

			//get viewed profiles
			//$viewLogObj = new VIEW_LOG();
			//$viewedProf = $viewLogObj->get($viewer,$this->profileIdStr,1);
                        $viewedProf = "";
                        
			unset($photoRequestSenders);
			unset($photoRequestReceivers);
			$countOfNoPhotoResults = 0;

			//if no photo uploaded, get details for photo requests from set of array($this->profileids)
			if($havePhoto == 'N' || $havePhoto == '')
			{
				$photoRequestReceivers[]=$viewer;
				$photoRequestSenders = $this->profileids;
			}

			foreach($this->profileids as $key=>$pid)
			{
				if($this->searchResultsData[$key]['HAVEPHOTO'] == '' || $this->searchResultsData[$key]['HAVEPHOTO'] == 'N')
				{
					$photoRequestReceivers[]=$pid;
					$countOfNoPhotoResults++;
				}
			}
			if($countOfNoPhotoResults > 0)
			{
				$photoRequestSenders[] = $viewer;
			}

			unset($pid);

			if(is_array($photoRequestSenders)  && is_array($photoRequestReceivers))
			{
				$pictureObj = new PictureService($this->viewerObj);
				$photoRequestResults = $pictureObj->getIfPhotoRequested($photoRequestSenders, $photoRequestReceivers);
				$photoRequests = $photoRequestResults['receivedByViewer'];
				$photosRequested = $photoRequestResults['sentByViewer'];
			}
      /*Commenting as horoscope no more required
			//if no horoscope uploaded, get details for horoscope requests
			if($haveHoroscope == 'N' || $haveHoroscope == '')
			{
				$horoscopeRequests = $horoscopeObj->ifHoroscopeRequested($this->profileids, $viewer,1);
			}
			*/
			//get logged-in user's bookmarks
			$bookmarkObj = new Bookmarks(searchConfig::getSearchDb());
			$bookmarks = $bookmarkObj->getProfilesBookmarks($viewer, $this->profileids, 1);

			//get chat requests received by logged-in user
			//$chatRequests  = $chatObj->getIfChatRequestSent($this->profileIdStr, $viewer, 1);

			//get awaiting contact requests received by logged-in user
			$contactObj = new ContactsRecords();
//			$this->contactRequests  = $contactObj->ifContactSent($this->profileIdStr, $viewer, 'I', 1);
			$contactsArr = $contactObj->getContactsSent($this->profileIdStr.",".$viewer,$this->profileIdStr.",".$viewer);
			$this->contactRequests = $contactsArr['viewerAwaitingContacts'];
			if(is_array($this->contactRequests))
			{
				foreach($this->contactRequests as $k=>$v)
				{
					if($v == 1)
						$this->contactRequests[$k] = 'I';
				}
			}
			$this->contactSent = $contactsArr['viewerSentContacts'];
			if(is_array($this->contactSent))
			{
				foreach($this->contactSent as $k=>$v)
				{
					$this->contactSent[$k]=1;
				}
			}
			$this->contactReplied = $contactsArr['viewerContacts'];
			if(is_array($this->contactReplied))
			{
				foreach($this->contactReplied as $k=>$v)
				{
					$this->contactReplied[$k]=1;
				}
			}
			$this->allContacts = $contactsArr['allContacts'];

			if(in_array($activated,array('U','N','P')) || $incomplete=='Y') 
			{
				$tempContacts = $contactObj->getTempContactsSent($viewer,$this->profileIdStr);
					$this->contactSent = $tempContacts;
			}

			if($searchId != '')
			{
				$ignoredStr = $_COOKIE["ignore_$searchId"];
				$ignoredProfiles = explode("**",$ignoredStr);
				foreach($ignoredProfiles as $keyVal => $ignProf)
				{
					if($ignProf != '')
						$ignProfArr[str_replace("*","",$ignProf)] = 1;
				}
			}
		}

		//get users online on gtalk
		//$gtalkUsers = $chatObj->getIfUserIsOnlineInGtalk($this->profileIdStr,1);
		

		//get users online on JS chat
		$jsChatUsers = $chatObj->getIfUserIsOnlineInJSChat($this->profileIdStr,1);

		//user's detail fields to be displayed in the search tuple: username, age, height, etc
		$fieldStr = SearchConfig::$searchDisplayFields;
		$fieldsArr = explode(",",$fieldStr);
		
		if(is_array($this->searchResultsData))
		{
			$offsetVal=1;
			$this->viewedGender = $this->searchResultsData[0]['GENDER']; //check!!!!!
			$decoratedMappingSearchDisplay = SearchConfig::decoratedMappingSearchDisplay();
			foreach($this->profileids as $key=>$pid)
			{
				if(!($key == 0 && $this->searchResultsData[$key]['FEATURED']=='Y'))
					$this->finalResultsArray[$pid]['OFFSET']=$offsetVal++;

				$this->profileObjArr[$key]=Profile::getInstance("",$pid);
				$this->profileObjArr[$key]->setHAVEPHOTO($this->searchResultsData[$key]['HAVEPHOTO']);
				$this->profileObjArr[$key]->setGENDER($this->searchResultsData[$key]['GENDER']);
				$this->profileObjArr[$key]->setPHOTOSCREEN($this->searchResultsData[$key]['PHOTOSCREEN']);
				$this->profileObjArr[$key]->setPHOTO_DISPLAY($this->searchResultsData[$key]['PHOTO_DISPLAY']);
				$this->profileObjArr[$key]->setPRIVACY($this->searchResultsData[$key]['PRIVACY']);

				//set for canonical url
				$this->profileObjArr[$key]->setRELIGION($this->searchResultsData[$key]['RELIGION']);
				$this->profileObjArr[$key]->setUSERNAME($this->searchResultsData[$key]['USERNAME']);
				$this->profileObjArr[$key]->setMTONGUE($this->searchResultsData[$key]['MTONGUE']);
				$this->profileObjArr[$key]->setCASTE($this->searchResultsData[$key]['CASTE']);
				$this->profileObjArr[$key]->setMSTATUS($this->searchResultsData[$key]['MSTATUS']);
				$this->profileObjArr[$key]->setCOMPANY_NAME($this->searchResultsData[$key]['COMPANY_NAME']);
				$this->profileObjArr[$key]->setCOLLEGE($this->searchResultsData[$key]['COLLEGE']);
				$this->profileObjArr[$key]->setPG_COLLEGE($this->searchResultsData[$key]['PG_COLLEGE']);
				
				//get DPP values for profiles with privacy as 'F'
				if($this->searchResultsData[$key]['PRIVACY']=='F')
				{
					$this->setViewedProfileDpp($pid,$key);
				}

				foreach($fieldsArr as $fieldName)
				{
					$fieldValue = $this->searchResultsData[$key][$fieldName];
					/*
					if($fieldName == 'AGE')
						$this->finalResultsArray[$pid][$fieldName]=$this->searchResultsData[$key][$fieldName];
					else	
					*/
						$this->finalResultsArray[$pid][$fieldName]=$this->searchResultsData[$key][$fieldName];

					if($fieldName == 'USERNAME')
					{
						if(strlen($this->searchResultsData[$key][$fieldName]) >8)
						{
							$this->finalResultsArray[$pid][$fieldName]=substr($this->searchResultsData[$key][$fieldName],0,6)."..";
						}
						//$this->finalResultsArray[$pid][$fieldName]=substr($this->searchResultsData[$key][$fieldName],0,8)."..".$pid;
					}
					if($fieldName =="NAME_OF_USER")
					{
						$name = "";
						$nameOfUserObj = new NameOfUser;
						$name = $nameOfUserObj->getNameStr($this->searchResultsData[$key][$fieldName],$this->viewerObj->getSUBSCRIPTION());
						$this->finalResultsArray[$pid][$fieldName]=$name;
					}
					if(strstr(SearchConfig::$searchDisplayDecoratedFields,$fieldName))
					{
						$decoratedFieldName = $decoratedMappingSearchDisplay[$fieldName];
						if($fieldName=='HEIGHT')
							$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(FieldMap::getFieldLabel($decoratedFieldName,$fieldValue));
						else if($fieldName=='CASTE')
							$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(trim(FieldMap::getFieldLabel($decoratedFieldName,$fieldValue),'-'));
						else if($fieldName == 'OCCUPATION')
						{
							$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(str_replace("/"," / ",FieldMap::getFieldLabel($decoratedFieldName,$fieldValue)));
						}
						else if($fieldName == 'EDU_LEVEL_NEW')
						{
							$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode($this->getEducationValue($fieldValue,$decoratedFieldName,$this->searchResultsData[$key]['UG_DEGREE'],$this->searchResultsData[$key]['PG_DEGREE'],$this->searchResultsData[$key]['OTHER_UG_DEGREE'],$this->searchResultsData[$key]['OTHER_PG_DEGREE']));
						}
						else if($fieldName == 'CITY_RES')
						{ 
                                                        $this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = CommonFunction::getResLabel($this->searchResultsData[$key]['COUNTRY_RES'],$this->searchResultsData[$key]['STATE'],$fieldValue,$this->searchResultsData[$key]['ANCESTRAL_ORIGIN'],$decoratedFieldName);
//							if(FieldMap::getFieldLabel($decoratedFieldName,$fieldValue) == '')
//							{
//								$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(FieldMap::getFieldLabel('country',$this->searchResultsData[$key]['COUNTRY_RES']));
//							}
//							else
//								$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(FieldMap::getFieldLabel($decoratedFieldName,$fieldValue));
                                                        //echo '<pre>';print_r($this->finalResultsArray);die;
						}
						else
							$this->finalResultsArray[$pid]['DECORATED_'.$fieldName] = html_entity_decode(FieldMap::getFieldLabel($decoratedFieldName,$fieldValue));
					
					}
				}

				$iconsSize = 0;
				$maxIconsSize = 180; //actual size is 210

				$this->finalResultsArray[$pid]['paid_joined_viewed_icon']=$this->getPaid_Joined_viewed_icon($this->searchResultsData[$key]['SUBSCRIPTION'],$this->searchResultsData[$key]['ENTRY_DT'],$viewedProf[$pid]);
				if($this->finalResultsArray[$pid]['paid_joined_viewed_icon'] != 'noOption')
				{
					$iconsSize += 95;
				}

				if($this->searchResultsData[$key]['CHECK_PHONE'] == 'V')
					$this->finalResultsArray[$pid]['CHECK_PHONE'] = 'V'; //verified no
				else if($this->searchResultsData[$key]['CHECK_PHONE'] == 'I')
					$this->finalResultsArray[$pid]['CHECK_PHONE'] = 'I'; //invalid no
				else if($this->searchResultsData[$key]['CHECK_PHONE'] == 'P')
					$this->finalResultsArray[$pid]['CHECK_PHONE'] = 'P'; //no available and not hidden/invalid

				if($this->finalResultsArray[$pid]['CHECK_PHONE'] != '')
				{
					$iconsSize += 30;
				}
				
				$this->finalResultsArray[$pid]['HOROSCOPE']=$this->searchResultsData[$key]['HOROSCOPE'];
				$this->finalResultsArray[$pid]['GUNASCORE']=array_key_exists("GUNASCORE",$this->searchResultsData[$key])?$this->searchResultsData[$key]['GUNASCORE']:"";
				if($this->finalResultsArray[$pid]['HOROSCOPE'] == 'Y')
				{
					$iconsSize += 30;
				}
				if($haveHoroscope == 'Y' && $this->finalResultsArray[$pid]['HOROSCOPE'] == 'Y')
				{
					$iconsSize += 40; //guna match
				}

				if(($iconsSize < $maxIconsSize) && ($recommendedProfiles[$pid] == 'H' || $recommendedProfiles[$pid] == 'R'))
				{
					$this->finalResultsArray[$pid]['RECOMMENDED']='Y';
					$iconsSize += 30;
				}
				else
					$this->finalResultsArray[$pid]['RECOMMENDED']='N';

				if($iconsSize < $maxIconsSize)
				{
					$this->finalResultsArray[$pid]['LINKEDIN']=$this->searchResultsData[$key]['LINKEDIN'];
					if($this->searchResultsData[$key]['LINKEDIN'] == 'Y')
						$iconsSize += 30;
				}

				if($iconsSize < $maxIconsSize)
				{
					$this->finalResultsArray[$pid]['HIV']=$this->searchResultsData[$key]['HIV'];
					if($this->searchResultsData[$key]['HIV'] == 'Y')
						$iconsSize += 30;
				}
				$this->finalResultsArray[$pid]['userLoginStatus']=$this->getUserLoginStatus($gtalkUsers[$pid],$jsChatUsers[$pid],$this->searchResultsData[$key]['LAST_LOGIN_DT']);
					
				$this->finalResultsArray[$pid]['availforchat']= false;
				$loggedInProfileObj = LoggedInProfile::getInstance("newjs_master",'');
				if(JsConstants::$chatOnlineFlag['search'] && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID() != '' && $jsChatUsers[$pid])
					$this->finalResultsArray[$pid]['availforchat']= true;

//				$this->finalResultsArray[$pid]['STATIC_UNAME'] = CommonUtility::statName($pid,$this->searchResultsData[$key]['USERNAME']);
				$this->finalResultsArray[$pid]['STATIC_UNAME'] = CommonUtility::CanonicalProfile($this->profileObjArr[$key]);
				$this->finalResultsArray[$pid]['PROFILECHECKSUM']=JsAuthentication::jsEncryptProfilechecksum($pid);
				if($this->finalResultsArray[$pid]['FEATURED']!='Y')
					$this->finalResultsArray[$pid]['FEATURED']=$this->searchResultsData[$key]['FEATURED'];
				$this->finalResultsArray[$pid]['HAVEPHOTO']=$this->searchResultsData[$key]['HAVEPHOTO'];
				$this->finalResultsArray[$pid]['PRIVACY']=$this->searchResultsData[$key]['PRIVACY'];
				$this->finalResultsArray[$pid]['PHOTO_DISPLAY']=$this->searchResultsData[$key]['PHOTO_DISPLAY'];
				$this->finalResultsArray[$pid]['GENDER']=$this->searchResultsData[$key]['GENDER'];
				$this->finalResultsArray[$pid]['MSTATUS']=FieldMap::getFieldLabel("mstatus",$this->searchResultsData[$key]['MSTATUS']);

				if($ignProfArr[$pid] == 1)
					$this->finalResultsArray[$pid]['IGNORED'] = 'Y';

				if($this->searchResultsData[$key]['ASTRO_DETAILS'] != 'N' && $this->finalResultsArray[$pid]['HOROSCOPE'] == 'Y')
				{
					if($this->searchResultsData[$key]['GENDER']=='F')
						$this->finalResultsArray[$pid]['HOROSCOPE_VAL'] = $pid.":2:".$this->searchResultsData[$key]['ASTRO_DETAILS'];
					else
						$this->finalResultsArray[$pid]['HOROSCOPE_VAL'] = $pid.":1:".$this->searchResultsData[$key]['ASTRO_DETAILS'];
				}

				if($this->searchResultsData[$key]['FILTER_SCORE'])
				{  
					$this->finalResultsArray[$pid]['FILTER_REASONS'] = $this->checkFilterReasons($this->searchResultsData[$key]['FILTER_SCORE']);
				}

				if($photoRequests[$pid] == 1)
					$this->finalResultsArray[$pid]['PHOTO_REQUEST']='Y';
				else
					$this->finalResultsArray[$pid]['PHOTO_REQUEST']='N';
				if($photosRequested[$pid] == 1)
					$this->finalResultsArray[$pid]['PHOTO_REQUESTED']='Y';
				else
					$this->finalResultsArray[$pid]['PHOTO_REQUESTED']='N';
				//if($horoscopeRequests[$pid] == 1)
					//$this->finalResultsArray[$pid]['HOROSCOPE_REQUEST']='Y';
				//else
					//$this->finalResultsArray[$pid]['HOROSCOPE_REQUEST']='N';
				/*
				if($chatRequests[$pid] == 1)
					$this->finalResultsArray[$pid]['CHAT_REQUEST']='Y';
				else
				*/
					$this->finalResultsArray[$pid]['CHAT_REQUEST']='N';
				if($this->contactRequests[$pid] == 'I')
					$this->finalResultsArray[$pid]['CONTACT_REQUEST']='Y';
				else
					$this->finalResultsArray[$pid]['CONTACT_REQUEST']='N';
				if($this->contactSent[$pid] == 1)
					$this->finalResultsArray[$pid]['CONTACT_SENT']='Y';
				else
					$this->finalResultsArray[$pid]['CONTACT_SENT']='N';
				if($this->contactReplied[$pid] == 1)
					$this->finalResultsArray[$pid]['CONTACT_REPLIED']='Y';
				else
					$this->finalResultsArray[$pid]['CONTACT_REPLIED']='N';

				if($this->allContacts[$pid])
					$this->finalResultsArray[$pid]['CONTACT_STATUS']=$this->allContacts[$pid];
				else
					$this->finalResultsArray[$pid]['CONTACT_STATUS']='';


				if($bookmarks[$pid] == 1)
					$this->finalResultsArray[$pid]['BOOKMARKED']='Y';
				else
					$this->finalResultsArray[$pid]['BOOKMARKED']='N';

				if(strstr($this->searchResultsData[$key]['SUBSCRIPTION'],'B'))
					$this->finalResultsArray[$pid]['BOLDLISTING']='B';
				else
					$this->finalResultsArray[$pid]['BOLDLISTING']='N';

				$this->finalResultsArray[$pid]['VERIFY_ACTIVATED_DT'] = SearchUtility::convertSolrTimeToMysqlTime($this->searchResultsData[$key]['VERIFY_ACTIVATED_DT']); 
				$this->finalResultsArray[$pid]['VERIFICATION_SEAL']=$this->getSealInfo($this->searchResultsData[$key]['VERIFICATION_SEAL']);
                                if($this->finalResultsArray[$pid]['VERIFICATION_SEAL'])
                                    $this->finalResultsArray[$pid]['VERIFICATION_STATUS'] = 1;
                                else
                                    $this->finalResultsArray[$pid]['VERIFICATION_STATUS'] = 0;
                //aadhar verification part
                  $this->finalResultsArray[$pid]['COMPLETE_VERIFICATION_STATUS'] = $this->getFinalVerificationStatus($this->finalResultsArray[$pid]['VERIFICATION_STATUS'],$pid);
				/* matchAlerts Sent Date Display */
				if($this->SearchParamtersObj)
				{
	                                $SearchUtility = new SearchUtility;
        	                        if($SearchUtility->isMatchAlertsPage($this->SearchParamtersObj))
                        	        {
                	                        $tempArr = $this->SearchParamtersObj->getAlertsDateConditionArr();
                                	        if(!$tempArr)
                                        	{
                                                	$MatchAlerts = new MatchAlerts();
	                                                $tempArr = $MatchAlerts->getProfilesWithOutSorting($this->viewerObj->getPROFILEID());
        	                                        $this->SearchParamtersObj->setAlertsDateConditionArr($tempArr);
                	                        }
                        	                $this->finalResultsArray[$pid]['SENT_DATE'] = date("d M Y",MatchAlerts::getLogicalDateFromLogDate($tempArr[$pid]));
                                	}elseif($this->SearchParamtersObj && $this->SearchParamtersObj->getSEARCH_TYPE() == SearchTypesEnums::contactViewAttempt){
                                            $tempArr = $this->SearchParamtersObj->getAttemptConditionArr();
                                            if(!$tempArr)
                                            {
                                                    $VCDTrackingObj = new VCDTracking;
                                                    $tempArr = $VCDTrackingObj->getContactAttemptProfiles($this->viewerObj->getPROFILEID());
                                                    $this->SearchParamtersObj->setAttemptConditionArr($tempArr);
                                            }
                                            $this->finalResultsArray[$pid]['SENT_DATE'] = CommonUtility::convertDateToDay($tempArr[$pid]);
                                          
                                        }elseif($this->SearchParamtersObj && $this->SearchParamtersObj->getSEARCH_TYPE() == SearchTypesEnums::JustJoinedMatchesDesktop)
					{
        	                                if($this->searchResultsData[$key]["ENTRY_DT"])
                	                                $this->finalResultsArray[$pid]['JOIN_DATE'] = CommonUtility::convertDateToDayDiff($this->searchResultsData[$key]["ENTRY_DT"]);
                        	        }
                                	elseif($SearchUtility->isKundliAlertsPage($this->SearchParamtersObj))
                                	{
                                        	$tempArr = $this->SearchParamtersObj->getAlertsDateConditionArr();
	                                        if(!$tempArr)
        	                                {
                	                                $KundliAlerts = new KundliAlerts();
                        	                        $tempArr = $KundliAlerts->getProfilesWithOutSorting($this->viewerObj->getPROFILEID());
                                	                $this->SearchParamtersObj->setAlertsDateConditionArr($tempArr);
                                        	}
                                 	       $this->finalResultsArray[$pid]['SENT_DATE'] = date("d M Y",($tempArr[$pid]));
	                                }
				}
                                /* matchAlerts Sent Date Display */
							
			}
		}
		
	}

	/*
	This function return the params on which the profile is filtered out
	@param - score generated from solr
	@return - string with param names
	*/
	private function checkFilterReasons($score)
	{
		$filterParamArr = array("age","marital status","religion","caste","country","city","mother tongue","income");
		$binStr = decbin($score);
		for($i=strlen($binStr)-1;$i>=0;$i--)
		{
			if(substr($binStr,$i,1)=='1')
				$reasonArr[] = $filterParamArr[strlen($binStr)-1-$i];
		}
		if($reasonArr && is_array($reasonArr))
		{
			if(count($reasonArr)>1)
			{
				foreach($reasonArr as $k=>$v)
				{
					if($k==count($reasonArr)-1)
					{
						$output = rtrim($output,", ");
						$output = $output." and ".$v;
					}
					else
						$output = $output.$v.", ";
				}		
			}
			else
				$output = implode(", ",$reasonArr);
		}
		return $output;
	}

	/**
	* This function gets featured profile results from an object and returns them in displayable format.
	* @param - $fpRespObj - Featured Profile response object
	* @return - $finalResultsArray - array of all the data to be displayed in the featured profile tuple.
	**/
	public function featuredProfileTemplateInfo($viewerObj,$isMobile,$fpRespObj,$sortlogic,$featureProfileId)
	{
		$this->viewerObj = $viewerObj;
		$this->isMobile = $isMobile;
		$this->sortLogic = $sortLogic;
		//getting featured profiles
		if($fpRespObj)
		{
			$fpIds = $fpRespObj->getSearchResultsPidArr();

			$this->profileids[0] = $fpIds[0];
			$this->profileIdStr = $this->profileids[0];

			$featuredProf = $fpRespObj->getResultsArr();
			$this->searchResultsData[0] = $featuredProf[0];
			$this->searchResultsData[0]['FEATURED'] = 'F';
			$this->searchResultsData[0]['PROFILEID']=$this->searchResultsData[0]['id'];

			$this->getDisplayData();

			$this->getProfilePhotoForMultipleUsers();

/*
			if(is_array($this->viewedProfilesDpp))
			{
				$this->checkFilteredConditions();
			}
*/

			return $this->finalResultsArray;
		}
	}

	/**
	* This function gets search results for a page and returns them in display-able format.
	* @param SearchResponseObj response object of search main results
	* @param - $searchId - searchId of the search that has been executed
	* @param fpRespObj feature profile response object
	* @return - $finalResultsArray - array of all the data to be displayed in the search tuples.
	**/
	public function searchPageDisplayInfo($isMobile, $viewerObj, $SearchResponseObj, $searchId, $sortLogic, $fpRespObj='',$photoType='Profile')
	{
		$this->isMobile = $isMobile;
		$this->viewerObj = $viewerObj;
		$this->sortLogic = $sortLogic;
		$this->photoType = $photoType;
		//getting featured profiles

		unset($this->profilesWithRestrictedPrivacy);

		if($fpRespObj)
		{
			$fpIds = $fpRespObj->getSearchResultsPidArr();
			if(is_array($fpIds))
			{
				$this->profileids[0] = $fpIds[0];
				$this->profileIdStr = $this->profileids[0];
				$featuredProf = $fpRespObj->getResultsArr();
				$this->searchResultsData[0] = $featuredProf[0];
				$this->searchResultsData[0]['FEATURED'] = 'Y';
			}
		}

		//getting search results
	
		$this->profileids1 = $SearchResponseObj->getSearchResultsPidArr();
		$this->searchResultsData1 = $SearchResponseObj->getResultsArr();

		//merging featured profile and search results
		if(is_array($this->profileids1))
		foreach($this->profileids1 as $k => $pid)
		{
			$this->profileids[] = $pid;
			$this->searchResultsData[] = $this->searchResultsData1[$k];
		}
		//if no profiles, return null
		if(!is_array($this->profileids))
			return NULL;
                else
		{
			if(count($SearchResponseObj->getFeturedProfileArr())>0){
				$featuredProfileArr=$SearchResponseObj->getFeturedProfileArr();
				foreach($featuredProfileArr as $k=>$v){
								$featuredProfileArrNew[$v["id"]]=$v;
								$this->profileids[] = $v["id"];
								
								$this->searchResultsData[] = $v;
				}
			}
										   
    }

		$this->profileIdStr = trim(implode(",",$this->profileids),",");

		//setting PROFILEID of profiles in the search results array
		foreach ($this->searchResultsData as $key=>$data)
		{
			$this->searchResultsData[$key]['PROFILEID']=$this->searchResultsData[$key]['id'];
		}

		$this->getDisplayData($searchId);
		$this->getProfilePhotoForMultipleUsers();
                
		return $this->finalResultsArray;

	}


	/**
	* This function gets Profile Photos of all the users to be displayed on the search page 
	* The conditions such as JPROFILE.PRIVACY and JPROFILE.PHOTO_DISPLAY are considered here.
	**/
	public function getProfilePhotoForMultipleUsers()
	{
//VA Whitelisting
         $whitelistedPhotoTypes = array_keys(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR);
	 if($this->photoType!='' && !in_array($this->photoType,$whitelistedPhotoTypes))
		 SendMail::send_email("eshajain88@gmail.com,lavesh.rawat@gmail.com","apps/jeevansathi/modules/search/lib/api/SearchApiDisplay.class.php phototype not whitelisted and came as".$v,"SearchApiDisplay.class.php phototype not whitelisted");
 
		$multiplePictureObj = new PictureArray($this->profileObjArr);
                
		if($this->isMobile)
			$photosArr = $multiplePictureObj->getProfilePhoto('',$this->viewedProfilesDpp,'','',$this->allContacts,'mobile','',searchConfig::getSearchDb());
		else
			$photosArr = $multiplePictureObj->getProfilePhoto('',$this->viewedProfilesDpp,'','',$this->allContacts,'','',searchConfig::getSearchDb());

		$noOfPhotosArr = $multiplePictureObj->getNoOfPics($this->profileObjArr);
		$this->viewerObj = LoggedInProfile::getInstance("newjs_master",'');
		if($this->viewerObj->getPROFILEID() != '')
			$loggedInUser  = 1;
                
		if(is_array($photosArr))
		{ 
                        if($this->photoType){
				foreach($this->profileids as $profileIdForID)
                                {
                                        $photoIdObj = $photosArr[$profileIdForID];
                                        if($photoIdObj && $photoIdObj->getPICTUREID())
						$pictureId[$profileIdForID]=$photoIdObj->getPICTUREID();
                                }
				if(is_array($pictureId))
				{
					if(!MobileCommon::isDesktop())
					{
                                		$pictureSizeObj = new PICTURE_MobAppPicSize("newjs_masterRep");
		                                $pictureSize = $pictureSizeObj->getPictureSize($pictureId);
					}
				}
                                
                        }
                        
			foreach($this->profileids as $profileId)
			{
				$photoObj = $photosArr[$profileId];
				if($photoObj)
				{
					if($this->isMobile && !$this->photoType){
						$this->finalResultsArray[$profileId]['PHOTO']=$photoObj->getThumbailUrl();
					}
					else
					{
						eval('$temp =$photoObj->get'.$this->photoType.'();');
						$this->finalResultsArray[$profileId]['PHOTO'] = $temp;
                                                if(MobileCommon::isAndroidApp())
                                                    $this->finalResultsArray[$profileId]['THUMBNAIL_PIC'] = $photoObj->getThumbailUrl();
						if(!MobileCommon::isDesktop())
							$this->finalResultsArray[$profileId]['SIZE']=$this->getpictureSizeToShow($profileId,$pictureSize[$profileId]);
						unset($temp);
						//$this->finalResultsArray[$profileId]['PHOTO'] = $photoObj->getProfilePicUrl();
					}

					$isPhotoShown = $photoObj->getIsPhotoShown();
					/*
					if($isPhotoShown == 'nonLoggedInPhoto')
					{
						$this->finalResultsArray[$profileId]['ISALBUM'] = 'L';
					}
					elseif(in_array($isPhotoShown,array('filteredPhoto','contactAcceptedPhoto','underScreeningPhoto')))
					{
						$this->finalResultsArray[$profileId]['ISALBUM'] = 'F';
					}
					elseif($noOfPhotosArr[$profileId] > 1)
						$this->finalResultsArray[$profileId]['ISALBUM'] = 'Y'; //more than 1 photo
					elseif($noOfPhotosArr[$profileId] == 1)
						$this->finalResultsArray[$profileId]['ISALBUM'] = 'N'; //one photo	
					*/
					if($isPhotoShown == 'nonLoggedInPhoto')
						$this->finalResultsArray[$profileId]['ALBUM_COUNT'] = "0";
					elseif(in_array($isPhotoShown,array('filteredPhoto','contactAcceptedPhoto','underScreeningPhoto')))
						$this->finalResultsArray[$profileId]['ALBUM_COUNT'] = "0";
					else
						$this->finalResultsArray[$profileId]['ALBUM_COUNT'] = $noOfPhotosArr[$profileId];
					
				}
				else
				{
					//if no photo available
					/*
					if($this->isMobile)
					{
						$this->finalResultsArray[$profileId]['PHOTO']=PictureService::getRequestOrNoPhotoUrl("requestPhoto","Thumbnail",$this->viewedGender,$this->isMobile);
					}
					else
					{
						$this->finalResultsArray[$profileId]['PHOTO']=PictureService::getRequestOrNoPhotoUrl("requestPhoto","Profile",$this->viewedGender,$this->isMobile);
					}
					*/
					$this->finalResultsArray[$profileId]['PHOTO'] = '';
					$this->finalResultsArray[$profileId]['SIZE']=$this->getpictureSizeToShow($profileId,$pictureSize[$profileId]);
					//$this->finalResultsArray[$profileId]['ISALBUM'] = '0'; //no photo
					$this->finalResultsArray[$profileId]['ALBUM_COUNT'] = '0'; //no photo
				}
			}
		}
		else
		{
			foreach($this->profileids as $profileId)
			{
				if($this->isMobile)
				{
					$this->finalResultsArray[$profileId]['PHOTO']=PictureService::getRequestOrNoPhotoUrl("requestPhoto","ThumbailUrl",$this->viewedGender,$this->isMobile);
				}
				else
				{
					$this->finalResultsArray[$profileId]['PHOTO']=PictureService::getRequestOrNoPhotoUrl("requestPhoto","ProfilePicUrl",$this->viewedGender,$this->isMobile);
				}
			}
		}
	}

	/**
	* This function returns PictureSize of all pictures to be shown
	* @param - $paid - subscription of the user
	* @param - $entrydt - the column JPROFILE.ENTRY_DT
	* @param - $viewed - Profileid which is being viewed
	* @return - the name of the icon that needs to be shown.
	**/
	function getpictureSizeToShow($profileId,$size){
		$noSize = array("WIDTH"=>"450","HEIGHT"=>"600");
		if(is_array($size))
			return $size;
		else
			return $noSize;
	}
	/**
	* This function returns which user detail icon (Paid/Recently Joined/Viewed) needs to be shown on the search page
	* @param - $paid - subscription of the user
	* @param - $entrydt - the column JPROFILE.ENTRY_DT
	* @param - $viewed - Profileid which is being viewed
	* @return - the name of the icon that needs to be shown.
	**/
	public function getPaid_Joined_viewed_icon($paid='',$entrydt='',$viewed)
	{
		if(strstr($paid,'F'))
			return 'paid';
		$entrydt = str_replace("T"," ",$entrydt);
		$entrydt = str_replace("Z"," ",$entrydt);
		$today = JSstrToTime(date("Y-m-d"));
		$entry_dt = JSstrToTime($entrydt);
		$diff = $today - $entry_dt;

		if($this->sortLogic == 'T' || $this->sortLogic == 'P')
			if($diff / (24*60*60) <= 15)
				return 'entry';

		if($viewed == 1)
			return 'viewed';
		return 'noOption';
	}

	/** This function checks returns whether the user is online on JSChat/Gtalk/Last Login Date
	  * Order of Priority : JSChat > Gtalk > Last Login Date
	**/
	public function getUserLoginStatus($gtalkStatus,$jsChatStatus,$lastLoginDate)
	{
                if($jsChatStatus == 1)
                        return 'Online now';
                elseif($gtalkStatus == 1)
                        return 'Online now';
		if($this->sortLogic != 'P')
			return $this->getLastLogin($lastLoginDate);
		else
			return NULL;
	}

	/** In case a user isn't online on neither JSChat nor Gtalk, the last login date of the user is shown.
	* This function returns the text that would be displayed in such a case.
	* @param: $lastLoginDate - last login date of the user
	* @return : text that needs to be shown about the "Last Login Status" of the user.
	**/
	function getLastLogin($lastLoginDate)
	{
		$lastLogin = explode("T",$lastLoginDate);
		//$lastLoginDate = $lastLogin[0];
                // input date format is (date T time Z), After exploding strinf at 'T' and removinf 'Z' from the string date time os passed.
		$timeText = CommonUtility::convertDateToDay($lastLogin[0].' '.rtrim($lastLogin[1],'Z'));
		$lastOnlineStr = "Last Online ".$timeText;
		return $lastOnlineStr;
	}
	
	
	     /**
	* This function is used to get seal info i.e. decode VERIFICATION SEAL
	* @param - $verificationSeal Verification Seal of user as per API requirement
	*/
	public function getSealInfo($verificationSeal='0')
	{ 
			if($verificationSeal == '0')
				return 0;
		
			$verificationSeal=  explode(",", $verificationSeal);
			if($verificationSeal[0]=="F"){
				$sealArr=array_flip(PROFILE_VERIFICATION_DOCUMENTS_ENUM::$VERIFICATION_SEAL_ARRAY);
				$keyArr=PROFILE_VERIFICATION_DOCUMENTS_ENUM::$ATTRIBUTES;
				foreach ($verificationSeal as $key => $value) {
								if($key!=0){
								$attribute=$sealArr[$key];
								if($value!="N"){
									switch ($keyArr[$attribute])
									{
										case "Highest Qualification":  $displaySeal[]= "Education";
											break;
										case "Self Address":
										case "Parent's Address": $displaySeal[]="Address";
											break;
										case "Date of Birth":$displaySeal[]="Age";
											break;
										case "Income" : $displaySeal[]="Income";
											break;
										case "Divorce" : $displaySeal[]="Marital Status";
											break;
											
									}
							}
						}
				}
				if(!is_array($displaySeal))
					return 1;
				
				return array_values(array_unique($displaySeal));
			}
			return 0;
							
		
	}
        /**
         * 
         * @param type $country
         * @param type $state
         * @param type $cityVal
         * @param type $nativeCityOpenText
         * @param type $decoredVal
         * @return string
         */
	/*protected function getResLabel($country,$state,$cityVal,$nativeCityOpenText,$decoredVal){                                
                $label = '';
                $city = explode(',',$cityVal);
                $citySubstr = substr($city[0], 0,2); // if city living in's state and native state is same do not show state
                if(FieldMap::getFieldLabel($decoredVal,$city[0]) == '')
                {
                        $label = html_entity_decode(FieldMap::getFieldLabel('country',$country));
                }
                else{
                        $label = FieldMap::getFieldLabel($decoredVal,$city[0]);
                }
                if(isset($city[1]) && $city[1] != '0' && FieldMap::getFieldLabel($decoredVal,$city[1]) != ''){
                     $nativePlace =  FieldMap::getFieldLabel($decoredVal,$city[1]);
                }else{
                     $states = explode(',',$state);
                     if($states[1] != '' && ($states[1] != $citySubstr || $nativeCityOpenText != '')){
                        $nativeState = FieldMap::getFieldLabel('state_india',$states[1]);
                        
                        if($nativeCityOpenText != '' && $nativeState != '')
                           $nativePlace = $nativeCityOpenText.', ';
                        
                        $nativePlace .= $nativeState;
                     }
                }
                if($nativePlace != '' && $nativePlace != $label)
                        $label .= ' & '.$nativePlace;
                
                return $label;
        }*/

    public function getFinalVerificationStatus($verificationStatus,$pid)
    {
    	if(MobileCommon::isAndroidApp())
		{
			$aadharObj = new aadharVerification();
   			$aadharArr = $aadharObj->getAadharDetails($pid);   			
   			unset($aadharObj);
   			$aadharStatus = $aadharArr[$pid]["VERIFY_STATUS"];
   			if($verificationStatus == 1 && $aadharStatus == "Y")
				return 3;   		
			elseif($aadharStatus == "Y")
				return 2;
   			else
   				return $verificationStatus;
		}
		else
		{
			return $verificationStatus;
		}
    }
}
