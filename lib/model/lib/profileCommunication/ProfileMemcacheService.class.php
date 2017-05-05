<?php
/**
 * CLASS ProfileMemcacheService
 * This class is responsible to handle memcache request as a service class to the main memcache class
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage memcache
 */
class ProfileMemcacheService
{
    /**
     * Constants are assigned for each group for identification 
     * Each group constant is assigned a prime number
     * Used for prime factor concept to identify whether a group is updated or not
     **/
    const CONTACTS = 2;
    const CONTACTS_LIMIT = 3;
    const HOROSCOPE = 5;
    const PHOTO_REQUEST = 7;
    const CUSTOM_MESSAGE = 11;
    const MATCHALERT = 13;
    const VISITOR_ALERT = 17;
    const CHAT_REQUEST = 19;
    const BOOKMARK = 23;
    const JUST_JOINED_MATCHES=29;
    const CONTACTS_VIEWED=31;
    const PEOPLE_WHO_VIEWED_MY_CONTACTS=37;
    const SAVED_SEARCH=41;
    const INTRO_CALLS= 43;
    const SKIP_PROFILES = 47;
    const MESSAGE_ALL = 53;
    const GROUPS_UPDATED = "GROUPS_UPDATED";
    private $groups = array(
						ProfileMemcacheService::CONTACTS => array(
							'ACC_BY_ME', 
							'ACC_ME', 
							'ACC_ME_NEW', 
							'DEC_BY_ME', 
							'DEC_ME', 
							'DEC_ME_NEW', 
                            'AWAITING_RESPONSE', 
							'INTEREST_EXPIRING', 
							'AWAITING_RESPONSE_NEW', 
							'FILTERED', 
                            'FILTERED_NEW',
                            'INTEREST_ARCHIVED',
							'NOT_REP', 
							'OPEN_CONTACTS', 
							'CANCELLED_EOI'), 
						ProfileMemcacheService::CONTACTS_LIMIT => array(
							'TODAY_INI_BY_ME', 
							'WEEK_INI_BY_ME', 
							'MONTH_INI_BY_ME', 
							'TOTAL_CONTACTS_MADE', 
							'CONTACTS_MADE_AFTER_DUP'), 
						/*ProfileMemcacheService::HOROSCOPE => array(
							'HOROSCOPE', 
							'HOROSCOPE_NEW'),*/ 
                        ProfileMemcacheService::HOROSCOPE => array(
                            'HOROSCOPE',
                            'HOROSCOPE_NEW', 
                            'HOROSCOPE_REQUEST_BY_ME'), 
                        ProfileMemcacheService::INTRO_CALLS => array(
                            'INTRO_CALLS',
                            'INTRO_CALLS_COMPLETE'), 
						ProfileMemcacheService::PHOTO_REQUEST => array(
							'PHOTO_REQUEST', 
							'PHOTO_REQUEST_NEW',
                            'PHOTO_REQUEST_BY_ME'), 
						ProfileMemcacheService::CUSTOM_MESSAGE => array(
							'MESSAGE', 
							'MESSAGE_NEW'), 
                                                ProfileMemcacheService::MESSAGE_ALL => array('MESSAGE_ALL'),
						ProfileMemcacheService::MATCHALERT => array('MATCHALERT','MATCHALERT_TOTAL'),
						ProfileMemcacheService::JUST_JOINED_MATCHES => array('JUST_JOINED_MATCHES','JUST_JOINED_MATCHES_NEW'), 
						ProfileMemcacheService::CONTACTS_VIEWED => array('CONTACTS_VIEWED'), 
						ProfileMemcacheService::PEOPLE_WHO_VIEWED_MY_CONTACTS => array('PEOPLE_WHO_VIEWED_MY_CONTACTS'),
                                                ProfileMemcacheService::VISITOR_ALERT => array('VISITOR_ALERT','VISITORS_ALL'), 
						ProfileMemcacheService::CHAT_REQUEST => array('CHAT_REQUEST'), 
						ProfileMemcacheService::BOOKMARK => array('BOOKMARK'),
						ProfileMemcacheService::SAVED_SEARCH => array('SAVED_SEARCH'),
						ProfileMemcacheService::SKIP_PROFILES => array('CONTACTED_BY_ME','CONTACTED_ME','IGNORED'));
    
    
    /**
     * 
     * Constructor for instantiating object of ProfileMemcacheService class
     * 
     * <p>
     * It sets the profileid variable 
     * </p>
     * 
     * @access public
     * @param Profile $profile
     */
    public function __construct($profile)
    {
        if ($profile instanceof Profile)
            $this->profileid = $profile->getPROFILEID();
        else
            $this->profileid = $profile;
        if ($this->profileid) {
            $this->updatedFields = null;
            $this->memcache      = ProfileMemcache::getInstance($this->profileid);
        }
    }
    /**
     * @return group varibale having all the memcache keys for each group with key as group constant
     **/
    public function getGroups()
    {
        return $groups;
    }
    /**
     * 
     * Sets details required to display Pre Component for action Accept
     * 
     * <p>
     * This function sets information required to built pre component. It instantiates PreComponent, set the details like drafts, template name etc. required and returns this component.
     * </p>
     * 
     * @access public
     */
    public function get($key, $optionalDataFlag = false)
    {
        $set = $this->checkPreSettings($key, $optionalDataFlag);
        if ($set === true)
            return $this->memcache->get($key);
        return false;
    }
    /**
     * 
     * update profiel memcache instance 
     * 
     * <p>
     * This function updates:
     * <ol>
     * <li> memcache </li>
     * <li> contacts table </li>
     * </ol>
     * </p>
     * 
     * @access public
     */
    public function update($key, $value, $optionalDataFlag = false)
    {
        $set = $this->checkPreSettings($key, $optionalDataFlag);
        if ($set === true) {
            $previous     = $this->memcache->get($key);
            $valueToBeSet = $this->updateCount($previous, $updateBy = $value);
            $this->memcache->set($key, $valueToBeSet);
        }
    }
    /**
     * @function updateCount
     * @brief adds $previous value to $updateBy 
     * @returns the final sum $new_value
     **/
    private function updateCount($previous, $updateBy)
    {
        if (true === is_numeric($updateBy)) {
            $previous  = $previous ? $previous : 0;
            $new_value = $previous + $updateBy;
            if ($new_value >= 0)
                return $new_value;
            else {
                $new_value = 0;
                return $new_value;
            }
        } else
            jsException::log("Update By step size is not an integer. Step size provided = $updateBy");
    }
    
