<?php

/**
 *
 */
class NewMatchesMailerStrategy extends NewMatchesStrategy {
        
        protected $logicUsed;
        protected $hasTrends;
        /**
         * 
         * @param type $loggedInProfileObj
         * @param type $limit
         * @param type $dppSwitch
         * @param type $hasTrends
         */
        public function __construct($loggedInProfileObj, $limit,$dppSwitch,$hasTrends) {
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->limit = $limit;
                $this->dppSwitch = $dppSwitch;
                $this->hasTrends = $hasTrends;
                $this->searchEngine = 'solr';
                $this->outputFormat = 'array';
                // If user dont have trends or switched to DPP then call dpp logic
                if($this->hasTrends == 1){
                   $this->logicUsed = MailerConfigVariables::$strategyTvsNewLogic;     
                }else{   
                   $this->logicUsed = MailerConfigVariables::$strategyNTvsNewLogic; 
                }
        }
        /**
         * This function checks for same gender Error and DPP exists error
         * @return boolean
         */
        public function getSameGenderAndDppExistsError(){
                $this->setSameGenderAndDppExistsError($this->hasTrends,$this->loggedInProfileObj);
                if($this->sameGenderError == 'Y' || $this->isPartnerProfileExist == 'N'){
                        return true;
                }
                return false;
        }
        /**
         * This function gets the search criteria for trends and dpp based new matches
         * @return type array of user profile Ids
         */
        public function getMatches($dailyCron=0) {
                              
                $SearchServiceObj = new SearchService;
		$SearchUtilityObj =  new SearchUtility;
                
		$paramArr["logic_used"] = $this->logicUsed;
                
		$searchObject = new NewMatchesMailer($this->loggedInProfileObj);
		$searchObject->getSearchCriteria($paramArr);
                $searchObject->setNoOfResults($this->limit);
                
                $this->canPerformRelaxation($this->dppSwitch);
                
                $pids = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj, $searchObject, 1);
                $this->setLogicLevelArray($pids);
                $totalRecords = $pids['CNT'];
               
                /*
                $this->educationRelaxation($searchObject);
                $pids = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj, $searchObject, 1, 1);
                $this->setLogicLevelArray($pids);
                
                $this->ageHeightCasteRelaxation($searchObject);
                $pids = $this->getSearchResult($SearchServiceObj, $SearchUtilityObj, $searchObject, 1, 1);
                $this->setLogicLevelArray($pids);
                */
                if (count($this->profileSet)){
                        $this->logRecords($this->loggedInProfileObj->getPROFILEID(), $this->profileSet, $this->logicUsed, $this->limit,$totalRecords,$dailyCron);
                }else{
                        $this->insertZeroRecordEntries($this->loggedInProfileObj->getPROFILEID());
                }
        }

}

?>
