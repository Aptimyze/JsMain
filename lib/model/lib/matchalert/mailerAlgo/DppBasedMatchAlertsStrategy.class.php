<?php
/**
* This class will handle matchalerts that need to be send based on dpp of user. 
*/
class DppBasedMatchAlertsStrategy extends MatchAlertsStrategy
{
	private $dontShowFilteredProfiles = 'N';
	/**
	* the constructor class
	* @param loggedInProfileObj
        * @param $limit : no of matchalerts to be send.
	*/
	public function __construct($loggedInProfileObj,$limit,$logicLevel)
	{
		$this->sort = SearchSortTypesEnums::dateSortFlag;
		$this->loggedInProfileObj = $loggedInProfileObj;
		$this->limit = $limit;
                $this->logicLevel = $logicLevel;
	}


	/**
	* This function will fetch the matches to be send in matchalerts
	* @return array 
	*/
	public function getMatches()
	{
		$arr = SearchCommonFunctions::getMyDppMatches($this->sort,$this->loggedInProfileObj,$this->limit,'','',$this->removeMatchAlerts,$this->dontShowFilteredProfiles);
                if(is_array($arr["PIDS"]))
                	$this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr["PIDS"], $this->logicLevel,$this->limit);
	}
}
?>
