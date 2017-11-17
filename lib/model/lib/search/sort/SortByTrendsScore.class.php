<?php

/**
 * This class perform Sort By Trends Score for match alerts
 * @author : Bhavana Kadwal
 * @package Search
 * @subpackage Sort
 * @since 2016-09-19
 */
class SortByTrendsScore extends SearchSort implements SortStrategyInterface {

        private $sortArray = array();
        protected $loggedInProfileObj;
        protected $sortString = '';
        private $trendsForwardRangeCriteria = array("AGE", "HEIGHT", "INCOME");
        private $trendsForwardCriteria = array("MTONGUE", "CASTE", "EDU_LEVEL_NEW" => "EDUCATION", "OCCUPATION", "CITY_RES" => "CITY");
        private $trendsForwardCriteriaMapping = array("EDUCATION"=>"EDU_LEVEL_NEW", "CITY"=>"CITY_RES");

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
                parent::setPaidDateSorting();
                //parent::isJsBoostSorting($loggedInProfileObj);
        }
        /**
         * get trends data if not set
         */
        public function setTrendsData() {
                $mtObj = new TWOWAYMATCH_TRENDS;
                $myrow = $mtObj->getData($this->loggedInProfileObj->getPROFILEID());
                $this->SearchParamtersObj->setTRENDS_DATA(serialize($myrow));
        }
        /**
         * This function create sort string from trends sort array
         */
        public function setSortString() {
                $sortArray = array();
                foreach ($this->sortArray as $field => $sortCon) {
                        if(isset($this->trendsForwardCriteriaMapping[$field])){
                                $field = $this->trendsForwardCriteriaMapping[$field];
                        }
                        $totalC = count($sortCon);
                        $i = 1;
                        if (!empty($sortCon)) {
                                foreach ($sortCon as $val => $weight) {
                                        $sortArray[$field] .= "if(tf(" . $field . "," . $val . ")," . ($weight);
                                        if ($totalC != $i) {
                                                $sortArray[$field] .= ',';
                                        } else {
                                                $sortArray[$field] .= ',0';
                                        }
                                        $i++;
                                }
                                for ($cnt = 0; $cnt < $totalC; $cnt++) {
                                        $sortArray[$field] .= ')';
                                }
                        }
                }
                $sortString = "sum(" . implode(",", $sortArray) . ")";
                $date = date('Y-m-d',strtotime('now'))."T".date('h:i:s',strtotime('now'))."Z";
                $sortString = "product(".$sortString.",div(1,pow(2,div(ceil(div(ms($date,LAST_LOGIN_DT),86400000)),10))))";
                $this->sortString = $sortString;
        }
        /**
         * This function creates a sort array on the basis of trends data
         */
        public function setTrendsSortString() {
                $trendsData = unserialize($this->SearchParamtersObj->getTRENDS_DATA());
                if($this->SearchParamtersObj->getGENDER()){
                        $maritalStatus = FieldMap::getFieldLabel('marital_status', 0, 1);
                        unset($maritalStatus['N']);
                        $mstatusArray = array_keys($maritalStatus);
                        $this->sortArray["MSTATUS"]  = array('N'=>$trendsData["MSTATUS_N_P"]*$trendsData["W_MSTATUS"]);
                        $marriedScore = $trendsData["MSTATUS_M_P"]*$trendsData["W_MSTATUS"];
                        foreach($mstatusArray as $mstatus){
                            $this->sortArray["MSTATUS"][$mstatus] = $marriedScore;
                        }
                }
                
                if($this->SearchParamtersObj->getMANGLIK_IGNORE() || $this->SearchParamtersObj->getMANGLIK()){
                        $this->sortArray["MANGLIK"]  = array('N'=>$trendsData["MANGLIK_N_P"]*$trendsData["W_MANGLIK"],'D'=>$trendsData["MANGLIK_N_P"]*$trendsData["W_MANGLIK"],'M'=>$trendsData["MANGLIK_M_P"]*$trendsData["W_MANGLIK"],'A'=>$trendsData["MANGLIK_M_P"]*$trendsData["W_MANGLIK"]);
                }
                if($this->SearchParamtersObj->getINDIA_NRI() || $this->SearchParamtersObj->getCOUNTRY_RES()){
                                $this->sortArray["INDIA_NRI"]  = array(2=>$trendsData["NRI_M_P"]*$trendsData["W_NRI"],1=>$trendsData["NRI_N_P"]*$trendsData["W_NRI"]);
                }
                foreach ($this->trendsForwardRangeCriteria as $criteria) {
                        eval('$tempLVal = $this->SearchParamtersObj->getL' . $criteria . '();');
                        eval('$tempHVal = $this->SearchParamtersObj->getH' . $criteria . '();');
                        if ($tempLVal || $tempHVal) {
                                $this->sortArray[$criteria] = $this->getSortValue($trendsData[$criteria . "_VALUE_PERCENTILE"], $tempLVal . "," . $tempHVal, $trendsData["W_" . $criteria], 'range');
                        }
                }
                foreach ($this->trendsForwardCriteria as $k => $criteria) {
                        if (!is_numeric($k)) {
                                eval('$tempVal = $this->SearchParamtersObj->get' . $k . '();');
                        } else {
                                eval('$tempVal = $this->SearchParamtersObj->get' . $criteria . '();');
                        }
                        if ($tempVal) {
                                $this->sortArray[$criteria] = $this->getSortValue($trendsData[$criteria . "_VALUE_PERCENTILE"], $tempVal, $trendsData["W_" . $criteria]);
                        }
                }
                $this->setSortString();
        }

        public function getSortValue($value, $selectedVal, $wValue, $type = '') {
                $forward_temp = explode("|", $value);
                foreach ($forward_temp as $tempF) {
                        if ($tempF) {
                                $temparr = explode("#", $tempF);
                                if ($temparr[1] > 5) {//2 is cut-off percentage of individual values.
                                        $forward_temp2[$temparr[0]] = $temparr[1] * $wValue;
                                }
                        }
                }
                if ($type == 'range')
                        return $forward_temp2;

                $searchArray = array();
                $selectedVal = explode(",", str_replace(" ",",",$selectedVal));
                if ($type == 'range')
                        $selectedVal = range($selectedVal[0], $selectedVal[1]);
                if(!empty($forward_temp2)){
                        foreach ($selectedVal as $selected) {
                                if (array_key_exists($selected, $forward_temp2))
                                        $searchArray[$selected] = $forward_temp2[$selected];
                                else
                                        $searchArray[$selected] = 0;
                        }
                        $searchArray = array_intersect($searchArray, $forward_temp2);
                        return $searchArray;
                }else{
                        return $forward_temp2;
                }
        }

        public function getSortString() {
                $counter = 0;
                /*if(parent::getJsBoostSorting()){
                        $sortString[$counter] =  parent::getJsBoostSorting();
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }*/
                
                $sortString[$counter] = parent::getReverseDppSort();
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                
                if ($this->SearchParamtersObj->getTRENDS_DATA() == '') {
                        $this->setTrendsData();
                }
                $this->setTrendsSortString();
                if($this->sortString != ''){
                        $sortString[$counter] = $this->sortString;
                        $sortAscOrDesc[$counter] = $this->sortByDesc;
                        $counter++;
                }
                
                //Paid members sorting
//                $sortString[$counter] = parent::getPaidDateSorting();
//                $sortAscOrDesc[$counter] = $this->sortByDesc;
//                $counter++;
                
                $sortString[$counter] = "SORT_DT";
                $sortAscOrDesc[$counter] = $this->sortByDesc;
                $counter++;
                $this->SearchParamtersObj->setSORTING_CRITERIA($sortString);
                $this->SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC($sortAscOrDesc);
                $this->SearchParamtersObj->setFL_ATTRIBUTE("*,TS:".$this->sortString);
        }
}
