<?php
/**
 * @brief This class implements module interface class and defines the functions as per the business logic
 * @author Reshu Rajput
 * @created 2013-09-27
 */
class Inbox implements Module
{
	private $profileObj;
	private $configurations;
	private $completeProfilesInfo;
	private $skipProfiles;
	private $considerProfiles;
	private $totalCount;
	private static $getTotal = "T";
	public static $profileCount = 10;
	
	//Constructor need profile object for myjs page 
	function __construct($module, Profile $profileObj)
	{
		if (!is_null($profileObj)) {
			$this->profileObj = $profileObj;
			$this->getConfiguration($module);
		} 
		else
			throw new JsException("", "Profile Object required in constructor Inbox.class.php");
	}


/*this function will set the 'seen' status of all photo requests as 'Y' for the profile id passed as parameter*/ 

		public static function setAllPhotoRequestsSeen($profileid) { 
		$dbName = JsDbSharding::getShardNo($profileid);
		$photoRequestQuery= new NEWJS_PHOTO_REQUEST($dbName);
		$photoRequestQuery->updateSeenStatusForAll($profileid);
		
		}

		public static function setAllHoroscopeRequestsSeen($profileid) { 
		$dbName = JsDbSharding::getShardNo($profileid);
		$horoscopeRequestQuery= new NEWJS_HOROSCOPE_REQUEST($dbName);
		$horoscopeRequestQuery->updateSeenStatusForAll($profileid);
		
		}
	





	/* This function will set the configuration object of myjs module
	 */

