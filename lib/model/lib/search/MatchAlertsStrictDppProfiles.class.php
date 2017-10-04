<?php

class MatchAlertsStrictDppProfiles extends MatchAlertsDppStrategy {
        /**
         * 
         * @param type $loggedInProfileObj
         */
        public function __construct($loggedInProfileObj,$hasTrends="0") {
                parent::__construct($loggedInProfileObj,$hasTrends);
        }
        /**
         * Add any custom called strict condition here
         * @param type $limit
         * @param type $sort
         */
        public function getStrictDppCriteria($limit,$sort){
                parent::getSearchCriteria($limit, $sort);
        }
}
?>

