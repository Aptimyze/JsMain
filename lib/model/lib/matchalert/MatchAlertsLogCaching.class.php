<?php
/*This class is used to handle the matchalerts from cache*/
class MatchAlertsLogCaching
{
        private $keyTimings = 18000;
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}
        public function getProfilesSentInMatchAlerts($profileId,$seperator="")
	{
                if(JsConstants::$whichMachine == 'matchAlert'){
                      $profileArray = $this->getProfilesSentInMatchAlertsFromTable($profileId,$seperator); 
                      return $profileArray;
                }
                $profileArray = array();
                $profiles = $this->getMatchAlertProfiles($profileId);
                if($profiles){
                        foreach($profiles as $pId=>$profile){
                                $profileArray[] = $pId;
                        }
                }
                if($seperator == 'spaceSeperator'){
                        $profileArrayString = implode(" ",$profileArray)." ";
                        return $profileArrayString;
                }
                return $profileArray;
        }
        public function getProfilesSentInMatchAlertsFromTable($profileId,$seperator=""){
                $matchalerts_LOG = new matchalerts_LOG($this->dbname);
                $profileArray = $matchalerts_LOG->getMatchAlertProfiles($profileId,$dateGreaterThanCondition);
                return $profileArray;
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
                if(!JsMemcache::getInstance()->keyExist($profileId."_MATCHALERTS_LOG_ALL") || ($keys && ($keys[0] != "0" || $keys[0]=="")) ){
                        $profileArray = $this->getMatchAlertProfilesFromTable($profileId,$dateGreaterThanCondition);
                        $keyArr = array();
                        if($profileArray){
                                foreach($profileArray as $mProfileId=>$intDate){
                                        $keyArr[] = $mProfileId."_".$intDate;
                                }
                        }else{
                                $keyArr = 0;
                        }
                        JsMemcache::getInstance()->storeDataInCacheByPipeline($profileId."_MATCHALERTS_LOG_ALL",$keyArr,$this->keyTimings);
                }else{
                        $profileArray= array();
                        if($keys){
                                foreach($keys as $key){
                                        $profileIdDate = explode("_",$key);
                                        if($profileIdDate[0] != "0"){
                                                if(($dateGreaterThanCondition && $dateGreaterThanCondition < $profileIdDate[1]) || $dateGreaterThanCondition == ""){
                                                        $profileArray[$profileIdDate[0]]   = $profileIdDate[1];
                                                }
                                        }
                                }
                        }
                        //echo '<pre>';print_r($profileArray);die;
                }
                return $profileArray;
        }
        public function setAddCacheKey($profileId,$profiles){
                $date=MailerConfigVariables::getNoOfDays();
                $keyArr = array();
                foreach($profiles as $pid){
                        $keyArr[] = $pid."_".$date;
                }
                JsMemcache::getInstance()->addDataToCache($profileId."_MATCHALERTS_LOG_ALL",$keyArr,$this->keyTimings);
        }
}
?>
