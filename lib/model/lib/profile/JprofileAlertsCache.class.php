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

    public function fetchMembershipStatus($profileid) {

        $strFields = "MEMB_CALLS,OFFER_CALLS";
        $result = $this->commonFunctionForSelect($profileid,$strFields);
        return $result;
    }

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

    public function getUnsubscribedProfiles($profileIdArr) {     
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $result = $objJALT->getUnsubscribedProfiles($profileIdArr);
        return $result;
    }


    public function getSubscriptions($profileid, $field) {

        $strFields = $field;
        $result = $this->commonFunctionForSelect($profileid,$strFields,'1');
        return $result;
        
    }

    public function getAllSubscriptions($profileid) {

        $field = "*";
        $strFields = $field;
        $result = $this->commonFunctionForSelect($profileid,$strFields);
        return $result;

    }

    public function getAllSubscriptionsArr($profileArr) {
       
            $output = ProfileCacheLib::getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA,$profileArr,'*',__CLASS__);  
            
            if($output == false)
            {

                $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
                $output = $objJALT->getAllSubscriptionsArr($profileArr);
                $objProCacheLib = ProfileCacheLib::getInstance();
                        
                foreach ($output as $key => $value) {
             $tempInsertResult = $value;
             unset($tempInsertResult['PROFILEID']);       
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$key, $tempInsertResult, __CLASS__);
                }

            }            
                return $output;
             } 
 
    public function update($profileid, $key, $val) {

        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        $out = $objJALT->update($profileid, $key, $val);
        if($out === true)
        {   
            $updateCacheVal[$key] = $val;

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

    /*
     * @param string profileid
     * @param string fields
     * @param string onlyValues
     * @return mixed
     */

      public function commonFunctionForSelect($profileid,$strFields,$onlyValue = 0)
    {

        $objProCacheLib = ProfileCacheLib::getInstance();
        
        $criteria = "PROFILEID";
        $fields = $strFields;
        $bServedFromCache = false;

        if($objProCacheLib->isCached($criteria,$profileid,$fields,__CLASS__)) 
            {

                $result = $objProCacheLib->get(ProfileCacheConstants::CACHE_CRITERIA, $profileid, $fields, __CLASS__);

           if (false !== $result) {
                $bServedFromCache = true;
                $result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
            }

            $columnForNotNullCondition = explode(',',$strFields)[0];
            $validNotFilled = array('N', ProfileCacheConstants::NOT_FILLED);
            
            if($result[$columnForNotNullCondition] && in_array($result[$columnForNotNullCondition], $validNotFilled)){
                $result = NULL;
            }   

             if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
                $this->logCacheConsumeCount(__CLASS__);
                if($onlyValue){
                    return $result[$fields];
                }
                return $result;
            }
        }
        
        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName); 
        $result = $objJALT->commonSelectFunction($profileid,$strFields,$onlyValue);
        $dummyResult['RESULT_VAL'] = $result;
 
        if($result === NULL)
        {   
             $keys = array('MEMB_CALLS','MEMB_MAILS','CONTACT_ALERT_MAILS','PHOTO_REQUEST_MAILS','SERVICE_MAILS','SERVICE_SMS','NEW_MATCHES_MAILS');
             $values = array(ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED,ProfileCacheConstants::NOT_FILLED);

             $tempInsertResult = array_fill_keys($keys, $values);
             $dummyResult['RESULT_VAL'] = $tempInsertResult;
        }



        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        return $result;  
            
    }  

    private function logCacheConsumeCount($funName)
  { 
    $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));
  }
}

?>
