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
	public function __construct($loggedInProfileObj,$limit,$type)
	{
                $this->sort = SearchSortTypesEnums::SortByLoginDate;
                if($type == MailerConfigVariables::$TrendsLoggedinWithTrendsScoreSort){
                        $this->sort = SearchSortTypesEnums::SortByTrendsScore;
                }
		$this->loggedInProfileObj = $loggedInProfileObj;
		$this->limit = $limit;
		$this->searchEngine = 'solr';
		$this->outputFormat = 'array';
	}

	/**
	* This function set Trends Criteria and creates a search Service and Search Service object to fetch result
	* @return type array of user profile Ids
	*/
	public function getMatches()
	{
                $this->TrendsProfileObj = new TrendsPartnerProfiles($this->loggedInProfileObj);
                $this->TrendsProfileObj->getDppCriteria($this->sort, $this->limit);
                
		$SearchServiceObj = new SearchService($this->searchEngine,$this->outputFormat,0);
		$SearchUtilityObj =  new SearchUtility;
                $pids =  $this->getSearchResult($SearchServiceObj,$SearchUtilityObj);
                
                if(is_array($pids))
                	$this->logRecords($this->loggedInProfileObj->getPROFILEID(), $pids, MailerConfigVariables::$strategyReceiversTVsT,$this->limit);
                
                return $pids;
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
		$SearchUtilityObj->removeProfileFromSearch($this->TrendsProfileObj,'spaceSeperator',$this->loggedInProfileObj,'',"",$this->removeMatchAlerts,'',1);
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
