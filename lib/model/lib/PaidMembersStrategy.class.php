<?php
class PaidMembersStrategy {
        private $loggedInProfileObj ;
        private $limit ;
        private $searchEngine = 'solr';
        private $outputFormat = 'array';

        /**
         * the constructor class
         * @param loggedInProfileObj
         * @param $limit : no of matchalerts to be send.
         */
        public function __construct($loggedInProfileObj, $limit) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->limit = $limit;
        }

        /**
         * This function will fetch the matches to be send in matchalerts
         * @return array 
         */
        public function getMatches() {
                $this->searchObj = new PaidMembersSearch($this->loggedInProfileObj);
                $this->searchObj->getSearchCriteria($this->limit);
                $SearchServiceObj = new SearchService($this->searchEngine, $this->outputFormat, 0);
                $SearchUtilityObj = new SearchUtility;
                $arr = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj);
                
                if (is_array($arr["PIDS"])){
                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr["PIDS"]);
                }
                
                return array("CNT"=>count($arr["PIDS"]));
        }
        /**
         * 
         * @param type $SearchServiceObj Object of search service class
         * @param type $SearchUtilityObj object of Search Utility Class
         * @return type array, array of user profile Ids 
         */
        private function getSearchResult($SearchServiceObj, $SearchUtilityObj) {
                $SearchServiceObj->setSearchSortLogic($this->searchObj, $this->loggedInProfileObj, "", "");
                $SearchUtilityObj->removeProfileFromSearch($this->searchObj, 'spaceSeperator', $this->loggedInProfileObj, '', 1, "","",'',1);
                $responseObj = $SearchServiceObj->performSearch($this->searchObj, "", "", "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                $PidsAndCount['PIDS'] = $PIDS;
                $PidsAndCount['CNT'] = $responseObj->getTotalResults();
                return $PidsAndCount;
        }
        
        private function logRecords($receiverId,$profileIds){
                $search_PAID_MEMBERS_MAILER = new search_PAID_MEMBERS_MAILER();
                $search_PAID_MEMBERS_MAILER->insertLogRecords($receiverId, $profileIds);
                unset($search_PAID_MEMBERS_MAILER);
        }
}

?>
