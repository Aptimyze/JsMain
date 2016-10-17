<?php
/**
*
*/
class TrendsBasedMatchAlertsStrategy extends MatchAlertsStrategy
{
	/**
	* 
	* @param type $loggedInProfileObj logged in user object
	* @param type $limit number of records to be fetched
	*/
	public function __construct($loggedInProfileObj,$limit)
	{
		$this->sort = SearchSortTypesEnums::dateSortFlag;
		$this->loggedInProfileObj = $loggedInProfileObj;
		$this->limit = $limit;
		$this->searchEngine = 'solr';
		$this->outputFormat = 'array';
	}

	/**
	* This function set Trends Criteria and creates a search Service and Search Service object to fetch result
	* @return type array of user profile Ids
	*/
	public function getMatches($intersection = '',$matchesSetting='')
	{
                if($intersection){
                    $this->TrendsProfileObj = new TrendsIntersectionDppProfiles($this->loggedInProfileObj);
                    $toSendFromIntersection = $this->TrendsProfileObj->getDppTrendsCriteria($this->sort, $this->limit);
                    if(!$toSendFromIntersection)
                        return false;
                }
                else{
                    $this->TrendsProfileObj = new TrendsPartnerProfiles($this->loggedInProfileObj);
                    $this->TrendsProfileObj->getDppCriteria($this->sort, $this->limit);
                }
		$SearchServiceObj = new SearchService($this->searchEngine,$this->outputFormat,0);
		$SearchUtilityObj =  new SearchUtility;
                if($intersection){
                    $pidsAndCount =  $this->getSearchResult($SearchServiceObj,$SearchUtilityObj,$intersection);
                    $pids = $pidsAndCount['PIDS'];
                    $totalCount = $pidsAndCount['CNT'];
                }
                else
                    $pids =  $this->getSearchResult($SearchServiceObj,$SearchUtilityObj);
                if(is_array($pids))
                	$this->logRecords($this->loggedInProfileObj->getPROFILEID(), $pids, MailerConfigVariables::$strategyReceiversTVsT,$this->limit,'',$matchesSetting);
                if($intersection)
                    return $totalCount;
	}

	/**
	* 
	* @param type $SearchServiceObj Object of search service class
	* @param type $SearchUtilityObj object of Search Utility Class
	* @return type array, array of user profile Ids 
	*/
	private function getSearchResult($SearchServiceObj,$SearchUtilityObj,$returnTotalCount='')
	{
		$SearchServiceObj->setSearchSortLogic($this->TrendsProfileObj,$this->loggedInProfileObj,"","");
		$SearchUtilityObj->removeProfileFromSearch($this->TrendsProfileObj,'spaceSeperator',$this->loggedInProfileObj,'',"",$this->removeMatchAlerts);
		$responseObj = $SearchServiceObj->performSearch($this->TrendsProfileObj,"","","",'',$this->loggedInProfileObj);
		$PIDS = $responseObj->getsearchResultsPidArr();
                if($returnTotalCount){
                    $PidsAndCount['PIDS'] = $PIDS;
                    $PidsAndCount['CNT'] = $responseObj->getTotalResults();
                    return $PidsAndCount;
                }
		return $PIDS;
	}
}
?>
