<?php

/**
 * Description of JPROFILE
 * Library Class to handle Model for JPROFILE Table
 *
 * @package     jeevansathi
 * @author      Kunal Verma
 * @created     7th July 2016
 */

class JPROFILE
{

    /**
     * Member Variable
     */
    /**
     * @var Static Instance of this class
     */
    private static $instance;

    /**
     * Object of Store class
     * @var instance of NEWJS_PROFILE|null
     */
    private static $objProfileMysql = null;

    var $activatedKey; //archiving

    /**
     * @fn Constructor
     * @brief Constructor function
     * @param $dbName - Database name to which the connection would be made
     */

    public function __construct($dbname = "")
    {
        self::$objProfileMysql = NEWJS_JPROFILE::getInstance($dbname);
    }


    /**
     * @fn getInstance
     * @brief fetches the instance of the class
     * @param $dbName - Database name to which the connection would be made
     * @return instance of this class
     */
    public static function getInstance($dbName = '')
    {
        if (!$dbName)
            $dbName = "newjs_master";
        if (isset(self::$instance)) {
            //If different instance is required
            if ($dbName != self::$instance->dbName) {
                $class = __CLASS__;
                self::$instance = new $class($dbName);
            }
        } else {
            $class = __CLASS__;
            self::$instance = new $class($dbName);
        }
        return self::$instance;
    }

    /**
     * @fn setActivatedKey
     * @brief Sets activatedKey for arhiving.
     * @param $activatedKey true/false
     */
    public function setActivatedKey($activatedKey)
    {
        self::$objProfileMysql->setActivatedKey($activatedKey);
    }

    /**
     * @fn getActivatedKey
     * @brief fetches activatedKey value
     * @return activatedKey value
     */
    public function getActivatedKey()
    {
        return self::$objProfileMysql->getActivatedKey();
    }

    /**
     * @fn getFields
     * @brief Returns column names to query
     */
    public function getFields()
    {
        return self::$objProfileMysql->getFields();
    }

