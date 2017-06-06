<?php

class TrendsPartnerProfiles extends PartnerProfile {

        /**
         * @private LAST_LOGGEDIN [No. of days in which we consider for last logged in matches]
         */
        private $LAST_LOGGEDIN = 15;
        protected $loggedInProfileObj;
        private $jpartnerStrictFields = array("MSTATUS"=>'MSTATUS',"MANGLIK"=>'MANGLIK',"COUNTRY_RES"=>'COUNTRY_RES',"RELIGION"=>'RELIGION',"DIET"=>'DIET',"HANDICAPPED"=>"HANDICAPPED");
        private $jpartnerDepFields = array("MSTATUS"=>"HAVECHILD");
        private $dppSuggestionFields = array("AGE"=>array("LAGE","HAGE"));
        private $dppSuggestionFieldsFunction = array("AGE"=>"Age");
        private $dppRelaxationFields = array("HHEIGHT"=>"HHEIGHT","CITY_RES"=>"CITY_RES","CASTE"=>"CASTE","SMOKE"=>"SMOKE","DRINK"=>"DRINK",'EDU_LEVEL_NEW'=>"EDUCATION", 'OCCUPATION'=>"OCCUPATION", 'CASTE'=>"CASTE", 'MTONGUE'=>"MTONGUE");
        //private $dppRelaxationFields = array("CASTE"=>"CASTE");
        private $specificConditionFields = array("INCOME"=>"INCOME");
        private $specificConditionFieldsFunction = array("INCOME"=>"setRelaxIncome");
        // Jpartner data
        private $jpartnerData = array();
        private $VERIFIED_CHECK = 2;
        private $LAST_LOGGEDIN_STARTFROM = "1960-01-01 00:00:00";

        /**
         * 
         * @param type $loggedInProfileObj
         */
        public function __construct($loggedInProfileObj) {
                parent::__construct($loggedInProfileObj);
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->table = "twowaymatch.TRENDS";
        }

        /**
         * 
         * @param type $sort sort logic
         * @param type $limit number of records
         */
        public function setSortParam($sort, $limit) {
                $this->setSORT_LOGIC($sort);
                $this->setNoOfResults($limit);
                $this->rangeParams .= ",LAST_LOGIN_DT";
                $this->setRangeParams($this->rangeParams);
                // Set login Date condition
                if ($sort == SearchSortTypesEnums::SortByTrendsScore) {
                        $endDate = date("Y-m-d H:i:s", strtotime("now"));
                        $startDate = date("Y-m-d 00:00:00", strtotime($endDate) - $this->LAST_LOGGEDIN * 24 * 3600);
                        $this->setLLAST_LOGIN_DT($startDate);
                        $this->setHLAST_LOGIN_DT($endDate);
                } else {
                        $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->LAST_LOGGEDIN * 24 * 3600);
                        $this->setLLAST_LOGIN_DT("1960-01-01 00:00:00");
                        $this->setHLAST_LOGIN_DT($endDate);
                }