    /**
     * 
     * function checkPreSettings
     * 
     * <p>
     * This function takes the key and check whether the data is set for the group in which the is present, and set the data in memcache if it is not present
     * </p>
     * 
     * @access private
     */
    
    private function checkPreSettings($key, $optionalDataFlag = false)
    {
        $this->groupId = $this->findGroupId($key);
        if ($this->groupId === false){
            throw new jsException("", "key not handled in memcache via services");
        }
        $this->print_data();
        return $set = $this->updateGroup($optionalDataFlag);
    }
    /**
     * @function updateGroup
     *@param $optionalDataFlag default value false implies that is the data is not set for the group then set it. True implies that if the group for the profile is not set then dont set/update it else if it is set then update it.
     * @brief this function checks if the group is updated or not, if not then call set() function to set memcache data for the group
     * @return it return true if the group being in consideration is either already set or is set (when optionalDataFlag is false), else the function returns false implying that the data considered is not there in memcache
     **/
    private function updateGroup($optionalDataFlag = false)
    {
        if (false === $this->isGroupUpdated($this->groupId)) {
            if ($optionalDataFlag === false) {
                $this->set();
                //$this->print_data();
                return $set = true;
            } else
                return $updateGroup = false;
        } else
            return $alreadySet = true;
    }
    public function unsetKey($key)
    {
        $this->groupId = $this->findGroupId($key);
        if ($this->groupId === false)
            throw new jsException("", "key not handled in memcache via services");
        $this->unsetGroupUpdated();
        $this->memcache->updateMemcacheData();

    }
    private function unsetGroupUpdated()
    {
        if ($this->memcache->get('GROUPS_UPDATED') % $this->groupId != 0)
            return;
        $newGroupValue = $this->memcache->get('GROUPS_UPDATED')/$this->groupId;
        $this->memcache->set('GROUPS_UPDATED',$newGroupValue);
        $this->memcache->setFieldUpdated($key);
    }

    private function setFieldsUpdated($arr)
    {
        foreach($arr as $key => $value)
        {
            $this->memcache->setFieldUpdated($key);  
            
        }
    }


    private function print_data()
    {
        //$md = unserialize(JsMemcache::getInstance()->get($this->profileid));
       // print_r($md);   die;
    }
    /**
     * 
     * function set
     * 
     * <p>
     * This function decides and set the information required in memcache for the group of the key and updates memcache and contact_status for a few keys from table
     * </p>
     * 
     * @access private
     */
    
