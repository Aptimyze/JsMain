<?php
/**
 * @brief This class implements module interface class and defines the functions as per the business logic
 * @author Reshu Rajput
 * @created 2013-09-27
 */

class MyJs implements Module
{
        
        private $profileObj;
        private $configurations;
        private $completeProfilesInfo;
        private static $getTotal = "T";
		private $arrProfiler = array();
  		private $bEnableProfiler = false;        
        
        //Constructor need profile object for myjs page 
        function __construct($module,Profile $profileObj)
        {
                if (!is_null($profileObj)) {
                        $this->profileObj = $profileObj;
                        $this->getConfiguration($module);
                } else
                        throw new JsException("", "Profile Object required in constructor MyJs.class.php");
        }
        
        /* This function will set the configuration object of myjs module
         */
        public function getConfiguration($module)
        {
        	LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "class Myjs getConfiguration hit ");
                $configObj            = new ProfileInformationModuleMap();
		if((MobileCommon::isApp() == "I")||(MobileCommon::isNewMobileSite()))
			$this->configurations = $configObj->getConfiguration($module,'',"IOS");
		else 
                	$this->configurations = $configObj->getConfiguration($module);
        
            //As Per Peek Level Unset Some Listing Across Channels
            if(JsConstants::$hideUnimportantFeatureAtPeakLoad >=2) {
                unset($this->configurations["INTEREST_EXPIRING"]);
            }
            if(JsConstants::$hideUnimportantFeatureAtPeakLoad >=3) {
                unset($this->configurations["SHORTLIST"]);
            }
        }
        
        
	/* 
	* This function will return the myjs specific count object
        * @param allFlag : its not null if count of new and all both is required else only new are retrieved
        * @return countObj : countObj with infotype and corresponding count mapping
        */
        public function getCount($allFlag = "",$infoTypenav="")
        {
                        try {
                        $memcacheServiceObj = new ProfileMemcacheService($this->profileObj);
                        $countObj           = array();
                        $ifHoroscopePresent = "";
                        $ifPhotoPresent     = "";
                        foreach ($this->configurations as $infoType => $config)
			{
				if(is_null($infoTypeNav) || (is_array($infoTypeNav) && array_key_exists($infoType, $infoTypeNav)))
				{
					unset($key);                                
					switch ($infoType)
					{
						case "INTEREST_RECEIVED":
							$key = "AWAITING_RESPONSE_NEW";
							break;
						case "ACCEPTANCES_RECEIVED":
							$key = "ACC_ME_NEW";
							break;
						case "NOT_INTERESTED":
							$key = "DEC_ME_NEW";
							break;	
						case "MESSAGE_RECEIVED":
							$key = "MESSAGE_NEW";
							break;
						case "VISITORS":
							$key = "VISITOR_ALERT";
							break;

						case "HOROSCOPE_REQUEST_RECEIVED":
							$horoscopeObj       = new Horoscope();
							$ifHoroscopePresent = $horoscopeObj->ifHoroscopePresent($this->profileObj->getPROFILEID());
							unset($horoscopeObj);
							if ($ifHoroscopePresent == "Y") 
							{
								$countObj[$infoType] = 0;
								$key                 = "";
							} else
								$key = "HOROSCOPE_NEW";
							break;
						case "PHOTO_REQUEST_RECEIVED":
							$pictureServiceObj = new PictureService($this->profileObj);
							$ifPhotoPresent    = $pictureServiceObj->isProfilePhotoPresent();
							unset($pictureServiceObj);
							if ($ifPhotoPresent == "Y")
							{
								$countObj[$infoType] = 0;
								$key                 = "";
							} else
								$key = "PHOTO_REQUEST_NEW";
							break;
						case "JUST_JOINED_MATCHES":
								$key="JUST_JOINED_MATCHES_NEW";
							break;
						case "ALL_ACCEPTANCE":
								$countObj[$infoType]=$memcacheServiceObj->get("ACC_ME_NEW");
							break;
            
					}
					if ($key != "")
						$countObj[$infoType] = $memcacheServiceObj->get($key);
					if ($allFlag == MyJs::$getTotal)
					{
						switch ($infoType)
						{
							case "INTEREST_RECEIVED":
								$key = "AWAITING_RESPONSE";
								break;
							case "ACCEPTANCES_RECEIVED":
								$key = "ACC_ME";
								break;
							case "DECLINE_RECEIVED":
								$key = "DEC_ME";
								break;
							case "MESSAGE_RECEIVED":
								$key = "MESSAGE";
								break;
							case "NOT_INTERESTED":
								$key = "DEC_ME";
								break;
							case "NOT_INTERESTED_BY_ME":
								$key = "DEC_BY_ME";
								break;
							case "VISITORS":
								$key = "VISITOR_ALERT";
								break;
							case "INTEREST_EXPIRING":
								$key = "INTEREST_EXPIRING";
								break;
							case "ACCEPTANCES_SENT":
								$key = "ACC_BY_ME";
								break;
							case "HOROSCOPE_REQUEST_RECEIVED":
								if ($ifHoroscopePresent == "Y")
								{
									$countObj[$infoType] = 0;
									$key                 = "";
								} else
									$key = "HOROSCOPE";
								break;
							case "PHOTO_REQUEST_RECEIVED":
								if ($ifPhotoPresent == "Y")
								{
									$countObj[$infoType] = 0;
									$key                 = "";
								} else
									$key = "PHOTO_REQUEST";
								break;							
						case "JUST_JOINED_MATCHES":
							$key="JUST_JOINED_MATCHES";
							break;
							case "ALL_ACCEPTANCE":
								$countObj[$infoType."_ALL"]=$memcacheServiceObj->get("ACC_BY_ME")+$memcacheServiceObj->get("ACC_ME");
							break;
						}
						if ($key != "")
							$countObj[$infoType . "_ALL"] = $memcacheServiceObj->get($key);
                                	}
                        	}
			}
			if($countObj["PHOTO_REQUEST_RECEIVED"] || $countObj["HOROSCOPE_REQUEST_RECEIVED"])
			{
                        	$countObj["REQUEST_RECEIVED"] = $countObj["PHOTO_REQUEST_RECEIVED"] + $countObj["HOROSCOPE_REQUEST_RECEIVED"];
                        	if ($allFlag == MyJs::$getTotal)
                                	$countObj["REQUEST_RECEIVED_ALL"] = $countObj["PHOTO_REQUEST_RECEIVED_ALL"] + $countObj["HOROSCOPE_REQUEST_RECEIVED_ALL"];
                      	}
			 return $countObj;
                }
                catch (Exception $e)
		{
                        throw new jsException($e);
                }
                
        }
        
	/* 
	*This function will return the whole display object  calling the module 
	*@param infoTypeNav : optional required only if information type is called by ajax, it should be key value pair of information type and navigation page number required to be retrieved.
	*@return moduleDisplayObj : complete object of all the information requested by the module
	*/
        public function getDisplay($infoTypeNav = null,$params = null)
        {
        	LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "class Myjs getDisplay hit ");

                $fields       = Array();
                $profiles     = Array();
                $tupleService = new TupleService();
		$tupleService->setLoginProfileObj($this->profileObj);
		$start = microtime(TRUE);
                foreach ($this->configurations as $infoType => $config)
		{
                        // Handles both the ajax and non ajax display call only if count is greater than 0,  new declined are not shown in tuples in myjs modul
                        if ( $config["TUPLE"] && (is_null($infoTypeNav) || (is_array($infoTypeNav) && array_key_exists($infoType, $infoTypeNav)))) 
			{
                                $tuple       = $config["TUPLE"];
                                $displayFlag = 1;
                                if ($infoType == "PHOTO_REQUEST_RECEIVED")
				{
                                        $pictureServiceObj = new PictureService($this->profileObj);
                                        if ($pictureServiceObj->isProfilePhotoPresent() == "Y") // No need to display photo requests if photo is already uploaded
                                                $displayFlag = 0;
                                        unset($pictureServiceObj);
                                }
				else if ($infoType == "HOROSCOPE_REQUEST_RECEIVED")
				{
                                        // No need to display horoscope request if already uploaded
                                        $horoscopeObj       = new Horoscope();
                                        $ifHoroscopePresent = $horoscopeObj->ifHoroscopePresent($this->profileObj->getPROFILEID());
                                        if ($ifHoroscopePresent == "Y")
                                                $displayFlag = 0;
                                        unset($horoscopeObj);
                                }
                                        
				// Myjs require only New information type tuples 
                                if ($displayFlag && PROFILE_COMMUNICATION_ENUM_INFO::ifInformationTypeExists($infoType) && PROFILE_COMMUNICATION_ENUM_INFO::ifTupleExists($tuple)) 
				{
                                        $infoTypeAdapter = new InformationTypeAdapter($infoType, $this->profileObj->getPROFILEID());
                                        $skipArray       = $this->getSkipProfiles($infoType);
                                        //Handling ajax case navigation
                                        $skipArrayTemp = $skipArray; 
                                        if (is_array($infoTypeNav) && array_key_exists($infoType, $infoTypeNav))
					{
                                                $nav = $infoTypeNav[$infoType];
						if(is_array($params) && array_key_exists("profileList", $params) && !is_null($params["profileList"]))
						{
							$pids = $this->getPidsFromProfilechecksums($params["profileList"]);
							if(is_array($pids))
							{
								if(is_array($skipArray))	
									$skipArray = array_merge($skipArray,$pids);
								else
									$skipArray = $pids;
							}
						}
					}
					if($this->bEnableProfiler)
 					{
 						$logString = $infoType.' SkipArray count -> '.count($skipArray).' ';
 						$this->arrProfiler[$infoType][] = CommonFunction::logResourceUtilization($start,$logString);
					}
                                        $conditionArray         = $this->getCondition($infoType, $nav);
                                        if($infoType == "MATCH_ALERT")
					{
						$profileMemcacheObj = new ProfileMemcacheService($this->profileObj);
						$matchAlertCount["TOTAL"] = $profileMemcacheObj->get("MATCHALERT_TOTAL");
						$matchAlertCount["NEW"] = $profileMemcacheObj->get("MATCHALERT");
					}
                                        
                                        
					if($nav > 1 && !is_array($pids))
						$infoTypeObj[$infoType] = null;
					else{
		                    if($infoType == "VISITORS")
		                    {
		                        if(MobileCommon::isNewMobileSite())
		                        {
									$conditionArray["matchedOrAll"] = 'A';
		                        }
		                        elseif (MobileCommon::isApp()=='I' || MobileCommon::isApp()=='A')
		                        {
									$request = sfContext::getInstance()->getRequest();
									$conditionArray["matchedOrAll"] = $request->getParameter("matchedOrAll");
									if($request->getParameter("matchedOrAll")!='M')
									{
										$conditionArray["matchedOrAll"] = 'A';
									}
		                        }
							}
                                            if(($infoType == "MATCH_ALERT")&&((MobileCommon::isNewMobileSite())||(MobileCommon::isApp()=='I'))) {
//                                                $conditionArray['LIMIT']=$matchAlertCount['NEW'];
                                                $infoTypeObj[$infoType] = $infoTypeAdapter->getProfiles($conditionArray, $skipArray);
                                            }
                                            else $infoTypeObj[$infoType] = $infoTypeAdapter->getProfiles($conditionArray, $skipArray);
                                            
                                            
                                        }
                                        //Cache the data
                                        $arrAllowedType = array('INTEREST_RECEIVED','VISITORS','MATCH_ALERT');
                                        if(in_array($infoType, $arrAllowedType))  
                                        {
                                          $szCackeKey = $this->profileObj->getPROFILEID().'_'.$infoType;
                                          JsMemcache::getInstance()->set($szCackeKey,serialize($infoTypeObj[$infoType]),1800); 
                                        }
					unset($skipArrayTemp);
					if(is_array($infoTypeObj[$infoType]))
					{
                                        	// Get required tuple, unique fields required by the tuples
                                        	$tupleFields            = $tupleService->getFields($tuple);
	                                	$fields                 = array_merge($fields, $tupleFields);

                                        	//Attaching the callout message,icons and buttons ids  with each profile
                                        	if (is_array($infoTypeObj[$infoType]))
						{
                                                	$infoTypeObj[$infoType] = $tupleService->setMessageIds($config["CALLOUT_MESSAGES"], $infoTypeObj[$infoType]);
                                                	$infoTypeObj[$infoType] = $tupleService->setIconIds($config["ICONS"], $infoTypeObj[$infoType]);
                                                	$infoTypeObj[$infoType] = $tupleService->setButtonIds($config["BUTTONS"], $infoTypeObj[$infoType]);
                                        	}
					}
                                }
                        }
                }
                if($this->bEnableProfiler)
                {
 					CommonFunction::logIntoProfiler('InfoType', $this->arrProfiler);
                }
                unset($conditionArray);
                unset($skipArray);
                //Creating Final object including infotype based all the information
                if (is_array($infoTypeObj))
		{
                        // Calling tuple service to retrieve complete information of all the profiles in one go
                        $tupleService->setProfileInfo($infoTypeObj, array_unique($fields));
			
                	$countObj     = $this->getCount(MyJs::$getTotal,$infoTypeNav);
                        if(JsMemcache::getInstance()->get("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileObj->getPROFILEID())){
                                $countObj["MATCH_OF_THE_DAY"] = JsMemcache::getInstance()->get("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileObj->getPROFILEID());
                                $countObj["MATCH_OF_THE_DAY_ALL"] = JsMemcache::getInstance()->get("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileObj->getPROFILEID());
                        }
                        
                        foreach ($this->configurations as $infoType => $config) 
			{
				if(is_null($infoTypeNav) || (is_array($infoTypeNav) && array_key_exists($infoType, $infoTypeNav)))
				{
					unset($tuplesValues);
					$this->completeProfilesInfo[$infoType] = Array();
					if($config["TUPLE"])
					{
				       		eval('$tuplesValues = $tupleService->get' . $infoType . '();');
                                		// Assigning infotype configurations and tuples
                                		if (sizeof($tuplesValues) > 0)
						{
                                       			$this->completeProfilesInfo[$infoType]["TUPLES"] = $tuplesValues;
                                        		//Handling ajax case
                                        		if (is_array($infoTypeNav) && array_key_exists($infoType, $infoTypeNav))
                                                		$this->completeProfilesInfo[$infoType]["CURRENT_NAV"] = $infoTypeNav[$infoType];
                                        		else
                                                		$this->completeProfilesInfo[$infoType]["CURRENT_NAV"] = 1;
						}
						else
							$this->completeProfilesInfo[$infoType]["CURRENT_NAV"] = 1;
					}
					else
						$this->completeProfilesInfo[$infoType] = Array();
					if (is_array($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]) || is_array($this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"])) 
					{
						$this->completeProfilesInfo["REQUEST_RECEIVED"] = Array();
			      		}
				}
			}
                        if ( !empty($this->completeProfilesInfo) && array_key_exists("REQUEST_RECEIVED",$this->completeProfilesInfo))
			{
                        	$this->getRequestReceived();
                                unset($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]);
                              	unset($this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]);
                        }
                        if(!empty($this->completeProfilesInfo))
			{
				foreach ($this->completeProfilesInfo as $infoType => $values)
				{
					if ($infoType == "REQUEST_RECEIVED")
						$config = $this->configurations[$values["REQUEST_TYPE"]];
					else
						$config = $this->configurations[$infoType];
					if(is_array($matchAlertCount) && $infoType == "MATCH_ALERT")
					{
						$countObj[$infoType . "_ALL"] = $matchAlertCount["TOTAL"];
						$countObj[$infoType] ='';// $matchAlertCount["NEW"]; changed by Palash .. to remove '0' appearing on myjs on jsms and ios
					}
					$this->completeProfilesInfo[$infoType]["ID"]             = $config["ID"];
					$this->completeProfilesInfo[$infoType]["VIEW_ALL_LINK"]  = $config["VIEW_ALL_LINK"];
                    $this->completeProfilesInfo[$infoType]["TRACKING"]       = (MobileCommon::isApp()=='A') ? $config["TRACKING"] : $this->getTracking($infoType);
					$this->completeProfilesInfo[$infoType]["VIEW_ALL_COUNT"] = $countObj[$infoType . "_ALL"];
					$this->completeProfilesInfo[$infoType]["NEW_COUNT"]      = $countObj[$infoType];
					$this->completeProfilesInfo[$infoType]["TITLE"]          = $config["TITLE"];
					if(array_key_exists("SUBTITLE",$config))
						$this->completeProfilesInfo[$infoType]["SUBTITLE"]          = $config["SUBTITLE"];
					$this->completeProfilesInfo[$infoType]["CONFIG_COUNT"]   = $config["COUNT"];
					if($config["TUPLE"]!="")
						$this->completeProfilesInfo[$infoType]["PARTIAL_NAME"]   = "_" . PROFILE_COMMUNICATION_ENUM_INFO::getClass($config["TUPLE"]);
					$this->completeProfilesInfo[$infoType]["SHOWING_START"] = ($this->completeProfilesInfo[$infoType]["CURRENT_NAV"] - 1) * $config["COUNT"] + 1;
					
					// Handling case if retrieved tuple count is less than config count 
					if ($config["COUNT"] && sizeof($values["TUPLES"]) < $config["COUNT"])
						$this->completeProfilesInfo[$infoType]["SHOWING_COUNT"] = sizeof($values["TUPLES"]) + $this->completeProfilesInfo[$infoType]["SHOWING_START"] - 1;
					else
						$this->completeProfilesInfo[$infoType]["SHOWING_COUNT"] = $config["COUNT"] + $this->completeProfilesInfo[$infoType]["SHOWING_START"] - 1;
					
					if ($this->completeProfilesInfo[$infoType]["CURRENT_NAV"] > 1)
						$this->completeProfilesInfo[$infoType]["SHOW_PREV"] = $this->completeProfilesInfo[$infoType]["CURRENT_NAV"] - 1;
					
					if ($config["COUNT"])
					{
						if($config["VIEW_FLAG"] == "ALL")
							$countToConsider = $countObj[$infoType . "_ALL"];
						else
							$countToConsider = $countObj[$infoType];
						if($countToConsider / $config["COUNT"] > $this->completeProfilesInfo[$infoType]["CURRENT_NAV"])
							$this->completeProfilesInfo[$infoType]["SHOW_NEXT"] = $this->completeProfilesInfo[$infoType]["CURRENT_NAV"] + 1;
						$this->completeProfilesInfo[$infoType]["NAVIGATION_INDEX"] = $this->getNavigationArray($this->completeProfilesInfo[$infoType]["CURRENT_NAV"], $countToConsider, $config["COUNT"]);
					}
          $this->completeProfilesInfo[$infoType]["CONTACT_ID"] = $this->profileObj->getPROFILEID().'_'.$infoType ;
				}
                        }
                        unset($infoTypeObj);
                        return $this->completeProfilesInfo;
                }
                return null;
        }
        
        /* This function will return the profiles which need to be skipped in myjs page according to business requirement
         *@param infotype : information type to find respective skip array 
         *@return skipProfiles : array of profile Ids need to be skipped
         */
        public function getSkipProfiles($infoType)
	{
		LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "class MyJs skipProfiles hit ");
		switch ($infoType) 
		{
			case 'HOROSCOPE_REQUEST_RECEIVED':
				$skipConditionArray = SkipArrayCondition::$HOROSCOPE_REQUEST;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'MESSAGE_RECEIVED':
				$skipConditionArray = SkipArrayCondition::$MESSAGE;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'PHOTO_REQUEST_RECEIVED':
				$skipConditionArray = SkipArrayCondition::$PHOTO_REQUEST;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'MATCH_ALERT':
				$skipConditionArray = SkipArrayCondition::$MATCHALERT;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
                        case 'MATCH_OF_THE_DAY':
				$skipConditionArray = SkipArrayCondition::$MATCHOFTHEDAY;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			default:
				$skipConditionArray = SkipArrayCondition::$default;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
		} 
		return $skipProfiles;
	}


	public function	getPidsFromProfilechecksums($profileChecksumString)
	{
		$profileChecksumArray = explode(",",$profileChecksumString);
		if(is_array($profileChecksumArray))
		{
			foreach($profileChecksumArray as $k=>$v)
			{
				$pid = JsAuthentication::jsDecryptProfilechecksum($v);
				if($pid)
					$pids[]=$pid;	
			}
			return $pids; 
		}
		else 
			return null;
	}
        
        /* This function will return the condition array required in myjs page according to business requirement
         *@param infotype : information type to find respective condition array for having profiles 
         *@param nav : handling ajax case navigation 
         *@return conditions : array of conditions to be used while getting profiles
         */
        public function getCondition($infoType, $nav = null)
        {
                $condition  = array();
                if ($infoType != "MATCH_ALERT" && $infoType != "VISITORS")
		{
                        //$condition["WHERE"]["NOT_IN"]["SEEN"] = "Y"; Commentted as no more required in app
                        if ($infoType == "INTEREST_RECEIVED")
			{
                                $condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
                                $yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
                                $back_90_days                                     = date("Y-m-d", $yday);
                                $condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
                                
                        }
                        if ($infoType == "MESSAGE_RECEIVED")
			{
                                $condition["WHERE"]["IN"]["IS_MSG"] = "Y";
                                $condition["WHERE"]["IN"]["TYPE"]   = "R";
                        }
                        if ($infoType == "INTEREST_EXPIRING")
                        {
							$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
							$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT, date("Y"));
							$bday                                             = mktime(0, 0, 0, date("m"), date("d") - (CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT - 1), date("Y"));
							$back_90_days                                     = date("Y-m-d", $yday);
							$back_83_days                                     = date("Y-m-d", $bday);
							$condition["WHERE"]["LESS_THAN_EQUAL_EXPIRING"]["TIME"] = "$back_90_days 00:00:00";
							$condition["WHERE"]["GREATER_THAN_EQUAL_EXPIRING"]["TIME"] = "$back_83_days 00:00:00";
						}

			$condition["LIMIT"] = $this->getLimit($infoType,$nav);
		}
		if($infoType == "VISITORS")
		{
			if (!is_null($nav))
				$condition["PAGE"]= intval($nav) - 1;
			else 
				$condition["PAGE"]=0;
			$condition["PROFILE_COUNT"]= $this->configurations[$infoType]["COUNT"];
		}
		if ($infoType == "MATCH_ALERT")
		{
			$condition["LIMIT"]= $this->configurations[$infoType]["COUNT"];
			$condition["NEW"] = 1;
      $condition["LOGIC"] = MatchAlertLogicEnum::MATCHES_LAST_SENT;
		}
		if ($infoType == "MATCH_OF_THE_DAY")
		{
			$condition["GENDER"] = $this->profileObj->getGENDER();
            $condition['PROFILEID'] = $this->profileObj->getPROFILEID();
            $condition['ENTRY_DT'] = date("Y-m-d 00:00:00", strtotime('now') - 7*24*3600);
            $condition['IGNORED'] = 'N';
		}
		
                $condition["ORDER"] = $this->configurations[$infoType]["TUPLE_ORDER"];
                return $condition;
        }

	public function getLimit($infoType, $nav = null)
	{
                $limit      = $this->configurations[$infoType]["COUNT"];
                $limitStart = 0;
                if ($infoType == "HOROSCOPE_REQUEST_RECEIVED" || $infoType == "PHOTO_REQUEST_RECEIVED")
		{
                	$limit      = $limitStart + $limit;
                        $limitStart = 0;
                }
                $condition = "$limitStart , $limit";
                if($infoType == "INTEREST_RECEIVED")
                	$condition = $limit;
		return $condition;
	}

        
        
        /*This function will handle the setting of Request Received information type which is combination of Photo and Horoscope request type */
        public function getRequestReceived()
        {
                if (is_array($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["TUPLES"]) && !is_array($this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["TUPLES"])) 
		{
                        $tuples                                                         = $this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["TUPLES"];
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["REQUEST_TYPE"] = "PHOTO_REQUEST_RECEIVED";
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["CURRENT_NAV"]  = $this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["CURRENT_NAV"];
                }
		elseif (!is_array($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["TUPLES"]) && is_array($this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["TUPLES"])) 
		{
                        $tuples                                                         = $this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["TUPLES"];
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["REQUEST_TYPE"] = "HOROSCOPE_REQUEST_RECEIVED";
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["CURRENT_NAV"]  = $this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["CURRENT_NAV"];
                        
                }
		elseif (is_array($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["TUPLES"]) && is_array($this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["TUPLES"]))
		{
                        $tuples = array_merge($this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["TUPLES"], $this->completeProfilesInfo["HOROSCOPE_REQUEST_RECEIVED"]["TUPLES"]);
                        uasort($tuples, array(
                                $this,
                                "compare_results"
                        ));
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["REQUEST_TYPE"] = "PHOTO_REQUEST_RECEIVED";
                        $this->completeProfilesInfo["REQUEST_RECEIVED"]["CURRENT_NAV"]  = $this->completeProfilesInfo["PHOTO_REQUEST_RECEIVED"]["CURRENT_NAV"];
                }
                $currentNav  = $this->completeProfilesInfo["REQUEST_RECEIVED"]["CURRENT_NAV"];
                $configCount = $this->configurations["PHOTO_REQUEST_RECEIVED"]["COUNT"];
                $this->setRequestReceivedTuple($tuples, $currentNav, $configCount);
        }
        
        /*This function is used to set tuples for Request Received type handled differently as this type is combination of Photo and Horoscope
        type and in accordance to current navigation and count required
        *@param tuples : array of tuple objects
        *@param currentNav : current navigation 
        *@param configCount : count of type request Received
        */
        public function setRequestReceivedTuple($tuples, $currentNav, $configCount)
        {
                $startIndex = ($currentNav - 1) * $configCount;
                $endIndex   = $startIndex + $configCount;
                $index      = 0;
                foreach ($tuples as $tuple => $values)
		{
                        if ($index >= $startIndex && $index < $endIndex)
                                $this->completeProfilesInfo["REQUEST_RECEIVED"]["TUPLES"][$values->getPROFILEID()] = $values;
                        $index++;
                }
                
        }
        
        /*This function is to compare time field of tuple object and send the descending order value
         *@param a,b: tuple objects
         *@return diff : diference to make time in ascending
         */
        public function compare_results($a, $b)
        {
                $diff = strcmp($a->getTIME(), $b->getTIME());
                if ($diff != 0)
                        return $diff < 0 ? 1 : -1; // descending order
                
                return 0;
        }
        
        /*This function will return the navigation array to be dispalyed for each information type
         *@param currentPage : Current page displayed for the required information type
         *@param totalCount : total new count for the information type
         *@param configCount : configured count for the information type
         *@return array of navigation page number to be shown for the information type 
         */
        public function getNavigationArray($currentPage, $totalCount, $configCount)
        {
                $navigationArray = Array();
                $displayConst    = 9;
                $totalPages      = ceil($totalCount / $configCount);
                if (($currentPage - 4) > 0)
                        $startIndex = $currentPage - 4;
                else
                        $startIndex = 1;
                if (($startIndex + $displayConst - 1) < $totalPages)
                        $endIndex = $startIndex + $displayConst - 1;
                else
                        $endIndex = $totalPages;
                for ($i = $startIndex; $i <= $endIndex; $i++)
		{
                        $navigationArray[] = $i;
                }
                return $navigationArray;
        }

    private function getTracking($listing){
		if(MobileCommon::isNewMobileSite())
		{
			$trackingMap=array(
                                "INTEREST_RECEIVED"=>"responseTracking=".JSTrackingPageType::MYJS_EOI_JSMS,
                                "VISITORS"=>"stype=".SearchTypesEnums::VISITORS_MYJS_JSMS,
                                "MATCH_ALERT"=>"stype=".SearchTypesEnums::MATCHALERT_MYJS_JSMS,
                                "INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING_JSMS
                               );
		}
		elseif(MobileCommon::isApp()=='I')
                        $trackingMap=array(
                                "INTEREST_RECEIVED"=>"responseTracking=".JSTrackingPageType::MYJS_EOI_IOS,
                                "INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING_MYJS_IOS,
                                "VISITORS"=>"stype=".SearchTypesEnums::VISITORS_MYJS_IOS,
                                "MATCH_ALERT"=>"stype=".SearchTypesEnums::MATCHALERT_MYJS_IOS,
                                "MATCH_OF_THE_DAY"=>SearchTypesEnums::MATCH_OF_THE_DAY_MYJS_IOS,
                                );

		return $trackingMap[$listing];
	}




        
}
?>
