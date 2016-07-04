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

	public function __construct($profileid)
	{
		$this->profileid = $profileid;
	}

	public function getRosterDataByType($type,$limit="")
	{
		$infoTypeAdapter = new InformationTypeAdapter($type, $this->profileid);
		$skipArray = $this->getSkipProfiles($type);
		$conditions = $this->getConditions($type,$limit);
		$profilelists = $infoTypeAdapter->getProfiles($conditions,$skipArray);
		if(is_array($profilelists))
		{
			$profArrObj                = new ProfileArray();
			foreach($profilelists as $key=>$value)
			{
				$profiles[] = $key;
			}
			$profileIdArr["PROFILEID"] = implode(",",$profiles);
			$usernameArray = $profArrObj->getResultsBasedOnJprofileFields($profileIdArr, '', '', implode(',',Array("PROFILEID", "USERNAME")),'JPROFILE',"newjs_bmsSlave");
			foreach($usernameArray as $key=>$value)
			{
				$profilelists[$value->getPROFILEID()]["USERNAME"] = $value->getUSERNAME();
			}
		}
		return $profilelists;
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
		return $condition;
	}

}