    private function set()
    {
        //		print_r($this->memcache);
        switch ($this->groupId) {
            case ProfileMemcacheService::CONTACTS:
                $arr = $this->groups[ProfileMemcacheService::CONTACTS];
                $this->setContactsData();
                break;
            case ProfileMemcacheService::CONTACTS_LIMIT:
                $this->setContactsLimitData();
                $arr = $this->groups[ProfileMemcacheService::CONTACTS_LIMIT];
                break;
            case ProfileMemcacheService::HOROSCOPE:
                $arr = $this->groups[ProfileMemcacheService::HOROSCOPE];
                $this->setHoroscopeData();
                break;
            case ProfileMemcacheService::INTRO_CALLS:
                $arr = $this->groups[ProfileMemcacheService::INTRO_CALLS];
                $this->setINTRO_CALLSData();
                break;
            case ProfileMemcacheService::PHOTO_REQUEST:
                $arr = $this->groups[ProfileMemcacheService::PHOTO_REQUEST];
                $this->setPhotoRequestData();
                break;
            case ProfileMemcacheService::CUSTOM_MESSAGE:
                $arr = $this->groups[ProfileMemcacheService::CUSTOM_MESSAGE];
                $this->setCustomMessageData();
                break;
            case ProfileMemcacheService::MATCHALERT:
                $arr = $this->groups[ProfileMemcacheService::MATCHALERT];
                $this->setMatchAlertData();
                break;
            case ProfileMemcacheService::VISITOR_ALERT:
                $arr = $this->groups[ProfileMemcacheService::VISITOR_ALERT];
                $this->setVisitorAlertData();
                break;
            case ProfileMemcacheService::CHAT_REQUEST:
                $arr = $this->groups[ProfileMemcacheService::CHAT_REQUEST];
                $this->setChatRequestData();
                break;
            case ProfileMemcacheService::BOOKMARK:
                $arr = $this->groups[ProfileMemcacheService::BOOKMARK];
                $this->setBookmarkData();
                break;
            case ProfileMemcacheService::MESSAGE_ALL:
                $arr = $this->groups[ProfileMemcacheService::MESSAGE_ALL];
                $this->setMessageAllData();
                break;
            case ProfileMemcacheService::JUST_JOINED_MATCHES:
                $arr = $this->groups[ProfileMemcacheService::JUST_JOINED_MATCHES];
                $this->setJustJoinedMatchesData();
                break;
            case ProfileMemcacheService::CONTACTS_VIEWED:
                $arr = $this->groups[ProfileMemcacheService::CONTACTS_VIEWED];
                $this->setContactsViewedData();
                break;
            case ProfileMemcacheService::PEOPLE_WHO_VIEWED_MY_CONTACTS:
                $arr = $this->groups[ProfileMemcacheService::PEOPLE_WHO_VIEWED_MY_CONTACTS];
                $this->setContactViewersData();
                break;
	    case ProfileMemcacheService::SAVED_SEARCH:
                $arr = $this->groups[ProfileMemcacheService::SAVED_SEARCH];
                $this->setSavedSearchData();
                break;
            case ProfileMemcacheService::SKIP_PROFILES:
                $this->setSKIP_PROFILES();
                $arr = $this->groups[ProfileMemcacheService::SKIP_PROFILES];
                break;
        }
        $this->setGroupUpdated();
        $this->setFieldsUpdated($arr);
        $this->memcache->updateMemcacheData();
        
    }
    /**
     *@fucntion setGroupUpdated
     * @breif udpate the GROUP_UPADTED variable based on the data set in memcache from tables
     **/
    private function setGroupUpdated()
    {
        if ($this->memcache->get('GROUPS_UPDATED') % $this->groupId == 0)
            return;
        $newGroupValue = $this->memcache->get('GROUPS_UPDATED') * $this->groupId;
        $this->memcache->set('GROUPS_UPDATED',$newGroupValue);
    }
    /**
     *@function findGroupId
     *@brief finds the group id of the memcache key provided
     *@return returns groupId if it belongs to a group, else returns false
     **/
    private function findGroupId($key)
    {
        foreach ($this->groups as $k => $v) {
            if (in_array($key, $v))
                return $k;
        }

        return false;
    }
    /**
     *@function isGroupUpdated
     *@brief checks whether the group id provided is updated in memcache or not
     *@return returns true of the group is updated else returns false
     **/
    public function isGroupUpdated($groupId)
    {
        if ($this->memcache->get('GROUPS_UPDATED') % $groupId == 0)
            return true;
        return false;
    }
    /**
     * fucntion setContactsData()
     *@brief fetches data from CONTACTS table and populate counts like ACC_BY_ME,DEC_BY_ME etc and the assign it to memcache variables
     **/
    public function setContactsData()
    {
        $contactRecoredObj = new ContactsRecords;
        $skipContactedType  = SkipArrayCondition::$default;
        $skipProfileObj     = SkipProfile::getInstance($this->profileid);
        $skipProfile        = $skipProfileObj->getSkipProfiles($skipContactedType);
        $group             = "FILTERED,TYPE,SEEN";
        $contactsCount     = $contactRecoredObj->getContactsCount(Array(
            "SENDER" => $this->profileid,
            "TYPE" => Array(
                'A',
                'D',
                'I',
                'E',
                'C'
            )
        ), $group, 1,$skipProfile); 
        if (is_array($contactsCount)) {
            foreach ($contactsCount as $key => $value) {
                switch ($value["TYPE"]) {
                    case 'A':
                        if ($value["SEEN"] != 'Y') {
                            $ACC_ME_NEW = $ACC_ME_NEW + $value["COUNT"];
                        }
                        $ACC_ME = $ACC_ME + $value["COUNT"];
                        break;
                    case 'I':
                        if ($value["SEEN"] != 'Y') {
                            $NOT_OPEN = $NOT_OPEN + $value["COUNT"];
                        }
                        $NOT_REP = $NOT_REP + $value["COUNT"];
                        break;
                    case 'D':
                        if ($value["SEEN"] != 'Y') {
                            $DEC_ME_NEW = $DEC_ME_NEW + $value["COUNT"];
                        }
                        $DEC_ME = $DEC_ME + $value["COUNT"];
                        break;
                    case 'E':
                        $CANCELLED_EOI = $CANCELLED_EOI + $value["COUNT"];
                        $DEC_BY_ME     = $DEC_BY_ME + $value["COUNT"];
                        break;
                    case 'C':
                        $DEC_BY_ME = $DEC_BY_ME + $value["COUNT"];
                        break;
                    default:
                        break;
                }
            }
        }
        $OPEN_CONTACTS = $NOT_REP - $NOT_OPEN;
        $contactsCount = $contactRecoredObj->getContactsCount(Array(
            "RECEIVER" => $this->profileid,
            "TYPE" => Array(
                'A',
                'D',
                'I',
                'E',
                'C'
            )
        ), $group, 1,$skipProfile,1);

        if (is_array($contactsCount)) {
            foreach ($contactsCount as $key => $value) {
                switch ($value["TYPE"]) {
                    case 'A':
                        if ($value['TIME1']!='2')
                        {
                            $ACC_BY_ME = $ACC_BY_ME + $value["COUNT"];
                        }
                        break;
                    case 'D':
                        if ($value['TIME1']!='2')
                        {
                            $DEC_BY_ME = $DEC_BY_ME + $value["COUNT"];
                        }
                        break;
                    case 'I':
                        
                        if ($value["FILTERED"] == 'Y'){
                                    if ($value['TIME1']=='0'){
                                    if ($value["SEEN"] != 'Y')
                                    $FILTERED_NEW = $FILTERED_NEW + $value["COUNT"];
                                	$FILTERED = $FILTERED + $value["COUNT"];
                                }
                        }
                        else 
                        {
							if($value["TIME1"] == 0)
							{
	                            if ($value["SEEN"] != 'Y')
	                            {
	                                $AWAITING_RESPONSE_NEW = $AWAITING_RESPONSE_NEW + $value["COUNT"];
								}
								$AWAITING_RESPONSE = $AWAITING_RESPONSE + $value["COUNT"];
							}
                            if ( $value["TIME1"] == 1 )
                            {
                               $INTEREST_ARCHIVED = $INTEREST_ARCHIVED + $value["COUNT"];                                
                            }
                            if ( $value["TIME1"] == 2 )
                            {
                                $INTEREST_EXPIRING = $INTEREST_EXPIRING + $value["COUNT"];
                            }

                        }
                        break;
                    case 'C':
                    case 'E':
                        if ($value['TIME1']!='2')
                        {
                            if ($value["SEEN"] != 'Y') {
                                $DEC_ME_NEW = $DEC_ME_NEW + $value["COUNT"];
                            }
                            $DEC_ME = $DEC_ME + $value["COUNT"];
                        }
                        break;

                    default:
                        break;
                }
            }
        }

        $this->memcache->set('ACC_BY_ME',$ACC_BY_ME ? $ACC_BY_ME : 0);
        $this->memcache->set('ACC_ME',$ACC_ME ? $ACC_ME : 0);
        $this->memcache->set('ACC_ME_NEW',$ACC_ME_NEW ? $ACC_ME_NEW : 0);
        $this->memcache->set('DEC_BY_ME',$DEC_BY_ME ? $DEC_BY_ME : 0);
        $this->memcache->set('DEC_ME',$DEC_ME ? $DEC_ME : 0);
        $this->memcache->set('DEC_ME_NEW',$DEC_ME_NEW ? $DEC_ME_NEW : 0);
        $this->memcache->set('NOT_REP',$NOT_REP ? $NOT_REP : 0);
        $this->memcache->set('CANCELLED_EOI',$CANCELLED_EOI ? $CANCELLED_EOI : 0);
        $this->memcache->set('FILTERED',$FILTERED ? $FILTERED : 0);
        $this->memcache->set('FILTERED_NEW',$FILTERED_NEW ? $FILTERED_NEW : 0);
        $this->memcache->set('AWAITING_RESPONSE',$AWAITING_RESPONSE ? $AWAITING_RESPONSE : 0);
        $this->memcache->set('AWAITING_RESPONSE_NEW',$AWAITING_RESPONSE_NEW ? $AWAITING_RESPONSE_NEW : 0);
        $this->memcache->set('OPEN_CONTACTS',$OPEN_CONTACTS ? $OPEN_CONTACTS : 0);
        $this->memcache->set('INTEREST_ARCHIVED',$INTEREST_ARCHIVED ? $INTEREST_ARCHIVED : 0);
        $this->memcache->set('INTEREST_EXPIRING',$INTEREST_EXPIRING ? $INTEREST_EXPIRING : 0);
    }
    /**
     * fucntion setPhotoRequestData()
     *@brief fetches data from PHOTO_REQUEST and get PHOTO related counts
     **/
    
