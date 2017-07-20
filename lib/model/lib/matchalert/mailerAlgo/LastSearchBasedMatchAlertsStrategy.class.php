<?php

/**
 * This class will handle matchalerts that need to be send based on last search of user. 
 */
class LastSearchBasedMatchAlertsStrategy extends MatchAlertsStrategy {

        private $dontShowFilteredProfiles = 'N';
        private $ordered = 1;

        /**
         * the constructor class
         * @param loggedInProfileObj
         * @param $limit : no of matchalerts to be send.
         */
        public function __construct($loggedInProfileObj, $limit, $logicLevel) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->limit = $limit;
                $this->logicLevel = $logicLevel;
                $this->searchEngine = 'solr';
                $this->outputFormat = 'array';
                $this->sort = SearchSortTypesEnums::dateSortFlag;
        }

        /**
         * This function will fetch the matches to be send in matchalerts
         * @return array 
         */
        public function getMatches() {
                $this->searchObj = new lastSearchResults($this->loggedInProfileObj);
                $this->searchObj->getLastSearchResultCriteria($this->limit,$this->sort,$this->ordered);
                $SearchServiceObj = new SearchService($this->searchEngine, $this->outputFormat, 0);
                $SearchUtilityObj = new SearchUtility;
                $arr = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj);
                if (is_array($arr)){
                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr, $this->logicLevel, $this->limit);
                }
                
                return array("CNT"=>count($arr));
        }
        /**
         * 
         * @param type $SearchServiceObj Object of search service class
         * @param type $SearchUtilityObj object of Search Utility Class
         * @return type array, array of user profile Ids 
         */
        private function getSearchResult($SearchServiceObj, $SearchUtilityObj) {
                $SearchServiceObj->setSearchSortLogic($this->searchObj, $this->loggedInProfileObj, "", "");
                $SearchUtilityObj->removeProfileFromSearch($this->searchObj, 'spaceSeperator', $this->loggedInProfileObj, '', 1, $this->removeMatchAlerts,"",'',1);
                $responseObj = $SearchServiceObj->performSearch($this->searchObj, "", "", "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                return $PIDS;
        }
}

?>
