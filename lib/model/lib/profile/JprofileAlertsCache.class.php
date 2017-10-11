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

            $tempInsertResult = array_combine($keys, $values);

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
            
            $objProCacheLib = ProfileCacheLib::getInstance();
            $output = $objProCacheLib->getForMultipleKeys(ProfileCacheConstants::CACHE_CRITERIA,$profileArr,'*',__CLASS__);  

            if (is_array($output) && false !== $output) { 
            $bServedFromCache = true;
            $output = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $output);
            }

        if ($bServedFromCache && ProfileCacheConstants::CONSUME_PROFILE_CACHE) {
            $this->logCacheConsumeCount(__CLASS__);
            return $output; 
        }
            
            if($output == false)
            {
                
                $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
                $newOutput = $objJALT->getAllSubscriptionsArr($profileArr);

            if($newOutput == NULL)
            {  
            $keys = array('PROMO_MMS','PROMO_USSD','SERVICE_USSD','SERVICE_MMS','KUNDLI_ALERT_MAILS','SERV_CALLS_PROF','SERV_CALLS_SITE','OFFER_CALLS','MEMB_CALLS','MEMB_MAILS','CONTACT_ALERT_MAILS','PHOTO_REQUEST_MAILS','SERVICE_MAILS','SERVICE_SMS','NEW_MATCHES_MAILS');
            $values = ProfileCacheConstants::NOT_FILLED;
            $tempInsertResult = array_fill_keys($keys, $values);
                foreach ($profileArr as $key => $value) {
            $tempInsertResult['PROFILEID'] = $value;       
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$value, $tempInsertResult, __CLASS__);

                }

                return $newOutput;
            }    
                     
            if($newOutput != NULL && is_array($newOutput))
            {           
                foreach ($profileArr as $key => $value) {
             $tempInsertResult = $newOutput[$value];
             if(($tempInsertResult) == NULL)
             {

                 $keys = array('PROMO_MMS','PROMO_USSD','SERVICE_USSD','SERVICE_MMS','KUNDLI_ALERT_MAILS','SERV_CALLS_PROF','SERV_CALLS_SITE','OFFER_CALLS','MEMB_CALLS','MEMB_MAILS','CONTACT_ALERT_MAILS','PHOTO_REQUEST_MAILS','SERVICE_MAILS','SERVICE_SMS','NEW_MATCHES_MAILS');
             $values = ProfileCacheConstants::NOT_FILLED;

             $tempInsertResult = array_fill_keys($keys, $values);
             $tempInsertResult['PROFILEID'] = $value;
             $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$value, $tempInsertResult, __CLASS__);

             }
             else{
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$value, $tempInsertResult, __CLASS__);
            }
                }

            } 
            }           
                return $newOutput;
             } 
 
    public function update($profileid, $key, $val) {

        $objJALT = new newjs_JPROFILE_ALERTS($this->dbName);
        list($out,$affectedRows) = $objJALT->update($profileid, $key, $val);
        if($affectedRows == 0){
            $jprofileAlertObj = new JprofileAlertsCache();
            $row = $jprofileAlertObj->getAllSubscriptions($profileid);
            unset($jprofileAlertObj);
            if($row != NULL){
                $row["PROFILEID"] = $profileid;
                $objJALT->insertAllColumns($row);
            }
            else{
                $jprofileAlertObj->insertNewRow($profileid);
            }
        }
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

           $keys = array('MEMB_CALLS','OFFER_CALLS','SERV_CALLS_SITE','SERV_CALLS_PROF','MEMB_MAILS','CONTACT_ALERT_MAILS','KUNDLI_ALERT_MAILS','PHOTO_REQUEST_MAILS','NEW_MATCHES_MAILS','SERVICE_MAILS','SERVICE_SMS','SERVICE_MMS','SERVICE_USSD','PROMO_USSD','PROMO_MMS');
            
           $values = 'S';

           $tempInsertResult = array_fill_keys($keys, $values);
           $tempInsertResult['PROFILEID'] = $profileid;

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

            $pid = $arrRecordData['PROFILEID'];


            $objProCacheLib = ProfileCacheLib::getInstance();
            $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA, $pid, $tempInsertResult, __CLASS__);

        }     
    }

    /*
     * @param string profileid
     * @param string fields
     * @param string onlyValues
     * @return mixed
     * The function if called with $onlyValue = 1, then it will only fetch the value present at that particular field. Thus we can only pass a single field for using $onlyValue
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
              $keys = array('MEMB_CALLS','OFFER_CALLS','SERV_CALLS_SITE','SERV_CALLS_PROF','MEMB_MAILS','CONTACT_ALERT_MAILS','KUNDLI_ALERT_MAILS','PHOTO_REQUEST_MAILS','NEW_MATCHES_MAILS','SERVICE_MAILS','SERVICE_SMS','SERVICE_MMS','SERVICE_USSD','PROMO_USSD','PROMO_MMS');
             $values = ProfileCacheConstants::NOT_FILLED;

             $tempInsertResult = array_fill_keys($keys, $values);
             $dummyResult['RESULT_VAL'] = $tempInsertResult;
        }



        $objProCacheLib->cacheThis(ProfileCacheConstants::CACHE_CRITERIA,$profileid, $dummyResult['RESULT_VAL'], __CLASS__);

        return $result;  
            
    }  

    private function logCacheConsumeCount($funName)
  { return;
   /* $key = 'cacheConsumption'.'_'.date('Y-m-d');
    JsMemcache::getInstance()->hIncrBy($key, $funName);
    
    JsMemcache::getInstance()->hIncrBy($key, $funName.'::'.date('H'));*/
  }
}

?>
