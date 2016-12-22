<?php
/*
*  This Class is used for Caching of JProfile_Alerts. All the calls to the store are made through this Library only. It checks if the code is present in Cache or not. If it is, It returns the result , else it makes a call to DB.
*
*/
class JprofileAlertsCache 
{

    private $dbname;

    public function __construct($dbname = "") {
        $this->dbname = $dbname;
    }
    //Tested
    public function fetchMembershipStatus($profileid) {

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";

        $fields = "MEMB_CALLS,OFFER_CALLS";

        $bServedFromCache = false;

        if($objProCacheLib->isCached($criteria,$profileid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result['MEMB_CALLS'] && in_array($result['MEMB_CALLS'], $validNotFilled)){
                $result = NULL;
            }   

             if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
                $this->logCacheConsumeCount(__CLASS__);
                return $result;
            }
        }
        
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->fetchMembershipStatus($profileid);
        $dummyResult['RESULT_VAL'] = $result;
        $dummyResult = array(); 
        if($result === NULL)
        {
            $tempInsertResult['MEMB_CALLS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['MEMB_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['CONTACT_ALERT_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['PHOTO_REQUEST_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['NEW_MATCHES_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['SERVICE_SMS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['SERVICE_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $dummyResult['RESULT_VAL'] = $tempInsertResult;
        }
    
        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        return $result;   

    }


    /** Function insert added by Hemant
     This function is used to insert the email. call,sms alert data from page 1 registration.
     *
     * @param $profileid
     * @param $alertArr
     *
     *
     *
     */
 
    public function insert($profileid, $alertArr, $argFrom = 'R') {

             $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $out = $objJALT-> insert($profileid, $alertArr, $argFrom = 'R');
        if($out === true)
        {

            $keys = array('PROFILEID','MEMB_CALLS','OFFER_CALLS','SERV_CALLS_SITE','SERV_CALLS_PROF','MEMB_MAILS','CONTACT_ALERT_MAILS','KUNDLI_ALERT_MAILS','PHOTO_REQUEST_MAILS','SERVICE_MAILS','SERVICE_SMS','SERVICE_MMS','SERVICE_USSD','PROMO_USSD','PROMO_MMS');

            $values = array($profileid,$alertArr['SERVICE_CALL'],$alertArr['SERVICE_CALL'],$alertArr['MEM_IVR'],$alertArr['MEM_IVR'],$alertArr['MEM_MAILS'],$alertArr['SERVICE_EMAIL'],$alertArr['SERVICE_EMAIL'],$alertArr['SERVICE_EMAIL'],$alertArr['SERVICE_EMAIL'],$alertArr['SERVICE_SMS'],$alertArr['SERVICE_SMS'],$alertArr['SERVICE_SMS'],$alertArr['MEM_SMS'],$alertArr['MEM_SMS']);

            $tempInsertResult = array_fill_keys($keys, $values);

            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $tempInsertResult, __CLASS__);

        }
  
        //Update EDIT_LOG_JPROFILE_ALERTS has not been moved into Cache as it belongs to a different table.

    }

    //Done
    public function getUnsubscribedProfiles($profileIdArr) {     
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->getUnsubscribedProfiles($profileIdArr);
        return $result;
    }

//tested
    public function getSubscriptions($profileid, $field) {

        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";

        $fields = $field;

        $bServedFromCache = false;

        if($objProCacheLib->isCached($criteria,$profileid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            $arr = explode(',',$field);
            if($result && in_array($arr[0], $validNotFilled)){
                $result = NULL;
            }   

             if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
                $this->logCacheConsumeCount(__CLASS__);
                return $result;
            }
        }
        
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->getSubscriptions($profileid, $field);
        $dummyResult['RESULT_VAL'] = $result;
        if($result === NULL)
        {
            $tempInsertResult['MEMB_CALLS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['MEMB_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['CONTACT_ALERT_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['PHOTO_REQUEST_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['NEW_MATCHES_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['SERVICE_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $dummyResult['RESULT_VAL'] = $tempInsertResult;

        }

        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        return $result;
        
    }

    public function getAllSubscriptions($profileid) {
        
        $objProCacheLib = ProfileCacheLib::getInstance();

        $criteria = "PROFILEID";

        $fields = "*";

        $bServedFromCache = false;

        if($objProCacheLib->isCached($criteria,$profileid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result && in_array($result['MEMB_CALLS'], $validNotFilled)){
                $result = NULL;
            }   

             if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
                $this->logCacheConsumeCount(__CLASS__);
                return $result;
            }
        }
        
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->getAllSubscriptions($profileid);
        $dummyResult['RESULT_VAL'] = $result;

        if($result === NULL)
        {
            $tempInsertResult['MEMB_CALLS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['MEMB_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['CONTACT_ALERT_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['PHOTO_REQUEST_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['NEW_MATCHES_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult['SERVICE_MAILS'] = ProfileCacheConstants::NOT_FILLED;
            $dummyResult['RESULT_VAL'] = $tempInsertResult;

        }

        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        return $result;
    }

    public function getAllSubscriptionsArr($profileArr) {
     
            $output = ProfileCacheLib::getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA,$profileArr,'*',__CLASS__);              

                 return $output;
             } 
 
    public function update($profileid, $key, $val) {

        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $out = $objJALT->update($profileid, $key, $val);
        if($out === true)
        {   
            $updateCacheVal[$key] = (intval($val) === 0 || ($val === NULL)) ? ProfileCacheConstants::NOT_FILLED : $val;

            ProfileCacheLib::getInstance()->updateCache($updateCacheVal,ProfileCacheConstants::CACHE_CRITERIA, $profileid ,__CLASS__);
        }

    }

    //Reviewed
    public function insertNewRow($profileid) {
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $out = $objJALT-> insertNewRow($profileid);
        if($out === true)
        {

           $keys = array('PROFILEID','MEMB_CALLS','OFFER_CALLS','SERV_CALLS_SITE','SERV_CALLS_PROF','MEMB_MAILS','CONTACT_ALERT_MAILS','KUNDLI_ALERT_MAILS','PHOTO_REQUEST_MAILS','SERVICE_MAILS','SERVICE_SMS','SERVICE_MMS','SERVICE_USSD','PROMO_USSD','PROMO_MMS');
            
           $values = array($profileid,'S','S','S','S','S','S','S','S','S','S','S','S','S','S');

           $tempInsertResult = array_fill_keys($keys, $values);

            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $tempInsertResult, __CLASS__);

        }   

    }

    /*
     * @param $arrRecordData
     * @return mixed
     */
    
    public function insertRecord($arrRecordData)
    {

         $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $out = $objJALT-> insertRecord($arrRecordData);
        if($out === true)
        {
       
            foreach($arrRecordData as $key=>$val)
            {
                $tempInsertResult[strtoupper($key)] = $val;
            }    

            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $arrRecordData['PROFILEID'], $tempInsertResult, __CLASS__);

        }     
    } 

    private function logCacheConsumeCount($funName)
  { 
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}

?>