                //just joined 2 day check
                $endDate = date("Y-m-d H:i:s", strtotime("now") - $this->VERIFIED_CHECK * 24 * 3600);
                $this->setLVERIFY_ACTIVATED_DT($this->LAST_LOGGEDIN_STARTFROM);
                $this->setHVERIFY_ACTIVATED_DT($endDate);
        }

        /**
         * 
         * @return type
         */
        public function getTrendsCriteria($sort, $limit) {
                parent::getDppCriteria('','',$this->getFromCache);
                $this->setSortParam($sort, $limit);
                $memObject = JsMemcache::getInstance();
                $jpartnerData = $memObject->get('SEARCH_JPARTNER_' . $this->loggedInProfileObj->getPROFILEID());
                $jpartnerData = '';
                if (empty($jpartnerData)) {
                        $dbName = JsDbSharding::getShardNo($this->loggedInProfileObj->getPROFILEID());
                        $JPARTNERobj = new newjs_JPARTNER($dbName);
                        $fields = SearchConfig::$dppSearchParamters . ",MAPPED_TO_DPP";
                        $this->jpartnerData = $JPARTNERobj->get(array("PROFILEID" => $this->loggedInProfileObj->getPROFILEID()), $fields);
                        $memObject->set('SEARCH_JPARTNER_' . $this->loggedInProfileObj->getPROFILEID(), serialize($this->jpartnerData), SearchConfig::$matchAlertCacheLifetime);
                } else {
                        $this->jpartnerData = unserialize($jpartnerData);
                }
                
                //$this->setStrictCriteria();
                $this->setSuggestionCriteria();
                $this->setRelaxCriteria();
                $this->setSpecificCriteria();
                $this->showFilteredProfiles = 'N';
                unset($this->jpartnerData);
                unset($memObject);
        }
        public function setStrictCriteria(){
                foreach($this->jpartnerStrictFields as $key=>$value){
                        $tempVal = $this->jpartnerData[0][$key];
                        if($tempVal)
                                eval('$this->set'.$value.'('.$tempVal.');');
                        if(array_key_exists($key, $this->jpartnerDepFields)){
                                $tempVal = $this->jpartnerData[0][$this->jpartnerDepFields[$key]];
                                $value = $this->jpartnerDepFields[$key];
                                if($tempVal)
                                        eval('$this->set'.$value.'('.$tempVal.');');
                                
                        }
                }
        }
        public function setSuggestionCriteria(){
                $suggestionObj = new dppSuggestions();
                foreach($this->dppSuggestionFields as $key=>$value){
                        if(is_array($value) && array_key_exists($key,$this->dppSuggestionFieldsFunction)){
                                $valArray = array();
                                $setArray = array();
                                foreach($value as $val){
                                        $valArray[] = $this->jpartnerData[0][$val];
                                        $setArray[$val] = $this->jpartnerData[0][$val];
                                }
                                eval('$suggArr =$suggestionObj->getDppSuggestions("",'.$key.',$valArray,"",$this->loggedInProfileObj);');
                                print_r($suggArr);die;
                                if(isset($suggArr["data"])){
                                        foreach($suggArr["data"] as $k=>$data){
                                                $setArray[$k] = $data;
                                        }
                                }
                                foreach($setArray as $k=>$v){
                                        eval('$this->set'.$k.'("'.$v.'");');
                                }
                                unset($suggArr);unset($valArray);unset($setArray);
                        }
                }
                unset($suggestionObj);
        }
        public function setSpecificCriteria(){
                foreach($this->specificConditionFields as $key=>$value){
                        $functionName = $this->specificConditionFieldsFunction[$key];
                        eval('$this->'.$functionName.'();');
                }
        }
        public function setRelaxCriteria(){
                $relaxedObj = new DppRelaxation($this->loggedInProfileObj);
                foreach($this->dppRelaxationFields as $key=>$value){
                        //$getval = str_replace("'","",$this->jpartnerData[0][$value]);
                        eval('$getval = $this->get'.$key.'();');
                        eval('$relaxVal = $relaxedObj->getRelaxed'.$value.'("'.$getval.'");');
                        if($key == "OCCUPATION"){
                                $this->setOCCUPATION($relaxVal['occ']);
                                if($relaxVal['occ']=='')
                                    $this->setOCCUPATION_GROUPING('');
                                if($relaxVal['notOcc']!='')
                                    $this->setOCCUPATION_IGNORE($relaxVal['notOcc']);
                        }elseif($key == "CITY_RES"){
                                $this->setCITY_RES(str_replace("'","",$relaxVal));
                                $this->setCITY_INDIA(str_replace("'","",$relaxVal));
                        }else{
                                if($key == "CASTE"){
                                        eval('$this->set'.$key.'("'.str_replace("'","",$relaxVal).'",1);');
                                }else{
                                        eval('$this->set'.$key.'("'.str_replace("'","",$relaxVal).'");');
                                }
                        }
                }
                unset($relaxedObj);
        }
        public function setRelaxIncome(){
                $incomeArray["LINCOME"] = $this->jpartnerData[0]["LINCOME"];
                $incomeArray["HINCOME"] = $this->jpartnerData[0]["HINCOME"];
                $incomeArray["LINCOME_DOL"] = $this->jpartnerData[0]["LINCOME_DOL"];
                $incomeArray["HINCOME_DOL"] = $this->jpartnerData[0]["HINCOME_DOL"];
                if($incomeArray["LINCOME"] || $incomeArray["LINCOME_DOL"]){
                        $rArr["minIR"] = "0" ;
                        $rArr["maxIR"] = "19" ;
                        $dArr["minID"] = "0" ;
                        $dArr["maxID"] = "19" ;
                        if($incomeArray["LINCOME"]){
                                $rArr["minIR"] = $incomeArray["LINCOME"];
                        }   
                        if($incomeArray["HINCOME"]){
                                $rArr["maxIR"] = $incomeArray["HINCOME"];
                        }   
                        if($incomeArray["LINCOME_DOL"]){
                                $dArr["minID"] = $incomeArray["LINCOME_DOL"];
                        }
                        else{
                                $dArr["maxID"] = $incomeArray["HINCOME_DOL"];
                        }
                        $incomeMapObj = new IncomeMapping($rArr,$dArr);
                        if($this->loggedInProfileObj->getGENDER() == "M"){
                                $incomeHighValue = $incomeMapObj->getImmediateHigherIncome("hincome",$incomeArray["HINCOME"]);
                                if($incomeHighValue != "")
                                        $rArr["maxIR"] = $incomeHighValue;
                                $incomeHighValueDol = $incomeMapObj->getImmediateHigherIncome("hincome_dol",$incomeArray["HINCOME_DOL"]);
                                if($incomeHighValueDol != "")
                                        $dArr["maxID"] = $incomeHighValueDol;
                        }else{
                                $incomeLowerValue = $incomeMapObj->getImmediateLowerIncome("lincome",$incomeArray["LINCOME"]);
                                if($incomeLowerValue != "")
                                        $rArr["minIR"] = $incomeLowerValue;
                                $incomeLowerValueDol = $incomeMapObj->getImmediateLowerIncome("lincome_dol",$incomeArray["LINCOME_DOL"]);
                                if($incomeLowerValueDol != "")
                                        $dArr["minID"] = $incomeLowerValueDol;
                                
                                $dArr["maxID"] = "19" ;
                                $rArr["maxIR"] = "19" ;
                        }
                        unset($incomeMapObj);
                        $incomeMapObj = new IncomeMapping($rArr,$dArr);
                        $incomeMapArr = $incomeMapObj->incomeMapping();
                        unset($incomeMapObj);
                        $Income = $incomeMapArr['istr'];
                        $this->setINCOME(str_replace("'", "",$Income));
                }
        }
}

?>