    public function setPhotoRequestData()
    {
        $photoRequestObj    = new PhotoRequest();
        $skipContactedType  = SkipArrayCondition::$PHOTO_REQUEST;
        $skipProfileObj     = SkipProfile::getInstance($this->profileid);
        $skipProfile        = $skipProfileObj->getSkipProfiles($skipContactedType);
        $photoRequestCounts = $photoRequestObj->getPhotoRequestCount($this->profileid, $skipProfile);
        $photoRequestSentCounts = $photoRequestObj->getPhotoRequestSentCount($this->profileid,$skipProfile);
        $PHOTO_REQUEST      = $photoRequestCounts[0]['TOTAL_COUNT'];
        $PHOTO_REQUEST_NEW  = $photoRequestCounts[0]['UNSEEN'];
        $PHOTO_REQUEST_BY_ME = $photoRequestSentCounts[0]['TOTAL_COUNT'];
        $this->memcache->set('PHOTO_REQUEST',$PHOTO_REQUEST ? $PHOTO_REQUEST : 0);
        $this->memcache->set('PHOTO_REQUEST_NEW',$PHOTO_REQUEST_NEW ? $PHOTO_REQUEST_NEW : 0);
        $this->memcache->set('PHOTO_REQUEST_BY_ME',$PHOTO_REQUEST_BY_ME ? $PHOTO_REQUEST_BY_ME : 0);
    }
    /**
     * fucntion setContactsLimitData()
     *@brief fetches data from MESSAGE_LOG regarding CONTACTS_LIMIT
     **/
    
