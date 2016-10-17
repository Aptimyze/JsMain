<?php

/**
 * This class will handle matchalerts that need to be send based on dpp of user. 
 */
class DppBasedMatchAlertsStrategy extends MatchAlertsStrategy {

        private $dontShowFilteredProfiles = 'N';

        const TDIVISOR = 90;
        const NTDIVISOR = 45;
        const TMIN = 20;
        const NTMIN = 30;

        /**
         * the constructor class
         * @param loggedInProfileObj
         * @param $limit : no of matchalerts to be send.
         */
        public function __construct($loggedInProfileObj, $limit, $logicLevel) {
                $this->sort = SearchSortTypesEnums::dateSortFlag;
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->limit = $limit;
                $this->listingCount = $limit;
                $this->logicLevel = $logicLevel;
                $this->searchEngine = 'solr';
                $this->outputFormat = 'array';
        }

        /**
         * This function will fetch the matches to be send in matchalerts
         * @return array 
         */
        public function getMatches($returnTotalCount = '',$matchesSetting='') {
                $performMutualMatch = TwoWayBasedDppAlerts::checkForDppProfile($this->loggedInProfileObj->getPROFILEID());

                if (empty($performMutualMatch)) {
                        $arr = $this->performMutualMatch();
                        if ($arr["CNT"] == 0) {
                                $arr = array();
                                TwoWayBasedDppAlerts::insertEntry($this->loggedInProfileObj->getPROFILEID(), 0);
                                $arr = $this->performDPP($this->limit);
                        } else {
                                $this->checkListingCount($arr["CNT"]);
                                TwoWayBasedDppAlerts::insertEntry($this->loggedInProfileObj->getPROFILEID(), $this->listingCount);
                        }
                } else {
                        $this->listingCount = $performMutualMatch['CNT'];
                        $arr = $this->performDPP($this->listingCount);
                }
                if (is_array($arr["PIDS"]))
                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr["PIDS"], $this->logicLevel, $this->limit, $this->listingCount,$matchesSetting);
                if ($returnTotalCount)
                        return $arr['CNT'];
        }

        public function performMutualMatch() {
                $this->searchObj = new TwoWayBasedDppProfiles($this->loggedInProfileObj);
                $this->searchObj->getMutualMatchCriteria($this->sort, 30);
                $SearchServiceObj = new SearchService($this->searchEngine, $this->outputFormat, 0);
                $SearchUtilityObj = new SearchUtility;
                $arr = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj, 1);
                return $arr;
        }

        public function performDPP($limit) {
                $arr = SearchCommonFunctions::getMyDppMatches($this->sort, $this->loggedInProfileObj, $limit, '', '', $this->removeMatchAlerts, $this->dontShowFilteredProfiles);
                return $arr;
        }

        /**
         * 
         * @param type $SearchServiceObj Object of search service class
         * @param type $SearchUtilityObj object of Search Utility Class
         * @return type array, array of user profile Ids 
         */
        private function getSearchResult($SearchServiceObj, $SearchUtilityObj, $returnTotalCount = '') {
                $SearchServiceObj->setSearchSortLogic($this->searchObj, $this->loggedInProfileObj, "", "");
                $SearchUtilityObj->removeProfileFromSearch($this->searchObj, 'spaceSeperator', $this->loggedInProfileObj, '', "", $this->removeMatchAlerts);
                $responseObj = $SearchServiceObj->performSearch($this->searchObj, "", "", "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                if ($returnTotalCount) {
                        $PidsAndCount['PIDS'] = $PIDS;
                        $PidsAndCount['CNT'] = $responseObj->getTotalResults();
                        return $PidsAndCount;
                }
                return $PIDS;
        }

        public function checkListingCount($mutualCount) {
                if ($this->logicLevel == MailerConfigVariables::$strategyReceiversTVsNT) {
                        $Divisor = self::TDIVISOR;
                        $MinOf = self::TMIN;
                } else {
                        $Divisor = self::NTDIVISOR;
                        $MinOf = self::NTMIN;
                }

                $maxVal = $mutualCount / $Divisor;
                if ($maxVal > $MinOf) {
                        $maxVal = $MinOf;
                }
                if ($maxVal > $this->limit) {
                        $this->listingCount = $maxVal;
                }
        }

}

?>
