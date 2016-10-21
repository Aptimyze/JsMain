<?php

/**
 * This class will handle matchalerts that need to be send based on dpp of user. 
 */
class DppBasedMatchAlertsStrategy extends MatchAlertsStrategy {

        private $dontShowFilteredProfiles = 'N';
        public $clusterLoginScore = 0;
        public $logProfile = 0;

        /**
         * the constructor class
         * @param loggedInProfileObj
         * @param $limit : no of matchalerts to be send.
         */
        public function __construct($loggedInProfileObj, $limit, $logicLevel,$type) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->limit = $limit;
                $this->logicLevel = $logicLevel;
                $this->searchEngine = 'solr';
                $this->outputFormat = 'array';
                $this->clusterToShow = array();
                if($this->logicLevel == MailerConfigVariables::$strategyReceiversTVsNT){
                        $this->sort = SearchSortTypesEnums::SortByLoginDate;
                        if($type == MailerConfigVariables::$DppLoggedinWithReverseDppSort){
                                $this->clusterToShow = array("LAST_LOGIN_SCORE"); // add cluster to check new matchalert condition
                                $this->sort = SearchSortTypesEnums::FullDppWithReverseFlag;
                                $this->logProfile = 1;
                        }elseif($type == MailerConfigVariables::$DppLoggedinWithTrendsScoreSort){
                                $this->sort = SearchSortTypesEnums::SortByTrendsScore;
                                $this->logicLevel = MailerConfigVariables::$strategyReceiversTVsT;
                        }
                }else{
                        $this->sort = SearchSortTypesEnums::FullDppWithReverseFlag;
                        $this->logProfile = 1;
                }
        }

        /**
         * This function will fetch the matches to be send in matchalerts
         * @return array 
         */
        public function getMatches($returnTotalCount = '',$returnTotalCountWithCluster = 0,$notInProfiles = array(),$matchesSetting='') {
                $this->searchObj = new MatchAlertsDppProfiles($this->loggedInProfileObj);
                $this->searchObj->getSearchCriteria($this->limit,$this->sort);
                $SearchServiceObj = new SearchService($this->searchEngine, $this->outputFormat, 0);
                $SearchUtilityObj = new SearchUtility;
                $arr = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj, 1,$this->clusterToShow,implode(' ',$notInProfiles));
                if (is_array($arr["PIDS"])){
                        if($this->logProfile == 0){
                                if(count($arr["PIDS"]) >= $this->limit){
                                        if(!empty($notInProfiles)){
                                                $arr["PIDS"] = array_merge($arr["PIDS"], $notInProfiles);
                                        }
                                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr["PIDS"], $this->logicLevel, $this->limit,0,$matchesSetting);
                                }else{
                                        return array("CNT"=>$arr['CNT'],"profiles"=>$arr["PIDS"]);    
                                }
                        }else{
                                if(!empty($notInProfiles)){
                                        $arr["PIDS"] = array_merge($arr["PIDS"], $notInProfiles);
                                }
                                $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $arr["PIDS"], $this->logicLevel, $this->limit,0,$matchesSetting);
                        }
                }

                if($returnTotalCountWithCluster == 1){
                        return array("CNT"=>count($arr["PIDS"]),"LOGIN_SCORE"=>$this->clusterLoginScore,"profiles"=>$arr["PIDS"]);
                }
                
                return array("CNT"=>count($arr["PIDS"]),"profiles"=>$arr["PIDS"]);
        }
        /**
         * 
         * @param type $SearchServiceObj Object of search service class
         * @param type $SearchUtilityObj object of Search Utility Class
         * @return type array, array of user profile Ids 
         */
        private function getSearchResult($SearchServiceObj, $SearchUtilityObj, $returnTotalCount = '',$clustersToShow = array(),$notInProfiles = '') {
                $SearchServiceObj->setSearchSortLogic($this->searchObj, $this->loggedInProfileObj, "", "");
                $SearchUtilityObj->removeProfileFromSearch($this->searchObj, 'spaceSeperator', $this->loggedInProfileObj, '', "", $this->removeMatchAlerts,$notInProfiles,'',1);
                $responseObj = $SearchServiceObj->performSearch($this->searchObj, "", $clustersToShow, "", '', $this->loggedInProfileObj);
                $PIDS = $responseObj->getsearchResultsPidArr();
                if(!empty($clustersToShow)){
                        $lastLoginCluster = $responseObj->getClustersResults();
                        $this->clusterLoginScore = isset($lastLoginCluster['LAST_LOGIN_SCORE'][100])?$lastLoginCluster['LAST_LOGIN_SCORE'][100]:0;
                }
                if ($returnTotalCount) {
                        $PidsAndCount['PIDS'] = $PIDS;
                        $PidsAndCount['CNT'] = $responseObj->getTotalResults();
                        return $PidsAndCount;
                }
                return $PIDS;
        }
}

?>
