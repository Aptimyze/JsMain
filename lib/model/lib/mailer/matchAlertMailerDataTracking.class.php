<?php

class matchAlertMailerDataTracking
{
	public function insertCountDataByLogicLevel($countByLogicArr)
	{		
		$todayDate = date("Y-m-d");
		$countByLogicObj = new MATCHALERT_TRACKING_MATCH_ALERT_DATA_BY_LOGIC();
		$countByLogicObj->insertCountByLogicTypeForDate($todayDate,$countByLogicArr);
		
		/*$matchAlertsToBeSentObj = new matchalerts_MATCHALERTS_TO_BE_SENT();
		$totalCount = $matchAlertsToBeSentObj->getTotalCount($todayDate);		
		$matchAlertByLogicTotalObj = new MATCHALERT_TRACKING_MATCH_ALERT_BYLOGIC_TOTAL();
		$matchAlertByLogicTotalObj->insertTotalCountForDate($todayDate,$totalCount);*/

	}

	public function insertCountDataByLogicLevelAndRecommendation($countByLogicAndRecommendations)
	{		
		$sql = "INSERT INTO ...... values";
		foreach($countByLogicAndRecommendations as $key=>$val)
		{
			foreach($val as $k1=>$v1)
			{
				if($k1 == "PeopleCount")
				{
					$insertSql.= "(:PCOUNT".$key.", ";
				}
				if($k1 == "LOGICLEVEL")
				{
					$insertSql.= ":LOGIC".$key.",";
				}
				if($k1 == "RecCount")
				{
					$insertSql.= ":RECOUNT".$key."),";
				}				
			}
		}
		$insertSql = rtrim($insertSql,",");
		$sql.=$insertSql;
		echo($sql);die;
	}
}