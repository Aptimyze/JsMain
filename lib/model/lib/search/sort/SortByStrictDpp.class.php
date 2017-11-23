<?php

/**
 * This class perform Sort By Trends Score/non trendsSort for scrictDpp for match alerts
 * @author : Bhavana Kadwal
 * @package Search
 * @subpackage Sort
 * @since 2017-08-29
 */
class SortByStrictDpp extends SortByTrendsScore {
        protected $loggedInProfileObj;

        /**
         * constructor class
         * @access public
         * @param SearchParamters $SearchParamtersObj
         * @param LoggedInProfile $loggedInProfileObj
         */
        public function __construct($SearchParamtersObj, $loggedInProfileObj = '') {
                $this->SearchParamtersObj = $SearchParamtersObj;
                $this->loggedInProfileObj = $loggedInProfileObj;
                parent::setReverseDppSorting($loggedInProfileObj, 0);
                parent::setPaidDateSorting($loggedInProfileObj, 0);
        }

        public function getSortString() {
                $counter = 0;

                $dppSortString[$counter] = "LAST_LOGIN_SCORE";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

                //reverse dpp sort
                $dppSortString[$counter] = parent::getReverseDppSort();
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

                //Paid members sorting
//                $dppSortString[$counter] = parent::getPaidDateSorting();
//                $sortAscOrDesc[$counter] = $this->sortByDesc;
//                $counter++;

                //Trends Sorting

                if ($this->SearchParamtersObj->getHAS_TRENDS()) {
                        if ($this->SearchParamtersObj->getTRENDS_DATA() == '') {
                                $this->setTrendsData();
                        }
                        $this->setTrendsSortString();
                        if ($this->sortString != '') {
                                $dppSortString[$counter] = $this->sortString;
                                $sortAscOrDesc[$counter] = $this->sortByDesc;
                                $counter++;
                        }
                        $this->SearchParamtersObj->setTRENDS_DATA("");
                }

                // Sort by last login dt
                //$dppSortString[$counter] = "SORT_DT";
                $dppSortString[$counter] = "LAST_LOGIN_DT";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;

                $this->SearchParamtersObj->setSORTING_CRITERIA($dppSortString);
                $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
                $this->SearchParamtersObj->setFL_ATTRIBUTE("*");
        }

}
