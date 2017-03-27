<?php
/*This class is used to handle the matchalerts*/
class MatchAlerts
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}

	/*
	This function is used to get count of match alert
	@param - profileid value, skipped type
	@return - count
	*/
	public function getMatchAlertCount($profileId, $skipProfile='',$days='')
	{
		$matchProfilesArray                              = SearchCommonFunctions::getMatchAlertsMatches('5000','',$profileId);
		$output["TOTAL"] = $matchProfilesArray["CNT"];
		$output["NEW"] = $matchProfilesArray["CNT_NEW"];
		return $output;
	}

	/*
        This function is used to get the matches sent to a profile
        @param - receiver profileid
        @return - array of matches
        */
	public function getProfilesSentInMatchAlerts($profileId)
	{
		$matchAlertObj = new matchalerts_LOG();
		$output = $matchAlertObj->getProfilesSentInMatchAlerts($profileId);
		unset($matchAlertObj);
                return $output;
	}

        public function getMatchAlertProfile($profileId,$skipProfile='',$limit='',$days='',$alertLogic='')
        {
			$matchProfilesArray                              = SearchCommonFunctions::getMatchAlertsMatches('','',$profileId,$alertLogic);
			if($days)
			{	
				if(is_array($matchProfilesArray['PIDS_NEW']))
					$profileids = $limit?array_slice($matchProfilesArray['PIDS_NEW'],0,$limit,true):$matchProfilesArray['PIDS_NEW'];
			}
			else
			{
				if(is_array($matchProfilesArray['PIDS']))
					$profileids = $limit?array_slice($matchProfilesArray['PIDS'],0,$limit,true):$matchProfilesArray['PIDS'];
			}
			
			if(is_array($profileids))
			foreach($profileids as $key=>$value)
			{
				$output[$value]["PROFILEID"] = $value;
				$output[$value]["TIME"] = $matchProfilesArray["TIME"][$value] ;
			}
			return $output;
        }

        public function getProfilesWithOutSorting($profileId,$weekFlag="")
        {
                $matchAlertObj = new MatchAlertsLogCaching();
		if($weekFlag)
			$dateGreaterThanCondition = self::getLogDateFromLogicalDate()-(7*$weekFlag);
                $output = $matchAlertObj->getMatchAlertProfiles($profileId,$dateGreaterThanCondition);
                return $output;
	}
        public static function getLogDateFromLogicalDate($inputDate='')
        {
		if(!$inputDate)
	               $inputDate=mktime(0,0,0,date("m"),date("d"),date("Y"));
                $zero=mktime(0,0,0,01,01,2005);
                $gap=($inputDate-$zero)/(24*60*60);
                return $gap;
        }

        public static function getLogicalDateFromLogDate($inputDate)
        {
		return strtotime("2005-01-01 +$inputDate days");
        }
        
        public function getMatchAlertHeading($profileID,$subHeading){
                $matchAlertTrendsObj = new TWOWAYMATCH_TRENDS();
                $cnt = $matchAlertTrendsObj->getInitialtedAndAcceptedCount($profileID);
                if($cnt>MailerConfigVariables::$trendThreshold)
                {
                        $trendExist=1;
                }
                $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
                $cnt_logic = $newjsMatchLogicObj->getPresentLogic($profileID,MailerConfigVariables::$oldMatchAlertLogic);
                if($cnt_logic>0)
                {
                        unset($cnt);
                        $switchToDpp=1;
                }
                if($trendExist && $switchToDpp){
                        $subHeadingLinkText = $subHeading["dpp"][1];
                        $subHeading = $subHeading["dpp"][0];
                        $subHeadingLogic = "new";
                }
                else if(!$trendExist) {
                        $subHeading = $subHeading["dpp"][0];
                }
                else if($trendExist) {
                        $subHeadingLinkText = $subHeading["trend"][1];
                        $subHeading = $subHeading["trend"][0];
                        $subHeadingLogic = "dpp";
                }
                $subHeadingArr["Heading"]=$subHeading;
                $subHeadingArr["subHeading"]=$subHeadingLinkText;
                $subHeadingArr["Logic"]=$subHeadingLogic;
                
                return $subHeadingArr;
        }
        public function getProfilesCountOfLogicLevel($profileId,$logicLevel)
        {
                $matchAlertObj = new matchalerts_LOG_TEMP($this->dbname);
                $output = $matchAlertObj->getProfilesCountOfLogicLevel($profileId,$logicLevel);
                return $output;
	}

}
?>
