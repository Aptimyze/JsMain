<?php

/**
 * This class perform Sort By Trends Score for match alerts
 * @author : Bhavana Kadwal
 * @package Search
 * @subpackage Sort
 * @since 2017-05-22
 */
class SortByBroaderDppScore extends SortByTrendsScore {

        private $dppSortArray = array();
        private $sortRangeArray = array();
        private $dppSortString = '';
        private $jpartnerData = array();
        protected $loggedInProfileObj;
        private $fieldsForStrickDppSort = array("HEIGHT" => array("LHEIGHT", "HHEIGHT"), "CITY_RES" => "CITY_RES", "CASTE" => "CASTE", "SMOKE" => "SMOKE", "DRINK" => "DRINK", 'EDU_LEVEL_NEW' => "EDUCATION", 'OCCUPATION' => "OCCUPATION", "INCOME" => "INCOME", "AGE" => array("LAGE", "HAGE"));

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
        }

        /**
         * This function create sort string from Dpp condition
         */
        public function setDppSortString() {
                $sortArray = array();
                $totalC = count($this->dppSortArray);
                foreach ($this->dppSortArray as $field => $val) {
                        if (!empty($val)) {
                                $values = explode(",",$val);
                                $totalC = count($values);
                                $i = 1;
                                $sortArray[$field] .= "or(";
                                foreach ($values as $value) {
                                        $sortArray[$field] .= "tf(" . $field . "," . $value . "),";
                                        $i++;
                                }
                                $sortArray[$field] = trim($sortArray[$field],",");
                                $sortArray[$field] .= ")";
                        }
                }
                foreach ($this->sortRangeArray as $field => $val) {
                                $sortArray[$field] .= "and(if(abs(sub(min(".$val['L'.$field].",$field),".$val['L'.$field].")),0,1),if(abs(sub(max(".$val['H'.$field].",$field),".$val['H'.$field].")),0,1))";
                }
                if (!empty($sortArray)) {
                        $brace = '';
                        $strCondition = '';
                        foreach ($sortArray as $arr) {
                                $strCondition .= "if(" . $arr . ",";
                                $brace .= ",0)";
                        }
                        $strCondition .= "1" . $brace;
                }
                $dppSortString = $strCondition;
                $this->dppSortString = $dppSortString;
        }

        /**
         * This function creates a sort array on the basis of trends data
         */
        public function setStrictDppSortString() {
                foreach ($this->fieldsForStrickDppSort as $k => $criteria) {
                        if (is_array($criteria)) {
                                foreach ($criteria as $val) {
                                        $this->sortRangeArray[$k][$val] = $this->jpartnerData[0][$val];
                                }
                        } else {
                                $this->dppSortArray[$k] = str_replace("'","",$this->jpartnerData[0][$k]);
                        }
                }
                $this->setDppSortString();
        }

        public function getSortString() {
                $counter = 0;
                $dppSortString[$counter] = "LAST_LOGIN_SCORE";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                $memObject = JsMemcache::getInstance();
                // Get jpartner data
                $jpartnerData = $memObject->get('SEARCH_JPARTNER_' . $this->loggedInProfileObj->getPROFILEID());
                if (empty($jpartnerData)) {
                        $dbName = JsDbSharding::getShardNo($this->loggedInProfileObj->getPROFILEID());
                        $JPARTNERobj = new newjs_JPARTNER($dbName);
                        $fields = SearchConfig::$dppSearchParamters . ",MAPPED_TO_DPP";
                        $this->jpartnerData = $JPARTNERobj->get(array("PROFILEID" => $this->loggedInProfileObj->getPROFILEID()), $fields);
                        $memObject->set('SEARCH_JPARTNER_' . $this->loggedInProfileObj->getPROFILEID(), serialize($this->jpartnerData), SearchConfig::$matchAlertCacheLifetime);
                } else {
                        $this->jpartnerData = unserialize($jpartnerData);
                }
                unset($memObject);
                //strict dpp sort
                $this->setStrictDppSortString();
                $dppSortString[$counter] = $this->dppSortString;
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                unset($this->jpartnerData);
                //reverse dpp sort
                $dppSortString[$counter] = parent::getReverseDppSort();
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                //Trends Sorting
                if ($this->SearchParamtersObj->getTRENDS_DATA() == '') {
                        $this->setTrendsData();
                }
                $this->setTrendsSortString();
                if($this->sortString != ''){
                        $dppSortString[$counter] = $this->sortString;
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                $this->SearchParamtersObj->setTRENDS_DATA("");
                // Sort by last login dt
                $dppSortString[$counter] = "LAST_LOGIN_DT";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                $this->SearchParamtersObj->setSORTING_CRITERIA($dppSortString);
                $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
                $this->SearchParamtersObj->setFL_ATTRIBUTE("*");
        }

}
