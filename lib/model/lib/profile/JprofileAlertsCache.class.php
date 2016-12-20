<?php
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

            $tempInsertResult['PROFILEID'] = $profileid;
            $tempInsertResult['MEMB_CALLS'] = $alertArr['SERVICE_CALL'];
            $tempInsertResult['OFFER_CALLS'] = $alertArr['SERVICE_CALL'];
            $tempInsertResult['SERV_CALLS_SITE'] = $alertArr['MEM_IVR'];
            $tempInsertResult['SERV_CALLS_PROF'] = $alertArr['MEM_IVR'];
            $tempInsertResult['MEMB_MAILS'] = $alertArr['MEM_MAILS'];
            $tempInsertResult['CONTACT_ALERT_MAILS'] = $alertArr['SERVICE_EMAIL'];
            $tempInsertResult['KUNDLI_ALERT_MAILS'] = $alertArr['SERVICE_EMAIL'];
            $tempInsertResult['PHOTO_REQUEST_MAILS'] = $alertArr['SERVICE_EMAIL'];
            $tempInsertResult['SERVICE_MAILS'] = $alertArr['SERVICE_EMAIL'];
            $tempInsertResult['SERVICE_SMS'] = $alertArr['SERVICE_SMS'];
            $tempInsertResult['SERVICE_MMS'] = $alertArr['SERVICE_SMS'];
            $tempInsertResult['SERVICE_USSD'] = $alertArr['SERVICE_SMS'];
            $tempInsertResult['PROMO_USSD'] = $alertArr['MEM_SMS'];
            $tempInsertResult['PROMO_MMS'] = $alertArr['MEM_SMS'];

            $dummyResult['RESULT_VAL'] = $tempInsertResult;
            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        }
  
        //Update EDIT_LOG_JPROFILE_ALERTS has not been moved into Cache as it belongs to a different table.

    }

    //Done
    public function getUnsubscribedProfiles($profileIdArr) {     
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->getUnsubscribedProfiles($profileIdArr);
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
     
                
            foreach ($profileArr as $key => $value) {

                    $tempResult = $this->getAllSubscriptions($value);
                    $output[$value] = $tempResult;

                          }              

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

            $tempInsertResult['PROFILEID'] = $profileid;
            $tempInsertResult['MEMB_CALLS'] = 'S';
            $tempInsertResult['OFFER_CALLS'] = 'S';
            $tempInsertResult['SERV_CALLS_SITE'] = 'S';
            $tempInsertResult['SERV_CALLS_PROF'] = 'S';
            $tempInsertResult['MEMB_MAILS'] = 'S';
            $tempInsertResult['CONTACT_ALERT_MAILS'] = 'S';
            $tempInsertResult['KUNDLI_ALERT_MAILS'] = 'S';
            $tempInsertResult['PHOTO_REQUEST_MAILS'] = 'S';
            $tempInsertResult['NEW_MATCHES_MAILS'] = 'S';
            $tempInsertResult['SERVICE_SMS'] = 'S';
            $tempInsertResult['SERVICE_MMS'] = 'S';
            $tempInsertResult['SERVICE_USSD'] = 'S';
            $tempInsertResult['PROMO_USSD'] = 'S';
            $tempInsertResult['SERVICE_MAILS'] = 'S';
            $tempInsertResult['PROMO_MMS'] = 'S';

            $dummyResult['RESULT_VAL'] = $tempInsertResult;
            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $dummyResult['RESULT_VAL'], __CLASS__);

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

            $dummyResult['RESULT_VAL'] = $tempInsertResult;
            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $arrRecordData['PROFILEID'], $dummyResult['RESULT_VAL'], __CLASS__);

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