    public function setContactsLimitData()
    {
        
        $dbInstance = new BILLING_SERVICE_STATUS();
        list($expDate, $expDays) = $dbInstance->getLastExpiryDate($this->profileid);
        $select           = "RECEIVER, DATEDIFF(now(),DATE) as TIME,DATE(DATE) as DATE";
        $group            = '';
        $where            = array(
            "SENDER" => $this->profileid,
            "TYPE" => 'I'
        );
        $message          = new MessageLog;
        $contactLimitData = $message->getMessageLogContactCount($where, $group, $select);
        if (is_array($contactLimitData)) {
            foreach ($contactLimitData as $key => $val) {
                if (isset($expDays) && $expDays >= 0) {
                    if ($val["TIME"] < $expDays)
                        $overAllLimitArr[$val["RECEIVER"]] = $val["TIME"];
                    else
                        unset($overAllLimitArr[$val["RECEIVER"]]);
                }
                $contactArr[$val["RECEIVER"]] = $val["TIME"];
                $contactDates[$val["RECEIVER"]] = $val["DATE"];
            }
        }
        if (is_array($contactArr)) {
            $datediff = floor(abs(JSstrToTime(date("Y-m-d")) - JSstrToTime(ErrorHandler::DUP_LIVE_DATE)) / (60 * 60 * 24));
            $contactLimitDates = CommonFunction::getContactLimitDates();
            foreach ($contactArr as $key => $val) {
                $contactDate = $contactDates[$key];
                if ($val == 0)
                    $TODAY_INI_BY_ME++;
                // insert logic for week's count
                if (strtotime($contactLimitDates['weekStartDate'])  <= strtotime($contactDate))
                    $WEEK_INI_BY_ME++;
                // insert logic for month's count
                if (strtotime($contactLimitDates['monthStartDate'])  <= strtotime($contactDate))
                    $MONTH_INI_BY_ME++;
                if ($datediff >= $val)
                {
					$CONTACTS_MADE_AFTER_DUP++;
				}
                if (isset($expDays) && $expDays >= 0) {
                    if (is_array($overAllLimitArr))
                        $OVERALL_CONTACTS_MADE = count($overAllLimitArr);
                    else
                        $OVERALL_CONTACTS_MADE = 0;
                } else
                    $TOTAL_CONTACTS_MADE++;
            }
        }
        $tempContactObj = new NEWJS_CONTACTS_TEMP();
        list($tempOverAllContactCount, $tempDayContactCount) = $tempContactObj->getTemporaryContactsCount($this->profileid);
        $TODAY_INI_BY_ME += $tempDayContactCount;
        $WEEK_INI_BY_ME += $tempDayContactCount;
        $MONTH_INI_BY_ME += $tempDayContactCount;
        $OVERALL_CONTACTS_MADE += $tempOverAllContactCount;
        
        if (!$OVERALL_CONTACTS_MADE)
            $OVERALL_CONTACTS_MADE = 0;
        if (!$TOTAL_CONTACTS_MADE)
            $TOTAL_CONTACTS_MADE = $OVERALL_CONTACTS_MADE;
        $this->memcache->set('TODAY_INI_BY_ME',$TODAY_INI_BY_ME ? $TODAY_INI_BY_ME : 0);
        $this->memcache->set('WEEK_INI_BY_ME',$WEEK_INI_BY_ME ? $WEEK_INI_BY_ME : 0);
        $this->memcache->set('MONTH_INI_BY_ME',$MONTH_INI_BY_ME ? $MONTH_INI_BY_ME : 0);
        $this->memcache->set('TOTAL_CONTACTS_MADE',$TOTAL_CONTACTS_MADE ? $TOTAL_CONTACTS_MADE : 0);
        $this->memcache->set('CONTACTS_MADE_AFTER_DUP',$CONTACTS_MADE_AFTER_DUP ? $CONTACTS_MADE_AFTER_DUP : 0);
    }
    /**
     * fucntion setHoroscopeData()
     *@brief fetches data from HOROSCOPE and get Horoscope related counts
     **/
    public function setHoroscopeData()
    {
        $horoscopeObj      = new Horoscope();
        $skipContactedType = SkipArrayCondition::$HOROSCOPE;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
        
        $horoscopeRequestReceivedCounts = $horoscopeObj->getHoroscopeRequestCount($this->profileid, $skipProfile);
        $horoscopeRequestSentCounts = $horoscopeObj->getHoroscopeRequestSentCount($this->profileid, $skipProfile);
        $HOROSCOPE      = $horoscopeRequestReceivedCounts[0]['TOTAL_COUNT'];
        $HOROSCOPE_NEW      = $horoscopeRequestReceivedCounts[0]['UNSEEN'];
        $HOROSCOPE_REQUEST_BY_ME      = $horoscopeRequestSentCounts[0]['TOTAL_COUNT'];
        //$HOROSCOPE_NEW   = $horoscopeCounts[0]['UNSEEN'];
        $this->memcache->set('HOROSCOPE',$HOROSCOPE ? $HOROSCOPE : 0);
        $this->memcache->set('HOROSCOPE_REQUEST_BY_ME',$HOROSCOPE_REQUEST_BY_ME ? $HOROSCOPE_REQUEST_BY_ME : 0);
        $this->memcache->set('HOROSCOPE_NEW',$HOROSCOPE_NEW ? $HOROSCOPE_NEW : 0);
    }
     /**
     * fucntion setINTRO_CALLSData(), set data for INTRO_CALLS
     **/
    public function setINTRO_CALLSData()
    {
        $introCallObj      = new getIntroCallHistory();
        $skipContactedType = SkipArrayCondition::$INTRO_CALLS;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
        $INTRO_CALLS = $introCallObj->getIntroCallsPendingCount($this->profileid,$skipProfile);
        $INTRO_CALLS_COMPLETE = $introCallObj->getIntroCallsCompleteCount($this->profileid,$skipProfile);
        $this->memcache->set('INTRO_CALLS',$INTRO_CALLS ? $INTRO_CALLS : 0);
        $this->memcache->set('INTRO_CALLS_COMPLETE',$INTRO_CALLS_COMPLETE ? $INTRO_CALLS_COMPLETE : 0);    
    }
    /**
     * fucntion setCustomMessageData()
     *@brief fetches data from MESSAGE_LOG and get data about message counts
     **/
    