	public function getConfiguration($module)
	{
		$configObj            = new ProfileInformationModuleMap();
		$this->configurations = $configObj->getConfiguration($module);
	}
	public function getInboxConfiguration()
	{
		return $this->configurations;
	}
	/* This function will return the myjs specific count object
	 *@param allFlag : its not null if count of new and all both is required else only new are retrieved
	 *@return countObj : countObj with infotype and corresponding count mapping
	 */
	public function getCount($allFlag = '', $infoTypenav = '', $fromGetDisplayFunction='')
	{
		
		try {
                        $memcacheServiceObj = new ProfileMemcacheService($this->profileObj);
			if (is_array($infoTypenav) && ($infoTypenav["NUMBER"]==null || $infoTypenav["NUMBER"]==1) && $fromGetDisplayFunction=='')
			{
				$memcacheServiceObj->unsetKey('CONTACTED_ME');
                            
			}
                        
			$countObj           = array();
			$ifHoroscopePresent = "";
			$ifPhotoPresent     = "";
			
			if($infoTypenav) {
				unset($key);
				switch ($infoTypenav["PAGE"]) {
					case "INTEREST_RECEIVED":
					case "INTEREST_RECEIVED_FILTER":
						$keyNew = "AWAITING_RESPONSE_NEW";
						$key = "AWAITING_RESPONSE";
						break;
					case "FILTERED_INTEREST":
						$key = "FILTERED";
						break;
					case "ACCEPTANCES_RECEIVED":
						$key = "ACC_ME";
						$keyNew = "ACC_ME_NEW";
						break;
					case "MY_MESSAGE":
                                   
						$keyNew = "MESSAGE_NEW";
						$key = "MESSAGE_ALL";
						break;
					case "MESSAGE_RECEIVED":
                    case "MY_MESSAGE_RECEIVED":
						$keyNew = "MESSAGE_NEW";
						$key = "MESSAGE";
						break;
					case "VISITORS":
						$key = "VISITOR_ALERT";
						break;
					case "MATCH_ALERT":
						$key = "MATCHALERT_TOTAL";
						break;
					case "PHOTO_REQUEST_RECEIVED":
						$keyNew = "PHOTO_REQUEST_NEW";
						$key = "PHOTO_REQUEST";
						break;
					case "PHOTO_REQUEST_SENT":
						$key = "PHOTO_REQUEST_BY_ME";
						break;
					case "HOROSCOPE_REQUEST_SENT":
						$key = "HOROSCOPE_REQUEST_BY_ME";
						break;
					case "HOROSCOPE_REQUEST_RECEIVED":
						$key = "HOROSCOPE";
						break;
					case "INTRO_CALLS":
						if($infoTypenav["NUMBER"] == 1)
							$memcacheServiceObj->setINTRO_CALLSData();
						$key = "INTRO_CALLS";
						break;
					case "INTRO_CALLS_COMPLETE":
						if($infoTypenav["NUMBER"] == 1)
							$memcacheServiceObj->setINTRO_CALLSData();
						$key = "INTRO_CALLS_COMPLETE";
						break;
					case "ACCEPTANCES_SENT":
						$key = "ACC_BY_ME";
						$keyNew = "ACC_ME_NEW";
						break;
					case "INTEREST_SENT":
						$key = "NOT_REP";
						break;
					case "SHORTLIST":
						if($infoTypenav["NUMBER"] == 1 && $fromGetDisplayFunction=='')
						{
							$memcacheServiceObj->setBookmarkData();
						}
						$key = "BOOKMARK";
						break;
					case "NOT_INTERESTED":
						$key = "DEC_ME";
						$keyNew = "DEC_ME_NEW";
						break;
					case "NOT_INTERESTED_BY_ME":
						$key = "DEC_BY_ME";
						break;
					case "CONTACTS_VIEWED":
						if($infoTypenav["NUMBER"] == 1 && $fromGetDisplayFunction=='')
							$memcacheServiceObj->setContactsViewedData();
						$key = "CONTACTS_VIEWED";
						break;
					case "PEOPLE_WHO_VIEWED_MY_CONTACTS":
						if($infoTypenav["NUMBER"] == 1 && $fromGetDisplayFunction=='')
							$memcacheServiceObj->setContactViewersData();
						$key = "PEOPLE_WHO_VIEWED_MY_CONTACTS";	
						break;
					case "IGNORED_PROFILES":
						$key = "IGNORED_PROFILES";
						$memKeyNotExists=1;
						break;
					case "INTEREST_ARCHIVED":
						$key = "INTEREST_ARCHIVED";
						break;
					case "INTEREST_EXPIRING":
						$key = "INTEREST_EXPIRING";
						break;
					case "MATCH_OF_THE_DAY":
						$key = "MATCH_OF_THE_DAY";
                                                $memKeyNotExists=1;
						break;

				}

				if($key == "IGNORED_PROFILES")
				{
					$IgnoredObj = new IgnoredProfiles;
                                        $pid=$this->profileObj->getPROFILEID();
                                        $IgnoredListArray = $IgnoredObj->getIgnoredProfile($pid);
                                        foreach ($IgnoredListArray as $key => $value) {
                                                $IgnoredList[]=$key;
                                        }

                                        $countObj[$infoTypenav["PAGE"]] = count($IgnoredList);
				}
                                
				if ($keyNew != "")
					$countObj[$infoTypenav["PAGE"]."_NEW"] = $memcacheServiceObj->get($keyNew);
				if ($key != "" && $memKeyNotExists!=1)
				{ 
					$countObj[$infoTypenav["PAGE"]] = $memcacheServiceObj->get($key);
					
				}
			} 
			
			if($infoTypenav["NUMBER"] == 1 && $infoTypenav["PAGE"] != "IGNORED_PROFILES" && $infoTypenav["PAGE"] != "MY_MESSAGE_RECEIVED")
			{
				if($infoTypenav["PAGE"] == "VISITORS")
				{
					$visitorObj = new Visitors($this->profileObj);
					$visitors = $visitorObj->getVisitorProfile("","",$infoTypenav);
					$countObj[$infoTypenav["PAGE"]] = count($visitors);
				}
				
			}
			
			return $countObj;
		}
		catch (Exception $e) {
			throw new jsException($e);
		}
	}
	/* This function will return the whole display object  calling the module 
	 *@param infoTypeNav : optional required only if information type is called by ajax, it should be key value pair of information type and                              navigation page number required to be retrieved 
	 *@return moduleDisplayObj : complete object of all the information requested by the module
	 */
	public function getDisplay($infoTypeNav = null,$params=null)
	{  
		$fields       = Array("profilechecksum");
		$profiles     = Array();
		$fromGetDisplayFunction=1;
		$countObj     = $this->getCount('',$infoTypeNav,$fromGetDisplayFunction);
		$tupleService = new TupleService(); 
		$tupleService->setLoginProfile($this->profileObj->getPROFILEID());
		$tupleService->setLoginProfileObj($this->profileObj);
		$key = $this->profileObj->getPROFILEID()."_".$infoTypeNav["PAGE"];
		$keyCount = $key."_COUNT"; 
		$infoType = $infoTypeNav["PAGE"];
		// Set limit too high as pagination not implemented in channels others than desktop for messages
		if(!MobileCommon::isDesktop() && ($infoType == "MESSAGE_RECEIVED" || $infoType == "MY_MESSAGE" || $infoType == "MY_MESSAGE_RECEIVED") && ($infoTypeNav["NUMBER"]==null || MobileCommon::isApp()==null))
		{
						$this->configurations[$infoType]["COUNT"]=10000;

		}
		$config = $this->configurations[$infoTypeNav["PAGE"]];
		if ($infoTypeNav && $config) {
				$tuple       = $config["TUPLE"];
				$displayFlag = 1;
				if (is_array($infoTypeNav) && $infoTypeNav["NUMBER"]!=null)
					$nav = $infoTypeNav["NUMBER"];
				else
					$nav =1;
				
				if(($nav == 1))
				{
					
					JsMemcache::getInstance()->remove($key);
					JsMemcache::getInstance()->set($keyCount,$countObj[$infoType]);
					$this->totalCount = $countObj[$infoType];
					
				}
				else
				{
					
					if(JsMemcache::getInstance()->get($keyCount))
						$this->totalCount = JsMemcache::getInstance()->get($keyCount);
					else 
						$this->totalCount = $countObj[$infoType];
				}
			
				if ($displayFlag && PROFILE_COMMUNICATION_ENUM_INFO::ifInformationTypeExists($infoType) && PROFILE_COMMUNICATION_ENUM_INFO::ifTupleExists($tuple)) {
					$memdata =  JsMemcache::getInstance()->get($key);
					$data = unserialize(JsMemcache::getInstance()->get($key));
					if(empty($memdata) || ($nav-1)*$config["COUNT"] >=count($data) || (count($data) == 0 && $countObj[$infoType]))
					{
						$infoTypeAdapter = new InformationTypeAdapter($infoType, $this->profileObj->getPROFILEID());
						// Myjs require only New information type tuples 
											
						$skipArray       = $this->getSkipProfiles($infoType);
						
						if($infoType == "VISITORS" || $infoType== "HOROSCOPE_REQUEST_RECEIVED" || $infoType=="HOROSCOPE_REQUEST_SENT")
						{
							$page = $nav;
						}
						else if(!empty($memdata))
						{
							foreach($data as $profileid => $value)
							{
								$profileArray[] = $profileid;
							}
							if(is_array($skipArray))
								$skipArray = array_merge($skipArray,$profileArray);	
							else
								$skipArray = $profileArray;
								
							$page =	ceil(($nav*$this->configurations[$infoType]["COUNT"] - count($data)) /self::$profileCount);
						}
						else
						{ 
							$page = $nav;
						}
						if(InboxEnums::$messageLogInQuery && ( $infoType=="MY_MESSAGE" || $infoType=="MESSAGE_RECEIVED" || $infoType=="MY_MESSAGE_RECEIVED"))
						{
							if(is_array($skipArray))
								$this->considerProfiles = array_diff($this->considerProfiles,$skipArray);
						}
						$conditionArray = $this->getCondition($infoType, $page); 
                                                if($infoType == "MY_MESSAGE"){
                                                    $conditionArray['LIMIT']++;
                                                    $conditionArray["pageNo"]=$nav;
                                                }
                                                if($infoTypeNav["matchedOrAll"])
                                                    $conditionArray["matchedOrAll"] = $infoTypeNav["matchedOrAll"];
						if(InboxEnums::$messageLogInQuery && ( $infoType=="MY_MESSAGE" || $infoType=="MESSAGE_RECEIVED" || $infoType=="MY_MESSAGE_RECEIVED" ))
						{
							if(is_array($this->considerProfiles) && count($this->considerProfiles)>0)
								$profilesArray = $infoTypeAdapter->getProfiles($conditionArray, $skipArray,$this->profileObj->getSUBSCRIPTION(),$this->considerProfiles);
						}
						else
							$profilesArray = $infoTypeAdapter->getProfiles($conditionArray, $skipArray,$this->profileObj->getSUBSCRIPTION());
                                                if($infoType == "MATCH_OF_THE_DAY" && JsMemcache::getInstance()->get("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileObj->getPROFILEID())){
                                                        $this->totalCount = JsMemcache::getInstance()->get("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileObj->getPROFILEID());
                                                }
                                                if($infoType == "MY_MESSAGE"){
                                                    	if(count($profilesArray)==$conditionArray['LIMIT'])
                                                        	array_pop($profilesArray);
                                                }
        
					 	if(!empty($memdata) && is_array($data) && is_array($profilesArray)){
					//		print_r(count($data));
							$data = $data+$profilesArray;
						}
						else if(is_array($profilesArray))
							$data = $profilesArray;
					//	print_r($data);  die;
						JsMemcache::getInstance()->set($key,serialize($data),1800);
					 
					}
					if(is_array($data))
					{
						$pager = array_slice($data,($infoTypeNav["NUMBER"]-1)*$config["COUNT"],$config["COUNT"],true);
					}
					$infoTypeObj[$infoType] = $pager;
					if (!empty($infoTypeObj[$infoType])) {

						// Get required tuple, unique fields required by the tuples
						$tupleFields            = $tupleService->getFields($tuple);
						$fields                 = array_merge($fields, $tupleFields);
						$nameOfUserObj = new NameOfUser;
						$profileNameData = $nameOfUserObj->getNameData($this->profileObj->getPROFILEID());
						if($profileNameData[$this->profileObj->getPROFILEID()]['DISPLAY']=="Y")
						{
							$fields[]="NAME_OF_USER";
						}
						//print_r($fields );die;
						//Attaching the callout message,icons and buttons ids  with each profile
						$infoTypeObj[$infoType] = $tupleService->setButtonIds($config["BUTTONS"], $infoTypeObj[$infoType]);
					} 
					
				}
			} 
		unset($conditionArray);
		unset($skipArray);
		//Creating Final object including infotype based all the information
		if (is_array($infoTypeObj)) {
			// Calling tuple service to retrieve complete information of all the profiles in one go  
			$tupleService->setProfileInfo($infoTypeObj, array_unique($fields),$profilesArray);

			if ($config) {
				unset($tuplesValues);
				if ($config["TUPLE"])
					eval('$tuplesValues = $tupleService->get' . $infoType . '();');
				// Assigning infotype configurations and tuples
				if (sizeof($tuplesValues) > 0)
					$this->completeProfilesInfo[$infoType]["TUPLES"] = $tuplesValues;
				//Handling ajax case
				if (is_array($infoTypeNav) )
					$this->completeProfilesInfo[$infoType]["CURRENT_NAV"] = $nav;
				else
					$this->completeProfilesInfo[$infoType]["CURRENT_NAV"] = 1;
			} //$this->configurations as $infoType => $config
			$config = $this->configurations[$infoType];
			//var_dump($this->totalCount);die;
			$this->completeProfilesInfo[$infoType]["ID"]             = $config["ID"];
			$this->completeProfilesInfo[$infoType]["VIEW_ALL_COUNT"] = $this->totalCount;
			//$this->completeProfilesInfo[$infoType]["VIEW_ALL_COUNT"] = JsMemcache::getInstance()->get("message_count_".LoggedInProfile::getInstance()->getPROFILEID());
			$this->completeProfilesInfo[$infoType]["NEW_COUNT"]      = $countObj[$infoType. "_NEW"];
			$this->completeProfilesInfo[$infoType]["TITLE"]          = $config["TITLE"];
			$this->completeProfilesInfo[$infoType]["HEADING"]          = $config["HEADING"];
			$this->completeProfilesInfo[$infoType]["CCMESSAGE"]          = $config["CCMESSAGE"];
			//if($infoType !="MY_MESSAGE" && $infoType !="MY_MESSAGE_RECEIVED")
			{
			if (array_key_exists("SUBTITLE", $config))
				$this->completeProfilesInfo[$infoType]["SUBTITLE"] = $config["SUBTITLE"];
			$this->completeProfilesInfo[$infoType]["CONFIG_COUNT"] = $config["COUNT"];
			if ($config["TUPLE"] != "")
				$this->completeProfilesInfo[$infoType]["PARTIAL_NAME"] = "_" . PROFILE_COMMUNICATION_ENUM_INFO::getClass($config["TUPLE"]);
			$this->completeProfilesInfo[$infoType]["SHOWING_START"] = ($this->completeProfilesInfo[$infoType]["CURRENT_NAV"] - 1) * $config["COUNT"] + 1;
			// Handling case if retrieved tuple count is less than config count 
			if ($config["COUNT"] && sizeof($values["TUPLES"]) < $config["COUNT"])
				$this->completeProfilesInfo[$infoType]["SHOWING_COUNT"] = sizeof($values["TUPLES"]) + $this->completeProfilesInfo[$infoType]["SHOWING_START"] - 1;
			else
				$this->completeProfilesInfo[$infoType]["SHOWING_COUNT"] = $config["COUNT"] + $this->completeProfilesInfo[$infoType]["SHOWING_START"] - 1;
			if ($this->completeProfilesInfo[$infoType]["CURRENT_NAV"] > 1)
				$this->completeProfilesInfo[$infoType]["SHOW_PREV"] = $this->completeProfilesInfo[$infoType]["CURRENT_NAV"] - 1;
			if ($config["COUNT"]) {
				if ($this->totalCount / $config["COUNT"] > $this->completeProfilesInfo[$infoType]["CURRENT_NAV"])
					$this->completeProfilesInfo[$infoType]["SHOW_NEXT"] = $this->completeProfilesInfo[$infoType]["CURRENT_NAV"] + 1;            
                                //elseif ($infoType == "MY_MESSAGE" && $this->totalCount / $config["COUNT"] > 1)
				//	$this->completeProfilesInfo[$infoType]["SHOW_NEXT"] = $this->completeProfilesInfo[$infoType]["CURRENT_NAV"] + 1;
				$this->completeProfilesInfo[$infoType]["NAVIGATION_INDEX"] = $this->getNavigationArray($this->completeProfilesInfo[$infoType]["CURRENT_NAV"], $this->totalCount, $config["COUNT"]);
                                if($infoType=="VISITORS" && $config["TRACKING"]=="stype=AV" && $infoTypeNav['matchedOrAll']=="M")
                                    $this->completeProfilesInfo[$infoType]["TRACKING"] = "stype=".SearchTypesEnums::MATCHING_VISITORS_ANDROID;
                                else
                                    $this->completeProfilesInfo[$infoType]["TRACKING"] = $config["TRACKING"];
				$this->completeProfilesInfo[$infoType]["contact_id"] = $key;
				$this->completeProfilesInfo[$infoType]["self_profileid"] = $this->profileObj->getPROFILEID();
				if($infoType == "INTEREST_RECEIVED_FILTER")
					if($this->totalCount <=20)
					{
						$memcacheServiceObj = new ProfileMemcacheService($this->profileObj);
						$this->completeProfilesInfo[$infoType]["filterCount"] = $memcacheServiceObj->get("FILTERED");
					}
			} //$config["COUNT"]
			} //$this->completeProfilesInfo as $infoType => $values
			unset($infoTypeObj); 
			// var_dump($this->completeProfilesInfo);
			// die();
			return $this->completeProfilesInfo;
		} //is_array($infoTypeObj)
		return null;
	}
	/* This function will return the profiles which need to be skipped in myjs page according to business requirement
	 *@param infotype : information type to find respective skip array 
	 *@return skipProfiles : array of profile Ids need to be skipped
	 */
	public function getSkipProfiles($infoType)
	{
		
		$memcacheServiceObj = new ProfileMemcacheService($this->profileObj);
		$memcacheServiceObj->setSKIP_PROFILES();
		if(count($this->skipProfiles)==0)
		switch ($infoType) {
			case 'HOROSCOPE_REQUEST_RECEIVED':
				$skipConditionArray = SkipArrayCondition::$HOROSCOPE;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'MESSAGE_RECEIVED':
			case 'MY_MESSAGE':
			case "MY_MESSAGE_RECEIVED":
				$skipConditionArray = SkipArrayCondition::$MESSAGE;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				if(InboxEnums::$messageLogInQuery)
				{
					$considerArray = SkipArrayCondition::$MESSAGE_CONSIDER;
					$this->considerProfiles =  $skipProfileObj->getSkipProfiles($considerArray);
				}
				break;
			case 'PHOTO_REQUEST_RECEIVED':
				$skipConditionArray = SkipArrayCondition::$PHOTO_REQUEST;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'PHOTO_REQUEST_SENT':
				$skipConditionArray = SkipArrayCondition::$PHOTO_REQUEST;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'INTRO_CALLS':
				$skipConditionArray = SkipArrayCondition::$INTRO_CALLS;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'INTRO_CALLS_COMPLETE':
				$skipConditionArray = SkipArrayCondition::$INTRO_CALLS_COMPLETE;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'HOROSCOPE_REQUEST_SENT':
				$skipConditionArray = SkipArrayCondition::$HOROSCOPE;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'PEOPLE_WHO_VIEWED_MY_CONTACTS':
				$skipConditionArray = SkipArrayCondition::$PEOPLE_WHO_VIEWED_MY_CONTACTS;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'CONTACTS_VIEWED':
				$skipConditionArray = SkipArrayCondition::$CONTACTS_VIEWED;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;	
			case 'SHORTLIST':
				$skipConditionArray = SkipArrayCondition::$SHORTLIST;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'MATCH_ALERT':
				$skipConditionArray = SkipArrayCondition::$MATCHALERT;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'MATCH_OF_THE_DAY':
				$skipConditionArray = SkipArrayCondition::$MATCHOFTHEDAY;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
				break;
			case 'IGNORED_PROFILES': $this->skipProfiles = array();
			  break;
			default:
				$skipConditionArray = SkipArrayCondition::$default;
				$skipProfileObj     = SkipProfile::getInstance($this->profileObj->getPROFILEID());
				$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray); 
				
				break;
		} //$infoType
		
		return $this->skipProfiles;
	}
	/* This function will return the condition array required in myjs page according to business requirement
	 *@param infotype : information type to find respective condition array for having profiles 
	 *@param nav : handling ajax case navigation 
	 *@return conditions : array of conditions to be used while getting profiles
	 */
	public function getCondition($infoType, $nav = null)
	{
		$condition  = array();
		if($infoType!="VISITORS")
			$limit      = ceil(($this->configurations[$infoType]["COUNT"]*$nav)/self::$profileCount)*self::$profileCount;
		else
			$limit = $this->configurations[$infoType]["COUNT"];
		if ($infoType != "MATCH_ALERT" && $infoType != "VISITORS") {
			//$condition["WHERE"]["NOT_IN"]["SEEN"] = "Y";
			if ($infoType == "INTEREST_RECEIVED") {
				$condition["WHERE"]["NOT_IN"]["FILTERED"]         = array('Y','J');
				$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
				$back_90_days                                     = date("Y-m-d", $yday);
				$condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
			} //$infoType == "INTEREST_RECEIVED"

			if ($infoType == "INTEREST_ARCHIVED") {
				$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
				$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
				$back_90_days                                     = date("Y-m-d", $yday);
				$condition["WHERE"]["LESS_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
			}

			if($infoType == "INTEREST_RECEIVED_FILTER")
			{
				$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
				$back_90_days                                     = date("Y-m-d", $yday);
				$condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
				if($this->totalCount>=20)
				{
					$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
					$limit = $this->totalCount==20?20:19;
				}	

			}
			if ($infoType == "INTEREST_EXPIRING") {
				$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
				$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::EXPIRING_INTEREST_UPPER_LIMIT, date("Y"));
				$bday                                             = mktime(0, 0, 0, date("m"), date("d") - (CONTACTS::EXPIRING_INTEREST_LOWER_LIMIT - 1), date("Y"));
				$back_90_days                                     = date("Y-m-d", $yday);
				$back_83_days                                     = date("Y-m-d", $bday);
				$condition["WHERE"]["LESS_THAN_EQUAL_EXPIRING"]["TIME"] = "$back_90_days 00:00:00";
				$condition["WHERE"]["GREATER_THAN_EQUAL_EXPIRING"]["TIME"] = "$back_83_days 00:00:00";
			}


		if ($infoType == "FILTERED_INTEREST") {
				$yday                                             = mktime(0, 0, 0, date("m"), date("d") - CONTACTS::INTEREST_RECEIVED_UPPER_LIMIT, date("Y"));
				$back_90_days                                     = date("Y-m-d", $yday);
				$condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
			}

			if ($infoType == "MESSAGE_RECEIVED") {
				$condition["WHERE"]["IN"]["IS_MSG"] = "Y";
				$condition["WHERE"]["IN"]["TYPE"]   = "R";
			} //$infoType == "MESSAGE_RECEIVED"
			/*if (!is_null($nav)) {
				$limitStart = (intval($nav) - 1) * intval($limit);
			} //!is_null($nav)*/
			
				$condition["LIMIT"] = "$limit";
			$condition["ORDER"] = $this->configurations[$infoType]["TUPLE_ORDER"]?$this->configurations[$infoType]["TUPLE_ORDER"]:'TIME';
                        
		} //$infoType != "MATCH_ALERT" && $infoType != "VISITORS"
		if ($infoType == "VISITORS") {
			if (!is_null($nav))
				$condition["PAGE"] = intval($nav) - 1;
			else
				$condition["PAGE"] = 0;
			$condition["PROFILE_COUNT"] = $limit;
		} //$infoType == "VISITORS"
		if ($infoType == "MATCH_ALERT")
		{
			$condition["NEW"] = 0;
		}
		if ($infoType == "MATCH_OF_THE_DAY")
		{
			$condition["GENDER"] = $this->profileObj->getGENDER();
                        $condition['PROFILEID'] = $this->profileObj->getPROFILEID();
                        $condition['ENTRY_DT'] = date("Y-m-d 00:00:00", strtotime('now') - 7*24*3600);
                        $condition['IGNORED'] = 'N';
		}
		return $condition;
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
		for ($i = $startIndex; $i <= $endIndex; $i++) {
			$navigationArray[] = $i;
		} //$i = $startIndex; $i <= $endIndex; $i++
		return $navigationArray;
	}
	
}
?>
