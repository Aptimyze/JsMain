<?php

class matchAlertMailerDataTracking
{
	
	public function insertCountDataByLogicLevel($countByLogicArr)
	{		
		$todayDate = date("Y-m-d");
		$countByLogicObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC();
		$countByLogicObj->insertCountByLogicTypeForDate($todayDate,$countByLogicArr);
		
		$matchAlertsToBeSentObj = new matchalerts_MATCHALERTS_TO_BE_SENT();
		$totalCount = $matchAlertsToBeSentObj->getTotalCount($todayDate);		
		$matchAlertByLogicTotalObj = new MATCHALERT_TRACKING_MATCH_ALERT_BYLOGIC_TOTAL();
		$matchAlertByLogicTotalObj->insertTotalCountForDate($todayDate,$totalCount);

	}

	public function insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations)
	{		
		$todayDate = date("Y-m-d");
		$loggingObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND();
		$loggingObj->insertCountByLogicTypeAndRecommendForDate($todayDate,$countByLogicAndRecommendations);				
		
		foreach($countByLogicAndRecommendations as $key =>$val)
		{
			foreach($val as $k=>$v)
			{
				if($k == "RecCount")
				{
					$totalCountArr[$v] += $val["PeopleCount"]; 
				}
			}
		}

		$totalCountObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC_RECOMMEND_TOTAL();
		$totalCountObj->insertTotalCountForRecommedByDate($totalCountArr,$todayDate);		
	}

	public function getNoOfDays()
    {
            $today=mktime(0,0,0,date("m"),date("d"),date("Y"));
            $zero=mktime(0,0,0,01,01,2005);
            $gap=($today-$zero)/(24*60*60);          
            return $gap;
    }
}