    public function setCustomMessageData()
    {
        $MESSAGE           = 0;
        $MESSAGE_NEW       = 0;
        $select            = "";
        $where             = array(
            "RECEIVER" => $this->profileid,
            "TYPE" => 'R',
            "IS_MSG" => 'Y'
        );
        $group             = "SEEN,SENDER";
        $skipContactedType = SkipArrayCondition::$MESSAGE;
        $message           = new MessageLog;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
	if(InboxEnums::$messageLogInQuery)
	{
		$considerArray = SkipArrayCondition::$MESSAGE_CONSIDER;
		$considerProfiles =  $skipProfileObj->getSkipProfiles($considerArray);
		$considerProfiles = array_diff($considerProfiles,$skipProfile);
		unset($skipProfile);
	}
       // print_r($skipProfile);
	if(is_array($considerProfiles) && count($considerProfiles)>0)
		$msgCount = $message->getMessageLogContactCount($where, $group, $select, $skipProfile,$considerProfiles);
//        $configObj            = new ProfileInformationModuleMap();
//        $configurations = $configObj->getConfiguration("ContactCenterDesktop");
//        $condition["LIMIT"]    = $configurations["MY_MESSAGE"]["COUNT"]+1;
        
        
        
        
        //print_r($msgCount); die;
        if(is_array($msgCount))
		{
			foreach($msgCount as $k=>$v)
			{
			if($v['SEEN']!="Y")
                    $MESSAGE_NEW  += 1;
                $MESSAGE +=1;
		}
		}
        /*$group='';
		$where = array("SENDER"=>$this->profileid,"TYPE" => 'R',"IS_MSG"=>'Y');
		$msgCount = $message->getMessageLogCount($where,$group,$select,$skipProfile);
		$MESSAGE_SENT =  $msgCount[0]["COUNT"]; */
		$this->memcache->set('MESSAGE',$MESSAGE ? $MESSAGE : 0);
        $this->memcache->set('MESSAGE_NEW',$MESSAGE_NEW ? $MESSAGE_NEW : 0);
       
    }
    /**
     * fucntion setMatchAlertData()
     *@brief fetches data from Match Alert Count and get match alert count
     **/
    
