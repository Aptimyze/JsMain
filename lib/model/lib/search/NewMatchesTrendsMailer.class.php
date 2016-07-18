<?php

//This class is for the new matches mailers. It is used in the matches generation logic and also on landing on the search page through "See New Matches" link in the mailer

class NewMatchesTrendsMailer extends TrendsPartnerProfile {

        private $pid;
        private $loggedInProfileObj;
        public $incomeStrings = array();
        public $forwardCriteria = array("GENDER","LAGE","HAGE","LHEIGHT","HHEIGHT","INCOME","LINCOME","HINCOME","LINCOME_DOL","HINCOME_DOL","INDIA_NRI");
        public $trendsSearchReverseForwardCriteria = array("MSTATUS"=>"PARTNER_MSTATUS","RELIGION"=>"PARTNER_RELIGION","MTONGUE"=>"PARTNER_MTONGUE","CASTE"=>"PARTNER_CASTE","EDU_LEVEL_NEW"=>"PARTNER_ELEVEL_NEW","OCCUPATION"=>"PARTNER_OCC","CITY_RES"=>"PARTNER_CITYRES","MSTATUS_IGNORE"=>"PARTNER_MSTATUS_IGNORE","MANGLIK_IGNORE"=>"PARTNER_MANGLIK_IGNORE","COUNTRY_RES"=>"PARTNER_COUNTRYRES","MANGLIK"=>"PARTNER_MANGLIK");
        
        public function __construct($loggedInProfileObj) {
                if ($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
                        $this->pid = $loggedInProfileObj->getPROFILEID();
                $this->loggedInProfileObj = $loggedInProfileObj;
                parent::__construct();
        }

        

        public function setSearchCriteria($paramArr) {
                $this->setPartnerDetails($this->pid);
                $income = str_replace("'", "", $this->getPARTNER_INCOME());
                $this->incomeStrings["INDIA_NRI"] = "";
                if($this->getPARTNER_COUNTRY_RES_IGNORE()){
                        $this->incomeStrings["INDIA_NRI"] = '2';
                }
                if ($income || $income == '0') {   //Get income values from TRENDS_SORTBY column starts
                        $imObj = new IncomeMapping;
                        $incomeArr = explode(",", $income);
                        foreach ($incomeArr as $k => $v) {
                                $temp = $imObj->getIncomeFromTrendsSortBy($v);
                                if ($temp && is_array($temp)) {
                                        foreach ($temp as $kk => $vv) {
                                                $incomeFinalArr[] = $vv;
                                        }
                                        unset($temp);
                                }
                        }
                        unset($incomeArr);
                        unset($imObj);
                }      //Get income values from TRENDS_SORTBY column ends

                if ($incomeFinalArr && is_array($incomeFinalArr)) { //Get LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL values from income
                        $lincome = 100;
                        $hincome = -1;
                        $lincome_dol = 100;
                        $hincome_dol = -1;
                        $incomeStr = "";
                        foreach ($incomeFinalArr as $k => $v) {
                                if ($v["SORTBY"] != 0) {
                                        if ($v["TYPE"] == "RUPEES") {
                                                if ($lincome > $v["MIN_VALUE"])
                                                        $lincome = $v["MIN_VALUE"];
                                                if ($hincome < $v["MAX_VALUE"])
                                                        $hincome = $v["MAX_VALUE"];
                                        }
                                        elseif ($v["TYPE"] == "DOLLARS") {
                                                if ($lincome_dol > $v["MIN_VALUE"])
                                                        $lincome_dol = $v["MIN_VALUE"];
                                                if ($hincome_dol < $v["MAX_VALUE"])
                                                        $hincome_dol = $v["MAX_VALUE"];
                                        }
                                }
                                $incomeStr = $incomeStr . $v["VALUE"] . ",";
                        }
                        $incomeStr = rtrim($incomeStr, ",");
                        if ($lincome == 100)
                                $lincome = "";
                        if ($hincome == -1)
                                $hincome = "";
                        if ($lincome_dol == 100)
                                $lincome_dol = "";
                        if ($hincome_dol == -1)
                                $hincome_dol = "";
                }      //Get LINCOME,HINCOME,LINCOME_DOL,HINCOME_DOL values from income ends
                unset($incomeFinalArr);
                
                $this->incomeStrings["INCOME"] = $incomeStr;
                $this->incomeStrings["LINCOME"] = $lincome;
                $this->incomeStrings["HINCOME"] = $hincome;
                $this->incomeStrings["LINCOME_DOL"] = $lincome_dol;
                $this->incomeStrings["HINCOME_DOL"] = $hincome_dol;
        }

}

?>
