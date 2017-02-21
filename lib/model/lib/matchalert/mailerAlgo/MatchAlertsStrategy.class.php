<?php
/**
* MatchAlerts Base class strategy.
*/
abstract class MatchAlertsStrategy
{
	protected $removeMatchAlerts = 1;
        private $frequency = 1;
        private $limitNtRec = 16;
	private $limitTRec = 10;
	abstract function getMatches();
  

        public function logRecords($receiverId,$profileIds,$logicLevel,$limit,$listCount = 0,$matchesSetting=''){

                $profileIdsForList = array();
                if($logicLevel == MailerConfigVariables::$strategyReceiversNT)
                        $profileIds = array_slice($profileIds,0,$this->limitNtRec);
                else{
                        $profileIds = array_slice($profileIds,0,$this->limitTRec);
                }
                $matchalertLogObj = new matchalerts_LOG();
                $matchalertTempLogObj = new matchalerts_LOG_TEMP();
                
         
                $matchalertLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
                $matchalertTempLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
                
                /*$mCache = new MatchAlertsLogCaching();
                $mCache->setAddCacheKey($receiverId,$profileIds);
                unset($mCache);*/
                JsMemcache::getInstance()->remove($receiverId."_MATCHALERTS_LOG_ALL"); // unset log cache
                
                unset($matchalertLogObj);
                unset($matchalertTempLogObj);

                $day_of_week=date("w");
                if($matchesSetting != 'U' && ($matchesSetting == 'A' || $matchesSetting == '' || in_array($day_of_week,array('1','3','5'))))
                {
                  $matchalertMailerObj = new matchalerts_MAILER();
                  $matchalertMailerObj->insertLogRecords($receiverId, $profileIds, $logicLevel, $this->frequency);
                  unset($matchalertMailerObj);
                }
        }
}
?>