    public function setMatchAlertData()
    {
        $matchAlertObj     = new MatchAlerts();
        $skipContactedType = SkipArrayCondition::$MATCHALERT;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
        $matchAlertCount   = $matchAlertObj->getMatchAlertCount($this->profileid, $skipProfile);
        $this->memcache->set('MATCHALERT',$matchAlertCount["NEW"] ? $matchAlertCount["NEW"] : 0);
        $this->memcache->set('MATCHALERT_TOTAL',$matchAlertCount["TOTAL"] ? $matchAlertCount["TOTAL"] : 0);
    }
    
        public function setMATCHALERT($new=0)
    {
        $this->memcache->set('MATCHALERT',$new);
    }
 
    
    public function setVisitorAlertData()
    {
        $profileObj=LoggedInProfile::getInstance('newjs_master');
        $visitorObj = new Visitors($profileObj);
                $infoTypenav["matchedOrAll"]='A';
		$visitors = $visitorObj->getVisitorProfile('','',$infoTypenav,$setAllVisitorsKey=$this->memcache);
		$this->memcache->set('VISITOR_ALERT',count($visitors) ? count($visitors) : 0);
	}
    public function setVisitorsAll($allVisitorCount)
    {
		$this->memcache->set('VISITORS_ALL',$allVisitorCount ? $allVisitorCount : 0);
    }
    public function setChatRequestData()
    {
        $chatObj     = new ChatLibrary();
        $chatRequest = $chatObj->getChatRequestCount($this->profileid);
        $this->memcache->set('CHAT_REQUEST',$chatRequest ? $chatRequest : 0);
    }
    public function setBookmarkData()
    {
        $bookmarkObj = new Bookmarks();
        $skipContactedType = SkipArrayCondition::$SHORTLIST;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
        $count       = $bookmarkObj->getBookmarkCount($this->profileid,$skipProfile);
        $this->memcache->set('BOOKMARK',$count ? $count : 0);
    }
    public function setMessageAllData()
    {
        $skipContactedType = SkipArrayCondition::$MESSAGE;
        $message           = new MessageLog;
        $skipProfileObj    = SkipProfile::getInstance($this->profileid);
        $skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
        if(InboxEnums::$messageLogInQuery)
	{
		$considerArray = SkipArrayCondition::$MESSAGE_CONSIDER;
		$considerProfiles =  $skipProfileObj->getSkipProfiles($considerArray);
		$considerProfiles = array_diff($considerProfiles,$skipProfile);
	}
       // print_r($skipProfile);
        $condition["WHERE"]["IN"]["PROFILE"] = $this->profileid;
        $condition["WHERE"]["IN"]["IS_MSG"]   = "Y";
        $condition["WHERE"]["IN"]["TYPE"]     = "R";
	if(InboxEnums::$messageLogInQuery)
	{
		if(is_array($considerProfiles) && count($considerProfiles)>0)
		{
			$profilesArray  = $message->getMessageListing($this->profileid, $condition, $skipProfile,$considerProfiles);
		}
	}
	else
	{
		$profilesArray  = $message->getMessageListing($this->profileid, $condition, $skipProfile);
	}
        if(is_array($profilesArray))
		$MESSAGE_ALL = count($profilesArray);
        $this->memcache->set('MESSAGE_ALL',$MESSAGE_ALL?$MESSAGE_ALL:0);
    }
    /**
     * fucntion setJustJoinedMatchesData()
     *@brief fetches data from Search Just joined matches and get data about Just joined counts
     **/
    
    public function setJustJoinedMatchesData()
    {
		$justJoinMatchArr = SearchCommonFunctions::getJustJoinedMatches($this->loginProfile,"countAll"); 
		$justJoinedMatches=$justJoinMatchArr['CNT'];
		$justJoinMatchArrNew = SearchCommonFunctions::getJustJoinedMatches($this->loginProfile,"CountOnly","havePhoto"); 
		$justJoinedMatchesNew=$justJoinMatchArrNew['CNT'];
		$this->memcache->set('JUST_JOINED_MATCHES',$justJoinedMatches ? $justJoinedMatches : 0);
	        $this->memcache->set('JUST_JOINED_MATCHES_NEW',$justJoinedMatchesNew ? $justJoinedMatchesNew : 0);
    }
     /**
     * fucntion setContactsViewedData()
     *@brief fetches data from view contacts log table to show all viewed contacts
     **/
    
