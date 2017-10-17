<?php

class MatchAlertsRelaxedDppProfiles extends MatchAlertsDppStrategy {

        protected $loggedInProfileObj;
        private $dppSuggestionFields = array("AGE" => array("LAGE", "HAGE"));
        private $dppSuggestionFieldsFunction = array("AGE" => "Age");
        private $dppRelaxationFields = array("HHEIGHT" => "HHEIGHT", "CITY_RES" => "CITY_RES", "CASTE" => "CASTE", "SMOKE" => "SMOKE", "DRINK" => "DRINK", 'EDU_LEVEL_NEW' => "EDUCATION", 'OCCUPATION' => "OCCUPATION", 'CASTE' => "CASTE", 'MTONGUE' => "MTONGUE", "DIET" => "DIET");
        private $specificConditionFields = array("INCOME" => "INCOME");
        private $specificConditionFieldsFunction = array("INCOME" => "setRelaxIncome");
        // Jpartner data
        private $jpartnerData = array();

        public function __construct($loggedInProfileObj,$hasTrends="0") {
                parent::__construct($loggedInProfileObj,$hasTrends);
        }

        /**
         * Add any custom called strict condition here
         * @param type $limit
         * @param type $sort
         */
        public function getRelaxedDppCriteria($limit, $sort) {
                $this->getSearchCriteria($limit, $sort);
                $this->performRelaxation();
        }

        public function performRelaxation() {
                $memObject = JsMemcache::getInstance();
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
                $this->setSuggestionCriteria();
                $this->setRelaxCriteria();
                $this->setSpecificCriteria();
                unset($this->jpartnerData);
                unset($memObject);
        }

        public function setSuggestionCriteria() {
                $suggestionObj = new dppSuggestions();
                foreach ($this->dppSuggestionFields as $key => $value) {
                        if (is_array($value) && array_key_exists($key, $this->dppSuggestionFieldsFunction)) {
                                $valArray = array();
                                $setArray = array();
                                foreach ($value as $val) {
                                        $valArray[] = $this->jpartnerData[0][$val];
                                        $setArray[$val] = $this->jpartnerData[0][$val];
                                }
                                eval('$suggArr =$suggestionObj->getDppSuggestions("",' . $key . ',$valArray,"",$this->loggedInProfileObj);');

                                if (isset($suggArr["data"])) {
                                        foreach ($suggArr["data"] as $k => $data) {
                                                $setArray[$k] = $data;
                                        }
                                }
                                foreach ($setArray as $k => $v) {
                                        eval('$this->set' . $k . '("' . $v . '");');
                                }
                                unset($suggArr);
                                unset($valArray);
                                unset($setArray);
                        }
                }
                unset($suggestionObj);
        }

        public function setSpecificCriteria() {
                foreach ($this->specificConditionFields as $key => $value) {
                        $functionName = $this->specificConditionFieldsFunction[$key];
                        eval('$this->' . $functionName . '();');
                }
        }

        public function setRelaxCriteria() {
                $relaxedObj = new DppRelaxation($this->loggedInProfileObj);
                foreach ($this->dppRelaxationFields as $key => $value) {
                        //$getval = str_replace("'","",$this->jpartnerData[0][$value]);
                        eval('$getval = $this->get' . $key . '();');
                        if($key == "MTONGUE"){
                                $userReligion = $this->pid = $this->loggedInProfileObj->getRELIGION();
                                eval('$relaxVal = $relaxedObj->getRelaxed' . $value . '("' . $getval . '","'.$userReligion.'");');
                        }else{
                                eval('$relaxVal = $relaxedObj->getRelaxed' . $value . '("' . $getval . '");');
                        }
                        if ($key == "OCCUPATION") {
                                $this->setOCCUPATION($relaxVal['occ']);
                                if ($relaxVal['occ'] == '')
                                        $this->setOCCUPATION_GROUPING('');
                                if ($relaxVal['notOcc'] != '')
                                        $this->setOCCUPATION_IGNORE($relaxVal['notOcc']);
                        }elseif ($key == "CITY_RES") {
                                $this->setCITY_RES(str_replace("'", "", $relaxVal), "", 2);
                        } else {
                                if ($key == "CASTE") {
                                        eval('$this->set' . $key . '("' . str_replace("'", "", $relaxVal) . '",1);');
                                } else {
                                        eval('$this->set' . $key . '("' . str_replace("'", "", $relaxVal) . '");');
                                }
                        }
                }
                unset($relaxedObj);
        }

        public function setRelaxIncome() {
                $incomeArray["LINCOME"] = $this->jpartnerData[0]["LINCOME"];
                $incomeArray["HINCOME"] = $this->jpartnerData[0]["HINCOME"];
                $incomeArray["LINCOME_DOL"] = $this->jpartnerData[0]["LINCOME_DOL"];
                $incomeArray["HINCOME_DOL"] = $this->jpartnerData[0]["HINCOME_DOL"];
                if ($incomeArray["LINCOME"] || $incomeArray["LINCOME_DOL"]) {
                        $rArr["minIR"] = "0";
                        $rArr["maxIR"] = "19";
                        $dArr["minID"] = "0";
                        $dArr["maxID"] = "19";
                        if ($incomeArray["LINCOME"]) {
                                $rArr["minIR"] = $incomeArray["LINCOME"];
                        }
                        if ($incomeArray["HINCOME"]) {
                                $rArr["maxIR"] = $incomeArray["HINCOME"];
                        }
                        if ($incomeArray["LINCOME_DOL"]) {
                                $dArr["minID"] = $incomeArray["LINCOME_DOL"];
                        } else {
                                $dArr["maxID"] = $incomeArray["HINCOME_DOL"];
                        }
                        $incomeMapObj = new IncomeMapping($rArr, $dArr);
                        if ($this->loggedInProfileObj->getGENDER() == "M") {
                                $incomeHighValue = $incomeMapObj->getImmediateHigherIncome("hincome", $incomeArray["HINCOME"]);
                                if ($incomeHighValue != "")
                                        $rArr["maxIR"] = $incomeHighValue;
                                $incomeHighValueDol = $incomeMapObj->getImmediateHigherIncome("hincome_dol", $incomeArray["HINCOME_DOL"]);
                                if ($incomeHighValueDol != "")
                                        $dArr["maxID"] = $incomeHighValueDol;
                        }else {
                                $incomeLowerValue = $incomeMapObj->getImmediateLowerIncome("lincome", $incomeArray["LINCOME"]);
                                if ($incomeLowerValue != "")
                                        $rArr["minIR"] = $incomeLowerValue;
                                $incomeLowerValueDol = $incomeMapObj->getImmediateLowerIncome("lincome_dol", $incomeArray["LINCOME_DOL"]);
                                if ($incomeLowerValueDol != "")
                                        $dArr["minID"] = $incomeLowerValueDol;

                                $dArr["maxID"] = "19";
                                $rArr["maxIR"] = "19";
                        }
                        unset($incomeMapObj);
                        $incomeMapObj = new IncomeMapping($rArr, $dArr);
                        $incomeMapArr = $incomeMapObj->incomeMapping();
                        unset($incomeMapObj);
                        $Income = $incomeMapArr['istr'];
                        $this->setINCOME(str_replace("'", "", $Income));
                }
        }

}
?>

