<?php

/**
 * This class will handle matchalerts that need to be send based on dpp of user. 
 */
class RelaxedDppBasedMatchAlertsStrategy extends MatchAlertsStrategy {
        private $listLimit;
        private $mailerLimit;
        private $hasTrends;
        private $logicLevel;
        private $sort;
        private $searchEngine = "solr";
        private $outputFormat = 'array';
        /**
         * 
         * @param type $loggedInProfileObj
         * @param type $listLimit profile limit to be added to list
         * @param type $mailerLimit profile limit to be sent in mailer
         * @param type $hasTrends
         */
        public function __construct($loggedInProfileObj, $listLimit, $mailerLimit, $hasTrends=0) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->listLimit = $listLimit;
                $this->mailerLimit = $mailerLimit;
                $this->hasTrends = $hasTrends;
        }

        /**
         * This function will fetch the matches to be send in matchalerts
         * @return array 
         */
        public function getMatches($matchesSetting='') {
                if($this->hasTrends == "1"){
                        $this->sort = SearchSortTypesEnums::SortByRelaxedTrends;
                        $this->logicLevel = MailerConfigVariables::$logicLevelRelaxedTrends;
                }else{
                        $this->sort = SearchSortTypesEnums::SortByRelaxedNonTrends;
                        $this->logicLevel = MailerConfigVariables::$logicLevelRelaxedNonTrends;
                }
                $this->searchObj = new MatchAlertsRelaxedDppProfiles($this->loggedInProfileObj,$this->hasTrends);
                $this->searchObj->getRelaxedDppCriteria($this->listLimit,$this->sort);
                $SearchServiceObj = new SearchService($this->searchEngine, $this->outputFormat, 0);
                $SearchUtilityObj = new SearchUtility;
                $arr = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj);
                if (is_array($arr)){
                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr, $this->logicLevel, $this->mailerLimit,$this->listLimit,$matchesSetting);
                }
                
                return array("CNT"=>count($arr),"profiles"=>$arr,"LOGIC_LEVEL"=>$this->logicLevel);
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
                $responseObj = $SearchServiceObj->performSearch($this->searchObj, "", array(), "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                return $PIDS;
        }
}

?>