    public function setContactsViewedData()
    {
        $skipConditionArray = SkipArrayCondition::$CONTACTS_VIEWED;
        $skipProfileObj     = SkipProfile::getInstance($this->profileid);
        $skipArray       = $skipProfileObj->getSkipProfiles($skipConditionArray);

		$contactsViewedObj=new JSADMIN_VIEW_CONTACTS_LOG();
		$contactsViewedCount=$contactsViewedObj->totalContactsViewedEver($this->profileid,$skipArray);
		$this->memcache->set('CONTACTS_VIEWED',$contactsViewedCount ? $contactsViewedCount : 0);
        //$this->memcache->set('CONTACTS_VIEWED_NEW',$justJoinedMatchesNew ? $justJoinedMatchesNew : 0);
    }
    /**
     * fucntion setContactViewersData()
     *@brief fetches data from view contacts log table to show all viewers of our contacts
     **/
    
    public function setContactViewersData()
    {
        $skipConditionArray = SkipArrayCondition::$PEOPLE_WHO_VIEWED_MY_CONTACTS;
        $skipProfileObj     = SkipProfile::getInstance($this->profileid);
        $skipArray       = $skipProfileObj->getSkipProfiles($skipConditionArray);
                
        $contactViewersObj=new JSADMIN_VIEW_CONTACTS_LOG();
        $contactViewersCount=$contactViewersObj->totalContactViewersEver($this->profileid,$skipArray);
        $this->memcache->set('PEOPLE_WHO_VIEWED_MY_CONTACTS',$contactViewersCount ? $contactViewersCount : 0);
        //$this->memcache->set('CONTACTS_VIEWED_NEW($justJoinedMatchesNew ? $justJoinedMatchesNew : 0);
    }
    
    /**
     * @function updateMemcache()
     * @brief updates memcache server with new data
     **/
    public function updateMemcache()
    {
        $this->memcache->updateMemcacheData();
    }
    /**
     * @function setDataForGroup
     * @brief set data for the group for which group id is provided based on $optionalDataFlag
     **/
    public function setDataForGroup($groupId, $optionalDataFlag = false)
    {
        if ($this->checkValidGroupId($groupId) === true) {
            $this->groupId = $groupId;
            return $set = $this->updateGroup($optionalDataFlag);
        }
    }
    /**
     *@function checkValidGroupId
     * @brief check whether the group id provided is a valid group
     **/
    private function checkValidGroupId($groupId)
    {
        if (is_array($this->groups[$groupId]))
            return true;
        else
            return false;
    }
    /**
     *@fucntion clearInstance()
     * clear memcache data for the profileid
     **/
    public function clearInstance()
    {
        $this->memcache->clearInstance($this->profileid);
        return;
    }
    /**
     *@function getMemcacheData()
     *@brief return all data stored in memcache against profileid
     * @return memcache data
     **/
    public function getMemcacheData()
    {
        return $this->memcache->getMemcacheData();
    }
    
    
    public function getContactedProfiles($skipArray)
    {
        $skipProfile = $this->memcache->getContactedProfiles();
        if (!is_array($skipProfile)) {
            if (isset($skipArray["CONTACT"])) {
                $contactsObj = new ContactsRecords();
                $skipProfile = $contactsObj->getSkipContactedProfile($this->profileid, $skipArray["CONTACT"]);
               
                $this->memcache->setContactedProfile($skipProfile);
            }
        }
        if (isset($skipArray["CONTACT"])) {
            foreach ($skipArray["CONTACT"] as $key => $value) {
                if (isset($skipArray["CONTACT"]))
                    $profileid[] = $skipProfile["CONTACTED_BY_ME"][$value] ? $skipProfile["CONTACTED_BY_ME"][$value] : array();
                $profileid[] = $skipProfile["CONTACTED_ME"][$value] ? $skipProfile["CONTACTED_ME"][$value] : array();
            }
        }
        if (is_array($profileid))
            $profileid = call_user_func_array('array_merge', $profileid);
        return $profileid;
        
    }
    public function setSavedSearchData() 
    {
        $loggedInProfile = new Profile('',$this->profileid);	
	$userSavedSearchesObj = new UserSavedSearches($loggedInProfile);
	$savedSearchesCount = $userSavedSearchesObj->countRecord();	
        $this->memcache->set('SAVED_SEARCH',$savedSearchesCount ? $savedSearchesCount : 0);
    }
    
   
    public function setSKIP_PROFILES()
    {
				$skipConditionArray = SkipArrayCondition::$SkippedAll;
        $skipProfileObj     = SkipProfile::getInstance($this->profileid);
        //print_r($skipConditionArray); die;
        $skipArray       = $skipProfileObj->getSkipProfiles($skipConditionArray,"1");
       // print_r($skipArray); die;
		
			$this->memcache->set('CONTACTED_BY_ME',serialize($skipArray["CONTACTED_BY_ME"]));
                	$this->memcache->set('CONTACTED_ME',serialize($skipArray["CONTACTED_ME"]));
			$this->memcache->set('IGNORED',serialize($skipArray["IGNORED"]));
			$this->memcache->updateMemcacheData();
    }
    
    
    
}
?>
