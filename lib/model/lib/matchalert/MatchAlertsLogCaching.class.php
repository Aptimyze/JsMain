<?php
/*This class is used to handle the matchalerts from cache*/
class MatchAlertsLogCaching
{
        private $keyTimings = 18000;
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}
        public function getMatchAlertProfilesFromTable($profileId,$dateGreaterThanCondition=""){
                $matchalerts_LOG = new matchalerts_LOG($this->dbname);
                $profileArray = $matchalerts_LOG->getMatchAlertProfiles($profileId,$dateGreaterThanCondition);
                return $profileArray;
        }
        public function getMatchAlertProfiles($profileId,$dateGreaterThanCondition="")
	{
                if(JsConstants::$whichMachine == 'matchAlert'){
                      $profileArray = $this->getMatchAlertProfilesFromTable($profileId,$dateGreaterThanCondition); 
                      return $profileArray;
                }
                $keys = JsMemcache::getInstance()->getSetsAllValue($profileId."_MATCHALERTS_LOG_ALL");
                if(JsMemcache::getInstance()->keyExist($profileId."_MATCHALERTS_LOG_ALL") && ($keys && ($keys[0] == "0" || $keys[0] != "")) ){
                        $profileArray= array();
                        if($keys){
                                $lastpartitionedOn = JsMemcache::getInstance()->get("MATCHALERTS_PARTITIONED_DT");
                                foreach($keys as $key){
                                        $profileIdDate = explode("_",$key);
                                        if($profileIdDate[0] != "0"){
                                                if((($dateGreaterThanCondition && $dateGreaterThanCondition < $profileIdDate[1]) || $dateGreaterThanCondition == "") && ($profileIdDate[1] >= $lastpartitionedOn)){
                                                        $profileArray[$profileIdDate[0]]   = $profileIdDate[1];
                                                }
                                        }
                                }
                        }
                }else{
                        $profileArray = $this->getMatchAlertProfilesFromTable($profileId,$dateGreaterThanCondition);
                        $keyArr = array();
                        if($profileArray){
                                foreach($profileArray as $mProfileId=>$intDate){
                                        $keyArr[] = $mProfileId."_".$intDate;
                                }
                        }else{
                                $keyArr = 0;
                        }
                        if($dateGreaterThanCondition==''){
                                JsMemcache::getInstance()->remove($profileId."_MATCHALERTS_LOG_ALL"); // remove if cache contains "" at first value then set
                                JsMemcache::getInstance()->storeDataInCacheByPipeline($profileId."_MATCHALERTS_LOG_ALL",$keyArr,$this->keyTimings);
                        }
                }
                return $profileArray;
        }
        public function setAddCacheKey($profileId,$profiles){
                $keys = JsMemcache::getInstance()->getSetsAllValue($profileId."_MATCHALERTS_LOG_ALL");
                if(JsMemcache::getInstance()->keyExist($profileId."_MATCHALERTS_LOG_ALL") && ($keys && ($keys[0] == "0" || $keys[0] != "")) ){
                        $date=MailerConfigVariables::getNoOfDays();
                        $keyArr = array();
                        foreach($profiles as $pid){
                                $keyArr[] = $pid."_".$date;
                        }
                        JsMemcache::getInstance()->addDataToCache($profileId."_MATCHALERTS_LOG_ALL",$keyArr,$this->keyTimings);
                }else{
                        JsMemcache::getInstance()->remove($profileId."_MATCHALERTS_LOG_ALL");
                }
        }
}
?>
