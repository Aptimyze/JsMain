<?php

class TwoWayBasedDppProfiles extends TwoWayMatch {

        /**
         * @const DAY_GAP [No. of days in which we consider for mutual matches]
         */
        const DAY_GAP = 15;

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
        public function getMutualMatchCriteria($sort, $limit) {
                parent::getSearchCriteria();
                
                // Set last login date in search param
                $startDate = date("Y-m-d h:i:s", strtotime("now") - self::DAY_GAP * 24 * 3600);
                $endDate = date("Y-m-d h:i:s", strtotime("now"));
                $this->setLLAST_LOGIN_DT($startDate);
                $this->setHLAST_LOGIN_DT($endDate);
                // get two way match range params and add last login date to it
                $rangeP = $this->getRangeParams();
                $this->setRangeParams($rangeP.",LAST_LOGIN_DT");
                
                $this->setSortParam($sort, $limit);
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

