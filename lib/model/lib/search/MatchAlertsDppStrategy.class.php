<?php

class MatchAlertsDppStrategy extends PartnerProfile {
        
	private $VERIFIED_CHECK = 2; 
	private $LAST_LOGGEDIN_STARTFROM = "1960-01-01 00:00:00"; 
        private $getFromCache = 1;
        private $hasTrends = 0;
        /**
         * 
         * @param type $loggedInProfileObj
         */
        public function __construct($loggedInProfileObj,$hasTrends) {
                parent::__construct($loggedInProfileObj,$hasTrends);
                $this->hasTrends = $hasTrends;
        }
        /**
         * This is a common code to be called for strict as well as relaxed dpp matchalerts
         * @param type $limit
         * @param type $sort
         */
        public function getSearchCriteria($limit,$sort) {
                $this->setHAS_TRENDS($this->hasTrends);
                parent::getDppCriteria('','',$this->getFromCache);
                $this->setSortParam($sort, $limit);             
                $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->VERIFIED_CHECK*24*3600);
                $this->setLVERIFY_ACTIVATED_DT($this->LAST_LOGGEDIN_STARTFROM);
                $this->setHVERIFY_ACTIVATED_DT($endDate);
                $this->setShowFilteredProfiles('N');
        }
        /**
         * Function to set sort order and results count
         * @param type $sort
         * @param type $limit
         */
        public function setSortParam($sort, $limit) {
                $this->setSORT_LOGIC($sort);
                $this->setNoOfResults($limit);
        }
}
?>

