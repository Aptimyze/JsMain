<?php

class matchAlertMailerDataTracking
{	
	//This function is used to insert data for count of profiles based on logic level
	public function insertCountDataByLogicLevel($countByLogicArr,$date)
	{			
		$countByLogicObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC('newjs_master');
		$rowCount = $countByLogicObj->insertCountByLogicTypeForDate($date,$countByLogicArr);
		if($rowCount == 0)
		{
			$this->sendSMS(MatchAlertDataLoggingEnums::$messageByLogic);
		}

		//This is called to save the total count of profiles grouped by logic level
		$this->insertTotalCountByLogicLevel($date);
		unset($countByLogicObj);		
	}

	//This function is used to insert data for count of profiles based on logic level and no of recommendations
	public function insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations,$date)
	{		
		$loggingObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND('newjs_master');
		$rowCount = $loggingObj->insertCountByLogicTypeAndRecommendForDate($date,$countByLogicAndRecommendations);				
		if($rowCount == 0)
		{
			$this->sendSMS(MatchAlertDataLoggingEnums::$messageByLogicRecommend);
		}
		unset($loggingObj);		
	}
	
    public function insertTotalCountByLogicLevel($date)
    {
    	$matchAlertsToBeSentObj = new matchalerts_MATCHALERTS_TO_BE_SENT();
		$totalCount = $matchAlertsToBeSentObj->getTotalCount($date);		
		$matchAlertByLogicTotalObj = new MATCHALERT_TRACKING_MATCH_ALERT_BYLOGIC_TOTAL('newjs_master');
		$rowCount = $matchAlertByLogicTotalObj->insertTotalCountForDate($date,$totalCount);
		if($rowCount == 0)
		{
			$this->sendSMS(MatchAlertDataLoggingEnums::$messageByLogicTotal);
		}		
		unset($matchAlertByLogicTotalObj);
		unset($matchAlertsToBeSentObj);
    }

    public function insertTotalCountGroupedByLogicAndReceiver($date)        
    {       	
    	$logTempObj = new matchalerts_LOG_TEMP();
    	$totalCountByLogicReceiver = $logTempObj->getTotalCountGroupedByLogicAndReceiver();    	  	
    	$totalCountObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND_TOTAL('newjs_master');
		$rowCount = $totalCountObj->insertTotalCountForRecommedByDate($totalCountByLogicReceiver,$date);
		$lowTrendsObj = new matchalerts_LowTrendsMatchalertsCheck();
		$zeroCountArr = $lowTrendsObj->getZeroCountProfiles();
		$rowCount = $totalCountObj->insertTotalCountForRecommedByDate($zeroCountArr,$date);
		if($rowCount == 0)
		{
			$this->sendSMS(MatchAlertDataLoggingEnums::$messageByLogicRecommendTotal);
		}
		unset($totalCountObj);
    }

    public function sendSMS($message)
    {
    	include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
    	foreach(MatchAlertDataLoggingEnums::$arrMob as $mobile1)
				$smsState = send_sms($message,MatchAlertDataLoggingEnums::$from,$mobile1,MatchAlertDataLoggingEnums::$uniqueId,'','Y');
    }	
}