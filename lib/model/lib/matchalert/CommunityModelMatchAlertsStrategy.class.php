<?php

/* This class is used to handle the Caste Relaxation logic in matchalerts */

class CommunityModelMatchAlertsStrategy extends MatchAlertsStrategy {

        private $postParams = array();
        private $profileId;
        private $loggedInProfileObj;
        public $timeout = 5000;
        private $partnerFieldsRequired = array("LINCOME","HINCOME","LAGE","HAGE","LHEIGHT","HHEIGHT","MANGLIK","BTYPE","CASTE","CITYRES","COUNTRYRES","ELEVEL_NEW","MSTATUS","MTONGUE","OCC","RELIGION");
        private $partnerFieldsRequiredwithPrefix = array("MANGLIK","BTYPE","CASTE","CITYRES","COUNTRYRES","ELEVEL_NEW","MSTATUS","MTONGUE","OCC","RELIGION");
        private $partnerKeyPrefix = "PARTNER_";

        public function __construct($loggedInProfileObj, $limit, $logicLevel) {
                $this->profileId = $loggedInProfileObj->getPROFILEID();
                $this->loggedInProfileObj = $loggedInProfileObj;
                $this->logicLevel = $logicLevel;
                $this->logProfile = 1;
                $this->limit = $limit;
        }

        /*
          This function is used to get all the profiles from analytics server
          @param - profileid
          @return - array of profiles
         */

        public function getMatches($matchesSetting = '') {
                $profilesArray = array();
                if ($this->profileId) {
                        $this->setCommunityPostParams();
                        $profilesArray = $this->sendCommunityPostRequest();
                        if($profilesArray != false){
                                $profilesArray = array_unique(array_filter(explode(",",$profilesArray)));
                                if (is_array($profilesArray) && count($profilesArray) > 1) {
                                        $profilesArray = array_slice($profilesArray, 0, $this->limit);
                                        $this->logRecords($this->profileId, $profilesArray, $this->logicLevel, $this->limit, 0, $matchesSetting);
                                }
                        }
                }
                return array("CNT" => count($profilesArray), "profiles" => $profilesArray);
        }

        private function setCommunityPostParams() {
                $this->postParams["removeProfiles"] = "";
                $searchUtilObj = new SearchUtility();
                $remPro = $searchUtilObj->getIgnoredProfiles($this->profileId, "spaceSeperator", 1, 1, 1, 1);// profileid ,separated by , noAwaitingContacts, removeMatchAlerts, tempContacts, getFromCache
                if($remPro != "" && $remPro !=0 && $remPro != " "){
                        $this->postParams["removeProfiles"] = trim(implode(",", explode(" ",$remPro)),',');
                }

                $suffix = "_" . $this->loggedInProfileObj->getGENDER();
                $this->postParams['pg_data']["edu_level" . $suffix] = (integer) $this->loggedInProfileObj->getEDU_LEVEL_NEW();
                $this->postParams['pg_data']["havephoto" . $suffix] = $this->loggedInProfileObj->getHAVEPHOTO();
                $this->postParams['pg_data']["gender" . $suffix] = $this->loggedInProfileObj->getGENDER();
                $this->postParams['pg_data']["age" . $suffix] = (integer) $this->loggedInProfileObj->getAGE();
                $this->postParams['pg_data']["btype" . $suffix] = (integer) $this->loggedInProfileObj->getBTYPE();
                $this->postParams['pg_data']["height" . $suffix] = (integer) $this->loggedInProfileObj->getHEIGHT();
                $this->postParams['pg_data']["city_res" . $suffix] = $this->loggedInProfileObj->getCITY_RES();
                $this->postParams['pg_data']["community" . $suffix] = (integer) $this->loggedInProfileObj->getMTONGUE();
                $this->postParams['pg_data']["mtongue" . $suffix] = (integer) $this->loggedInProfileObj->getMTONGUE();
                $this->postParams['pg_data']["income" . $suffix] = (integer) $this->loggedInProfileObj->getINCOME();
                $this->postParams['pg_data']["profileid" . $suffix] = (string) $this->profileId;
                $this->postParams['pg_data']["caste" . $suffix] = (integer) $this->loggedInProfileObj->getCASTE();
                $this->postParams['pg_data']["posted_by" . $suffix] = (integer) $this->loggedInProfileObj->getRELATION();
                $this->postParams['pg_data']["occupation" . $suffix] = (integer) $this->loggedInProfileObj->getOCCUPATION();
                $this->postParams['pg_data']["country_res" . $suffix] = (integer) $this->loggedInProfileObj->getCOUNTRY_RES();
                $this->postParams['pg_data']["mstatus" . $suffix] = $this->loggedInProfileObj->getMSTATUS();
                $this->postParams['pg_data']["manglik" . $suffix] = $this->loggedInProfileObj->getMANGLIK() == "" ? "N" : $this->loggedInProfileObj->getMANGLIK();
                //$this->postParams['pg_data']["religion" . $suffix] = (integer) $this->loggedInProfileObj->getRELIGION();
                $dppData = $this->getDppData();
                foreach($this->partnerFieldsRequired as $fieldRequired){
                        $ky = $fieldRequired;
                        if(in_array($fieldRequired,$this->partnerFieldsRequiredwithPrefix)){
                              $ky = $this->partnerKeyPrefix.$ky; 
                        }
                        $this->postParams['dpp_pg'][$ky] = $dppData[0][$fieldRequired];
                        if($ky == "PARTNER_COUNTRYRES"){
                                $this->postParams['dpp_pg'][$ky] = $dppData[0]["COUNTRY_RES"];
                        }elseif($ky == "PARTNER_CITYRES"){
                                $this->postParams['dpp_pg'][$ky] = $dppData[0]["CITY_RES"];
                        }
                        
                }
                $filterObj = new ProfileFilter();
                $flData = $filterObj->fetchEntry($this->profileId);
                unset($flData["FILTERID"]);unset($flData["PROFILEID"]);unset($flData["COUNT"]);unset($flData["HARDSOFT"]);
                if($flData){
                        foreach($flData as $filterField=>$value){
                                $this->postParams['dpp_pg_hard'][$filterField] = $value;
                        }
                }else{
                        $this->postParams['dpp_pg_hard'] = "";
                }
        }

        private function sendCommunityPostRequest() {
                $postParams = json_encode($this->postParams);
                $ch = curl_init(JsConstants::$matchAlertsCommunityModelApi);
                $header[0] = "Accept: application/json";
                curl_setopt($ch, CURLOPT_HEADER, $header);
                curl_setopt($ch, CURLOPT_USERAGENT, "JsInternal");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $this->timeout);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, $this->timeout * 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec($ch);
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $headerStr = substr($output, 0, $header_size);
                $output = substr($output, $header_size);
                return $output;
        }

        private function getDppData() {
                $dbName = JsDbSharding::getShardNo($this->profileId);
                $JPARTNERobj = new newjs_JPARTNER($dbName);
                $fields = SearchConfig::$dppSearchParamters . ",MAPPED_TO_DPP,PARTNER_BTYPE AS BTYPE,PARTNER_OCC as OCC,PARTNER_ELEVEL_NEW as ELEVEL_NEW";
                $jpartnerData = $JPARTNERobj->get(array("PROFILEID" => $this->profileId), $fields);
                unset($JPARTNERobj);
                return $jpartnerData;
        }

}
