<?php

/* This class is used to handle the Caste Relaxation logic in matchalerts */

class CommunityModelMatchAlertsStrategy extends MatchAlertsStrategy {

        private $postParams = array();
        private $profileId;
        private $loggedInProfileObj;
        public static $communityModelApi = "http://10.10.18.87:2233/commModelRecommendations_live";

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
                                $profilesArray = explode(",",$profilesArray);
                                if (is_array($profilesArray) && count($profilesArray) > 1) {
                                        $profilesArray = array_slice($profilesArray, 0, $this->limit);
                                        $this->logRecords($this->profileId, $profilesArray, $this->logicLevel, $this->limit, 0, $matchesSetting);
                                }
                        }
                }
                return array("CNT" => count($profilesArray), "profiles" => $profilesArray);
        }

        private function setCommunityPostParams() {
                $searchUtilObj = new SearchUtility();
                $this->postParams["removeProfiles"] = implode(",", explode(" ", $searchUtilObj->getIgnoredProfiles($this->profileId, "spaceSeperator", 1, 1, 1, 1))); // profileid ,separated by , noAwaitingContacts, removeMatchAlerts, tempContacts, getFromCache
                $suffix = "_" . $this->loggedInProfileObj->getGENDER();
                $this->postParams['pg_data']["edu_level" . $suffix] = (integer) $this->loggedInProfileObj->getEDU_LEVEL_NEW();
                $this->postParams['pg_data']["havephoto" . $suffix] = str_replace("NA", "N", $this->loggedInProfileObj->getHAVEPHOTO());
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
                $this->postParams['pg_data']["ref_countries" . $suffix] = (integer) $this->loggedInProfileObj->getCOUNTRY_RES();
                $this->postParams['pg_data']["ref_mstatus" . $suffix] = $this->loggedInProfileObj->getMSTATUS();
                $this->postParams['pg_data']["ref_manglik" . $suffix] = $this->loggedInProfileObj->getMANGLIK() == "" ? "N" : $this->loggedInProfileObj->getMANGLIK();
                $this->postParams['pg_data']["religion" . $suffix] = (integer) $this->loggedInProfileObj->getRELIGION();
                $this->postParams['pg_data']["commzone" . $suffix] = "North";
                $this->postParams['pg_data']["cityzone" . $suffix] = "North";
                $dppData = $this->getDppData();
                $this->postParams['dpp_pg']["manglik"] = $dppData[0]["MANGLIK"]== "" ? "N" : $dppData[0]["MANGLIK"];
        }

        private function sendCommunityPostRequest() {
                $timeout = 5000;
                $urlToHit = self::$communityModelApi;
                $postParams = json_encode($this->postParams);
                $ch = curl_init($urlToHit);
                $header[0] = "Accept: application/json";
                curl_setopt($ch, CURLOPT_HEADER, $header);
                curl_setopt($ch, CURLOPT_USERAGENT, "JsInternal");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout * 10);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $output = curl_exec($ch);
                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $headerStr = substr($output, 0, $header_size);
                $output = substr($output, $header_size);
                return $output;
        }

        private function getDppData() {
                $memObject = JsMemcache::getInstance();
                // Get jpartner data
                $jpartnerData = $memObject->get('SEARCH_JPARTNER_' . $this->profileId);
                if (empty($jpartnerData)) {
                        $dbName = JsDbSharding::getShardNo($this->profileId);
                        $JPARTNERobj = new newjs_JPARTNER($dbName);
                        $fields = SearchConfig::$dppSearchParamters . ",MAPPED_TO_DPP";
                        $jpartnerData = $JPARTNERobj->get(array("PROFILEID" => $this->profileId), $fields);
                } else {
                        $jpartnerData = unserialize($jpartnerData);
                }
                unset($memObject);
                unset($JPARTNERobj);
                return $jpartnerData;
        }

}
