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
  
        public function logRecords($receiverId,$profileIds,$logicLevel,$limit,$listCount = 0){
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
          
                unset($matchalertLogObj);
                unset($matchalertTempLogObj);

                $matchalertMailerObj = new matchalerts_MAILER();
                $matchalertMailerObj->insertLogRecords($receiverId, $profileIds, $logicLevel, $this->frequency);
                unset($matchalertMailerObj);
        }
}
?>
