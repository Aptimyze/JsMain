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
                
                $memObject=JsMemcache::getInstance();
                $logDate = $memObject->get('MATCHALERT_LOG_DATE');
                if($logDate === false || $logDate == ''){
                        $logDate = strtotime(MailerConfigVariables::$matchalertsLogTimeFor);
                        $memObject->set('MATCHALERT_LOG_DATE',$logDate,  MailerConfigVariables::$matchalertsLogTimeCache);
                }
                $dateForMailer = date("Y-m-d",$logDate);
                $date=MailerConfigVariables::getNoOfDays($logDate);
                $matchalertLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel,$date);
                $matchalertTempLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel,$date);
                
                $mCache = new MatchAlertsLogCaching();
                $mCache->setAddCacheKey($receiverId,$profileIds);
                unset($mCache);
                
                unset($matchalertLogObj);
                unset($matchalertTempLogObj);

                $day_of_week=date("w");
                if($matchesSetting != 'U' && ($matchesSetting == 'A' || $matchesSetting == '' || in_array($day_of_week,array('1','3','5'))))
                {
                  $matchalertMailerObj = new matchalerts_MAILER();
                  $matchalertMailerObj->insertLogRecords($receiverId, $profileIds, $logicLevel, $this->frequency,$dateForMailer);
                  unset($matchalertMailerObj);
                }
        }
}
?>
