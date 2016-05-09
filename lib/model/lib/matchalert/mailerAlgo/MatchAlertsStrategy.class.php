<?php
/**
* MatchAlerts Base class strategy.
*/
abstract class MatchAlertsStrategy
{
	protected $removeMatchAlerts = 1;
        private $frequency = 1;
	abstract function getMatches();
  
        public function logRecords($receiverId,$profileIds,$logicLevel,$limit){
	  $profileIds = array_slice($profileIds,0,$limit);
          $matchalertLogObj = new matchalerts_LOG();
          $matchalertLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
          unset($matchalertLogObj);

          $matchalertTempLogObj = new matchalerts_LOG_TEMP();
          $matchalertTempLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
          unset($matchalertTempLogObj);

          $matchalertMailerObj = new matchalerts_MAILER();
          $matchalertMailerObj->insertLogRecords($receiverId, $profileIds, $logicLevel, $this->frequency);
          unset($matchalertMailerObj);
        }
}
?>
