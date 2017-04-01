<?php

/**
 * This class perform Sort By Login Date
 * @author : Bhavana Kadwal
 * @package Search
 * @subpackage Sort
 * @since 2016-09-28
 */
class SortByLoginDate extends SearchSort implements SortStrategyInterface {
        /**
         * constructor class
         * @access public
         * @param SearchParamters $SearchParamtersObj
         * @param LoggedInProfile $loggedInProfileObj
         */
        public function __construct($SearchParamtersObj, $loggedInProfileObj = '') {
                $this->SearchParamtersObj = $SearchParamtersObj;
                $this->loggedInProfileObj = $loggedInProfileObj;
                //parent::isJsBoostSorting($loggedInProfileObj);
        }

        public function getSortString() {
                $counter = 0;
                
                /*if(parent::getJsBoostSorting()){
                        $sortString[$counter] =  parent::getJsBoostSorting();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }*/
                
                $sortString[$counter] = "LAST_LOGIN_DT";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
    
                $this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
                $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
                $this->SearchParamtersObj->setFL_ATTRIBUTE("*");
        }
}
