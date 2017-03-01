<?php

/**
 * This class perform Sort By login bucket, reverse dpp and login date for match alerts 
 * @author : Bhavana Kadwal
 * @package Search
 * @subpackage Sort
 * @since 2016-09-19
 */
class SortByLoginWithReverseDpp extends SearchSort implements SortStrategyInterface {
        /**
         * constructor class
         * @access public
         * @param SearchParamters $SearchParamtersObj
         * @param LoggedInProfile $loggedInProfileObj
         */
        public function __construct($SearchParamtersObj, $loggedInProfileObj = '') {
                $this->SearchParamtersObj = $SearchParamtersObj;
                parent::setReverseDppSorting($loggedInProfileObj, 0);
                parent::isJsBoostSorting($loggedInProfileObj);
        }

        /**
         * This funcion will set the solr post parameters required to fetch the desired results.
         * @access public
         */
        public function getSortString() {
                $counter = 0;
                
                if(parent::getJsBoostSorting()){
                        $sortString[$counter] =  parent::getJsBoostSorting();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                
                $sortString[$counter] = "LAST_LOGIN_SCORE";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                $sortString[$counter] = "product(LAST_LOGIN_SCORE,".parent::getReverseDppSort().")";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

                $sortString[$counter] = "LAST_LOGIN_DT";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                $this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
                $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
        }
}
