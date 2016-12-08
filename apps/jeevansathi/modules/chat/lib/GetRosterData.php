<?php

/**
 * Created by PhpStorm.
 * User: Pankaj1
 * Date: 04/07/16
 * Time: 4:23 PM
 */
class GetRosterData
{
	private $profileid;
	private $skipProfiles;
	CONST LOGIN_MONTHS_GAP = 5;

	public function __construct($profileid)
	{
		$this->profileid = $profileid;
	}

	public function getRosterDataByType($type,$limit="")
	{
		$infoTypeAdapter = new InformationTypeAdapter($type, $this->profileid);
		$profileObj = new Profile("",$this->profileid);
		$profileObj->getDetail();
		$gender = $profileObj->getGENDER();

		if($gender == "F")
		{
			$otherGender='M';
		}
		else
		{
			$otherGender='F';
		}

		$skipArray = $this->getSkipProfiles($type);

		$newLimit = $limit+$limit;//ForOptimization
		$conditions = $this->getConditions($type);
		$profilelists = $infoTypeAdapter->getProfiles($conditions,$skipArray);
		if(is_array($profilelists))
		{
			foreach($profilelists as $key=>$value)
			{
				$profile[] = $key;
			}
			$whereArr["PROFILEID"] = implode(",",$profile);
			$whereArr["GENDER"] = $otherGender;
			$whereArr["ACTIVATED"] = 'Y';

			/** 
			*code added to condiser profile who are logged in in LOGIN_MONTHS_GAP time
			*/
			$monthGap = mktime(0, 0, 0, date("m")- self::LOGIN_MONTHS_GAP, date("d"),   date("Y"));
			$dateAfterMonthGap = CommonUtility::makeTime(date("Y-m-d",$monthGap));
			$greaterThanEqualArrayWithoutQuote["LAST_LOGIN_DT"] = "'".$dateAfterMonthGap."'";
			$orderBy = "LAST_LOGIN_DT DESC";
			
			//$orderBy = "FIELD(PROFILEID,$whereArr[PROFILEID])";

			$profArrObj                = new ProfileArray();
			$usernameArray = $profArrObj->getResultsBasedOnJprofileFields($whereArr, '', '', implode(',',Array("PROFILEID", "USERNAME")),'JPROFILE',"newjs_bmsSlave","",$greaterThanEqualArrayWithoutQuote,$orderBy,$limit);
			foreach($usernameArray as $key=>$value)
			{
				$profilelist[$value->getPROFILEID()] = $profilelists[$value->getPROFILEID()];
				$profilelist[$value->getPROFILEID()]["USERNAME"] = $value->getUSERNAME();
				$profilelist[$value->getPROFILEID()]["PROFILECHECKSUM"] = md5($value->getPROFILEID())."i".$value->getPROFILEID();
			}
		}
		return $profilelist;
	}

	public function getSkipProfiles($infoType)
	{

		$memcacheServiceObj = new ProfileMemcacheService($this->profileid);
		$memcacheServiceObj->setSKIP_PROFILES();
		if(count($this->skipProfiles)==0)
			switch ($infoType) {

				case 'SHORTLIST':
					$skipConditionArray = SkipArrayCondition::$SkippedAll;
					$skipProfileObj     = SkipProfile::getInstance($this->profileid);
					$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);
					break;
				default:
					$skipConditionArray = SkipArrayCondition::$default;
					$skipProfileObj     = SkipProfile::getInstance($this->profileid);
					$this->skipProfiles       = $skipProfileObj->getSkipProfiles($skipConditionArray);

					break;
			}
		return $this->skipProfiles;
	}

	public function getConditions($type,$limit)
	{
		if ($type == "INTEREST_RECEIVED") {
			$condition["WHERE"]["NOT_IN"]["FILTERED"]         = "Y";
			$yday                                             = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y"));
			$back_90_days                                     = date("Y-m-d", $yday);
			$condition["WHERE"]["GREATER_THAN_EQUAL"]["TIME"] = "$back_90_days 00:00:00";
		}
		if ($limit) $condition["LIMIT"] = "$limit";
		$condition["ORDER"] = "TIME";
		return $condition;
	}

}
