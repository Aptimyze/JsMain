<?php

class MatchAlertsDppProfiles extends PartnerProfile {
        /**
        * @private LAST_LOGGEDIN [No. of days in which we consider for last logged in matches]
        */
	private $LAST_LOGGEDIN = 15; 
	private $LAST_LOGGEDIN_STARTFROM = "1960-01-01 00:00:00"; 
        private $getFromCache = 1;
        /**
         * 
         * @param type $loggedInProfileObj
         */
        public function __construct($loggedInProfileObj) {
                parent::__construct($loggedInProfileObj);
        }

        /**
         * 
         * @return type
         */
        public function getSearchCriteria($limit,$sort) {
                parent::getDppCriteria('','',$this->getFromCache);
                $this->rangeParams .= ",LAST_LOGIN_DT";
                $this->setRangeParams($this->rangeParams);
                $this->setSortParam($sort, $limit);
                if($sort == SearchSortTypesEnums::SortByTrendsScore || $sort == SearchSortTypesEnums::FullDppWithReverseFlag){
                        $endDate = date("Y-m-d H:i:s", strtotime("now"));
                        $startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->LAST_LOGGEDIN*24*3600);
                        $this->setLLAST_LOGIN_DT($startDate);
                        $this->setHLAST_LOGIN_DT($endDate);
                }else{
                        $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->LAST_LOGGEDIN*24*3600);
                        $this->setLLAST_LOGIN_DT($this->LAST_LOGGEDIN_STARTFROM);
                        $this->setHLAST_LOGIN_DT($endDate);
                }
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

