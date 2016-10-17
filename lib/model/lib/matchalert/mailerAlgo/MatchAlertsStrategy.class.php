<?php
/**
* MatchAlerts Base class strategy.
*/
abstract class MatchAlertsStrategy
{
	protected $removeMatchAlerts = 1;
        private $frequency = 1;
	abstract function getMatches();
  
        public function logRecords($receiverId,$profileIds,$logicLevel,$limit,$listCount = 0,$matchesSetting=''){
          $profileIdsForList = array();
          if($listCount != 0)
                $profileIdsForList = array_slice($profileIds,0,$listCount); // Profile id list to be added to listings according to new logic
          
	  $profileIds = array_slice($profileIds,0,$limit);
          
          $matchalertLogObj = new matchalerts_LOG();
          $matchalertTempLogObj = new matchalerts_LOG_TEMP();
          
          if(count($profileIdsForList) > count($profileIds)){
                $matchalertLogObj->insertLogRecords($receiverId, $profileIdsForList, $logicLevel);
                $matchalertTempLogObj->insertLogRecords($receiverId, $profileIdsForList, $logicLevel);
          }else{
                $matchalertLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
                $matchalertTempLogObj->insertLogRecords($receiverId, $profileIds, $logicLevel);
          }
          
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