    /**
     * @fn get
     * @brief fetches results from JPROFILE
     * @param $value Query criteria value
     * @param $criteria Query criteria column
     * @param $fields Columns to query
     * @param $where additional where parameter
     * @return results according to criteria
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */
    public function get($value = "", $criteria = "PROFILEID", $fields = "", $extraWhereClause = null, $cache = false)
    {
        $fields = $this->getRelevantFields($fields);
        $bServedFromCache = false;
        $this->totalQueryCount();
        if (ProfileCacheLib::getInstance()->isCached($criteria, $value, $fields, __CLASS__)) {
            $result = ProfileCacheLib::getInstance()->get($criteria, $value, $fields, __CLASS__, $extraWhereClause);
            //When processing extraWhereClause results could be false,
            //so for that case also we are going to query mysql
            if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }
        }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            // LoggingManager::getInstance(ProfileCacheConstants::PROFILE_LOG_PATH)->logThis(LoggingEnums::LOG_INFO,"Consuming from cache for criteria: {$criteria} : {$value}");
            $this->logCacheConsumption();
            return $result;
        }

        //Get Records from Mysql
        $result = self::$objProfileMysql->selectRecord($value, $criteria, $fields, $extraWhereClause, $cache);
        //TODO : Request to Cache this Record, on demand
        if(is_array($result) && $criteria == "PROFILEID") {
          $result['PROFILEID'] = $value;
        }
        if ( is_array($result) && 
	     isset($result['PROFILEID']) &&
	     false === ProfileCacheLib::getInstance()->isCommandLineScript()
	) {
            ProfileCacheLib::getInstance()->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $result['PROFILEID'], $result);
        }

        return $result;

    }

    /**
     * @fn edit
     * @brief edits JPROFILE
     * @param $value Query criteria value
     * @param $criteria Query criteria column
     * @param $paramArr key-value pair of columns and values to edit
     * @return edits results
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */
    public function edit($paramArr = array(), $value, $criteria = "PROFILEID", $extraWhereCnd = "")
    {
        $bResult = self::$objProfileMysql->updateRecord($paramArr,$value,$criteria,$extraWhereCnd);

        if(true === $bResult) {
            ProfileCacheLib::getInstance()->updateCache($paramArr, $criteria, $value, __CLASS__, $extraWhereCnd);
        }

        //If Criteria is not PROFILEID then remove data from cache.
        if ($bResult && $criteria != "PROFILEID") {
           if(isset($paramArr['PROFILEID'])) {
               $iProfileId = $paramArr['PROFILEID'];
           } else {
               $arrData = $this->get($value,$criteria,"PROFILEID");
               $iProfileId = $arrData['PROFILEID'];
           }

           //Remove From Cache
           ProfileCacheLib::getInstance()->removeCache($iProfileId);
        }
        return $bResult;
    }

    /**
     * @param array $paramArr
     * @return mixed
     */
    public function insert($paramArr = array())
    {
        $bResult = self::$objProfileMysql->insertRecord($paramArr);

        if(false !== $bResult) {
            ProfileCacheLib::getInstance()->insertInCache($bResult, $paramArr);
        }
        return $bResult;
    }

    /**
     * @fn fields
     * @brief Helper function for edit. Contains JPROFILE field names.
     */
    public function fields()
    {
        return self::$objProfileMysql->fields();
    }

    /**
     * @fn getArray
     * @brief fetches results for multiple profiles to query from JPROFILE
     * @param $valueArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are included in the result
     * @param $excludeArray - array with field name as key and comma separated field values as the value corresp to the key - rows satisfying these values are excluded from the result
     * @param $fields Columns to query
     * @param $orderby string FIELDS ASC/DESC
     * @param $limit string 1/2/3
     * @return results Array according to criteria having incremented index
     * @exception jsException for blank criteria
     * @exception PDOException for database level error handling
     */

    public function getArray($valueArray = "", $excludeArray = "", $greaterThanArray = "", $fields = "PROFILEID", $lessThanArray = "", $orderby = "", $limit = "", $greaterThanEqualArrayWithoutQuote = "", $lessThanEqualArrayWithoutQuote = "", $like = "", $nolike = "", $addWhereText = "")
    {
      if(is_array($valueArray) && count($valueArray) && $valueArray['PROFILEID']) {      
       // $this->logProfileIDs($valueArray['PROFILEID']);
      }
        return self::$objProfileMysql->getArray($valueArray, $excludeArray, $greaterThanArray, $fields, $lessThanArray, $orderby, $limit, $greaterThanEqualArrayWithoutQuote, $lessThanEqualArrayWithoutQuote, $like, $nolike, $addWhereText);
    }

    public function getProfileIdsThatSatisfyConditions($equality_cond_arr = '', $between_cond = '')
    {
        return self::$objProfileMysql->getProfileIdsThatSatisfyConditions($equality_cond_arr, $between_cond);
    }

    /**
     * Function to fetch profiles based on some conditions : lastlogin and registration date
     *
     * @param   $lastLoginOffset ,$lastRegistrationOffset
     * @return $profiles - array of desired profiles
     */
    public function fetchProfilesConditionBased($lastLoginOffset, $lastRegistrationOffset)
    {
        return self::$objProfileMysql->fetchProfilesConditionBased($lastLoginOffset, $lastRegistrationOffset);
    }

    public function getProfileSelectedDetails($pid, $fields = "*", $extraWhereClause = null)
    {
        return self::$objProfileMysql->getProfileSelectedDetails($pid, $fields, $extraWhereClause);
    }

    public function checkPhone($numberArray = '', $isd = '')
    {
        return self::$objProfileMysql->checkPhone($numberArray, $isd);
    }

    public function Deactive($pid)
    {
        $result = self::$objProfileMysql->Deactive($pid);
        ProfileCacheLib::getInstance()->removeCache($pid);
        return $result;
    }

    public function duplicateEmail($email)
    {
        return self::$objProfileMysql->duplicateEmail($email);
    }


    public function getPassword($username)
    {
        return self::$objProfileMysql->getPassword($username);
    }

    public function getUsername($profileid)
    {
        return self::$objProfileMysql->getUsername($profileid);
    }

    public function getProfileSubscription($proid)
    {
        return self::$objProfileMysql->getProfileSubscription($proid);
    }

    /* Update Login Date Sort Date From Api Login Authentication
     * @param int profileid
     * @return int rowCount
     */
    public function updateLoginSortDate($pid,$currentTime = '')
    {
        $now = $currentTime ? $currentTime : date('Y-m-d H:i:s');
        $arrData = $this->get($pid,'PROFILEID','SORT_DT');

        $time = new DateTime();
        $time->sub(date_interval_create_from_date_string("7 days"));
        $time7days = $time->format('Y-m-d H:i:s');

        if ($time7days > $arrData['SORT_DT']) {
            $arrData['SORT_DT'] = $time7days;
        }

        $paramArr = array('LAST_LOGIN_DT'=>$now,'SORT_DT'=>$arrData['SORT_DT']);
        return $this->edit($paramArr, $pid, "PROFILEID");
    }

    public function getLoggedInProfilesForDateRange($logindDtStart, $loginDtEnd)
    {
        return self::$objProfileMysql->getLoggedInProfilesForDateRange($logindDtStart, $loginDtEnd);
    }

    public function getAllPasswords($l1, $l2)
    {
        return self::$objProfileMysql->getAllPasswords($l1, $l2);
    }

    public function getCity($profileIdArr)
    {
        return self::$objProfileMysql->getCity($profileIdArr);
    }

    public function getMembershipMailerProfiles($condition)
    {
        return self::$objProfileMysql->getMembershipMailerProfiles($condition);
    }

    public function getLoggedInProfilesForPreAlloc($logindDtStart, $loginDtEnd)
    {
        return self::$objProfileMysql->getLoggedInProfilesForPreAlloc($logindDtStart, $loginDtEnd);
    }

    public function fetchSourceWiseProfiles($start_dt, $end_dt)
    {
        return self::$objProfileMysql->fetchSourceWiseProfiles($start_dt, $end_dt);
    }

    public function updateHaveJEducation($profiles)
    {
        $result = self::$objProfileMysql->updateHaveJEducation($profiles);
        if ($result) {
            ProfileCacheLib::getInstance()->removeCache(explode(",",$profiles));
        }
        return $result;
    }

    public function getProfilesForDateRange($start_dt, $end_dt, $status)
    {
        return self::$objProfileMysql->getProfilesForDateRange($start_dt, $end_dt, $status);
    }

    public function getSubscriptions($profileid, $field)
    {
        return self::$objProfileMysql->getSubscriptions($profileid, $field);
    }

    public function updateOfflineBillingDetails($profileid)
    {
        $res = self::$objProfileMysql->updateOfflineBillingDetails($profileid);
        ProfileCacheLib::getInstance()->removeCache($profileid);
        return $res;
    }

    public function updateSubscriptionStatus($subscription, $profileid)
    {
        $paramArr = array('SUBSCRIPTION'=>$subscription);
        return $this->edit($paramArr, $profileid, 'PROFILEID');
    }

    public function updatePrivacy($privacy, $profileid)
    {
        $paramArr = array('PRIVACY'=>$privacy, 'MOD_DT'=>date('Y-m-d H:i:s'));
        return $this->edit($paramArr, $profileid, "PROFILEID","activatedKey=1");
    }

    public function SelectPrivacy($profileId)
    {
        return self::$objProfileMysql->SelectPrivacy($profileId);
    }


    public function SelectHide($profileid)
    {
        return self::$objProfileMysql->SelectHide($profileid);
    }

    public function SelectActicated($profileid)
    {
        return self::$objProfileMysql->SelectActicated($profileid);
    }

    public function updateHide($privacy, $profileid, $dayinterval)
    {
        $result = self::$objProfileMysql->updateHide($privacy, $profileid, $dayinterval);
        ProfileCacheLib::getInstance()->removeCache($profileid);
        return $result;
    }

    public function updateUnHide($privacy, $profileid)
    {
        $result = self::$objProfileMysql->updateUnHide($privacy, $profileid);
        ProfileCacheLib::getInstance()->removeCache($profileid);
        return $result;
    }

    public function SelectDeleteData($profileid)
    {
        return self::$objProfileMysql->SelectDeleteData($profileid);
    }


    public function updateDeleteData($profileid)
    {
        $result = self::$objProfileMysql->updateDeleteData($profileid);
        ProfileCacheLib::getInstance()->removeCache($profileid);
        return $result;

    }

    public function getEmailFromUsername($username)
    {
        return self::$objProfileMysql->getEmailFromUsername($username);
    }

    public function getEmailFromProfileId($profileid)
    {
        return self::$objProfileMysql->getEmailFromProfileId($profileid);
    }

    /**
     * Function to fetch profiles(registered after given date)
     *
     * @param   $registerDate ,$fieldsRequired(default-all JPROFILE fields)
     * @return $profilesArr - array of desired profiles
     */
    public function getRegisteredProfilesAfter($registerDate, $fieldsRequired = "*")
    {
        return self::$objProfileMysql->getRegisteredProfilesAfter($registerDate, $fieldsRequired);
    }

    /**
     * Function to update incomplete status for profiles
     *
     * @param   $profileid
     * @return update results
     */
    public function updateIncompleteProfileStatus($profileIdArray)
    {
        $result = self::$objProfileMysql->updateIncompleteProfileStatus($profileIdArray);
        ProfileCacheLib::getInstance()->removeCache($profileIdArray);
        return $result;
    }

    /**
     * Function to fetch profiles registered in last 3 days)
     * @return $profilesArr - array of desired profiles
     */
    public function getProfileQualityRegistationData($registerDate)
    {
        return self::$objProfileMysql->getProfileQualityRegistationData($registerDate);
    }

    public function getAllSubscriptionsArr($profileArr)
    {
        return self::$objProfileMysql->getAllSubscriptionsArr($profileArr);
    }

    /*
     * this function returns profileids with entry date specified
     * @return - profileid array
     */
    public function getProfilesWithGivenRegDates($dateArr)
    {
        return self::$objProfileMysql->getProfilesWithGivenRegDates($dateArr);
    }

    /*
     * this function return array of profileids who have registered within given dates
     * @param - date after which users have registered
     * @return - array of profiledids
     */
    public function getProfilesWithinGivenActiveDate($date)
    {
        return self::$objProfileMysql->getProfilesWithinGivenActiveDate($date);
    }

    public function getLatestValue($field)
    {
        return self::$objProfileMysql->getLatestValue($field);
    }

    /**
     * updateProfileForArchive
     * Update Profile Columns for archive i.e. setting
     * PREACTIVATED,ACTIVATED,activatedKey,JsArchived,MOD_DT column
     * @param type $iProfileID
     * @throws jsException
     * @return rowCount
     */
    public function updateProfileForArchive($iProfileID)
    {
        $result = self::$objProfileMysql->updateProfileForArchive($iProfileID);
        if($result) {
            ProfileCacheLib::getInstance()->removeCache($iProfileID);
        }
        return $result;
    }

    /**
     * updateProfileForBilling
     * Update Profile Columns for archive i.e. setting
     * PREACTIVATED,ACTIVATED,activatedKey,column
     * @param type $iProfileID
     * @throws jsException
     * @return rowCount
     */
    public function updateProfileForBilling($paramArr = array(), $value, $criteria = "PROFILEID", $extraStr = '')
    {
        $result = self::$objProfileMysql->updateProfileForBilling($paramArr, $value, $criteria, $extraStr);
        if($result && $criteria == "PROFILEID") {
            ProfileCacheLib::getInstance()->removeCache($value);
        }
        return $result;
    }

    /**
     * updateProfileSeriousnessCount
     * This query is in use at SugarCRM
     * @param $profileArr
     * @return bool
     */
    function updateProfileSeriousnessCount($profileArr)
    {
        $result = self::$objProfileMysql->updateProfileSeriousnessCount($profileArr);
        if ($result) {
            ProfileCacheLib::getInstance()->removeCache($profileArr);
        }
        return $result;
    }


    /**
     * updateForMutipleProfiles
     * This query is in use to edit values for mutiple profiles
     * @param $paramArr
     * @param $profileArr
     * @return bool
     */
    function updateForMutipleProfiles($paramArr, $profileArr)
    {
        $result = self::$objProfileMysql->updateForMutipleProfiles($paramArr, $profileArr);
        if ($result) {
            ProfileCacheLib::getInstance()->removeCache($profileArr);
        }
        return $result;
    }


    //This function gets data for CITY_RES/MTONGUE/(AGE/GENDER) grouped by the same along with month/day as per the condition
    public function getRegistrationMisGroupedData($fromDate, $toDate, $month = '', $groupType)
    {
        return self::$objProfileMysql->getRegistrationMisGroupedData($fromDate, $toDate, $month, $groupType);
    }

    public function checkUsername($username)
    {
        return self::$objProfileMysql->checkUsername($username);
    }

    /**
     * update sort date in Jprofile
     * @param $profileId
     * @return bool
     */
    function updateSortDate($profileId)
    {
        $result = self::$objProfileMysql->updateSortDate($profileId);
        if($result) {
            ProfileCacheLib::getInstance()->removeCache($profileId);
        }

        return $result;
    }


    private function getRelevantFields($fields)
    {
        $fields = $fields ? $fields : $this->getFields(); //Get columns to query
        $defaultFieldsRequired = array("HAVE_JCONTACT", "HAVEPHOTO", "MOB_STATUS", "LANDL_STATUS", "SUBSCRIPTION", "INCOMPLETE", "ACTIVATED", "PHOTO_DISPLAY", "GENDER", "PRIVACY");
        if (!stristr($fields, "*")) {
            if ($fields) {
                foreach ($defaultFieldsRequired as $k => $fieldName) {
                    if (!stristr($fields, $fieldName))
                        $fields .= "," . $fieldName;
                }
            } else {
                $fields = implode(", ", $defaultFieldsRequired);
            }
        }
        return $fields;
    }

    /**
     * @param $profileArr
     * @return mixed
     */
    public function DeactiveProfiles($profileArr)
    {
        $result = self::$objProfileMysql->DeactiveProfiles($profileArr);
        ProfileCacheLib::getInstance()->removeCache($profileArr);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getNewScreenProfileCount()
    {
        return self::$objProfileMysql->getNewScreenProfileCount();
    }

    /**
     * @return mixed
     */
    public function getEditScreenProfileCount()
    {
        return self::$objProfileMysql->getEditScreenProfileCount();
    }

    /**
     * @return mixed
     */
    public function getPhotoScreenAcceptQueueCount()
    {
        return self::$objProfileMysql->getPhotoScreenAcceptQueueCount();
    }

    /**
     * @return mixed
     */
    public function getPhotoScreenProcessQueueCount()
    {
        return self::$objProfileMysql->getPhotoScreenAcceptQueueCount();
    }

    /**
     * @return mixed
     */
    public function getEmailLike($email)
    {
        return self::$objProfileMysql->getEmailLike($email);
    }

    /**
     * @return mixed
     */
    public function updateEmail($email, $newEmail)
    {
        return self::$objProfileMysql->updateEmail($email,$newEmail);
    }
    /**
     * This function executes a select query on join of jprofile and incentives.name_of_user
     * to fetch PROFILEID,EMAIL,USERNAME for the profiles that match the criteria
     */
    public function getDataForLegal($nameArr,$age,$addressArr,$email)
    {
        return self::$objProfileMysql->getDataForLegal($nameArr, $age, $addressArr, $email);
    }
    
    public function getActiveProfiles($totalScript=1,$currentScript=0,$lastLoginWithIn='6 months',$limitProfiles=0){
        return self::$objProfileMysql->getActiveProfiles($totalScript,$currentScript,$lastLoginWithIn,$limitProfiles);
    }

    private function logCacheConsumption()
    {return;
        $key = 'cacheConsumeCount'.date('Y-m-d');
        JsMemcache::getInstance()->incrCount($key);

        $key .= '::'.date('H');
        JsMemcache::getInstance()->incrCount($key);

    }

    private function totalQueryCount()
    {return;
        $key = 'totalQueryCount'.date('Y-m-d');
        JsMemcache::getInstance()->incrCount($key);

        $key .= '::'.date('H');
        JsMemcache::getInstance()->incrCount($key);
    }
    
    /**
     * 
     * @param type $Var
     */
    private function logProfileIDs($Var)
    {
      if(is_array($Var)) {
        $Var = implode(',',$Var);
      }
      
      $now = time();//date('Y-m-d H:i:s').':'.uniqid();
      JsMemcache::getInstance()->zAdd('JPROFILE_GET_ARRAY', $now, $Var);
    }

    //This function is used to fetch the latest entry date in JPROFILE so as to check in MIS whether there is a lag in slave.
    public function getLatestEntryDate()
    {
        return self::$objProfileMysql->getLatestEntryDate();
    }

    public function getZombieProfiles($gtDate,$limit=0,$ltDate=null) 
    {
        return self::$objProfileMysql->getZombieProfiles($gtDate,$limit,$ltDate);
    }
}

?>
