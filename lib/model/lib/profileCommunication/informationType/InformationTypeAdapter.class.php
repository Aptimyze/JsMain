<?php
/*
This is adapter class for different Information Type.
Author : Reshu Rajput
Created on :  2013-10-09
*/
class InformationTypeAdapter
{
    private $infoType;
    private $profileId;
    public function __construct($infoType, $profileId)
    { 
        $this->infoType  = $infoType;
        $this->profileId = $profileId;
    }
    
    public function getProfiles($condition, $skipArray,$subscription="",$considerProfiles = '')
    {
		$profilesArray = array();
       
		switch ($this->infoType) {
            case "INTEREST_RECEIVED":
                $contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
             case "INTEREST_ARCHIVED":
                $contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            case "INTEREST_RECEIVED_FILTER":
                $contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            case "INTEREST_EXPIRING":
                $contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            case "CONTACTS_VIEWED":
				if(CommonFunction::isPaid($subscription))
                {
									$limit = array_key_exists("LIMIT",$condition)?$condition["LIMIT"]:'';
					$contactsObj                          = new JSADMIN_VIEW_CONTACTS_LOG();
					$profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId,$skipArray,$limit);
				}
                break;
			case "PEOPLE_WHO_VIEWED_MY_CONTACTS":
                $contactsObj                          = new JSADMIN_VIEW_CONTACTS_LOG();
                $limit = array_key_exists("LIMIT",$condition)?$condition["LIMIT"]:'';
                $profilesArray                        = $contactsObj->getProfilesWhoViewedMyContacts($this->profileId,$skipArray,$limit);
                break;
            case "FILTERED_INTEREST":
                $contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["FILTERED"]     = "Y";
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;

            case "INTEREST_SENT":
				$contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::INITIATED;
                $condition["WHERE"]["IN"]["SENDER"] = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            case "ACCEPTANCES_RECEIVED":
                $contactsObj                        = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]   = ContactHandler::ACCEPT;
                $condition["WHERE"]["IN"]["SENDER"] = $this->profileId;
                $profilesArray                      = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            case "ACCEPTANCES_SENT":
                $contactsObj                        = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]   = ContactHandler::ACCEPT;
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                      = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                break;
            
            case "MESSAGE_RECEIVED":
                $messageLogObj                        = new MessageLog();
                $condition["WHERE"]["IN"]["RECEIVER"] = $this->profileId;
                $profilesArray                         = $messageLogObj->getMessageLogProfile($this->profileId, $condition, $skipArray);
                break;
            case "HOROSCOPE_REQUEST_RECEIVED":
                $horoscopeObj                                     = new Horoscope();
                $condition["WHERE"]["IN"]["PROFILEID_REQUEST_BY"] = $this->profileId;
                $profilesArray                                     = $horoscopeObj->getHoroscopeRequestProfile($this->profileId, $condition, $skipArray);
                break;
            case "HOROSCOPE_REQUEST_SENT":
                $horoscopeObj                                     = new Horoscope();
                $condition["WHERE"]["IN"]["PROFILEID"] = $this->profileId;
                $profilesArray                                     = $horoscopeObj->getHoroscopeRequestProfile($this->profileId, $condition, $skipArray);
                break;
            case "PHOTO_REQUEST_RECEIVED":
                $photoRequestObj                              = new PhotoRequest();
                $condition["WHERE"]["IN"]["PROFILEID_REQ_BY"] = $this->profileId;
                $profilesArray                                 = $photoRequestObj->getPhotoRequestProfile($this->profileId, $condition, $skipArray);
                break;
            case "PHOTO_REQUEST_SENT":
                $photoRequestObj                              = new PhotoRequest();
                $condition["WHERE"]["IN"]["PROFILEID"] = $this->profileId;
                $profilesArray                                 = $photoRequestObj->getPhotoRequestSentProfile($this->profileId, $condition, $skipArray);
                break;
            case "INTRO_CALLS":
                $introCallObj                              = new getIntroCallHistory();
                $condition["WHERE"]["IN"]["PROFILEID"] = $this->profileId;
                $profilesArray                                 = $introCallObj->getHistoryOfIntroCallsPending($this->profileId, $condition, $skipArray);
                break;
             case "INTRO_CALLS_COMPLETE":
                $introCallObj                              = new getIntroCallHistory();
                $condition["WHERE"]["IN"]["PROFILEID"] = $this->profileId;
                $profilesArray                                 = $introCallObj->getHistoryOfIntroCallsComplete($this->profileId, $condition, $skipArray);
                break;
	    case "VISITORS":
	        $visitorObj                              = new Visitors(LoggedInProfile::getInstance('newjs_master'));
                $profilesArray                           = $visitorObj->getVisitorProfile($condition["PAGE"],$condition["PROFILE_COUNT"],array("matchedOrAll"=>$condition["matchedOrAll"]));
			break;
	    case "MY_MATCHES":
		$SearchCommonFunctions = new SearchCommonFunctions;
		$profilesArray1 = $SearchCommonFunctions->getMyDppMatches(SearchSortTypesEnums::dateSortFlag,'',$condition["LIMIT"]);
		if(is_array($profilesArray1["PIDS"]))
		foreach($profilesArray1["PIDS"] as $k=>$v)
			$profilesArray[$v] = array();
			break;
	    case "MATCH_ALERT":
                $matchAlertObj                              = new MatchAlerts();
        $matchAlertLogic = isset($condition["LOGIC"]) ? $condition["LOGIC"] : ''; 
                $profilesArray                              = $matchAlertObj->getMatchAlertProfile($this->profileId,$skipArray,$condition["LIMIT"],$condition["NEW"],$matchAlertLogic);
                break;
	    case "MY_MESSAGE":
                $messageLogObj                        = new MessageLog();
                $condition["WHERE"]["IN"]["PROFILE"] = $this->profileId;
                $condition["WHERE"]["IN"]["IS_MSG"]   = "Y";
                $condition["WHERE"]["IN"]["TYPE"]     = "R";
                $profilesArray                         = $messageLogObj->getMessageListing($this->profileId, $condition, $skipArray,$considerProfiles);
                break;
        case "MY_MESSAGE_RECEIVED":
                $messageLogObj                        = new MessageLog();
                $condition["WHERE"]["IN"]["PROFILE"] = $this->profileId;
                $condition["WHERE"]["IN"]["IS_MSG"]   = "Y";
                $condition["WHERE"]["IN"]["TYPE"]     = "R";
                $profilesArray                         = $messageLogObj->getMessageReceivedListing($this->profileId, $condition, $skipArray);
                break;
        case "SHORTLIST":
				$shortlistObj							= new Bookmarks();
				$condition["WHERE"]["IN"]["BOOKMARKER"] = $this->profileId;
				$profilesArray							= $shortlistObj->getBookmarkedProfile($this->profileId,$condition, $skipArray);
				break;
		case "IGNORED_PROFILES":
				$IgnoredObj							= new IgnoredProfiles;
				$profilesArray							= $IgnoredObj->getIgnoredProfile($this->profileId,$condition,$skipArray);
				/*foreach ($profilesArray as $key => $value) {
					$profilesArray[$value] = null;
					unset($profilesArray[$key]);
				}
				print_r($profilesArray);die;*/
				break;
		case "NOT_INTERESTED":
                $limit = $condition["LIMIT"];
                
		$contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::DECLINE;
                $condition["WHERE"]["IN"]["SENDER"]   = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                unset($condition["WHERE"]["IN"]["TYPE"]);
                unset($condition["WHERE"]["IN"]["SENDER"]);
                $condition["WHERE"]["IN"]["RECEIVER"]   = $this->profileId;
                $condition["WHERE"]["IN"]["TYPE"][0]     = ContactHandler::CANCEL;
                $condition["WHERE"]["IN"]["TYPE"][1]     = ContactHandler::CANCEL_CONTACT;
                $profilesArray1                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                unset($condition["WHERE"]["IN"]["TYPE"]);
                unset($condition["WHERE"]["IN"]["RECEIVER"]);
		if(is_array($profilesArray))
		{
			if(is_array($profilesArray1))
			{
				$profilesArray = $profilesArray + $profilesArray1;
			}
		}
		else
		{
			if(is_array($profilesArray1))
			{
				$profilesArray = $profilesArray1;
			}
		}
                uasort($profilesArray,array($this,"cmp"));
		if(is_array($profilesArray))
			$profilesArray = array_slice($profilesArray,0,$limit,true);
                break;
		
                case "NOT_INTERESTED_BY_ME":
                $limit = $condition["LIMIT"];
                
		$contactsObj                          = new ContactsRecords();
                $condition["WHERE"]["IN"]["TYPE"]     = ContactHandler::DECLINE;
                $condition["WHERE"]["IN"]["RECEIVER"]   = $this->profileId;
                $profilesArray                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                unset($condition["WHERE"]["IN"]["TYPE"]);
                unset($condition["WHERE"]["IN"]["RECEIVER"]);
                $condition["WHERE"]["IN"]["SENDER"]   = $this->profileId;
                $condition["WHERE"]["IN"]["TYPE"][0]     = ContactHandler::CANCEL;
                $condition["WHERE"]["IN"]["TYPE"][1]     = ContactHandler::CANCEL_CONTACT;
                $profilesArray1                        = $contactsObj->getContactedProfileArray($this->profileId, $condition, $skipArray);
                unset($condition["WHERE"]["IN"]["TYPE"]);
                unset($condition["WHERE"]["IN"]["SENDER"]);
                if(is_array($profilesArray))
								{
                        if(is_array($profilesArray1))
												{
                                $profilesArray = $profilesArray + $profilesArray1;
												}
								}
                else
								{
                        if(is_array($profilesArray1))
												{
                                $profilesArray = $profilesArray1;
												}
								}
								if(is_array($profilesArray))
	                uasort($profilesArray,array($this,"cmp"));
								$profilesArray = array_slice($profilesArray,0,$limit,true);
                break;
            case "MATCH_OF_THE_DAY":
                        $matchOfDayObj = new MOBILE_API_MATCH_OF_DAY('newjs_master');
                        $profilesArray = $matchOfDayObj->getMatchForProfileForListing($condition, $skipArray);
                        if($condition["GENDER"] == 'F'){
                                $searchObj = new NEWJS_SEARCH_MALE();
                        }else{
                                $searchObj = new NEWJS_SEARCH_FEMALE();
                        }
                        if(!empty($profilesArray)){
                                $data = $searchObj->getArray(array("PROFILEID"=>implode(',',$profilesArray)));
                                $profilesArray1 = array();
                                $profilesArraySorted = array();
                                if(!empty($data)){
                                        foreach($data as $profiles){
                                                $profilesArray1[] = $profiles["PROFILEID"];
                                        }
                                        foreach($profilesArray as $profileid){
                                                if(in_array($profileid, $profilesArray1))
                                                        $profilesArraySorted[$profileid]["PROFILEID"] =  $profileid;
                                        }
                                        unset($profilesArray);
                                        $profilesArray = $profilesArraySorted;
                                }else{
                                    $profilesArray = array();
                                }
                        }else{
                                $profilesArray = array();
                        }
                        JsMemcache::getInstance()->set("MATCHOFTHEDAY_VIEWALLCOUNT_".$this->profileId,  count($profilesArray));
                break;
        
            default:
				throw new JsException("","Wrong infoType is given in InformationTypeAdapter.class.php");
        }
        return $profilesArray;
        
    }
    
    private static function cmp($a,$b)
    {
		if ($a["TIME"] == $b["TIME"]) {
			return 0;
		}
		return ($a["TIME"] > $b["TIME"]) ? -1 : 1;
	}
    
}
